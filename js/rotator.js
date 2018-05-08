		$(document).ready(function(){
		
			// получаем массив блоков с нужными нам изображениями
			var banners = $("#rotator div").toArray();
			
			// класс с параметрами
			settings = function() {
				this.banners = banners;              // массив блоков с изображениями
				this.sum	 = this.banners.length;  // количество блоков с изображениями
				this.timeIn  = 1000;                 // время для появления
				this.timeOut = 1000;                 // время для скрытия
				this.timeView= 4000;                 // тайм-аут для показа
			}
			
			var obj = new settings();
			if (obj.sum < 1) {
				$("#rotator").html("<p>Изображения для показа не найдены!</p>");			
			} 
			else {
				
				// скрываем все изображения блока #rotator
				$("#rotator div").css({
					"display":"none"
				});
				
				// создаем блок для показа с индикатором загрузки для эффектного начала
				$("#rotator").prepend("<div id='rotator_view'><img src='/images/load.gif'></div>");
				
				// немного стилей (можно указать через CSS)
				$("#rotator_view").css({
					"height" : "125px"
				});
				$("#rotator_view img").css({
					"display" : "block",
					"margin" : "22.5px auto",
					"text-align" : "center"
				});
				
				// запускаем функцию показа
				view (0);
			}
			
			function view (num){
				// инициализируем экземпляр класса settings()
				var obj = new settings();
				// если показали все изображения, показываем их снова
				if (num >= obj.sum) num = 0;
				
				var interval = setInterval (function(){
					// очистка блока показа
					$("#rotator_view *").remove();
					
					// копия изображения в блок показа
					$(obj.banners[num]).clone().prependTo("#rotator_view");
					
					// показ изображения
					$("#rotator_view div").fadeIn(obj.timeIn);
					
					clearInterval(interval);
					num++;
				},obj.timeIn);
				
				// скрытие изображения
				$("#rotator_view div").fadeOut(obj.timeOut);
				
				// снова запускаем сами себя если изображений больше одного 
				if (obj.sum > 1) setTimeout(function(){view(num)},obj.timeIn+obj.timeOut+obj.timeView);
			}
		});	
