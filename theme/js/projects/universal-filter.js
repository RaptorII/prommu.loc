/**
 * Created by Stanislav on 24.08.2018.
 */
'use strict'
let IndexUniversalFilter = (function () {

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
        $('.prommu__universal-filter .u-filter__text').keypress(function ()
        {
            //Проверяем иерархию фильтров

            //Аякс с задержкой
            clearTimeout(IndexUniversalFilter.AjaxTimer);

            IndexUniversalFilter.AjaxTimer = setTimeout(function () {
                self.hierarchyTracking();
                self.ajaxSetParams();
            }, 700); // время в мс
        });


        self.setDefaultFilterProperties();

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

        if(getRequest) {
            $.ajax({
                type: 'GET',
                url: '/ajax/123', //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                data: getRequest,
                dataType: 'json',
                success: function (value) {
                }
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

            var d = ""+ data_buff;
            if(d.indexOf(',')===1){
                var mas = d.split(',');
                $.each( mas, function(i, l){
                    l = parseInt(l);
                    parent__value_id.push(l);
                });

            }else{
                parent__value_id.push(data_buff);
            }
            //let parent__value_id = $(this).data('parent-value-id').split(',');

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


    return IndexUniversalFilter;
}());

$(document).ready(function () {
    console.log('Script here!');
    new IndexUniversalFilter();
});


