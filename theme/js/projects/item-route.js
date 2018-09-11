/**
 * Created by Stanislav on 07.09.2018.
 */

/**
 * Created by Stanislav on 07.09.2018.
 */

let IndexRoute = (function () {



    function IndexRoute() {
        this.init();
    }

    IndexRoute.prototype.init = function () {
        let self = this;


        $('.route__table-item').click(function(){
            $('.route__table-item').each(function () {
                $(this).removeClass('route__table-active');
            });
            $(this).addClass('route__table-active');
        });

        $('.route__button-change').click(function(){
            $('.project__route-changer').show();
            $('.rout__main').hide();
        });

        $('.route__button-save').click(function(){
            $('.project__route-changer').hide();
            $('.rout__main').show();
        });


    };

    IndexRoute.prototype.alertPopup = function (text) {
        console.log(text);
    };



    return IndexRoute;
}());

$(document).ready(function () {
    new IndexRoute();

    $("#sortable").sortable();
    $("#sortable").disableSelection();
});