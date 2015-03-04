<?php
 $url5= $this->url->create('Users/add' );
 $button5= "<form class= 'hoverButton' action='$url5' method='get'><button>Registrera ny användare</button></form>"; 
?>

<div>
<?=$button5 ?>
<h3><?=$title?></h3>
 
<?php foreach ($Users as $user) {
	$properties = $user->getProperties();
	$url = $this->url->create('Users/id/' . $properties['id']);
	$avatar= md5(strtolower(trim($properties['email'])));

	echo <<<EOD
		<form class= 'hoverButton' style="display: inline" action="$url" method="get"><button>
	   	<img class='left' src="http://www.gravatar.com/avatar/{$avatar}.jpg?s=30"  alt='Bild' />
		<table class= 'width3'>
		<tr><td><b> {$properties['nickname']}</b></td><td>{$properties['firstname']} {$properties['lastname']}</td></tr>
		<tr><td>Medlem från: {$properties['joined']}</td><td>{$properties['email']}</td></tr>
		<tr><td><b>Betyg: </b> {$properties['activity']}</td><td><b>Status: </b>{$properties['status']}</td></tr>
		</table>
		</button></form>
EOD;
}
?>
</div>