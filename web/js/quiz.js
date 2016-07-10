jQuery.noConflict();
(function( $ ) {

	var questions = $(".question-wrapper"),
		showResultsBtn = $(".show-results"),
		counter = 0;

	// Hide all Questions
	questions.hide();
	showResultsBtn.hide();

	// Show first Question
	$(questions.get(counter)).show();

	// On click next
	$(".next").click(function() {

		var isValid = answerIsChecked(".answer-opt");

		counter = nextQuestion(counter, isValid);

	});

	/*
	 * @param int
	 * @return int
	 */
	function nextQuestion(counter, validation) {

		// Show next question if answer is valid 
		if(counter < $(questions).size()-1 && validation == true) {
			$(questions.get(counter)).hide();
			$(questions.get(counter+1)).show();
			counter++;
		}

		// Show result button after last question 
		if(counter >= $(questions).size()-1 ) {
			$(".next").hide();
			$(".show-results").show();
		}

		return counter; 
	}

	/*
	 * @param string
	 * @return boolean
	 */
	function answerIsChecked(selector) {

		// Get answers of current question
		var answers = $(questions.get(counter)).find(selector),
			isValid = false;

		// Check if at least one radio box is checked 
		$(answers).each(function(key, val) {
			if($(val).is(':checked')) {

				isValid = true;
			}
		});

		return isValid;
	}


})(jQuery);