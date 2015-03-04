<?php
namespace Roka\Dbtables;

class WGTQuerys extends \Anax\MVC\CDatabaseModel
{ 

// Get Answers to a specific Question
 public function GetAnswers2question($questionId){
	$sql="
		SELECT Q.idUser, Q.id AS Qid,  U.nickname,  Q.questionTxt, A.answerTxt, A.id AS Aid FROM Users as U ,Answers as  A,  Questions as Q INNER JOIN Answers2question AS AQ
		ON AQ.answerId = A.id and AQ.questionId=Q.id
		WHERE U.id = A.idUser AND Q.id =$questionId";
		$res = $this->db->executeFetchAll($sql);
return $res;
}

// Lista svar som tillhör en viss användare
public function GetAnswers2User($id){
	$sql="SELECT * FROM Answers Where idUser = $id";
	$res = $this->db->executeFetchAll($sql);
return $res;
}





// Get Question to a specific Answer
public function GetQuestion2Answer($answerId){
	$sql=
		"SELECT A.idUser, A.id AS Aid,  U.nickname, Q.questionTxt, Q.id AS Qid FROM Users as U ,Answers as  A,  Questions as Q INNER JOIN Answers2question AS AQ
		ON AQ.answerId = A.id and AQ.questionId=Q.id
		WHERE U.id = Q.idUser AND A.id =$answerId";
	$res = $this->db->executeFetchAll($sql);
	return $res;
}

// Get a list of Users in order how active they have been
public function GetActiveUsers(){
	$sql ="
		SELECT  *  FROM Users ORDER BY activity DESC LIMIT 8 ";
	$res = $this->db->executeFetchAll($sql);
	return $res;
}

// Get a list of most populare tags	
public function GetPopTags(){
	$sql="
		SELECT tagId, COUNT(*) , TAGS.*
		FROM Tags, Tags2question WHERE TagId = TAGS.id
		GROUP BY tagId ORDER BY COUNT(*) DESC LIMIT 4 " ;
	$res = $this->db->executeFetchAll($sql);
	return $res;	
}	

 // Get a list of latest Questions in Date Order 
 public function GetLatestQuestion(){
	$sql="
		SELECT Questions.id ,questionTxt, questionDate, idUser, Users.nickname,Users.email FROM Questions, Users 
		WHERE Questions.idUser=Users.id ORDER by questionDate DESC LIMIT 8";
	$res = $this->db->executeFetchAll($sql);
	return $res;
}

// Get All Questions with a specific Tag
 public function GetQuestion2Tags($tagId){
	$sql="
		SELECT questionTxt , id FROM Questions as Q, Tags2question as Tq 
		WHERE Q.id = Tq.questionId AND  Tq.tagId = $tagId ";
	$res = $this->db->executeFetchAll($sql);
	return $res;
}

// Get tags for a specific Question 
 public function GetTags2Question($questionId){
	$sql="
		SELECT tagName ,tagId FROM Tags as T, Tags2question as Tq 
		WHERE T.id = Tq.tagId AND  Tq.questionId = $questionId ";
	$res = $this->db->executeFetchAll($sql);
	return $res;
}


 // Get Questions to Selected User
 public function GetQuestion2User($id){
	$sql="
		SELECT questionTxt , id FROM Questions 
		WHERE idUser = $id ";
	$res = $this->db->executeFetchAll($sql);
	return $res;
}

 // Get Commnets to Selected User
 public function GetComments2User($id){
	$sql="
		SELECT commentsTxt , id  FROM Comments 
		WHERE idUser = $id ";
	$res = $this->db->executeFetchAll($sql);
	return $res;
}

 

// Get comments belonging to selected Question 
 public function GetComments2question($questionId){
	$sql="
		SELECT C.idUser, C.id,  U.nickname,  C.commentsTxt, Q.questionTxt  FROM Users as U ,Comments as  C,  Questions as Q INNER JOIN Comments2Question AS CQ
		ON CQ.commentId = C.id and CQ.questionId=Q.id
		WHERE U.id = C.idUser AND Q.id =$questionId ";
	$res = $this->db->executeFetchAll($sql);
	return $res;
}

//GET Question to Comments from a specific Commentators useriD
public function GetQuestion2Comment($userId){
	$sql="
SELECT U.nickname, C.*, CQ.*, Q.* FROM  Comments2Question AS CQ, Users AS U, Questions AS Q  INNER JOIN Comments AS C
ON CQ.commentId=C.id AND CQ.questionId=Q.id AND U.id=Q.idUser WHERE C.idUser =$userId";
	$res = $this->db->executeFetchAll($sql);
	return $res;
}

//GET Answer to Comments from a specific Commentators useriD
public function GetAnswer2Comment($userId){
	$sql="
SELECT U.nickname, C.*, CA.*, A.* FROM  Comments2Answer AS CA, Users AS U, Answers AS A  INNER JOIN Comments AS C
ON CA.commentId=C.id AND CA.answerId=A.id AND U.id=A.idUser WHERE C.idUser =$userId";
	$res = $this->db->executeFetchAll($sql);
	return $res;
}

// Get Comment belonging to selected answer
 public function GetComments2Answer($answerId){
$sql="
SELECT C.idUser, C.id AS Cid,  U.nickname,  C.commentsTxt, A.answerTxt  FROM Users as U ,Comments as  C,  Answers as A INNER JOIN Comments2answer AS CA
ON CA.commentId = Cid and CA.answerId=A.id
WHERE U.id = C.idUser AND A.id = $answerId ";

$res = $this->db->executeFetchAll($sql);
return $res;
}


} // end of class