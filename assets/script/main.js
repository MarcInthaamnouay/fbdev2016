jQuery(document).ready(function(){
	console.log('jQuery On!');

	randomClick();
});


var randomClick = function(){
	console.log("TOtotototot");
	jQuery('#random1').click(function(){
        jQuery('#random1').addClass("active");
        jQuery('#random2').removeClass("hidden");
        jQuery('#allPictures1').removeClass("active");
        jQuery('#allPictures2').addClass("hidden");
    });

    jQuery('#allPictures1').click(function(){
        jQuery('#allPictures1').addClass("active");
        jQuery('#allPictures2').removeClass("hidden");
        jQuery('#random1').removeClass("active");
        jQuery('#random2').addClass("hidden");
    });
}