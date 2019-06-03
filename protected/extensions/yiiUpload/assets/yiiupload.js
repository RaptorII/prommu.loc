'use strict'
var YiiUpload = (function () {
	YiiUpload.prototype.params = [];
	YiiUpload.prototype.block;
	YiiUpload.prototype.errors;
	YiiUpload.prototype.result;
	YiiUpload.prototype.files;
	YiiUpload.prototype.inputs;
	YiiUpload.prototype.btns;

	function YiiUpload()
	{
		this.init(arguments[0]);
	}
	//
	YiiUpload.prototype.init = function ()
	{
		let self = this,
				cropperObj,
				cropOptions = {},
				cropParams = {
						aspectRatio: 1/1,
						viewMode: 1,
						zoomable: true,
						rotatable: true,
						background: false,
						guides: false,
						highlight: false,
						minCropBoxWidth: 200,
						minCropBoxHeight: 200,
						preview: '.YiiUpload__editor-prev',
						crop: function(e){ cropOptions=e.detail }
					};

		self.params = arguments[0];

		$('.YiiUpload__call-btn').on('click',function(){
			$('body').append('<div class="YiiUpload__block">'
				+ '<div class="YiiUpload__close"><div class="YiiUpload__close">'
				+ '<form class="YiiUpload__form">'
					+ '<div class="YiiUpload__form-close YiiUpload__close"></div>'
					+ '<div class="YiiUpload__form-content">'
						+ '<div class="YiiUpload__form-title">Загурзка файлов' 
							+ (self.params.fileFormat.length 
								? ' (' + self.params.fileFormat.join(', ') 
								+ ')' : '') + '</div>'
						+ '<div class="YiiUpload__form-body">'
							+ '<div class="YiiUpload__form-errors"></div>'
							+ '<div class="YiiUpload__form-result"></div>'
							+ '<div class="YiiUpload__editor">'
								+ '<div class="YiiUpload__editor-field"></div>'
								+ '<div class="YiiUpload__editor-prev"></div>'
								+ '<div class="YiiUpload__editor-panel">'
									+ '<div class="YiiUpload__editor_l-rotate" title="Повернуть на 90 градусов влево"></div>'
									+ '<div class="YiiUpload__editor-r-rotate" title="Повернуть на 90 градусов вправо"></div>'
									+ '<div class="YiiUpload__editor-success" title="Сохранить"></div>'
								+	'</div>'
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
			self.addInput();
			self.addButtons(['open']);

			$(self.block).fadeIn();
			$('body').css({overflow:'hidden'});
		});
		// event popup
		$('body').on('click','.YiiUpload__block',function(e){
			if($(e.target).hasClass('YiiUpload__close')) // close popup
			{
				$(self.block).fadeOut();
				setTimeout(function(){ $(self.block).remove(); },500);
				$('body').css({overflow:'inherit'});
			}
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
					self.addButtons(['open']);
				}
				else if(self.params.fileLimit>1 && $('.YiiUpload__form-input').length<=self.params.fileLimit)
				{
					self.addButtons(['open','send']);
				}
				else // лимит по файлам достигнут
				{
					self.addButtons(['send']);
				}
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
				let name = arName[1] + '.' + arName[2];
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
				if($.inArray(arName[2],self.params.fileFormat)<0) // проверяем формат на корректность
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
						self.params.onError('event_send','load-error');
					}
					if(r.error.length==r.items.length) // ошибки на всех файлах
					{
						$(self.files).html('');
						$(self.inputs).html('');
						self.addInput();
						self.addButtons(['open','send']);
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
							}
							else // если все же есть
							{
								$('.YiiUpload__editor-field').append(arImg);
								arImg = $('.YiiUpload__editor-field img');
								$.each(arImg,function(i,e){
									i>0 && $(this).css({display:'none'});
								});



								$(arImg[0]).load(function(){
									cropperObj = new Cropper(arImg[0], cropParams);
									$('.YiiUpload__editor_r-rotate').click(function(){ cropperObj.rotate(90) });
									$('.YiiUpload__editor_l-rotate').click(function(){ cropperObj.rotate(-90) });
								});


								//cropperObj = new Cropper(arImg[0], cropParams);
								$('.YiiUpload__editor').show();
							}
							$(self.files).html('');
							$(self.result).html('');
							$(self.inputs).html('');
							self.addButtons();
							$('.YiiUpload__form-title').text('Выберите область для отображения');	
						}
						else // завершаем обработку
						{
							let str = r.success.length>1
										? 'Успешно загруженныe файлы:'
										: 'Успешно загруженный файл:',
									result = '<p class="YiiUpload__green">'+str+'</p>';

							$.each(r.success, function(){
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
								$(self.result).append(result);
							});
							
							$(self.files).html('');
							$(self.inputs).html('');
							self.addButtons(['close']);
						}
						console.log(r);
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
				}
			});
		});
	};
	// set errors
	YiiUpload.prototype.setError = function ()
	{
		$(this.errors).html(arguments[0]!=null ? arguments[0] : '');
	}
	// set buttons
	YiiUpload.prototype.addButtons = function ()
	{
		let self = this,
				objBtns = {open:'Выбрать файл', send:'Отправить', close:'Хорошо'};

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
	// add inputs
	YiiUpload.prototype.addInput = function ()
	{
		let self = this;
		$(self.inputs).append('<input type="file" name="upload[]" class="YiiUpload__form-input">');
	}
	//
	return YiiUpload;
}());