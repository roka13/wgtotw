<?php
$url1= $this->url->create('Dbtables/select' );
 
 $button1= "<form action='$url1' method='get'><button>Åter till Huvudsidan</button></form>";
?>
<div>
<h1><?=$title?></h1>
<?=$content?>
 <?=$button1?>
 </div>