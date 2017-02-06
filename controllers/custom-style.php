<?php

	header("Content-type: text/css");

	require_once __DIR__.'/../entity/Db.php';

	$dbController = new DB();

	$backgroundCustom = $dbController->getActiveStyle()[0]['color'];

?>

.custom-background{
	background-color : <?php echo $backgroundCustom; ?> !important;
}