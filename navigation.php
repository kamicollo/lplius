<div id=nav class=clearfix>
	<ul class="insides clearfix">
	<?php foreach($grid->getNavigation() as $item) { ?>
		<li <?php if($grid->isCurrentNav($item)) echo "id=current" ?> ><a href="<?php echo $item->getLink(); ?>"><?php echo $item->getName(); ?></a></li>
	<?php } ?>
		<li id="widget-link"><a href="#widget-selector">Valdiklis</a></li>
	</ul>
</div>
