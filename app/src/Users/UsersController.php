<?php
namespace Anax\Users;
 
/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->Users = new \Anax\Users\Users();
	$this->Users->setDI($this->di);
	$this->Query = new \Roka\Dbtables\WGTQuerys();
    $this->Query->setDI($this->di);
}		


/**
 * List all users.
 *
 * @return void
 */
public function listAction()
{ 
    $all = $this->Users->findAll();
    $this->theme->setTitle("List all members");
	$this->views->add('Users/listt', [
        'Users' => $all,
        'title' => "Alla Användare",
    ]);
}


/**
 * List user with id.
 *
 * @param int $id of user to display
 *
 * @return void
 */
public function idAction($id= null)
{
	$user 		= $this->Users->find($id);
	$answers    = $this->Query->GetAnswers2User($id);
	$questions  = $this->Query->GetQuestion2User($id);
  	$comments   = $this->Query->GetQuestion2Comment($id);
	$commansws  = $this->Query->GetAnswer2Comment($id);
	
    $this->theme->setTitle("View user with id");
    $this->views->add('Users/view', [
        'user'		 => $user,
		'title'		 => "Användare",
		'questions'  => $questions,
		'answers'    => $answers,
		'comments'   => $comments,
		'commansws'  => $commansws
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

 $form = $this->form->create([], [

		'nickname' => [
            'type'        => 'text',
			'min'	  	  => 3,
            'required'    => true,
			'label'       => 'Alias',
            'validation'  => ['not_empty'],
        ],
		
		'firstname' => [
            'type'        => 'text',
            'label'       => 'Förnamn:',
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
	
        'lastname' => [
            'type'        => 'text',
            'label'       => 'Efternamn:',
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
		
        'email' => [
            'type'        => 'email',
			'label'       => 'Epost-adress',
            'required'    => true,
            'validation'  => ['not_empty', 'email_adress'],
        ],
		
		'password1' => [
            'type'        => 'password',
			'label'       => 'Lösenord',
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
		
		'password2' => [
            'type'        => 'password',
			'label'       => 'Bekräfta Lösenord',
            'required'    => true,
			'validation'  => ['not_empty', 'match'=>'password1'],
			
		],	
       
		'beskrivning' => [
            'type'        => 'textarea',
			'label'       => 'Berätta om dig själv',
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
		
        
        'submit' => [
            'type'      => 'submit',
			'value' => 'Spara',
		    'callback'  => function ($form) {
	    
			$now = date('Y-m-d ');
			
			// Prevent wrong input
					$nickname    = strip_tags($form->Value('nickname')); 
					$firstname   = strip_tags($form->Value('firstname'));
					$lastname    = strip_tags($form->Value('lastname'));
					$password    = strip_tags($form->Value('password1'));
					$beskrivning = strip_tags( $form->Value('beskrivning'));
			
			$this->Users->save([
					'nickname'     => $nickname, 
					'email'   	   => $form->Value('email'), 
					'firstname'    => $firstname,
					'lastname'     => $lastname, 
					'password'     => password_hash($password, PASSWORD_DEFAULT), 
					'joined'       => $now, 		
					'description'  => $beskrivning,
					'status' 	   => 'aktiv',
					'activity' 	   => 1,
				]);
					
			    return true;
            }],
        
		'reset' => [
				'type'      => 'reset',
				'value'		=> 'Ångra texten',
				'callback'  => function($form) {
					$form->saveInSession = false;
					$url = $this->di->request->getCurrentUrl();
					$this->response->redirect($url);
					return false;
			}],
	
    ]);

           $status = $form->check(); 

        if ($status === true) { 
		//logout if neccesary and login the new account
			unset($_SESSION['user']);
			$_SESSION['user'] =  $this->Users->nickname;
			$_SESSION['id'] =  $this->Users->id;
					
		    $url = $this->url->create('Users/id/' . $this->Users->id); 
            $this->response->redirect($url); 
         }
		else if ($status === false) { 
			$form->AddOutput("<h3>Kontrollera data</h2>", 'gw');
			header("Location: " . $_SERVER['PHP_SELF']);
			//	$url = $this->di->request->getCurrentUrl();
			//	$this->response->redirect($url);
        } 
		
		$url = $this->url->create('Users/list');
		$this->theme->setTitle("Ny medlem");
		$cont = $form->getHTML(['columns'=> 2]);
		$link="<form action='$url' method='get'><button>Lista Alla medlemmar</button></form>";
		$content = $cont . $link;
			
		$this->views->add('Default/forms',[
		'title' => "<h4>Fyll i formuläret och registrera dig !</h4><p> Du blir automatiskt inloggad när du är klar</p>",
		'form' => $content,
		'fail' => ''
		]);
}

    /**  
    * Update user.  
    *  
    * @param integer $id of user to update.  
    *  
    * @return void 
    */  
    public function updateAction($id = null)      // skall redigeras
    {  
		$user = $this->Users->find($id);
		$form = $this->form; 
        $form = $form->create([], [ 
            'nickname' => [ 
                'type'        => 'text', 
                'label'       => 'nickname', 
                'required'    => true, 
                'validation'  => ['not_empty'], 
                'value' 	  => $user->nickname, 
            ], 
			'firstname' => [ 
                'type'        => 'text', 
                'label'       => 'firstname', 
                'required'    => true, 
                'validation'  => ['not_empty'], 
                'value'	      => $user->firstname, 
            ], 
			
            'lastname' => [ 
                'type'        => 'text', 
                'label'       => 'lastname', 
                'required'    => true, 
                'validation'  => ['not_empty'], 
                'value'		  => $user->lastname, 
            ], 
            'email' => [ 
                'type'        => 'text', 
                'required'    => true, 
                'validation'  => ['not_empty', 'email_adress'], 
                'value' 	  => $user->email, 
            ], 
						
		'password1' => [
            'type'        => 'password',
			'label'       => 'Skriv in nytt lösenord om du vill ändra. Annars hoppa över',
		],	
		
		'beskrivning' => [
            'type'        => 'textarea',
			'label'       => 'Redigera beskrivningen',
            'required'    => true,
            'validation'  => ['not_empty'],
		    'value' 	  => $user->description, 
        ],
			
            'submit' => [ 
                'type'      => 'submit', 
				'value' => 'Spara',
                'callback'  => function($form) use ($user) { 
				
				//check password changes
				if($form->Value('password1')){
				  		$password  = strip_tags($form->Value('password1'));
						$this->Users->save([ 
					    'id'        => $user->id,
				  		'password'     => password_hash($password, PASSWORD_DEFAULT)
						]);
				   }
			
              	$now = date('Y-m-d ');
				// Prevent wrong input
					$nickname    = strip_tags($form->Value('nickname')); 
					$firstname   = strip_tags($form->Value('firstname'));
					$lastname    = strip_tags($form->Value('lastname'));
					$beskrivning = strip_tags( $form->Value('beskrivning'));
					//add points for activity
					$activ +=($user->activity);

                    $this->Users->save([ 
                        'id'     	   => $user->id, 
                        'nickname'     => $nickname, 
                        'email'    	   => $form->Value('email'), 
						'firstname'    => $firstname, 
                        'lastname'     => $lastname, 
                        'updated'      => $now, 
						'description'  => $beskrivning,
					    'status'  	   => 'aktiv',
						'activity'     => $activ
                    ]); 

                    return true; 
                } 
            ], 

        ]); 

        // Check the status of the form 
        $status = $form->check(); 

        if ($status === true) { 
		   $url = $this->url->create('Users/id/' . $user->id);
		   $this->response->redirect($url);
        }
		else if ($status === false) { 
            header("Location: " . $_SERVER['PHP_SELF']); 
            exit; 
        } 

        $this->theme->setTitle("Redigera användare"); 
		$this->views->add('default/forms',[
            'title' => "Redigera användare", 
            'form'  => $form->getHTML(['columns'=> 2])
        ]); 
    }  
	

/**
 * List all active and not deleted users.
 *
 * @return void
 */
public function activeAction()
{
    $all = $this->Users->query()
        ->where('status = "aktiv"')
	    ->execute();
 
    $this->theme->setTitle("Active Users");
    $this->views->add('Users/listt', [
        'Users' => $all,
        'title' => "Aktiva användare",
    ]);
}

  /**
 * Login User.
 *
 * @return void
 */
 public function loginAction() {
	
 if(isset($_SESSION['user'])){ 
		$content = "Du är redan inloggad som {$_SESSION['user']}"; 
		$this->views->add('error/fail',[
		'content'	=> $content,
		'fail' 		=> ''
		]);
}
else{

    $form = $this->form->create([], [
      
        'email' => [
            'type'        => 'text',
			'label'       => 'Epost-adress',
            'required'    => true,
            'validation'  => ['not_empty', 'email_adress'],
        ],
		'password' => [
            'type'        => 'password',
			'label'       => 'Lösenord',
            'required'    => true,
            'validation'  => ['not_empty'],
        ],

        'submit' => [
            'type'      => 'submit',
			'value' 	=> 'Login',
		    'callback'  => function ($form) {
					if($this->Users->checkLogin($form->Value('email'),($form->Value('password')))){
					return true;
				}
			}
		],	
		]);	

	    $status = $form->check(); 

        if ($status === true) { 
		 $_SESSION['loginCount']=0;
		header("Location: " . $this->url->create(''));
	   }
	   
		else if($_SESSION['loginCount'] < 1){
			$fail='';
			$_SESSION['loginCount'] += 1;
		}
		else{ 
		 $form->AddOutput("Fel Emailadress eller fel lösenord försök igen !", 'gw');
				$_SESSION['loginCount'] += 1;
			if ($_SESSION['loginCount'] > 3){
				 $_SESSION['loginCount']=0;
										
				$fail = "Du har misslyckats med inloggningen tre gånger"; 
					 
				$url = $this->url->create('Users/add');
				$this->theme->setTitle("Ej Auktoriserad");
				
				$content="<form action='$url' method='get'><button>Gå till registrering av ny användare</button></form>";
				$this->views->add('error/fail',[
					'content' => $content,
					'fail'	  => $fail
				]);
				
				return false;
				
			}
    
        } 
		
		$content = $form->getHTML();
		$this->views->add('Default/forms',[
	    'form' => $content,
		'title'=>'Logga in',
		'fail' => ''
      ]); 
		
	}
}	

public function logoutAction(){
	unset($_SESSION['user']);
	unset($_SESSION['id']);
	      header("Location: " . $this->url->create(''));
}
	
} //end of controller