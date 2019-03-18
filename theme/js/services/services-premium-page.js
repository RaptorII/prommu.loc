$(function(){
	var arInputs = $('.ps-vacancies__item-input'),
		arLabels = $('.ps-vacancies__item'),
		$button = $('.service__btn'),
		$all = $('#choose-all'),
		bAllTrue = false,
		bAllFalse = false;

	arInputs.change(function(){
		var $this = $(this),
			$parent = $(this).parent('.ps-vacancies__item'),
		bAllTrue = false;
		bAllFalse = false;
		$this.is(':checked') ? $parent.addClass('active') : $parent.removeClass('active');	
		$.each(arInputs, function(){ $(this).is(':checked') ? bAllTrue=true : bAllFalse=true });
		!bAllFalse ? $all.prop('checked',true) : $all.prop('checked',false);
		bAllTrue ? $button.fadeIn() : $button.fadeOut();				
	});
	//
	$all.change(function(){
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
		bAllTrue ? $button.fadeIn() : $button.fadeOut();
	});
});