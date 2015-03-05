<?php
// setup url and buttons

 $url1= $this->url->create('Dbtables/select' );
 
 $button1= "<form action='$url1' method='get'><button>Åter till Huvudsidan</button></form>";

?>	
<div>
<?=$button1?>
<h1><?=$title?></h1>
<h3> Visar datatabellen med namnet: <?=$tblname?></h3>
<?=$tblcontent?>


</div>



