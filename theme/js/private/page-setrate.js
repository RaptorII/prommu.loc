'use strict'
$(function(){
	// tab evant
	$('.rai__b-subtitle').click(function(){
		let parent = $(this).closest('.rai__b-tab'),
				content = $(this).siblings('.rai__b-tab-content');

		if($(parent).hasClass('enable'))
		{
			$(content).fadeOut();
			setTimeout(function(){ $(parent).removeClass('enable') },500);
		}
		else
		{
			$(content).fadeIn();
			setTimeout(function(){ $(parent).addClass('enable') },500);
		}
	});
	//
	// events
	//
	// rating
	$('.rai-table__cnt input').change(function(){
		checkRating(false);
	});
	// submit
	$('#send_rating').click(function(e){
		e.preventDefault();
		let rating = checkRating(true),
				review = checkReview(1),
				reviewPromu = checkReview(2),
				arErrors = $('#form_rating .error');

		if(!rating && !review && !reviewPromu)
		{
			console.log(0);
			$('.rai__b-tab').addClass('enable');
			$('.rai__b-tab-content').fadeIn();
			return false;
		}
		if(arErrors.length)
		{
			return false;
		}
		$('#form_rating').submit();
	});
	// input in textarea
	$('.rai__review-area').on('input',function(){
		let v = $(this).val().trim();
		!v.length ? $(this).addClass('error') : $(this).removeClass('error');
	});
	// blur of textarea
	$('.rai__review-area').on('blur',function(){
		let v = $(this).val().trim();
		$(this).val(v);
	});
	//
	//	functions
	//
	function checkRating(isSendForm)
	{
		let arRate = $('.rai__table input'),
				tab = $('.rai__b-tab:eq(0)'),
				arRows = $('.rai__table tr'),
				arCheckedRows = [],
				cnt = 0;

		$.each(arRate, function(i,e){
			$(this).is(':checked') && arCheckedRows.push(Math.floor(i/3));
		});

		if(!arCheckedRows.length) // ничего не чекалось
		{
			$.each(arRows, function(){
				$(this).removeClass('error');
			});
			return false;
		}
		else if(arRows.length!=arCheckedRows.length) // если что-то чекнуто
		{
			if(isSendForm==true) // показываем ошибки только если отправляется форма
			{
				$.each(arRows,function(i,e){
					$.inArray(i, arCheckedRows)<0
						? $(this).addClass('error')
						: $(this).removeClass('error');
				});
				$(tab).addClass('enable');
				$(tab).find('.rai__b-tab-content').fadeIn();
			}
			else
			{
				$.each(arRows,function(i,e){
					$.inArray(i, arCheckedRows)>=0 && $(this).removeClass('error');
				});
			}
			return true;
		}
		else if(arRows.length==arCheckedRows.length) // если все чекнуто
		{
			$.each(arRows,function(i,e){
				$.inArray(i, arCheckedRows)>=0 && $(this).removeClass('error');
			});
			return true;
		}
		return false;
	}

	function checkReview(num)
	{
		let tab = $('.rai__b-tab:eq('+num+')'),
				arRadio = $(tab).find('input'),
				arLabel = $(tab).find('label'),
				area = $(tab).find('textarea'),
				length = $(area).val().trim().length;

		if($(arRadio[0]).is(':checked') || $(arRadio[1]).is(':checked'))
		{
			$(arLabel).removeClass('error');
			if(!length)
			{
				$(area).addClass('error');
				$(tab).addClass('enable');
				$(tab).find('.rai__b-tab-content').fadeIn();
			}
		}
		else if(length)
		{
			$(area).removeClass('error');
			if(!$(arRadio[0]).is(':checked') && !$(arRadio[1]).is(':checked'))
			{
				$(arLabel).addClass('error');
				$(tab).addClass('enable');
				$(tab).find('.rai__b-tab-content').fadeIn();
			}
		}

		if(!$(arRadio[0]).is(':checked') && !$(arRadio[1]).is(':checked') && !length)
		{
			return false;
		}
		return true;
	}
});