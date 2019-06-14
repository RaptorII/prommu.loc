jQuery(function($){
	var strYear = 4, // год - 4 цифры
		strAbout = 2000, // ограничение для поля "О себе"
		hwLen = 3, // Вес-рост - 3 цифры
		phoneLen = 10, // нормальное кол-во цифр в телефоне
		curDate = new Date(),
		curYear = curDate.getFullYear(),
		curDay = Number(curDate.getDate()),
		bYear = Number($('#epa-byear').val()),
		bMonth = Number($('#epa-bmonth').val())-1,
		bDay = Number($('#epa-bday').val()),
		oldEmail = $('#epa-email').val(),
		cityM = '#city-module',
		cntctM = '#contacts-module',
		mainM = '#main-module',
		emailTimer = null,
		arSelectPhones = [],
		arErrorsFields = [];
		arIdCities = [];
		arNewPosts = [];
		arSelectMetroes = [];
		arSelect = ['messenger',
				'hcolor',
				'hlen',
				'ycolor',
				'chest',
				'waist',
				'thigh',
            	//'posts', // list of vacancy <ul id="epa-list-posts"> in
				'education',
				'language'];
		cropOptions = {};
		cropperObj = null,
		oldPhone = $('#phone-code').val(),
		oldFlag = '',
		keyCode = false,
		confirmEmail = $('#conf-email').hasClass('complete') ? true : false;
		confirmPhone = $('#conf-phone').hasClass('complete') ? true : false;

	$(document).keydown(function(e){ keyCode = e.keyCode });

	curDate.setFullYear(curYear-14); // ограничение по возрасту от 14 лет
	// прокрутка по содержанию
	$('.epa__logo-name').click(function(){
		var num = $(this).index();
		$('html, body').animate({ scrollTop: $('.epa__content-title:eq('+num+')').offset().top-20 }, 1000);
	});
	// LOCATION
	//	Собираем массив уже выбраных городов
	$.each($(cityM+' .epa__city-item'), function(){
		if(this.dataset.id!='')
			arIdCities.push(this.dataset.idcity);
	});
	// собираем выбранные метро
	$.each($(cityM+' .epa__metro-item [type=hidden]'), function(){
		arSelectMetroes.push($(this).val());
	});
	// собираем выбранные телефоны
	updateArSelectPhones();
	//
	$(document).on('click', function(e){
		var it = e.target;
		// select
		for(var i=0; i<arSelect.length; i++){
			var veil = 'epa-veil-' + arSelect[i],
				list = '#epa-list-' + arSelect[i],
				btn = '#epa-list-' + arSelect[i] + ' i';

			if(it.id == veil)
				$(list).fadeIn();
			else if($(it).is(btn) || !$(it).closest(list).length)
				$(list).fadeOut();
		}
		// single post select	
		if($(it).hasClass('epa__post-veil')){
			var list = $(it).siblings('.epa__post-list');
			$(list).fadeIn();
		}
		else if($(it).is('.epa__post-btn') || !$(it).closest('.epa__post-list').length)
			$('.epa__post-list').fadeOut();
	});
	// события выбора мессенджера
	$('#epa-list-messenger input').on('change', function(){
		var arInputs = $('#epa-list-messenger input'),
			arMess = [];
			showHint = false;
		$.each(arInputs, function(){
			mess = $(this).data('mess');
			if($(this).is(':checked')){
				arMess.push($(this).siblings('label').text());
				$('.epa__mess-'+mess).removeClass('off');
				showHint = true;
			}
			else{
				$('.epa__mess-'+mess).addClass('off');
				$('.epa__mess-'+mess+' input').val('');
			}
		});
		$('#epa-str-messenger').val(arMess);
		showHint ? $('.epa__mess-hint').removeClass('off') : $('.epa__mess-hint').addClass('off');
	});
	// события изменения внешности
	$('#epa-list-hcolor input').on('change', function(){ changeRadio('hcolor') }); 
	$('#epa-list-hlen input').on('change', function(){ changeRadio('hlen') }); 
	$('#epa-list-ycolor input').on('change', function(){ changeRadio('ycolor') }); 
	$('#epa-list-chest input').on('change', function(){ changeRadio('chest') }); 
	$('#epa-list-waist input').on('change', function(){ changeRadio('waist') }); 
	$('#epa-list-thigh input').on('change', function(){ changeRadio('thigh') });
	// ввода данных роста и веса
	$('#epa-height, #epa-weight').keyup(function(){
		var val = getNum($(this).val());
		$(this).val(val.substr(0,hwLen));
	});
	// событие изменения образования
	$('#epa-list-education input').on('change', function(){ changeRadio('education') });
	// события выбора языка
	$('#epa-list-language input').on('change', function(){
		var arInputs = $('#epa-list-language input'),
		arLang = [];
		$.each(arInputs, function(){
			if($(this).is(':checked'))
				arLang.push($(this).siblings('label').text());
		});
		$('#epa-str-language').val(arLang);
	});
	// события выбора вакансии
	$('#epa-list-posts').on('change', 'input', function(e){
		var arInputs = $('#epa-list-posts [name="donjnost[]"]:checked');
		// нельзя удалять послeднюю должность
		if(!arInputs.length) {
			var el = e.target.nextElementSibling;
			confirm('Должна быть установлена хотя бы одна вакансия');
			setTimeout(function(){ $(el).click() },10);
			return false;
		}
		checkPosts();
	});
	//
	$('.epa__post-detail').on('input', '[type=text]', function(){ checkField(this) });
	// создать новую должнотсть
	$('#epa-posts-add span').click(function(){
		$('#epa-posts-add').hide();
		$('#epa-posts-save').show();
		$('#epa-posts-save input').focus();
	});
	// сохранить новую должность
	$('#epa-posts-save span').click(function(){
		var val = $('#epa-posts-save input').val();
		if(val!==''){
			id = randomInt();
			html = 	'<li>'+
						'<input type="checkbox" name="donjnost[]" value="'+id+'" data-name="'+val+'" id="epa-post-'+id+'" checked>'+
						'<label for="epa-post-'+id+'">'+val+'<b></b></label>'+
					'</li>';
			$('#epa-posts-add').before(html);
			checkPosts(id);
			$('#epa-posts-add').show();
			$('#epa-posts-save').hide();
			$('#epa-posts-save input').val('');
		}
	});
	// установка параметров должности
	$('.epa__post-detail').on('change', '[type=radio]', function(e){
		var newVal = $(e.target.nextElementSibling).text(),
			list = $(e.target).closest('ul');
			input = $(list).siblings('input');

		$(input).val(newVal);
		$(list).fadeOut();
	});
	// удаление должности
	$('.epa__post-detail').on('click', '.epa__post-close', function(){
		var id = $(this).closest('.epa__post-block').data('id'),
			arInputs = $('#epa-list-posts').find('input'),
			$it = 0,
			checked = 0;

		$.each(arInputs, function(){
			if($(this).val()==id) $it = $(this);
			if($(this).is(':checked')) checked++;
		});

		if($it!=0 && checked>1){
			$it.attr('checked', false);
			checkPosts();		
		}
		else{
			confirm('Должна быть установлена хотя бы одна вакансия');
		}
	});
	// ввода данных 'О себе'
	$('.epa__textarea').keyup(function(){
		var val = $(this).val();
		if(val.length>strAbout)
			$(this).val(val.substr(0,strAbout));
	});
	//	устанавливаем маски
	$("#city-module .epa__period .epa__input").on(
			'input',
			function(){ checkPeriod(this) }
		);

	function checkPeriod (e){
		var v = e.value,
				l = v.length;

		if(keyCode==8) { //backspace
			if(l==8 || l==7) {
				var arV = v.split(' до ');
				if(arV[0]===v)
					v = 'С ' + getNum(v).substr(0,2);
				else if(!getNum(arV[1]).length)
					v = 'С ' + getNum(v);
			}
			if(l==2)
				v = '';
		}
		else {
			if(!getNum(v).length)
				v = 'С ';
			if(l==1 && getNum(v).length==1) 
				v = 'С ' + getNum(v);
			if(l==4 && getNum(v).length==2 )
				v = 'С ' + getNum(v) + ' до ';
			if(l==4 && getNum(v).length==1)
				v = (v.substr(-1)==' ' ? 'С ' + getNum(v) + ' до ' : 'С ' + getNum(v));
			if(l==5) {
				var s = v.substr(-1);
				if(getNum(s).length==1) 
					v = 'С ' + getNum(v).substr(0,2) + ' до ' + s;
				else if(s==' ') 
					v = 'С ' + getNum(v) + ' до ';
				else 
					v = 'С ' + getNum(v);
			}
			if(l>=7) {
				var arV = v.split(' до ');
				if(arV[0]===v) {
					v = 'С ' + getNum(v).substr(0,2) + ' до ' + getNum(v).substr(2,4);
				}
				else {
					v = 'С ' + getNum(arV[0]).substr(0,2) + ' до ' + getNum(arV[1]).substr(0,2);
				}
			}
		}

		e.value = v;
	}
	//
	//  блок для создания города
	//
	$('.epa__add-city-btn').on('click', function(){
		$('.epa__cities-block-list').append($('#add-city-content').html());
		var main = $(cityM).find('.epa__city-item:eq(-1)');

		$('html, body').animate({ scrollTop: $(main).offset().top-20 }, 1000);
	});
	//
	//  Ввод города
	//
	$(cityM).on('focus', '.city-input', function(){ findCities(this) });
	//
	$(cityM).on('keyup', '.city-input', function(){
		var main = $(this).closest('.epa__city-item'),
			cityError = $(this).siblings('.epa__city-err'),
			cityLabel = $(this).closest('.epa__label'),
			res = verificationCities($(this).val(), main[0].dataset.idcity);

		if(res.error == 2){ // проверяем только совпадение ID
			addErr(cityError);
			addErr(cityLabel);
		}
		else{
			remErr(cityError);
			remErr(cityLabel);
			main[0].dataset.idcity = res.id!='' ? res.id : main[0].dataset.idcity;
		}
		findCities(this);
	});
	//
	$(cityM).on('blur', '.city-input', function(){
		var main = $(this).closest('.epa__city-item'),
			cityError = $(this).siblings('.epa__city-err'),
			cityLabel = $(this).closest('.epa__label'),
			res = verificationCities($(this).val(), main[0].dataset.idcity);

		if(res.error){
			if(res.error==2) addErr(cityError);
			addErr(cityLabel);
		}
		else{
			remErr(cityError);
			remErr(cityLabel);
			main[0].dataset.idcity = res.id!='' ? res.id : main[0].dataset.idcity;
			checkSelectCity(main);
			checkAvailabilityMetro(main);
		}
	});
	//  выбор города из списка
	$(cityM).on('click', '.city-list li', function(){
		var main = $(this).closest('.epa__city-item'),
			cityList = $(this).closest('.city-list'),
			cityError = $(cityList).siblings('.epa__city-err'),
			cityInput = $(cityList).siblings('.city-input'),
			cityLabel = $(this).closest('.epa__label');

		if(!$(this).hasClass('emp')){
			$(cityInput).val($(this).text());
			var res = verificationCities($(this).text(), main[0].dataset.idcity);

			if(res.error==2){ // проверяем только совпадение ID
				addErr(cityError);
				addErr(cityLabel);
			}
			else{
				$(cityList).fadeOut();
				remErr(cityError);
				remErr(cityLabel);
				main[0].dataset.idcity = res.id!='' ? res.id : main[0].dataset.idcity;
				checkSelectCity(main);
				checkAvailabilityMetro(main);
			}     
		}
		else{ addErr(cityLabel) }
	});
	//  закрываем список городов
	$(document).click(function(e){
		if(!$(e.target).closest('.city-list').length){
			if($('.city-input').is(e.target)){// закрываем ненужные списки
					$.each($('.city-list'), function(){
					var input = $(this).siblings('.city-input');
					if(!$(input).is(e.target)) 
						$(this).fadeOut();
				});
			}
			else{ $('.city-list').fadeOut() }
		}
	});
	// удалить город
	$(cityM).on('click', '.epa__city-del', function(){
		var main = $(this).closest('.epa__city-item'),
			arNames = $('.epa__cities-list b'),
			idCity = main[0].dataset.idcity,
			name = arCities[idCity];
			num = -1;
		
		$.each(arNames, function(){ if($(this).text()==name) num=$(this).index() });
		$(arNames[num]).remove(); // удалили зеленое название

		arIdCities.splice(arIdCities.indexOf(idCity),1); // убираем город из массива выбранных
		main.remove();
	});
	//
	//  Ввод метро
	//
	$(cityM).on('focus', '.metro-input', function(){ findMetroes(this) });
	//
	$(cityM).on('keyup', '.metro-input', function(){ 
		var $it = $(this),
			find = -1,
			value = $it.val(),
			main = $it.closest('.epa__city-item'),
			metroLabel = $it.closest('.epa__label');

		$.each(arMetroes[main[0].dataset.idcity], function(i, metro){
			if(value.toLowerCase()==metro.toLowerCase()){
				find = true;
				selectMetro($it, i);
			} 
		});
		(find<0 && value!='') ? addErr(metroLabel) : remErr(metroLabel);
		findMetroes(this);
	});
	//
	$(cityM).on('blur', '.metro-input', function(){
		var $it = $(this),
			find = -1,
			value = $it.val(),
			main = $it.closest('.epa__city-item'),
			metroLabel = $it.closest('.epa__label');

		$.each(arMetroes[main[0].dataset.idcity], function(i, metro){
			if(value.toLowerCase()==metro.toLowerCase()){
				find = i;
				selectMetro($it, i);
			}
		});
		(find<0 && value!='') ? addErr(metroLabel) : remErr(metroLabel);
	});
	//  выбор метро из списка
	$(cityM).on('click', '.metro-list li', function(){
		var $it = $(this),
			metroList = $it.closest('.metro-list'),
			metroInput = $(metroList).siblings('.metro-input'),
			metroLabel = $it.closest('.epa__label');

		if(!$it.hasClass('emp')){
			$(metroInput).val($it.text());
			metroList.fadeOut();
			remErr(metroLabel);
			selectMetro(metroInput, $it.data('val'));
		}
		else{ addErr(metroLabel) }  
	});
	//  закрываем список метро
	$(document).click(function(e){
		if(!$(e.target).closest('.metro-list').length){
			if($('.metro-input').is(e.target)){
				// закрываем ненужные списки
				$.each($('.metro-list'), function(){
					var input = $(this).siblings('.metro-input');
					if(!$(input).is(e.target)) $(this).fadeOut();
				});
			}
			else{ $('.metro-list').fadeOut() }
		}
	});
	// удаление метро
	$(cityM).on('click', '.epa__metro-close', function(){
		var metroItem = $(this).closest('.epa__metro-item'),
			id = $(this).siblings('[type=hidden]').val();

		arSelectMetroes.splice(arSelectMetroes.indexOf(id), 1); // удалили из массива выбранных метро
		metroItem.remove();
	});
	//
	//		Ввод телефона
	//
	$(document).on('click',function(e){ checkPhone(e) });
	$('#phone-code').on('input',function(e){ checkPhone(e) });
	//$(cntctM).on('focus', '.phone-input', function(){ findPhones(this) });  // рлеп выкдючили поиск телефона
	//$(cntctM).on('keyup', '.phone-input', function(){ findPhones(this) });
	//  выбор телефона из списка
	$(cntctM).on('click', '.phone-list li', function(){
		var phoneList = $(this).closest('.phone-list'),
			phoneInput = $(phoneList).siblings('.phone-input');

		$(phoneInput).val($(this).text());
		phoneList.fadeOut();
	});
	//  закрываем список телефонов
	$(document).click(function(e){
		if(!$(e.target).closest('.phone-list').length){
			if($('.phone-input').is(e.target)){		
				$.each($('.phone-list'), function(){// закрываем ненужные списки
					var input = $(this).siblings('.phone-input');
					if(!$(input).is(e.target)) $(this).fadeOut();
				});
			}
			else{ $('.phone-list').fadeOut() }
		}
	});
	//
	//
	// добавление/удаление периода
	$(cityM).on('change', '.epa__day-input', function(){
		var main = $(this).closest('.epa__city-item'),
			perList = $(main).find('.epa__period-list'),
			content = $('#add-day-period').html(),
			idDay = $(this).val(),
			dayName = $(this).data('day'),
			idCity = main[0].dataset.idcity,
			prnt = $(this).closest('.epa__days-checkboxes');

		if($(this).is(':checked')){
			content = content.replace(/NEWDAY/g, idDay);
			content = content.replace(/NEWID/g, idCity);
			$(perList).append(content);
			var item = $(perList).find('.epa__period:eq(-1)');
			
			$(item).find('i').text(dayName);
			$(item).find('.epa__input').on('input',function(){
				checkPeriod(this);
			});

            $.each($(item).find('.epa__input'),function(){
                checkField(this);
            });

		}
		else{
			var arItems = $(perList).find('.epa__period');
			$.each(arItems, function(){ 
				if($(this).data('id')==idDay) 
					$(this).remove(); 
			});
		}
		var checked = false,
				arL = $(prnt).find('.epa__checkbox');
		$.each($(prnt).find('.epa__day-input'), function(){
			if($(this).is(':checked')) checked = true;
		});
		checked
		? $.each(arL, function(){ remErr(this) })
		: $.each(arL, function(){ addErr(this) });
	});
	// удаление периода
	$(cityM).on('click', '.epa__period-close', function(){
		var main = $(this).closest('.epa__city-item'),
			period = $(this).closest('.epa__period'),
			arDays = $(main).find('.epa__days-checkboxes input'),
			idDay = $(period).data('id');

		$.each(arDays, function(){ if($(this).val()==idDay) $(this).attr('checked',false) });
		period.remove();
	});
	// проверка периода
	$(cityM).on('blur', '.epa__period input', function(){
		var val = $(this).val(),
			label = $(this).closest('.epa__label');

        //addErr(label);

		if(val!=='') {
            arVals = val.split('до');
            if (arVals.length == 1) {
                $(this).val('');
                addErr(label);
            }
            else if (getNum(arVals[0]).length == 0 || getNum(arVals[1]).length == 0) {
                $(this).val('');
                addErr(label);
            }
        }
	});
	//	проверка ввода ЗП
	$('.epa__post-detail').on('keyup', '.epa__payment input', function(){
		var val = getNum($(this).val());
		$(this).val(val);
	});
	//    Проверка полей
	$(mainM).on('keyup','.epa__required',function(){ checkField(this) });	
	$(cityM).on('keyup','.epa__required',function(){ checkField(this) });
	$(cityM).on('change','.epa__required',function(){ checkField(this) });
	$(cntctM).on('keyup','.epa__required',function(){ checkField(this) });
	$(cntctM).on('change','.epa__required',function(){ checkField(this)	});
	$('#epa-mail').keyup(function(){ checkField(this) });
	$('#epa-gmail').change(function(){ checkField(this)	});
	$('#epa-gmail').change(function(){ checkField(this)	});
	$('#epa-gmail').change(function(){ checkField(this)	});
	$('.epa__education [type="radio"], .epa__language [type="checkbox"]')
		.change(function(){ checkField(this) });
	//
	$('.epa__save-btn').click(function(e){
		var self = this,
				epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
				nemail = $('#epa-email').val(),
				resEmail = false,
				errors = false;

		e.preventDefault();

		if(MainScript.isButtonLoading(self))
			return false;
		else
			MainScript.buttonLoading(self,true);

		resEmail = epattern.test(nemail) ? remErr('.epa__email') : addErr('.epa__email');
		$('.epa__email').removeClass('erroremail');

		if(resEmail && nemail!=oldEmail){
			clearTimeout(emailTimer);
			emailTimer = setTimeout(function(){
				$.ajax({
					type: 'POST',
					url: '/ajax/emailVerification',
					data: 'nemail='+nemail+'&oemail='+oldEmail,
					dataType: 'json',
					success: function(res){
						res
						? $('.epa__email').addClass('erroremail error')
						: $('.epa__email').removeClass('erroremail error');

						errorFieldName('#epa-email',res);

						$.each($(cityM+' .epa__required'), function(){
							if(!checkField(this)) errors = true; 
						});
						$.each($(cityM+' .epa__days-checkboxes'), function(){
							var checked = false,
									$p = $(this);

							$.each($p.find('.epa__day-input'), function(){ 
								if($(this).is(':checked')) checked=true;
							});
							if(!checked) {
								$.each($p.find('.epa__checkbox'), function(){ addErr(this) })
								errors = true; 
							}
						});

						if(!checkField('[name="about-mself"]')) errors = true;

						if(!checkField($('[name="user-attribs[edu]"]'))) errors = true;
						if(!checkField($('[name="langs[]"]'))) errors = true;
						$.each($('.epa__post-detail .epa__required'), function(){
							if(!checkField(this)) errors = true; 
						});

						checkPhone({'type':'input'});

						var arErrors = $('.error');
						if(arErrors.length>0)
						{
							MainScript.buttonLoading(self,false);
							$.fancybox.open({
								src: '.prmu__popup',
								type: 'inline',
								touch: false,
								afterClose: function(){
									$('html, body').animate({ scrollTop: $($('.error')[0]).offset().top-20 }, 1000);
								}
							});
						}

						if(!errors && !arErrors.length){
							var arPosts = $('#epa-list-posts input'),
								arCityItems = $('#city-module .epa__city-item'),
								arTimeItems = $('#city-module .epa__period input'),
								addInputs = '';
							$.each(arPosts, function(){ // добавляем массив опыта вакансий
								if($(this).is(':checked'))
									addInputs += '<input type="hidden" name="donjnost-exp[]" value="'+$(this).val()+'">';
							});
							$.each(arCityItems, function(){ // добавляем ID городов
								addInputs += '<input type="hidden" name="city[]" value="'+this.dataset.idcity+'">';
							});
							$('#epa-edit-form').append(addInputs);	

							$.each(arTimeItems, function(){ 	// преображаем время в достойный вид
								var val = $(this).val();
								if(val!=''){
									arVals = val.split('до');
									newVal = getNum(arVals[0]) + '-' + getNum(arVals[1]);
									$(this).val(newVal)
								}
							});
							
							var arAllInputs = $('.epa__cities-block-list input');
							$.each(arAllInputs, function(){
								var main = $(this).closest('.epa__city-item'),
									idCity = main[0].dataset.idcity;

								if($(this).closest('.epa__days-checkboxes').length){
									$(this).attr('name', 'days['+idCity+']');
								}
								if($(this).closest('.epa__period-list').length){
									var day = $(this).attr('name').slice(-3);
									$(this).attr('name', 'time['+idCity+']'+day);
								}					
							});
							$('#epa-edit-form').submit();
							//console.log($('#epa-edit-form').serializeArray());
						}
					}
				});
			}, 500);
		}
		else{
			$.each($(cityM+' .epa__required'), function(){
				if(!checkField(this)) errors = true; 
			});
			$.each($(cityM+' .epa__days-checkboxes'), function(){
				var checked = false,
						$p = $(this);

				$.each($p.find('.epa__day-input'), function(){ 
					if($(this).is(':checked')) checked=true;
				});
				if(!checked) {
					$.each($p.find('.epa__checkbox'), function(){ addErr(this) })
					errors = true; 
				}
			});

			if(!checkField('[name="about-mself"]')) errors = true; 

			if(!checkField($('[name="user-attribs[edu]"]'))) errors = true;
			if(!checkField($('[name="langs[]"]'))) errors = true;
			$.each($('.epa__post-detail .epa__required'), function(){
				if(!checkField(this)) errors = true; 
			});

			checkPhone({'type':'input'});

			var arErrors = $('.error');
			if(arErrors.length>0)
			{
				MainScript.buttonLoading(self,false);
        $.fancybox.open({
          src: '.prmu__popup',
          type: 'inline',
          touch: false,
          afterClose: function(){
            $('html, body').animate({ scrollTop: $($('.error')[0]).offset().top-20 }, 1000);
          }
        });
			}
		//console.log(arErrors);
			if(!errors && !arErrors.length){
				var arPosts = $('#epa-list-posts input'),
					arCityItems = $('#city-module .epa__city-item'),
					arTimeItems = $('#city-module .epa__period input'),
					addInputs = '';
				$.each(arPosts, function(){ // добавляем массив опыта вакансий
					if($(this).is(':checked'))
						addInputs += '<input type="hidden" name="donjnost-exp[]" value="'+$(this).val()+'">';
				});
				$.each(arCityItems, function(){ // добавляем ID городов
					addInputs += '<input type="hidden" name="city[]" value="'+this.dataset.idcity+'">';
				});
				$('#epa-edit-form').append(addInputs);	

				$.each(arTimeItems, function(){ 	// преображаем время в достойный вид
					var val = $(this).val();
					if(val!=''){
						arVals = val.split('до');
						newVal = getNum(arVals[0]) + '-' + getNum(arVals[1]);
						$(this).val(newVal)
					}
				});
				
				var arAllInputs = $('.epa__cities-block-list input');
				$.each(arAllInputs, function(){
					var main = $(this).closest('.epa__city-item'),
						idCity = main[0].dataset.idcity;

					if($(this).closest('.epa__days-checkboxes').length){
						$(this).attr('name', 'days['+idCity+']');
					}
					if($(this).closest('.epa__period-list').length){
						var day = $(this).attr('name').slice(-3);
						$(this).attr('name', 'time['+idCity+']'+day);
					}
				});
				$('#epa-edit-form').submit();
				//console.log($('#epa-edit-form').serializeArray());
			}
		}
	});
	//
	//	добавить еще один номер
	//
	$('.epa__add-phone-btn').click(function(){
		var label = $(this).closest('.epa__label'),
			arItems = $(cntctM+' .epa__add-phone'),
			html = $('#add-additional-phone').html();

		html = html.replace(/NEWNUM/g, arItems.length);

		if(arItems.length>0)
			$(cntctM+' .epa__add-phone:eq(-1)').after(html);
		else
			$(label).after(html);
	});
	//
	$('.epa__req-list').on('click', 'b', function(){
		var name = $(this).text();
		$('html, body').animate({ scrollTop: $('[data-name="' + name + '"]').offset().top-20 }, 1000);
	});
	/*
	*     Финкции
	*/
	// additional functions
	function addErr(e){ 
		$(e).addClass('error');
		return false;
	}
	function remErr(e){ 
		$(e).removeClass('error');
		return true;
	}
	// select radio
	function changeRadio(str){
		var arInputs = $('#epa-list-' + str + ' input');
		$.each(arInputs, function(){
			if($(this).is(':checked')) 
				$('#epa-str-' + str).val($(this).siblings('label').text());
		});
		$('#epa-list-' + str).fadeOut();
	}
	function checkPosts(id=0){
		var arInputs = $('#epa-list-posts').find('input'),
			postBlock = $('#epa-post-single').html(),
			arPostBlock = $('.epa__post-detail').find('.epa__post-block'),
			arPostsName = [],
			arPostId = [],
			arPostsNewId = [],
			htmlStr = '',
			htmlBlock = '';

		//	собираем ID блоков должностей
		$.each(arPostBlock, function(){ 
			arPostId.push(Number($(this).data('id')));
		});
		// проверяем выбранные должности
		$.each(arInputs, function(){
			var $it = $(this),
				elId = Number($it.val()),
				elName = $it.siblings('label').text(),
				custom = typeof $it.data('name')=='string';

			if($it.is(':checked')){
				arPostsName.push(elName);
				arPostsNewId.push(elId);
				if(custom)
					changeArrNewPosts(id>0 && id==elId ? id : elId, elName, true);
			}
			else if(custom)
				changeArrNewPosts(elId, elName, false);
		});
		//	записываем в псевдоинпут
		$('#epa-str-posts').val(arPostsName);
		//	добавляем зелен. должность
		$.each(arPostsName, function(){ htmlStr += '<b> ' + this + '</b>' });
		$('.epa__posts-list').html(htmlStr);
		// выбираем ID к удалению
		var arTemp = [];
		$.each(arPostId, function(){
			if($.inArray(Number(this),arPostsNewId)<0) 
				arTemp.push(Number(this));
		});
		// удаляем блоки с этим ID
		$.each(arPostBlock, function(){
			if($.inArray(Number($(this).data('id')), arTemp)>=0) $(this).remove();
		});
		// выбираем ID к добавлению
		arTemp = [];
		$.each(arPostsNewId, function(i){
			if($.inArray(Number(this),arPostId)<0){
				arTemp.push({'id':Number(this), 'name':arPostsName[i]});
			}
		});
		$.each(arTemp, function(){
			newId = id>0 ? id : this.id;
			temp = postBlock.replace(/NEWID/g,newId);
			temp = temp.replace('NEWNAME',this.name);
			htmlBlock += temp;
		});

		// добавляем блоки
		//$('.epa__post-detail .clearfix').before(htmlBlock); //в конец
        $('.epa__post-detail').prepend(htmlBlock); //в начало

        $.each($('.epa__post-detail input'),function(){
            checkField(this);
        });
	}
	function randomInt(){
		var min = 1000,
			max = 9999;
		do{
			rand = min + Math.random() * (max + 1 - min);
			rand = Math.floor(rand);
		}while($.inArray(rand, arNewPosts)>=0);
		return rand;
	}
	function changeArrNewPosts(id, name, add){
		var pos = -1;
		$.each(arNewPosts, function(i){ if(this.id==id) pos = i });
		if(pos<0 && add)
			arNewPosts.push({'id':id,'name':name});
		if(pos>=0 && !add)
			arNewPosts.splice(pos,1);
	}
  	//
	function findCities(e){
		var val = $(e).val().toLowerCase(),
			arResult = [],
			arResultId = [],
			content = '';

		if(val.length>2){ // если введено более 3х символов
			$.each(arCities, function(i){
				if(this.toLowerCase().indexOf(val)>=0){ 
					arResult.push(this);
					arResultId.push(i);
				}
			});
			arResult.length>0  
			? $.each(arResult, function(i){ content += '<li data-val="'+arResultId[i]+'">'+this+'</li>' })         
			: content = '<li class="emp">Список пуст</li>';

			$(e).siblings('.city-list').empty().append(content).fadeIn(); 
		}
	} 
	// поиск в выбраных городах
	function verificationCities(value, idcity=''){
		var result = {'error' : 0, 'id' : ''},
			find = false;

		$.each(arCities, function(i){
			if(value.toLowerCase()==arCities[i].toLowerCase()){
				if(idcity=='' || (idcity!='' && i!=idcity)){
					if($.inArray(i, arIdCities)>=0){
						result.error = 2; // такой город уже выбран
					}
					else{   
						result.error = 0; // этот город еще не выбирался
						result.id = i;
					}
				}
				find = true;
			}
		});
		if(!find) result.error = 1;
		return result;
	}
	function checkSelectCity(main){
		var arNames = $('.epa__cities-list b'),
			arCBlocks = $('#city-module .epa__city-item'),
			index = $(main).index(),
			name = arCities[main[0].dataset.idcity],
			html = '<b>'+name+'</b>';

		arCBlocks.length-arNames.length==1
		? $('.epa__cities-list>div').append(html)
		: $(arNames[index]).text(name);
	}
	//
	function findMetroes(e){ 
		var val = $(e).val().toLowerCase(),
			arResult = [],
			content = '',
			main = $(e).closest('.epa__city-item'),
			idcity = main[0].dataset.idcity;

		$.each(arMetroes[idcity], function(i){
			if(this.toLowerCase().indexOf(val)>=0 && $.inArray(i, arSelectMetroes)<0) 
				arResult.push({'id':i,'name':this});
		});

		arResult.length>0  
		? $.each(arResult, function(){ content += '<li data-val="'+this.id+'">'+this.name+'</li>' })
		: content = '<li class="emp">Список пуст</li>';

		$(e).siblings('.metro-list').empty().append(content).fadeIn();
	}
	// metro
	function checkAvailabilityMetro(main){
		var idcity = main[0].dataset.idcity,
			cityBlock = $(main).find('.epa__city'),
			metroBlock = $(main).find('.epa__metro'),
			metroList = $(main).find('.epa__metro-list');

		if(typeof arMetroes[idcity] === "object" && metroBlock.length==0){
			$(cityBlock).after($('#add-metro-content').html());
		}
		if(typeof arMetroes[idcity] !== "object" && metroBlock.length>0){
			$(metroBlock).remove();
			$(metroList).remove();
		}
	}
	//
	function selectMetro(input, idMetro){
		var main = $(input).closest('.epa__city-item'),
			metroLabel = $(input).closest('.epa__metro'),
			metroList = $(input).siblings('.metro-list'),
			metroListBlock = $(main).find('.epa__metro-list'),
			idCity = main[0].dataset.idcity,
			nameMetro = arMetroes[idCity][idMetro],
			html = $('#add-metro-item').html();

		if($.inArray(idMetro,arSelectMetroes)<0)
			arSelectMetroes.push(String(idMetro)); // добавили в массив выбранных, чтобы не было дублей

		html = html.replace(/IDCITY/g, idCity);
		html = html.replace(/IDMETRO/g, idMetro);
		html = html.replace(/NAMEMETRO/g, nameMetro);

		$(metroListBlock).append(html);
		$(input).val('');
		$(input).blur();
		$(metroList).fadeOut();
		remErr(metroLabel);
	}
	// check fields
	function checkField(e){
		var $it = $(e),
			val = $it.val(),
			id = $it.prop('id'),
			label = $it.closest('.epa__label'),
			epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
			res = false;

		if(id=='epa-email'){ // email
			res = epattern.test(val) ? remErr(label) : addErr(label);
			$('.epa__email').removeClass('erroremail');
			if(res && val!=oldEmail){
				clearTimeout(emailTimer);
				emailTimer = setTimeout(function(){
					$.ajax({
						type: 'POST',
						url: '/ajax/emailVerification',
						data: 'nemail='+val+'&oemail='+oldEmail,
						dataType: 'json',
						success: function(res){
							if(res){
								$('.epa__email').addClass('erroremail error'); 
							}
							else{
								$('.epa__email').removeClass('erroremail error');
								$('#conf-email').removeClass('complete')
									.html('<p>Почта не подтверждена. <em>Подтвердить</em></p>');
								confirmEmail = false;
							}
						}
					});
				}, 500);
			}	
		}
		else
			if($(label).hasClass('epa__period')){ // поле установки подходящего времени в дни недели

			if(val.length>8){ // 8 минимум
				var arVals = val.split('до');
				if(arVals.length==2)
				{
					var from = Number(getNum(arVals[0])),
							to = Number(getNum(arVals[1]));

					res = (from>23 || to>24 || from>=to) ? addErr(label) : remErr(label); // проверяем правильность временного промежутка
				}
			}
			else if(val=='')
				res = addErr(label);
			else
				res = remErr(label);
		}
		else if($(label).hasClass('epa__education')) { // образование
			var selected = false;
			$.each($(label).find('[type="radio"]'),
				function(){ if(this.checked) selected = true; });
			res = (!selected ? addErr(label) : remErr(label));
		}
		else if($(label).hasClass('epa__language')) { // языки
			var selected = false;
			$.each($(label).find('[type="checkbox"]'),
				function(){ if(this.checked) selected = true; });
			res = (!selected ? addErr(label) : remErr(label));
		}
		else{
			if($(label).hasClass('epa__payment')) // исключения для поля "Ожидаемая оплата""
			{
				label = $it;
			}
			res = ((val=='' || val==null) ? addErr(label) : remErr(label));
		}
		if(id=='epa-mail' || id=='epa-gmail'){
			res = (epattern.test(val) || val=='') ? remErr(label) : addErr(label);
		}
		errorFieldName(e,!res);
		return res;
	};
	//	Поиск телефона
	function findPhones(e){ 
		var newV = getNum($(e).val()),
			newL = newV.length,
			arResult = [],
			content = '';

		updateArSelectPhones();
		$.each(arSelectPhones, function(){
			var oldV = getNum(this),
				oldL = oldV.length
			if(oldV.indexOf(newV)>=0 && newL<oldL) 
				arResult.push(this);
		});

		arResult.length>0  
		? $.each(arResult, function(){ content += '<li>'+this+'</li>' })
		: content = '';

		$(e).siblings('.phone-list').empty().append(content).fadeIn();
	}
	// собираем введенные телефоны
	function updateArSelectPhones(){ 
		arSelectPhones = [];
		$.each($(cntctM+' .epa__phone'), function(){
			var val = $(this).val(),
				clearVal = getNum(val);
			if(clearVal!='' && clearVal.length==phoneLen && $.inArray(val, arSelectPhones)<0) 
				arSelectPhones.push($(this).val());
		});
	}
	// получаем номер
	function getNum(value){ return value.replace(/\D+/g,'') }
  	//
	function errorFieldName(e,show){
		var name = $(e).data('name'),
		flag = $.inArray(name, arErrorsFields)<0 ? false : true;
		strErr = '<b>';

		if(flag && !show)
			arErrorsFields.splice(arErrorsFields.indexOf(name),1);

		if(!flag && show)
			arErrorsFields.push(name);

		strErr += arErrorsFields.join('</b>, <b>') + '</b>';
		$('.epa__req-list div').html(strErr);
		arErrorsFields.length>0 ? $('.epa__req-list').show() : $('.epa__req-list').hide();
	}
	//
	//
	//
	getFlagTimer = setInterval(function(){ // ищем флаг страны
		if($('.country-phone-selected>img').is('*')){
			oldFlag = $('.country-phone-selected>img').attr('class');
			clearInterval(getFlagTimer);
		}
	},500);
	// события верикации
	$('#conf-email').on('click','em',function(){ restoreCode('email') });
	$('#conf-phone').on('click','em',function(){ restoreCode('phone') });
	$('#conf-email-block .epa__confirm-btn').click(function(){ confirmContact('email') });
	$('#conf-phone-block .epa__confirm-btn').click(function(){ confirmContact('phone') });

	function confirmContact(e){
		var val = e=='email' ? $('#epa-email').val() : ($('[name="__phone_prefix"]').val() + $('#phone-code').val()),
			$btn = $('#conf-' + e),
			$code = $('#conf-' + e + '-inp'),
			code = $code.val(),
			$hint = $('.confirm-user.' + e),
			$block = $('#conf-' + e + '-block'),
			main = $btn.closest('.epa__label');

		if(code!=''){
			$btn.addClass('loading').show();
			$('#conf-email-block').fadeOut();
			$.ajax({
				type: 'POST',
				url: '/ajax/confirm',
				data: 'code='+ code + '&' + e + '=' + val,
				dataType: 'json',
				success: function(r){
					$btn.removeClass('loading');
					if(r.code==200){
						$btn.addClass('complete');
						$hint.fadeOut(); // спрятали подсказку под лого
						if(e=='email'){
							showPopupMess('E-mail подтвержден','Электронная почта подтверждена');
							$btn.find('p').text('Почта подтверждена');
							oldEmail = val;
							confirmEmail = true;
						}
						else{
							showPopupMess('Телефон подтвержден','Номер телефона подтвержден');
							$btn.find('p').text('Телефон подтвержден');
							oldPhone = $('#phone-code').val();
							selectPhoneCode = $('[name="__phone_prefix"]').val();
							oldFlag = $('.country-phone-selected>img').attr('class');
							confirmPhone = true;
						}
					}
					else{
						$hint.fadeIn(); // показали подсказку под лого
						if(e=='email'){
							showPopupMess('Ошибка','Электронная почта не подтверждена');
							$('#epa-email').val(oldEmail);
							confirmEmail = false;
						}
						else{
							showPopupMess('Ошибка','Номер телефона не подтвержден');
							$('#phone-code').val(oldPhone);
							$('[name="__phone_prefix"]').val(selectPhoneCode);
							$('.country-phone-selected>img').attr('class',oldFlag);
							$('.country-phone-selected>span').text('+' + selectPhoneCode);
							confirmPhone = false;
						}
					}
					$code.val('');
					$block.fadeOut();
					$(main).fadeIn();
				}
			});
		}
	}
	//
	function restoreCode(e){
		var val = e==='email' ? $('#epa-email').val() : ($('[name="__phone_prefix"]').val() + $('#phone-code').val()),
			check = e==='email' ? $('#epa-email').val() : $('#phone-code').val(),
			$btn = $('#conf-' + e),
			$block = $('#conf-' + e + '-block'),
			main = $btn.closest('.epa__label');

		if(!$btn.hasClass('complete') && !$btn.hasClass('loading')){
			if(check!=='' && !$(main).hasClass('error')){



				$btn.fadeOut();
				$.ajax({
					type: 'POST',
					url: '/ajax/restorecode',
					data: e + '='+ val,
					success: function(r){ 
						if(e==='email')
							showPopupMess('Проверка почты','На почту выслан код для подтверждения. Введите его в поле "Проверочный код"');
						else
							showPopupMess('Проверка телефона','На телефон выслан код для подтверждения. Введите его в поле "Проверочный код"');
						$block.fadeIn();
						$(main).fadeOut();
					}
				});				
			}
			else{
				if(e==='email'){
					addErr($('#epa-email').closest('.epa__label'));
				}
				else{
					addErr($('#phone-code').closest('.epa__label'));
				}
			}
		}		
	}
	//
	$('.confirm-user.email').click(function(){
		$(this).fadeOut();
		$('#conf-email em').click();
	});
	//
	$('.confirm-user.phone').click(function(){
		$(this).fadeOut();
		$('#conf-phone em').click();
	});
	//
	//
	//
	//
	var timerHintEmail, timerHintPhone;
	$(document).mousemove(function(e){	// подсказка для подтверждения почты
		if($(e.target).closest('#conf-email').length || $(e.target).is('#conf-email')){
			$('#conf-email p').fadeIn(300);
			clearTimeout(timerHintEmail);
		};
		if($(e.target).closest('#conf-phone').length || $(e.target).is('#conf-phone')){
			$('#conf-phone p').fadeIn(300);
			clearTimeout(timerHintPhone);
		}
	})
	.mouseout(function(e){	// подсказка для подтверждения телефона
		if(!$(e.target).closest('#conf-email').length && !$(e.target).is('#conf-email')){
			clearTimeout(timerHintEmail);
			timerHintEmail = setTimeout(function(){ $('#conf-email p').fadeOut(300) },500);
		}
		if(!$(e.target).closest('#conf-phone').length && !$(e.target).is('#conf-phone')){
			clearTimeout(timerHintPhone);
			timerHintPhone = setTimeout(function(){ $('#conf-phone p').fadeOut(300) },500);
		}
	});
	//
	function showPopupMess(t, m){
		var html = "<form data-header='" + t + "'>" + m + "</form>";
		ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
	}
	// проверка номера
	function checkPhone(e){
		var $inp = $('#phone-code'),
			len = getNum($inp.val()).length,
			code = $('[name="__phone_prefix"]').val().length;

		if(e.type=='click' && !$(e.target).is('.country-phone') && !$(e.target).closest('.country-phone').length){
			if((code==3 && len<9) || (code==1 && len<10)){ // UKR || RF
				addErr($inp.closest('.epa__label'));
				$inp.val('');
			}
			else{
				remErr($inp.closest('.epa__label')); 
				if($inp.val()!==oldPhone){
					$('#conf-phone').removeClass('complete')
						.html('<p>Телефон не подтвержден. <em>Подтвердить</em></p>');
					confirmPhone = false;
				}
			}
		}
		if(e.type=='input'){
			if((code==3 && len<9) || (code==1 && len<10) || len==0){
				addErr($inp.closest('.epa__label'));
			}
			else{
				remErr($inp.closest('.epa__label'));
				if($inp.val()!==oldPhone){
					$('#conf-phone').removeClass('complete')
						.html('<p>Телефон не подтвержден. <em>Подтвердить</em></p>');
					confirmPhone = false;
				}
			}
		}
	}
	//
	//
	// управляем позицией блока содержания
	var posContentList = $('.epa__logo-name-list').offset().top - 15;
	$(window).on('resize scroll',scrollContentList);
	scrollContentList();
	function scrollContentList() {
		(
			$(document).scrollTop() > posContentList
			&&
			$(window).width() > 767
		)
		? $('.epa__logo-name-list').addClass('fixed')
		: $('.epa__logo-name-list').removeClass('fixed');
	}

    //fixed menu in personal account
    var posAccMenu = $('.personal-acc__menu').offset().top - 100;
    $(window).on('resize scroll',scrollAccMenu);
    scrollAccMenu();
    function scrollAccMenu() {
        (
            $(document).scrollTop() > posAccMenu
            &&
            $(window).width() < 768
        )
            ? $('.personal-acc__menu').addClass('fixed')
            : $('.personal-acc__menu').removeClass('fixed');
    }

    //
	// начальное выделение полей
	//
	$.each($('.epa__post-detail .epa__required'), function(){ checkField(this) });
	$.each($(mainM + ' .epa__required'), function(){ checkField(this) });
	$.each($(cntctM + ' .epa__required'), function(){ checkField(this) });
	$.each($('.epa__education [type="radio"]'), function(){ checkField(this) });
	$.each($('.epa__language [type="checkbox"]'), function(){ checkField(this) });
	checkField('[name="about-mself"]');
	checkField('[name="user-attribs[edu]"]');
	checkField('[name="langs[]"]');
	checkPhone({type:'input'});
	//
	//
	// инициализация календаря
	$("#birthday").datepicker({
		maxDate: '-14y',
		changeYear: true,
		yearRange: "1970:2005",
		beforeShow: function(){
			$('#ui-datepicker-div').addClass('custom-calendar');
		}
	});
	// проверка корректности даты
	if($('#birthday').is('*'))
	{
		$('#birthday').change(function(){
			if(this.value.length)
			{
				let objDate = $(this).datepicker('getDate'),
						checkYear = new Date().getFullYear() - 14,
						d = String(objDate.getDate()),
						m = String(objDate.getMonth()+1),
						y = objDate.getFullYear();

				d = d.length<2 ? ('0'+d) : d;
				m = m.length<2 ? ('0'+m) : m;
				y = checkYear<y ? checkYear : y;

				this.value = d + '.' + m + '.' + y;
				if(this.value=='01.01.1970')
				{
					addErr('.epa__label.epa__date');
					this.value='';
				}
				else
				{
					remErr('.epa__label.epa__date');
				}
			}
			else
			{
				addErr('.epa__label.epa__date');
			}
		});
	}
	//
	$(document).click(function(e){
		if($(e.target).closest('.epa__period-error').length || $(e.target).is('.epa__period-error'))
		{
			var main = $(e.target).closest('.epa__period')[0],
					input = $(main).find('.epa__input');
			$(input).focus();
			remErr(main);
		}
	});
	//
	$(cityM).on('blur','.epa__period .epa__input',function(e){
		setTimeout(function(){ checkField(e.target) },100);
	});
});