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


        $('.route__table-item').click(function(){
            $('.route__table-item').each(function () {
                $(this).removeClass('route__table-active');
            });
            $(this).addClass('route__table-active');
        });

        $('.route__button-change,.route__change-id').click(function(){
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

        //меняем местами
        $(".project__route-touch.touch__arrow-top").click(function(){
            var pdiv = $(this).closest('.project__route-changer').find('.route__table-active');
            pdiv.insertBefore(pdiv.prev());
            return false
        });
        //меняем местами
        $(".project__route-touch.touch__arrow-bottom").click(function(){
            var pdiv = $(this).closest('.project__route-changer').find('.route__table-active');
            pdiv.insertAfter(pdiv.next());
            return false
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
        let self = this;
            
        smElement = $(e).closest('.route__item-box').find('.routes__map')[0];

        if(!$(smElement).hasClass('map_active')){
            $(e).html('СКРЫТЬ МАРШРУТ');
            if(!self.cnt) {
                let e = document.createElement("script");
                e.src = self.urlMap;
                e.type="text/javascript";
                document.getElementsByTagName("head")[0].appendChild(e);
                self.cnt = true;  
            }
            else if(!$(smElement).html().length) {
                smLoadMap();
            }
        }
        else{
            
            $(e).html('СМОТРЕТЬ МАРШРУТ');
        }
        $(smElement).toggleClass('map_active');
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

        $('.project__route-changer').hide();
        $('.rout__main').show();
    };

    return IndexRoute;
}());

$(document).ready(function () {
    new IndexRoute();

    $("#sortable").sortable();
    $("#sortable").disableSelection();


    var map = L.map('map').setView([55.7251293,37.6167661], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ''
    }).addTo(map);

    map.locate({setView: true, maxZoom: 16});

    var myLines = [{
        "type": "LineString",
        "coordinates": [[-100, 40], [-105, 45], [-110, 55]]
    }, {
        "type": "LineString",
        "coordinates": [[-105, 40], [-110, 45], [-115, 55]]
    }];

    var myStyle = {
        "color": "#ff7800",
        "weight": 5,
        "opacity": 0.65
    };

    L.geoJSON(myLines, {
        style: myStyle
    }).addTo(map);



    var geojsonFeature = {
        "type": "Feature",
        "geometry": {
            "type": "Point",
            "coordinates": [-104.99404, 39.75621]
        },
        "geometry": {
            "type": "Point",
            "coordinates": [-100, 38]
        }
    };

    L.geoJSON(geojsonFeature).addTo(map);


    var states = [{
        "type": "Feature",
        "properties": {"party": "Republican"},
        "geometry": {
            "type": "Polygon",
            "coordinates": [[
                [-104.05, 48.99],
                [-97.22,  48.98],
                [-96.58,  45.94],
                [-104.03, 45.94],
                [-104.05, 48.99]
            ]]
        }
    }, {
        "type": "Feature",
        "properties": {"party": "Democrat"},
        "geometry": {
            "type": "Polygon",
            "coordinates": [[
                [-109.05, 41.00],
                [-102.06, 40.99],
                [-102.03, 36.99],
                [-109.04, 36.99],
                [-109.05, 41.00]
            ]]
        }
    }];

    L.geoJSON(states, {
        style: function(feature) {
            switch (feature.properties.party) {
                case 'Republican': return {color: "#ff0000"};
                case 'Democrat':   return {color: "#0000ff"};
            }
        }
    }).addTo(map);




});
/*
*
*/
smLoadMap = function(){
    var sm = new scribblemaps.ScribbleMap(
            smElement,
            { controlMode: { 'mapType': scribblemaps.ControlModes.SMALL }}
        );
    sm.view.setCenter({'lat':55.758031768239185,'lng':37.6171875});// Moscow
    sm.map.setType('road');
    sm.view.setZoom(8);
    sm.ui.setAvailableTools([]);
    sm.ui.setMapInfoIcons([]);
    sm.ui.setMapTypes([]);
    sm.ui.styleControl(scribblemaps.ControlType.SEARCH,{display:"none"});
    sm.ui.styleControl(scribblemaps.ControlType.LINE_COLOR,{display:"none"});
    sm.ui.styleControl(scribblemaps.ControlType.FILL_COLOR,{display:"none"});
    sm.ui.styleControl(scribblemaps.ControlType.LINE_SETTINGS,{display:"none"});
    sm.ui.styleControl(scribblemaps.ControlType.UNDO_CONTROL,{display:"none"});
    sm.ui.styleControl(scribblemaps.ControlType.ZOOM,{
        background: '#abb92a',
        border: '1px solid #dedede'
    });
    console.log(sm);
}