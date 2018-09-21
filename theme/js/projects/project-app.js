/**
 * Created by Stanislav on 30.08.2018.
 */

let IndexTasks = (function () {
    function IndexTasks() {
        this.init();
    }

    IndexTasks.prototype.init = function () {
        let self = this;

        $('.tasks__list').on('click','.task__table-watch',function(){
            let arU = $('.task__single'),
                user = this.dataset.user,
                date = this.dataset.date,
                point = this.dataset.point;

            for (let i=0; i<arU.length; i++) {
                if(
                    arU[i].dataset.user===user
                    &&
                    arU[i].dataset.date===date
                    &&
                    arU[i].dataset.point===point
                ) {
                    $(arU[i]).css({'display':'flex'});
                    $('.tasks__list').fadeOut();
                }
                else{
                    $(arU[i]).hide();
                }
            }
        });
    };


    return IndexTasks;
}());

$(document).ready(function () {
    new IndexTasks();


    var map = L.map('map').setView([55.7251293,37.6167661], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ''
    }).addTo(map);

    function locate() {
        map.locate({setView: true, maxZoom: 16});
    }

    function onLocationFound(e) {
        var current_position;
        if (current_position) {
            map.removeLayer(current_position);
        }
        current_position = L.marker(e.latlng).addTo(map);
        let points = current_position._latlng;
        let lat = points.lat;
        let lng = points.lng;
        let user_id = $('.global_user_id').val();
        let project_id = $('.global_project_id').val();

        if(lat && lng){
            $.ajax({
                type: 'POST',
                url: '/ajax/reportproject',
                data: {
                    latitude:lat,
                    longitude:lng,
                    idus:user_id,
                    project:project_id
                },
                dataType: 'json',
                success: function (val) {

                }
            });
        }
    }

    $('#get_points').click(function(){
        locate();
        map.on('locationfound', onLocationFound);
    });
});