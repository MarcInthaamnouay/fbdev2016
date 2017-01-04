jQuery(document).ready(function(){
	console.log('jQuery On!');


	enableSlickSlider();

	changeArrowsText();

	enableModalDesc();

	closeModalDesc();
});

var enableSlickSlider = function(){

	/* MAIN SLIDER */
	jQuery('.main-slider').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: true,
		dots: false,
		asNavFor: '.slider-menu'
	});

	/* NAV */
	jQuery('.slider-menu').slick({
		slidesToShow: 6,
		slidesToScroll: 1,
		asNavFor: '.main-slider',
		dots : false,
		arrows: true,
		focusOnSelect: true
	});
}


var changeArrowsText = function(){
	jQuery('.slick-arrow.slick-prev').html('<i class="icon-circle-left"></i>');
	jQuery('.slick-arrow.slick-next').html('<i class="icon-circle-right"></i>');
};

var enableModalDesc = function(){
	jQuery('.open-modal-desc').on('click', function(e){
		e.preventDefault();
		jQuery('.modal-desc').addClass('active');
		return false;
	});
};

var closeModalDesc = function(){
	jQuery('.modal-desc .icon-cancel-circle').on('click', function(){
		jQuery('.modal-desc').removeClass('active');
	})
}