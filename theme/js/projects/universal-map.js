/**
 * Created by Stanislav on 26.09.2018.
 */

let IndexMap = (function () {

    IndexMap.prototype.mainMapContainer = false;

    function IndexMap() {
        this.init();
    }


    IndexMap.prototype.init = function () {
        let self = this;
        self.mainMapContainer =  self.initializationMapPopup();

        //Клик по значку геолокации
        $(".content-block").on('click', '.js-get-map', function() {

            let map_project= $(this).data('map-project');
            let map_user = $(this).data('map-user');
            let map_point = $(this).data('map-point');
            let map_date = $(this).data('map-date');
            let type = 'coordinates';

            var data = self.initData(map_project,map_user,map_point,map_date, type);
            self.ajaxGetMapParams(data);
        });

        //Клик по значку кнопке
        $(".content-block").on('click', '.map__universal-button', function() {

            let map_project= $(this).data('map-project')+"";
            let map_user = $(this).data('map-user')+"";
            let map_point = $(this).data('map-point')+"";
            let map_date = $(this).data('map-date')+"";
            let type = 'coordinates';


            var map_container = $(this).closest('.map__universal-container').find('.map__universal')
            if(map_container && $(map_container).data('map')!='Y') {

                var map_container = $(this).closest('.map__universal-container').find('.map__universal');
                var id = 'map_' + map_project + '_' + map_user + '_' + map_date;
                $(map_container).attr('id', id);
                $(map_container).attr('data-map', 'Y');

                var data = self.initData(map_project,map_user,map_point,map_date, type);

                setTimeout(function() {
                    var map = self.initializationMap(id);
                    self.ajaxGetBoxMapParams(map, data);
                }, 1000);

            }
        });
    };

    IndexMap.prototype.initializationMapPopup = function () {
        $('body').append('<div class="map__container"><div id="map_main"></div></div>');
        $('body').append('<div class="map__error">Ошибка</div>');

        var startPoint = [];
        startPoint.push('55.7527111');
        startPoint.push('37.6436342');
        //******Start points******//

        var map = L.map('map_main').setView(startPoint, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ''
        }).addTo(map);

        $('.map__container').hide();

        return map;
    };


    IndexMap.prototype.initializationMap = function (id) {

        var startPoint = [];
        startPoint.push('55.7527111');
        startPoint.push('37.6436342');
        //******Start points******//

        var map = L.map(id).setView(startPoint, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: ''
        }).addTo(map);

        return map;
    };



    IndexMap.prototype.setUniqueMapLines = function (map, pointsloc) {
        self = this;
        var location = [];
        if (pointsloc.error !=true) {


            //******Start points******//
            var startPoint = [];
                var longitude = pointsloc[0]['longitude'];
                var latitude = pointsloc[0]['latitude'];

                startPoint.push(latitude);
                startPoint.push(longitude);
            //******Start points******//

            map.eachLayer(function (layer) {
                map.removeLayer(layer);
            });


            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ''
            }).addTo(map);

            map.panTo(startPoint, 6);


            var size = Object.keys(pointsloc).length;

            for (var d = 0; d < size; d++) {
                var points = pointsloc[d];

                var arPoints = [];
                arPoints.push(points.longitude);
                arPoints.push(points.latitude);
                location.push(arPoints);

                var geojsonFeature = {
                    "type": "Feature",
                    "geometry": {
                        "type": "Point",
                        "coordinates": arPoints
                    }
                };

                L.geoJSON(geojsonFeature).addTo(map);
            }

            var myLines = [{
                "type": "LineString",
                "coordinates": location
            }];

            var myStyle = {
                "color": "red",
                "weight": 4,
                "opacity": 0.65
            };

            L.geoJSON(myLines, {
                style: myStyle
            }).addTo(map);

            self.mapPopupOpen();
        } else {
            self.errorPopupOpen();
        }
    };


    IndexMap.prototype.setMapLines = function (map, pointsloc) {
        self = this;
        var location = [];
        if (pointsloc.error !=true) {


            //******Start points******//
            var startPoint = [];
            var longitude = pointsloc[0]['longitude'];
            var latitude = pointsloc[0]['latitude'];

            startPoint.push(latitude);
            startPoint.push(longitude);
            //******Start points******//

            map.eachLayer(function (layer) {
                map.removeLayer(layer);
            });


            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: ''
            }).addTo(map);

            map.panTo(startPoint, 6);


            var size = Object.keys(pointsloc).length;

            for (var d = 0; d < size; d++) {
                var points = pointsloc[d];

                var arPoints = [];
                arPoints.push(points.longitude);
                arPoints.push(points.latitude);
                location.push(arPoints);

                var geojsonFeature = {
                    "type": "Feature",
                    "geometry": {
                        "type": "Point",
                        "coordinates": arPoints
                    }
                };

                L.geoJSON(geojsonFeature).addTo(map);
            }

            var myLines = [{
                "type": "LineString",
                "coordinates": location
            }];

            var myStyle = {
                "color": "red",
                "weight": 4,
                "opacity": 0.65
            };

            L.geoJSON(myLines, {
                style: myStyle
            }).addTo(map);
        }
    };


    IndexMap.prototype.mapPopupOpen = function () {
        $.fancybox.open({
            src  : "div.map__container",
            type : 'inline',
            touch : false
        });
    };

    IndexMap.prototype.errorPopupOpen = function () {
        $.fancybox.open({
            src  : "div.map__error",
            type : 'inline',
            touch : false
        });
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
            console.log('Точки юзера ' + userId + ' не найдены');
        }
    };


    IndexMap.prototype.initData = function (project, user, point, date, type) {
        var data_object = {};

        if(project.length>0) {
            data_object.project = project;
        }
        if(user.length>0) {
            data_object.user = user;
        }
        if(point.length>0){
            data_object.point = point;
        }
        if(date.length>0) {
            data_object.date = date;
        }
        if(type.length>0) {
            data_object.type = type;
        }

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
                    self.setUniqueMapLines(self.mainMapContainer, value)
                }
            }
        });
    };

    IndexMap.prototype.ajaxGetBoxMapParams = function (map, data) {
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
                    self.setMapLines(map, value)
                }
            }
        });
    };

    return IndexMap;
}());


$(document).ready(function () {
    new IndexMap();
});