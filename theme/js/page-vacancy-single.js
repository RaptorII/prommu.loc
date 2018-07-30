$(function(){
	var curDate = new Date();
		
	$.each($('.sv__data-calendar'), function() {
		if(arLoc[this.dataset.id] == null)
			$(this).hide();
	});
	//
	//	Событие календаря
	//
	$(document).on('click', function(e){
		var it = e.target,
			arCalendars = $('.sv__data-calendar div');

		if($(it).hasClass('sv__data-calendar')){
			$.each($('.sv__data-calendar div'), function(){
				var prnt = $(this).closest('.sv__data-calendar');
				if(prnt.is(it))
					Calendar(it, curDate.getFullYear(), curDate.getMonth());
				else
					$(this).fadeOut();
			});
		}
		else if($(it).hasClass('sv__data-calendar-name')){
			$.each($('.sv__data-calendar div'), function(){
				var prnt = $(this).closest('.sv__data-calendar'),
					itPrnt = $(it).closest('.sv__data-calendar');
				if(prnt.is(itPrnt))
					Calendar(itPrnt[0], curDate.getFullYear(), curDate.getMonth());
				else
					$(this).fadeOut();
			});
		}
		else if(!$(it).is('.sv__data-calendar') && !$(it).closest('.sv__data-calendar').length){
			$.each(arCalendars, function(){ $(this).fadeOut() });
		}
		if($(it).hasClass('mright') || $(it).hasClass('mleft')){
			var month = $(it).siblings('.mname'),
				prnt = $(it).closest('.sv__data-calendar');
				op = ($(it).hasClass('mright') ? 1 : -1);

			Calendar(prnt[0], month[0].dataset.year, parseFloat(month[0].dataset.month)+op);
		}
	});
	//
	//	функции
	//
	function Calendar(item, year, month){
		var Dlast = new Date(year,month+1,0).getDate(),
			D = new Date(year,month,Dlast),
			DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
			DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
			calendar = '<table><thead><tr><td class="mleft"><td colspan="5" class="mname"><td class="mright">'+
				'<tr><td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс'+
				'<tbody><tr>',
			month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];

		if(DNfirst != 0)
			for(var  i = 1; i < DNfirst; i++) calendar += '<td><span></span>';
		else
			for(var  i = 0; i < 6; i++) calendar += '<td><span></span>';

		for(var  i = 1; i <= Dlast; i++){
			newDate = new Date(D.getFullYear(),D.getMonth(),i);
			content = getPeriodData(newDate, item.dataset.id);
			if(content != ''){
				calendar += '<td class="active"><span>' + i + '</span>' + content;
			}
			else if(i==curDate.getDate() && D.getFullYear()==curDate.getFullYear() && D.getMonth()==curDate.getMonth()) // today
				calendar += '<td class="today"><span>' + i + '</span>';
			else
				calendar += '<td class="day"><span>' + i + '</span>';

			if(new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0) {
				calendar += '<tr>';
			}
		}
		if(DNlast)
			for(var i = DNlast; i < 7; i++)
				calendar += '<td><span></span>';
		var block = $(item).find('div');
		block.html(calendar);
		mName = item.querySelector('.mname');
		mName.innerHTML = month[D.getMonth()] +' '+ D.getFullYear();
		mName.dataset.month = D.getMonth();
		mName.dataset.year = D.getFullYear();
		block.fadeIn();
	}
	//
	function getDate(e){
		var result = 0;
		arDate = e.split('.');
		result = new Date(Number(arDate[2]),Number(arDate[1]-1),Number(arDate[0]));
		return result;
	}
	//
	function diffDate(date1, date2){
		miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
		return Math.ceil((date1 - date2) / miliToDay);
	}
	//
	function getPeriodData(date, idcity){
		var res = '',
			d = Number(date.getDate()),
			m = Number(date.getMonth()),
			y = Number(date.getFullYear());

		if(arLoc[idcity] != null){
			res += '<div>';
			bCnt = false;
			$.each(arLoc[idcity], function(i, loc){
				if(
					diffDate(date, getDate(loc.time[0])) >= 0
					&&
					diffDate(getDate(loc.time[1]),date) >= 0
				){
					res += '<strong>' + loc.name + '</strong><br>' + 
						loc.addr + '<br>' + 
						loc.time[0] + ' - ' + loc.time[1] + '<br>' +
						loc.time[2] + ' - ' + loc.time[3] + '<br>';
					bCnt = true;
				}
			});
			bCnt ? res+='</div>' : res='';
		}
		return res;
	}
});