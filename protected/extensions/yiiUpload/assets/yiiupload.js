'use strict'
var YiiUpload = (function () {
	YiiUpload.prototype.params = [];
	YiiUpload.prototype.block;
	YiiUpload.prototype.errors;
	YiiUpload.prototype.result;
	YiiUpload.prototype.files;
	YiiUpload.prototype.inputs;
	YiiUpload.prototype.btns;
	YiiUpload.prototype.editor;
	YiiUpload.prototype.camera;
	YiiUpload.prototype.cropperCnt;
	YiiUpload.prototype.objCropper;
	YiiUpload.prototype.cropOptions;
	YiiUpload.prototype.cropParams;
	YiiUpload.prototype.bComplete;
	YiiUpload.prototype.snapshots = [];

	function YiiUpload()
	{
		this.init(arguments[0]);
	}
	//
	YiiUpload.prototype.init = function ()
	{
		let self = this,
				bWebCam = arguments[0].useWebcam;

		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
		window.URL.createObjectURL = window.URL.createObjectURL || window.URL.webkitCreateObjectURL || window.URL.mozCreateObjectURL || window.URL.msCreateObjectURL;
		if(typeof navigator.getUserMedia==='undefined')
		{
			bWebCam = false;
		}

		self.params = arguments[0];
		self.cropperCnt = 0;
		self.cropOptions = [];
		self.cropParams = {
				aspectRatio: 1/1,
				viewMode: 1,
				zoomable: true,
				rotatable: true,
				background: false,
				guides: false,
				highlight: false,
				minCropBoxWidth: 200,
				minCropBoxHeight: 200,
				preview: '.YiiUpload__editor-prev-item',
				crop: function(e){ self.changeCropField(e.detail) }
			};
		self.bComplete = false;

		$('.YiiUpload__call-btn').on('click',function(){
			$('body').append('<div class="YiiUpload__block">'
				+ '<div class="YiiUpload__close"><div class="YiiUpload__close">'
				+ '<form class="YiiUpload__form">'
					+ '<div class="YiiUpload__form-close YiiUpload__close"></div>'
					+ '<div class="YiiUpload__form-content">'
						+ '<div class="YiiUpload__form-title"></div>'
						+ '<div class="YiiUpload__form-body">'
							+ '<div class="YiiUpload__form-errors"></div>'
							+ '<div class="YiiUpload__form-result"></div>'
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
								+	'</div>'
							+ '</div>'
							+ '<div class="YiiUpload__camera">'
								+ '<video autoplay playsinline></video>'
								+ '<canvas></canvas>'
								+ '<img src="">'
							+ '</div>'
							+ '<div class="YiiUpload__form-files"></div>'
							+ '<div class="YiiUpload__form-inputs"></div>'
							+ '<div class="YiiUpload__form-btns"></div>'
						+ '</div>'
					+ '</div>'
				+ '</form>'
			+ '</div></div></div>');

			
			self.block = document.querySelector('.YiiUpload__block');
			self.errors = document.querySelector('.YiiUpload__form-errors');
			self.result = document.querySelector('.YiiUpload__form-result');
			self.files = document.querySelector('.YiiUpload__form-files');
			self.inputs = document.querySelector('.YiiUpload__form-inputs');
			self.btns = document.querySelector('.YiiUpload__form-btns');
			self.editor = document.querySelector('.YiiUpload__editor');
			self.camera = document.querySelector('.YiiUpload__camera');
			self.cropperCnt = 0;
			self.setTitle();
			self.addInput();
			self.addButtons(bWebCam?['open','snapshot']:['open']);
			$(self.block).fadeIn();
			$('body').css({overflow:'hidden'});
		});
		//
		// event popup
		//
		$('body').on('click','.YiiUpload__block',function(e){
			//
			//
			if($(e.target).hasClass('YiiUpload__close')) // close popup
			{
				let result = true;
				if(!self.bComplete)
					result = confirm('Данные не сохранятся. Вы уверены?');

				if(result)
				{
					$(self.block).fadeOut();
					setTimeout(function(){ $(self.block).remove(); },500);
					$('body').css({overflow:'inherit'});
				}
			}
			//
			//
			if($(e.target).hasClass('YiiUpload__delete')) // remove file
			{
				let fileName = $(e.target).closest('div')[0],
						cnt = $('.YiiUpload__form-files div').index(fileName),
						input = $('.YiiUpload__form-input:eq('+cnt+')');

				$(fileName).remove();
				if((cnt+1)==($('.YiiUpload__form-input').length)) // последний инпут должен существовать всегда
				{
					$(input).val('');
				}
				else // иначе удаляем
				{
					$(input).remove();
				}
				//
				// проверяем на допустимое кол-во загружаемых файлов
				if($('.YiiUpload__form-input').length==1)
				{
					self.addButtons(bWebCam?['open','snapshot']:['open']);
				}
				else if(self.params.fileLimit>1 && $('.YiiUpload__form-input').length<=self.params.fileLimit)
				{
					self.addButtons(bWebCam?['open','snapshot','send']:['open','send']);
				}
				else // лимит по файлам достигнут
				{
					self.addButtons(['send']);
				}
			}
			//
			//
			if($(e.target).hasClass('YiiUpload__snapshot')) // start snapshot
			{
				$(self.block).addClass('loading');
				navigator.getUserMedia(
						{ audio:false, video:true }, 
						function(e){ self.getStream(e) },
						function(e){ self.streamError(e) }
					);
			}
			//
			//
			if($(e.target).hasClass('YiiUpload__wc_shoot')) // get snapshot
			{
				let canvas = document.querySelector('.YiiUpload__camera canvas'),
						video = document.querySelector('.YiiUpload__camera video'),
						width = video.videoWidth,
						height = video.videoHeight,           
						context = canvas.getContext('2d'); // Объект для работы с canvas

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
					self.addButtons(['wc_done','wc_reset']);
				}


			}
			//
			//
			if($(e.target).hasClass('YiiUpload__wc_done')) // end snapshot
			{
				let image = $('.YiiUpload__camera img'),
						src = $(image).attr('src'),
						arr = src.split(','),
						mime = arr[0].match(/:(.*?);/)[1],
						bstr = atob(arr[1]),
						n = bstr.length,
						u8arr = new Uint8Array(n),
						name = 'Снимок-с-камеры_' + (self.snapshots.length+1) + '.png',
						file;

				while(n--)
				{ u8arr[n] = bstr.charCodeAt(n); };
				file = new File([u8arr], name, {type:mime});
				self.snapshots.push(file);

				$(image).hide();
				$(self.camera).hide();
				$(self.files).fadeIn();
				self.addInput();
				$(self.files).append('<div><span>' 
					+ name + '</span><i class="YiiUpload__delete"></i></div>');

				if($('.YiiUpload__form-input').length==1)
				{
					self.addButtons(bWebCam?['open','snapshot']:['open']);
				}
				else if(self.params.fileLimit>1 && $('.YiiUpload__form-input').length<=self.params.fileLimit)
				{
					self.addButtons(bWebCam?['open','snapshot','send']:['open','send']);
				}
				else // лимит по файлам достигнут
				{
					self.addButtons(['send']);
				}
			}
			//
			//
			if($(e.target).hasClass('YiiUpload__wc_reset')) // reset snapshot
			{
				$('.YiiUpload__camera img').hide();
				self.addButtons(['wc_shoot']);
			}
		})
		.on('click','.YiiUpload__open',function(e){ // select file
			$('.YiiUpload__form-input').last().click();
		})
		.on('change','.YiiUpload__form-input',function(e){ // change file
			let input = this,
					arName = $(input).val().match(/\\([^\\]+)\.([^\.]+)$/),
					arInput = $('.YiiUpload__form-input'),
					f = input.files[0],
					bUnique = true;

			self.setError();
			if(arName!=null && arName[1].length && arName[2].length)
			{
				let name = arName[1] + '.' + arName[2],
						format = arName[2].toLowerCase();

				$.each(arInput, function(){
					let tf = this.files[0];
					if(
						!$(input).is(this) && 
						tf.name===f.name && 
						tf.size==f.size && 
						tf.type===f.type
					)
						bUnique = false;
				});
				if(!bUnique) // файл уже выбран в другом инпуте
				{
					self.setError('- этот файл уже выбран');
					$(this).val('');
					return;
				}
				if($.inArray(format,self.params.fileFormat)<0) // проверяем формат на корректность
				{
					self.setError("- у файла '" + name + "' некорректный формат");
					$(this).val('');
					return;
				}
				$(self.files).append('<div><span>' 
					+ name + '</span><i class="YiiUpload__delete"></i></div>');
				// проверяем на допустимое кол-во загружаемых файлов
				if(self.params.fileLimit>1 && $('.YiiUpload__form-input').length<self.params.fileLimit)
				{
					self.addInput();
					self.addButtons(['open','send']);
				}
				else // лимит по файлам достигнут
				{
					self.addButtons(['send']);
				}
			}
			else // недопустимое название файла
			{
				self.setError('- некорректный файл');
				$('.YiiUpload__form-input').last().val('');
			}
		})
		.on('click','.YiiUpload__send',function(e){ // send file
			let form = $('.YiiUpload__form')[0],
					formData = new FormData(form);

			$(self.block).addClass('loading');

			$.ajax({
				url: self.params.action,
				data: formData,
				type: 'POST',
				contentType: false,
				processData: false,
				success: function(r)
				{
					r = JSON.parse(r);
					if(r.error.length) // если есть ошибки
					{
						self.setError($.parseHTML(r.error.join('</br>')));
					}
					if(r.error.length==r.items.length) // ошибки на всех файлах
					{
						$(self.files).html('');
						$(self.inputs).html('');
						self.addInput();
						self.addButtons(['open']);
					}
					else // есть успешно загруженные файлы
					{
						if(self.params.imageEditor==true) // редактор изображений
						{
							let imgCnt = 0, arImg = [];
							$.each(r.success, function(){
								if(this.isImg==true)
								{
									imgCnt++;
									arImg.push(this.imgTag);
								}
							});

							if(!imgCnt) // если изображений нет
							{
								self.setError('- нет изображений для редактирования');
								self.addButtons(['close']);
								self.bComplete = true;
							}
							else // если все же есть
							{
								$('.YiiUpload__editor-field').append(arImg);
								self.setCropper();
							}
							$(self.files).html('');
							$(self.result).html('');
							$(self.inputs).html('');
							self.addButtons();
							self.setTitle('Выберите область для отображения');
							self.bComplete = false;
						}
						else // завершаем обработку
						{
							self.setSuccess(r.success);
						}
					}
					$(self.block).removeClass('loading');
				},
				error: function() // если вернуло статус!=200
				{
					self.setError('- системная ошибка. Обратитесь к администратору');
					$(self.files).html('');
					$(self.inputs).html('');
					self.addButtons(['close']);
					$(self.block).removeClass('loading');
					self.bComplete = true;
				}
			});
		})
		.on('click','.YiiUpload__crop',function(e){ // send file
			self.cropperCnt++;
			self.setCropper();
		});
	};
	//
	// croper edit step
	YiiUpload.prototype.setCropper = function ()
	{
		let self = this,
				arImages = $('.YiiUpload__editor-field>img');

		if(self.cropperCnt==arImages.length)
		{
			$(self.block).addClass('loading');
			$.each(arImages,function(i,e){
				self.cropOptions[i]['name'] = $(this).data('name');	
				self.cropOptions[i]['oldName'] = $(this).attr('alt');	
			});
			$.ajax({
				url: self.params.action,
				data: {state:'edit', data:self.cropOptions},
				type: 'POST',
				success: function(r)
				{
					r = JSON.parse(r);
					self.setError();
					self.setSuccess(r.success);
					$(self.block).removeClass('loading');
				},
				error: function() // если вернуло статус!=200
				{
					self.setError('- системная ошибка. Обратитесь к администратору');
					$(self.files).html('');
					$(self.inputs).html('');
					self.addButtons(['close']);
					$(self.block).removeClass('loading');
					self.bComplete = true;
				}
			});
			return;
		}

		$.each(arImages,function(i,e){
			i==self.cropperCnt ? $(this).show() : $(this).hide();
		});

		if(self.objCropper)
		{
			$('body').off('click','.YiiUpload__editor_r-rotate');
			$('body').off('click','.YiiUpload__editor_l-rotate');
			self.objCropper.destroy();
		}

		self.objCropper = new Cropper(arImages[self.cropperCnt], self.cropParams);

		$('body').on('click','.YiiUpload__editor_r-rotate',function(){
			self.objCropper.rotate(90)
		});
		$('body').on('click','.YiiUpload__editor_l-rotate',function(){ 
			self.objCropper.rotate(-90)
		});
		$('.YiiUpload__editor').fadeIn();
	}
	//
	//
	YiiUpload.prototype.setSuccess = function ()
	{
		let self = this,
				str = arguments[0].length>1
					? 'Успешно загруженныe файлы:'
					: 'Успешно загруженный файл:',
				result = '<p class="YiiUpload__green">'+str+'</p>';

		$.each(arguments[0], function(){
			result += '<p>' + this.oldname + '</p>';
			if(self.params.showTags==true) // если надо вывести теги
			{
				result += '<label><span>Ссылка</span>'
							+ '<input type="text" name="link" disabled/>'
						+ '</label>'
						+ '<label><span>HTML ссылка</span>'
							+ '<input type="text" name="html_link" disabled/>'
						+ '</label>';
				$(self.result).append(result);
				result = '';

				$(self.result).find('input:eq(-2)').val(this.path);
				$(self.result).find('input:eq(-1)').val(this.linkTag);

				if(this.isImg==true) // если файл - картинка
				{
					result += '<label><span>HTML картинка</span>'
							+ '<input type="text" name="html_img" disabled/>'
						+ '</label>';
					$(self.result).append(result);
					result = '';
					$(self.result).find('input:eq(-1)').val(this.imgTag);	
				}
			}
		});
		$(self.result).append(result);
		
		$(self.files).html('');
		$(self.inputs).html('');
		$(self.editor).html('');
		self.setTitle();
		self.addButtons(['close']);
		self.bComplete = true;
	}
	//
	//
	YiiUpload.prototype.changeCropField = function ()
	{
		this.cropOptions[this.cropperCnt]=arguments[0];
	}
	//
	// set errors
	YiiUpload.prototype.setError = function ()
	{
		$(this.errors).html(arguments[0]!=null ? arguments[0] : '');
	}
	//
	// set buttons
	YiiUpload.prototype.addButtons = function ()
	{
		let self = this,
				objBtns = {
					open:'Выбрать файл', 
					send:'Отправить', 
					close:'Хорошо',
					snapshot:'Сделать снимок',
					wc_done:'Сохранить',
					wc_reset:'Заново',
					wc_shoot:'Сделать снимок'
				};

		$(self.btns).html('');
		if(typeof arguments[0]!=='object')
		{
			return;
		}
		$.each(arguments[0], function(){
			$(self.btns).append('<div class="YiiUpload__form-btn YiiUpload__' 
				+ this + '">' + objBtns[this] + '</div>');
		});
	}
	//
	// add inputs
	YiiUpload.prototype.addInput = function ()
	{
		let self = this;
		$(self.inputs).append('<input type="file" name="upload[]" class="YiiUpload__form-input">');
	}
	//
	// set popup title
	YiiUpload.prototype.setTitle = function ()
	{
		let self = this,
				result = arguments[0];

		if(typeof result!=='string')
		{
			result = 'Загурзка файлов' + (self.params.fileFormat.length 
				? ' (' + self.params.fileFormat.join(', ') + ')' : '');
		}
		$('.YiiUpload__form-title').text(result);
	}
	//
	// get stream from webcam
	YiiUpload.prototype.getStream = function (stream)
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
			$(self.block).removeClass('loading');
			$(self.camera).fadeIn();
			$(self.files).hide();
			self.addButtons(['wc_shoot']);
		},500);
	};
	//
	//	show errrors by navigator
	YiiUpload.prototype.streamError = function (e)
	{
		let self = this;
		
		if(typeof e!='underfined')
		{
			console.log(e['name']);
			if(e['name']==='PermissionDeniedError' || e['name']==='NotAllowedError')
			{
				self.setError('- для съемки необходим доступ к вебкамере');
			}
			if(e['name']==='DevicesNotFoundError')
			{
				self.setError('- камера не найдена');
			}
			if(e['name']==='ConstraintNotSatisfiedError')
			{
				self.setError('- решение не поддерживается вашим устройством');
			}
		}
		$(self.camera).hide();
		$(self.files).fadeIn();
		$(self.block).removeClass('loading');
	};

	return YiiUpload;
}());