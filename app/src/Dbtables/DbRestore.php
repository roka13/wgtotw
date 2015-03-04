<?php
namespace Roka\Dbtables;

class DbRestore extends \Anax\MVC\CDatabaseModel
{ 


public function RestoreDb(){

$this->CreateUserTbl();
$this->CreateCommentsTbl();
$this->CreateQuestionsTbl();
$this->CreateTagsTbl();
$this->CreateAnswerTbl();
$this->CreateTags2questionsTbl();

$this->CreateAnswers2QuestionTbl();
$this->CreateComments2QuestionTbl();
$this->CreateComments2AnswerTbl();
$this->PopulateUsers();
$this->PopulateAnswer();
$this->PopulateTags();
$this->PopulateQuestions();
//$sql +=$this->CreateQuestionTagsView();
//echo $sql;
//$this->db->execute($sql);
}

public function CreateUserTbl(){
	$this->db->dropTableIfExists('Users')->execute();

	$this->db->createTable(
	'Users',
	[
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'nickname' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
			'firstname' =>['varchar(24)'],
            'lastname' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'joined' => ['datetime'],
            'updated' => ['datetime'],
            'softdeleted' => ['datetime'],
            'active' => ['datetime'],
            'status'=>['varchar(20)'],
			'activity' =>['integer'],
			'description' =>['varchar(240)'],
			
        ]
        )->execute();
}


 public function CreateCommentsTbl(){
	  $this->db->dropTableIfExists('Comments')->execute();

	$this->db->createTable(
        'Comments',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'commentsTxt'  => ['varchar(200)', 'not null'],
            'commentVotes'     => ['integer'],
       		'CommentDate'=>['datetime'],
			'idUser'	   =>['integer'],
			
        ]
    )->execute();
}

public function CreateQuestionsTbl(){
	$this->db->dropTableIfExists('Questions')->execute();

	$this->db->createTable(
	'Questions',
	[
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'questionTxt' => ['varchar(240)','not null'],
            'questionDate' => ['datetime'],
			'questionVotes' =>['integer'],
            'totalVotes' => ['integer'],
            'idUser' => ['integer'],
           
        ]
        )->execute();

}

public function CreateTagsTbl(){
$this->db->dropTableIfExists('Tags')->execute();

	$this->db->createTable(
	'Tags',
	[
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'tagName' => ['varchar(24)', 'unique', 'not null'],
          	'tagDescription' =>['varchar(240)'],
        ]
        )->execute();

}

public function CreateTags2questionsTbl(){
$this->db->dropTableIfExists('Tags2question')->execute();

	$this->db->createTable(
	'Tags2question',
	[
            'questionId' => ['integer'],
            'tagId' => ['integer'],
                 ]
        )->execute();
}



public function CreateAnswerTbl(){
	$this->db->dropTableIfExists('Answers')->execute();

	$this->db->createTable(
	'Answers',
	[
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'answerTxt' => ['varchar(240)','not null'],
            'answerDate' => ['datetime'],
			'answerVotes' =>['integer'],
            'accepted' => ['boolean'],
            'idUser' => ['integer'],
           
        ]
        )->execute();
}

public function CreateAnswers2questionTbl(){
	$html="	DROP TABLE IF EXISTS Answers2question";
$this->db->execute($html);	

$html=<<<EOD
CREATE TABLE "Answers2question" (
    "answerId" INTEGER NOT NULL,
    "questionId" INTEGER NOT NULL,
    CONSTRAINT "Answers2question" PRIMARY KEY ("answerId", "questionId"),
    FOREIGN KEY ("questionId") REFERENCES "Questions" ("id") ,
    FOREIGN KEY ("answerId") REFERENCES "Answers" ("id") 
);
EOD;
$this->db->execute($html);	
}

public function CreateComments2questionTbl(){
	$html="	DROP TABLE IF EXISTS Comments2Question";
$this->db->execute($html);	

$html=<<<EOD
CREATE TABLE "Comments2Question" (
    "commentId" INTEGER NOT NULL,
    "questionId" INTEGER NOT NULL,
    CONSTRAINT "Comments2Question" PRIMARY KEY ("commentId", "questionId"),
    FOREIGN KEY ("commentId") REFERENCES "Comments" ("id") ,
    FOREIGN KEY ("questionId") REFERENCES "Questions" ("id") 
);
EOD;
$this->db->execute($html);	
}

public function CreateComments2answerTbl(){
	$html="	DROP TABLE IF EXISTS Comments2Answer";
$this->db->execute($html);	

$html=<<<EOD
CREATE TABLE "Comments2Answer" (
    "commentId" INTEGER NOT NULL,
    "answerId" INTEGER NOT NULL,
    CONSTRAINT "Comments2Answer" PRIMARY KEY ("commentId", "answerId"),
    FOREIGN KEY ("commentId") REFERENCES "Comments" ("Iid") ,
    FOREIGN KEY ("answerId") REFERENCES "Answers" ("id") 
);

EOD;
$this->db->execute($html);	
}

