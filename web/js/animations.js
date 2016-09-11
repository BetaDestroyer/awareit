jQuery.noConflict();
(function( $ ) {
	var percent_number_step = $.animateNumber.numberStepFactories.append(' %')
	$('.teaser-text-container .teaser-text .text-item.lg').animateNumber(
	{
		number: 60,
		'font-size': '100px',

		easing: 'easeInQuad',

		numberStep: percent_number_step
	},
	5000,
	function() {
		$(this).fadeOut( "slow", function() {
		});
		$(this).fadeIn( "slow", function() {
		});
    }
	);

	$(".more-text .text-item").click(function() {
		$('html, body').animate({
			scrollTop: $(".description-wrapper").offset().top
		}, 900);
	});
})(jQuery);