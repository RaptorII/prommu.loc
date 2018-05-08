$(function(){
	var $form = $('#F1Filter'),
		$content = $('#content'),
		arPostsBox = $('.filter-dolj .psv__checkbox-input'),
		$load = $('.psv__veil'),
		arSalary = $('.psv__salary .psv__input'),
		$salaryType = $('#psv-salary-type'),
		addData = '#psv-additional',
		addH1 = '#psv-seo-h1',
		addText = '#psv-seo-text';

	$(".templatingSelect2").select2();

	var selectTimer = setInterval(function(){ checkSelect() }, 100);

	// подгрузка данных для городов
	$('#vac-srch-cities').change(function(){ getVacanciesAjax() });
	// подгрузка данных для должностей
	$('.filter-dolj .psv__checkbox-input').change(function(){
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
	// подгрузка данных для занятости, пола, смарта и карт
	$('.filter-busy input, .filter-sex input, .filter-smart input, .filter-card input').change(function(){ 
		setTimeout(function(){ getVacanciesAjax() }, 300); 
	});
	// подгрузка данных для ЗП
	arSalary.focus(function(){
		var sr = 1;
		for(var i=0; i<arSalary.length; i++)
			if($(this).is(arSalary[i]))
				sr = (i>5 ? 4 : (i<4 ? (i>1 ? 2 : 1) : 3));

		for(var i=0; i<arSalary.length; i++)
			if(
				( sr==1 && (i!=0 && i!=1) ) ||
				( sr==2 && (i!=2 && i!=3) ) ||
				( sr==3 && (i!=4 && i!=5) ) ||
				( sr==4 && (i!=6 && i!=7) )
			)
				$(arSalary[i]).val('');

		$salaryType.val(sr);
	});
	// подгрузка данных для текстовых полей
	$('.psv__filter-btn').click(function(){
		setTimeout(function(){ getVacanciesAjax() }, 300);
	});
	// подгрузка данных при перелистывании
	$('#content').on('click', '.paging-wrapp a', function(e){ getVacanciesAjax(e) });
	// подгрузка данных при изменении вида
	$('#content').on('click', '.psv__view-block a', function(e){ getVacanciesAjax(e) });

	$('.more-posts').click(function(){
		$(this).closest('.filter-content').css({'height':'788px'});
		$(this).fadeOut();
	});

	function getVacanciesAjax(e=false){
		var AJAX_GET_VACANCIES = '/vacancy',
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
			document.location.href='http://spb.prommu.com/vacancy'+strP;


		if(e){
			e.preventDefault();
			params = e.target.href.slice(e.target.href.indexOf('/vacancy?') + 9);
		}
		
		$load.show();
		urlString = AJAX_GET_VACANCIES + '?' + params;

		$.ajax({
			type: 'GET',
			url: AJAX_GET_VACANCIES,
			data: params,
			success: function(res){	
				$content.html(res);
	
				if(arSeo.url!=null) urlString = arSeo.url;
				arSeo.seo_h1!=null 
				? $('h1').text(arSeo.seo_h1) 
				: $('h1').text('Поиск вакансий');

				arSeo.meta_keywords!=null 
				? $(addText).html(arSeo.meta_keywords) 
				: $(addText).html('');

				arSeo.meta_title!=null 
				? document.title=arSeo.meta_title 
				: document.title='Поиск вакансий';

				window.history.pushState("object or string", "page name",urlString);
				if(e) $('html, body').animate({ scrollTop: $content.offset().top - 100 }, 700);
				$load.hide();
			}
		});
	}
	function checkSelect(){	
		if($('*').is('.filter-cities .select2')){
			$('.filter-cities .filter-content').addClass('active');
			clearInterval(selectTimer);
		}	
	}
	//
	//
	//
	var $f  = $('#F1Filter');
	$(window).on('load resize',function(){
		if($(window).width() < '768')
			$('.psv__filter-vis').hasClass('active') ? $f.show() : $f.hide(); 
		else
			$f.show();
	});
	$('.psv__filter-vis').click(function(){
		$(this).hasClass('active') ? $f.fadeOut() : $f.fadeIn();
		$(this).toggleClass('active');
	});
});