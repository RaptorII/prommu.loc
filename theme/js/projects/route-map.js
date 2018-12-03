'use strict'
let GoogleMap = (function () {

    GoogleMap.prototype.mainMapContainer = false;
    GoogleMap.prototype.directionsService;
    GoogleMap.prototype.map;

    function GoogleMap() {
        this.init();
    }


    GoogleMap.prototype.init = function () {
        let self = this;

       // console.log(this.getGeoData('Донецк просп. Ватутина 36'));

        $(".content-block").on('click', '.js-get-map', function() {

            let map_project= $(this).data('map-project');
            let map_user = $(this).data('map-user');
            let map_point = $(this).data('map-point');
            let map_date = $(this).data('map-date');
            let type = 'coordinates';

            var data = self.initData(map_project,map_user,map_point,map_date, type);
            self.ajaxGetMapParamsFact(data);
        });

        $(".content-block").on('click', '.map__universal-button', function() {

            let map_project= $(this).data('map-project');
            let map_user = $(this).data('map-user');
            let map_point = $(this).data('map-point');
            let map_date = $(this).data('map-date');
            let type = 'coordinates';

            var data = self.initData(map_project,map_user,map_point,map_date, type);
            self.ajaxGetMapParamsPlan(data);
        });

        $(".content-block").on('click', '.js-get-target', function() {

            let map_project= $(this).data('map-project');
            let map_user = $(this).data('map-user');
            let map_point = $(this).data('map-point');
            let map_date = $(this).data('map-date');
            let type = 'coordinates';

            var data = self.initData(map_project,map_user,map_point,map_date, type);
            self.ajaxGetMapParamsPlan(data);
        });

        if($('.map__get-route')){

            $('.map__get-route').each(function () {
                var element = this;
                let map_project= $(this).data('map-project');
                let map_user = $(this).data('map-user');
                let map_point = $(this).data('map-point');
                let map_date = $(this).data('map-date');
                let type = 'coordinates';

                var data = self.initData(map_project,map_user,map_point,map_date, type);
                self.ajaxGetMapParamsFactForBlock(data, element);
            });
        }

        if($('.map__get-point')){

            $('.map__get-point').each(function () {
                var element = this;
                let map_project= $(this).data('map-project');
                let map_user = $(this).data('map-user');
                let map_point = $(this).data('map-point');
                let map_date = $(this).data('map-date');
                let type = 'coordinates';

                var data = self.initData(map_project,map_user,map_point,map_date, type);
                self.ajaxGetMapParamsPlanForBlock(data, element);
            });
        }

    };

    GoogleMap.prototype.initialize = function (value) {
        //console.log(this);
        var self = this;
        this.initializationMapPopup();
        var mapOptions = {
            zoom: 4,
            //center: new google.maps.LatLng(39.5, -98.35),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        this.map = new google.maps.Map(document.getElementById('map_main'),    mapOptions);
        $('.map__container').hide();



        this.directionsService = new google.maps.DirectionsService();

        //console.log(value);

        var sizeValue = Object.keys(value).length;


        if (value.error !=true && sizeValue>0) {
            var size = Object.keys(value).length;

            if (size>1){
                for (var d = 1; d < size; d++) {
                    var points_start = value[d-1];
                    var points_stop = value[d];

                    var start_point = new google.maps.LatLng(points_start.latitude, points_start.longitude);
                    var end_point = new google.maps.LatLng(points_stop.latitude, points_stop.longitude);
                    this.requestDirections(start_point, end_point, { strokeColor:'#ff8300' });
                }
            }else{
                for (var d = 0; d < size; d++) {
                    var points_stop = value[d];
                    var end_point = new google.maps.LatLng(points_stop.latitude, points_stop.longitude);
                    this.requestDirections(end_point, end_point, { strokeColor:'#ff8300' });
                }
            }

            $.fancybox.open({
                src  : "div.map__container",
                type : 'inline',
                touch : false
            });
        }else{
            $.fancybox.open({
                src  : "div.map__error",
                type : 'inline',
                touch : false
            });
        }
        /*var start_point = new google.maps.LatLng(55.738013,37.5226403);
        var end_point = new google.maps.LatLng(55.715705,37.5436313);
        var end_point2 = new google.maps.LatLng(55.706332,37.5786703);
        var end_point3 = new google.maps.LatLng(55.737122,37.5871923);

        this.requestDirections(start_point, end_point, { strokeColor:'#ff8300' });
        this.requestDirections(end_point, end_point2, { strokeColor:'#ff8300' });
        this.requestDirections(end_point2, end_point3, { strokeColor:'#ff8300' });
        this.requestDirections(end_point3, start_point, { strokeColor:'#ff8300' });*/

        /*setTimeout(function() {
            self.map.setZoom(4);
        }, 2000);*/
    };



    GoogleMap.prototype.initializeForStaticBlocks = function (value, element) {
        //console.log(this);
        var self = this;

        var element_id = $(element).attr("id");
        var mapOptions = {
            zoom: 3,
            center: new google.maps.LatLng(55.75370903771494,37.61981338262558),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        this.map = new google.maps.Map(document.getElementById(element_id),    mapOptions);

        this.directionsService = new google.maps.DirectionsService();

        //console.log(value);

        var sizeValue = Object.keys(value).length;


        if (value.error !=true && sizeValue>0) {
            var size = Object.keys(value).length;

            if (size>1){
                for (var d = 1; d < size; d++) {
                    var points_start = value[d-1];
                    var points_stop = value[d];

                    var start_point = new google.maps.LatLng(points_start.latitude, points_start.longitude);
                    var end_point = new google.maps.LatLng(points_stop.latitude, points_stop.longitude);
                    this.requestDirections(start_point, end_point, { strokeColor:'#ff8300' });
                }
            }else{
                for (var d = 0; d < size; d++) {
                    var points_stop = value[d];
                    var end_point = new google.maps.LatLng(points_stop.latitude, points_stop.longitude);
                    this.requestDirections(end_point, end_point, { strokeColor:'#ff8300' });
                }
            }
        }else{
            $(element).closest('.geo__map-container').find('.geo__item-error').fadeIn();
        }
        /*var start_point = new google.maps.LatLng(55.738013,37.5226403);
         var end_point = new google.maps.LatLng(55.715705,37.5436313);
         var end_point2 = new google.maps.LatLng(55.706332,37.5786703);
         var end_point3 = new google.maps.LatLng(55.737122,37.5871923);

         this.requestDirections(start_point, end_point, { strokeColor:'#ff8300' });
         this.requestDirections(end_point, end_point2, { strokeColor:'#ff8300' });
         this.requestDirections(end_point2, end_point3, { strokeColor:'#ff8300' });
         this.requestDirections(end_point3, start_point, { strokeColor:'#ff8300' });*/

        /*setTimeout(function() {
         self.map.setZoom(4);
         }, 2000);*/
    };


    GoogleMap.prototype.initializationMapPopup = function () {
        $('.map__container').remove()
        $('.map__error').remove()
        $('body').append('<div class="map__container"><div id="map_main"></div></div>');
        $('body').append('<div class="map__error">Данные не найдены</div>');
    };

    GoogleMap.prototype.errorPopupOpen = function () {
        $.fancybox.open({
            src  : "div.map__error",
            type : 'inline',
            touch : false
        });
    };

    GoogleMap.prototype.renderDirections = function(result, polylineOpts) {
        var directionsRenderer = new google.maps.DirectionsRenderer();

        if(polylineOpts) {
            directionsRenderer.setOptions({
                //polylineOptions: polylineOpts,
                map: this.map,
                draggable: false,
                markerOptions:{icon:'/theme/pic/projects/geo.png'},
                routeIndex: 1,
                suppressInfoWindows: false
                //suppressMarkers: true,
                //suppressInfoWindows: true

            });
        }

        directionsRenderer.setDirections(result);
    };

    GoogleMap.prototype.requestDirections = function(start, end, polylineOpts) {
        var self = this;
        this.directionsService.route({
            origin: start,
            destination: end,
            //travelMode: google.maps.DirectionsTravelMode.DRIVING
            travelMode: google.maps.DirectionsTravelMode.WALKING,
            unitSystem: google.maps.UnitSystem.METRIC,
            provideRouteAlternatives: true,
            avoidHighways: false,
            avoidTolls: true
        }, function(result) {
            self.renderDirections(result, polylineOpts);
        });
    };

    GoogleMap.prototype.initData = function (project, user, point, date, type) {
        var data_object = {};

        project = project.toString();
        user = user.toString();
        point = point.toString();
        date = date.toString();
        type = type.toString();

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

    GoogleMap.prototype.ajaxGetMapParamsFact = function (data) {
        if (!data) return;

        let self = this;

        $.ajax({
            type: 'GET',
            url: '/ajax/Project',
            data: {data: JSON.stringify(data)},
            dataType: 'json',
            success: function (value) {
                console.log(value);
                if(value['fact']){
                    self.initialize(value['fact']);
                }
            }
        });
    };

    GoogleMap.prototype.ajaxGetMapParamsPlan = function (data) {
        if (!data) return;

        let self = this;

        $.ajax({
            type: 'GET',
            url: '/ajax/Project',
            data: {data: JSON.stringify(data)},
            dataType: 'json',
            success: function (value) {
                console.log(value);
                if(value['plane']){
                    self.initialize(value['plane']);
                }
            }
        });
    };


    GoogleMap.prototype.ajaxGetMapParamsFactForBlock = function (data, element) {
        if (!data) return;

        let self = this;

        $.ajax({
            type: 'GET',
            url: '/ajax/Project',
            data: {data: JSON.stringify(data)},
            dataType: 'json',
            success: function (value) {
                console.log(value);
                if(value['fact']){
                    self.initializeForStaticBlocks(value['fact'], element);
                }
            }
        });
    };

    GoogleMap.prototype.ajaxGetMapParamsPlanForBlock = function (data, element) {
        if (!data) return;

        let self = this;

        $.ajax({
            type: 'GET',
            url: '/ajax/Project',
            data: {data: JSON.stringify(data)},
            dataType: 'json',
            success: function (value) {
                console.log(value);
                if(value['plane']){
                    self.initializeForStaticBlocks(value['plane'], element);
                }
            }
        });
    };

    GoogleMap.prototype.getGeoData = function (adress) {
        var resultlat = ''; var resultlng = '';
        $.ajax({
            async: false,
            dataType: "json",
            url: 'https://maps.googleapis.com/maps/api/geocode/json?address='+adress+'&key=AIzaSyC9M8BgorAu7Sn226LNP2rteTF5gO7KjLc',
            success: function(data){
                //console.log(data);
                for (var key in data.results) {
                    resultlat = data.results[key].geometry.location.lat;
                    resultlng = data.results[key].geometry.location.lng;
                } }
        });
        return { lat: resultlat, lng: resultlng}
    };

    return GoogleMap;
}());


$(document).ready(function () {
    new GoogleMap();
});