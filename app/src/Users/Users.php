<?php
namespace Anax\Users;
 /**
 * Model for Users.
 *
 */
class Users extends \Anax\MVC\CDatabaseModel
{


public function checkLogin($mail,$pwd){
	$this->db->select()
    ->from($this->getSource())
    ->where("email= ?");
    $this->db->execute([$mail]);
 
	$user=  $this->db->fetchInto($this);
	if($user){
		$hashPwd = $user->password;
		if(password_verify($pwd, $hashPwd)){ 
			$properties=$user->getProperties();
			//$_SESSION['id'] = $properties['id'];
			$_SESSION['id']= $user->id;
			$_SESSION['user'] = $user->nickname;
		 //   $user->save();
		
			return true;
		}
	}

	return false;
 } //end of checkLogin()

public function getNickName($id){
	$this->db->select()
    ->from($this->getSource())
    ->where("id= ?");
    $this->db->execute([$id]);
 $user=  $this->db->fetchInto($this);
	return $user->nickname;
}

public function addPoints($user){
	$sql="
SELECT activity  FROM Users WHERE id= $user";
$res = $this->db->executeFetchAll($sql);
	
$activity = $res[0]-> activity;	
$activity +=1;	
	
$sql= "UPDATE Users SET activity =$activity WHERE id = $user";
$res = $this->db->execute($sql);
	//Return;
}	


 } // End of class