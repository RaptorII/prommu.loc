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
        $('#save-template').click(function(){
            var title = $('#template_title').val();
            var text = $('#template_description').val();

            if(title.length && text.length) {
                self.addTemplate(title,text);
            }
            else {
                confirm('Оба поля должны быть заполнены');
            }
        })

        $(".templates__block").on( "click", ".template__item", function() {
            var text = $(this).find('i').text();
            $('#admin-answer').val(text);
        });

        $(".templates__block").on( "click", ".template__item b", function() {
            var main = $(this).parent('div'),
                id = $(main).data('id'),
                query = confirm('Вы точно хотите удалить шаблон?');

            if(query)
                self.delTemplate(id);
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
});