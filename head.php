<!DOCTYPE html>
<html lang=lt>

<head>
	<meta charset=UTF-8>
	<meta name=description content="Atrask įdomiausius ir populiariausius Google+ lietuvius. Pridėk save. Pridėk savo draugus. Nes jei tavęs nėra čia, tavęs nėra visai." />
	<meta name=keywords content="Google+, Ernesta, ernes7a, Aurimas, lietuviai" />
	<meta name=author content="Ernesta ir Aurimas" />
	<base href="<?php echo Grid::buildLink(); ?>" />

	<title>Lietuviai+</title>

	<link href="style.css?ver=1.3.1" rel=stylesheet type=text/css />
	<link href="http://fonts.googleapis.com/css?family=Special+Elite" rel=stylesheet type=text/css>

	<link rel="shortcut icon" href=favicon.ico />
	<link rel=icon type=image/png href=images/favicon.png />
	<link rel=apple-touch-icon href=images/favicon-apple-57.png />
	<link rel=apple-touch-icon sizes=72x72 href=images/favicon-apple-72.png />
	<link rel=apple-touch-icon sizes=114x114 href=images/favicon-apple-114.png />

	<meta property=og:title content="Lietuviai+" />
	<meta property=og:type content="website" />
	<meta property=og:url content="<?php echo Grid::$baseUrl; ?>" />
	<meta property=og:description content="Atrask įdomiausius ir populiariausius Google+ lietuvius. Pridėk save. Pridėk savo draugus. Nes jei tavęs nėra čia, tavęs nėra visai." />
	<meta property=og:image content="http://lplius.ernes7a.lt/images/lplius.png" />
	<meta property=fb:admins content="833735472,516139298" />

	<script src=js/jquery-1.6.2.min.js></script>
	<script src=js/jquery.easing.1.3.js></script>
	<script src=js/jquery-ui-1.8.16.custom.min.js></script>
	<link rel="stylesheet" href="js/jquery-ui/jquery-ui-1.8.16.custom.css" type="text/css" media="screen" />
        <script>
        website_base_url = "<?php echo Grid::$baseUrl; ?>";
        </script>
	<script type="text/javascript" src="<?php Grid::$baseUrl; ?>valdiklis/lplius.min.js"> </script>  	
	
	<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" href="js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

	<!--[if lt IE 9]><script src=http://html5shiv.googlecode.com/svn/trunk/html5.js></script><![endif]-->

	<script>
		jQuery(document).ready(function () {
			jQuery("#ernesta, #ernesta-corner").hover(
				function () {
					jQuery("#ernesta-corner").css("display", "block");
				},
				function () {
					jQuery("#ernesta-corner").css("display", "none");
				}
			)});

			jQuery(document).ready(function () {
			jQuery("#aurimas, #aurimas-corner").hover(
				function () {
					jQuery("#aurimas-corner").css("display", "block");
				},
				function () {
					jQuery("#aurimas-corner").css("display", "none");
				}
			)});
	</script>

	<script>
		jQuery(function() {
			jQuery('a#smooth').bind('click', function (event) {
				console.log("mekeke");

				jQuery('html, body').stop().animate({
					scrollTop: jQuery(jQuery(this).attr('href')).offset().top
				}, 1500,'easeInOutExpo');

				event.preventDefault();
		});
	});
	</script>


	<script>
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-19315846-13']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
	<script>
		$(document).ready(function() {	
			$("li#widget-link a").fancybox( {
				'padding': 20,
				'speedIn': 600,
				'overlayColor': '#0B1728'
			});			
		});

	</script>
</head>
