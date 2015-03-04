<?php
namespace Roka\Dbtables;
 
/**
 * A controller for building a table and admin related events.
 *
 */
class DbtablesController   implements \Anax\DI\IInjectionAware
{
      use \Anax\DI\TInjectable;


/**
 * Initialize the controller.
 *
 * @return void
 */

public function initialize()
{
	$this->dbt = new \Roka\Dbtables\DbRestore();
	$this->dbt ->setDI($this->di);

}	



/**
 * View contents from a specific datatable 
 *
 * @returns a table with all contents and
 * fieldnames
 */
 public function listAction(){
	if((isset($_POST['tblName']) && $_POST['tblName'] !="tblName" )){
		$this->theme->setTitle("Visa tabell");
		
		
		$sql1="SELECT * FROM ".$_POST['tblName'];
	
		$content=$this->readContent($sql1);
	
		$this->views->add('Dbtables/show', [
			'title' =>'',
			'tblcontent' => $content,
			'tblname' => $_POST['tblName'],
		]);
	}
	else{
		$this->selectAction();
	}
}
	
/**
 * Method to retrieve all datatables in the database 
 *
 * @returns a select list
*/
public function selectAction(){

	$this->theme->setTitle("Mina Datatabeller");
	$sql="SELECT tbl_name FROM sqlite_master WHERE(type='table' OR type='view')";
	$res=$this->db->execute($sql);
	$res= $this->db->fetchAll();
	$lista= $this->readContentToArray($res);
	$this->views->add('Dbtables/maintbl', [
		'title' =>'Mina Data-Tabeller',
		'lista' =>$lista,
	  ]);
}
	
/**
 * Function to  build an array from an object
 *
 *@params $res the object
 * @returns an array with datatables name
*/
function readContentToArray($res){
	$array=array();
	foreach($res as $key => $val){ 
		foreach ( $val as $rad => $val2){
			$array[]=$val2;
		}		
	}
	return $array;
}
	
	
/**
 * Function to build a table of the content
 * in a datatable
 * params $sql the select query
 * returns a Table
*/
function readContent($sql){

	$res=$this->db->execute($sql);
	$res= $this->db->fetchAll();
	// rubrik till tabell
//	dump($res);
//	echo $sql;
	$html= "<h3> Sorry The Table or View has no content ! </h3>";
 if (count($res) >0){ 
	$rubrik= $res[0];
	$html ="<table><tr>";
	foreach ($rubrik as $key => $val ){
		$html .="<th>$key</th>";
	}
	// rader till tabellen	
	foreach($res as $key => $val){ 
		$html .="<tr>";
		foreach ( $val as $rad => $val2){	
			$html .=" <td>$val2</td> ";
		}		
		$html .= "</tr>";
	}
	$html .="</table>";
}	
	
	return $html;
}

public function emptyAction(){
	$this->views->add('Dbtables/empty', [
		'title' =>'Ledsen men sidan är inte klar ännu',
		'content' =>'<p>Försök igen i framtiden</p>',
    ]);
}

public function restoreAction(){
	$this->initialize();
 	$this->dbt-> RestoreDb();
	$this->views->add('Dbtables/empty', [
		'title' =>'Databasen är återställd ',
		'content' =>'<p>Endast en användare admin finns inlagd</p>',
    ]);
   
}


/* Function för att hämta en lista av rubriker till tabellen
*  Param $res innehåller resultatet av sökning i databasen
*/
/*
function readHeads( $tbl){
{
$sql = "SELECT * FROM ".$tbl;
}
 $res=$this->db->execute($sql);
 $res= $this->db->fetchAll();
$rubrik= $res[0];
$html ="<table><tr>";
foreach ($rubrik as $key => $val )
	{
	 $html .="<th>$key</th>";
	 
	}
	$html .= "</tr></table>";
//echo $html;
return $html;
}		
*/

/**
 * List table with id.
 *
 * @param int $id of table to display
 *
 * @return void
 */
 /*
public function idAction($id = null)
{ 
//$this->dbablesController->initialize();
	
    $tbl = $this->db->find($id);
 
    $this->theme->setTitle("View tables with id");
    $this->views->add('dbables/view', [
        'tabell' => $tbl,
		'title' => "Tabellnamn",
    ]);
}
 /*
 /**
 * Add new table.
 *
 * @param string $tablename of table to add.
 * @param string $fields number of fields int table
 * @return void
 */
 /*
 public function addAction() {
 	 $this->dbablesController->initialize();
	session_start();
	

    $form = $this->form->create([], [

	'tablename' => [
            'type'        => 'text',
            'required'    => true,
			'label'       => 'Tabellnamn',
            'validation'  => ['not_empty'],
        ],
	
        'fields' => [
			'type'    => 'text',
            'label'       => 'Ange hur många fält tabellen skall innehålla ',
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
       
        'submit' => [
            'type'      => 'submit',
			'value' => 'Spara',
		    'callback'  => function ($form) {
			
			$now = date('Y-m-d ');
			
			$this->tabell->save([
					'tablename'     => $form->Value('tablename'), 
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
		    $url = $this->url->create('dbables/id/' . $this->tabell->id); 
            $this->response->redirect($url); 
         }
		else if ($status === false) { 
         $form->AddOutput("<h3>Kontrollera data</h2>", 'gw');
			$url = $this->di->request->getCurrentUrl();
			$this->response->redirect($url);
        } 
		
		$url = $this->url->create('dbables/list');
		$this->theme->setTitle("New Table");
		$cont = $form->getHTML();
	$link="<form action='$url' method='get'><button> Åter till Lista alla tabeller</button></form>";
	$content = $cont . $link;
		$this->views->add('dbables/main', [
		'title' => "Lägg till en ny tabell",
		'content' => $content
		
		]);
}
*/
    /**  
    * Update table.  
    *  
    * @param integer $id of table to update.  
    *  
    * @return void 
    */  
/*
    public function updateAction($id = null)  
    {  	session_start();
        $form = $this->form; 

        $table = $this->tabell->find($id); 

        $form = $form->create([], [ 
            'tablename' => [ 
                'type'        => 'text', 
                'label'       => 'Tabellnamn', 
                'required'    => true, 
                'validation'  => ['not_empty'], 
                'value' => $table->tablename, 
            ], 
            'fields' => [ 
                'type'        => 'text', 
                'label'       => 'Antal Fält', 
                'required'    => true, 
                'validation'  => ['not_empty'], 
                'value' => $table->fields, 
            ], 
           
            'submit' => [ 
                'type'      => 'submit', 
                'callback'  => function($form) use ($table) { 

              	$now = date('Y-m-d ');

                    $this->tabell->save([ 
                        'id'        => $table->id, 
                        'tablename'     => $form->Value('tablename'), 
                        'fields'     => $form->Value('fields'), 
                        'created'     => $now, 
                                     ]); 

                    return true; 
                } 
            ], 

        ]); 

        // Check the status of the form 
        $status = $form->check(); 

        if ($status === true) { 
            $url = $this->url->create('dbables/id/' . $table->id); 
            $this->response->redirect($url); 
         
        } else if ($status === false) { 
            header("Location: " . $_SERVER['PHP_SELF']); 
            exit; 
        } 

        $this->theme->setTitle("Redigera Tabellnamn"); 
        $this->views->add('dbables/main', [ 
            'title' => "Redigera Tabellnamn", 
            'content' => $form->getHTML() 
        ]); 
    }  
*/
 /**
 * Delete table.
 *
 * @param integer $id of table to delete.
 *
 * @return void
 */
 /*
public function deleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
//	$user = $this->users->find($id);
  $res = $this->tabell->delete($id);

//     $user->save();
 
    $url = $this->url->create('dbables/list/' );
    $this->response->redirect($url);
}

/**
 * Delete (soft) user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
 /*
public function softDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
	$now = date('Y-m-d ');
 
    $user = $this->users->find($id);
	$user->active = null;
	$user->softdeleted = $now;
	$user -> status = 'papperskorg';
	$user->save();
    $url = $this->url->create('users/id/' . $id);
    $this->response->redirect($url);
}

*/

/**
 * Unactivate user.
 * @author Roka13
 * @param integer $id of user to deactivate.
 *
 * @return void
 */
 /*
public function unactivateAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
	
	$now = date('Y-m-d ');

	$user = $this->users->find($id);
	$user->active = null;
	$user -> status = 'inaktiv';
	$user->save();
 
    $url = $this->url->create('users/id/' . $id);
    $this->response->redirect($url);
}
*/
/**
 * SoftUndelete user.
 * @author Roka13
 * @param integer $id of user to unactivate.
 *
 * @return void
 */
 /*
public function softundeleteAction($id = null)
{
	if (!isset($id)) {
        die("Missing id");
    }
	
		$now = date('Y-m-d ');

    $user = $this->users->find($id);
	$user->active = $now;
	$user->softdeleted = null;
	$user->status= 'inaktiv';
	$user->save();
 
    $url = $this->url->create('users/id/' . $id);
    $this->response->redirect($url);
}

*/


/**
 * Activate user.
 * @author Roka13
 * @param integer $id of user to activate.
 *
 * @return void
 */
 /*
public function activateAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
	
 $now = date('D j M Y');

    $user = $this->users->find($id);
	$user->active = $now;
	$user->softdeleted = NULL;
	$user->status= 'aktiv';
	$user->save();
 
    $url = $this->url->create('users/id/' . $id);
    $this->response->redirect($url);
}
*/
/**
 * List all softdeleted  users.
 * @author Roka 13
 * @return void
 */
 /*
public function inactiveAction()
{
    $all = $this->users->query()
   	     ->where('status ="inaktiv"')
   	     ->execute();
 
    $this->theme->setTitle("Inactive  Users");
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Inaktiva och användare i papperskorgen",
    ]);
}

/**
 * List all active and not deleted users.
 *
 * @return void
 */
/*
public function activeAction()
{
    $all = $this->users->query()
        ->where('status = "aktiv"')
	    ->execute();
 
    $this->theme->setTitle("Active Users");
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Aktiva användare",
    ]);
}

/**
 * List all deleted users.
 *
 * @return void
 */
/*
 public function softdeletedAction()
{
    $all = $this->users->query()
        ->where('status= "papperskorg"')
        ->execute();
 
    $this->theme->setTitle("Users that are deleted");

    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Borttagna användare",
    ]);
}
*/


/*
public function createTableAction($newtable='test'){

 $this->dbablesController->initialize();
	$this->theme->setTitle("Create New Table");

		$this->db->dropTableIfExists($newtable)->execute();

	$this->db->createTable(
	$newtable,
	[
            'id' => ['integer', 'primary key', 'not null' ],
            'namn' => ['varchar(20)', 'unique', 'not null'],
             'yrke' =>['varchar(20)'],
			 'betyg' =>['varchar(3)']
          ]
        )->execute();

//Lägg till testtabeller
	 $this->db->insert(
       $newtable,
        ['id','name','yrke','betyg']
        );
                   
 	
	$this->db->execute([
	'5',
        'jonte',
          'sotare',
		  'aaa'
		]);

	$this->db->execute([
	


$all = $this->tabell->findAll();

	$this->views->add('dbables/list-all', [
  'tabell' => $all,
       'title' =>'Databasen är nollställd'
          ]);

 
}  // end function 
         
*/
}
