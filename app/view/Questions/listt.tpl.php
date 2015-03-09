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
    $descript = strip_tags($this->textFilter->doFilter($properties['questionTxt'], 'shortcode, markdown')); 
	   
	echo <<<EOD
		<div class='buttonram' >
			<form class= 'hoverButton'  style="display: inline" action="$url" method="get">
				<button>
				<img class='left' src="http://www.gravatar.com/avatar/{$avatar}.jpg?s=30"  alt='Bild' />
				<b> {$prop['nickname']}</b>
				{$descript}
				</button>
			</form>
		</div>
EOD;
}

?>
</div>


