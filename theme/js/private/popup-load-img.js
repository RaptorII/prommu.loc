$(function(){
	var dataBrowser = [
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
	var dataOS = [ 
		{ string: navigator.platform, subString: "Win", identity: "Windows" }, 
		{ string: navigator.platform, subString: "Mac", identity: "Mac" }, 
		{ string: navigator.userAgent, subString: "iPhone", identity: "iPhone/iPod" }, 
		{ string: navigator.platform, subString: "Linux", identity: "Linux" } 
	];

	$('body').append($('.body__content').html());
	$('.body__content').remove();

	var $veil = $('body').find('.load-img__bg-veil'),
		$popup = $('body').find('.load-img__form'),
		$form = $popup.find('form'),
		$mess = $popup.find('.load-img__mess'),
		$errBtn = $popup.find('.load-img__err-btn'),
		$load = $popup.find('.load-img__load'),
		$crop = $popup.find('.load-img__crop'),
		$cropImg = $popup.find('.load-img__crop-img'),
		$cropWrap = $popup.find('.load-img__crop-cont'),
		$cropBtn = $popup.find('.save-crop'),
		$closeBtn = $popup.find('.load-img__close'),
		$snapshot = $popup.find('.load-img__snapshot'),
		$snapBtn = $popup.find('.load-img__shot-btn'),
		$snapBtnsBlock = $popup.find('.load-img__shot-btns'),
		$snapBtnDone = $popup.find('.load-img__shot-done'),
		$snapBtnReset = $popup.find('.load-img__shot-rst'),
		$newImg = $popup.find('.load-img__shot-res'),
		$file = $('#input-load-img'),
		cntImages = 0;

	var cropOptions = {};
	var cropperObj = null;
	var cropParams = {
			aspectRatio: 1/1,
			viewMode: 1,
			zoomable: true,
			rotatable: true,
			background: false,
			guides: false,
			highlight: false,
			minCropBoxWidth: 200,
			minCropBoxHeight: 200,
			preview: '.load-img__prev',
			crop: function(e){ cropOptions=e.detail }
		};

	navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
	// прячем кнопку для снимка, если браузер не поддерживает
	if(typeof navigator.getUserMedia==='undefined')
		$('#load-img-module').addClass('single');
	// загружаем файл
	$('#btn-load-image').click(function(){
		$file.val('');
		$file.trigger('click');
	});
	//	скрываем попап
	$veil.click(hideLoadImg);
	$closeBtn.click(hideLoadImg);
	$errBtn.click(hideLoadImg);
	// делаем снимок
	$('#btn-get-snapshot').click(function(){
		showLoadImg();
		if(cntImages==10)
			showErrorMess('В профиль возможно загружать не более 10 файлов');
		else{
			$mess.text('В верхней части появится запрос на использование вебкамеры');
			$load.fadeIn();
			navigator.getUserMedia({ audio:false, video:true }, getStream, streamError);			
		}
	});
	//
	$snapBtn.click(function(){
		var hidden_canvas = document.querySelector('canvas'),
		video = document.querySelector('video'),
		width = video.videoWidth,
		height = video.videoHeight,           
		context = hidden_canvas.getContext('2d');// Объект для работы с canvas.
		// Установка размеров canvas идентичных с video.
		hidden_canvas.width = width;
		hidden_canvas.height = height;
		// Отрисовка текущего кадра с video в canvas.
		context.drawImage(video, 0, 0, width, height);
		// Преобразование кадра в изображение dataURL.
		var imageDataURL = hidden_canvas.toDataURL('image/png');
		if(imageDataURL.length>6){
			$newImg.attr('src',imageDataURL);
			$newImg.show();
			$snapBtnsBlock.fadeIn();
		}
	});
	//
	$snapBtnReset.click(function(){
		$newImg.attr('src','');
		$newImg.hide();
		$snapBtnsBlock.fadeOut();
	});
	//
	$snapBtnDone.click(function(e){
		$snapshot.fadeOut();
		$load.fadeIn();

		$.ajax({
			url: '/ajax/postlogosnapshot/',
			type: 'POST',
			data: 'data=' + $newImg.attr('src'),
			success: function(d){ sendOriginal(d) },
			error: function(){ err='Ошибка загрузки файла, обновите страницу и попробуйте еще раз' },
		}).always(function(){ $load.fadeOut() });
	});	
	// отправляем файл
	$file.change(function(){
		var f = this.files[0],
			err = false;

		showLoadImg();

		if(f){
			if(f.type!=="image/jpeg" && f.type!="image/png")
				err = 'Тип файла для загрузки должен быть JPG или PNG';
			if(f.size > 5242880)
				err = 'Загружаемое фото не должно превышать размер 5 Мб';
			if(cntImages==10)
				err = 'В профиль возможно загружать не более 10 файлов';
		}
		if(!err){
			var formData = new FormData($form[0]);

			$.ajax({
				url: MainConfig.AJAX_POST_POSTLOGOFILE,
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(d){ sendOriginal(d) },
				error: function(){ err='Ошибка загрузки файла, обновите страницу и попробуйте еще раз' },
			}, 'json')
			.always(function(){ $load.fadeOut() });
		}
		if(err)
			showErrorMess(err);
	});
	// отправляем измененный файл
	$cropBtn.click(function(){
		$crop.fadeOut();
		$load.fadeIn();
		$mess.text('');
		$.post(MainConfig.AJAX_POST_CROPLOGO, cropOptions, function(d){
			d = JSON.parse(d);
			if(d.error)
				showErrorMess(d.message);
			else if(d.length==0)
				showErrorMess('В профиль возможно загружать не более 10 файлов');
			else{
				$("#HiLogo").val(d.idfile);
				cntImages++;
				hideLoadImg();
			}
		})
		.always(function(){ $load.fadeOut() });
	});
	//
	//	functions
	//
	function hideLoadImg(){
		$veil.fadeOut();
		$popup.fadeOut();
		$load.fadeIn();
		$cropWrap.empty();
		if(cropperObj) cropperObj.destroy();
		$crop.fadeOut();
		setTimeout(function(){ $mess.text('') }, 500);
		$errBtn.fadeOut();
		$snapshot.fadeOut();
		$newImg.attr('src','');
		$newImg.hide();
		$snapBtnsBlock.fadeOut();
	};
	//
	function showLoadImg(){
		$veil.fadeIn();
		$popup.fadeIn();
		$load.fadeIn();
	};
	//
	function showErrorMess(m){
		$load.fadeOut();
		$mess.text(m);
		$errBtn.fadeIn();
	};
	//
	function sendOriginal(d){
		d = JSON.parse(d);
		if(d.error)
			showErrorMess(d.message);
		else{
			var img = $cropImg.clone();
			$cropWrap.prepend(img);
			$mess.text('Выберите область для отображения');
			img.attr('src', d.file).load(function(){
				cropperObj = new Cropper(img[0], cropParams);
				$crop.fadeIn();
				$('.cropper__rotate-right').click(function(){ cropperObj.rotate(90) });
				$('.cropper__rotate-left').click(function(){ cropperObj.rotate(-90) });
			});
		}
	};
	// get stream from webcam
	function getStream(stream){ 
		var video = document.querySelector('video');

		if(searchString(dataBrowser)=='Safari'){
			video.srcObject = stream;
			video.play();
		}
		else{ video.src=URL.createObjectURL(stream) }
		setTimeout(function(){
			$mess.text('');
			$load.fadeOut();
			$snapshot.fadeIn();
			$snapBtn.show();
		},500);
	};
	//	show errrors by navigator
	function streamError(e){
		console.log(e);
		if(typeof e!='underfined'){
			if(e['name']==='PermissionDeniedError' || e['name']==='NotAllowedError')
				showErrorMess('Для съемки необходим доступ к вебкамере');
			if(e['name']==='DevicesNotFoundError')
				showErrorMess('Камера не найдена');
			if(e['name']==='ConstraintNotSatisfiedError')
				showErrorMess('Решение не поддерживается вашим устройством');
		}
	};
	// get browser name
	function searchString(data){
		for (var i=0;i<data.length;i++) { 
			var dataString = data[i].string; 
			var dataProp = data[i].prop; 
			if (dataString){ 
				if (dataString.indexOf(data[i].subString) != -1) 
					return data[i].identity;
			} 
			else if (dataProp) 
				return data[i].identity; 
		} 
	};
});