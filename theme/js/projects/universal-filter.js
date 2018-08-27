/**
 * Created by Stanislav on 24.08.2018.
 */
'use strict'
let IndexUniversalFilter = (function () {

    function IndexUniversalFilter() {
        this.init();
    }
    IndexUniversalFilter.prototype.init = function () {
        let self = this;
        $('.prommu__universal-filter').on('click', '.u-filter__select', function()
        {   let width = $(this).parent().width();
            self.showHiddenUlContent(this, width);
        });

        $('.prommu__universal-filter').on('click', '.u-filter__li-hidden', function()
        {
            self.setValueFromLI(this);
        });
        $('.prommu__universal-filter .u-filter__text').keypress(function ()
        {
            clearTimeout(IndexUniversalFilter.AjaxTimer);
            IndexUniversalFilter.AjaxTimer = setTimeout(function () {
                self.ajaxSetParams();
            }, 1000); // время в мс
        });


        self.setDefaultFilterProperties();

    };

    /**Функция отображения/скрытия пунктов меню**/
    IndexUniversalFilter.prototype.showHiddenUlContent = function (e, width) {
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
                let content = element.find('.u-filter__hidden-default').val();
                if(content){
                    element.find('.u-filter__text').val(content);
                }
            }
            if(element.data('type')==="select"){
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
                params+='&';
                params+=name+'='+value;
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


  return IndexUniversalFilter;
}());

$(document).ready(function () {
    console.log('Script here!');
    new IndexUniversalFilter();
});


