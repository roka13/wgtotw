 <?php
 // setup url and buttons
 	 $properties = $question->getProperties();

	 //hämta användares nickname
	$this->Users = new \Anax\Users\Users();
    $this->Users->setDI($this->di); 
	$user= $this->Users->find($properties['idUser']);
    $prop = $user->getProperties();

   
   
 $url5= $this->url->create('Questions/add/' . $properties['id']);
 $url7= $this->url->create('Questions/list');
  $url8= $this->url->create('Answers/add');
  $url9= $this->url->create('Comments/add');
 $button5= "<form class= 'hoverButton' style='display: inline' action='$url5' method='get'><button>Ny Fråga</button></form>"; 
 $button7= "<form class= 'hoverButton' style='display: inline' action='$url7' method='get'><button>Visa Alla</button></form>";
 $button8= "<form class= 'hoverButton' action='$url8' method='get'><button>Svara </button></form>";
 $button9= "<form class= 'hoverButton'  action='$url9' method='get'><button>Kommentera</button></form>";


  //check status and unset not applicable  buttons 
 if(isset($_SESSION['user'])){ 
		    if(!($_SESSION['user']=='RGK')) {
					$button6=null;
			}
 }
 else{ $button5=$button6=null;
 }

   $descript = $this->textFilter->doFilter($properties['questionTxt'], 'shortcode, markdown'); 
?>

<?=$button5?><?=$button7?>


<p><b> Fråga från: </b><?= $prop['nickname'] . ' <b>Ställd den</b> ' . $properties['questionDate'] ?> </p>

<div class ='qram' >

 <div class ='right'><?=$button8?><?=$button9?></div>
<?= $descript ?>	

		<?php foreach ($comments as $comment) {
		   $comm = $this->textFilter->doFilter($comment->commentsTxt, 'shortcode, markdown');
	echo <<<EOD
		<div class = 'ram'>
		<b>Kommentar från :	{$comment->nickname}</b>
		{$comm}
		</div>
EOD;

} 
?>
</div>

<div>	 
		<b> Taggar:</b>   
		<?php foreach ($tags as $tag) {
		$url = $this->url->create('Tags/id/' . $tag->tagId);
	echo <<<EOD
		<form class= 'hoverButton' style="display: inline" action="$url" method="get"><button>
		{$tag->tagName}
		</button></form>
EOD;
} 
?>
</div>	

<?php
if(isset($commanswer)){
 echo $commanswer;
}
?>

	
	