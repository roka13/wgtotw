<?php

namespace Anax\Comments;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsController  implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

/**
 * Initialize the controller.
 *
 * @return void  
 */
public function initialize()
{
    $this->Comments = new \Anax\Comments\Comments();
    $this->Comments->setDI($this->di);
	$this->Users = new \Anax\Users\Users();
    $this->Users->setDI($this->di);
}

/**
     * View all comments.
     *
     * @return void
     */
    public function viewAction()
    {
        $all = $this->comments->findAll();
        $this->views->add('comments/comments', [
            'comments' => $all,
        ]);
    }

 /**
 * Add new comment.
 *
 * @params posted texts in comment/form
 *
 * @return void 
 */
 public function addAction($id=null) {
 	$this->CommentsController->initialize();
	$questionId= $_SESSION['QuestionId'];
	$_SESSION['AnswerId'] = $id;
	$answerId = $id;
  
	$now = date('Y-m-d ');
	// hämta användare från sessionen för att lägga in IdUser
 if(!isset($_SESSION['user'])){ 
		$this->theme->setTitle("Error");
		$content = "Du måste vara registrerad användare och inloggad för att kunna kommentera ett inlägg"; 
		$fail = "Ej Auktoriserad";
		$this->views->add('error/fail',[
		'content' => $content,
		'fail' => $fail
		]);
}
else{ 
  $idUser=$_SESSION['id'];
  $form = $this->form->create([], [

	'idUser' => [
            'type'        => 'hidden',
           	'label'       => 'Alias',
			'value' => $idUser,  
        ],
		 
	'commentsTxt' => [
            'type'        => 'textarea',
			'label'       => ' Skriv din kommentar ' . $_SESSION['user'],
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
		     
		
        
        'submit' => [
            'type'      => 'submit',
			'value' => 'Spara',
		    'callback'  => function ($form) {
				$now = date('Y-m-d ');
				// Prevent wrong input
				$answer    = strip_tags($form->Value('commentsTxt')); 
		
				$this->Comments->save([
					'commentsTxt'     => $answer, 
					'idUser'   	  => $form->Value('idUser'), 
					'commentDate'     => $now, 		
				]);
			
				$lastId = $this->db->lastInsertId();
				$_SESSION['commentId']=$lastId;
				
			 // Save action point to  this user
			$this->Users->AddPoints($_SESSION['id']);
		
		if(!isset( $_SESSION['AnswerId'])){
		// Save QuestionsId and CommentsId to Comments2Questions db
		 $sql="INSERT INTO Comments2question (commentId, questionId) VALUES ({$lastId},{$_SESSION['QuestionId']})";
			}
		else{
				// Save AnswerId and CommentsId to Comments2answer db
			 $sql="INSERT INTO Comments2Answer (commentId, answerId) VALUES ({$lastId},{$_SESSION['AnswerId']})";
			}	
		 $this->db->execute($sql);	
	
			return true;
			}],
        
		'reset' => [
			'type'      => 'reset',
				'value' => 'Ångra texten',
				'callback'  => function($form) {
			  
				$form->saveInSession = false;
				$url = $this->di->request->getCurrentUrl();
			$this->response->redirect($url);
				// $form->AddOutput("<p><i>DoSubmitFail(): Form was submitted but I failed to process/save/validate it</i></p>");
					return false;
			}],
	
    ]);

           $status = $form->check(); 
          
        if ($status === true) { 
									
	    $url = $this->url->create('Questions/id/' .$_SESSION['QuestionId']); 
		//echo $url;
           $this->response->redirect($url); 
         }
		else if ($status === false) { 
		
         $form->AddOutput("<h3>Något blev fel försök igen</h2>", 'gw');
		
        } 
		if(!$answerId){ 		
		$sql="SELECT questionTxt  FROM Questions WHERE id = {$_SESSION['QuestionId']} ";
		$res = $this->db->executeFetchAll($sql);
		$question= "<h4>Frågan : </h4>". $this->textFilter->doFilter( $res[0]->questionTxt, 'shortcode, markdown');
		}
		else{
			$sql="SELECT answerTxt  FROM Answers WHERE id = {$answerId} ";
			$res = $this->db->executeFetchAll($sql);
				$question= "<h4>Svaret : </h4>". $this->textFilter->doFilter( $res[0]->answerTxt, 'shortcode, markdown') ;
		}
	
	   $url = $this->url->create('Questions/id/' . $_SESSION['QuestionId']);
		$this->theme->setTitle("Kommentar");
		$content = $form->getHTML();
		$link="<form action='$url' method='get'><button>Åter till frågan</button></form><br>";
		$this->views->add('default/forms',[
		'form' => $content,
		'link' => $link,
		'question' =>$question,
		]);
	 }	
	
}

//end of file	
}