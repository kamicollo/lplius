<div style="display:none">
	<div id="widget-selector">
		
		<div id="widget-container">
			<a class="lplius-widget" data-id="" data-width="180"></a>
		</div>
		
		<p>Jei norėtum turėti nuorodą į savo Google+ profilį bloge ar svetainėje, gali įsidėti mūsų valdiklį. Susirask savo vardą sąraše, pasirink reikiamą valdiklio plotį ir žemiau sugeneruotą kodą įsidėk į norimą svetainės vietą.</p>
		<p>Pora pastebėjimų: tau keičiant parametrus, pavyzdinio valdiklio plotis <em>(180px)</em> nesikeis. Minimalus valdiklio plotis yra <em>180px</em>, maksimalus - <em>400px</em>. Siūlom rinktis <em>iframe</em> versiją tik tada, kai <em>JavaScript</em> neveikia. O jei kartais prireiktų pagalbos, gali drąsiai kreiptis į mus.</p>
			
		<select id="person-picker">
		<?php
		try {
			$sql = 'SELECT googleplus_id, first_name, last_name FROM plusperson ORDER BY first_name collate utf8_lithuanian_ci ASC, last_name collate utf8_lithuanian_ci ASC';
			$people = $db->createObjects($sql, array(), 'PlusPersonView');
			foreach ($people as $person) {
				?>
			<option value="<?php echo $person->getId(); ?>" <?php if ($person->getId() == '117118694151742553199')
					echo 'selected="selected"'; ?>>
				<?php echo $person->getName(); ?>
			</option>
				<?php
			}
		} 
		catch (Exception $e) {} ?>
		</select>

		<div id="slider-container">
			<div id="slider"></div>
			<span id="width-value">250px</span>
		</div>
		
		<form id="code-form">
			<div id="code-toggle">
				<input type="radio" id="radio1" name="radio" /><label for="radio1">JavaScript</label>
				<input type="radio" id="radio2" name="radio" /><label for="radio2">iframe</label>
			</div>
		</form>
		
		<code id="lplius-widget-code"></code>


<script type="text/javascript">
	
	var LplusWidget = { element: null };
	
	$('#person-picker').change(function(event) {
		var id = $("option:selected", this).val();
		generate_widget(id);		
		generate_code(id, $('#slider').slider('value'));
		event.stopImmediatePropagation();				
	});
	
	function generate_widget(id) {
	 console.log('generating widget');
		if (LplusWidget.element == null) {
			var a = document.createElement('a');
			a.setAttribute('class', 'lplius-widget');		
			a.setAttribute('data-width', 180);
			LplusWidget.element = a;
		}		
		LplusWidget.element.setAttribute('data-id', id);
		$('#widget-container').html(LplusWidget.element);
		initiate_lplus_creation();
	}
	
	function generate_code(id, width) {
		if ($('#radio1').attr('checked') == 'checked') {				
			generate_js_code(id, width);
		}
		else {
			generate_iframe_code(id, width);
		}
	}
	
	function generate_js_code(id, width) {
		var anchor = '<a class="lplius-widget" data-id="'+ id +'" data-width="' + width  +'"></a>';
		var js = '<script src="<?php echo Grid::$baseUrl; ?>valdiklis/lplius.min.js"></scr' + 'ipt>';
		$('#lplius-widget-code').text(anchor + js);
	}
	
	function generate_iframe_code(id, width) {
		var iframe = '<iframe src="<?php echo Grid::$baseUrl; ?>valdiklis/iframe.php?'+ 
			'width=' + width + '&id=' + id + 
			'"height="' + (width + 150) + 'px" width="' + width + 'px"' + 
			' scrolling="no" frameborder="0" marginheight="0" marginwidth="0"></iframe>';
		$('#lplius-widget-code').text(iframe);
	}
				
	$(document).ready( function() {
		
		$('#person-picker option[value=117118694151742553199]').attr('selected', 'selected');
		generate_code($("#person-picker option:selected").val(), 250);
		
		$('#slider').slider({ animate: true, max: 400, min: 180, value: 250 });
		
		$("#slider").bind("slide", function(event, ui) {						
			$('#width-value').text(ui.value + 'px');
			generate_code($("#person-picker option:selected").val(), ui.value);
		});
				
		$("#code-toggle").buttonset();
		
		$('#radio1').attr('checked', 'checked');
		
		$('#radio1, #radio2').change(function() {
			var id = $("#person-picker option:selected").val();
			var width = $('#slider').slider('value');
			generate_code(id, width);
		});						
	});
 </script>
</div>
</div>
