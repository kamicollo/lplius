<?php if (VIEW !== 'error') { ?>
<div id=pag class="insides clearfix">
		<a <?php if (!$grid->prevPage) echo 'class="disabled"'; ?> id=left <?php echo $grid->getPrevLink(); ?>>&#8810; ATGAL</a>
		<a id=right <?php if (!$grid->nextPage) echo 'class="disabled"'; ?> <?php echo $grid->getNextLink(); ?>>PIRMYN &#8811;</a>
	<ul id=pagination>
	<?php	foreach ($grid->getPagination() as $page)  { ?>
	<li 
		<?php if ($grid->isCurrentPage($page)) echo 'id="selected"'; ?>
		<?php if ($page instanceof DotItem) echo 'class="dotdotdot"'; ?> 
	>
	<?php if (!($page instanceof DotItem)) { ?>
		<a href="<?php echo $page->getLink(); ?>"><?php echo $page->getName(); ?></a>
	<?php } else echo $page->getName(); ?>	
	</li>
	<?php } ?>
	</ul>
</div>
<?php } ?>
