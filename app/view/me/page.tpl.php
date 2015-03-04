<?php

    if (isset($title)) : ?>   
	<h1><?=$title?></h1>
     <?php endif; ?>


<?php
    if (isset($content)) : ?>   
	<?=$content?>
     <?php endif; ?>
 

    <?php if(isset($byline)) : ?>
	
        <footer class="byline">
  
            <?=$byline?>
        </footer>
    <?php endif; ?>
 
