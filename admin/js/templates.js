/**
 * Created by Stanislav on 09.11.2018.
 */
var Templates = (function () {
    Templates.prototype.URL = '/admin/ajax/feedback';

    function Templates() {
        this.init();
    }

    Templates.prototype.init = function () {
        var self = this,
            myNicEditor = new nicEditor(
                { 
                    maxHeight: 200, 
                    buttonList: ['bold','italic','underline','left','center','right','justify','ol','ul'] 
                }
            );

        $('#save-template').click(function(){
            var title = $('#template_title').val(),
                message = self.NicEditor.nicInstances[1].getContent().trim();

            if(!message.length || message==='<br>' || !title.length)
            {
                alert('Оба поля должны быть заполнены');
                return false;
            }

            self.addTemplate(title,message);
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

    Templates.prototype.addTemplate = function (title, text) {
        var self = this;
        $.ajax({
            type: 'GET',
            url: self.URL,
            data: {title: title, text:text, type:'add'},
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

    return Templates;
}());

$(document).ready(function () {
    new Templates();


    $("#theme__sel").click(function(){
        $("#theme__new-btn").append('<option value="' + $("#theme__new-input").val() + '">' + $("#theme__new-input").val() + '</option>');
    });

});