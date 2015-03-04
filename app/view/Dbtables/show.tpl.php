<?php
// setup url and buttons

 $url1= $this->url->create('Dbtables/select' );
 
 $button1= "<td><form action='$url1' method='get'><button>Åter till Huvudsidan</button></form></td>";

?>	
<div>
<h1><?=$title?></h1>
<h3> Visar datatabellen med namnet: <?=$tblname?></h3>
<?=$tblcontent?>

<?=$button1?>
</div>



