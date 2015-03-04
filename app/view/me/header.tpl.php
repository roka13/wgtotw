<img class='siteLogo' src='<?=$this->url->asset("img/wgtotw.png")?>' alt='WGTOTW Logo'/>
<div class='sitetitle'><?=$siteTitle?></div>
<div class ='siteLogin'>
<?php
    if (isset($_SESSION['user'])) : ?> 
	<div >
	<form class= 'hoverButton' style="display: inline" action='<?=$this->url->create('Users/logout')?>' method="get">
 	 <button>Logout</button></form></br>
	 <b>Inloggad som <?=$_SESSION['user']?></b>
	 </div>
	 <?php else : ?>
 <form class= 'hoverButton' style="display: inline" action='<?=$this->url->create('Users/login')?>' method="get">
 	 <button>Login</button></form>

 <form class= 'hoverButton' style="display: inline" action='<?=$this->url->create('Users/add')?>' method="get">
 	 <button>Registrera</button></form>
	    <?php endif; ?>
   </div>



 
<?php if(isset($_SESSION['user']) && $_SESSION['user'] == 'RGK') : ?>	
 <form class= 'hoverButton' class= 'hoverButton' style="display: inline" action='<?=$this->url->create('Dbtables/list')?>' method="get">
 <button>Hantera Databasen</button></form>  
  
<?php endif; ?>

<div class='siteslogan'><?=$siteTagline?></div>