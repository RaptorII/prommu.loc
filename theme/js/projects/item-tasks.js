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


        $('.task__block').on('click', '.task__name', function()
        {
            self.showHiddenUlContent(this);
        });

        $('.task__block').on('click', '.task__hidden-ul li', function()
        {
            self.setValueFromLI(this);
        });


        /************Кнопки управления*********/

        /**Запрос данных для изменения**/
        $('.task__block').on('click', '.task__button-upload', function()
        {
            let type1 = "getTaskInfo";
            console.log(type1);
            let data = self.setProperties(this, type1);
            self.ajaxPushParams(data, "GET");
        });

        /**Отправляем измененные данные обратно**/
        $('.task__block').on('click', '.task__add-update', function()
        {
            let type1 = "setTaskInfo";
            let data = self.setProperties(this, type1);
            self.ajaxPushParams(data, "UPDATE");
        });


        $('.task__block').on('click', '.task__add-update', function()
        {
            let type1 = "setTaskInfo";
            let data = self.setProperties(this, type1);
            self.ajaxPushParams(data, "POST");
        });

    };

    IndexTasks.prototype.setProperties = function (e, type2) {
        let parent = $(e).closest('.task__block');
        let data = [];

        switch (type2) {
            case "getTaskInfo":
                data = {
                    'task_id': $(parent).find('input[name="task_id"]').val(),
                    'project_id': $(parent).find('input[name="project_id"]').val(),
                    'person_id': $(parent).find('input[name="person_id"]').val()
                };
                break;
            case "setTaskInfo":
                data = {
                    'task_id': $(parent).find('input[name="task_id"]').val(),
                    'project_id': $(parent).find('input[name="project_id"]').val(),
                    'person_id': $(parent).find('input[name="person_id"]').val(),
                    'task_title': $(parent).find('input[name="task_title"]').val(),
                    'task_descr': $(parent).find('input[name="task_descr"]').val(),
                };
                break;
        }

        return data;
    };

    IndexTasks.prototype.setValueFromLI = function (e) {
        let li = e;
        let id = $(li).data('task-id');
        let value = $(li).text();
        /**Устанавливаем скрытый input***/

        $(li).closest('.task__block').find('.task_id-hidden').val(id);
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

    IndexTasks.prototype.ajaxPushParams = function (data, type) {

        let jsonString = JSON.stringify(data);
        console.log(jsonString);
        $.ajax({
            type: type,
            url: '/ajax/123', //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            data: {
                data: jsonString
            },
            dataType: 'json',
            success: function (value) {

            }
        });

    };

        return IndexTasks;
}());

$(document).ready(function () {
    console.log('IndexTasks script here!');
    new IndexTasks();
});