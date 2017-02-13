<?php

	header("Content-type: text/css");

	require_once __DIR__.'/../entity/Db.php';

	$dbController = new DB();

	$backgroundCustom = $dbController->getActiveBackgroundColor()[0]['backgroundcolor'];

	$hoverBackgroundCustom = $dbController->getActiveBackgroundColorHover()[0]['hoverbackgroundcolor'];

	$fontColorCustom = $dbController->getActiveFontColor()[0]['fontcolor'];

	$hoverFontColorCustom = $dbController->getActiveFontColorHover()[0]['hoverfontcolor'];

?>

.custom-background{
	background-color : <?php echo $backgroundCustom; ?> !important;
}

.custom-background-with-hover{
	background-color : <?php echo $backgroundCustom; ?> !important;
}

.custom-background-with-hover:hover{
	background-color : <?php echo $hoverBackgroundCustom; ?> !important;	
}

.custom-font-color{
	color : <?php echo $fontColorCustom; ?> !important;
}

.custom-border-color{
	border-color : <?php echo $backgroundCustom; ?> !important;
}