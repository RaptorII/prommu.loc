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
});