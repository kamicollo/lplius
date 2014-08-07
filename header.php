<header class=clearfix>
	<div class="insides clearfix">
		<div id=head class=clearfix>
			<h1><a href=""<?php echo Grid::buildLink(); ?>">Lietuviai+</a></h1>
			<span>Jau <?php echo $grid->getTotalUserCount(); ?></span>
		</div>
		<div id=share>

			<div id=twitter><a href="http://twitter.com/share" class="twitter-share-button" data-url="<?php echo Grid::$baseUrl ?>" data-counturl="http://lplius.lt" data-text="Lietuviai+: Google+ lietuviai vienoje vietoje" data-count="vertical" data-via="ernes7a" data-related="aurimas">Tweet</a></div>
			<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>

                        <div id=facebook><iframe id=fb src="http://www.facebook.com/plugins/like.php?locale=en_US&app_id=172673592803509&amp;href=<?php echo urlencode(Grid::$baseUrl); ?>&amp;send=false&amp;layout=box_count&amp;width=50&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=62" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:62px;" allowTransparency="true"></iframe></div>

			<div id=gplus><g:plusone size="tall" href="<?php echo Grid::$baseUrl ?>"></g:plusone></div>
			<script>
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
			</script>
		</div>

		<div id=about>
			Atrask įdomiausius ir populiariausius <a href=http://plus.google.com>Google+</a> lietuvius. Pridėk save (<a id=smooth href=#form>apačioje</a>). Pridėk savo draugus. Nes jei tavęs nėra čia, tavęs nėra visai.
		</div>


	</div>
</header>
