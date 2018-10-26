$(function(){
	var arSelect = $('.set-main__select-val'),
		arList = $('.set-main__select-list'),
		arSelInp = $('.set-main__select-list input'),
		oldEmail = $('#s-p-email').val(),
		oldPhone = $('#phone-code').val(),
		oldCode = $('[name="__phone_prefix"]').val(),
		$pswBtn = $('.set-priv__psw'),
		$eVeil = $('#email-inp span'),
		$pVeil = $('#phone-inp span'),

		epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
		sendingCode = false;

	var eConfirm = $('#email-inp').hasClass('confirm') ? true : false;
	var pConfirm = $('#phone-inp').hasClass('confirm') ? true : false;

  // проверка ввода времени
  var keyCode;
  $(document).keydown(function(e){ keyCode = e.keyCode });
  //
  $('#time').on('input',function(){
    var v = this.value,
        l = v.length;

    if(keyCode==8) { //backspace
      if(l==2) v = v.slice(0, -1);
    }
    else {
      switch(l) {
        case 1:
          v = v.replace(/[\D+]/g,'');
          break;
        case 2:
          v = v.replace(/[\D+]/g,'') + ':';
          break;
        case 3:
        case 4:
        case 5:
          arV = v.split(':');
          $.each(arV,function(i,e){
            arV[i] = e.replace(/[\D+]/g,'');
            arV[i] = arV[i].substr(0,2);
          });
          v = arV[0] + ':' + arV[1];
          break;
        default:
        	arV = v.split(':');
        	if(arV[0]===v) {
        		v = arV[0].replace(/[\D+]/g,'').substr(0,2) + ':';
        	}
        	else {
        		v = arV[0].replace(/[\D+]/g,'').substr(0,2) + ':' + arV[1].replace(/[\D+]/g,'').substr(2,4);
        	}
        	break;
      }
    }
    this.value = v;
  });
	$('#time').change(function(){
		var val = $(this).val();

		if(val.length<5){
			$(this).val('00:00');
			return false;
		}

		var arVals = val.split(':'),
			h = Number(getNum(arVals[0])),
			m = Number(getNum(arVals[1]));

		if(h>23) h = 23;
		if(m>59) m = 59;

		$(this).val(h+':'+m);
	});
	//
	//	БЛОК ОСНОВНЫЕ
	//
	arSelect.click(function(){	//	открываем список
		var list = $(this).siblings('ul');

		list.fadeIn();
		$.each(arList,function(){
			if(!$(this).is(list))
				$(this).fadeOut();
		});
	});
	//	изменяем выбор и закрываем список
	arSelInp.change(function(){
		var val = $(this).closest('.settings-main__select')
					.children('.set-main__select-val'),
			newVal = $(this).siblings('label')
						.children('span').text(),
			list = $(this).closest('ul').fadeOut();

		$(val).text(newVal);
	});
	//	закрываем все списки
	$(document).on('click',function(e){
		if(!$(e.target).is('.set-main__select-val') && !$(e.target).closest('.set-main__select-list').length)
			$.each(arList,function(){ $(this).fadeOut() });			
	});
	//
	//		БЛОК ПРИВАТ
	//
	$('#email-inp span').click(function(){	//	фокусируем поля почты
		if(!$(this).hasClass('code')){
			var input = $(this).hide().siblings('input'),
				val = $(input).val();
			$(this).siblings('b').hide();
			$(input).val('').focus().val(val);
		}
	});
	$('#phone-inp .set-priv__veil').click(function(){	//	фокусируем поля телефона
		if(!$(this).hasClass('code')){
			var input = $('#phone-code'),
					main = input.closest('.country-phone'),
					val = input.val();

			$(main).addClass('focus');
			$(this).hide().siblings('b').hide();
			input.val('').focus().val(val);
		}
	});
	//
	$('#email-inp input, #phone-inp input')
	.keyup(function(){//	ввод почты или телефона
		var val = $(this).val(),
			$btn = $(this).siblings('div'),
			$veil = $(this).siblings('span'),
			pVal = val.replace(/\D+/g,'');

		$(this).siblings('b').hide();

		if($(this).closest('#email-inp').length) { // если это почта
			if(epattern.test(val)) {
				$btn.fadeIn();
				$veil.fadeOut();
			}
			else {
				$btn.fadeOut();
				$veil.fadeOut();
			}
		}
		if($(this).closest('#phone-inp').length) { // если это почта
			var code = $('[name="__phone_prefix"]').val(),
					phoneLen;

			$btn = $('#phone-inp .set-priv__btn');
			$veil = $('#phone-inp .set-priv__veil');

			if(code.length==3) phoneLen = 9; // UKR, BEL
			if(code.length==1) phoneLen = 10; // RF

			if(pVal.length==phoneLen) {
				$btn.fadeIn();
				$veil.fadeOut();
			}
			else {
				$btn.fadeOut();
				$veil.fadeOut();
			}
		}
	})
	.blur(function(){	// потеря фокуса почты или телефона
		var val = $(this).val(),
			$btn = $(this).siblings('div'),
			$veil = $(this).siblings('span'),
			$conf = $(this).siblings('b'),
			len = val.replace(/\D+/g,'').length;

		if($(this).closest('#email-inp').length){  // если это почта
			if(epattern.test(val)){
				if(val===oldEmail){
					$btn.fadeOut();
					$veil.fadeIn();
					$conf.show();
				}
				else{
					$.ajax({
						type: 'POST',
						url: '/ajax/emailVerification',
						data: 'nemail='+val+'&oemail='+oldEmail,
						dataType: 'json',
						success: function(error){ verificationEmail(error, val) }
					});
				}
			}
			else{
				$(this).val(oldEmail);
				$btn.fadeOut();
				$veil.fadeIn();
				$conf.show();
			}
		}
		else{ }
	});
	//
	$('#email-inp div').click(function(){ // сохраняем почту или телефон
		var $inp = $(this).siblings('input'),
			$veil = $(this).siblings('span'),
			$conf = $(this).siblings('b'),
			val = $inp.val(),
			len = val.replace(/\D+/g,'').length,
			isEmail = ($(this).closest('#email-inp').length ? 1 : 0),
			oldVal = (isEmail ? oldEmail : oldPhone),
			$btn = $(this);

		if(isEmail && epattern.test(val)){
			if(val===oldVal){
				$btn.fadeOut();
				$veil.fadeIn();
				$conf.show();
			}
			else{
				$.ajax({
					type: 'POST',
					url: '/ajax/emailVerification',
					data: 'nemail='+val+'&oemail='+oldVal,
					dataType: 'json',
					success: function(error){ verificationEmail(error, val) }
				});
			}
		}
		else{
			$inp.val(oldVal);
			$btn.fadeOut();
			$veil.fadeIn();
			$conf.show();
		}
	});
	//
	$('#phone-inp .set-priv__btn').click(function(){ // сохраняем почту или телефон
		var main = $('.country-phone'),
				codeInp = $('[name="__phone_prefix"]'),
				code = codeInp.val(),
				val = $('#phone-code').val(),
				len = val.replace(/\D+/g,'').length,
				phoneLen;

		$btn = $('#phone-inp .set-priv__btn');
		$veil = $('#phone-inp .set-priv__veil');
		$conf = $('#phone-inp>b');
		main.removeClass('focus');

		if(code.length==3) phoneLen = 9; // UKR, BEL
		if(code.length==1) phoneLen = 10; // RF

		if(len==phoneLen) {
			if(val===oldPhone){
				$btn.fadeOut();
				$veil.fadeIn();
				$conf.show();
			}
			else if(!sendingCode) {
				sendingCode = true;
				$.ajax({ type:'POST', url:'/ajax/restorecode', data:'phone='+code+val });
				$('#phone-code-inp').fadeIn();
				$pVeil.addClass('code').fadeIn();
				$('#phone-inp .set-priv__btn').fadeOut();
				showPopupError('Проверка телефона','На телефон выслан код для подтверждения. Введите его в поле "Проверочный код"');
			}
		}
	});
	//
	$('#email-code input, #phone-code-inp input').keyup(function(){ // проверка ввода кода для email
		if($(this).closest('#email-code').length)
			$(this).val().length==6 ? $('#email-code div').fadeIn() : $('#email-code div').fadeOut();
		else
			$(this).val().length==6 ? $('#phone-code-inp div').fadeIn() : $('#phone-code-inp div').fadeOut();
	});
	//
	//	отправка введенного кода для почты и телефона
	$('#email-code div, #phone-code-inp div').click(function(){
		$(this).fadeOut();

		if($(this).closest('#email-code').length){ // для почты
			var code = $('#email-code input').val(),
				mail = $('#email-inp input').val();

			$.ajax({
				type: 'POST',
				url: '/ajax/confirm',
				data: 'code='+ code + '&email=' + mail,
				dataType: 'json',
				success: function(r){
					sendingCode = false;
					if(r.code==200){
						$('#email-inp').addClass('confirm');
						$('#email-inp b').attr('title','Почта подтверждена').show();
						$eVeil.removeClass('code');
						$('#email-code').fadeOut();
						$('#email-code div').fadeOut();
						$('#email-code input').val('');
						showPopupError('E-mail подтвержден','Электронная почта подтверждена');
						saveData({'email':mail});
						eConfirm = true;
					}
					else{
						$('#email-inp input').val(oldEmail);
						eConfirm ? $('#email-inp').addClass('confirm') : $('#email-inp').removeClass('confirm');
						$('#email-inp b').attr('title','Почта не подтверждена').show();
						$eVeil.removeClass('code');
						$('#email-code').fadeOut();
						$('#email-code div').fadeOut();
						$('#email-code input').val('');
						showPopupError('Ошибка','Электронная почта не подтверждена');
					}
				}
			});
		}
		else{	// для телефона
			var code = $('#phone-code-inp input').val(),
					codeInp = $('[name="__phone_prefix"]'),
					phoneCode = codeInp.val(),
					phone = $('#phone-code').val();

			$.ajax({
				type: 'POST',
				url: '/ajax/confirm',
				data: 'code='+ code + '&phone=' + phoneCode + phone,
				dataType: 'json',
				success: function(r){
					sendingCode = false;
					if(r.code==200){
						$('#phone-inp').addClass('confirm');
						$('#phone-inp b').attr('title','Телефон подтверждена').show();
						$pVeil.removeClass('code');
						$('#phone-code-inp').fadeOut();
						$('#phone-code-inp div').fadeOut();
						$('#phone-code-inp input').val('');
						showPopupError('Телефон подтвержден','Номер телефона подтвержден');
						saveData({'phone':phone,'phoneCode':phoneCode});
						pConfirm = true;
					}
					else{
						$('#phone-code').val(oldPhone);
						pConfirm ? $('#phone-inp').addClass('confirm') : $('#phone-inp').removeClass('confirm');
						$('#phone-inp b').attr('title','Почта не подтверждена').show();
						$('#phone-inp').removeClass('focus');
						$pVeil.removeClass('code');
						$('#phone-code-inp').fadeOut();
						$('#phone-code-inp div').fadeOut();
						var country = '';
						if(oldCode=='7') // RF
							country = 'ru';
						if(oldCode=='380') // UA
							country = 'ua';
						if(oldCode=='375') // BY
							country = 'by';

						$('.country-phone-selected img').removeClass().addClass('flag flag-'+country);
						$('.country-phone-selected span').text('+'+oldCode);
						$('#phone-code-inp input').val('');
						codeInp.val(oldCode);
						showPopupError('Ошибка','Номер телефона не подтвержден');
					}
				}
			});
		}
	});
	//
	$('#email-inp em, #phone-inp em').click(function(){ //	верификация почты и телефона
		var $it = $(this),
			isEmail = ($it.closest('#email-inp').length ? 1 : 0),
			main = (isEmail ? $it.closest('#email-inp') : $it.closest('#phone-inp'));

		if(!$(main).hasClass('confirm') && !sendingCode){
			sendingCode = true;
			if(isEmail){
				$.ajax({ type:'POST', url:'/ajax/restorecode', data:'email='+oldEmail });
				$('#email-code').fadeIn();
				$eVeil.addClass('code').fadeIn();
				$('#email-inp div').fadeOut();
				$('#email-inp p').fadeOut();
				showPopupError('Проверка почты','На почту выслан код для подтверждения. Введите его в поле "Проверочный код"');
			}
			else{
				$.ajax({ type:'POST', url:'/ajax/restorecode', data:'phone='+oldPhone });
				$('#phone-code').fadeIn();
				$pVeil.addClass('code').fadeIn();
				$('#phone-inp div').fadeOut();
				$('#phone-inp p').fadeOut();
				showPopupError('Проверка телефона','На телефон выслан код для подтверждения. Введите его в поле "Проверочный код"');				
			}	
		}
	});
	//
	//		ПАРОЛЬ
	//	ввод пароля
	$('#psw-inp span').click(function(){
		var input = $(this).hide().siblings('input');

		$(input).val('').focus();
		$('#new-psw-inp').fadeIn();
	});
	//
	// потеря фокуса пароля
	$(document).click(function(e){
		var $it = $(e.target),
			val = $('#psw-inp input').val();

		if(!$it.closest('#psw-inp').length && !$it.closest('#new-psw-inp').length && !$it.closest('#MWwrapper').length && val===''){
			$('#psw-inp input').val('password');
			$('#psw-inp span').show();
			$('#new-psw-inp').fadeOut();
			$('#new-psw-inp input').eq(0).val('');
			$('#new-psw-inp input').eq(1).val('');
		}
		/*
		*		потеря фокуса телефона
		*/
		var main = $('.country-phone');
		if(main.hasClass('focus') && !$it.closest('.country-phone').length && !$it.is('#phone-inp .set-priv__veil') ) {
			var codeInp = $('[name="__phone_prefix"]'),
					code = codeInp.val(),
					len, phoneLen;

			val = $('#phone-code').val();
			len = val.length;
			$btn = $('#phone-inp .set-priv__btn');
			$veil = $('#phone-inp .set-priv__veil');
			$conf = $('#phone-inp>b');
			main.removeClass('focus');

			if(code.length==3) phoneLen = 9; // UKR, BEL
			if(code.length==1) phoneLen = 10; // RF

			if(len==phoneLen) {
				if(val!==oldPhone || code!=oldCode){
					$btn.fadeIn();
					$veil.fadeOut();
					$conf.hide();
				}
				else{
					$btn.fadeOut();
					$veil.fadeIn();
					$conf.show();
				}
			}
			else {
				var country = '';
				if(oldCode=='7') // RF
					country = 'ru';
				if(oldCode=='380') // UA
					country = 'ua';
				if(oldCode=='375') // BY
					country = 'by';

				$('.country-phone-selected img').removeClass().addClass('flag flag-'+country);
				$('.country-phone-selected span').text('+'+oldCode);
				$('#phone-code').val(oldPhone);
				codeInp.val(oldCode);
				$btn.fadeOut();
				$veil.fadeIn();
				$conf.show();
			}
		}
	});
	//
	//	ввод паролей
	$('#psw-inp input, #new-psw-inp input').keyup(function(){
		var newPsw = $('#new-psw-inp input').eq(0).val(),
			vrfPsw = $('#new-psw-inp input').eq(1).val(),
			$btn = $('#new-psw-inp .set-priv__btn');

		(newPsw===vrfPsw && newPsw!=='') ? $btn.fadeIn() : $btn.fadeOut();
	});
	//
	//	изменение пароля
	$('#new-psw-inp .set-priv__btn').click(function(){
		var oldPsw = $('#psw-inp input').val(),
			newPsw = $('#new-psw-inp input').eq(0).val(),
			vrfPsw = $('#new-psw-inp input').eq(1).val(),
			$btn = $('#new-psw-inp .set-priv__btn');

		$(this).fadeOut();

		if(newPsw===vrfPsw && newPsw!=='' && oldPsw!=='')
			saveData({ 'oldpsw':oldPsw, 'newpsw':newPsw});
	});
	//
	//
	$('#e-analytic').change(function(){

		if($(this).is(':checked')){
			$('#analytic').fadeIn();
		}
		else{
			$('#analytic').fadeOut();
		}
	});
	//
	$('#settings-save').click(function(e){
	/*	var errors = true;
		e.preventDefault();

		var val = $('#s-p-email').val(),
			field = $('#s-p-email').closest('.settings-priv__field');

		if(epattern.test(val)){
			if(val!==oldEmail){
				$.ajax({
					type: 'POST',
					url: '/ajax/emailVerification',
					data: 'nemail='+val+'&oemail='+oldEmail,
					dataType: 'json',
					success: function(error){
						if(error){
							addErr(field);
							showPopupError('Новый e-mail адрес уже используется в системе');
						}
						else remErr(field);
					}
				});
			}
			else remErr(field);
		}
		else addErr(field);



		var arErrors = $('.error');
		if(arErrors.length>0)
			$('html, body').animate({ scrollTop: $(arErrors[0]).offset().top-20 }, 1000);

		if(!errors && !arErrors.length){
			$('#settings-form').submit();
			//console.log($('#epa-edit-form').serializeArray());			
		}*/
	});
	//
	var timerHintEmail, timerHintPhone;
	$(document).mousemove(function(e){
		if($(e.target).closest('#email-inp b').length || $(e.target).is('#email-inp b')){
			$('#email-inp p').fadeIn(300);
			clearTimeout(timerHintEmail);
		};
		if($(e.target).closest('#phone-inp b').length || $(e.target).is('#phone-inp b')){
			$('#phone-inp p').fadeIn(300);
			clearTimeout(timerHintPhone);
		}
	})
	.mouseout(function(e){
		if(!$(e.target).closest('#email-inp b').length && !$(e.target).is('#email-inp b')){
			clearTimeout(timerHintEmail);
			timerHintEmail = setTimeout(function(){ $('#email-inp p').fadeOut(300) },500);
		}
		if(!$(e.target).closest('#phone-inp b').length && !$(e.target).is('#phone-inp b')){
			clearTimeout(timerHintPhone);
			timerHintPhone = setTimeout(function(){ $('#phone-inp p').fadeOut(300) },500);
		}
	});
	//
	//
	// функция проверки почты
	function verificationEmail(e, v){ 
		if(e){
			$('#email-inp input').val(oldEmail);
			showPopupError('Ошибка','Новый e-mail адрес уже используется в системе');
		}
		else if(!sendingCode){
			sendingCode = true;
			$.ajax({ type:'POST', url:'/ajax/restorecode', data:'email='+v });
			$('#email-code').fadeIn();
			$eVeil.addClass('code').fadeIn();
			$('#email-inp div').fadeOut();
			showPopupError('Проверка почты','На новую почту выслан код для подтверждения. Введите его в поле "Проверочный код"');			
		}
	}
	//
	//	функция сохранения почты
	function saveData(d){
		if(d.email!==oldEmail)
			oldEmail = d.email;

		if(d.phone!==oldPhone) {
			oldPhone = d.phone;
			oldCode = d.phoneCode;
			d.phone = d.phoneCode + d.phone;
		}

		$.ajax({ 
			type : 'POST', 
			url : '/ajax/savesettings', 
			data : d,
			success: function(d){
				d = JSON.parse(d);
				if(d.type=='psw'){
					if(d.error){
						showPopupError('Ошибка','Старый пароль не соответствует введенному');
						$('#new-psw-inp .set-priv__btn').fadeOut();
						$('#psw-inp input').val('');
					}
					else{
						showPopupError('Изменение пароля','Новый пароль сохранен успешно');
						$('#psw-inp input').val('password');
						$('#psw-inp span').show();
						$('#new-psw-inp').fadeOut();
						$('#new-psw-inp input').eq(0).val('');
						$('#new-psw-inp input').eq(1).val('');
					}
				}
			}
		});
	}
	function addErr(e){ 
		$(e).addClass('error');
		return false;
	}
	function remErr(e){ 
		$(e).removeClass('error');
		return true;
	}
	function showPopupError(t, m){
		var html = "<form data-header='" + t + "'>" + m + "</form>";
		ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
	}
	// становить все конкретные уведомления
	$('#s-n-email').change(function(){
		var arEInputs = $('.settings-notif__point input').filter(':even'),
			checked = ($(this).is(':checked') ? true : false);

		$.each(arEInputs, function(){ $(this).prop('checked', checked) });
	});
	$('#s-n-push').change(function(){
		var arPInputs = $('.settings-notif__point input').filter(':odd'),
			checked = ($(this).is(':checked') ? true : false);

		$.each(arPInputs, function(){ $(this).prop('checked', checked) });
	});
	//	проверка установки уведомлений
	$('.settings-notif__point input').change(function(){
		var arEInputs = $('.settings-notif__point input').filter(':even'),
			arPInputs = $('.settings-notif__point input').filter(':odd'),
			cntTrue = 0,
			cntFalse = 0;

		$.each(arEInputs, function(){ $(this).prop('checked') ? cntTrue++ : cntFalse++ });
		if(cntTrue>0)
			$('#s-n-email').prop('checked',true);
		if(cntFalse==arEInputs.length)
			$('#s-n-email').prop('checked',false);

		cntTrue = 0;
		cntFalse = 0;

		$.each(arPInputs, function(){ $(this).prop('checked') ? cntTrue++ : cntFalse++ });
		if(cntTrue>0)
			$('#s-n-push').prop('checked',true);
		if(cntFalse==arPInputs.length)
			$('#s-n-push').prop('checked',false);
	});
	function getNum(value){ return value.replace(/\D+/g,'') }
});