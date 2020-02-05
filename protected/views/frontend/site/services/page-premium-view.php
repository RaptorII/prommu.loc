<?
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'services/services-premium-page.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'services/services-premium-page.js', CClientScript::POS_END);
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
                                <span class="ps-vacancies__item-about"><strong>Завершение:</strong><?=' '.$val['remdate'] ?></span>
                                <span class="ps-vacancies__item-about"><strong>Город:</strong><?=' '.$val['name'] ?></span>
                                <span class="ps-vacancies__item-about"><strong>Сайт:</strong>
                                    <?php
                                    if ($val['id_city']=='1307')//moskov
                                    { echo ' '.'prommu.com'; }
                                    else
                                    { echo ' ' . $val['seo_url'] . '.prommu.com'; }
                                    ?>
                                </span>

                            </div>
                            <input type="checkbox" name="vacancy[]" value="<?=$val['id']?>" class="ps-vacancies__item-input">
                            <input type="checkbox"
                                   name="vacancy_city[]"
                                   value="<?=$val['id_city']?>"
                                   class="ps-vacancies__item-input-city">
                        </label>
                    <?php endforeach; ?>
                </div>
				<input type="hidden" name="service" value="premium-vacancy">
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