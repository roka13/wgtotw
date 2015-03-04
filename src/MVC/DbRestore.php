<?php
namespace Anax\MVC;

class DbRestore
{

public function RestoreDb(){

$sql=$this->CreateUserTbl();
$sql +=$this->CreateCommentsTbl();
$sql +=$this->CreateQuestionsTbl();
$sql +=$this->CreateTagsTbl();
$sql +=$this->CreateTags2QuestionsTbl();
$sql +=$this->CreateAnswersTbl();
$sql +=$this->CreateAnswers2QuestionTbl();
$sql +=$this->CreateComments2QuestionTbl();
$sql +=$this->CreateComments2AnswerTbl();
$sql +=$this->CreateQuestionTagsView();

$this->db->execute($sql);
}


public function CreateUserTbl(){

$html=<<<EOD

DROP TABLE IF EXISTS "Users";
CREATE TABLE "Users" (
    "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "acronym" VARCHAR(20) NOT NULL,
    "firstName" VARCHAR(20) NOT NULL,
    "lastName" VARCHAR(20) NOT NULL,
    "eMail" VARCHAR(20) NOT NULL,
    "description" VARCHAR(20) NOT NULL,
    "passWord" VARCHAR(20) NOT NULL,
    "avatar" VARCHAR(20) NOT NULL,
    "joined" DATETIME NOT NULL,
    "activity" INTEGER
);
EOD;
$this->db->execute($html);

//return $html;
}

