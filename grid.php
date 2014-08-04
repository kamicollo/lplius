<div id=grid class=clearfix>
<?php
	$i = 1;
	foreach ($grid->getChildren() as $person) {
	?>
	<div class="profile clearfix <?php if (($i % 5) == 1) echo 'first'; ?>">
		<div class=clearfix>
			<?php echo $person->getSecretLink(); ?>
			<a href="<?php echo $person->getProfileLink(); ?>">
				<img class=pic src="<?php echo $person->getPhoto(162); ?>" <?php echo $person->getSecretClass(); ?> alt="<?php echo $person->getName(); ?>" />
			</a>

			<div class=name>
				<a href="<?php echo $person->getProfileLink(); ?>"><?php echo $person->getName(); ?></a>
			</div>

			<div class=bio>
				<?php echo $person->getDescription(); ?>
			</div>

			<div class="count clearfix">
				<ul class=clearfix>
					<?php foreach($person->getFollowerTrafficLight() as $number => $reached) { ?>
					<li <?php if ($reached) echo 'class="reached"'; ?> title="Apie <?php echo round($number / 10, 0) * 10; ?> sekėjų"></li>
					<?php } ?>
				</ul>
				<span title="Sekėjų skaičius"><?php echo $person->getFollowerCount(); ?></span>
			</div>

			<a class=cat href="<?php echo Grid::buildLink(0, $person->getCategory()); ?>" >#<?php echo $person->getCategory(); ?></a>
		</div>

		<span class=shade></span>
	</div>

		<?php
		$i++;
	}
?>
</div>
