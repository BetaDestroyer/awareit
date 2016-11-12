jQuery.noConflict();
(function( $ ) {
	$( document ).ready(function() {
		$('form.fos_user_registration_register').submit(function (evt) {
			var validation = validateCaptcha();
			if(validation == false) {
			    evt.preventDefault();
			}
		});
		var validateCaptcha = function() {
			var googleResponse = jQuery('#g-recaptcha-response').val();
			console.log(googleResponse);
			if (!googleResponse) {
			    $(".g-recaptcha").css("border","1px solid red");
			    return false;
			} else {
			    return true;
			}
		};
	});
})(jQuery);