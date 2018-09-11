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
            $.fancybox.open({
                src: "div.project__route-changer",
                type: 'inline',
            });
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