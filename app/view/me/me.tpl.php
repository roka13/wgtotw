<?php if(isset($content)) :?>
<?= $content?>
 <?php endif; ?>

<?php if (isset($questions)) : ?> 

	<h4>Senaste frågorna </h4>

<article class ='article1'>
<?php foreach ($questions as $question) {
	
	$url = $this->url->create('Questions/id/' . $question->id);
	$avatar= md5(strtolower(trim($question->email)));
$comm = $this->textFilter->doFilter($question->questionTxt, 'shortcode, markdown');

	echo <<<EOD
		 <form class= 'hoverButton'style="display: inline"  action="$url" method="get"><button class= 'width4'>
	   	<img class='left' src="http://www.gravatar.com/avatar/{$avatar}.jpg?s=30"  alt='Bild' />
		<p><b>{$question->nickname} Datum  </b> {$question->questionDate}</p>
		<div class ='ram'>
		{$comm}
		</div>
		</button></form>
EOD;
}
?>
</article>
     <?php endif; ?>
	 


<?php if (isset($active)) : ?> 
<article class ='article1'>
	<h4>Mest aktiva medlemmarna </h4>

<div>	
<?php foreach ($active as $user) {

	$url = $this->url->create('Users/id/' . $user->id);
	$avatar= md5(strtolower(trim($user->email)));
	   $descript = $this->textFilter->doFilter($user->description, 'shortcode, markdown'); 
	   
	echo <<<EOD
		<form class= 'hoverButton'  style="display: inline" action="$url" method="get"><button class= 'width4'>
		<img class='left' src="http://www.gravatar.com/avatar/{$avatar}.jpg?s=30"  alt='Bild' />
		<p><b> {$user->nickname}</b></p>
		<div class ='ram'>
		{$descript}
		</div>
		</button></form>
EOD;
}
?>
</div>
</article>
 <?php endif; ?>
 

<?php if (isset($tags)) : ?> 
<article class ='article1'>
	<h4>Populäraste taggarna</h4>
	
<div>	
<?php foreach ($tags as $tag) {
	$descript = $this->textFilter->doFilter($tag->tagDescription, 'shortcode, markdown'); 
	$url = $this->url->create('Tags/id/' . $tag->id); 
	
	echo <<<EOD
		<form class= 'hoverButton'  style="display: inline" action="$url" method="get"><button class= 'width4'>	
		<p><b> {$tag->tagName}</b></p>
		<div class ='ram'>
		{$descript}
		</div>
		</button></form>
EOD;
}
?>
</div>
</article>
 <?php endif; ?>

    <?php if(isset($byline)) : ?>
           <footer class="byline">
 
            <?=$byline?>
       
        </footer>
    <?php endif; ?>
 
