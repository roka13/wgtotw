<?php
namespace Roka\Dbtables;
 /**
 * Model for Tables.
 *
 */
//class Dbtables extends \Roka\Dbtables\DbtablesModel
class Dbtables extends \Anax\MVC\CDatabaseModel
{ 

private $newtable;


public function setupAction(){
         
	//$this->theme->setTitle("Nollställning av databas");

	//	$this->db->dropTableIfExists($newtable)->execute();

	$this->db->createTable(
	$newtable,
	[
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'tablename' => ['varchar(20)', 'unique', 'not null'],
            'fields' => ['integer','not null'],
             'created' => ['datetime'],
          ]
        )->execute();

//Lägg till testtabeller
	 $this->db->insert(
       $newtable,
        ['tablename','fields','created']
        );
                   
 
	$now = date('Y-m-d');
	
	$this->db->execute([
	'en ny tabell',
        '5',
          $now
		]);

	$this->db->execute([
	'ännu en tabell',
        '15',
         $now,
		]);



$all = $this->db->findAll();

	$this->views->add('dbtables/list-all', [
  'tabell' => $all,
       'title' =>'Databasen är nollställd'
          ]);

 
}  // end function 
          
/**
 * Add new table.
 *
 * @param string $tablename of table to add.
 * @param string $fields number of fields int table
 * @return void
 */
 public function addAction() {
 	// $this->DbtablesController->initialize();
	session_start();

    $form = $this->form->create([], [

	'newtable' => [
            'type'        => 'text',
            'required'    => true,
			'label'       => 'Tabellnamn',
            'validation'  => ['not_empty'],
        ],
	
        'fields' => [
			'type'    => 'text',
            'label'       => 'Ange hur mängden fält ',
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
       
        'submit' => [
            'type'      => 'submit',
			'value' => 'Spara',
		    'callback'  => function ($form) {
			
			$now = date('Y-m-d ');
			
		$this->db->dropTableIfExists($form->Value('newtable'))->execute();	
		$this->db->createTable(
		$form->Value('newtable'),
		  [
	        'id' =>[ 'integer',' primary key','not null', 'auto_increment'],
           'tablename' => [ 'varchar(20)', 'unique', 'not null'],
            'fields' =>['integer', 'not null'],
            'created'=>['datetime'],
			]
          );	
		$this->db->execute();

	
		  
//Lägg till testtabeller
	 $this->db->insert(
       $form->Value('newtable'),
        ['tablename','fields','created']
        );
                   
 
	$now = date('Y-m-d');
	
	$this->db->execute([
		
					'tablename'     => $form->Value('newtable'), 
					'fields'   	  => $form->Value('fields'), 
					'created'     => $now, 
								]);
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
		    $url = $this->url->create('Dbtables/id/' . $this->db->id); 
            $this->response->redirect($url); 
         }
		else if ($status === false) { 
         $form->AddOutput("<h3>Kontrollera data</h2>", 'gw');
			$url = $this->di->request->getCurrentUrl();
			$this->response->redirect($url);
        } 
		
		$url = $this->url->create('Dbtables/select');
		$this->theme->setTitle("New Table");
		$cont = $form->getHTML();
	$link="<form action='$url' method='get'><button> Åter till Lista alla tabeller</button></form>";
	$content = $cont . $link;
		$this->views->add('Dbtables/newform', [
		'title' => "Lägg till en ny tabell",
		'newform' => $content,
		'dbtabell'=> 'tablename',
		
		]);
}





 }//end of class