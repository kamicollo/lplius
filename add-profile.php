<form class=clearfix method="POST" action="<?php echo $_SERVER['REQUEST_URI'] . '#form'; ?>">

	<div id=rowOne class=clearfix>
		<label for=link>Nuoroda:</label>
		<div id=link>
			<input name="profile_id" type= "text" />
		</div>
	</div>
	
	<div id=rowTwo class=clearfix>
		<div id=card class=clearfix>
			<label for=category>
				Kategorija:
				<span>
					<p>Gyvenimas: asmeniniai įrašai, juokeliai, įdomybės.</p>
					<p>Pramogos: muzika, kinas, renginiai.</p>
				</span>
			</label>
		</div>
		
		<div id=submit>
			<select id=category name="category">
			<option value="-1">-</option>
		<?php foreach ($grid->getCategories() as $item) { ?>
				<option value="<?php echo $item->getId(); ?>"><?php echo $item->getName(); ?></option>
		<?php } ?>
			</select>
	
			<input id=ok type="submit" value="Pridėti">
		</div>
	</div>

	<div id=feedback><?php if (isset($PostMessage)) echo $PostMessage->getMessage(); ?></div>
</form>
