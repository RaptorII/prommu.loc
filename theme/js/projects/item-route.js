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

        $('.route__button-map').click(function () {
            self.mapOpen(this);
        });

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
                e.src = self.urlMap;
                e.type = "text/javascript";
                document.getElementsByTagName("head")[0].appendChild(e);
                self.cnt = true;
            }
            else if (!$(smElement).html().length) {
                smLoadMap();
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

    return IndexRoute;
}());

$(document).ready(function () {
    new IndexRoute();

    $("#sortable").sortable();
    $("#sortable").disableSelection();


    console.log(mapLocation);

    var d1 = initializationMap(15642, 3180);
    setMapLines(15642, 3180, d1);
    setMapPoints(15642, 3180, d1);

    var d2 = initializationMap(1, 3180);
    setMapPoints(2,3180,d2);

    var d3 = initializationMap(2, 3180);
    setMapPoints(2,3180,d3);

    var d4 = initializationMap(3, 3180);
    setMapPoints(3,3180,d4);

});
/*
 *
 */
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
};

initializationMap = function (userId, pointId) {
    var mapid = 'map_' + userId;

    //******Start points******//
    var startPoint = [];
    if (mapLocation[userId]) {
        var longitude = mapLocation[userId]['points'][pointId][0]['longitude'];
        var latitude = mapLocation[userId]['points'][pointId][0]['latitude'];

        startPoint.push(latitude);
        startPoint.push(longitude);
    }
    else {
        startPoint.push('55.7527111');
        startPoint.push('37.6436342');
    }
    //******Start points******//

    var map = L.map(mapid).setView(startPoint, 9);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ''
    }).addTo(map);

    return map;
};

setMapLines = function (userId, pointId, map) {

    var location = [];
    if (mapLocation[userId]) {
        var size = Object.keys(mapLocation[userId]['points']).length;
        for (var d = 0; d < size; d++) {
            var points = mapLocation[userId]['points'][pointId];

            $.each(points, function (i, e) {
                var arPoints = [];
                arPoints.push(this.longitude);
                arPoints.push(this.latitude);
                location.push(arPoints);
            });
        }

        var myLines = [{
            "type": "LineString",
            "coordinates": location
        }];

        var myStyle = {
            "color": "#ff7800",
            "weight": 5,
            "opacity": 0.65
        };

        L.geoJSON(myLines, {
            style: myStyle
        }).addTo(map);
    } else {
        console.log('Точки юзера ' + userId + ' не найдены');
    }
};

setMapPoints = function (userId, pointId, map) {

    var location = [];
    if (mapLocation[userId]) {
        console.log(mapLocation[userId]['points']);
        var size = Object.keys(mapLocation[userId]['points']).length;
        for (var d = 0; d < size; d++) {
            var points = mapLocation[userId]['points'][pointId];

            $.each(points, function (i, e) {
                var arPoints = [];
                arPoints.push(this.longitude);
                arPoints.push(this.latitude);
                location.push(arPoints);

                var geojsonFeature = {
                    "type": "Feature",
                    "geometry": {
                        "type": "Point",
                        "coordinates": arPoints
                    }
                };

                L.geoJSON(geojsonFeature).addTo(map);
            });
        }

    } else {
        console.log('Точки юзера ' + userId + ' не найдены');
    }
};