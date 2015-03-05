<?php
// setup url and buttons
	$this->Query = new \Roka\Dbtables\WGTQuerys();
    $this->Query->setDI($this->di);
	
	 $properties = $user->getProperties();
$button1=$button2=$button3=$button4=$button5=$button6=null;
?> 


 <?php
	$url1= $this->url->create('Users/update/' . $properties['id']);
	$url7= $this->url->create('Users/list');
	$url8= $this->url->create('Answers/add');
    $url9= $this->url->create('Comments/add');
	$button1= "<td><form class= 'hoverButton' action='$url1' method='get'><button>Uppdatera</button></form></td>";
	$button7= "<td><form class= 'hoverButton' action='$url7' method='get'><button>Visa Alla</button></form></td>";
	$button8= "<form class= 'hoverButton'  action='$url8' method='get'><button>Svara</button></form>";
	$button9= "<form class= 'hoverButton'  action='$url9' method='get'><button>Kommentera</button></form>";
	
 //check status and unset not applicable  buttons 
 
 if(isset($_SESSION['user'])){
	switch($properties['status']){
		case 'aktiv':
		    if(!($_SESSION['user']=='RGK')) {
					$button2=$button3=$button4=$button5=null;
			}
			
			if(($_SESSION['user'] != $properties['nickname'])){
			$button1=$button3=$button4=$button5=null;
			}
			
		break;		
		case 'inaktiv':
			$button1=$button2=$button5=null;
		break;

		case 'papperskorg':
			$button1=$button2=$button3=$button4=null;
		break;	
		
		case 'softdeleted':
			$button1=$button2=$button3=$button4=null;
		break;	

		default:
	}
 }
 else{ $button1=$button2=$button3=$button4=$button5=null;
 }
  $descript = $this->textFilter->doFilter($properties['description'], 'shortcode, markdown'); 
$avatar= md5(strtolower(trim($properties['email']))); ?>

<table>
<tr><?=$button2?><?=$button3?><?=$button4?><?=$button5?><?=$button7?></tr>
</table>

<h3> Medlem: </h3>
<div class ='qram'>
<div class='right'>
<?=$descript ?>
</div>

<img class='left' src="http://www.gravatar.com/avatar/<?=$avatar?>.jpg?s=60"  alt='Bild' />
<table>
		<tr><td>Alias:</td><td><?= $properties['nickname']?></td></tr>
		<tr><td>Namn:</td><td><?= $properties['firstname']?>  <?= $properties['lastname']?></td></tr>
	    <tr><td>Epost:</td><td><?= $properties['email'] ?></td></tr>
	    <tr><td>Blev medlem: </td><td><?= $properties['joined'] ?></td></tr>
		<tr><td>Uppdaterades: </td><td><?= $properties['updated'] ?></td></tr>
	    <tr><td>Status:</td><td><?=$properties['status'] ?></td><td>Betyg:</td><td><?=$properties['activity'] ?></td></tr>
</table>
	<?=$button1?>
</div>
 <?php if($questions) : ?>
<div>
	<p><b><?= $properties['firstname']?>s frågor</b></p>
<?php foreach ($questions as $question) {
		$quest=$question->questionTxt;
		$quests =$this->textFilter->doFilter($quest, 'shortcode, markdown');
	echo <<<EOD
		<div class ='qram'>
		{$quests}
		</div>
EOD;
} 
?>
</div>
<?php endif; ?>


 <?php if($answers) : ?>
	<div>
	<p><b><?= $properties['firstname']?>s svar</b></p>
	<?php foreach ($answers as $answer) {
		
		$res=$this->Query->GetQuestion2Answer($answer->id);
		$answername =$res[0]->nickname;
		$questionTxt =$this->textFilter->doFilter($res[0]->questionTxt, 'shortcode, markdown');
		$quests =$this->textFilter->doFilter($answer->answerTxt, 'shortcode, markdown');

	echo <<<EOD
		<div class ='qram' >
		<div class='right'>
		På fråga från <b>{$answername}</b>{$questionTxt}
		</div>
 	 	Svar: {$quests}
		</div>
EOD;
} 
?>
</div>
<?php endif; ?>
	
<?php if($comments) : ?>
	<div>
	 <p><b><?= $properties['firstname']?>s kommentarer till frågor</b></p>
	<?php foreach ($comments as $comment) {

	echo <<<EOD
		<div class ='qram' >
		<div class='right'>
		På fråga från <b>{$comment->nickname}</b>{$this->textFilter->doFilter($comment->questionTxt, 'shortcode, markdown')}
		</div>
		Kommentar: {$this->textFilter->doFilter($comment->commentsTxt, 'shortcode, markdown')}
		</div>
EOD;
} 
	
?>

</div>
<?php endif; ?>

<?php if($commansws) : ?>
	<div>
	 <b><?= $properties['firstname']?>s kommentarer till svar</b>
	<?php foreach ($commansws as $commansw) {

	echo <<<EOD
		<div class ='qram' >
		<div class='right'>
		På svar från <b>{$commansw->nickname}</b>{$this->textFilter->doFilter($commansw->answerTxt, 'shortcode, markdown')}
		</div>
		Kommentar: {$this->textFilter->doFilter($commansw->commentsTxt, 'shortcode, markdown')}
		</div>
EOD;
} 
	
?>

</div>
<?php endif; ?>