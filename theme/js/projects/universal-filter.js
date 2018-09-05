/**
 * Created by Stanislav on 24.08.2018.
 */
'use strict'
let IndexUniversalFilter = (function () {

    IndexUniversalFilter.prototype.bDate = $('[name="bdate"]').val();
    IndexUniversalFilter.prototype.eDate = $('[name="edate"]').val();

    function IndexUniversalFilter() {
        this.init();
        this.hierarchyTracking();
    }
    IndexUniversalFilter.prototype.init = function () {
        let self = this;


        $('.prommu__universal-filter').on('click', '.u-filter__select', function()
        {   let width = $(this).parent().width();

            let blocked = $(this).parent().parent().hasClass('blocked');
            if(!blocked){
                self.showHiddenUlContent(this, width);
            }
        });

        $('.prommu__universal-filter').on('click', '.u-filter__li-hidden', function()
        {
            self.setValueFromLI(this);
        });
        $('.prommu__universal-filter .u-filter__text').on('input',function ()
        {
            //Аякс с задержкой
            clearTimeout(IndexUniversalFilter.AjaxTimer);

            IndexUniversalFilter.AjaxTimer = setTimeout(function () {
                //Проверяем иерархию фильтров
                self.hierarchyTracking();

                self.ajaxSetParams();
            }, 700); // время в мс
        });


        // работа с датами
        if(self.bDate!=undefined && self.eDate!=undefined){

            self.bDate = self.getDateFromData(self.bDate);
            self.eDate = self.getDateFromData(self.eDate);
            $.each($('.calendar'), function (e, item) {
                let v = item.nextElementSibling.value.split('.');
                self.buildCalendar(item, Number(v[2]), Number(v[1] - 1));
            });
            $('.prommu__universal-filter').on('click', '.calendar-filter span', function () {
                self.showCalendar(this)
            });
            $('.prommu__universal-filter').on('click', '.mleft', function () {
                self.changeMonth(this, -1)
            });
            $('.prommu__universal-filter').on('click', '.mright', function () {
                self.changeMonth(this, 1)
            });
            $('.prommu__universal-filter').on('click', '.calendar .day', function (e) {
                self.checkDate(e.target)
            });
            // обрабатываем клики
            $(document).on('click', function (e) {
                self.closureCalendar(e.target);
            });
        }

        self.setDefaultFilterProperties();
        //      аякс постраничная навигация
        $('#staff-content').on('click','.paging-wrapp a', function(e){
            e.preventDefault();
            self.ajaxSetParams(e.target);
        });
    };

    /**Функция отображения/скрытия пунктов меню**/
    IndexUniversalFilter.prototype.showHiddenUlContent = function (e, width) {
        let element = e;
        $(element).parent().parent().parent().find('.u-filter__ul-hidden').fadeOut();

        let hiddenUl = e.nextElementSibling;
        $(hiddenUl).width(width);
        if($(hiddenUl).is(":visible")){
            $(hiddenUl).fadeOut();
        }else{
            $(hiddenUl).fadeIn();
        }
    };

    /**Функция установки Default значений для полей**/
    IndexUniversalFilter.prototype.setDefaultFilterProperties = function () {
        $('.prommu__universal-filter .u-filter__item').each(function () {
            let element = $(this);
            if(element.data('type')==="text"){

                /**Проверка блокировки**/
                let blocked = element.hasClass('blocked');

                if(blocked){
                    element.find('.u-filter__text').prop('readonly', true);
                }
                else{
                    /**если нет блокировки - устанавливаем default**/
                    let content = element.find('.u-filter__hidden-default').val();
                    if(content){
                        element.find('.u-filter__text').val(content);
                    }
                }
            }
            if(element.data('type')==="select"){
                /**Проверка блокировки**/
                let blocked = element.hasClass('blocked');
                if(!blocked) {
                    let content = element.find('.u-filter__hidden-default').val();
                    if(content){
                        let elements = element.find('.u-filter__ul-hidden .u-filter__li-hidden');
                        elements.each(function () {
                            if($(this).data('id')=== parseInt(content)){
                                let e_value = $(this).text();
                                let e_id = $(this).data('id');

                                element.find('.u-filter__select').text(e_value);
                                element.find('.u-filter__hidden-data').val(e_id);
                            }
                        });
                    }
                }
            }
            if(element.data('type')==="calendar"){
                /**Проверка блокировки**/
                /*let blocked = element.hasClass('blocked');
                if(!blocked) {
                    let content = element.find('.u-filter__hidden-default').val();
                    if(content)
                    {
                        //element.find('.u-filter__calendar').text(content);
                        //element.find('.u-filter__hidden-data').val(content);
                    }
                }*/
            }
        })
    };


    IndexUniversalFilter.prototype.setValueFromLI = function (e) {
        let li = e;
        let id = $(li).data('id');
        let value = $(li).text();
        /**Устанавливаем скрытый input***/
        $(li).parent().parent().find('.u-filter__hidden-data').val(id);
        /******Устанавливаем value в span*******/
        $(li).parent().parent().find('.u-filter__select').text(value);
        /******Скрываем список ul*******/
        $(li).parent().fadeOut();

        //Проверяем иерархию фильтров
        this.hierarchyTracking();
        //Запускаем Ajax
        this.ajaxSetParams();
    };

    IndexUniversalFilter.prototype.ajaxSetParams = function () {
        let params = '';
        let mainParam='';

        $('.prommu__universal-filter').find('input').each(function () {

            $('.filter__veil').show();


            //Формирование запроса Get
            let name = $(this).attr("name");
            let value = $(this).val();
            if(name && name!='id'){
                if(value!==''){
                    params+='&';
                    params+=name+'='+value;
                }
            }
            if(name=='id'){
                mainParam+=name+'='+value;
            }
        });

        let getRequest = mainParam +''+ params;

        if(arguments.length) {
            let str = $(arguments[0]).attr('href');
            
            getRequest += str.slice(str.indexOf("&page="));
        }

        if(getRequest) {
            $.ajax({
                type: 'GET',
                url: window.location.pathname,
                data: getRequest,
                success: function (r) {
                    $('#ajax-content').html(r);
                    $('.filter__veil').hide();
                },
            });
        }
    };

    /**Функция отслеживания и контроля иерархии фильтров**/
    IndexUniversalFilter.prototype.hierarchyTracking = function () {
        $('.prommu__universal-filter .u-filter__item').each(function () {



            let parent_id = parseInt($(this).data('parent-id'));

            let parent_value = $(this).data('parent-value');
            let parent__value_id = [];
            let data_buff = $(this).data('parent-value-id');

            let d = ""+ data_buff;
            if(d.indexOf(',')===1){
                let mas = d.split(',');
                $.each(mas, function(i, l){
                    l = parseInt(l);
                    parent__value_id.push(l);
                });

            }else{
                parent__value_id.push(data_buff);
            }
            let type = $(this).data('type');

            if(parent_id>=0){



                let parent = $(".u-filter__item[data-id='" + parent_id +"']");

                let parent_type = parent.data('type');

                if(parent_type==="text"){
                    let value = parent.find('.u-filter__text').val();

                    if(parent_value == value){
                        $(this).removeClass('blocked');
                        if(type==="text"){
                            $(this).find('.u-filter__text').prop('readonly', false);
                        }
                    }
                    else{
                        if(!$(this).hasClass('blocked')){
                            $(this).addClass('blocked');
                            if(type==="text"){
                                $(this).find('.u-filter__text').prop('readonly', true);
                                $(this).find('.u-filter__text').val('');
                            }
                            if(type==="select"){
                                $(this).find('.u-filter__hidden-data').val('');
                                $(this).find('.u-filter__select').text('');
                            }
                        }
                    }
                }
                if(parent_type==="select"){
                    let value = parseInt(parent.find('.u-filter__hidden-data').val());
                    if(jQuery.inArray(value,parent__value_id)>=0){
                        $(this).removeClass('blocked');
                        if(type==="text"){
                            $(this).find('.u-filter__text').prop('readonly', false);
                        }
                    }
                    else{
                        if(!$(this).hasClass('blocked')){
                            $(this).addClass('blocked');
                            if(type==="text"){
                                $(this).find('.u-filter__text').prop('readonly', true);
                                $(this).find('.u-filter__text').val('');
                            }
                            if(type==="select"){
                                $(this).find('.u-filter__hidden-data').val('');
                                $(this).find('.u-filter__select').text('');
                            }
                        }
                    }
                }
            }

        });
    };


    /*************КАЛЕНДАРЬ!****************/

    /**Создание календарей**/
    IndexUniversalFilter.prototype.buildCalendar = function (item, year, month) {
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
            main = $(item).closest('.prommu__universal-filter')[0],
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
    };

    //      Проверка даты
    IndexUniversalFilter.prototype.checkDate = function (day) {
        let self = this,
            $it = $(day),
            main = $it.closest('.prommu__universal-filter')[0],
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
        self.ajaxSetParams();
    };
    //      Определение разницы во времени
    IndexUniversalFilter.prototype.diffDate = function (date1, date2) {
        let miliToDay = 1000 * 60 * 60 * 24;// переводим милисекунды в дни
        return Math.ceil((date1 - date2) / miliToDay);
    };
    //      форматируем в формат даты
    IndexUniversalFilter.prototype.getDateFromData = function (date) {
        let arDate = date.split('.'),
            obj = new Date(
                Number(arDate[2]),
                Number(arDate[1]-1),
                Number(arDate[0])
            );
        return obj.setHours(0,0,0,0);
    };
    //      Изменение месяца
    IndexUniversalFilter.prototype.changeMonth = function (e, m) {
        let self = this,
            calendar = $(e).closest('.calendar')[0],
            data = $(e).siblings('.mname')[0].dataset,
            newMonth = parseFloat(data.month)+m;

        self.buildCalendar(calendar, data.year, newMonth);
    };
    //      Вывод календаря
    IndexUniversalFilter.prototype.showCalendar = function (e) {
        let calendar = e.nextElementSibling;
        $(calendar).fadeIn();
    };
    //      Закрытие календаря
    IndexUniversalFilter.prototype.closureCalendar = function (e) {
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
            else if($('.prommu__universal-filter span').is(e)) { // это поле даты
                let calendar = $(e).siblings('.calendar')[0];
                if(!$(arCalendars[i]).is(calendar))
                    $(arCalendars[i]).fadeOut();
            }
            else // это что-то другое
                $(arCalendars[i]).fadeOut();
        }
    };


    return IndexUniversalFilter;
}());

$(document).ready(function () {
    new IndexUniversalFilter();
});


