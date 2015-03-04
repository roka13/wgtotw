<?php
namespace Anax\Tags;
 
/**
 * A controller for Tags and admin related events.
 *
 */
class TagsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->Tags = new \Anax\Tags\Tags();
    $this->Tags->setDI($this->di);
	$this->Users = new \Anax\Users\Users();
    $this->Users->setDI($this->di);
}		


/**
 * List all Tags.
 *
 * @return void
 */
public function listAction()
{ 
//$this->TagsController->initialize();
 
    $tags = $this->Tags->findAll();

    $this->theme->setTitle("List all tags");
	 $this->views->add('Tags/listt', [
        'tags' => $tags,
        'title' => "Alla Taggar",
    ]);
}


/**
 * List tag with id.
 *
 * @param int $id of tag to display
 *
 * @return void
 */
public function idAction($id= null)
{
	$this->Query = new \Roka\Dbtables\WGTQuerys();
    $this->Query->setDI($this->di);
	
    $tag = $this->Tags->find($id);
    $questions = $this->Query->GetQuestion2Tags($id);
    $this->theme->setTitle("View tag with id");
    $this->views->add('Tags/view', [
        'tags' => $tag,
		'title' => "Taggar",
		'questions' => $questions,
    ]);
}
  
 /**
 * Add new user.
 *
 * @param string $nickname of user to add.
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

    $form = $this->form->create([], [

	'tagName' => [
            'type'        => 'text',
			'min'	  	  => 3,
            'required'    => true,
			'label'       => 'Tagnamn',
            'validation'  => ['not_empty'],
        ],
		
       
		'beskrivning' => [
            'type'        => 'textarea',
			'label'       => 'Beskriv taggens syfte',
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
		
        
        'submit' => [
            'type'      => 'submit',
			'value' => 'Spara',
		    'callback'  => function ($form) {
	    			
			// Prevent wrong input
					$tagName    = strip_tags($form->Value('tagName')); 
					$beskrivning = strip_tags( $form->Value('beskrivning'));
			
			$this->Tags->save([
					'tagName'     => $tagName, 
					'tagDescription' => $beskrivning,
				]);
				
				
		// Save action point to  this user
		 $this->Users->AddPoints($_SESSION['id']);
		$lastId = $this->db->lastInsertId();
		$_SESSION['tagId']=$lastId;	

				
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
				
		    $url = $this->url->create('Tags/list'); 
		//echo $url;
            $this->response->redirect($url); 
         }
		else if ($status === false) { 
		
         $form->AddOutput("<h3>Kontrollera data</h2>", 'gw');
		 header("Location: " . $_SERVER['PHP_SELF']);
		//	$url = $this->di->request->getCurrentUrl();
		//	$this->response->redirect($url);
        } 
		
		$url = $this->url->create('Tags/list');
		$this->theme->setTitle("Ny tag");
		$cont = $form->getHTML();
	$link="<form action='$url' method='get'><button>Lista Alla Taggar</button></form>";
	$content = $cont . $link;
		$this->views->add('Default/forms', [
		'title' => "<h4>Fyll i tagnamn och beskrivning !",
		'form' => $content,
		'fail' => ''
		]);
	}	
}

    /**  
    * Update tag.  
    *  
    * @param integer $id of tag to update.  
    *  
    * @return void 
    */  
    public function updateAction($id = null)     
    {  
		$tag = $this->Tags->find($id);
	
		   
			$form = $this->form; 
        $form = $form->create([], [ 
            'tagName' => [ 
                'type'        => 'text', 
                'label'       => 'Tag-namn', 
                'required'    => true, 
                'validation'  => ['not_empty'], 
                'value' => $tag->tagName, 
            ], 
			
		'tagDescription' => [
            'type'        => 'textarea',
			'label'       => 'Redigera beskrivningen',
            'required'    => true,
            'validation'  => ['not_empty'],
		    'value' 	 => $tag->tagDescription, 
        ],
			
            'submit' => [ 
                'type'      => 'submit', 
				'value' => 'Spara',
                'callback'  => function($form) use ($tag) { 
		
       				// Prevent wrong input
					$tagName    = strip_tags($form->Value('tagName')); 
					$beskrivning = strip_tags( $form->Value('tagDescription'));
				
                    $this->Tags->save([ 
                        'id'        => $tag->id, 
                        'tagName'     => $tagName,  				
						'tagDescription' => $beskrivning
			               ]); 

                    return true; 
                } 
            ], 

        ]); 

        // Check the status of the form 
        $status = $form->check(); 

        if ($status === true) { 
		    $url = $this->url->create('Tags/id/' . $tag->id);
			   $this->response->redirect($url);
	
         
        } else if ($status === false) { 
            header("Location: " . $_SERVER['PHP_SELF']); 
            exit; 
        } 

        $this->theme->setTitle("Redigera Taggar"); 
        $this->views->add('Default/forms', [ 
            'title' => "Redigera Taggar", 
            'form' => $form->getHTML()
        ]); 
    }  
	
	

 /**
 * Delete tag.
 *
 * @param integer $id of tag to delete.
 *
 * @return void
 */
public function deleteAction($id = null){  
    if (!isset($id)) {
        die("Missing id");
    }

	$res = $this->Tags->delete($id);
    $url = $this->url->create('Tags/list' );
    $this->response->redirect($url);
}
 
} //end of controller

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 

