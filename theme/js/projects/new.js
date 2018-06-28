'use strict'
var ProjectPage = (function () {
    ProjectPage.prototype.bAjaxTimer = false;
    ProjectPage.prototype.arIdCities = [];
    ProjectPage.prototype.idCo = $('#index').data('country');

	function ProjectPage() {
        let self = this;
        self.init();
    }

    ProjectPage.prototype.init = function () {
    	let self = this,
            arCalendars = document.querySelectorAll('.calendar');
        //
        //      Главная страница
        //
        $('#add-xls').click(self.addXlsFile);
        $('#save-project').click(self.checkErrors);
        $('.project__opt-btn').click(function() { self.showModule(this) });
        $('#add-xls-inp').change(function() { self.checkFormatFile(this) });
        $('#project-name').on('input', self.checkProjectName); // работа с названием проекта
        // 
        //      Страница создания адресной программы
        //
        // события кнопок программы
        $('#save-index').click(function(){
            let params = $('#new-project').serializeArray();

            console.log(params);
        });

        $('#index').on('click', '.add-loc-btn', function(){ self.addLocation(this) });
        $('#index').on('click', '.add-period-btn', function() { self.addPeriod(this) });
        // работа с городами
        $('#index').on('input', '.city-inp', function() { self.inputCity(this) });
        $('#index').on('focus', '.city-inp', function() { self.focusCity(this) });
        $('#add-city-btn').click(function(){ self.addCity() });
        // работаем с метро
        $('#index').on('input','.metro-inp',function(){ self.inputMetros(this) });
        $('#index').on('focus','.metro-inp',function(){ self.focusMetro(this) });
        $('#index').on('click','.metro-select',function(e){ 
            if(!$(e.target).is('b'))
                $(e.target).find('.metro-inp').focus();
        });
        // работа с датами
        for (let i=0; i<arCalendars.length; i++)
            self.buildCalendar(arCalendars[i]);
        $('#index').on('click', '.project__index-period span', function() { self.showCalendar(this) });
        $('#index').on('click', '.mleft', function(){ self.changeMonth(this,-1) });
        $('#index').on('click', '.mright', function(){ self.changeMonth(this,1) });
        $('#index').on('click', '.calendar .day', function(e){ self.checkDate(e.target) });
        // работа с временем
        $('.time-inp').mask('99:99');
        $('#index').on('blur', '.time-inp', function() { self.checkTime(this) });
        // обрабатываем клики
        $(document).on('click', function(e) {
            self.checkCity(e.target);
            self.checkMetro(e.target);
            self.closureCalendar(e.target);
        });
    };
    //
    //
    //      Проверка готовности для создания проекта
    ProjectPage.prototype.checkErrors = function () {
        let nameInp = $('#project-name'),
            name = $(nameInp).val();

        if(name.length<1){
            $(nameInp).addClass('error');
        }
    }
    //      Выбор файла для адресной программы
    ProjectPage.prototype.addXlsFile = function () {
        $('#add-xls-inp').click();
    }
    //      Проверка формата файла .XLS .XLSX
    ProjectPage.prototype.checkFormatFile = function () {
        let self = this,
            $inp = $('#add-xls-inp'),
            $name = $('#add-xls-name'),
            arExt = $inp.val().match(/\\([^\\]+)\.([^\.]+)$/);

        if(arExt[2]!=='xls' && arExt[2]!=='xlsx'){
            self.showPopup('error','xls');
            self.addXlsFile;
            $inp.val('');
            $name.text('').hide();
        }
        else{
            $name.text(arExt[1] + '.' + arExt[2]).show();
        }
    }
    //      показать нужный модуль
    ProjectPage.prototype.showModule = function (e) {
        let self = this,
            data = e.dataset.event,
            arBlocks = $('.project__module'),
            name = self.checkProjectName();

        if(data==undefined)
            return false;

        if(!name){
            self.showPopup('error','name');
            return false;
        }

        for (let i = 0, n = arBlocks.length; i<n; i++) {
            $(arBlocks[i]).attr('id')===data
            ? $(arBlocks[i]).fadeIn()
            : $(arBlocks[i]).hide();
        }
    }
    //      Проверка ввода названия проекта
    ProjectPage.prototype.checkProjectName = function () {
        let $it = $('#project-name'),
            val = $it.val();

        if(!val.length){
            $it.addClass('error');
            return false;
        }
        else{
            $it.removeClass('error');
            return true;
        }
    }
    //
    //      ГОРОДА
    //
    //		добавление города
    ProjectPage.prototype.addCity = function () {
    	let self = this,
    		arCities = $('#index .project__body'),
            newCity = $('#city-content').html(),
            empty = self.checkFields(arCities);

        if (!empty) {
            $(arCities[arCities.length-1]).after(newCity);
            let arTime = $('#index .project__body:eq(-1)').find('.time-inp');
            $(arTime).mask('99:99');
        }
        else
            self.showPopup('error', 'add-city');
    }
    //      ввод города
    ProjectPage.prototype.inputCity = function (e) {
        let self = this,
            val = $(e).val();

        clearTimeout(self.bAjaxTimer);
        self.setFirstUpper(e);

        self.bAjaxTimer = setTimeout(function(){ self.getAjaxCities(val, e) },1000);
    }
    //      фокус поля города
    ProjectPage.prototype.focusCity = function (e) {
        let self = this,
            val = $(e).val();
        $(e).val('').val(val);
        self.setFirstUpper(e);
        self.getAjaxCities(val, e);
    };
    //      запрос списка городов
    ProjectPage.prototype.getAjaxCities = function (val, e) {
        let self = this,
            $e = $(e),
            list = $e.siblings('.select-list')[0],
            main = $e.closest('.city-field')[0],
            mainCity = $e.closest('.project__body')[0],
            idcity = Number(mainCity.dataset.city), 
            piece = val.toLowerCase(),
            content = '',
            params = 'query=' + val + '&idco=' + self.idCo;
        
        self.getSelectedCities();
        $(main).addClass('load'); // загрузка началась
        
        $.ajax({
            type: 'POST',
            url: MainConfig.AJAX_GET_VE_GET_CITIES,
            data: params,
            dataType: 'json',
            success: function(r) {
                for (let i in r.suggestions) {
                    let item = r.suggestions[i],
                        id = +item.data;

                    if(isNaN(item.data))
                        break;

                    if(
                        ( $.inArray(id, self.arIdCities)<0 || id==idcity )
                        && 
                        item.value.toLowerCase().indexOf(piece) >= 0
                    ){ // собираем список
                        content += '<li data-id="' 
                            + item.data + '" data-metro="' + item.ismetro 
                            + '">' + item.value + '</li>';
                    }
                }
                content 
                ? $(list).html(content).fadeIn() 
                : $(list).html('<li class="emp">Список пуст</li>').fadeIn();
                $(main).removeClass('load'); // загрузка завершена
            }
        });
    }
    //      фокус инпута и выбор города
    ProjectPage.prototype.checkCity = function (e) {
        let self = this,
            $e = $(e),
            arCities = $('#index .city-field');

       if( !$e.closest('.city-field').length && !$e.is('.city-field') ) {
            for(let i=0; i<arCities.length; i++){ // закрываем списки без фокуса
                let cSelect = $(arCities[i]).find('.city-select'),
                    cInput = $(arCities[i]).find('.city-inp'),
                    cList = $(arCities[i]).find('.select-list'),
                    v = $(cSelect).text();

                cSelect.text()==='' ? cSelect.hide() : cSelect.show();
                cInput.val(v).hide();
                cList.fadeOut();
            }
        }
        else{ // клик по объектам списка
            if( $e.is('li') && !$e.hasClass('emp') ) { // выбираем из списка
                let main = $e.closest('.project__body')[0],
                    select = $(main).find('.city-select'),
                    inpText = $(main).find('.city-inp'),
                    list = $(main).find('.select-list'),
                    input = $(inpText).siblings('[type="hidden"]'),
                    mContent = $('#metro-content').html(),
                    cContent = $('#item-col').html(),
                    arLoc = $(main).find('.loc-part'),
                    arMetro = $(main).find('.metro-item'),
                    arCols = main.getElementsByClassName('project__index-col');

                if(main.dataset.city!=='' && main.dataset.city===e.dataset.id) {
                    let v = select.text();
                    inpText.val(v).hide();
                    select.show();
                    list.fadeOut();
                }
                else { // ввод нового города
                    let v = $(e).text();
                    main.dataset.city = e.dataset.id;
                    input.val(e.dataset.id);
                    inpText.val(v).hide();
                    select.html(v+'<b></b>').show();
                    list.fadeOut();

                    

                    if(e.dataset.metro==='1') {

                        if (!$(main).find('.metro-item').length) {
                            for(var i=0; i<arLoc.length; i+=2) {
                                let html = !i ? (cContent+cContent+mContent) : mContent;
                                $(arLoc[i]).before(html); 
                            }
                            let cnt = 0;
                            for(var i=0; i<arCols.length; i++) {
                                if($(arCols[i]).hasClass('empty')) {
                                    if(cnt>=2) {
                                       $(arCols[i]).remove(); 
                                    }
                                    cnt++;
                                }
                            }
                        }
                    }
                    else if($(main).find('.metro-item').length) {
                        let eCnt = 0, lCnt = 0;
                        for(var i=0; i<arCols.length; i++) {
                            if ($(arCols[i]).hasClass('empty') && eCnt<2) {
                                $(arCols[i]).remove(); 
                                eCnt++;
                            }
                            if ($(arCols[i]).hasClass('loc-part')) {
                                lCnt++;
                            }
                            if (lCnt==2) {
                                $(arCols[i]).after(cContent);
                                lCnt = 0;
                            }
                        }
                        /*
                        arCols[1].remove();
                        arCols[2].remove();
                        $(arMetro).remove();
                        */
                    }

                    console.log(arCols);
                }
            }
            else{
                let main = $e.is('.city-field') ? e : $e.closest('.city-field')[0];
                for(let i=0; i<arCities.length; i++) { // закрываем списки без фокуса
                    let cSelect = $(arCities[i]).find('.city-select'),
                        cInput = $(arCities[i]).find('.city-inp'),
                        cList = $(arCities[i]).find('.select-list'),
                        v = $(cSelect).text();

                    if( !$(arCities[i]).is(main) ) {
                        cSelect.show();
                        cInput.val(v).hide();
                        cList.fadeOut();
                    }
                    else{
                        if( $e.is('b') )
                            cInput.val('');
                        cInput.show().focus();
                        cSelect.hide();
                    }
                }
            }
        }
    }
    //      правильный ввод названия города
    ProjectPage.prototype.setFirstUpper = function (e) {
        let split = $(e).val().split(' ');

        for(let i=0, len=split.length; i<len; i++)
            split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
        $(e).val(split.join(' '));

        split = $(e).val().split('-');
        for(let i=0, len=split.length; i<len; i++)
            split[i] = split[i].charAt(0).toUpperCase() + split[i].slice(1);
        $(e).val(split.join('-'));
    }
    //       получить выбранные города
    ProjectPage.prototype.getSelectedCities = function () {
        let self = this;

        self.arIdCities = [];

        $.each($('#index .project__body'), function(){
            if(this.dataset.city!=='')
                self.arIdCities.push(Number(this.dataset.city));
        });
    }
    //
    //      МЕТРО
    //
    //      Ввод метро
    ProjectPage.prototype.inputMetros = function (e) {
        let self = this,
            val = $(e).val();

        $(e).css({width:(val.length * 10 + 5)+'px'});
        clearTimeout(self.bAjaxTimer);
        self.bAjaxTimer = setTimeout(function(){ self.getAjaxMetros(val, e) },1000);
    }
    //      фокус поля метро
    ProjectPage.prototype.focusMetro = function (e) {
        let self = this,
            val = $(e).val();
        $(e).val('').val(val);
        self.getAjaxMetros(val, e);
    };
    //      запрос списка метро
    ProjectPage.prototype.getAjaxMetros = function (val, e) {
        let self = this,
            $e = $(e),
            main = $e.closest('.metro-field')[0],
            mainCity = $e.closest('.project__body')[0],
            list = $(main).find('.select-list')[0],
            input = $(main).find('[type="hidden"]')[0],
            idcity = Number(mainCity.dataset.city),
            params = 'id=' + idcity + '&query=' + val + '&select=' + $(input).val(),
            content = '';

        $(main).addClass('load'); // загрузка началась
        
        $.ajax({
            type: 'POST',
            url: '/ajaxvacedit/vegetmetros/',
            data: params,
            dataType: 'json',
            success: function(metros) {
                if(metros.error!==true)
                    for (let i in metros)
                        content += '<li data-id="' + metros[i].id + '">' 
                            + metros[i].name + '</li>';

                content 
                ? $(list).html(content).fadeIn() 
                : $(list).html('<li class="emp">Список пуст</li>').fadeIn();
                $(main).removeClass('load'); // загрузка завершена
            }
        });
    }
    //      Установка метро
    ProjectPage.prototype.checkMetro = function (e) {
        let self = this,
            $e = $(e),
            arMetros = $('#index .metro-field');

        if( !$e.closest('.metro-field').length && !$e.is('.metro-field') ) {
            for(let i=0; i<arMetros.length; i++){ // закрываем списки без фокуса
                let cInput = $(arMetros[i]).find('.metro-inp'),
                    cList = $(arMetros[i]).find('.select-list');

                cInput.val('').css({width:'5px'});
                cList.fadeOut();
            }
        }
        else{ // клик по объектам списка
            if( $e.is('.select-list li') && !$e.hasClass('emp') ) { // выбираем из списка
                let main = $e.closest('.metro-item')[0],
                    select = $(main).find('.metro-select'),
                    inpText = $(main).find('.metro-inp'),
                    list = $(main).find('.select-list'),
                    input = $(main).find('[type="hidden"]'),
                    name = $(e).text(),
                    v = $(input).val(),
                    arMetros = v.length ? v.split(',') : [];

                arMetros.push(e.dataset.id);
                $(input).val(arMetros);
                inpText.val('').css({width:'5px'});
                $(select).find('[data-id="0"]').before('<li data-id="' 
                    + e.dataset.id + '">' + name 
                    + '<b></b></li>');
                list.fadeOut();
            }
            else if($e.is('.metro-select b')) { // удаление выбранного метро из списка
                let main = $e.closest('.metro-item')[0],
                    input = $(main).find('[type="hidden"]'),
                    name = $(e).text(),
                    metro = $e.closest('li')[0],
                    arMetros = $(input).val().split(',');

                arMetros.pop(arMetros.indexOf(metro.dataset.id));
                $(input).val(arMetros.join(','));
                $(metro).remove();
            }
        }
    }
    //
    //      ЛОКАЦИИ
    //
    //      добавление локации
    ProjectPage.prototype.addLocation = function (e) {
        let self = this,
            btnBlock = $(e).closest('.project__all-btns')[0],
            body = btnBlock.previousElementSibling,
            arCols = body.getElementsByClassName('project__index-col'),
            arCities = $('#index .project__body'),
            newLoc = $('#loc-content').html(),
            newCol = $('#item-col').html(),
            empty = self.checkFields(arCities),
            htmlBtn = $(arCols).eq(-1).html();

        if (!empty) {
            if($(body).find('.metro-item').length) {// если есть метро
                newLoc = $('#metro-content').html() + $('#period-content').html() + newLoc;
            }
            else {
                newLoc = newLoc + newCol + $('#period-content').html();
            }
            $(arCols).eq(-1).after(newLoc + newCol);
            $(arCols).eq(-1).removeClass('empty').html(htmlBtn);
            let arTime = $(body).find('.time-inp');
            $(arTime).mask('99:99');
        }
        else
            self.showPopup('error', 'add-period');
    }
    //
    //      ДАТА
    //
    //      Создание календарей
    ProjectPage.prototype.buildCalendar = function (item, year, month) {
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
            main = $(item).closest('.project__index-period')[0],
            parent = $(item).closest('.period-item')[0],
            arCalendars = $(main).find('.period-item'),
            begDate = $(arCalendars[0]).find('input').val(),
            endDate = $(arCalendars[1]).find('input').val(),
            type,body,mName,nDays,newDate,res;

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
            res = self.diffDate(newDate,date);

            if(res==0)
                content += ' today'; // today
            else if(res<0)
                content += ' nofit'; // прошедшее
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
    ProjectPage.prototype.checkDate = function (day) {
        let self = this,
            $it = $(day),
            main = $it.closest('.project__index-period')[0],
            arCalendars = $(main).find('.period-item'),
            begDate = $(arCalendars[0]).find('input').val(),
            endDate = $(arCalendars[1]).find('input').val(),
            parent = $it.closest('.period-item')[0],
            data = $(parent).find('.mname')[0].dataset,
            calendar = $(parent).find('.calendar')[0],
            output = $(parent).find('span')[0],
            input = $(parent).find('input')[0],
            d = Number($(day).text()),
            m = Number(data.month),
            y = Number(data.year),
            newDate = new Date(y, m, d),
            res;

        if( $(day).hasClass('empty')  || $(day).hasClass('nofit') )
            return false;

        $(calendar).fadeOut();
        res = ('0' + Number($(day).text())).slice(-2) + '.' 
            + ('0' + (Number(data.month) + 1)).slice(-2) 
            + '.' + data.year;

        $(output).text(res);
        $(input).val(res);
        self.buildCalendar(arCalendars[0], y, m);
        self.buildCalendar(arCalendars[1], y, m);
    }
    //      Определение разницы во времени
    ProjectPage.prototype.diffDate = function (date1, date2) {
        let miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
        return Math.ceil((date1 - date2) / miliToDay);
    }
    //      форматируем в формат даты
    ProjectPage.prototype.getDateFromData = function (date) {
        let arDate = date.split('.'), 
            obj = new Date(
                Number(arDate[2]),
                Number(arDate[1]-1),
                Number(arDate[0])
            );
        return obj.setHours(0,0,0,0);
    }
    //      Изменение месяца
    ProjectPage.prototype.changeMonth = function (e, m) {
        let self = this,
            calendar = $(e).closest('.calendar')[0],
            data = $(e).siblings('.mname')[0].dataset,
            newMonth = parseFloat(data.month)+m;

        self.buildCalendar(calendar, data.year, newMonth);
    }
    //      Вывод календаря
    ProjectPage.prototype.showCalendar = function (e) {
        let calendar = e.nextElementSibling;
        $(calendar).fadeIn();
    }
    //      Закрытие календаря
    ProjectPage.prototype.closureCalendar = function (e) {
        let arCalendars = $('#index .calendar');

        for(let i=0, n=arCalendars.length; i<n; i++) {
            if($('.calendar').is(e) && !$(arCalendars[i]).is(e)) { // это точно календарь
                $(arCalendars[i]).fadeOut();
            }
            else if($(e).closest('.calendar').length) { // это составные календаря
                let calendar = $(e).closest('.calendar')[0];
                if(!$(arCalendars[i]).is(calendar))
                    $(arCalendars[i]).fadeOut();
            }
            else if($('.period-item span').is(e)) { // это поле даты
                let calendar = $(e).siblings('.calendar')[0];
                if(!$(arCalendars[i]).is(calendar))
                    $(arCalendars[i]).fadeOut();
            }
            else // это что-то другое
                $(arCalendars[i]).fadeOut();
        }
    }
    //
    //      Время
    //
    //      Добавление периода
    ProjectPage.prototype.addPeriod = function (e) {
        let self = this,
            empty = false,
            main = $(e).closest('.project__body')[0],
            btnCol = $(e).closest('.project__index-col')[0],
            arInputs = $(main).find('.project__index-period input'),
            newPeriod = $('#period-content').html();

        for (let i = 0, l = arInputs.length; i < l; i++)
            if( !arInputs[i].value.length )
                    empty = true;

        if(!empty) {
            $(btnCol).before(newPeriod);
            $(main).find('.time-inp').mask('99:99');
        }
        else 
            self.showPopup('error', 'add-period');
    }
    //      Проверка времени
    ProjectPage.prototype.checkTime = function (e) {
		let self = this,
			$e = $(e),
			main = $e.closest('.project__index-period'),
			arTimes = $(main).find('input'),
			arT1 = $(arTimes[0]).val().split(':'),
			arT2 = $(arTimes[1]).val().split(':'),
			t = $(arTimes[0]).is(e) ? 0 : 1;

		if(arT1.length==2) {
			arT1[0] = Number(arT1[0])>23 ? '23' : arT1[0];
			arT1[1] = Number(arT1[1])>59 ? '59' : arT1[1];
			self.setTime(arTimes[0], arT1);
		}
		if(arT2.length==2) {
			arT2[0] = Number(arT2[0])>23 ? '23' : arT2[0];
			arT2[1] = Number(arT2[1])>59 ? '59' : arT2[1];
			self.setTime(arTimes[1], arT2);
		}

		if(arT1.length!=2 || arT2.length!=2)
			return false;

		arT1[0] = Number(arT1[0]);
		arT1[1] = Number(arT1[1]);
		arT2[0] = Number(arT2[0]);
		arT2[1] = Number(arT2[1]);

		if(isNaN(arT1[0]) || isNaN(arT1[1]) || isNaN(arT2[0]) || isNaN(arT2[1]))
			return false;

		if( (arT1[0] > arT2[0]) || (arT1[1] > arT2[1]) ) {
			self.showPopup('error', 'time');
			$(arTimes[t]).val('');
		}
        if( (arT1[0] == arT2[0]) && (arT1[1] == arT2[1]) ) {
            self.showPopup('error', 'time');
            $(arTimes[t]).val('');
        }
    }
    //		Установка времени
    ProjectPage.prototype.setTime = function (e, arT) {
		arT[0] = ('0' + arT[0]).slice(-2);
		arT[1] = ('0' + arT[1]).slice(-2);
		$(e).val(arT[0] + ':' + arT[1]);
    }
    //      Проверка заполненности полей
    ProjectPage.prototype.checkFields = function (arr) {
        let empty = false;

        for (let i = 0, l = arr.length; i < l; i++) {
            let arInputs = $(arr[i]).find('input');
    
            for (let j = 0, n = arInputs.length; j < n; j++) {
                let name = $(arInputs[j]).attr('name');
                if ($.inArray(name, ['c','m'])<0 && !arInputs[j].value.length) {
                    //console.log(arInputs[j]);
                    empty = true;
                }
            }
        }
        empty = false;
        return empty;
    }
    //
    //      ADDITIONAL
    //
    //      Вывод ошибок
    ProjectPage.prototype.showPopup = function (event, type) {
        let header = '',
            body = '';

        if(event=='error'){
            switch(type) {
                case 'xls': 
                    header = 'Ошибка';
                    body = 'Формат файла должен быть "xls" или "xlsx". Выберите подходящий файл!';  
                    break;
                case 'name':
                    header = 'Предупреждение';
                    body = 'Для продолжения необходимо ввести название проекта';  
                    break;
				case 'time':
                    header = 'Ошибка';
                    body = 'Неправильно задано время работы';  
                    break;
                case 'add-city':
                    header = 'Предупреждение';
                    body = 'Перед добавлением нового города необходимо '
                        + 'заполнить все поля у существующих городов';  
                    break;
                case 'add-period':
                    header = 'Предупреждение';
                    body = 'Перед добавлением нового периода необходимо '
                        + 'заполнить все поля у существующих периодов';  
                    break; 

                default: break;  
            }
        }

        let html = "<form data-header='" + header + "'>" + body + "</form>";
        ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
    }
    return ProjectPage;
}());
$(document).ready(function () {
	let Project = new ProjectPage();
});

