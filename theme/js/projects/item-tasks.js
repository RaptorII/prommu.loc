/**
 * Created by Stanislav on 30.08.2018.
 */

let IndexTasks = (function () {
    function IndexTasks() {
        this.init();
    }

    IndexTasks.prototype.init = function () {
        let self = this;

        /************Кнопки управления*********/

        /**Добавить новую задачу**/
        $('.task__block').on('click', '.task__add-task', function(e)
        {
            let data = self.setProperties(this, "new-task");
            self.ajaxPushParams(data, "POST", this);
        });
        /**Запрос данных для изменения**/
        $('.task__block').on('click', '.task__button-change', function()
        {
            let data = self.setProperties(this, "change-task");
            self.ajaxPushParams(data, "POST", this);
        });
        /**Дублироать на все даты**/
        $('.task__block').on('click', '.task__button-alldate', function()
        {
            let result = confirm("Вы действительно хотите продублировать задачу на весь период работ?");
            if(result){
                let data = self.setProperties(this, "all-dates-task");
                self.ajaxPushParams(data, "POST", this);
            }
        });
        /**Добавить новую задачу для всех юзеров**/
        $('.task__block').on('click', '.task__button-users', function()
        {
            let result = confirm("Вы действительно хотите продублировать задачу всему персоналу?");
            if(result){
                let data = self.setProperties(this, "all-users-task");
                self.ajaxPushParams(data, "POST", this);
            }
        });
        /**Удалить задачу**/
        $('.task__block').on('click', '.task__button-del', function(e)
        {
            let result = confirm("Вы действительно хотите удалить задачу?");
            if(result){
                let data = self.setProperties(this, "delete-task");
                self.ajaxPushParams(data, "POST", this);
            }
        });

        // Переход на страницу с задачами
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
        // Работаем с селектами
        $(document).on('click', function(e) {
            let $e = $(e.target),
                data = e.target.dataset,
                arList = $('.users__list .task__hidden-ul');


            if(!$e.closest('.task__tasks-title').length) {
                for(let i=0; i<arList.length; i++)
                    $(arList[i]).fadeOut();  // закрываем все списки
            }
            if($e.is('.task__name')) { // закрываем все без фокуса
                let list = e.target.nextElementSibling;
                for(let i=0; i<arList.length; i++) {
                    $(list).is(arList[i])
                    ? $(arList[i]).fadeIn()
                    : $(arList[i]).fadeOut();
                }
            }
            if($e.is('.task__hidden-ul li')) { // выбираем из списка
                let list = e.target.parentElement,
                    parent = $e.closest('.task__block'),
                    select = $e.text(),
                    name = $(parent).find('.task__info-name'),
                    text = $(parent).find('.task__info-descr'),
                    buttons = $(parent).find('.task__tasks-buttons'),
                    create = $(parent).find('.task__add-task');

                for(let i=0; i<arList.length; i++)
                    $(arList[i]).fadeOut();

                $(parent).find('.task_id-hidden').val(data.id);
                $(parent).find('.task__name').text(select);
                if(data.id==='new') {
                    $(name).val('');
                    $(text).val('');
                    $(buttons).fadeOut();
                    $(create).show();
                }
                else {
                    $(name).val(select);
                    $(text).val(data.text);
                    $(buttons).fadeIn();
                    $(create).hide();
                }


            }
        });


        $('.tasks__count').click(function () {
            $('.tasks__popup').fadeIn();
        });
        $('.tasks__popup-close').click(function () {
            $('.tasks__popup').fadeOut();
        });
    };

    IndexTasks.prototype.setProperties = function (e, type) {
        let parent = $(e).closest('.task__block'),
            arInputs = $(parent).find('[type=hidden]'),
            arLi = $(parent).find('.task__hidden-ul li'),
            data = {'type' : type};

        for (let i=0; i<arInputs.length; i++)
            data[$(arInputs[i]).attr('name')] = $(arInputs[i]).val();

        data['title'] = $(parent).find('.task__info-name').val();
        data['text'] = $(parent).find('.task__info-descr').val();

        switch (type) {
            case "change-task":         
                for (let i=0; i<arLi.length; i++)
                    if(
                        arLi[i].dataset.id==data.task
                        &&
                        arLi[i].dataset.text===data.text 
                        &&
                        $(arLi[i]).text()===data.title 
                        ) {
                        MainProject.showPopup('notif','nochange');
                        return false;
                    }
                break;
            case "new-task":
                if(data['title']==="" || data['text']==="") {
                    MainProject.showPopup('notif','empty-fields');
                    return false;
                }
                break;
        }
        return data;
    };

    IndexTasks.prototype.ajaxPushParams = function (data, type, e) {
        if(!data) return;
        
        $.ajax({
            type: 'POST',
            url: '/ajax/Project',
            data: { data: JSON.stringify(data) },
            dataType: 'json',
            success: function (r){
                if(r.error==true) {
                    MainProject.showPopup('error','server');
                    return;
                }
                if(data.type==='new-task') {
                    MainProject.showPopup('success',data.type);
                    let parent = $(e).closest('.task__block'),
                        top = $(parent).find('.task__tasks-title');

                    $(top).find('span').text(data.title);
                    $(top).find('ul').append('<li data-id="'
                        +r.data.task+'" data-text="'+data.text
                        +'">'+data.title+'</li>');
                    $(parent).find('.task_id-hidden').val(r.data.task);
                    $(top).fadeIn();
                    $(parent).find('.task__tasks-buttons').fadeIn();
                    $(parent).find('.task__empty').hide();
                    $('.task__add-task').hide();
                }
                if(data.type==='change-task' || data.type==='all-dates-task' || data.type==='all-users-task') {
                    MainProject.showPopup('success',data.type);
                    let parent = $(e).closest('.task__block'),
                        top = $(parent).find('.task__tasks-title'),
                        li = $(top).find('.task__hidden-ul [data-id='+data.task+']')[0];

                    $(top).find('span').text(data.title);
                    $(li).text(data.title);
                    li.dataset.text = data.text;
                }
                if(data.type==='delete-task') {
                    MainProject.showPopup('success',data.type);
                    let parent = $(e).closest('.task__block'),
                        top = $(parent).find('.task__tasks-title');
                        arLi = $(top).find('.task__hidden-ul li');

                    $(top).find('span').text('Новое задание');
                    $(top).find('.task__hidden-ul [data-id='+data.task+']').remove();
                    $(parent).find('.task_id-hidden').val('new');
                    $(parent).find('.task__info-name').val('');
                    $(parent).find('.task__info-descr').val('');
                    $(parent).find('.task__tasks-buttons').fadeOut();
                    $('.task__add-task').show();
                    if(arLi.length==2) {
                        $(top).hide();
                        $(parent).find('.task__empty').fadeIn();
                    }   
                }                
            }
        });
    };

    return IndexTasks;
}());

$(document).ready(function () {
    new IndexTasks();
});