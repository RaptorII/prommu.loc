'use strict'
jQuery(function($){
  var ajaxTimer = false;
  // отправка формы
  $('.submit-btn').on('click',function(){
    //var v = $('#message_body').val().split('\n').join('<br>');
    // $('#message_body').val(v);
    $('#notification-form').submit();
  });
  // поиск получателей
  $('#receivers_field').on('input',function(){
    var value = this.value.trim(),
        arItems = $('[name="receivers[]"]'),
        arId = [];

    if(!value.length)
      return;

    clearTimeout(ajaxTimer);
    ajaxTimer = setTimeout(function(){
      if(arItems.length) // собираем юзеров, которых не нужно искать
      {
        $.each(arItems, function(){
          arId.push(this.value);
        });
      };
      $('#receivers_load').addClass('load');
      $.ajax({
        type: 'GET',
        url: '/admin/ajax/SearchUsers',
        data: {data:JSON.stringify({search:value,selected:arId})},//{'data':JSON.stringify(self.dataset)},
        dataType: 'json',
        success: function (result)
        {
          if(result.error)
          {
            $('#receivers_list').html('Ошибка запроса. Сообщите разработчику').show();
          }
          else if(!result.items.length)
          {
            $('#receivers_list').html(result.message).show();
          }
          else
          {
            var html = '';
            $.each(result.items, function(){
              html += '<div class="item" data-id="' + this.id + '">'
                + '<img src="' + this.src + '" alt="' + this.name + '" title="Добавить в список получателей">'
                + '<div class="info">'
                  + '<b title="Добавить в список получателей">' + this.name + '</b><br>'
                  + '<span>' + (this.status==='2' ? 'соискатель' : 'работодатель') + '</span>'
                + '</div>'
                + '<div class="links">'
                  + '<a href="/admin/' + (this.status==='2' ? 'PromoEdit/' : 'EmplEdit/') + this.id + '" class="glyphicon glyphicon-edit" target="_blank" title="Ссылка на профиль в вдминистративной части сайта"></a>'
                  + '<a href="/ankety/' + this.id + '" class="glyphicon glyphicon-new-window" target="_blank" title="Ссылка на профиль в публичной части сайта"></a>'
                + '</div>'
                + '</div>';
            });
            $('#receivers_list').html(html).show();
          };
          $('#receivers_load').removeClass('load');
        }
      });
    },1000);
  });
  //
  $(document).on('click',function(e){
    // закрываем список
    if(
      !$(e.target).is('#receivers_list')
      &&
      !$(e.target).closest('#receivers_list').length
      &&
      !$(e.target).is('#receivers_field')
    )
    {
      $('#receivers_list').hide();
    }
    else if($(e.target).is('#receivers_field')) // открываем список
    {
      $('#receivers_list').show();
    }
    // выбираем из списка
    if($(e.target).is('#receivers_list img') || $(e.target).is('#receivers_list b'))
    {
      var item = $(e.target).closest('.item'),
          parent = $(item).clone(),
          id = $(parent).data('id'),
          html = '<input type="hidden" name="receivers[]" value="' + id + '"><i class="glyphicon glyphicon-remove" title="Удалить из списка получателей"></i>';

      $(item).remove();
      $(parent).append(html);
      $(parent).find('img').attr('title','Добавлен в список получателей');
      $(parent).find('.info b').attr('title','Добавлен в список получателей');
      $('#receivers_list').hide();

      $('#receivers_result').append(parent);
    }
    // удаляем блок
    if($(e.target).is('#receivers_result i'))
    {
      var item = $(e.target).closest('.item');
      $(item).remove();
    }
  });
});