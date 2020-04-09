'use strict'
var ServicesList = (function () {

    function ServicesList() {
        var self = this;
        self.init();
    }
    ServicesList.prototype.init = function () {
      var self = this;
      $(".order-service a").click(function (e) {
        self.onOrderServiceClickFn(e, this);
      });
      //
      $('.user.creation-vacancy').click(function () {
        $.fancybox.open({
          src: '.creation-vacancy_mess.prmu__popup',
          type: 'inline',
          touch: false
        });
      });
      // Переключаем между секциями
      $('.services__sections-item').click(function () {
        $('#' + this.dataset.section).fadeIn();
        $('.services__sections').fadeOut();
      });
      $('.services__back').click(function(){
        var main = $(this).closest('.disable');

        $(main).fadeOut();
        $('.services__sections').fadeIn();
      });
      // листаем по юзерам
      $('.history__users-name').click(function(){
        var main = $(this).closest('.history__item-users')[0],
            content = $(main).find('.history__users-ajax');

        if($(main).hasClass('enable')) // скрыть
        {
          $(this).removeClass('active');
          setTimeout(function(){ $(main).removeClass('enable') },300);
          $(content).fadeOut();
        }
        if(!$(main).hasClass('enable')) // показать
        {
          if(!$(content).text().length)
          {
            self.getUsers(content, {service:main.dataset.id, event:'service_users'});
          }
          else
          {
            $(content).fadeIn();
          }
          $(this).addClass('active');
          setTimeout(function(){ $(main).addClass('enable') },300);
        }
      });
      // пагинация для юзеров
      $('.history__users-ajax').on('click','.paging-wrapp a',function(e){
        e.preventDefault();
        var main = $(e.target).closest('.history__item-users')[0],
            content = $(main).find('.history__users-ajax');

        self.getUsers(content, e.target.href);
      });
      //
      if(typeof arSuccessMess === 'undefined' || arSuccessMess == null)
          return;

      if(arSuccessMess.event==='social')
          var itm = $('.repost-to-social-form').clone();
      else if(arSuccessMess.event==='email' || arSuccessMess.event==='push')
          var itm = $('.email-invitation-form').clone();
      else if(arSuccessMess.event==='free')
          var itm = $('.services-finish-form.free').clone();
      else
          var itm = $('.services-finish-form.payable').clone();
      itm.toggleClass('services-form tmpl');
      ModalWindow.open({ content: itm, action: { active: 0 }, additionalStyle:'dark-ver' });
    };
    //
    ServicesList.prototype.getUsers = function(e, data)
    {
      console.log(typeof data);

      MainScript.stateLoading(true);
      $.ajax({
        data: (typeof data=='object' ? data : ''),
        url: (typeof data=='string' ? data : ''),
        success: function (result)
        {
          $(e).html(result).fadeIn();
          MainScript.stateLoading(false);
        }
      });
    }
    //
    ServicesList.prototype.onOrderCreateFn = function (e, that) {
        var self = this;
        var $that = $(that);
        var fc = new FormCheckers();
        var ret = fc.FormSubmit({ event: e,
            form: $that.closest('form'),
            justReturn: 1
        });
        e.preventDefault();
        var checkFields = new InputFields;
        if(!checkFields.checkPhone('#service-phone')) ret = false;
        if(!checkFields.checkEmail('#service-email')) ret = false;
        if (ret) {
            var props = $that.closest('form').serialize();
            $.post(MainConfig.AJAX_POST_CREATESERVICEORDER, props, function (data) {
                data = JSON.parse(data);
                var itm = $(".order-success-tpl").clone();
                itm.toggleClass('order-success-tpl tmpl order-success');
                ModalWindow.redraw({ content: itm, action: { active: 1 } });
            });
        }
    };

    ServicesList.prototype.onOrderServiceClickFn = function (e, that) {
        var self = this,
            type = $(that).parent().data('type'),
            id = $(that).parent().data('id');

        if($(that).hasClass('user'))
            return;
        e.preventDefault();
        if(type==='disable' || type==='geolocation-staff') {
            var itm = $(".services-form.disable-form").clone();
            itm.toggleClass('services-form tmpl');
            ModalWindow.open({ content: itm, action: { active: 0 }, additionalStyle:'dark-ver' });
        }
        else if(type==='sms-informing-staff'){
            var itm = $(".services-form.sms-form").clone();
            itm.toggleClass('services-form tmpl');
            ModalWindow.open({ 
                content: itm, 
                action: { active: 0 }, 
                additionalStyle:'light-ver',
                afterOpen: function () { $(".mw-win").css({ position:'fixed', top:'40%'}) }
            });
        }
        else if(type==='push-notification'){
            var itm = $(".services-form.push-form").clone();
            itm.toggleClass('services-form tmpl');
            $('body').animate({ scrollTop: 0 }, 500);
            ModalWindow.open({ 
                content: itm, 
                action: { active: 0 }, 
                additionalStyle:'dark-ver',
                afterOpen: function () { $(".mw-win.dark-ver").css({ position:'fixed', margin: '0 0 0 50%', left:'-175px', top: '100px' }) }
            });
        }
        else if(type==='premium-vacancy'){
            var itm = $(".services-form.premium-form.premium").clone();
            itm.toggleClass('services-form tmpl');
            ModalWindow.open({ 
                content: itm, 
                action: { active: 0 }, 
                additionalStyle:'light-ver',
                afterOpen: function () { $(".mw-win").css({position:'fixed', top:'40%'}) }
            });
        }
        else if(type==='podnyatie-vacansyi-vverh'){
            var itm = $(".services-form.premium-form.upvacancy").clone();
            itm.toggleClass('services-form tmpl');
            ModalWindow.open({
                content: itm,
                action: { active: 0 },
                additionalStyle:'light-ver',
                afterOpen: function () { $(".mw-win").css({position:'fixed', top:'40%'}) }
            });
        }
        else if(type==='personal-invitation'){
            var itm = $(".services-form.premium-form.personal-invitation").clone();
            itm.toggleClass('services-form tmpl');
            ModalWindow.open({
                content: itm,
                action: { active: 0 },
                additionalStyle:'light-ver',
                afterOpen: function () { $(".mw-win").css({position:'fixed', top:'40%'}) }
            });
        }
        else if(type==='publish-vacancy'){
            var itm = $(".services-form.premium-form.vacancy").clone();
            itm.toggleClass('services-form tmpl');
            ModalWindow.open({
                content: itm,
                action: { active: 0 },
                additionalStyle:'light-ver vacancy',
                afterOpen: function () { $(".mw-win").css({position:'fixed', top:'40%'}) }
            });
        }
        else if(type==='creation-vacancy'){
          $.fancybox.open({
            src: '.creation-vacancy_mess.prmu__popup',
            type: 'inline',
            touch: false
          });
        }
        else{
            $.get(MainConfig.AJAX_GET_GETSERVICE, { id: id }, function (data) {
                data = JSON.parse(data);
                var itm = $(".form-order-tpl").clone();
                itm.attr('data-title', data.name);
                itm.find('#HiId').val(id);
                var checkFields = new InputFields;
                checkFields.setPhoneMask(itm.find('#service-phone'));
                itm.toggleClass('form-order-tpl tmpl form-order');
                ModalWindow.open({ 
                    content: itm, 
                    action: { active: 0 },
                    afterOpen: function () { $(".mw-win").css({ position:'fixed', top:'40%'}) }
                });
                itm.find('.btn-order-create').click(function (e) { self.onOrderCreateFn(e, this); });
                itm.find('#service-phone').change(function(){ checkFields.checkPhone('#service-phone') });
                itm.find('#service-email').change(function(){ checkFields.checkEmail('#service-email') });
            });
        }
    };

    return ServicesList;
}());
//
//
//
$(document).ready(function () {
  new ServicesList();
});