/**
* Lista vilka taggar som tillhör utvald fråga

*/
public function  CreateQuestion2TagsView(){
	$html="	DROP VIEW IF EXISTS QuestionTagsView";
$this->db->execute($html);	

$html=<<<EOD
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
$this->db->execute($html);	
}

/**
* Lista alla Frågor med samtliga uppgifter om frågan samt frågeställarens idnr
* och det svar som finns som Answerstxt
*/
public function  CreateQuestionAnswersView(){
	$html="	DROP VIEW IF EXISTS QuestionAnswersView";
$this->db->execute($html);	

$html=<<<EOD
CREATE VIEW QuestionAnswersView AS 
SELECT
Q.*,
GROUP_CONCAT(A.AnswerTxt) AS Answerstxt
FROM Questions AS Q
LEFT OUTER JOIN Answers2question AS A2Q
ON Q.Id = A2Q.questionId
LEFT OUTER JOIN Answers AS A
ON A2Q.answerId = A.Id
GROUP BY Q.Id
;
EOD;
$this->db->execute($html);	
}

public function  CreateQuestionCommentsView(){
	$html="	DROP VIEW IF EXISTS QuestionCommentsView";
$this->db->execute($html);	

$html=<<<EOD
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
$this->db->execute($html);	
}

public function  CreateAnswerCommentsView(){
	$html="	DROP TVIEW IF EXISTS AnswerCommentsView";
$this->db->execute($html);	

$html=<<<EOD
CREATE VIEW AnswerCommentsView AS
SELECT
A.*, C.*,
GROUP_CONCAT(C.commentsTxt) AS Comments
FROM Answers AS A
LEFT OUTER JOIN Comments2Answer AS C2A
ON A.Id = C2A.answerId
LEFT OUTER JOIN Comments AS C
ON C2A.commentId = C.Id
GROUP BY A.Id
;
EOD;
$this->db->execute($html);	
}

public function PopulateUsers(){
//Lägg till användare
	 $this->db->insert(
        'Users',
        ['nickname','email','firstname','lastName','description','password','joined','activity','status','active']
        );
                   

	$now = date('Y-m-d');
	
	$this->db->execute([
		'RGK',
        'karlssonrg@gmail.com',
		'Göran',
        'Karlsson',
		'Administratör och moderator för denna webbplats',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
		'activity' => 1,
		'status' => "aktiv",
		'active' => $now
	]);

	$this->db->execute([
		'Default',
        'default@fake.se',
		'Lasse',
        'Liten',
		'Användare utan speciella rättigheter',
        password_hash('default', PASSWORD_DEFAULT),
        $now,   
		'activity' => 1,
		'status' => "aktiv",
		'active' => $now
	]);
} // end of CreateUserTbl

public function PopulateAnswer() {
	
//Lägg till användare
	 $this->db->insert(
        'Answers',
        ['answerTxt','answerDate','idUser','accepted']
        );
                   

	$now = date('Y-m-d');
	
	$this->db->execute([
		'Därför att det är mycket bekvämare',
        $now,
		'idUser' => 1,
		false
         
	]);

	//Lägg till Taggar
	 $this->db->insert(
        'Answers2question',
        ['answerId','questionId']
        );
                   

	$this->db->execute([
		'answerId' => 1,
        'questionId'=>1,
	]);
} // end of PopulateAnswers

public function PopulateTags(){
	//Lägg till Taggar
	 $this->db->insert(
        'Tags',
        ['tagName','tagDescription']
        );
                   

	$this->db->execute([
		'Luft/Luft-värmepump',
        'Värmepump med luft som värmekälla och luft för distribution'
	]);

	$this->db->execute([
		'Luft/Vatten-värmepump',
        'Värmepump med luft som värmekälla och vatten för distribution'
	]);
		
		$this->db->execute([
		'Vätska/Vatten-värmepump',
        'Värmepump med Vätska som värmekälla och vatten för distribution'
	]);
	
		$this->db->execute([
		'Berg-värmepump',
        'Värmepump med Berg som värmekälla och vatten för distribution'
	]);
	
		$this->db->execute([
		'Mark-värmepump',
        'Värmepump med markslinga som värmekälla och vatten för distribution'
	]);
	
		$this->db->execute([
		'Flytande Kondensering',
        'Värmepump med flytande kondensering styrd av behovet för stunden'
	]);
	
		$this->db->execute([
		'Övriga Frågor',
        'Alla frågor som inte kan hamna i de andra kategorierna'
	]);
	
	
}//end of PopulateTags

public function PopulateQuestions(){	
//Lägg till Fråga
	 $this->db->insert(
        'Questions',
        ['QuestionTxt','questionDate','idUser']
        );
                   

	$now = date('Y-m-d');
	
	$this->db->execute([
		'Varför skall man välja en viss sorts värmepump när man har gratis ved ?',
        $now,
		'idUser' => 1
	]);
	 $this->db->insert(
        'Tags2question',
        ['QuestionId','tagId']
        );

	$this->db->execute([
	       'QuestionId' => 1,
		   'tagId'      => 7,
           
	]);
	
}//end of PopulateQuestions



}//End of class











