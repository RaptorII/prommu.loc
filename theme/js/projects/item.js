'use strict'
jQuery(function($){
	var veil = $('.bg_veil').clone(),
		map = $('.personal__map').clone();

	$('body').append(veil);
	$('body').append(map);
	veil.remove();
	map.remove();

	$('.personal__item-add a').click(function(){showMap(this)});
	$('.bg_veil,.personal__map b').click(closeMap);

	function showMap(e){
		var name = $(e)
			.closest('.personal__item')
			.find('.personal__item-name').text();

		$('.personal__map-header span').text(name);
		$('.bg_veil').fadeIn();
		$('.personal__map').fadeIn();
	}
	function closeMap(){
		$('.bg_veil').fadeOut();
		$('.personal__map').fadeOut();
	}	
});