<?php
	require("controller.php");
	require("head.php");
?>

<body>
	<div id=wrap class=clearfix>
	<div id=content class=clearfix>
	<?php
		require("header.php");
		require("navigation.php");
		require(VIEW . '.php');
		require("pagination.php");

	?>
	</div>
	</div>

	<?php
		require("footer.php");
		require('selector.php');
	?>
</body>
</html>
