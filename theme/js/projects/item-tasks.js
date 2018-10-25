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

                    /*$.fancybox.open({
                        src  : arU[i],
                        type : 'inline',
                        touch : false
                    });*/

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

        $('.tasks__popup-close').click(function () {
            $('.tasks__popup').fadeOut();
        });


        $(".content-block").on('click', '.tasks__count', function() {

            let project= $(this).data('popup-project');
            let user = $(this).data('popup-user');
            let point = $(this).data('popup-point');
            let date = $(this).data('popup-date');
            let type = 'userdata';

            var data = self.initData(project,user,point,date, type);

            $.ajax({
                type: 'GET',
                url: '/ajax/Project',
                data: {data: JSON.stringify(data)},
                dataType: 'json',
                success: function (value){
                    if(value.tasks){
                        self.popupShow(value);
                    }
                }
            });
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


    IndexTasks.prototype.initData = function (project, user, point, date, type) {
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

    IndexTasks.prototype.popupShow = function (d) {

        var data = new Object();
        data = d.tasks;
        var user = d.user;
        var size = Object.keys(data).length;
        if(size>0){
            this.popupClear();
            var html = '';

            $.each(data, function (i, e) {
                html+='<tr>';
                html+='<td>'+ data[i].date+'</td>';
                html+='<td>'+ data[i].name+'</td>';
                html+='<td>'+ data[i].text+'</td>';
                html+='</tr>';
            });
            var popup = $(".tasks__popup");
            $(popup).find(".popup__table tbody").append(html);
            $(popup).find(".popup__user-name").html(user.firstname);
            $(popup).find(".popup__user-secondname").html(user.lastname);
            $(popup).find(".popup__content-logo").prop("src",user.logo);
            var status = user.is_online;
            var status_html = '';

            if(status==1){
                status_html = "<span class='geo__green'>● активен</span>";
            }else{
                status_html = "<span class='geo__red'>● неактивен</span>";
            }
            $(popup).find(".popup__user-status").html(status_html);

            $('.tasks__popup').fadeIn();
        }
    };

    IndexTasks.prototype.popupClear = function () {
        var popup = $(".tasks__popup");
        $(popup).find(".popup__table tbody").html('');
        $(popup).find(".popup__user-name").html('');
        $(popup).find(".popup__user-secondname").html('');
        $(popup).find(".popup__user-status").html('');
        $(popup).find(".popup__content-logo").prop("src",'');
    };

    return IndexTasks;
}());

$(document).ready(function () {
    new IndexTasks();



        if ($(window).scrollTop()>="250") $("#ToTop").fadeIn("slow");
        $(window).scroll(function(){
            if ($(window).scrollTop()<="250") $("#ToTop").fadeOut("slow");
            else $("#ToTop").fadeIn("slow");
        });

        if ($(window).scrollTop()<=$(document).height()-"999") $("#OnBottom").fadeIn("slow");
        $(window).scroll(function(){
            if ($(window).scrollTop()>=$(document).height()-"999") $("#OnBottom").fadeOut("slow");
            else $("#OnBottom").fadeIn("slow");
        });

        $("#ToTop").click(function(){$("html,body").animate({scrollTop:0},"slow")});
        $("#OnBottom").click(function(){$("html,body").animate({scrollTop:$(document).height()},"slow")});
});