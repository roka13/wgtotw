<?php
namespace Anax\Home;
 
/**
 * A controller for Homesite
 *
 */
class HomeController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


public function indexAction()
{ 
	$this->Query = new \Roka\Dbtables\WGTQuerys();
    $this->Query->setDI($this->di);
	$question = $this->Query->GetLatestQuestion();
	$active = $this->Query->GetActiveUsers();
	$tags = $this ->Query->GetPopTags();
//dump($question);
    $this->theme->setTitle("Homepage");
	$content = $this->fileContent->get('me.md');
    $content = $this->textFilter->doFilter($content, 'shortcode, markdown');
	$this->views->add('me/me', [
        'questions' => $question,
		'content'    => $content,
		'active'  => $active,
		'tags'   => $tags,
           ]);
}

} //end of controller

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 

