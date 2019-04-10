$(function(){
	var arRate = $('.rai__table tr');
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
	$('.rai__review-area').keyup(function(){ checkTextField(this) });		
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
		var arItems = $('.rai__review-input'),
				arLabels = $('.rai__review-label');

		if(!arItems[0].checked && !arItems[1].checked){
			$(arLabels[0]).addClass('error');
			$(arLabels[1]).addClass('error');
		}
		else{
			$(arLabels[0]).removeClass('error');
			$(arLabels[1]).removeClass('error');
		}
		if(!arItems[2].checked && !arItems[3].checked){
			$(arLabels[2]).addClass('error');
			$(arLabels[3]).addClass('error');
		}
		else{
			$(arLabels[2]).removeClass('error');
			$(arLabels[3]).removeClass('error');
		}
	}
	function checkTextField(){
		var arItems = $('.rai__review-area');

		if(typeof arguments[0] == 'object')
			arItems = [arguments[0]];

		$.each(arItems, function(){
			$(this).val().trim()!=''
				? $(this).removeClass('error')
				: $(this).addClass('error');
		});
	}
});