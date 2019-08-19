jQuery(function($){
	var page = $('.vacs-info').data('page');

	$('.change-btn').each(function(){
		var main = $(this).closest('tr')[0],
			sBlock = $(main).find('.control-block-cont')[0];
			s = main.dataset.status,
			content = '',
			arRes = [];

		if(s==='0' || s==='1' || s==='3') arRes.push({'s':5, 't':'Утвердить'});
		if(s==='0' || s==='3' || s==='5') arRes.push({'s':1, 't':'Отложить'});
		if(s==='0' || s==='1' || s==='5') arRes.push({'s':3, 't':'Отклонить'});

		$.each(arRes, function(){ content += '<span class="btn__orange" data-st=' + this.s + '>' + this.t + '</span>' });
		$(sBlock).empty().html(content);
		//$('.change-btn').fadeOut();
	});
	//
	$('.control-block-cont').on('click', 'span', function(e){
		var main = $(this).closest('tr')[0],
			s = e.target.dataset.st;

		res = (s==='3' ? confirm('Вы действительно хотите отклонить заявку') : true);

		$('#DiContent .content-block').addClass('load');

		if(res)
		{
			$.post(MainConfig.AJAX_POST_SETRESPONSESTATUS, { idres:main.dataset.sid, s:s }, function(d){
				d = JSON.parse(d);
				if(!d.error)
				{
					if(s==='5')
						showPopupMess('Утверждено', 'Заявка успешно утверждена');
					else if(s==='1')
						showPopupMess('Отложено', 'Заявка отложена и перенесена в раздел "Отложенные"');
					else
						showPopupMess('Отклонено', 'Заявка отклонена и перенесена в раздел "Отклоненные"');
					$('#DiContent .content-block').removeClass('load');
				}
			});
		}
	});
	//
	function showPopupMess(t, m){
		var html = "<form data-header='" + t + "'>" + m + "</form>";
		ModalWindow.open({ 
			content: html, 
			action: { active: 0 },
			additionalStyle:'dark-ver',
			afterClose: function(){ location.reload() }
		});
	}
});