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
            let type = "getTaskInfo";
            let data = self.setProperties(this, type);
            self.ajaxPushParams(data, "GET");


            $(this).closest('.task__block').find('.task__add-task').css({"display":"none"});
            $(this).closest('.task__block').find('.task__add-cancel').fadeIn();
            $(this).closest('.task__block').find('.task__add-update').fadeIn();
        });

        /**Отправляем измененные данные на сервер кнопка Изменить**/
        $('.task__block').on('click', '.task__add-update', function()
        {
            let type = "setTaskInfo";
            let data = self.setProperties(this, type);
            self.ajaxPushParams(data, "UPDATE");

            $(this).closest('.task__block').find('.task__add-task').fadeIn();
            $(this).closest('.task__block').find('.task__add-cancel').css({"display":"none"});
            $(this).closest('.task__block').find('.task__add-update').css({"display":"none"});
        });

        /**Добавить новую задачу**/
        $('.task__block').on('click', '.task__add-task', function()
        {
            let type = "addTaskNew";
            let data = self.setProperties(this, type);
            self.ajaxPushParams(data, "POST");
        });

        /**Дублироать на все даты**/
        $('.task__block').on('click', '.task__button-alldate', function()
        {
            let result = confirm("Дублироать на все даты. Вы уверены?");
            if(result){
                let type = "addTaskAllDate";
                let data = self.setProperties(this, type);
                self.ajaxPushParams(data, "POST");
            }
        });
        /**Добавить новую задачу**/
        $('.task__block').on('click', '.task__button-allpersons', function()
        {
            let result = confirm("Дублироать задачу всем. Вы уверены?");
            if(result){
                let type = "addTaskAllPersons";
                let data = self.setProperties(this, type);
                self.ajaxPushParams(data, "POST");
            }
        });

        $('.task__block').on('click', '.task__add-cancel', function()
        {
            $(this).closest('.task__block').find('.task__add-task').fadeIn();
            $(this).closest('.task__block').find('.task__add-cancel').css({"display":"none"});
            $(this).closest('.task__block').find('.task__add-update').css({"display":"none"});
        });

    };

    IndexTasks.prototype.alertPopup = function (text) {
        console.log(text);
    };

    IndexTasks.prototype.setProperties = function (e, type) {
        let parent = $(e).closest('.task__block');
        let data = [];
        let error = false;

        let task_id = $(parent).find('input[name="task_id"]').val();
        switch (type) {
            case "getTaskInfo":
                if(task_id>0) {
                    data = {
                        'task_id': task_id,
                        'project_id': $(parent).find('input[name="project_id"]').val(),
                        'person_id': $(parent).find('input[name="person_id"]').val(),
                        'type': 'getTaskInfo'
                    };
                }else{
                    this.alertPopup("Задание не выбрано!");
                    error = true;
                }
                break;
            case "setTaskInfo":
                if(task_id>0) {
                    data = {
                        'task_id': $(parent).find('input[name="task_id"]').val(),
                        'project_id': $(parent).find('input[name="project_id"]').val(),
                        'person_id': $(parent).find('input[name="person_id"]').val(),
                        'task_title': $(parent).find('input[name="task_title"]').val(),
                        'task_descr': $(parent).find('textarea[name="task_descr"]').val(),
                        'type': 'setTaskInfo'
                    };
                }else{
                    this.alertPopup("Задание не выбрано!");
                    error = true;
                }
                break;
            case "addTaskNew":

                let titleElement =  $(parent).find('.task__info-name');
                let descrElement = $(parent).find('.task__info-descr');

                let title = $(titleElement).val();
                let descr = $(descrElement).val();

                if(descr!="" && title!="") {
                    data = {
                        'task_id': $(parent).find('input[name="task_id"]').val(),
                        'project_id': $(parent).find('input[name="project_id"]').val(),
                        'person_id': $(parent).find('input[name="person_id"]').val(),
                        'task_title': title,
                        'task_descr': descr,
                        'type': 'addTaskNew'
                    };
                }
                else{
                    console.log(data);
                    this.alertPopup("Поля не заполнены!");
                    error = true;
                }
                break;
            case "addTaskAllDate":
                if(task_id>0) {
                    data = {
                        'task_id': $(parent).find('input[name="task_id"]').val(),
                        'project_id': $(parent).find('input[name="project_id"]').val(),
                        'person_id': $(parent).find('input[name="person_id"]').val(),
                        'task_title': $(parent).find('input[name="task_title"]').val(),
                        'task_descr': $(parent).find('textarea[name="task_descr"]').val(),
                        'type': 'addTaskAllDate'
                    };
                }else{
                    this.alertPopup("Задание не выбрано!");
                    error = true;
                }
                break;
            case "addTaskAllPersons":
                if(task_id>0) {
                    data = {
                        'task_id': $(parent).find('input[name="task_id"]').val(),
                        'project_id': $(parent).find('input[name="project_id"]').val(),
                        'person_id': $(parent).find('input[name="person_id"]').val(),
                        'task_title': $(parent).find('input[name="task_title"]').val(),
                        'task_descr': $(parent).find('input[name="task_descr"]').val(),
                        'type': 'addTaskAllPersons'
                    };
                }else{
                    this.alertPopup("Задание не выбрано!");
                    error = true;
                }
                break;
        }

        if(error==false){
            return data;
        }
        else{
            return false;
        }

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
        if(data) {
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
        }
        else{
            console.log("Пустые данные!");
        }

    };

    return IndexTasks;
}());

$(document).ready(function () {
    new IndexTasks();
});