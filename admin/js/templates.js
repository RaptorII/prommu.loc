/**
 * Created by Stanislav on 09.11.2018.
 */
var Templates = (function () {
    Templates.prototype.URL = '/admin/ajax/feedback';

    function Templates() {
        this.init();
    }

    Templates.prototype.init = function () {
        var self = this;

        var myNicEditor = new nicEditor(
            {
                maxHeight: 200,
                buttonList: ['bold','italic','underline','left','center','right','justify','ol','ul']
            }
        );

        $('#theme__sel-work ').click(function(){

            var themeId = $('#theme__sel-work').val();

            if (themeId) {
                self.viewTheme(themeId);
            }
        });

        $('#theme__new-btn').click(function(){
            var themeNew = $('#theme__new-input').val();

            if(!themeNew.length) {
                alert('Введите новую тему');
                return false;
            } else if(confirm('Добавть тему?')) {
                self.addTmplNewTheme(themeNew);
            }
        });

        $('#theme__del-btn').click(function(){

            var themeDel = $('#theme__sel').val();
            query = confirm('Вы точно хотите удалить выбраную тему?');

            if(query) {
                self.dellTmplTheme(themeDel);
            }
        });

        $('#save-template').click(function(){

            var theme = $('#theme__sel').val();
            var title = $('#template_title').val();
            var message = self.NicEditor.nicInstances[1].getContent().trim();

            if(!message.length || message==='<br>' || !title.length) {

                alert('Поля должны быть заполнены');
                return false;

            } else if(theme == 'None') {

                alert('Выберите тему');
                return false;

            }

            self.addTemplate(theme, title, message);
        });

        $(".templates__block").on( "click", ".template__item", function() {
            var text = $(this).find('i').html();
            self.NicEditor.nicInstances[0].setContent(text);
        });

        $(".templates__block").on( "click", ".template__item b", function() {
            var main = $(this).parent('div'),
                id = $(main).data('id'),
                query = confirm('Вы точно хотите удалить шаблон?');

            if(query)
                self.delTemplate(id);
        });

        self.NicEditor = myNicEditor;
        myNicEditor.addInstance('admin-answer');
        myNicEditor.setPanel('admin-answer-panel');
        self.NicEditor = myNicEditor;
        myNicEditor.addInstance('template_description');
        myNicEditor.setPanel('template_text-panel');
        
        $('#btn_submit').click(function()
        {
            var message = self.NicEditorAnswer.nicInstances[0].getContent().trim();
            if(!message.length || message==='<br>')
            {
                alert('Необходимо заполнить поле ответа');
                return false;
            }
        });
    };

    Templates.prototype.addTemplate = function (theme, title, text) {
        var self = this;
        $.ajax({
            type: 'GET',
            url: self.URL,
            data: {theme: theme, title: title, text: text, type:'add'},
            dataType: 'json',
            success: function (value){
                if(value.error==true)
                    return;

                var str = '<div data-id="' + value.id + '" class="template__item">'
                            + title + '<b>x</b><i>' + text + '</i></div>';

                $('.templates__block').prepend(str);
            }
        });
    };

    Templates.prototype.delTemplate = function (id) {
        var self = this;
        $.ajax({
            type: 'POST',
            url: self.URL,
            data: {id: id, type:'del'},
            dataType: 'json',
            success: function (value){
                if(value.error==true)
                    return;

                $('.templates__block').find('.template__item[data-id="'+id+'"]').remove();
            }
        });
    };

    Templates.prototype.addTmplNewTheme = function (themeNew) {
        var self = this;
        $.ajax({
            type: 'GET',
            url: self.URL,
            data: {themeNew: themeNew, type:'themeNew'},
            dataType: 'json',
            success: function (value) {
                if (value.error==true) return;
                $("#theme__sel").append('<option value="'+ value.id +'" >'+themeNew+'</optionvalue>').val(themeNew);
                $("#theme__sel-work").append('<option  value="'+ value.id +'" >'+themeNew+'</option>').val(themeNew);
            }
        });
    };

    Templates.prototype.dellTmplTheme = function (id) {
        var self = this;

        $.ajax({
           type: 'POST',
           url: self.URL,
           data: {id: id, type: 'themeDel'},
           dataType: 'json',
           success: function (value) {
               if (value.error == true) return;

               var themeDelName = $('#theme__sel option:selected').text();

               $('#theme__sel option:contains("' + themeDelName + '")').remove();
               $('#theme__sel-work option:contains("' + themeDelName + '")').remove();
           }
        });

    };

    Templates.prototype.viewTheme = function (themeId) {
        var self = this;
        $.ajax({
            type: 'GET',
            url: self.URL,
            data: {themeId: themeId, type:'themeId'},
            dataType: 'json',
            success: function (value) {
                if (value.error==true) return;
                if (value) {
                    $('.templates__block').html(
                        '<div data-id="" class="template__item"></div>'
                    );
                    $.each(value, function(){
                        $('.templates__block').append(
                            '<div data-id="' + this.id + '" class="template__item">'
                            + this.name + '<b>x</b><i>' + this.text + '</i></div>'
                        );
                    });
                }
            }
        });
    };

    return Templates;
}());

$(document).ready(function () {
    new Templates();

    $(".theme__slide-btn").click(function(){
        $("#theme__panel").slideToggle("slow");
        $(this).toggleClass("active");
    });

    /**/
    $(function(){
        //Живой поиск
        $('.who').bind("keyup", function() {
            if(this.value.length >= 2){
                $.ajax({
                    type: 'post',
                    url: '/admin/ajax/searchquick', //Путь к обработчику
                    data: {'referal': this.value},
                    response: 'text',
                    dataType: 'json',
                    success: function(value){
                         // console.log(value);
                        $('.search_result').html(
                            ' '
                        );

                        if (value.error==true) return;
                        if (value) {
                            $.each(value, function(){
                                $('.search_result').append(
                                    '<li data-id="'+ this.id +'">' + this.name + '</li>'
                                );
                            });
                        }
                    },
                })
            }
        });

        $(".search_result").hover(function(){
            $(".who").blur();
        })

        $(".search_result").on("click", "li", function(){
            s_user = $(this).text();
            idValue = $(this).attr("data-id");

            $("#theme__sel-work").val(idValue).change().trigger('click');
            // $('button').trigger('click');

            //$(".who").val(s_user).attr('disabled', 'disabled'); //деактивируем input, если нужно
            $(".search_result").html(' ');
        });

        $(document).mouseup(function (e){
            var sr = $(".search_result");
            if (!sr.is(e.target) && sr.has(e.target).length === 0) {
                $(".search_result").html(' ');
            }
        });

    });

    //human view for nicEdit
    $('.nicEdit-main').width('100%');
    $('.nicEdit-main').height('100px');
    $('.nicEdit-main').parent().width('100%');
    /**/

});