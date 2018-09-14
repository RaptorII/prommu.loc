'use strict'
var GeoProgram = (function () {
	GeoProgram.prototype.bDate = $('[name="bdate"]').val();
	GeoProgram.prototype.eDate = $('[name="edate"]').val();
	GeoProgram.prototype.ID = $('.project-inp').val();
	//
	function GeoProgram() {
		this.init();
  }
  //
	GeoProgram.prototype.init = function () {
		let self = this;
		// работа с датами
		self.bDate = self.getDateFromData(self.bDate);
		self.eDate = self.getDateFromData(self.eDate);
		$.each($('.calendar'), function(e,item) {
			let v = item.nextElementSibling.value.split('.');
			self.buildCalendar(item, Number(v[2]),Number(v[1]-1));
		});
		$('#filter-form').on('click', '.calendar-filter span', function() { self.showCalendar(this) });
		$('#filter-form').on('click', '.mleft', function(){ self.changeMonth(this,-1) });
		$('#filter-form').on('click', '.mright', function(){ self.changeMonth(this,1) });
		$('#filter-form').on('click', '.calendar .day', function(e){ self.checkDate(e.target) });
		// обрабатываем клики
		$(document).on('click', function(e) {
			self.checkCity(e.target);
			self.closureCalendar(e.target);
		});
	}
	//		Выбор города в фильтре
	GeoProgram.prototype.checkCity = function (e) {
		let self = this,
			list = '.city-list';

		if($(e).hasClass('city-filter') || $(e).hasClass('city-filter__select'))
			$(list).fadeIn();
		else if($(e).is('li') && $(e).closest(list).length) {
			$(list).fadeOut();
			$('.city-input').val(e.dataset.id);
			$('.city-filter__select').text($(e).text());
			self.ajaxFilterList();
		}
		else {
			$(list).fadeOut();
		}
	}
	//		Фильтрация по параметрам
	GeoProgram.prototype.ajaxFilterList = function () {
/*		let data = $('#filter-form').serialize();

		setTimeout(function(){
			$('.filter__veil').show();
			$('#geo-list').html('Здесь будут отфильтрованные данные с сервера');
			$('#user-data').html('Здесь будут отфильтрованные данные с сервера');
			console.log(data);
		},500);
		setTimeout(function(){ $('.filter__veil').hide(); },1000);
*/

		$('.filter__veil').show();

		$.ajax({
			type: 'GET',
			url: window.location.pathname,
			data: $('#filter-form').serialize(),
			success: function(r) {
			$('#geo-list').html(r);
			$('.filter__veil').hide();
			},
		});
	}
	//
	//      ДАТА
	//
	//		Создание календарей
	GeoProgram.prototype.buildCalendar = function (item, year, month) {
		year = (typeof year=="undefined" ? new Date().getFullYear() : year);
		month = (typeof month=="undefined" ? new Date().getMonth() : month);

		let self = this,
			Dlast = new Date(year,month+1,0).getDate(),
			D = new Date(year,month,Dlast),
			DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
			DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
			content = '<tr>',
			arMonth = [
			    "Январь","Февраль","Март","Апрель",
			    "Май","Июнь","Июль","Август",
			    "Сентябрь","Октябрь","Ноябрь","Декабрь"
			],
			date = new Date(),
			main = $(item).closest('.geo__header-date')[0],
			parent = $(item).closest('.calendar-filter')[0],
			arCalendars = $(main).find('.calendar-filter'),
			begDate = $(arCalendars[0]).find('input').val(),
			endDate = $(arCalendars[1]).find('input').val(),
			type,body,mName,nDays,newDate;

		date.setHours(0, 0, 0, 0);

		if(begDate!=='')
			begDate = self.getDateFromData(begDate);
		if(endDate!=='')
			endDate = self.getDateFromData(endDate);

		for( let i=0, n=arCalendars.length; i<n; i++ )
			if( $(arCalendars[i]).is(parent) )
				type = i;

		if(DNfirst != 0) {
			for(let i = 1; i < DNfirst; i++) content += '<td>';
		} else {
			for(let i = 0; i < 6; i++) content += '<td>';
		}
		for(let i = 1; i <= Dlast; i++) {
			content += '<td class="day';
			newDate = new Date(D.getFullYear(),D.getMonth(),i);
			newDate.setHours(0, 0, 0, 0);

			if(self.diffDate(newDate,date)==0)
				content += ' today'; // today
			if(self.diffDate(newDate, self.bDate)<0)
				content += ' nofit'; // выход за дату начала
			if(self.diffDate(newDate, self.eDate)>0)
				content += ' nofit'; // выход за дату окончания
			if(type==0 && endDate>0 && self.diffDate(newDate,endDate)>0)
				content += ' nofit'; // обозначаем недоступные дни
			else if(type==1 && begDate>0 && self.diffDate(newDate,begDate)<0)
				content += ' nofit'; // обозначаем недоступные дни
			else if(endDate==='' && self.diffDate(newDate,begDate)==0)
				content += ' select'; // обозначаем выделенные дни
			else if(begDate==='' && self.diffDate(newDate,endDate)==0)
				content += ' select'; // обозначаем выделенные дни
			else if(
				endDate>0 && begDate>0
				&&
				self.diffDate(newDate,begDate)>=0
				&&
				self.diffDate(newDate,endDate)<=0
			)
				content += ' select'; // обозначаем выделенные дни

			content += '">' + i;
			if(new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0)
				content += '<tr>';
		}
		for(let i = DNlast; i < 7; i++) content += '<td>&nbsp;';

		body = item.querySelector('tbody');
		body.innerHTML = content;
		mName = item.querySelector('.mname');
		mName.innerHTML = arMonth[D.getMonth()] +' '+ D.getFullYear();
		mName.dataset.month = D.getMonth();
		mName.dataset.year = D.getFullYear();

		nDays = item.querySelectorAll('tbody tr');
		if(nDays.length < 6) { // всегда 6 строк
			content = '<tr>';
			for(let i=0; i<7; i++)
				content += '<td class="empty">&nbsp;';
			body.innerHTML += content;
		}
	}
	//      Проверка даты
	GeoProgram.prototype.checkDate = function (day) {
		let self = this,
			$it = $(day),
			main = $it.closest('.geo__header-date')[0],
			arCalendars = $(main).find('.calendar-filter'),
			begDate = $(arCalendars[0]).find('input').val(),
			endDate = $(arCalendars[1]).find('input').val(),
			parent = $it.closest('.calendar-filter')[0],
			data = $(parent).find('.mname')[0].dataset,
			calendar = $(parent).find('.calendar')[0],
			output = $(parent).find('span')[0],
			input = $(parent).find('input')[0],
			d = Number($(day).text()),
			m = Number(data.month),
			y = Number(data.year),
			newDate = new Date(y, m, d),
			res1, res2;

		if( $(day).hasClass('empty')  || $(day).hasClass('nofit') )
			return false;

		$(calendar).fadeOut();
		res1 = ('0' + $(day).text()).slice(-2) + '.'
		+ ('0' + (Number(data.month) + 1)).slice(-2) + '.';
		res2 = res1 + data.year;
		res1 = res1 + data.year.slice(-2);

		$(output).text(res1);
		$(input).val(res2);
		self.buildCalendar(arCalendars[0], y, m);
		self.buildCalendar(arCalendars[1], y, m);
		self.ajaxFilterList();
	}
	//      Определение разницы во времени
	GeoProgram.prototype.diffDate = function (date1, date2) {
		let miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
		return Math.ceil((date1 - date2) / miliToDay);
	}
	//      форматируем в формат даты
	GeoProgram.prototype.getDateFromData = function (date) {
		let arDate = date.split('.'),
				obj = new Date(
						Number(arDate[2]),
						Number(arDate[1]-1),
						Number(arDate[0])
					);
		return obj.setHours(0,0,0,0);
	}
	//      Изменение месяца
	GeoProgram.prototype.changeMonth = function (e, m) {
		let self = this,
			calendar = $(e).closest('.calendar')[0],
			data = $(e).siblings('.mname')[0].dataset,
			newMonth = parseFloat(data.month)+m;

		self.buildCalendar(calendar, data.year, newMonth);
	}
	//      Вывод календаря
	GeoProgram.prototype.showCalendar = function (e) {
		let calendar = e.nextElementSibling;
		$(calendar).fadeIn();
	}
	//      Закрытие календаря
	GeoProgram.prototype.closureCalendar = function (e) {
		let arCalendars = $('.calendar');

		for(let i=0, n=arCalendars.length; i<n; i++) {
			if($('.calendar').is(e) && !$(arCalendars[i]).is(e)) { // это точно календарь
				$(arCalendars[i]).fadeOut();
			}
			else if($(e).closest('.calendar').length) { // это составные календаря
				let calendar = $(e).closest('.calendar')[0];
				if(!$(arCalendars[i]).is(calendar))
					$(arCalendars[i]).fadeOut();
			}
			else if($('.geo__header-date span').is(e)) { // это поле даты
				let calendar = $(e).siblings('.calendar')[0];
				if(!$(arCalendars[i]).is(calendar))
					$(arCalendars[i]).fadeOut();
			}
			else // это что-то другое
				$(arCalendars[i]).fadeOut();
		}
	}
	//
	return GeoProgram;
}());
/*
*
*/
$(document).ready(function () {
	new GeoProgram();
});