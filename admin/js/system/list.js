'use strict'
jQuery(function($){
	$(document).on(
		'click',
		'.system-module tbody td',
		function(e){ 
			var parent = $(this).closest('.system-module')[0],
					type = parent.dataset.type,
					id = $(this).siblings('td').eq(0).text(),
					url = '/admin/system/' + id + '?type=' + type;

			if(!$(this).hasClass('empty'))
				$(location).attr('href',url);
		});
	//
	$('#search_form .btn').click(function(){
		var value = $('#search_form input').val(),
				params = {
						'CodeReview[name]':$('[name="CodeReview[name]"]').val(),
						'CodeReview[author]':$('[name="CodeReview[author]"]').val(),
						'CodeReview_page':'1',
						'search':value
					};

		$.fn.yiiGridView.update("review_table",{data:params});
		$('#search_form .clear').fadeIn();
	});
	$('#search_form .clear').click(function(){
		var params = {
						'CodeReview[name]':$('[name="CodeReview[name]"]').val(),
						'CodeReview[author]':$('[name="CodeReview[author]"]').val(),
						'CodeReview_page':'1',
						'search':''
					};

		$('#search_form input').val('');
		$.fn.yiiGridView.update("review_table",{data:params});
		$('#search_form .clear').fadeOut();
	});
});