jQuery(function($){
	var $form = $('.register-form'),
		$email = $('#EdEmail'),
		$phone = $('.country-phone'),
		epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
		startCheckEmail = false,
		$psw = $('#EdPass'),
		$cnfPsw = $('#EdPassRep');
	// имя	
	$('#phone-code').show();
	if($('[name="type-reg"]:checked').val()==2){
		$email.hide().val('').attr('data-field-check','');
		$phone.css({display:'table'});
		$('.reg-form__label.com3').addClass('mob');
		$('.reg-form__label.emp2').addClass('mob');
		$form.prop('action',$form.data('phone'));
	}

	$('#EdName').on('input', function(){
		var $it = $(this);
		if($form.attr('id')=='F1registerEmpl')
			$it.val($it.val().charAt(0).toUpperCase() + $it.val().slice(1));
		else
			$it.val($(this).val().charAt(0).toUpperCase() + $it.val().slice(1).toLowerCase());
		$it.val()=='' ? $it.addClass('error') : $it.removeClass('error');
	});
	// фамилия
	$('#EdLname').on('input', function(){ // first symbol to upper case
		var $it = $(this);
		$it.val($it.val().charAt(0).toUpperCase() + $it.val().slice(1).toLowerCase());
		$it.val()=='' ? $it.addClass('error') : $it.removeClass('error');
	});
	// email
	$('#EdEmail').on('input', function(){ // first symbol to upper case
		var $it = $(this),
			val = $it.val();
		if(epattern.test(val)){
			startCheckEmail = true;
			$it.removeClass('error');
		}
		if((!epattern.test(val) || val==='') && startCheckEmail){
			$it.addClass('error')
		}
	});
	// потеря фокуса email
	$email.blur(function(){
		var $it = $(this),
			val = $it.val();
		if(!epattern.test(val) || val===''){
			startCheckEmail = true;
			$it.addClass('error');
		}
		else{
			$it.removeClass('error');
		}	
	});
	// выбор типа контакта
	$('[name="type-reg"]').change(function(){
		if($(this).val()==1){
			$email.show().attr('data-field-check','name:Email,empty,email');
			$phone.hide();
			$('#phone-code').val('');
			$('.reg-form__label.com3').removeClass('mob');
			$('.reg-form__label.emp2').removeClass('mob');
			$form.prop('action',$form.data('email'));
		}
		else{
			$email.hide().val('').attr('data-field-check','');
			$phone.css({display:'table'});
			$('.reg-form__label.com3').addClass('mob');
			$('.reg-form__label.emp2').addClass('mob');
			$form.prop('action',$form.data('phone'));
		}
	});
	//

	// нажатие кнопки
	$('.reg-form__btn').click(function(e){
		var state = $('[name="type-reg"]:checked').val(),
			psw = $psw.val(),
			cnfPsw = $cnfPsw.val(),
			error = false;

		e.preventDefault();

		if(MainScript.isButtonLoading(this))
		{
			return false;
		}
		else
		{
			MainScript.buttonLoading(this,true);
		}

		$('#EdName').val()==='' ? $('#EdName').addClass('error') : $('#EdName').removeClass('error');
		$('#EdLname').val()==='' ? $('#EdLname').addClass('error') : $('#EdLname').removeClass('error');

		// контакты
		if(state==1){	// почта
			valEmail = $email.val();
			if(!epattern.test(valEmail) || valEmail===''){
				startCheckEmail = true;
				$email.addClass('error');
			}
			else{
				$email.removeClass('error');
			}
			$('#phone-code').closest('.country-phone').removeClass('error');
		}
		else{	// телефон
			code = $('[name="__phone_prefix"]').val();
			if(code.length==1)
				phoneLen = 10;
			if(code.length==3)
				phoneLen = 9;
			else
				phoneLen = 10;

			if($('#phone-code').val().replace(/\D+/g,'').length<phoneLen){
            	$('#phone-code').closest('.country-phone').addClass('error');
				error = true;
			}
			else{
				$('#phone-code').closest('.country-phone').removeClass('error');
			}
			$email.removeClass('error');
		}
		// пароли
		if(psw!==cnfPsw || psw==='' || cnfPsw===''){
			error = true;
			$psw.addClass('error');
			$cnfPsw.addClass('error');
		}
		else{
			$psw.removeClass('error');
			$cnfPsw.removeClass('error');
		}

		if(!$('.error').length && !error)
		{
			if(state==2){
				var newPhone = '+' + $('[name="__phone_prefix"]').val() + $('#phone-code').val();
				$('[name="email"]').val(newPhone);
			}	
			$form.submit();
		}
		else
		{
			MainScript.buttonLoading(this,false);
		}
		
	});
	
});