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

    IndexTasks.prototype.ajaxPushParams = function (type) {
        if(IndexTasks.prototype.project_id){
            $.ajax({
                type: type,
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