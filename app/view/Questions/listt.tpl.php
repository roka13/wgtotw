<?php
 $url5= $this->url->create('Questions/add' );
 $button5= "<form class= 'hoverButton'  action='$url5' method='get'><button>Ställ en fråga</button></form>"; 
?>

<div>
<?=$button5 ?>

<h3><?=$title?></h3>


	
<?php foreach ($Questions as $question) {
	$properties = $question->getProperties();
	$idUser=$properties['idUser'];
	$this->Users = new \Anax\Users\Users();
    $this->Users->setDI($this->di);
	$user = $this->Users->find($idUser);
	$prop = $user->getProperties();

	$url = $this->url->create('Questions/id/' . $properties['id']);
	$avatar= md5(strtolower(trim($prop['email'])));
    $descript = $this->textFilter->doFilter($properties['questionTxt'], 'shortcode, markdown'); 
	   
	echo <<<EOD
	 <form class= 'hoverButton'  style="display: inline" action="$url" method="get"><button class= 'width4'>
	  	<img class='left' src="http://www.gravatar.com/avatar/{$avatar}.jpg?s=30"  alt='Bild' />
			
	 <p><b> {$prop['nickname']}</b></p>
	 <div class='ram'>
	{$descript}
	</div>
	</button></form>
EOD;
}
?>
</div>

