<?php 
header("Content-type: image/png"); 
session_start();

$length = 5;
$fontDirectory = "web/font/";
$char = "azertyuiopmlkjhgfdsqwxvcbn1234567890";
$char = str_shuffle($char);

$captcha = substr($char, 0, $length);
$_SESSION["captcha"] = $captcha;

$width = 160;
$height = 50;

$image = imagecreate(160,50); 
$backcolor = imagecolorallocate($image, rand(200,255), rand(0,255), 0); 
$textcolor = imagecolorallocate($image, 0, rand(0,255), rand(200,255)); 


//création de forme aléatoire
for ($i=0; $i <5 ; $i++) { 
	$form = rand(0,2);

	switch ($form) {
		case 0:
			imageline ($image , rand(0, $width) , rand(0, $height) , rand(0, $width) , rand(0, $height) , $textcolor);
			break;
		case 1:
			imageellipse ( $image , rand(0, $width) , rand(0, $height) , rand(0, $width) , rand(0, $height) , $textcolor );
			break;
		default:
			imagerectangle($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $textcolor);
			break;
	}


}








//Récupérer une police aléatoire dans le dossier font
if(file_exists($fontDirectory)){

	$listingFont = scandir($fontDirectory);

	unset($listingFont[0]);
	unset($listingFont[1]);
	unset($listingFont[2]);
	shuffle($listingFont);
	$font = $listingFont[0];

	imagettftext($image, 12, rand(5,-5), 10, 30, $textcolor, $fontDirectory.$font, $captcha);

}else{

	imagestring($image, 4, 10, 10, $captcha, $textcolor);

}


imagepng($image);