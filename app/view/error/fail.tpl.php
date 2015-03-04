

<?php if (isset($fail)) : ?>
<h3><?=$fail?></h3>
<?php endif; ?>
<p><?=$content?></p>
<?php if (isset($links)) : ?>
<ul>
<?php foreach ($links as $link) : ?>
<li><a href="<?=$link['href']?>"><?=$link['text']?></li>
<?php endforeach; ?>
</ul>

<?php endif; ?>
