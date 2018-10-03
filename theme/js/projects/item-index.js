'use strict'
var IndexProgram = (function () {
	IndexProgram.prototype.bDate = $('[name="bdate"]').val();
	IndexProgram.prototype.eDate = $('[name="edate"]').val();
	IndexProgram.prototype.ID = $('.project-inp').val();

	function IndexProgram() {
        this.init();
    }

	IndexProgram.prototype.init = function () {
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
        // удаление города
        $('.addresses').on(
            'click', 
            '.delcity,.delloc', 
            function(e){ self.ajaxDelIndex(e.target) 
        });
        // обрабатываем клики
        $(document).on('click', function(e) {
            self.checkCity(e.target);
            self.closureCalendar(e.target);
        });
        // загружаем новый xls
        $('#add-xls').click(function(){ self.addXlsFile(this) });
        $('body').on('click','.xls-popup-btn',function(){
          $('#add-xls-inp').click();
        });
        $('#add-xls-inp').change(function() { self.checkFormatFile(this) });
    };
    //		Выбор города в фильтре
    IndexProgram.prototype.checkCity = function (e) {
    	let self = this,
    		list = '.city-list';

    	if($(e).hasClass('city-filter'))
    		$(list).fadeIn();
    	else if($(e).is('li') && $(e).closest(list).length) {
    		$(list).fadeOut();
    		$('.city-input').val(e.dataset.id);
    		$('.city-filter').text($(e).text());
    		self.ajaxFilterList();
    	}
    	else {
    		$(list).fadeOut();
    	}
    }
    //		Фильтрация по параметрам
    IndexProgram.prototype.ajaxFilterList = function () {
  		$('.filter__veil').show();

      $.ajax({
        type: 'GET',
        url: window.location.pathname,
        data: $('#filter-form').serialize(),
        success: function(r) {
          $('.addresses').html(r);
          $('.filter__veil').hide();
        },
      });
    }
    //
    //      ДАТА
    //
    //      Создание календарей
    IndexProgram.prototype.buildCalendar = function (item, year, month) {
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
            main = $(item).closest('.addr__header-date')[0],
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
    IndexProgram.prototype.checkDate = function (day) {
        let self = this,
            $it = $(day),
            main = $it.closest('.addr__header-date')[0],
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
    IndexProgram.prototype.diffDate = function (date1, date2) {
        let miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
        return Math.ceil((date1 - date2) / miliToDay);
    }
    //      форматируем в формат даты
    IndexProgram.prototype.getDateFromData = function (date) {
        let arDate = date.split('.'),
            obj = new Date(
                Number(arDate[2]),
                Number(arDate[1]-1),
                Number(arDate[0])
            );
        return obj.setHours(0,0,0,0);
    }
    //      Изменение месяца
    IndexProgram.prototype.changeMonth = function (e, m) {
        let self = this,
            calendar = $(e).closest('.calendar')[0],
            data = $(e).siblings('.mname')[0].dataset,
            newMonth = parseFloat(data.month)+m;

        self.buildCalendar(calendar, data.year, newMonth);
    }
    //      Вывод календаря
    IndexProgram.prototype.showCalendar = function (e) {
        let calendar = e.nextElementSibling;
        $(calendar).fadeIn();
    }
    //      Закрытие календаря
    IndexProgram.prototype.closureCalendar = function (e) {
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
            else if($('.addr__header-date span').is(e)) { // это поле даты
                let calendar = $(e).siblings('.calendar')[0];
                if(!$(arCalendars[i]).is(calendar))
                    $(arCalendars[i]).fadeOut();
            }
            else // это что-то другое
                $(arCalendars[i]).fadeOut();
        }
    }
    //
    IndexProgram.prototype.ajaxDelIndex = function (e) {
    	let self = this, 
            main = $(e).closest('.address__item')[0],
            i = $(e).hasClass('delcity') ? 'c' : 'l',
            query, arItems, params;

        if(i==='c') {
           query = 'Будет удален город и все связанные данные.\nВы действительно хотите это сделать?';
           arItems = $('.address__item');
           params = 'type=index&project=' + self.ID + '&city=' + e.dataset.id;
        }
        else {
            query = 'Будет удалена ТТ и все связанные данные.\nВы действительно хотите это сделать?';
            arItems = $(main).find('.loc-item');
            main = $(e).closest('.loc-item')[0];
            params = 'type=index&project=' + self.ID + '&city=' + e.dataset.idcity + '&location=' + e.dataset.id;
        }

    	if(arItems.length==1) {
            i==='c'
            ? MainProject.showPopup('error','onecity')
            : MainProject.showPopup('error','onelocation');
        }
    	else {
    		if(confirm(query)) {
	        $.ajax({
            type: 'DELETE',
            url: '/ajax/Project',
            data: {data: JSON.stringify(params)},
            dataType: 'json',
            success: function(r) {
              if(r.error==true) {
                MainProject.showPopup('error','server');
              }
              else {
                $(main).fadeOut();
                setTimeout(function(){ $(main).remove() },500);
                i==='c'
                ? MainProject.showPopup('success','delcity')
                : MainProject.showPopup('success','delloc');
              }
            },
	        });
    		}
    	}
    }
    //
    IndexProgram.prototype.addXlsFile = function () {
      let self = this;

      let html = "<div class='xls-popup' data-header='Изменение программы'>"+
        "1) Необходимо открыть скачаный файл<br>"+
        "2) Исправить существующие данные, либо добавить новые<br>"+
        "3) Загрузить измененный файл<br>"+
        '<span class="xls-popup-err">Формат файла должен быть "xls" или "xlsx". Выберите подходящий файл!</span>'+
        "<div class='xls-popup-btn'>ЗАГРУЗИТЬ</div>"+
        "</div>";

      ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
    }
    //      Проверка формата файла .XLS .XLSX
    IndexProgram.prototype.checkFormatFile = function () {
      let self = this,
        $inp = $('#add-xls-inp'),
        $name = $('#add-xls-name'),
        arExt = $inp.val().match(/\\([^\\]+)\.([^\.]+)$/);

      if(arExt[2]!=='xls' && arExt[2]!=='xlsx'){
        $inp.val('');
        $('.xls-popup-err').show();
      }
      else{
        $('.xls-popup-err').hide();
        $('#xls-form').submit();
      }
    }
    //
    return IndexProgram;
}());
/*
*
*/
$(document).ready(function () {
	new IndexProgram();
});
