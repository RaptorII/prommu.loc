jQuery(document).ready(function($){
	//
	//	анимация активности меню
	//
	$("#menu").on("click","a.cd-faq-trigger", function (event) {
		if($(this).data('state')=='normal'){
			location.href = $(this).attr('href');
		}else{
			event.preventDefault();
			var id  = $(this).attr('href');
			if($('*').is(id)){
				$('body,html').animate({scrollTop: $(id).offset().top}, 700);
			}
		}
	});
	$(document).mouseup(function (e){ // событие клика по веб-документу
		var arBtnBlock = $(".cd-faq-content"); // тут указываем ID элемента
		if (!arBtnBlock.is(e.target) // если клик был не по нашему блоку
			&& arBtnBlock.has(e.target).length === 0) { // и не по его дочерним элементам
			arBtnBlock.hide(); // скрываем его
		}
	});
	//
	//
	// управляем позицией меню
	scrollMenu();
	$(window).on('resize scroll',scrollMenu); 
	//
	//
	// функция для скрола меню
	function scrollMenu(){
	  var $menu = $('#menu'),
	      hMenu = $menu.height(),
	      topIndent = 20, // отступ меню от верхнего края экрана

	      $content =  $("#DiContent .content-block"),
	      posContent = $content.offset().top - topIndent, 
	      hContent = $content.height(),

	      hService = $('.service-content').height();
	      scrollPos = $(document).scrollTop(),
	      wDisplay = $(window).width();
	      

	  $menu.css({ width:$('.service-menu').width() });
	  if(scrollPos>posContent && hService>hMenu && wDisplay>768){
	    $menu.addClass('fixed');
	    if((scrollPos + hMenu)<(posContent + hContent)){  
	      // меняем позицию, когда в области контента
	      $menu.css({top:topIndent+'px'});
	    }
	    else{
	        // меняем позицию, чтоб не налезт на футер
	        $menu.css({top:((posContent + hContent) - (scrollPos + hMenu))});
	    }
	  }
	  else{
	    // меняем позицию, чтоб не налез на хедер
	    $menu.removeClass('fixed');
	    // $menu.css({top:(posContent - scrollPos + topIndent)});
	  }
	}
});