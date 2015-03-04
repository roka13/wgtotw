<?php
namespace Anax\Tags2question;
 
/**
 * A controller for Tags2question and admin related events.
 *
 */
class Tags2questionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->Tags2question = new \Anax\Tags2question\Tags2question();
    $this->Tags2question->setDI($this->di);
	
}		

/**
 * List all Tags2question.
 *
 * @return void
 */
public function listAction()
{ 
    $all = $this->Tags2question->findAll();
    $this->theme->setTitle("List all Tags2question");
	 $this->views->add('Tags2question/listt', [
        'Tags2question' => $all,
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
    $tag = $this->Tags2question->find($tagId);

    $this->theme->setTitle("View tag with id");
    $this->views->add('Tags2question/view', [
        'Tags2question' => $tag,
		'title' => "Taggar"
    ]);
}
 
 /**
 * Add new Tags2question.
 *
 * @param string $tagId and questionId.
 *
 * @return void
 */
 public function addAction($tagId,$questionId) {	
 
			$this->Tags2question->save([
					'questionId'     => $questionId, 
					'tagid' => $tagId,
				]);
					
 }	
   

 /**
 * Delete tag2question.
 *
 * @param integer $id of tag to delete.
 *
 * @return void
 */
public function deleteAction($questionId){
   
	$res = $this->Tags2question->delete($id);
    $url = $this->url->create('Tags2question/list' );
    $this->response->redirect($url);
}

 
} //end of controller

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 

