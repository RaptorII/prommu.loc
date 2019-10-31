'use strict'
/**
 *
 * @type {RegisterPage}
 */
var RegisterPage = (function () {
  //
  RegisterPage.prototype.firstInputCompany = true;
  RegisterPage.prototype.firstInputCode = true;
  RegisterPage.prototype.codeLength = 4;
  RegisterPage.prototype.passwordLength = 6;
  RegisterPage.prototype.cropOptions;
  RegisterPage.prototype.cropParams;
  RegisterPage.prototype.bWebCam;
  RegisterPage.prototype.codeTimer;
  //
  function RegisterPage()
  {
    this.init();
  }
  //
  RegisterPage.prototype.init = function ()
  {
    let self = this;

    self.cropOptions = [];
    self.cropParams = {
      aspectRatio: 1/1,
      viewMode: 1,
      zoomable: true,
      zoomOnWheel: false,
      rotatable: true,
      background: false,
      guides: false,
      highlight: false,
      minCropBoxWidth: 200,
      minCropBoxHeight: 200,
      preview: '.YiiUpload__editor-prev-item',
      crop: function(e){ self.cropOptions=e.detail }
    };
    self.codeTimer = false;

    self.bWebCam = true;
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    window.URL.createObjectURL = window.URL.createObjectURL || window.URL.webkitCreateObjectURL || window.URL.mozCreateObjectURL || window.URL.msCreateObjectURL;
    if(typeof navigator.getUserMedia==='undefined')
    {
      self.bWebCam = false;
    }
    self.checkSnapshot();

    $('body')
      .on( // step 1
        'change',
        '#register_form .input-type',
        function(){
          let data = self.getFormData();
          self.send(data);
        })
      .on( // step 2
        'input',
        '#register_form .input-name, #register_form .input-surname',
        function(){ self.checkName(this) })
      .on(
        'input',
        '#register_form .input-company',
        function(){ self.checkCompany(this) })
      .on(
        'input',
        '#register_form .input-login',
        function(){ self.checkText(this) })
      .on(
        'click',
        '#register_form .login__error a',
        function(e){
          e.preventDefault();
          let btn = $('#register_form').find('button'),
            step = $(btn).data('step');

          self.send({step:step,redirect:'auth',href:this.href});
        })
      .on( // step 3 | 4
        'click',
        '#register_form .back-away',
        function(){
          let btn = $('#register_form').find('button'),
            step = $(btn).data('step');

          self.send({step:step,redirect:'back'});
        })
      .on(
        'click',
        '#register_form .repeat-code',
        function(){
          let btn = $('#register_form').find('button'),
            step = $(btn).data('step');

          if(!$('.repeat-code').hasClass('grey'))
          {
            self.send({step:step,send_code:'Y'});
          }
        })
      .on(
        'input',
        '#register_form .input-code',
        function(){ self.checkCode(this)})
      .on( // step 4
        'input',
        '#register_form .input-password',
        function(){ self.checkPassword(this) })
      .on(
        'input',
        '#register_form .input-r-password',
        function(){ self.checkPassword(this) })
      .on( // form 5
        'click',
        '#register_form .btn-upload',
        function(){ $('.input-upload').click() })
      .on(
        'change',
        '#register_form .input-upload',
        function(){ self.sendImage() })
      .on(
        'click',
        '.YiiUpload__crop',
        function(){ self.sendEditImage() })
      .on( // get snapshot
        'click',
        '.YiiUpload__wc_shoot',
        function(){
          let canvas = document.querySelector('.YiiUpload__camera canvas'),
            video = document.querySelector('.YiiUpload__camera video'),
            width = video.videoWidth,
            height = video.videoHeight,
            context = canvas.getContext('2d');

          // Установка размеров canvas идентичных с video
          canvas.width = width;
          canvas.height = height;
          // Отрисовка текущего кадра с video в canvas
          context.drawImage(video, 0, 0, width, height);
          // Преобразование кадра в изображение dataURL
          let imageDataURL = canvas.toDataURL('image/png');
          if(imageDataURL.length>6)
          {
            $('.YiiUpload__camera img').attr('src',imageDataURL).show();
            $('.YiiUpload__form-btns').html('<div class="YiiUpload__form-btn YiiUpload__wc_done">Сохранить</div>'
              + '<div class="YiiUpload__form-btn YiiUpload__wc_reset">Заново</div>');
          }
        })
      .on( // end snapshot
        'click',
        '.YiiUpload__wc_done',
        function(){
          let image = $('.YiiUpload__camera img'),
            src = $(image).attr('src'),
            arr = src.split(','),
            mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]),
            n = bstr.length,
            u8arr = new Uint8Array(n),
            file;

          while(n--)
          { u8arr[n] = bstr.charCodeAt(n); };
          file = new Blob([u8arr], {type:mime});
          self.sendImage(file);
          $('.YiiUpload__block').hide();
          $('.YiiUpload__camera img').attr('src','');
          $('.YiiUpload__camera').hide();
          $('.YiiUpload__form-btns').html('');
        })
      .on( // reset snapshot
        'click',
        '.YiiUpload__wc_reset',
        function(){
          $('.YiiUpload__camera img').hide();
          $('.YiiUpload__form-btns').html('<div class="YiiUpload__form-btn YiiUpload__wc_shoot">Сделать снимок</div>');
        })
      .on(
        'click',
        '.active-logo',
        function(){
          if(this.dataset.big != 'undefined')
          {
            self.createPopup(true,true);
            $('.YiiUpload__editor-field').html('<img src="' + this.dataset.big
              + '" data-name="' + this.dataset.name
              + '" alt="' + this.alt + '" data-edit="1">');
            self.setCropper();
          }
        })
      .on(
        'click',
        '.YiiUpload__form-close', // close popup
        function(e){
          if(!$(e.target).hasClass('YiiUpload__form-close'))
            return;

          self.delPhoto();
        })
      .on(
        'click',
        '.YiiUpload__block', // close popup
        function(e){
          if(!$(e.target).hasClass('YiiUpload__block'))
            return;

          self.delPhoto();
        })
      .on(
        'click',
        '.YiiUpload__block-child', // close popup
        function(e){
          if(!$(e.target).hasClass('YiiUpload__block-child'))
            return;

          self.delPhoto();
        })
      .on(
        'click',
        '.YiiUpload__block-subchild', // close popup
        function(e){
          if(!$(e.target).hasClass('YiiUpload__block-subchild'))
            return;

          self.delPhoto();
        });
    // выключаем копипаст
    $('#register_form [type="text"]').bind('paste',function(e) { e.preventDefault() });
    //
    $('#register_form').submit(function(e){
      let btn = $(this).find('button'),
        step = Number($(btn).data('step'));

      e.preventDefault();
      if(step==2)
      {
        self.firstInputCompany = false;
        self.checkName($('#register_form .input-name'));
        self.checkName($('#register_form .input-surname'));
        self.checkCompany($('#register_form .input-company'));
        self.checkText($('#register_form .input-login'));
      }
      if(step==3)
      {
        self.firstInputCode = false;
        self.checkCode('#register_form .input-code');
      }
      if(step==4)
      {
        self.checkPassword();
      }
      if(step==5)
      {
        self.checkImage();
      }

      if(!$('#register_form .input__error').length)
      {
        let data = self.getFormData();
        self.send(data);
      }
    });
    // установка таймера
    if($('.repeat-code span').is('*'))
    {
      self.setTimer();
    }
    //
    self.startSvg();
  },
    // отправляем аяксом
    RegisterPage.prototype.send = function (data) {
      let self = this;
      $('body').addClass('prmu-load');
      $.ajax({
        type: 'POST',
        data: {data: JSON.stringify(data)},
        success: function (html) {
          $('#register_form').html(html);
          //self.startSvg();
          if(typeof data.href !=='undefined')
          {
            window.location.href = data.href;
          }
          else
          {
            self.setTimer();
            self.checkSnapshot();
            $('body').removeClass('prmu-load');
          }
        },
        error: function(){
          alert('Системная ошибка');
          $('body').removeClass('prmu-load');
        }
      });
    },
    // проверка текстового поля
    RegisterPage.prototype.checkName = function (input)
    {
      if(!$(input).is('*'))
        return true;

      let v = $(input).val().replace(/[0-9]/g,'');

      $(input).val((v.charAt(0).toUpperCase() + v.slice(1).toLowerCase()));

      return this.inputError(input, !$(input).val().trim().length);
    },
    // проверка компании
    RegisterPage.prototype.checkCompany = function (input)
    {
      if(!$(input).is('*'))
        return true;

      let v = $(input).val();

      $(input).val((v.charAt(0).toUpperCase() + v.slice(1)));
      v = $(input).val().trim();

      if(v.length>=3)
        this.firstInputCompany = false;

      let result = ((!v.length || v.length<3) && !this.firstInputCompany);

      return this.inputError(input, result);
    },
    // простая проверка на пустоту
    RegisterPage.prototype.checkText = function (input)
    {
      if(!$(input).is('*'))
        return true;

      return this.inputError(input, !$(input).val().trim().length);
    },
    // проверка кода подтверждения
    RegisterPage.prototype.checkCode = function (input)
    {
      if(!$(input).is('*'))
        return true;

      let v = $(input).val().replace(/\D/, '').substr(0,this.codeLength),
        checkCode = v.length==this.codeLength;

      $(input).val(v);

      if(checkCode)
        this.firstInputCode = false;

      return this.inputError(input, (!checkCode && !this.firstInputCode) || !v.length);
    },
    // проверка кода подтверждения
    RegisterPage.prototype.checkPassword = function ()
    {
      let input1 = $('.input-password'),
        input2 = $('.input-r-password');

      if(!$(input1).is('*') && !$(input2).is('*'))
        return true;

      if(typeof arguments[0] == 'undefined')
      {
        this.inputError(input1, !$(input1).val().length);
        this.inputError(input2, !$(input2).val().length);
        if( $(input1).val() != $(input2).val() )
        {
          this.inputError(input1, 1);
          this.inputError(input2, 1);
        }
        else if($(input1).val().length < this.passwordLength)
        {
          this.inputError(input1, 1);
          this.inputError(input2, 1);
        }
      }
      else
      {
        this.inputError(arguments[0], !$(arguments[0]).val().length);
      }
    },
    // проверка кода подтверждения
    RegisterPage.prototype.checkImage = function ()
    {
      if(!$('#login-img').is('*'))
        return true;

      this.inputError($('.login__photo-img'), !$('#login-img').hasClass('active-logo'));
    },
    // утсановка поля
    RegisterPage.prototype.inputError = function (input, error)
    {
      if(error)
      {
        $(input).addClass('input__error');
        return false;
      }
      else
      {
        $(input).removeClass('input__error');
        return true;
      }
    },
    //
    RegisterPage.prototype.startSvg = function ()
    {
      let self = this,
        height = window.innerHeight,
        width = window.innerWidth,
        s = Snap('.svg-bg');

      for (var i = 0; i < 50; i++) {
        var obj = s.rect(self.getRandom(0, width),
          self.getRandom(0, height),
          self.getRandom(20, 80),
          self.getRandom(30, 170));
        obj.attr({opacity: Math.random(), transform: 'r30'});
      }
      self.svgPulse(s,width,height);
      setInterval(function () { self.svgPulse(s,width,height) }, 20000);
    },
    //
    RegisterPage.prototype.getRandom = function (min, max)
    {
      return Math.floor((Math.random() * max) + min);
    },
    //
    RegisterPage.prototype.svgPulse = function (s, width, height)
    {
      let self = this;
      s.selectAll('rect').forEach(function (e)
      {
        e.animate({
          x: self.getRandom(0, width),
          y: self.getRandom(0, height),
          width: self.getRandom(20, 120),
          height: self.getRandom(30, 420),
          opacity: Math.random() / 2 ,
        }, 20000, mina.easeinout);
      });
    },
    // получение данных с формы
    RegisterPage.prototype.getFormData = function ()
    {
      let self = this,
        arForm = $('#register_form').serializeArray(),
        result = {};

      $(arForm).each(function () {
        result[this.name] = this.value;
      });

      return result;
    },
    // таймер отправки кода
    RegisterPage.prototype.setTimer = function ()
    {
      let self = this;
      if(!$('.repeat-code span').is('*'))
        return false;

      self.codeTimer = setInterval(function(){
        let main = $('.repeat-code span'),
          sec = Number($(main).text());

        sec--;
        if(sec<=0)
        {
          $('.repeat-code').removeClass('grey').html('Отправить повторно');
          clearInterval(self.codeTimer);
        }
        else
        {
          $(main).text(sec);
        }
      },1000);
    },
    //
    RegisterPage.prototype.setCropper = function ()
    {
      let self = this,
        image = document.querySelector('.YiiUpload__editor-field img');

      $(image).show();

      if(self.objCropper) // убираем обработчики с предыдущего изображения
      {
        $('body').off('click','.YiiUpload__editor_r-rotate');
        $('body').off('click','.YiiUpload__editor_l-rotate');
        self.objCropper.destroy();
      }
      // устанавливаем кропер на следующее изображение
      self.objCropper = new Cropper(image, self.cropParams);
      // устанавливаем обработчики поворотов
      $('body').on('click','.YiiUpload__editor_r-rotate',function(){
        self.objCropper.rotate(90)
      });
      $('body').on('click','.YiiUpload__editor_l-rotate',function(){
        self.objCropper.rotate(-90)
      });
      $('.YiiUpload__editor').fadeIn();
    },
    //
    RegisterPage.prototype.sendImage = function ()
    {
      let self = this,
        form = document.querySelector('#register_form'),
        formData = new FormData(form);

      if(typeof arguments[0]=='object')
      {
        formData.append('upload',arguments[0],'snapshot.png');
      }

      $('body').addClass('prmu-load');

      $.ajax({
        data: formData,
        type: 'POST',
        contentType: false,
        processData: false,
        success: function(r)
        {
          $('body').removeClass('prmu-load');
          r = JSON.parse(r);
          if(r.error.length) // если есть ошибки
          {
            $('.upload-block').append('<span class="login__error">');
            $('.login__error').html($.parseHTML(r.error.join('</br>')))
          }
          else // есть успешно загруженные файлы
          {
            $('.login__error').remove();
            let image = '<img src="' + r.success.path
              + '" alt="' + r.success.oldname + '" data-name="'
              + r.success.name + '"/>';

            self.createPopup(true,true);
            $('.YiiUpload__editor-field').append(image);
            self.setCropper();
          }
        },
        error: function()
        {
          alert('Системная ошибка');
          $('body').removeClass('prmu-load');
        }
      });
    },
    //
    RegisterPage.prototype.sendEditImage = function ()
    {
      let self = this,
        image = document.querySelector('.YiiUpload__editor-field img'),
        resultImage = $('.login__photo-img img');

      self.cropOptions['name'] = $(image).data('name');
      self.cropOptions['oldName'] = $(image).attr('alt');
      self.cropOptions['step'] = 5;
      if($(image).data('edit')==1)
      {
        self.cropOptions['edit'] = 1;
      }

      $('body').addClass('prmu-load');

      $.ajax({
        data: {data:JSON.stringify(self.cropOptions)},
        type: 'POST',
        success: function(r)
        {
          r = JSON.parse(r);

          if(typeof r.items == 'object')
          {
            $(resultImage).attr('src',r.items['400']);
            $(resultImage).attr('alt',r.name);
            $(resultImage).attr('data-name',r.name);
            $(resultImage).attr('data-big',r.items['000']);
            $(resultImage).addClass('active-logo');
            $('.login__photo-img').removeClass('input__error');
          }
          self.createPopup(false,false);
          self.objCropper.destroy();
        },
        error: function()
        {
          alert('Системная ошибка');
          $('body').removeClass('prmu-load');
        }
      });
    },
    // get stream from webcam
    RegisterPage.prototype.getStream = function (stream)
    {
      let browser,
        self = this,
        video = document.querySelector('.YiiUpload__camera video'),
        dataBrowser = [
          { string:navigator.userAgent, subString:"Chrome", identity:"Chrome" },
          { string:navigator.userAgent, subString:"OmniWeb", versionSearch:"OmniWeb/", identity:"OmniWeb" },
          { string:navigator.vendor, subString:"Apple", identity:"Safari", versionSearch:"Version" },
          { prop:window.opera, identity:"Opera", versionSearch:"Version" },
          { string:navigator.vendor, subString:"iCab", identity:"iCab" },
          { string:navigator.vendor, subString:"KDE", identity:"Konqueror" },
          { string:navigator.userAgent, subString:"Firefox", identity:"Firefox" },
          { string:navigator.vendor, subString:"Camino", identity:"Camino" },
          { string:navigator.userAgent, subString:"Netscape", identity:"Netscape" },
          { string:navigator.userAgent, subString:"MSIE", identity:"Internet Explorer", versionSearch:"MSIE" },
          { string:navigator.userAgent, subString:"Gecko", identity:"Mozilla", versionSearch:"rv" },
          { string:navigator.userAgent, subString:"Mozilla", identity:"Netscape", versionSearch: "Mozilla" },
          { string:navigator.vendor, subString:"Apple", identity:"Safari", versionSearch:"Version" }
        ];

      for (let i=0;i<dataBrowser.length;i++)
      {
        let dataString = dataBrowser[i].string;
        let dataProp = dataBrowser[i].prop;
        if (dataString){
          if (dataString.indexOf(dataBrowser[i].subString) != -1)
            browser = dataBrowser[i].identity;
        }
        else if (dataProp)
          browser = dataBrowser[i].identity;
      }

      if(browser=='Safari')
      {
        video.srcObject = stream;
        video.play();
      }
      else
      {
        video.srcObject = stream;
      }

      setTimeout(function(){
        $('body').removeClass('prmu-load');
        self.createPopup(true,true);
        $('.YiiUpload__camera').fadeIn();
        $('.YiiUpload__form-btns').html('<div class="YiiUpload__form-btn YiiUpload__wc_shoot">Сделать снимок</div>');
      },500);
    },
    //	show errrors by navigator
    RegisterPage.prototype.streamError = function (e)
    {
      let self = this;

      if(typeof e!='undefined')
      {
        let error = '';
        console.log(e['name']);
        if(e['name']==='PermissionDeniedError' || e['name']==='NotAllowedError')
        {
          error = 'Для съемки необходим доступ к вебкамере';
        }
        if(e['name']==='DevicesNotFoundError')
        {
          error = 'Камера не найдена';
        }
        if(e['name']==='ConstraintNotSatisfiedError')
        {
          error = 'Решение не поддерживается вашим устройством';
        }
        $('.snapshot-error').remove();
        $('.snapshot-block').append('<p class="separator center snapshot-error"><span class="login__error">' + error + '</span></p>')
      }
      $('.YiiUpload__camera').hide();
      $('body').removeClass('prmu-load');
    },
    // добавление кнопки "Сделать снимок"
    RegisterPage.prototype.checkSnapshot = function ()
    {
      let self = this;

      if(
        $('.upload-block').is('*')
        &&
        !$('.snapshot-block').is('*')
        &&
        self.bWebCam
      )
      {
        $('.upload-block').after('<p class="separator center snapshot-block">'
          + '<span class="btn-orange btn-snapshot">Сделать снимок</span></p>');
        $('.btn-snapshot').click(function(){
          $('body').addClass('prmu-load');
          self.createPopup(true,false);
          navigator.getUserMedia(
            { audio:false, video:true },
            function(e){ self.getStream(e) },
            function(e){ self.streamError(e) }
          );
        });
      }
    },
    //
    RegisterPage.prototype.createPopup = function ()
    {
      if(arguments[0] && !$('.YiiUpload__block').is('*'))
      {
        $('body').append('<div class="YiiUpload__block">'
          + '<div class="YiiUpload__block-child"><div class="YiiUpload__block-subchild">'
          + '<form class="YiiUpload__form">'
          + '<div class="YiiUpload__form-close"></div>'
          + '<div class="YiiUpload__form-content">'
          + '<div class="YiiUpload__form-title">Выберите область для отображения</div>'
          + '<div class="YiiUpload__form-body">'
          + '<div class="YiiUpload__editor">'
          + '<div class="YiiUpload__editor-field"></div>'
          + '<div class="YiiUpload__editor-prev">'
          + '<div class="YiiUpload__editor-prev-item YiiUpload__editor-prev-lg"></div>'
          + '<div class="YiiUpload__editor-prev-item YiiUpload__editor-prev-sm"></div>'
          + '</div>'
          + '<div class="YiiUpload__editor-panel">'
          + '<div class="YiiUpload__editor_l-rotate" title="Повернуть на 90 градусов влево"></div>'
          + '<div class="YiiUpload__editor_r-rotate" title="Повернуть на 90 градусов вправо"></div>'
          + '<div class="YiiUpload__crop" title="Сохранить"></div>'
          + '</div>'
          + '</div>'
          + '<div class="YiiUpload__camera">'
          + '<video autoplay playsinline></video>'
          + '<canvas></canvas>'
          + '<img src="">'
          + '</div>'
          + '<div class="YiiUpload__form-btns"></div>'
          + '</div>'
          + '</div>'
          + '</form>'
          + '</div></div></div>');
      }
      if(arguments[1])
      {
        $('.YiiUpload__block').fadeIn();
        $('body').css({overflow:'hidden'});
      }
      if(!arguments[1])
      {
        $('.YiiUpload__block').fadeOut();
        $('body').css({overflow:'inherit'});
      }
      if(!arguments[0] && $('.YiiUpload__block').is('*'))
      {
        setTimeout(function(){
          $('.YiiUpload__block').remove();
        },500);
        $('#register_form .input-upload').val('');
        $('body').removeClass('prmu-load');
      }
    },
    //
    RegisterPage.prototype.delPhoto = function ()
    {
      let self = this,
        image = $('.YiiUpload__editor-field>img'),
        data = {step:5};

      if(image.length)
      {
        data.delfile = image[0].dataset.name;
        $.ajax({
          type: 'POST',
          data: {data: JSON.stringify(data)}
        });
      }
      self.createPopup(false,false);
    }
  //
  return RegisterPage;
}());
/*
*
*/
$(document).ready(function () {
  new RegisterPage();
});