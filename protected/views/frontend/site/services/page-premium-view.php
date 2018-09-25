<?
	Yii::app()->getClientScript()->registerCssFile('/theme/css/services/services-premium-page.css');
?>
<div class="row">
	<div class="col-xs-12 premium-service">
		<?php if( $viData['vacs'] ): ?>
			<h2 class="premium-service__title">КАКИЕ ВАКАНСИИ НЕОБХОДИМО ВЫДЕЛИТЬ "ПРЕМИУМ" СТАТУСОМ?</h2>
			<p>Публикация вакансий со статусом «Премиум» предназначена для привлечения к ней большего внимания соискателей. Это достигается за счет ее размещения в ТОПовых позициях поиска. Премиум-вакансия представлена на главной странице сайта, а также вначале списка всех предложений о работе. Она выделяется специальной рамкой, что делает ее более заметной и повышает процент откликов от соискателей!</p><br>
			<form action="<?=MainConfig::$PAGE_PAYMENT?>" method="POST" id="premium-form">		
				<div class="premium-service__choose-all">
					<span class="ps-choose-all__name">Выбрать все вакансии</span>
					<div class="ps-choose-all__checkbox">
						<input id="choose-all" name="choose-all" value="1" type="checkbox">
						<label for="choose-all" class="ps-choose-all__checkbox-label">
							<div class="ps-choose-all__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
						</label>
					</div>
				</div>
				<div class="ps-vacancies__list">
					<?php foreach ($viData['vacs'] as $key => $val): ?>
						<label class="ps-vacancies__item">
							<div class="ps-vacancies__item-bg">
								<span class="ps-vacancies__item-title"><?=$val['title'] ?></span>
							</div>
							<input type="checkbox" name="vacancy[]" value="<?=$val['id']?>" class="ps-vacancies__item-input">
						</label>		
					<?php endforeach; ?>
				</div>
				<input type="hidden" name="service" value="premium-vacancy">
				<button class="premium-service__btn payment-button">ОПЛАТИТЬ</button>
			</form>
		<?php else: ?>
			<h2 class="premium-service__title">У ВАС НЕТ ВАКАНСИЙ</h2>
			<a href="<?=MainConfig::$PAGE_VACPUB?>" class="premium-service__btn visible">ДОБАВИТЬ ВАКАНСИЮ</a>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		var arInputs = $('.ps-vacancies__item-input'),
			arLabels = $('.ps-vacancies__item'),
			$button = $('.premium-service__btn'),
			$all = $('#choose-all'),
			bAllTrue = false,
			bAllFalse = false;

		arInputs.change(function(){
			var $this = $(this),
				$parent = $(this).parent('.ps-vacancies__item'),
			bAllTrue = false;
			bAllFalse = false;
			$this.is(':checked') ? $parent.addClass('active') : $parent.removeClass('active');	
			$.each(arInputs, function(){ $(this).is(':checked') ? bAllTrue=true : bAllFalse=true });
			!bAllFalse ? $all.prop('checked',true) : $all.prop('checked',false);
			bAllTrue ? $button.fadeIn() : $button.fadeOut();				
		});
		//
		//
		//
		$all.change(function(){
			if($(this).is(':checked')){
				$.each(arInputs, function(){ $(this).prop('checked', true) });
				$.each(arLabels, function(){ $(this).addClass('active') });
				bAllTrue = true;
				bAllFalse = false;
			}
			else{
				$.each(arInputs, function(){ $(this).prop('checked', false) });
				$.each(arLabels, function(){ $(this).removeClass('active') });
				bAllTrue = false;
				bAllFalse = true;
			}
			bAllTrue ? $button.fadeIn() : $button.fadeOut();
		});
	});
</script>