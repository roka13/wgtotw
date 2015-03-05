<?php
 $url5= $this->url->create('Tags/add' );
 $button5= "<form class= 'hoverButton'  action='$url5' method='get'><button>Lägg till en ny tagg</button></form>"; 
?>

<div>
<?=$button5 ?>

<h3><?=$title?></h3>

<?php foreach ($tags as $tag) {
	$properties = $tag->getProperties();
	$descript = strip_tags($this->textFilter->doFilter($properties['tagDescription'], 'shortcode, markdown')); 
	$url = $this->url->create('Tags/id/' . $properties['id']);
	
	echo <<<EOD
	<div class='buttonram'>
		 <form class= 'hoverButton' action="$url" method="get">
		 <button>
		<b>{$properties['tagName']}</b><br>
			{$descript}
		</button>
		</form>
		</div>
EOD;
}
?>
</div>

