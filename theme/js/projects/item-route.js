/**
 * Created by Stanislav on 07.09.2018.
 */
window.scribblemaps = {
    settings: {baseAPI: "google", key: 'AIzaSyAOevBkK_oALP0mD9aG3g4RyhUePQHl6SU'}
};
window.smElement = false;
/*
 *
 */
let IndexRoute = (function () {

    IndexRoute.prototype.cnt = false;
    IndexRoute.prototype.urlMap = '//scribblemaps.com/api/js/?callback=smLoadMap';

    function IndexRoute() {
        this.init();
    }

    IndexRoute.prototype.init = function () {
        let self = this;


        $('.route__table-item').click(function () {
            $('.route__table-item').each(function () {
                $(this).removeClass('route__table-active');
            });
            $(this).addClass('route__table-active');
        });

        $('.route__button-change,.route__change-id').click(function () {
            $('.project__route-changer').show();
            $('.rout__main').hide();
        });

        $(".route__button-save").click(function () {
            var data = self.getSortData();
            self.ajaxPushParams(data, 'POST');
        });

        $('.route__button-cancel').click(function () {
            $('.project__route-changer').hide();
            $('.rout__main').show();
        });

        /*$('.route__button-map').click(function () {
            self.mapOpen(this);
        });*/

        //меняем местами
        $(".project__route-touch.touch__arrow-top").click(function () {
            var pdiv = $(this).closest('.project__route-changer').find('.route__table-active');
            pdiv.insertBefore(pdiv.prev());
            return false
        });
        //меняем местами
        $(".project__route-touch.touch__arrow-bottom").click(function () {
            var pdiv = $(this).closest('.project__route-changer').find('.route__table-active');
            pdiv.insertAfter(pdiv.next());
            return false
        });

        // загружаем новый xls
        $('.add-xls-new, .add-xls-change').click(function(){ self.addXlsFile(this) });
        $('body').on('click','.xls-popup-btn',function(){
            $('#add-xls-inp').click();
        });
        $('#add-xls-inp').change(function() { self.checkFormatFile(this) });
    };

    IndexRoute.prototype.getSortData = function () {
        var data_object = {};

        $('#sortable .route__table-item').each(function (i, e) {
            data_object[i] = $(this).data('location');
        });

        return data_object;
    };

    IndexRoute.prototype.mapOpen = function (e) {
        let self = this;

        smElement = $(e).closest('.route__item-box').find('.routes__map')[0];

        if (!$(smElement).hasClass('map_active')) {
            $(e).html('СКРЫТЬ МАРШРУТ');

            if (!self.cnt) {
                let e = document.createElement("script");
                //e.src = self.urlMap;
                //e.type = "text/javascript";
                document.getElementsByTagName("head")[0].appendChild(e);
                self.cnt = true;
            }
            else if (!$(smElement).html().length) {
                //smLoadMap();
            }
        }
        else {

            $(e).html('СМОТРЕТЬ МАРШРУТ');
        }
        $(smElement).toggleClass('map_active');
    };

    IndexRoute.prototype.ajaxPushParams = function (data, type) {
        if (!data) return;

        $.ajax({
            type: type,
            url: '/ajax/123123',
            data: {data: JSON.stringify(data)},
            dataType: 'json',
            success: function (value) {
                location.reload();
            }
        });

        $('.project__route-changer').hide();
        $('.rout__main').show();
    };
    //
    IndexRoute.prototype.addXlsFile = function () {
        let self = this;

        let html = "<div class='xls-popup' data-header='Изменение программы'>"+
        "Загрузить измененный файл<br>"+
        '<span class="xls-popup-err">Формат файла должен быть "xls" или "xlsx". Выберите подходящий файл!</span>'+
        "<div class='xls-popup-btn'>ЗАГРУЗИТЬ</div>"+
        "</div>";

        ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
    }
    //      Проверка формата файла .XLS .XLSX
    IndexRoute.prototype.checkFormatFile = function () {
        let self = this,
        $inp = $('#add-xls-inp'),
        $name = $('#add-xls-name'),
        arExt = $inp.val().match(/\\([^\\]+)\.([^\.]+)$/);

        if(arExt[2]!=='xls' && arExt[2]!=='xlsx'){
            $inp.val('');
            $('.xls-popup-err').show();
        }
        else{
            let fd = new FormData;
            fd.append('xls', $inp.prop('files')[0]);
            fd.append('type', 'xls-index');
            $.ajax({
                type: 'POST',
                url: '/ajax/Project',
                data: fd,
                processData: false,
                contentType: false,
                success: function (r){
                    r = JSON.parse(r);
                    if(r.error==true)
                    {
                        r.message = (r.message!=null ? r.message : 'load-file');
                        MainProject.showPopup('error',r.message);
                        $inp.val('');
                    }
                    else
                    {
                        $('.xls-popup-err').hide();
                        $('#xls-form').submit();
                    }
                }
            });
        }
    }

    return IndexRoute;
}());

$(document).ready(function () {
    new IndexRoute();

    $("#sortable").sortable();
    $("#sortable").disableSelection();




    /*var d1 = initializationMap(15642, 3180);
    setMapLines(15642, 3180, d1);
    setMapPoints(15642, 3180, d1);

    var d2 = initializationMap(1, 3180);
    setMapPoints(2,3180,d2);

    var d3 = initializationMap(2, 3180);
    setMapPoints(2,3180,d3);

    var d4 = initializationMap(3, 3180);
    setMapPoints(3,3180,d4);*/

});
/*
 *
 */
 /*
smLoadMap = function () {
    var sm = new scribblemaps.ScribbleMap(
        smElement,
        {controlMode: {'mapType': scribblemaps.ControlModes.SMALL}}
    );
    sm.view.setCenter({'lat': 55.758031768239185, 'lng': 37.6171875});// Moscow
    sm.map.setType('road');
    sm.view.setZoom(8);
    sm.ui.setAvailableTools([]);
    sm.ui.setMapInfoIcons([]);
    sm.ui.setMapTypes([]);
    sm.ui.styleControl(scribblemaps.ControlType.SEARCH, {display: "none"});
    sm.ui.styleControl(scribblemaps.ControlType.LINE_COLOR, {display: "none"});
    sm.ui.styleControl(scribblemaps.ControlType.FILL_COLOR, {display: "none"});
    sm.ui.styleControl(scribblemaps.ControlType.LINE_SETTINGS, {display: "none"});
    sm.ui.styleControl(scribblemaps.ControlType.UNDO_CONTROL, {display: "none"});
    sm.ui.styleControl(scribblemaps.ControlType.ZOOM, {
        background: '#abb92a',
        border: '1px solid #dedede'
    });
    console.log(sm);
};*/