'use strict'
/**
 *
 * @type {UploadAvatar}
 */
var UploadAvatar = (function () {
  //
  UploadAvatar.prototype.cropOptions;
  UploadAvatar.prototype.cropParams;
  UploadAvatar.prototype.bWebCam;
  UploadAvatar.prototype.video;
  //
  function UploadAvatar()
  {
    this.init();
  }
  //
  UploadAvatar.prototype.init = function ()
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

    self.bWebCam = true;
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    window.URL.createObjectURL = window.URL.createObjectURL || window.URL.webkitCreateObjectURL || window.URL.mozCreateObjectURL || window.URL.msCreateObjectURL;
    if(typeof navigator.getUserMedia==='undefined')
    {
      self.bWebCam = false;
    }
    self.checkSnapshot();

    $('body')
      .on(
        'click',
        '#avatar_form .btn-upload',
        function(){
          console.log(1);
          $('.input-upload').click();
        })
      .on(
        'change',
        '#avatar_form .input-upload',
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
            context = canvas.getContext('2d'),
            width, height;

          self.video = document.querySelector('.YiiUpload__camera video');
          width = self.video.videoWidth;
          height = self.video.videoHeight;
          // Установка размеров canvas идентичных с video
          canvas.width = width;
          canvas.height = height;
          // Отрисовка текущего кадра с video в canvas
          context.drawImage(self.video, 0, 0, width, height);
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
          self.video.srcObject.getTracks()[0].stop(); // выключаем поток
          //
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
      .on( // edit image OR upload image
        'click',
        '#login-img',
        function(){
          if($(this).hasClass('active-logo'))
          {
            if(this.dataset.big != 'undefined')
            {
              self.createPopup(true,true);
              $('.YiiUpload__editor-field').html('<img src="' + this.dataset.big
                + '" data-name="' + this.dataset.name
                + '" alt="' + this.alt + '" data-edit="1">');
              self.setCropper();
            }
          }
          else
          {
            $('.input-upload').click();
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
  },
  // проверка кода подтверждения
  UploadAvatar.prototype.checkImage = function ()
  {
    if(!$('#login-img').is('*'))
      return true;

    this.inputError($('.ppp__logo-main'), !$('#login-img').hasClass('active-logo'));
  },
  // утсановка поля
  UploadAvatar.prototype.inputError = function (input, error)
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
  UploadAvatar.prototype.setCropper = function ()
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
  UploadAvatar.prototype.sendImage = function ()
  {
    let self = this,
      form = document.querySelector('#avatar_form'),
      formData = new FormData(form);

    if(typeof arguments[0]=='object')
    {
      formData.append('upload',arguments[0],'snapshot.png');
    }
    else
    {
      let input = $('.input-upload')[0],
        f = input.files[0],
        arName = $(input).val().match(/\\([^\\]+)\.([^\.]+)$/);

      if(arName!=null && arName[1].length && arName[2].length)
      {
        let name = arName[1] + '.' + arName[2],
          format = arName[2].toLowerCase();

        if($.inArray(format, imageParams.fileFormat)<0) // проверяем формат на корректность
        {
          $('.upload-block').append('<span class="login__error">');
          $('.login__error').html('У файла некорректный формат');
          $(input).val('');
          return;
        }
        if((imageParams.maxFileSize * 1024 * 1024) < f.size)
        {
          $('.upload-block').append('<span class="login__error">');
          $('.login__error').html('Размер файла больше допустимого значения (' + imageParams.maxFileSize + 'Мб)');
          $(input).val('');
          return;
        }
      }
      else // недопустимое название файла
      {
        $('.upload-block').append('<span class="login__error">');
        $('.login__error').html('Некорректный файл');
        $(input).val('');
        return;
      }
    }

    MainScript.stateLoading(true);

    $.ajax({
      url: '/ajax/RegisterAvatar',
      data: formData,
      type: 'POST',
      contentType: false,
      processData: false,
      success: function(r)
      {
        MainScript.stateLoading(false);
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
        confirm('Системная ошибка');
        MainScript.stateLoading(false);
      }
    });
  },
  //
  UploadAvatar.prototype.sendEditImage = function ()
  {
    let self = this,
      image = document.querySelector('.YiiUpload__editor-field img'),
      resultImage = $('.ppp__logo-main img');

    self.cropOptions['name'] = $(image).data('name');
    self.cropOptions['oldName'] = $(image).attr('alt');

    if($(image).data('edit')==1)
    {
      self.cropOptions['edit'] = 1;
    }

    MainScript.stateLoading(true);

    $.ajax({
      url: '/ajax/RegisterAvatar',
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
          $('.ppp__logo-main').removeClass('input__error');
        }
        self.createPopup(false,false);
        self.objCropper.destroy();
      },
      error: function()
      {
        confirm('Системная ошибка');
        MainScript.stateLoading(false);
      }
    });
  },
  // get stream from webcam
  UploadAvatar.prototype.getStream = function (stream)
  {
    let browser,
      self = this,
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

    self.video = document.querySelector('.YiiUpload__camera video');

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
      self.video.srcObject = stream;
      self.video.play();
    }
    else
    {
      self.video.srcObject = stream;
    }

    setTimeout(function(){
      MainScript.stateLoading(false);
      self.createPopup(true,true);
      $('.YiiUpload__camera').fadeIn();
      $('.YiiUpload__form-btns').html('<div class="YiiUpload__form-btn YiiUpload__wc_shoot">Сделать снимок</div>');
    },500);
  },
  //	show errrors by navigator
  UploadAvatar.prototype.streamError = function (e)
  {
    if(typeof e!='undefined')
    {
      let error = '';
      console.log(e['name']);
      switch (e['name'])
      {
        case 'PermissionDeniedError':
        case 'NotAllowedError':
        case 'SecurityError':
          error = 'Для съемки необходим доступ к вебкамере';
          break;
        case 'DevicesNotFoundError':
        case 'NotFoundError':
          error = 'Камера не найдена';
          break;
        case 'ConstraintNotSatisfiedError':
          error = 'Решение не поддерживается вашим устройством';
          break;
        case 'SourceUnavailableError':
          error = 'Камера используется другой программой';
          break;
      }
      $('.snapshot-error').remove();

      if(error.length)
      {
        $('.snapshot-block').append('<p class="separator center snapshot-error"><span class="login__error">' + error + '</span></p>')
      }
    }
    $('.YiiUpload__camera').hide();
    MainScript.stateLoading(false);
  },
  // добавление кнопки "Сделать снимок"
  UploadAvatar.prototype.checkSnapshot = function ()
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
      $('.upload-block').after('<p class="snapshot-block">'
        + '<span class="prmu-btn prmu-btn_normal btn-snapshot">Сделать снимок</span></p>');
      $('.btn-snapshot').click(function(){
        MainScript.stateLoading(true);
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
  UploadAvatar.prototype.createPopup = function ()
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
      $('#avatar_form .input-upload').val('');
      MainScript.stateLoading(false);
    }
  },
  //
  UploadAvatar.prototype.delPhoto = function ()
  {
    let self = this,
      image = $('.YiiUpload__editor-field>img'),
      data = {};

    if(image.length)
    {
      data.delfile = image[0].dataset.name;
      $.ajax({
        url: '/ajax/RegisterAvatar',
        type: 'POST',
        data: {data: JSON.stringify(data)}
      });
    }
    self.createPopup(false,false);
  }
  //
  return UploadAvatar;
}());
/*
*
*/
$(document).ready(function () {
  new UploadAvatar();
});