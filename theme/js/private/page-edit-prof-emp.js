jQuery(function($){
	var phoneLen = 10, // нормальное кол-во цифр в телефоне
		epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
		oldEmail = $('.epe__input-mail').val(),
		emailTimer = null,
		oldPhone = $('#phone-code').val(),
		oldFlag = '',
		confirmEmail = $('#conf-email').hasClass('complete') ? true : false;
		confirmPhone = $('#conf-phone').hasClass('complete') ? true : false;
	//
	//
	$(document).on('click', function(e){
		var it = e.target;
		// select
		var listT = '#epe-list-type',
			listM = '#epe-list-mess';

		if(it.id=='epe-veil-type') $(listT).fadeIn();
		else if($(it).is('#epe-list-type i') || !$(it).closest(listT).length) $(listT).fadeOut();
		if(it.id=='epe-veil-mess') $(listM).fadeIn();
		else if($(it).is('#epe-list-mess i') || !$(it).closest(listM).length) $(listM).fadeOut();
	});
	// события выбора мессенджера
	$('#epe-list-mess input').on('change', function(){
		var arInputs = $('#epe-list-mess input'),
			arMess = [];
			showHint = false;
		$.each(arInputs, function(){
			mess = $(this).data('mess');
			if($(this).is(':checked')){
				arMess.push($(this).siblings('label').text());
				$('.epe__mess-'+mess).removeClass('off');
				showHint = true;
			}
			else{
				$('.epe__mess-'+mess).addClass('off');
				$('.epe__mess-'+mess+' input').val('');
			}
		});
		$('#epe-str-mess').val(arMess);
		showHint ? $('.epe__mess-hint').removeClass('off') : $('.epe__mess-hint').addClass('off');
	});
	//	изменяем тип работодателя
	$('#epe-list-type input').on('change', function(){
		var arInputs = $('#epe-list-type input');
		$.each(arInputs, function(){
			if($(this).is(':checked')) 
				$('#epe-str-type').text($(this).siblings('label').text());
		});
		$('#epe-list-type').fadeOut();
	});
	//
	//		Ввод телефона
	//
	$(document).on('click',function(e){ checkPhone(e) });
	$('#phone-code').on('input',function(e){ checkPhone(e) });
	//
	$('.epe__btn').click(function(e){
		var self = this,
				nemail = $('.epe__input-mail').val(),
				errors = false;

		e.preventDefault();

		if(MainScript.isButtonLoading(self))
			return false;
		else
			MainScript.buttonLoading(self,true);


		checkPhone({'type':false,'target':false});

		if(epattern.test(nemail) && nemail!=oldEmail){
			clearTimeout(emailTimer);
			emailTimer = setTimeout(function(){
				$.ajax({
					type: 'POST',
					url: '/ajax/emailVerification',
					data: 'nemail='+nemail+'&oemail='+oldEmail,
					dataType: 'json',
					success: function(res){
						res
						? $('.epe__email').addClass('erroremail error')
						: $('.epe__email').removeClass('erroremail error');
 							
						if(!checkField($('.epe__input-name'))) errors = true; 

						var arErrors = $('.error');
						if(arErrors.length>0)
						{
							$('html, body').animate({ scrollTop: $(arErrors[0]).offset().top-20 }, 1000);
							MainScript.buttonLoading(self,false);
						}
						if(!errors && !arErrors.length){
							$('#F1compprof').submit();
						}
					}
				});
			}, 500);
		}
		else{
			$.each($('.epe__required'), function(){
				if(!checkField(this)) errors = true; 
			});
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
				$('#F1compprof').submit();
			}
		}
		//console.log($('#F1compprof').serializeArray());
	});
	//    Проверка полей
	$('.epe__required').on('input', function(){ checkField(this) });
	$('.epe__required').on('change', function(){ checkField(this) });
	//
	//	Functions
	//
	// получаем номер
	function getNum(value){ return value.replace(/\D+/g,'') }
	// additional functions
	function addErr(e){ 
		$(e).addClass('error');
		return false;
	}
	function remErr(e){ 
		$(e).removeClass('error');
		return true;
	}
	// check fields
	function checkField(e){
		var val = $(e).val(),
			label = $(e).closest('.epe__label'),
			res = false;

		if($(e).hasClass('epe__input-mail')){
			res = epattern.test(val) ? remErr(label) : addErr(label);
			$('.epe__email').removeClass('erroremail');
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
								$('.epe__email').addClass('erroremail error');
							}
							else{
								$('.epe__email').removeClass('erroremail error');
								$('#conf-email').removeClass('complete')
									.html('<p>Почта не подтверждена. <em>Подтвердить</em></p>');
								confirmEmail = false;
							}
						}
					});
				}, 500);
			}	
		}
		else{
			res = ((val=='' || val==null) ? addErr(label) : remErr(label));
		}
		return res;
	};
	// проверка номера
	function checkPhone(e){
		var $inp = $('#phone-code'),
			len = getNum($inp.val()).length,
			code = $('[name="__phone_prefix"]').val().length;

		if(e.type=='click' && !$(e.target).is('.country-phone') && !$(e.target).closest('.country-phone').length){
			if((code==3 && len<9) || (code==1 && len<10)){ // UKR || RF
				addErr($inp.closest('.epe__label'));
				$inp.val('');
			}
			else{
				remErr($inp.closest('.epe__label')); 
				if($inp.val()!==oldPhone){
					$('#conf-phone').removeClass('complete')
						.html('<p>Телефон не подтвержден. <em>Подтвердить</em></p>');
					confirmPhone = false;
				}
			}
		}
		else{
			if((code==3 && len<9) || (code==1 && len<10) || len==0){
				addErr($inp.closest('.epe__label'));
			}
			else{
				remErr($inp.closest('.epe__label'));
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
	$('#conf-email-block .epe__confirm-btn').click(function(){ confirmContact('email') });
	$('#conf-phone-block .epe__confirm-btn').click(function(){ confirmContact('phone') });

	function confirmContact(e){
		var val = e=='email' ? $('#epe-email').val() : ($('[name="__phone_prefix"]').val() + $('#phone-code').val()),
			$btn = $('#conf-' + e),
			$code = $('#conf-' + e + '-inp'),
			code = $code.val(),
			$hint = $('.confirm-user.' + e),
			$block = $('#conf-' + e + '-block'),
			main = $btn.closest('.epe__label');

		if(code!=''){
			$btn.addClass('loading').show();
			$('#conf-mail-block').fadeOut();
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
							$('#epe-email').val(oldEmail);
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
		var val = e=='email' ? $('#epe-email').val() : ($('[name="__phone_prefix"]').val() + $('#phone-code').val()),
			check = e==='email' ? $('#epe-email').val() : $('#phone-code').val(),
			$btn = $('#conf-' + e),
			$block = $('#conf-' + e + '-block'),
			main = $btn.closest('.epe__label');

		if(!$btn.hasClass('complete') && !$btn.hasClass('loading')){
			if(check!=='' && !$(main).hasClass('error')){
				$btn.fadeOut();
				$.ajax({
					type: 'POST',
					url: '/ajax/restorecode',
					data: e + '='+ val,
					success: function(r){ 
						if(e=='email')
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
					addErr($('#epe-email').closest('.epe__label'));
				}
				else{
					addErr($('#phone-code').closest('.epe__label'));
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
	//
	//      ГОРОДА
	//
	var bAjaxTimer = false;
	$('#F1compprof').on('input', '.epe__input-city', function() { inputCity(this) });
	$('#F1compprof').on('focus', '.epe__input-city', function() { focusCity(this) });
	// обрабатываем клики
	$(document).on('click', function(e) { checkCity(e.target) });
  //      ввод города
  inputCity = function (e) {
    var v = $(e).val();
    clearTimeout(bAjaxTimer);
    setFirstUpper(e);
    bAjaxTimer = setTimeout(function(){ getAjaxCities(v, e) },1000);
  }
  //      фокус поля города
  focusCity = function (e) {
    var v = $(e).val();
    $(e).val('').val(v);
    setFirstUpper(e);
    getAjaxCities(v, e);
  };
  //      запрос списка городов
  getAjaxCities = function (val, e) {
    var $e = $(e),
				list = $e.siblings('.city-list')[0],
				main = $e.closest('.city-field')[0],
				mainCity = $e.closest('.city-item')[0],
				idcity = Number($('#id-city').val()),
				piece = val.toLowerCase(),
				content = '';

    $(main).addClass('load'); // загрузка началась

    $.ajax({
      type: 'POST',
      url: MainConfig.AJAX_GET_VE_GET_CITIES,
      data: 'query=' + val,
      dataType: 'json',
      success: function(r) {
        for (var i in r.suggestions) {
          var item = r.suggestions[i],
            id = +item.data;

          if(isNaN(item.data))
            break;

          if(item.value.toLowerCase().indexOf(piece) >= 0)
          { // собираем список
            content += '<li data-id="' + item.data + '">' + item.value + '</li>';
          }
        }
        content
        ? $(list).html(content).fadeIn()
        : $(list).html('<li class="emp">Список пуст</li>').fadeIn();
        $(main).removeClass('load'); // загрузка завершена
      }
    });
  }
  //      фокус инпута и выбор города
  checkCity = function (e) {
    var $e = $(e),
    		cNew = $e.text(),
      	data = e.dataset,
				cSelect = $('.city-select'),
				cInput = $('.epe__input-city'),
				cList = $('.city-list'),
				inp = $('#id-city'),
				id = inp.val(),
				v = cSelect.text();

   	if( !$e.closest('.city-field').length && !$e.is('.city-field') )
   	{
			cSelect.text()==='' ? cSelect.hide() : cSelect.show();
			cInput.val(v).hide();
			cList.fadeOut();
    }
    else if( $e.is('li') && !$e.hasClass('emp') ) // клик по объектам списка
		{ // выбираем из списка
			if(id!=='' && id===data.id)
			{
				cInput.val(v).hide();
				cSelect.show();
			}
			else
			{ // ввод нового города
				inp.val(data.id);
				cInput.val(cNew).hide();
				cSelect.html(cNew+'<b></b>').show();
			}
			cList.fadeOut();
		}
		else
		{
			$e.is('b') && cInput.val('');
			cInput.show().focus();
			cSelect.hide();
		}
  }
  //      правильный ввод названия города
  setFirstUpper = function (e) {
    let split = $(e).val().split(' ');

    for(let i=0, len=split.length; i<len; i++)
      split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join(' '));

    split = $(e).val().split('-');
    for(let i=0, len=split.length; i<len; i++)
      split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
    $(e).val(split.join('-'));
  }
  //
  $('[name="user-attribs[stationaryphone]"]').on('input',function(){
  	this.value = this.value.replace(/\D+/g,'');
  })
  //
  // начальное выделение полей
  //
  $.each($('.epe__required'), function(){ checkField(this) });
});