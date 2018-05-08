$(function(){
	var AJAX_EMPL_PATH = '/searchempl',
		$form = $('#F1Filter'),
		$content = $('#content'),
		$load = $('.pse__veil'),
		addH1 = '#pse-seo-h1',
		addText = '#pse-seo-text',
		citySelect = '#pse-cities',
		oldUrl = document.location.pathname + document.location.search;

	// следим за изменением урла
	var urlTimer = setInterval(function(){ checkUrl() }, 500);
	// подгрузка данных для городов
	$(citySelect).select2();
	$form.on('change', citySelect, function(){ getVacanciesAjax() });
	var selectTimer = setInterval(function(){ checkSelect() }, 100);

	// подгрузка данных для типа
	$form.on('change', '.filter-type input', function(){
		var arTypesInp = $('.filter-type input');

		if($(this).is(arTypesInp[0])){
			if($(this).is(':checked'))
				for(var i=1; i<arTypesInp.length; i++)
					$(arTypesInp[i]).prop('checked', true);
			else
				for(var i=1; i<arTypesInp.length; i++)
					$(arTypesInp[i]).prop('checked', false);
		}
		setTimeout(function(){ getVacanciesAjax() }, 300);
	});
	
	$(document).click(function(e){
		// подгрузка данных для текстовых полей
		if($(e.target).hasClass('pse__filter-btn'))
			setTimeout(function(){ getVacanciesAjax() }, 300);
		else if($(e.target).hasClass('pse__filter-name')){
			var $it = $(e.target);
			if($it.hasClass('opened')){
				$it.siblings('.pse__filter-content').slideUp(200);
				setTimeout(function(){
					$it.removeClass('opened');
					$it.siblings('.pse__filter-content').removeClass('opened');
				},200);
			}
			else{
				$it.addClass('opened');
				$it.siblings('.pse__filter-content').slideDown(500);
				$it.siblings('.pse__filter-content').addClass('opened');
			}			
		}
	});
	// подгрузка данных при перелистывании
	$('#content').on('click', '.paging-wrapp a', function(e){ getVacanciesAjax(e) });
	//
	//		ФУНКЦИИ
	//
	function getVacanciesAjax(e=false, url=''){
		var params = $form.serialize(),
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
			document.location.href='http://spb.prommu.com/searchempl'+strP;


		if(e){
			e.preventDefault();
			params = e.target.href.slice(e.target.href.indexOf(AJAX_EMPL_PATH+'?') + 12);// вырезаем GET
		}
		if(url!=''){	// если урл изменился посредством нажатия кнопок "НАЗАД" или "ВПЕРЕД"
			urlString = url;
			params = url.slice(url.indexOf(AJAX_EMPL_PATH+'?') + 12);// вырезаем GET
			arParams = [];
			var hashes = params.split('&');
			for(var i = 0; i < hashes.length; i++){
				hash = hashes[i].split('=');
				arParams.push({'name':hash[0],'value':hash[1]});
			}
			clearInterval(urlTimer);
		}
		else{
			urlString = AJAX_EMPL_PATH + '?' + params;
		}
		
		$load.show();	

		$.ajax({
			type: 'GET',
			url: AJAX_EMPL_PATH,
			data: params,
			success: function(res){
				// add new list
				$content.html(res);
				// add new seo
				if(arSeo != null){
					typeof arSeo.meta_title != 'undefined' ? document.title=arSeo.meta_title : document.title="Поиск работодателей";
					if($('*').is(addH1)) typeof arSeo.seo_h1 != 'undefined' ? $(addH1).text(arSeo.seo_h1) : $(addH1).text('');
					typeof arSeo.meta_keywords != 'undefined' ? $(addText).html(arSeo.meta_keywords) : $(addText).html('');
				}
				else{
					document.title="Поиск работодателей";
					$(addH1).text('');
					$(addText).html('');
				}
				// add new url
				window.history.pushState("object or string", "page name",urlString);
				oldUrl = urlString;
				// scroll if event - next page
				if(e) $('html, body').animate({ scrollTop: $content.offset().top - 100 }, 700);//прокручиваем к заголовку
				// render filter
				//getFilter(arParams);
				$load.hide();
			}
		});

	}
	function getFilter(arParams){
		$.ajax({
			type:'POST',
			url : AJAX_EMPL_PATH,
			data: 'all='+ JSON.stringify(arAllData) + 
				'&new=' + JSON.stringify(arNewData) + 
				'&get=' + JSON.stringify(arParams),
			success: function(res){ 
				$form.html(res);
				$('body .select2-container--default.select2-container--open').remove();
				$(citySelect).select2();
				var selectTimer = setInterval(function(){ checkSelect() }, 100);
				$load.hide();
				var urlTimer = setInterval(function(){ checkUrl() }, 500);
			}
		});
	}
	function checkSelect(){	
		if($('*').is('.filter-cities .select2')){
			$('.filter-cities .pse__filter-content').addClass('active');
			clearInterval(selectTimer);
		}
	}
	function checkUrl(){	
		var newUrl = document.location.pathname + document.location.search;
		if(newUrl!==oldUrl){
			oldUrl = newUrl;
			getVacanciesAjax(false, newUrl);
		}
	}
	//
	//
	//
	var $f  = $('#F1Filter');
	$(window).on('load resize',function(){
		if($(window).width() < '768')
			$('.pse__filter-vis').hasClass('active') ? $f.show() : $f.hide(); 
		else
			$f.show();
	});
	$('.pse__filter-vis').click(function(){
		$(this).hasClass('active') ? $f.fadeOut() : $f.fadeIn();
		$(this).toggleClass('active');
	});
});