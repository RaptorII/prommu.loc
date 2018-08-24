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
                console.log(content);
                if(content){
                    let elements = element.find('.u-filter__ul-hidden .u-filter__li-hidden');
                    elements.each(function () {
                        if($(this).data('id')=== parseInt(content)){
                            let e_value = $(this).text();
                            let e_id = $(this).data('id');

                            console.log(e_value);
                            console.log(e_id);
                            element.find('.u-filter__select').text(e_value);
                            element.find('.u-filter__hidden-data').val(e_id);
                        }
                    });
                }
            }
        })
    };


  return IndexUniversalFilter;
}());

$(document).ready(function () {
    console.log('Script here!');
    new IndexUniversalFilter();
});


