$(function(){
	var $form = $('#F1Filter'),
		$content = $('#content'),
		$load = $('.psa__veil'),
		addH1 = '#psa-seo-h1',
		addText = '#psa-seo-text',
		arPostsBox = $('.filter-positions input');

	// подгрузка данных для городов
	$('.templatingSelect2').select2();
	$('#ank-srch-cities').change(function(){ getVacanciesAjax() });
	var selectTimer = setInterval(function(){ checkSelect() }, 100);

	// вкладки фильтра
	$('.psa__filter-name').click(function(){
		var $it = $(this);
		if($it.hasClass('opened')){
			$it.siblings('.psa__filter-content').slideUp(200);
			setTimeout(function(){
				$it.removeClass('opened');
				$it.siblings('.psa__filter-content').removeClass('opened');
			},200);

		}
		else{
			$it.addClass('opened');
			$it.siblings('.psa__filter-content').slideDown(500);
			$it.siblings('.psa__filter-content').addClass('opened');
		}
	});
	// подгрузка данных для должности
	$('.filter-positions input').change(function(){
		if($(this).is(arPostsBox[0])){
			if($(this).is(':checked'))
				for(var i=1; i<arPostsBox.length; i++)
					$(arPostsBox[i]).prop('checked', true);
			else
				for(var i=1; i<arPostsBox.length; i++)
					$(arPostsBox[i]).prop('checked', false);
		}
		setTimeout(function(){ getVacanciesAjax() }, 300);
	});
	// подгрузка данных для текстовых полей
	$('.psa__filter-btn').click(function(){
		setTimeout(function(){ getVacanciesAjax() }, 300);
	});
	// подгрузка данных для пола, и дополнительно
	$('.filter-sex input, .filter-additional input, .filter-card input').change(function(){ 
		setTimeout(function(){ getVacanciesAjax() }, 300); 
	});
	// подгрузка данных при перелистывании
	$('#content').on('click', '.paging-wrapp a', function(e){ getVacanciesAjax(e) });
	// подгрузка данных при изменении вида
	$('#content').on('click', '.psa__view-block a', function(e){ getVacanciesAjax(e) });
	//	нужно больше вакансий
	$('.more-posts').click(function(){
		$(this).closest('.psa__filter-content').css({'height':'693px'});
		$(this).fadeOut();
	});
	//
	//		ФУНКЦИИ
	//
	function getVacanciesAjax(e=false){
		var AJAX_GET_PROMO = '/ankety',
			params = $form.serialize(),
			arParams = $form.serializeArray(),
			flagR = false,
			strP = '';

		//redirect to SPB
		$.each(arParams, function(i, e){
			if(e.name=='cities[]'){
				if(e.value=='1838') 
					flagR = true; // id SPB
			}
			else if(e.value!='')
				strP += ((strP=='')?'?':'&') + e.name + '=' + e.value;
		});
		if(flagR)
			document.location.href='http://spb.prommu.com/ankety'+strP;


		if(e){
			e.preventDefault();
			params = e.target.href.slice(e.target.href.indexOf('/ankety?') + 8);// вырезаем GET
		}
		
		$load.show();
		urlString = AJAX_GET_PROMO + '?' + params;

		$.ajax({
			type: 'GET',
			url: AJAX_GET_PROMO,
			data: params,
			success: function(res){
				$content.html(res);

				if(arSeo.url!=null) urlString = arSeo.url;
				arSeo.seo_h1!=null 
				? $('h1').text(arSeo.seo_h1) 
				: $('h1').text('Поиск соискателей');

				arSeo.meta_keywords!=null 
				? $(addText).html(arSeo.meta_keywords) 
				: $(addText).html('');

				arSeo.meta_title!=null 
				? document.title=arSeo.meta_title 
				: document.title='Поиск соискателей';

				window.history.pushState("object or string", "page name",urlString);
				if(e)
					$('html, body').animate({ scrollTop: $content.offset().top - 100 }, 700);//прокручиваем к заголовку
				$load.hide();
			}
		});
	}
	function checkSelect(){	
		if($('*').is('.filter-cities .select2')){
			$('.filter-cities .psa__filter-content').addClass('active');
			clearInterval(selectTimer);	
		}
	}
	//
	//
	//
	var $f  = $('#F1Filter');
	$(window).on('load resize',function(){
		if($(window).width() < '768')
			$('.psa__filter-vis').hasClass('active') ? $f.show() : $f.hide(); 
		else
			$f.show();
	});
	$('.psa__filter-vis').click(function(){
		$(this).hasClass('active') ? $f.fadeOut() : $f.fadeIn();
		$(this).toggleClass('active');
	});
});