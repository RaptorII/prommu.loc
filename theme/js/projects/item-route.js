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

        $(".route__button-save").click(function(){
            var data = self.getSortData();
            self.ajaxPushParams(data, 'POST');
        });

        $('.route__button-cancel').click(function(){
            $('.project__route-changer').hide();
            $('.rout__main').show();
        });

        $('.route__button-map').click(function () {
            self.mapOpen(this);
        });


    };

    IndexRoute.prototype.getSortData = function () {
        var data_object = {};

        $('#sortable .route__table-item').each(function(i, e){
            data_object[i] = $(this).data('location');
        });

        return data_object;
    };

    IndexRoute.prototype.mapOpen = function (e) {
        let map = $(e).closest('.route__item-box').find('.routes__map');
        map.css({'height':'auto'});
    };

    IndexRoute.prototype.ajaxPushParams = function (data, type) {
        if(!data) return;

        $.ajax({
            type: type,
            url: '/ajax/123123',
            data: { data: JSON.stringify(data) },
            dataType: 'json',
            success: function (value){
                location.reload();
            }
        });
    };
    return IndexRoute;
}());

$(document).ready(function () {
    new IndexRoute();

    $("#sortable").sortable();
    $("#sortable").disableSelection();
});