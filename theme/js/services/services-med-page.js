$(function(){
	var arReq = $('.smr__required');
	// phone
	//$('#smr-phone').mask('+7(999) 999-99-99');

	$('#smr-surname').bind('keyup change blur', function(){ checkFio(this) });
	$('#smr-name').bind('keyup change blur', function(){ checkFio(this) });
	$('#smr-patronymic').bind('keyup change blur', function(){ checkFio(this) });

	$('.smr__required').bind('keyup change blur', function(){ checkFields(this) });
	// submit
	$('#service__btn').click(function(e){
		e.preventDefault();
		var flag = false;

    if(MainScript.isButtonLoading(this))
      return false;
    else
    	MainScript.buttonLoading(this,true);

		$.each(arReq,function(){
			if(checkFields(this)) flag = true;
		});

		if(flag)
		{
			MainScript.buttonLoading(this,false);
			return false;
		}
		$('#smr-form').submit();
	});

	function addEr(e){ 
		$(e).addClass('error');
		return true;
	}
	function remEr(e){ 
		$(e).removeClass('error');
		return false;
	}
	function checkFields(e){
		var r = false,
			v = $(e).val(),
			id = $(e).attr('id');

		if(id=='smr-mail'){
			var p = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
			if(v != '')
				r = p.test(v) ? remEr(e) : addEr(e);
			else
				r = addEr(e);
		}
		else if(id=='phone-code') {
			var main = $(e).closest('.country-phone')[0],
					code = $('[name="__phone_prefix"]').val(),
					phone = v.replace(/\D+/g,''),
					l = phone.length,
          phoneLen = 10;

			if(code.length==3) // UKR, BEL
				phoneLen = 9;
			if(code.length==1) // RF
				phoneLen = 10;

			r = (l!=phoneLen ? addEr(main) : remEr(main));
		}
		else{
			r = ( ($(e).is('select') && v==null) || ($(e).is('input') && v=='') ) 
				? addEr(e) 
				: remEr(e);
		}
		return r;
	}
	function checkFio(e){
		var v = $(e).val(),
			nv = v.replace(/[^а-яА-ЯїЇєЄіІёЁ ]/g,''),
			arMany = nv.split(' ');
			if(arMany.length){
				$.each(arMany, function(i){
					arMany[i] = arMany[i].charAt(0).toUpperCase() + arMany[i].slice(1).toLowerCase();
				});
				nv = arMany.join(' ');
			}
			else{
				nv = nv.charAt(0).toUpperCase() + nv.slice(1).toLowerCase();
			}

			$(e).val(nv);
	}
});