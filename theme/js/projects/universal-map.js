/**
 * Created by Stanislav on 26.09.2018.
 */

let IndexMap = (function () {

    IndexRoute.prototype.mainMapContainer = false;

    function IndexMap() {
        this.init();
    }

    IndexMap.prototype.init = function () {
        let self = this;
        self.mainMapContainer =  self.initializationMapPopup();
        $.fancybox.open({
            src  : "div.map__container",
            type : 'inline',
            touch : false
        });

        $('.js-get-map').click(function () {

            let map_project= $(this).data('map-project');
            let map_user = $(this).data('map-user');
            let map_point = $(this).data('map-point');
            let map_date = $(this).data('map-date');
            let type = 'coordinates';

            var data = self.initData(map_project,map_user,map_point,map_date, type);
            self.ajaxGetMapParams(data);
        });
    };

    IndexMap.prototype.initializationMapPopup = function () {
        $('body').append('<div class="map__container"><div id="map_main"></div></div>');

        var startPoint = [];
        startPoint.push('55.7527111');
        startPoint.push('37.6436342');
        //******Start points******//

        var map = L.map('map_main').setView(startPoint, 9);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ''
        }).addTo(map);

        return map;
    };

    IndexMap.prototype.initializationMap = function (userId, pointId) {
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

    IndexMap.prototype.setMapLines = function (userId, pointId, map) {

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
            console.log('����� ����� ' + userId + ' �� �������');
        }
    };

    IndexMap.prototype.setMapPoints = function (userId, pointId, map) {

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
            console.log('����� ����� ' + userId + ' �� �������');
        }
    };


    IndexMap.prototype.initData = function ( project, user, point, date, type) {
        var data_object = {};
        data_object.project = project;
        data_object.user = user;
        data_object.point = point;
        data_object.date = date;
        data_object.type = type;

        return data_object;
    };


    IndexMap.prototype.ajaxGetMapParams = function (data) {
        if (!data) return;

        let self = this;

        $.ajax({
            type: 'GET',
            url: '/ajax/Project',
            data: {data: JSON.stringify(data)},
            dataType: 'json',
            success: function (value) {
                console.log(value);
                if(value){

                }
            }
        });
    };

    return IndexMap;
}());


$(document).ready(function () {
    new IndexMap();
});