<?php if(isset($_SESSION['user']) && $_SESSION['user'] == 'RGK') : ?>	

  
<?php
$url1= $this->url->create('Dbtables/create' );
$url2= $this->url->create('Dbtables/delete' );
$url3= $this->url->create('Dbtables/edit' );
$url4= $this->url->create('Dbtables/edit' );
$url5= $this->url->create('Dbtables/restore' );

$url1 =$url2 =$url3 =$url4 =$this->url->create('Dbtables/empty' );

$button1= "<form action='$url1' method='get'><button>Lägg till ny tabell</button></form>";

$button2= "<form action='$url2' method='get'><button>Ta bort befintlig tabell</button></form>";

$button3= "<form action='$url3' method='get'><button>Redigera befintlig tabell</button></form>";

$button4= "<form action='$url4' method='get'><button>Lägg till fält i befimtlig tabell</button></form>";

$button5= "<form action='$url5' method='get'><button>Återställ samtliga tabeller</button></form>";
?>
 <h4 class ='red'>Var försiktig vid användandet av denna sida. Du kan ställa till med oförlåtliga blunder
 om du gör fel</h4>
<?php endif; ?>


 <h1>Huvudmeny för mina Datatabeller</h1>

 <div>
 <div class='float left'>
 <h3>Välj tabell :</h3>
 <p> Lista samtliga fält</p>
 <p>och poster i tabellen</p>
    <form method='POST' action = '<?=$this->url->create('Dbtables/list')?>' >
			<select size='7' name='tblName'>
			<?php foreach($lista as $dfile):?>
			<option value= '<?=$dfile?>'><?=$dfile?></option>
			<?php endforeach; ?>	
			</select><br/>
		   <input type='submit'  value='Hämta'> 	  
    </form>
</div>

<div class ='float right'>
<h3> Länkar till övriga funktioner. </h3>
<?=$button1?>
<?=$button2?>
<?=$button3?>
<?=$button4?>
<?=$button5?>

</div>
</div>


