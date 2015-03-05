<?php if(isset($content)) :?>
<?= $content?>
 <?php endif; ?>

<?php if (isset($questions)) : ?> 
<div class ='displayram'>
	<h4>Senaste frågorna </h4>

<article class ='article1' >
<?php foreach ($questions as $question) {	
	$url = $this->url->create('Questions/id/' . $question->id);
	$avatar= md5(strtolower(trim($question->email)));
	$comm = strip_tags($this->textFilter->doFilter($question->questionTxt, 'shortcode, markdown'));

	echo <<<EOD
		<div class='buttonram left' >
			<form class= 'hoverButton'  action="$url" method="get">
				<button>
					<img class='left' src="http://www.gravatar.com/avatar/{$avatar}.jpg?s=30"  alt='Bild' />
					<b>{$question->nickname} Datum </b> {$question->questionDate}<br><br>
					{$comm}
				</button>
			</form>
		</div>	
EOD;
}
?>
</article>
</div>
<?php endif; ?>

<?php if (isset($active)) : ?> 
<div class ='displayram left'>
	<h4>Mest aktiva medlemmarna </h4>
	
<article class ='article1'>
<?php foreach ($active as $user) {
	$url = $this->url->create('Users/id/' . $user->id);
	$avatar= md5(strtolower(trim($user->email)));
	$descript = strip_tags($this->textFilter->doFilter($user->description, 'shortcode, markdown')); 
	   
	echo <<<EOD
		<div class='buttonram' >
			<form class= 'hoverButton'  action="$url" method="get">
				<button>
					<img class='left' src="http://www.gravatar.com/avatar/{$avatar}.jpg?s=30"  alt='Bild' />
					<b>{$user->nickname}</b><br><br>
					{$descript}
				</button>
			</form>
		</div>
EOD;
}
?>

</article>
 </div>
 <?php endif; ?>


<?php if (isset($tags)) : ?> 
<div class='displayram left' >
	<h4>Populäraste taggarna</h4>
	
<article class ='article1'>
<?php foreach ($tags as $tag) {
	$descript = strip_tags($this->textFilter->doFilter($tag->tagDescription, 'shortcode, markdown')); 
	$url = $this->url->create('Tags/id/' . $tag->id); 
	
	echo <<<EOD
		<div class='buttonram' >
			<form class= 'hoverButton'  style="display: inline" action="$url" method="get">
				<button>	
				<b> {$tag->tagName}</b><br><br>	
				{$descript}
				</button>
			</form>
		</div>
EOD;
}
?>
</article>
</div>
 <?php endif; ?>

    <?php if(isset($byline)) : ?>
        <footer class="byline">
        <?=$byline?>
        </footer>
    <?php endif; ?>
 
