jQuery(function($){
	var curDate = new Date(),
		$form = $('#analytics-form'),
		$content = $('#analytics-content'),
		$load = $('#analytics-veil'),
		$btn = $('#analytics-submit');

	google.charts.load('current', {packages: ['corechart', 'line']});
	google.charts.setOnLoadCallback(drawGraph);

	//  строим календари
	$.each($('.pa__cal-table'), function(){
		Calendar(this, curDate.getFullYear(), curDate.getMonth());
	});
	// открываем/закрываем календари
	$(document).on('click',function(e){
		var $it = $(e.target),
			arCalendars = $('.pa__calendar');
		if($it.hasClass('pa-filter__date')){
			var main = $it.siblings('.pa__calendar');
			$.each(arCalendars, function(){
				if(!$(this).is(main))
					$(this).fadeOut();
			});
			$(main).fadeIn();			
		}
		else if(!$it.hasClass('pa__calendar') && !$it.closest('.pa__calendar').length){
			$('.pa__calendar').fadeOut();
		}
		if($it.hasClass('day') && !$it.hasClass('none')){
			$it.closest('.pa__calendar').fadeOut();
		}
	});
	//  выбор даты
	$(document).on('click', '.pa__cal-table .day', function(){ checkDate(this) });
	//  переключаем месяцы
	$('.m-left').click(function(e){
		var table = $(this).closest('table')[0],
			d = e.target.nextElementSibling.dataset;
		Calendar(table, d.year, parseFloat(d.month)-1);
	});
	$('.m-right').click(function(e){ 
		var table = $(this).closest('table')[0];
			d = e.target.previousElementSibling.dataset;
		Calendar(table, d.year, parseFloat(d.month)+1);
	});
	// выбора события
	$('#pa-event').change(function(){ modulesVisibility() });
	// 
	$btn.on('click', function(e){
		e.preventDefault();
		getAjaxData();
		$btn.fadeOut()
	});
	//
	//    fuctions
	//
	function Calendar(item, year, month){
		var Dlast = new Date(year,month+1,0).getDate(),
			bDate = getDate('#pa-begin'),
			eDate = getDate('#pa-end'),
			D = new Date(year,month,Dlast),
			DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
			DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
			calendar = '<tr>',
			month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
			cDate = new Date(),
			body = item.querySelector('tbody'),
			mName =  item.querySelector('.m-name'),
			begin = $(item).prop('id')=='pa-cal-begin' ? true : false;

		// пустота до начала месяца
		if(DNfirst!=0)  for(var i=1; i<DNfirst; i++)  calendar += '<td>';
		else  for(var i=0; i<6; i++)  calendar += '<td>';
		// месяц
		for(var  i=1; i<=Dlast; i++){
			nDate = new Date(D.getFullYear(),D.getMonth(),i);
			var str = '';
			if(compareDate(cDate,D,i)) // today
				str = ' today';
			if(diffDate(nDate, cDate)>0 || begin && diffDate(nDate, eDate)>0 || !begin && diffDate(nDate, bDate)<0)
				str += ' none';
			if( ( begin && compareDate(bDate,nDate,i) ) || ( !begin && compareDate(eDate,nDate,i) ) )
				str += ' select';

			calendar += '<td class="day' + str + '">' + i;

			if(new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0){
				calendar += '<tr>';
			}
		}
		// пустота после месяца
		for(var i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';

		body.innerHTML = calendar;
		mName.innerHTML = month[D.getMonth()] +' '+ D.getFullYear();
		mName.dataset.month = D.getMonth();
		mName.dataset.year = D.getFullYear();
	}
	//
	function checkDate(elem){
		var table = $(elem).closest('table')[0],
			id = $(table).prop('id'),
			bDate = getDate('#pa-begin'),
			eDate = getDate('#pa-end'),
			mName = table.querySelector('.m-name'),
			d = mName.dataset,
			arDays = $(table).find('.day');

		if(!$(elem).hasClass('none')){
			var day = ('0' + $(elem).text()).slice(-2),
				month = ('0' + (Number(d.month) + 1)).slice(-2),
				year = d.year,
				str = day + '.' + month + '.' +  year;

			idDate = id=='pa-cal-begin' ? '#pa-begin' : '#pa-end';
		
			$.each(arDays, function(){ $(this).removeClass('select') });
			$(elem).addClass('select');
			$(idDate).val(str);
			$(idDate+'-str').text(str);
			if($('*').is(idDate+'-app'))
				$(idDate+'-app').text(str);

			if(bDate.getMonth()==eDate.getMonth()){
				changeTable = id=='pa-cal-begin' ? $('#pa-cal-end') : $('#pa-cal-begin');
				Calendar(changeTable[0], year, month-1);
			}
			setTimeout(function(){ $btn.fadeIn() },500);
		}
	}
	//
	function diffDate(date1, date2){
		miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
		return Math.ceil((date1 - date2) / miliToDay);
	}
	//
	function getDate(e){
		var date = $(e).val(),
			year = date.slice(-4),
			month = Number(date.slice(3,5)) - 1,
			day = Number(date.slice(0,2));

		return new Date(year, month, day);
	}
	//
	function getAjaxData(){
		var AJAX_ANALYTICS = '/user/analytics',
			params = $form.serialize();
	
		$load.show();

		$.ajax({
			type: 'POST',
			url: AJAX_ANALYTICS,
			data: params,
			success: function(res){
				$content.html(res);
				modulesVisibility();
				$load.hide();
				google.charts.load('current', {packages: ['corechart', 'line']});
				google.charts.setOnLoadCallback(drawGraph);
			}
		});
	}
	//
	function compareDate(d1, d2, day){
		return d1.getDate()==day && d1.getFullYear()==d2.getFullYear() && d1.getMonth()==d2.getMonth();
	}
	//
	function modulesVisibility(){
		var arM = $('.pa__module'),
			v = Number($('#pa-event').val()),
			c = 0;

		$.each(arM, function(i){
			if(v>=0 && i!=v){
				$(this).hide();
				$(this).removeClass('on');
			}
			else{
				$(this).show();
				$(this).addClass('on');
				c++;
			}
		});

		if(c==arM.length){
			$.each(arM, function(){ $(this).removeClass('on') });
		}		
	}
	//
	function drawGraph(){
		if(typeof arGraph!=='undefined'){
			/*$.each(arGraph, function(i){
				var d = this[0].slice(0,2),
					m = this[0].slice(3,5),
					y = this[0].slice(-4);

				arGraph[i][0] = new Date(y,m,d);//.toLocaleString('ru', {year:'numeric',month:'long',day:'numeric'});
			});*/
			/*arGraph = [
				['01.12.17',0],
				['02.12.17',1],
				['03.12.17',4],
				['04.12.17',100],
				['05.12.17',5],
				['06.12.17',20],
				['07.12.17',10],
				['08.12.17',12],
				['09.12.17',13],
				['10.12.17',15],
				['11.12.17',1],
				['12.12.17',2],
				['13.12.17',3],
				['14.12.17',0]
			];*/
			var maxVal = 0;
			$.each(arGraph, function(){
				if(this[1]>maxVal) maxVal = this[1];
			});
			if(maxVal<5)
				arView = {max : 4, min : 0};
			else
				arView = {min : 0};

			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Date');
			//data.addColumn('date', 'Date');
			data.addColumn('number', 'Просмотров');    
			data.addRows(arGraph);

			var options = {
				chartArea : {top : 10, right : 20, bottom : 50, left : 50},
				viewWindowMode: 'explicit',
				colors : ['#abb820'],
				vAxis : {viewWindow : arView},
				series:{ 1 : {curveType : 'function'} },
				forceIFrame:true
			};

			var chart = new google.visualization.LineChart(document.getElementById('pa-chart'));
			chart.draw(data, options);
		}
	}
});