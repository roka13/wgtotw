<?php
namespace Anax\Questions;
 
/**
 * A controller for Questions and admin related events.
 *
 */
class QuestionsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

/**
 * Initialize the controller.
 *
 * @return void
 */

public function initialize()
{
    $this->Questions = new \Anax\Questions\Questions();
    $this->Questions->setDI($this->di);
	$this->Users = new \Anax\Users\Users();
   $this->Users->setDI($this->di);
	$this->Query = new \Roka\Dbtables\WGTQuerys();
    $this->Query->setDI($this->di);
}		


	
/**
 * List all Questions.
 *
 * @return void
 */
public function listAction()
{ 
    $all = $this->Questions->findAll();
    $this->theme->setTitle("List all Questions");
	$this->views->add('Questions/listt', [
        'Questions' => $all,
        'title' 	=> "Alla Frågor",
    ]);
}


/**
 * List question with id.
 *
 * @param int $id of question to display
 *
 * @return void
 */
public function idAction($id= null)
{
    $question = $this->Questions->find($id);
	$tags	  = $this->Query->GetTags2Question($id);
	$answers  = $this->Query->GetAnswers2question($id);
	$comments = $this->Query->GetComments2question($id);
	//$answers =
	$commanswer=$this->GetCommentsToAnswers($id,$answers);
	$_SESSION['QuestionId'] = $id;
	 
	$this->theme->setTitle("View question with id");
    $this->views->add('Questions/view', [
        'question' 	=> $question,
		'title'		=> "Frågor",
		'tags'		=> $tags,
		'comments'	=> $comments,
		'commanswer'=> $commanswer,
    ]);
}

 
/** 
* bygg upp en vy med alla kommentarer till svaren på vald fråga för 
* en användare  $id = id på svaret $answers array (alla svaren.)
 */
public function GetCommentsToAnswers($id,$answers){
	$co=''; $an=''; $result='';

 foreach ($answers as $answ) {
	  $svar = $this->textFilter->doFilter($answ->answerTxt, 'shortcode, markdown');
      $url10=$this->url->create('Comments/add/'.$answ->Aid); // 27 28
	  $commansw = $this->Query->GetComments2Answer($answ->Aid);
   	  $button10= "<form class= 'hoverButton'  action='$url10' method='get'><button>Kommentera</button></form>";
	
	$an = <<<EOD
	<div class = 'qram'>
	 <div class ='right'>{$button10}</div>
	<b>Svar från :	{$answ->nickname}</b>
		{$svar}
EOD;

                
 foreach ($commansw as $cans) {	 
			   $comm = $this->textFilter->doFilter($cans->commentsTxt, 'shortcode, markdown');
	$comment = <<<EOD
	<div class = 'qram'>
	<b>Kommentar till svar från :	{$cans->nickname}</b>
    {$comm}
	</div>
EOD;

	$co = $co . $comment;
}
	$result = $result .$an .$co;
	$co='';
	$result=$result . "</div>" ;
  }
return $result;		   
} //end function

 /**
 * Add new question.
 *
 * @param string $nickname of question to add.
 *
 * @return void
 */
 public function addAction() {						
 	 
 // hämta nickname från db för idUser
// hämta användare från sessionen för att lägga in IdUser
 if(!isset($_SESSION['user'])){ 
		$this->theme->setTitle("Error");
		$content = "Du måste vara registrerad användare och inloggad för att kunna ställa in fråga"; 
		$fail = "Ej Auktoriserad";
		$this->views->add('error/fail',[
		'content'	=> $content,
		'fail' 		=> $fail
		]);
}
else{ 
  $idUser=$_SESSION['id'];
  
  // hämta alla taggar som array
    $this->Tags = new \Anax\Tags\Tags();
    $this->Tags->setDI($this->di);
    $Tags = $this->Tags->findAll();
	 
	foreach ($Tags as $tag) {
		$properties = $tag->getProperties();
		$tagName= $properties['tagName'] ;
		$tagId = $properties['id'];
		$taggen[] = $tagId . ' ' . $tagName;
		}

     $form = $this->form->create([], [

	'idUser' => [
            'type'        => 'hidden',
           	'label'       => 'Alias',
			'value' => $idUser,  
        ],
		 
	'questionTxt' => [
            'type'        => 'textarea',
			'label'       => 'Skriv din fråga ' . $_SESSION['user'],
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
		'Tags' => [
              'type'        => 'checkbox-multiple',
            'label'       => 'Markera lämpliga taggar för din fråga',
          'values'      => $taggen,
		   ],
	
     
		
        
        'submit' => [
            'type'      => 'submit',
			'value' => 'Spara',
		    'callback'  => function ($form) {
				$now = date('Y-m-d ');
				// Prevent wrong input
				$question    = strip_tags($form->Value('questionTxt')); 
		
				$this->Questions->save([
					'questionTxt'     => $question, 
					'idUser'   	 	  => $form->Value('idUser'), 
					'questionDate'    => $now	
				]);
		// Save action point to  this user
		$this->Users->AddPoints($_SESSION['id']);
		$lastId = $this->db->lastInsertId();
		$_SESSION['questionId']=$lastId;
				
		// Save all marked tags from this Question in table  Tags2question	
		// if omitted skip saving 
			 if(isset($_POST['Tags'])){
				$tag[]=$_POST['Tags'];
				foreach ($tag as $tq){
				//dump($tq);
					while($val = current($tq)){
						$valet=mb_substr($val,0,1);
						$this->Tags2question = new \Anax\Tags2question\Tags2question();
						$this->Tags2question->setDI($this->di);
						$this->Tags2question->save([
							'questionId'  => $lastId, 
							'tagId'		  => $valet,
						]);
					next($tq);
					}
				}
			}
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
									
	    $url = $this->url->create('Questions/id/' . $this->Questions->id); 
		//echo $url;
           $this->response->redirect($url); 
         }
		else if ($status === false) { 
		
         $form->AddOutput("<h3>Något blev fel försök igen</h2>", 'gw');
		
        } 
		
		$url = $this->url->create('Questions/list');
		$this->theme->setTitle("Ny Fråga");
		$content = $form->getHTML(['columns'=> 2]);
	$link="<form action='$url' method='get'><button>Åter till frågorna</button></form>";
			
		$this->views->add('default/forms',[
		'form' => $content,
		'link' => $link
		]);
		
 }

}
	
} //end of controller

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 

