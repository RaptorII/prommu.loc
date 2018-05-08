$(function(){
	var arRate = $('.rai__table tr'),
		arReviews = $('.rai__review-input'),
		arRevLabels = $('.rai__review-label'),
		textarea = $('.rai__review-area');
	
	//
	//	event send form
	//
	$('.rai__btn').click(function(e){
		e.preventDefault();
		
		checkRates();
		checkReviews();
		checkTextField();

		errors = $('.error');
		if(errors.length==0){
			$('#F1rate').submit();
		}
	});
	$('.rai__table input').change(function(){ checkRates(this) });
	arReviews.change(function(){ checkReviews() });
	textarea.keyup(function(){ checkTextField() });	
	//
	//	functions
	//
	function checkRates(e=false){
		for(var i=0; i<arRate.length; i++){
			var bChecked = false,
				bFlag = false,
				arInputs = $(arRate[i]).find('input');

			for(var j=0; j<arInputs.length; j++){
				bChecked = arInputs[j].checked ? true : bChecked;
				bFlag = $(e).is(arInputs[j]) ? true : bFlag;
			}	

			if(!e){
				(bChecked && !e) 
				? $(arRate[i]).removeClass('error') 
				: $(arRate[i]).addClass('error');			
			}
			else if(bChecked && bFlag){
				$(arRate[i]).removeClass('error');
			}
		}
	}
	function checkReviews(){
		if(!arReviews[0].checked && !arReviews[1].checked){
			$(arRevLabels[0]).addClass('error');
			$(arRevLabels[1]).addClass('error');
		}
		else{
			$(arRevLabels[0]).removeClass('error');
			$(arRevLabels[1]).removeClass('error');
		}
	}
	function checkTextField(){
		textarea.val()!='' ? textarea.removeClass('error') : textarea.addClass('error');		
	}
});