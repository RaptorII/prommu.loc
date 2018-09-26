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

    var btnElement,
        map = L.map('map').setView([55.7251293,37.6167661], 10)
                .on('locationfound', onLocationFound);

    $('.project__module').on('click','.app__loc-send',function(e){ 
        map.locate();
        btnElement = this;
    });

    function onLocationFound(e) {
        let cp = L.marker(e.latlng).addTo(map),
            data = {
                    type : 'coordinates',
                    project : btnElement.dataset.project,
                    user : btnElement.dataset.user,
                    point : btnElement.dataset.point,
                    latitude :cp._latlng.lat,
                    longitude : cp._latlng.lng
                };

        if(cp._latlng.lat && cp._latlng.lng){
            $.ajax({
                type: 'POST',
                url: '/ajax/Project',
                data: {data: JSON.stringify(data)},
                dataType: 'json',
                success: function (r) {
                    console.log(r);
                    r.error==false
                    ? MainProject.showPopup('success','output-gps')
                    : MainProject.showPopup('error','server');
                }
            });
        }
        else {
            MainProject.showPopup('error','server');
        }
    } 
});