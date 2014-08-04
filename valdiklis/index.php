<?php
	include 'generator.php';
	$data = array('data' => CONTENTS, 'width' => $width, 'id' => $id, 'element' => (int) $_GET['element']);
	$json = json_encode($data);
	echo 'create_lplus_widget(' . $json . ')';
?>
