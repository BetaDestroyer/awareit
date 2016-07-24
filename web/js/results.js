jQuery.noConflict();
(function( $ ) {

	$.each($(".answer-opt"), function() {

		if( $(this).is(':checked') ) {
			$(this).parent().addClass("given-answer");
		}
		
	});

})(jQuery);