jQuery(function($){
	var $form = $('#F1registerAppl'),
		$email = $('#EdEmail'),
		$phone = $('#phone-code'),
		$pass = $('#EdPass'),
		$pCode = $('.country-phone'),
		epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
		startCheckEmail = false;
	// выбор типа
	$('[name="type-reg"]').change(function(){
		var $it = $(this);
		if($it.val()==1){
			$pCode.removeClass('error').hide();
			$phone.val('');
			$email.show();
		}
		else{
			$email.val('').removeClass('error').hide();
			$pCode.css({display:'table'});
		}
	});
	//
	$email.on('input', function(){ // email
		var $it = $(this),
			val = $it.val();
		if(epattern.test(val)){
			startCheckEmail = true;
			$it.removeClass('error');
		}
		if((!epattern.test(val) || val==='') && startCheckEmail){
			$it.addClass('error')
		}
	})
	.blur(function(){ // потеря фокуса email
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
	//
	$pass.bind('input blur', function(){ // пароль
		$(this).val()==='' ? $(this).addClass('error') : $(this).removeClass('error');
	});

	// нажатие кнопки
	$('.auth-form__btn').click(function(e){
		var state = $('[name="type-reg"]:checked').val(),
			error = false;

		e.preventDefault();

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
			$('.country-phone').removeClass('error');
		}
		else{	// телефон
			code = $('[name="__phone_prefix"]').val();
			if(code.length==1) phoneLen = 10; // код РФ 
			if(code.length==3) phoneLen = 9;	// Украина, Беларусь
			else phoneLen = 10;

			if($('#phone-code').val().replace(/\D+/g,'').length<phoneLen){
            	$('.country-phone').addClass('error');
				error = true;
			}
			else{
				$('.country-phone').removeClass('error');
			}
			$email.removeClass('error');
		}
		if($pass.val()==='')
			$pass.addClass('error');

		if(!$('.error').length && !error){
			if(state==2){
				var newPhone = '+' + $('[name="__phone_prefix"]').val() + $phone.val();
				$email.val(newPhone);
			}	
			$form.submit();
			//console.log($form.serializeArray());
		}
		
	});
});