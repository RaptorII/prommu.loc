jQuery(function($){
	var $form = $('#F1registerAppl'),
		$btn = $('#reg-fb-btn'),
		$email = $('#EdEmail'),
		epattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

	$btn.click(function(e){
		e.preventDefault();

		if($email.val()!='' && epattern.test($email.val())){
			$email.removeClass('error');
			$form.submit();
		}
		else{
			$email.addClass('error');
		}
	});
});