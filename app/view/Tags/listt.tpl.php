<?php
 $url5= $this->url->create('Tags/add' );
 $button5= "<form class= 'hoverButton'  action='$url5' method='get'><button>Lägg till en ny tagg</button></form>"; 
?>

<div>
<?=$button5 ?>

<h3><?=$title?></h3>

<?php foreach ($tags as $tag) {
	$properties = $tag->getProperties();
	$descript = $this->textFilter->doFilter($properties['tagDescription'], 'shortcode, markdown'); 
	$url = $this->url->create('Tags/id/' . $properties['id']);
	
	echo <<<EOD
		 <form class= 'hoverButton' style="display: inline" action="$url" method="get"><button class= 'width4' >
		<p><b>{$properties['tagName']}</b></p>
		<div class='ram'>
		{$descript}
		</div>
		</button></form>
EOD;
}
?>
</div>