 public function CreateCommentsTbl(){
 $html=<<<EOD
 DROP TABLE IF EXISTS "Comments";
CREATE TABLE "Comments" (
    "Id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "commentTxt" VARCHAR(20) NOT NULL,
    "commentDate" DATETIME NOT NULL,
    "commentVotes" INTEGER,
    "idUser" INTEGER,
    FOREIGN KEY ("idUser") REFERENCES "Users" ("id") 
);


EOD;
return $html;
}

public function CreateQuestionsTbl(){

$html=<<<EOD
DROP TABLE IF EXISTS "Questions";
CREATE TABLE "Questions" (
    "Id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "questionTxt" VARCHAR(20) NOT NULL,
    "questionDate" DATETIME NOT NULL,
    "questionVotes" INTEGER,
    "totalVotes" INTEGER NOT NULL,
    "idUser" INTEGER NOT NULL,
    FOREIGN KEY ("idUser") REFERENCES "Users" ("id") 
);

EOD;
return $html;
}

public function CreateTagsTbl(){

$html=<<<EOD
DROP TABLE IF EXISTS "Tags";
CREATE TABLE "Tags" (
    "Id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "tagName" VARCHAR(20) NOT NULL,
    "tagDescription" VARCHAR(20)
);
EOD;
return $html;
}

public function CreateTags2QuestionsTbl(){

$html=<<<EOD
DROP TABLE IF EXISTS Tags2Question;
CREATE TABLE "Tags2Question" (
    "questionId" INTEGER NOT NULL,
    "tagId" INTEGER NOT NULL,
    CONSTRAINT "Tags2Question" PRIMARY KEY ("questionId", "tagId"),
    FOREIGN KEY ("questionId") REFERENCES "Questions" ("Id") ,
    FOREIGN KEY ("tagId") REFERENCES "Tags" ("Id") 
);


EOD;
return $html;
}

public function CreateAnswersTbl(){

$html=<<<EOD
DROP TABLE IF EXISTS "Answers";
CREATE TABLE "Answers" (
    "Id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "answerTxt" VARCHAR(20) NOT NULL,
    "answerDate" DATETIME NOT NULL,
    "accepted" INTEGER,
    "answerVotes" INTEGER,
    "idUser" INTEGER NOT NULL,
    FOREIGN KEY ("idUser") REFERENCES "Users" ("id") 
);
EOD;
return $html;
}

public function CreateAnswers2QuestionTbl(){

$html=<<<EOD
DROP TABLE IF EXISTS "Answers2Question";
CREATE TABLE "Answers2Question" (
    "answerId" INTEGER NOT NULL,
    "questionId" INTEGER NOT NULL,
    CONSTRAINT "Answers2Question" PRIMARY KEY ("answerId", "questionId"),
    FOREIGN KEY ("questionId") REFERENCES "Questions" ("Id") ,
    FOREIGN KEY ("answerId") REFERENCES "Answers" ("Id") 
);
EOD;
return $html;
}

public function CreateComments2QuestionTbl(){

$html=<<<EOD
DROP TABLE IF EXISTS "Comments2Question";
CREATE TABLE "Comments2Question" (
    "commentId" INTEGER NOT NULL,
    "questionId" INTEGER NOT NULL,
    CONSTRAINT "Comments2Question" PRIMARY KEY ("commentId", "questionId"),
    FOREIGN KEY ("commentId") REFERENCES "Comments" ("Id") ,
    FOREIGN KEY ("questionId") REFERENCES "Questions" ("Id") 
);
EOD;
return $html;
}

public function CreateComments2AnswerTbl(){

$html=<<<EOD
DROP TABLE IF EXISTS "Comments2Answer";
CREATE TABLE "Comments2Answer" (
    "commentId" INTEGER NOT NULL,
    "answerId" INTEGER NOT NULL,
    CONSTRAINT "Comments2Answer" PRIMARY KEY ("commentId", "answerId"),
    FOREIGN KEY ("commentId") REFERENCES "Comments" ("Id") ,
    FOREIGN KEY ("answerId") REFERENCES "Answers" ("Id") 
);

EOD;
return $html;
}

/**
* Lista vilka taggar som tillhör utvald fråga

*/
public function  CreateQuestionTagsView(){

$html=<<<EOD
DROP VIEW IF EXISTS QuestionTagsView;
CREATE VIEW QuestionTagsView AS 
SELECT
Q.*,
GROUP_CONCAT(T.TagName) AS Tags
FROM Questions AS Q
LEFT OUTER JOIN Tags2Question AS T2Q
ON Q.Id = T2Q.questionId
LEFT OUTER JOIN Tags AS T
ON T2Q.tagId = T.Id
GROUP BY Q.Id
;
EOD;
return $html;
}

/**
* Lista alla Frågor med samtliga uppgifter om frågan samt frågeställarens idnr
* och det svar som finns som Answerstxt
*/
public function  CreateQuestionAnswersView(){

$html=<<<EOD
DROP VIEW IF EXISTS QuestionAnswersView;
CREATE VIEW QuestionAnswersView AS 
SELECT
Q.*,
GROUP_CONCAT(A.AnswerTxt) AS Answerstxt
FROM Questions AS Q
LEFT OUTER JOIN Answers2Question AS A2Q
ON Q.Id = A2Q.questionId
LEFT OUTER JOIN Answers AS A
ON A2Q.answerId = A.Id
GROUP BY Q.Id
;
EOD;
return $html;
}

public function  CreateQuestionCommentsView(){

$html=<<<EOD
DROP VIEW IF EXISTS QuestionCommentsView;
CREATE VIEW QuestionCommentsView AS 
SELECT
Q.*,
GROUP_CONCAT(C.commentTxt) AS Comments
FROM Questions AS Q
LEFT OUTER JOIN Comments2Question AS C2Q
ON Q.Id = C2Q.questionId
LEFT OUTER JOIN Comments AS C
ON C2Q.commentId = C.Id
GROUP BY Q.Id
;
EOD;
return $html;
}

public function  CreateAnswerCommentsView(){

$html=<<<EOD
DROP VIEW IF EXISTS AnswerCommentsView;
CREATE VIEW AnswerCommentsView AS
SELECT
A.*,
GROUP_CONCAT(C.commentTxt) AS Comments
FROM Answers AS A
LEFT OUTER JOIN Comments2Answer AS C2A
ON A.Id = C2A.answerId
LEFT OUTER JOIN Comments AS C
ON C2A.commentId = C.Id
GROUP BY A.Id
;
EOD;
return $html;
}



public function InsertTags(){

$sql=<<<EOD
INSERT INTO Tags ('tagname', 'tagDescription')
VALUES 
( 'Nytt', 'De senaste inläggen'),
('Gammalt', 'Ej aktuellt')
;
EOD;
return $sql;
}

public function InsertUsers(){
$img='<img src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($eMail)));?>.jpg?s=60"  alt="Bild" />';
	
$sql="
INSERT INTO Users ('acronym','eMail','firstName','lastName','description','password','avatar','joined')
VALUES ('admin',
        'admin@roka.se',
		'Kalle',
        'Administrator',
		'knäpp kille',
        password_hash('admin', PASSWORD_DEFAULT),
		$img,
        $now )
		";
		
}		
}











