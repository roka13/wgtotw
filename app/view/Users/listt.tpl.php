<?php
 $url5= $this->url->create('Users/add' );
 $button5= "<form class= 'hoverButton' action='$url5' method='get'><button>Registrera ny användare</button></form>"; 
?>


<?=$button5 ?>
<h3><?=$title?></h3>
 <div>
<?php foreach ($Users as $user) {
	$properties = $user->getProperties();
	$url = $this->url->create('Users/id/' . $properties['id']);
	$avatar= md5(strtolower(trim($properties['email'])));

	echo <<<EOD
		<div class='left'>
			<form class= 'hoverButton' action="$url" method="get">
				<button>
					<img class='left' src="http://www.gravatar.com/avatar/{$avatar}.jpg?s=30"  alt='Bild' />
					<b> {$properties['nickname']}</b> <br>
					<b>Medlem från: </b>{$properties['joined']}<br>
				</button>
			</form>
		</div>
EOD;
}
?>
</div>