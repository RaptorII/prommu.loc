$(function(){
	var arInputs = $('.os-vacancies__item-input'),
		arLabels = $('.os-vacancies__item'),
		$button = $('.outstaffing-service__btn'),
		arServices = $('.os-services__item input'),
		arContacts = $('.os__contact-input');
		bAllTrue = false,
		bAllFalse = false;
	//
	arInputs.change(function(){
		var $this = $(this),
			$parent = $(this).parent('.os-vacancies__item'),
		bAllTrue = false;
		bAllFalse = false;
		$this.is(':checked') ? $parent.addClass('active') : $parent.removeClass('active');	
		$.each(arInputs, function(){ $(this).is(':checked') ? bAllTrue=true : bAllFalse=true });
		!bAllFalse ? $('#choose-all-v').prop('checked',true) : $('#choose-all-v').prop('checked',false);
		//bAllTrue ? $button.fadeIn() : $button.fadeOut();
		if(bAllTrue){
			$('.service-block').fadeIn();
		}
		else{
			$('.service-block').fadeOut();
		}
	});
	//
	//
	//
	arServices.change(function(){
		var $this = $(this),
			bAllTrue = false,
			bAllFalse = false;
		$.each(arServices, function(){ $(this).is(':checked') ? bAllTrue=true : bAllFalse=true });
		!bAllFalse ? $('#choose-all-s').prop('checked',true) : $('#choose-all-s').prop('checked',false);
		bAllTrue ? $button.fadeIn() : $button.fadeOut();
	});
	//
	//
	//
	arContacts.change(function(){
		var bChecked = false;
		$.each(arContacts, function(){ 
			if($(this).is(':checked'))
				bChecked=true;
		});
		bChecked ? $button.fadeIn() : $button.fadeOut();
	});
	//
	$('.os__contact-textarea').keyup(function(){
		if($(this).val()!=''){
			$('#os-other').prop('checked', true);
			$button.fadeIn()
		}
		else{
			$('#os-other').prop('checked', false);
			$button.fadeOut()			
		}
	});
	//
	//
	//
	$('#choose-all-v').change(function(){
		if($(this).is(':checked')){
			$.each(arInputs, function(){ $(this).prop('checked', true) });
			$.each(arLabels, function(){ $(this).addClass('active') });
			bAllTrue = true;
			bAllFalse = false;
		}
		else{
			$.each(arInputs, function(){ $(this).prop('checked', false) });
			$.each(arLabels, function(){ $(this).removeClass('active') });
			bAllTrue = false;
			bAllFalse = true;
		}
		//bAllTrue ? $button.fadeIn() : $button.fadeOut();
		bAllTrue ? $('.service-block').fadeIn() : $('.service-block').fadeOut();
	});
	//
	$('#choose-all-s').change(function(){
		if($(this).is(':checked')){
			$.each(arServices, function(){ $(this).prop('checked', true) });
			bAllTrue = true;
			bAllFalse = false;
		}
		else{
			$.each(arServices, function(){ $(this).prop('checked', false) });
			bAllTrue = false;
			bAllFalse = true;
		}
		bAllTrue ? $button.fadeIn() : $button.fadeOut();
	});
});