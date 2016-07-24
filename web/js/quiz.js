jQuery.noConflict();
(function( $ ) {

	var questions = $(".question-wrapper"),
		showResultsBtn = $(".show-results"),
		counter = 0;

	// Get current counter by ajax
	$.ajax({
		dataType: "json",
		url: window.location.href+'/counter/',
		async: false,
		error: function(message) {
			console.log(message);
		},
		success: function(data) {
			counter = data.response;
		}
	});

	if(counter >= $(questions).size()) {
		window.location.href = window.location.href+'/results/';
	}

	// Hide all Questions
	questions.hide();
	showResultsBtn.hide();

	// Show first Question
	$(questions.get(counter)).show();

	// On click next
	$(".next-question").click(function() {

		// Check if its the last question
		if(counter <= $(questions).size()-1) {
			
			var isValid 	= answerIsChecked(".answer-opt"),
			questionId 		= getQuestionId(counter),
			answerId   		= getAnswerId(".answer-opt"),
			url 	   		= window.location.href+'/answer/'+questionId+'/'+answerId+'/'+(parseInt(counter)+1)+'';

			// Check if an answer is selected
			if( isValid == true ) {

				// Save answer and show next question if no error
				$.ajax({
					url: url,
					error: function(message) {
						console.log(message);
					},
					success: function(data) {
						counter = nextQuestion(counter, isValid);
					}
				});

			}

		}

		// Show result button after last question 
		if(counter >= $(questions).size()-1 ) {
			$(".next-question").hide();
			$(".show-results").show();
		}
		
	});

	$(".answer-opt").click(function() {
		$(".quiz-answer").removeClass("is-checked");
		$(this).parent().addClass("is-checked");
	});

	/*
	 * @param int
	 * @return int
	 */
	function getQuestionId(counter) {
		var questionId = $(questions.get(counter)).attr('id');
		return questionId;
	}

	/*
	 * @param int
	 * @return int
	 */
	function getAnswerId(selector) {
		// Get answers of current question
		var answers = $(questions.get(counter)).find(selector),
			answerId = null;

		// Check if at least one radio box is checked 
		$(answers).each(function(key, val) {

			if($(val).is(':checked')) {
				answerId = $(val).val();
			}
		});

		return answerId;
	}

	/*
	 * @param int
	 * @return int
	 */
	function nextQuestion(counter, validation) {

		// Show next question if answer is valid 
		if(counter < $(questions).size()-1 && validation == true) {
			$(questions.get(counter)).hide();
			$(questions.get(parseInt(counter)+1)).show();
			counter++;
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