<?php
namespace Anax\Answers;
 
/**
 * A controller for Answers and admin related events.
 *
 */
class AnswersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->Answers = new \Anax\Answers\Answers();
    $this->Answers->setDI($this->di);
	$this->Users = new \Anax\Users\Users();
    $this->Users->setDI($this->di);
}		

 /**
 * Add new Answers.
 *
 * @param string $nickname of Questions to add.
 *
 * @return void
 */
 public function addAction() {						
 	 $this->AnswersController->initialize();
	 $questionId= $_SESSION['QuestionId'];
 // hämta nickname från db för idUser
// hämta användare från sessionen för att lägga in IdUser
 if(!isset($_SESSION['user'])){ 
 	   $this->theme->setTitle("Error");
		$content = "Du måste vara registrerad användare och inloggad för att kunna svara på en fråga"; 
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
		 
	'answerTxt' => [
            'type'        => 'textarea',
			'label'       => 'Skriv ditt svar ' . $_SESSION['user'],
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
		     
		
        
        'submit' => [
            'type'      => 'submit',
			'value' => 'Spara',
		    'callback'  => function ($form) {
				$now = date('Y-m-d ');
				// Prevent wrong input
				$answer    = strip_tags($form->Value('answerTxt')); 
		
				$this->Answers->save([
					'answerTxt'     => $answer, 
					'idUser'   	  => $form->Value('idUser'), 
					'answerDate'     => $now, 		
				]);
			
				$lastId = $this->db->lastInsertId();
				$_SESSION['answerId']=$lastId;
				
		// Save QuestionsId and AnswersId to Answers2Questions db
		 $sql="INSERT INTO Answers2question (answerId, questionId) VALUES ({$lastId},{$_SESSION['QuestionId']})";
		 $this->db->execute($sql);	
		 
		 // Save action point to  this user
		 $this->Users->AddPoints($_SESSION['id']);
				
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
		
		$sql="SELECT questionTxt  FROM Questions WHERE id = {$_SESSION['QuestionId']} ";
		$res = $this->db->executeFetchAll($sql);
		
		$question= "<div class ='smallram'><b>Fråga : </b>". $this->textFilter->doFilter( $res[0]->questionTxt, 'shortcode, markdown') ."</div>";
		$url = $this->url->create('Questions/id/' . $_SESSION['QuestionId']);
		$this->theme->setTitle("Svar på fråga");
		$content = $form->getHTML(['columns'=> 2]);
		$link="<form action='$url' method='get'><button>Åter till frågan</button></form>";
		$this->views->add('default/forms',[
		'form' => $content,
		'link' => $link,
		'question'=> $question
		]);
		
 }
 }

} //end of controller

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 

