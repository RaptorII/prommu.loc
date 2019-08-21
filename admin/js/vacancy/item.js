'use strict'
var nicEditorParams = {
    maxHeight: 200,
    buttonList: ['bold','italic','underline','left','center','right','justify','ol','ul']
  },
  nicEditorReq = new nicEditor(nicEditorParams),
  nicEditorDuties = new nicEditor(nicEditorParams),
  nicEditorCond = new nicEditor(nicEditorParams),
  nicEditorComment = new nicEditor(nicEditorParams);

jQuery(function($){
  nicEditorReq.addInstance('area_requirements');
  nicEditorReq.setPanel('panel_requirements');
  nicEditorDuties.addInstance('area_duties');
  nicEditorDuties.setPanel('panel_duties');
  nicEditorCond.addInstance('area_conditions');
  nicEditorCond.setPanel('panel_conditions');
  nicEditorComment.addInstance('area_comment');
  nicEditorComment.setPanel('panel_comment');
});
jQuery(function($){
  $(document).on(
    'click',
    '.notif-module tbody td',
    function(e){
      var parent = $(this).closest('.notif-module')[0],
        type = parent.dataset.type,
        id = $(this).siblings('td').eq(0).text(),
        url = '/admin/notifications/' + id + '?type=' + type;

      if(!$(this).hasClass('empty'))
        $(location).attr('href',url);
    });
  $('#tablist a').on('click',function(){
    var link = this.href,
      start = link.indexOf('#') + 1,
      anchor = link.substr(start, link.length - start),
      newLink = location.protocol + '//' + location.host + location.pathname + '?anchor=' + anchor;

    window.history.pushState('object or string', 'page name', newLink);
  });
  // ajax для отзывов
  $(document).on('click','a',function(e){
    if(
      $(e.target).closest('#tab_responses .pager').length
      ||
      $(e.target).closest('#tab_responses .responses__tabs').length
    )
    {
      e.preventDefault();
      $('#tab_responses').addClass('load');
      $.ajax({
        url: e.target.href,
        success: function (r) {
          $('#tab_responses').html(r).removeClass('load');
        }
      });
    }
  });
});