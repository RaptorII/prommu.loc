/**
 * Created by Stanislav on 30.08.2018.
 */

let IndexTasks = (function () {
    function IndexTasks() {
        this.init();
    }

    IndexTasks.prototype.init = function () {
        let self = this;

        $('.task__item-container').on('click', '.button__task-control', function(e)
        {
            let type = $(this).data('type');
            self.setControlPanel(type, this);
        });
        $('.task__item-container').on('click', '.control__panel-add', function(e)
        {
            let type = $(this).data('type');
            self.setControlPanel(type, this);
        });


        /*********Скрол************/
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
        /*********Скрол************/

        $('.tasks').on('click', '.control__panel-save', function()
        {
            let data = [];
            $('.task__item[data-type="add"]').each(function(){

                let task_data = [];
                task_data['data'] = [];

                /*****************/
                let title = $(this).find('.name .control__text-area').val();
                task_data['data']['title'] = title;
                let descr = $(this).find('.descr .control__text-area').val();
                task_data['data']['descr'] = descr;
                let status = $(this).find('.status .control__select-area').val();
                task_data['data']['status'] = status;
                /*****************/

                /*****************/
                let panel = $(this).next();
                let all_date = $(panel).find('[name="all_date"]').is(':checked');
                task_data['all_date'] = all_date;

                let all_users = $(panel).find('[name="all_users"]').is(':checked');
                task_data['all_users'] = all_users;

                let del = $(panel).find('[name="delete"]').is(':checked');
                task_data['delete'] = del;
                /*****************/

                task_data['type'] = 'new';

                data.push(task_data);
            });

            /*******Ищем изменяемые******/
            $('textarea[data-type="change"]').each(function () {
                console.log($(this));
                let item = $(this).closest('.task__item');
                let id = $(item).data('task-id');

                let user_id = $(item).data('user-id');
                let tt_id = $(item).data('tt-id');
                let date = $(item).data('date-unix');

                let task_data = [];
                task_data['data'] = [];

                /*****************/
                let title = $(item).find('.name .control__text-area').val();
                task_data['data']['title'] = title;
                let descr = $(item).find('.descr .control__text-area').val();
                task_data['data']['descr'] = descr;
                let status = $(item).find('.status .control__select-area').val();
                task_data['data']['status'] = status;
                /*****************/

                /*****************/
                let panel = $(item).next();
                let all_date = $(panel).find('[name="all_date"]').is(':checked');
                task_data['all_date'] = all_date;

                let all_users = $(panel).find('[name="all_users"]').is(':checked');
                task_data['all_users'] = all_users;

                let del = $(panel).find('[name="delete"]').is(':checked');
                task_data['delete'] = del;
                /*****************/

                task_data['type'] = 'change';
                task_data['task_id'] = id;
                task_data['user_id'] = user_id;
                task_data['tt_id'] = tt_id;
                task_data['date'] = date;


                data.push(task_data);
            });

            console.log(data);
        });

    };

    IndexTasks.prototype.setControlPanel = function (type, e) {
        if(type=='add'){
            let template = this.getTemplate(type);
            let target = $(e).closest('.task__item-container').find('.tasks__one-user');
            $(target).append(template);

            this.setButtonPanel(target);
            this.setCheckboxPanel(target);

        }else if(type=='change'){
            let target = $(e).closest('.task__item-container').find('.tasks__one-user');
            this.setButtonPanel(target);
            this.setCheckboxPanel(target);
            this.setChangeBox(target);
        }
    };

    IndexTasks.prototype.setButtonPanel = function (e) {
        let template = '<div class="control__panel">'+
            '<button data-type="add" class="control__panel-add">+</button>'+
            '<button class="control__panel-save">Сохранить</button>'+
            '</div>';
        if(!$(e).next().hasClass('control__panel')){
            $(e).after(template);
        }
    };

    IndexTasks.prototype.setCheckboxPanel = function (e) {
        let template = '<div class="control__panel-checkbox">'+
            '<div class="panel-checkbox">'+
                '<label class="task__checkbox">'+
                    '<input class="task__checkbox-input" name="all_date" type="checkbox">'+
                    '<span class="task__checkbox-checker"><span></span></span>'+
                    'Дублировать на все даты'+
                '</label>'+
            '</div>'+


            '<div class="panel-checkbox">' +
                '<label class="task__checkbox">'+
                    '<input class="task__checkbox-input" name="all_users" type="checkbox">'+
                    '<span class="task__checkbox-checker"><span></span></span>'+
                    'Дублировать всем'+
                '</label>'+
            '</div>'+

            '<div class="panel-checkbox">' +
                '<label class="task__checkbox">'+
                    '<input class="task__checkbox-input" name="delete" type="checkbox">'+
                    '<span class="task__checkbox-checker"><span></span></span>'+
                    'Удалить'+
                '</label>'+
            '</div>'+
        '</div>';

        $(e).find('.task__item').each(function(){
            if(!$(this).next().hasClass('control__panel-checkbox')) {
                $(this).after(template);
            }
        })
    };

    IndexTasks.prototype.setChangeBox = function (e) {
        var self = this;
        $(e).find('td').each(function(){
            if($(this).hasClass('name')){
                if($(this).find('.control__text-area').length==0){
                    let text = $(this).find('div').text();
                    let task_id = $(this).closest('.task__item').data('task-id');
                    $(this).find('div').html('');
                    $(this).find('div').append('<textarea data-content="name" data-id="'+task_id+'" data-type="change" class="control__text-area">' + text.trim() + '</textarea>');
                }
            }

            if($(this).hasClass('descr')){
                if($(this).find('.control__text-area').length==0){
                    let text = $(this).find('div').text();
                    $(this).find('div').html('');
                    $(this).find('div').append('<textarea class="control__text-area">' + text.trim() + '</textarea>');
                }
            }

            if($(this).hasClass('status')){
                if($(this).find('.control__select-area').length==0) {
                    let text = $(this).find('.status__active').text();
                    let html = self.getSelectTemplate(text);
                    $(this).find('div').html('');
                    $(this).find('div').append(html);
                }
            }
        });
    };


    IndexTasks.prototype.getTemplate = function (type) {
        if(type=='add'){
            let template = '<div data-type="add" class="task__item">'+
            '<table class="task__table">'+
            '<thead>'+
            '<tr>'+
            '<th class="name">Название</th>'+
            '<th class="descr">Описание</th>'+
            '<th class="status">Статус</th>'+
            '</tr>'+
            '</thead>'+
            '<tbody>'+
            '<tr>'+
            '<td class="name">'+
            '<div class="task__table-cell border">'+
            '<textarea placeholder="Введите название задачи" class="control__text-area"></textarea>'+
            '</div>'+
            '</td>'+
            '<td class="descr">'+
            '<div class="task__table-cell border task__table-index">'+
            '<textarea placeholder="Введите описание задачи" class="control__text-area"></textarea>'+
            '</div>'+
            '</td>'+
            '<td class="status">'+
            '<div class="task__table-cell border">'+
            '<span class="tasks__status-work tasks__status">'+
            '<select class="control__select-area">'+
            '<option value="0">Ожидание</option>'+
            '<option value="1">В работе</option>'+
            '<option value="2">Доработка</option>'+
            '<option value="3">Готова</option>'+
            '<option value="4">Отменена</option>'+
            '</select>'+
            '</div>'+
            '</td>'+
            '</tr>'+
            '</tbody>'+
            '</table>'+
            '</div>';
            return template;
        }else if(type=='change'){
            console.log('change');
        }
    };

    IndexTasks.prototype.getSelectTemplate = function (value) {
        let status = [];
        status.push('Ожидание');
        status.push('В работе');
        status.push('Доработка');
        status.push('Готова');
        status.push('Отменена');

        let html ='<select data-content="status" class="control__select-area">';
        for(let i = 0; i<status.length;i++){
            if(status[i]==value){
                html+='<option value="'+i+'" selected>'+status[i]+'</option>';
            }else{
                html+='<option value="'+i+'">'+status[i]+'</option>';
            }
        }
        html+='</select>';
        return html;
    };

    return IndexTasks;
}());

$(document).ready(function () {
    new IndexTasks();
});