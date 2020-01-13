<?
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'services/services-premium-page.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'services/services-premium-page.js', CClientScript::POS_END);
?>
<div class="row">
	<div class="col-xs-12 premium-service">
		<?php if( $viData['vacs'] ): ?>
			<h2 class="premium-service__title">КАКИЕ ВАКАНСИИ НЕОБХОДИМО ПОДНЯТЬ В ВЫДАЧЕ?</h2>
			<p>
                Эта услуга поможет найти дополнительно подходящих соискателей на Вашу вакансию,
                так как поднимет её вверх и отобразит, как новую.
            </p>
           <br>
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
						<label class="ps-vacancies__item ps-vacancies__up">
							<div class="ps-vacancies__item-bg">
								<span class="ps-vacancies__item-title"><?=$val['title'] ?></span>
							</div>
							<input type="checkbox" name="vacancy[]" value="<?=$val['id']?>" class="ps-vacancies__item-input">
						</label>		
					<?php endforeach; ?>
				</div>
				<input type="hidden" name="service" value="podnyatie-vacansyi-vverh">
				<br>
				<button class="service__btn pull-right prmu-btn prmu-btn_normal"><span>ОПЛАТИТЬ</span></button>
			</form>
		<?php else: ?>
			<br>
			<h2 class="premium-service__title center">У ВАС НЕТ АКТИВНЫХ ВАКАНСИЙ</h2>
			<a href="<?=MainConfig::$PAGE_VACPUB?>" class="service__btn visible prmu-btn prmu-btn_normal"><span>ДОБАВИТЬ ВАКАНСИЮ</span></a>
		<?php endif; ?>
	</div>
</div>