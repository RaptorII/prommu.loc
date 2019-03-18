$(function(){
	var arVacs = $('.sd__vacancy'),
		$btn = $('.repost-button');

	$('.sd__vacancy').on('click','input',function(e){
		var isChanged = false;

		for(var i=0; i<arVacs.length; i++){
			var inpVk = $(arVacs[i]).find('input').eq(0),
				inpFb = $(arVacs[i]).find('input').eq(1),
				inpTl = $(arVacs[i]).find('input').eq(2),
				id = arVacs[i].dataset.id,
				repost =  arVacs[i].dataset.repost,
				newRepost = 0;

			newRepost = $(inpVk).is(':checked') ? '1' : '0';
			newRepost += $(inpFb).is(':checked') ? '1' : '0';
			newRepost += $(inpTl).is(':checked') ? '1' : '0';

			if(newRepost!==repost) isChanged = true;
		}

		isChanged ? $btn.fadeIn() : $btn.fadeOut();
	});
	$btn.click(function(e){
		e.preventDefault();
    if(MainScript.isButtonLoading(this))
    {
      return false;
    }
    else
    {
      MainScript.buttonLoading(this,true);
      $('#repost-form').submit();
    }
	});
});