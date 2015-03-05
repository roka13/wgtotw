 <?php
 // setup url and buttons
 	 $properties = $tags->getProperties();
 
 $url1= $this->url->create('Tags/update/' . $properties['id']);
 $url5= $this->url->create('Tags/add/' . $properties['id']);
 $url7= $this->url->create('Tags/list');
 
 $button1= "<form class= 'hoverButton' style='display: inline'  action='$url1' method='get'><button>Uppdatera</button></form>";
 $button5= "<form class= 'hoverButton' style='display: inline' action='$url5' method='get'><button>Skapa ny tagg</button></form>"; 
 $button7= "<form class= 'hoverButton' style='display: inline' action='$url7' method='get'><button>Visa Alla</button></form>";

 //check status and unset not applicable  buttons 
 if(isset($_SESSION['user'])){ 
		    if(!($_SESSION['user']=='RGK')) {
					$button1=null;
			}
 }
 else{ $button1=$button5=null;
 }
?>

<?=$button1?><?=$button5?><?=$button7?>
<br>
<div class ='qram'>
	<b> Tagg nr:  <?= $properties['id'] ?>
		<?= $properties['tagName'] ?></b>
	   <p><?=$properties['tagDescription'] ?></p>
</div>

 
	<b> Frågor med denna tagg :</b>
<article class='article1'>
	<?php foreach ($questions as $question) {
		$url = $this->url->create('Questions/id/' . $question ->id);
		$quest= strip_tags($this->textFilter->doFilter($question->questionTxt, 'shortcode, markdown'));
	echo <<<EOD
	<div class='buttonram'>
		<form class= 'hoverButton'  action="$url" method="get">
			<button>
				{$quest}
			</button>
		</form>
	</div>
EOD;
} 
?>
	   
</article>