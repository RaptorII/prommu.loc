/**
 * Created by Stanislav on 30.08.2018.
 */

let IndexTasks = (function () {

    IndexTasks.prototype.task_title;
    IndexTasks.prototype.task_descr;
    IndexTasks.prototype.person_id;
    IndexTasks.prototype.task_id;
    IndexTasks.prototype.project_id;
    IndexTasks.prototype.task_date;
    IndexTasks.prototype.task_location_id;


    function IndexTasks() {
        this.init();
    }

    IndexTasks.prototype.init = function () {
        let self = this;

        $('.task__add-task').click(function(){
            self.findProperties(this);
            self.ajaxPushParams('POST');
        });

        $('.task__add-update').click(function(){
            self.findProperties(this);
            self.ajaxPushParams('UPDATE');
        });

        $('.task__block').on('click', '.task__name', function()
        {
            self.showHiddenUlContent(this);
        });

        $('.task__block').on('click', '.task__hidden-ul li', function()
        {
            self.setValueFromLI(this);
        });

    };

    IndexTasks.prototype.setValueFromLI = function (e) {
        let li = e;
        let value = $(li).text();
        /**Устанавливаем скрытый input***/

        /******Устанавливаем value в span*******/
        $(li).closest('.task__block').find('.task__name').text(value);
        /******Скрываем список ul*******/
        $(li).parent().fadeOut();
    };

    /**Функция отображения/скрытия пунктов меню**/
    IndexTasks.prototype.showHiddenUlContent = function (e) {
        let element = e;
        $(element).closest('.task__single-info').find('.task__hidden-ul').fadeOut();;

        let hiddenUl = e.nextElementSibling;
        if($(hiddenUl).is(":visible")){
            $(hiddenUl).fadeOut();
        }else{
            $(hiddenUl).fadeIn();
        }
    };


    IndexTasks.prototype.findProperties = function (e) {
        let parent = $(e).closest('.task__block');

        IndexTasks.prototype.task_title = parent.find('input[name="task_title"]');
        IndexTasks.prototype.task_descr = parent.find('input[name="task_descr"]');
        IndexTasks.prototype.person_id = parent.find('input[name="person_id"]');
        IndexTasks.prototype.task_id = parent.find('input[name="task_id"]');
        IndexTasks.prototype.project_id = parent.find('input[name="project_id"]');
        IndexTasks.prototype.task_date = parent.find('input[name="task_date"]');
        IndexTasks.prototype.task_location_id = parent.find('input[name="task_location_id"]');
    };

    IndexTasks.prototype.ajaxPushParams = function () {
        if(IndexTasks.prototype.project_id){
            $.ajax({
                type: 'POST',
                url: '/ajax/123', //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                data: {
                    task_title: IndexTasks.prototype.task_title,
                    task_descr: IndexTasks.prototype.task_descr,
                    person_id: IndexTasks.prototype.person_id,
                    task_id: IndexTasks.prototype.task_id,
                    project_id: IndexTasks.prototype.project_id,
                    task_date: IndexTasks.prototype.task_date,
                    task_location_id: IndexTasks.prototype.task_location_id
                },
                dataType: 'json',
                success: function (value) {

                }
            });
        }
    };

        return IndexTasks;
}());

$(document).ready(function () {
    console.log('IndexTasks script here!');
    new IndexTasks();
});