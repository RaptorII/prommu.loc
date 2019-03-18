<?
	$rq = Yii::app()->getRequest();
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'services/services-outstaffing-page.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'services/services-outstaffing-page.js', CClientScript::POS_END);
?>
<div class="row">
	<div class="col-xs-12 outstaffing-service">
		<?php if($rq->isPostRequest): ?>
			<?php if($rq->getParam('step')==2): ?>
				<?
					$id = Share::$UserProfile->exInfo->id;
					$sql = "SELECT s.val phone
						FROM user_attribs s
						WHERE s.id_us = {$id}
						AND s.id_attr = 1";
					$phone = Yii::app()->db->createCommand($sql)->queryScalar();
					$email = Share::$UserProfile->exInfo->email;
				?>
				<h2 class="outstaffing-service__title">ВЫБЕРИТЕ КОНТАКТНЫЕ ДАННЫЕ, ПО КОТОРЫМ С ВАМИ СВЯЖЕТСЯ НАШ МЕНЕДЖЕР</h2>
				<form action="/user/outstaffing" method="POST" id="outstaffing-form" class="outstaffing-contact-form">
					<input type="checkbox" name="phone" value="<?=$phone?>" id="os-phone" class="os__contact-input">
					<label for="os-phone" class="os__contact-label"><?=$phone?></label>
					<br>
					<input type="checkbox" name="email" value="<?=$email?>" id="os-email" class="os__contact-input">
					<label for="os-email" class="os__contact-label"><?=$email?></label>
					<br>
					<input type="checkbox" name="other" value="other" id="os-other" class="os__contact-input">
					<label for="os-other"  class="os__contact-label">Свой вариант для связи</label>
					<br>
					<textarea placeholder="Свой вариант для связи" name="other-contact" class="os__contact-textarea"></textarea>
					<?php foreach ($rq->getParam('vacancy') as $vacancy): ?>
						<input type="hidden" name="vacancy[]" value="<?=$vacancy?>">
					<?php endforeach ?>
					<input type="hidden" name="consultation" value="<?=$rq->getParam('consultation')?>">
					<input type="hidden" name="registration-rf" value="<?=$rq->getParam('registration-rf')?>">
					<input type="hidden" name="account" value="<?=Share::$UserProfile->id?>">
					<input type="hidden" name="registration-mr" value="<?=$rq->getParam('registration-mr')?>">
					<input type="hidden" name="service" value="outstaffing">
					<div class="center">
						<br>
						<button class="service__btn prmu-btn prmu-btn_normal">
							<span>СОХРАНИТЬ</span>
						</button>
					</div>
				</form>
			<?php else: ?>
				<h2 class="outstaffing-service__title">ВЫБЕРИТЕ УСЛУГИ АУТСТАФФИНГА</h2>
				<form action="" method="POST" id="outstaffing-form">
					<div class="outstaffing-service__choose-all">
						<span class="os-choose-all__name">Выбрать все услуги</span>
						<div class="os-choose-all__checkbox">
							<input id="choose-all-s" name="choose-all" value="1" type="checkbox">
							<label for="choose-all-s" class="os-choose-all__checkbox-label">
								<div class="os-choose-all__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
							</label>
						</div>
					</div>
					<div class="os-vacancies__list">
						<div class="os-services__item">
							<input type="checkbox" name="consultation" value="Консультация менеджера по услуге" id="outstaffing1">
							<label class="os-services__label" for="outstaffing1">
								<span class="os-services__circle">
									<span class="os-services__name">Консультация<br>менеджера по услуге</span>
									<span class="os-services__img ico1"></span>
								</span>
							</label>
						</div>
						<div class="os-services__item">
							<input type="checkbox" name="registration-rf" value="Оформление сотрудников-резидентов по всей РФ" id="outstaffing2">
							<label class="os-services__label" for="outstaffing2">
								<span class="os-services__circle">
									<span class="os-services__name">Оформление<br>сотрудников-резидентов<br>по всей РФ</span>
									<span class="os-services__img ico2"></span>
								</span>
							</label>
						</div>
						<div class="os-services__item">
							<input type="checkbox" name="registration-mr" value="Оформление сотрудников-нерезидентов в Москве и МО" id="outstaffing3">
							<label class="os-services__label" for="outstaffing3">
								<span class="os-services__circle">
									<span class="os-services__name">Оформление<br>сотрудников-нерезидентов<br>в Москве и МО</span>
									<span class="os-services__img ico3"></span>
								</span>
							</label>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php foreach ($rq->getParam('vacancy') as $vacancy): ?>
						<input type="hidden" name="vacancy[]" value="<?=$vacancy?>">
					<?php endforeach ?>
					<input type="hidden" name="step" value="2">
					<button class="service__btn prmu-btn prmu-btn_normal pull-right">
						<span>ЗАКАЗАТЬ</span>
					</button>
				</form>
			<?php endif; ?>
		<?php else: ?>
			<?php if( $viData['vacs'] ): ?>
				<h2 class="outstaffing-service__title">КАКИЕ ВАКАНСИИ НУЖДАЮТСЯ В УСЛУГАХ АУТСТАФФИНГА?</h2>
				<form action="" method="POST" id="outstaffing-form">
					<div class="outstaffing-service__choose-all">
						<span class="os-choose-all__name">Выбрать все вакансии</span>
						<div class="os-choose-all__checkbox">
							<input id="choose-all-v" name="choose-all" value="1" type="checkbox">
							<label for="choose-all-v" class="os-choose-all__checkbox-label">
								<div class="os-choose-all__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
							</label>
						</div>
					</div>
					<div class="os-vacancies__list">
						<?php foreach ($viData['vacs'] as $key => $val): ?>
							<label class="os-vacancies__item">
								<div class="os-vacancies__item-bg">
									<span class="os-vacancies__item-title"><?=$val['title'] ?></span>
								</div>
								<input type="checkbox" name="vacancy[]" value="<?=$val['id']?>" class="os-vacancies__item-input">
							</label>		
						<?php endforeach; ?>
					</div>
					<div class="service-block">
						<div class="outstaffing-service__choose-all check2">
							<span class="os-choose-all__name">Выбрать все услуги</span>
							<div class="os-choose-all__checkbox">
								<input id="choose-all-s" name="choose-all" value="1" type="checkbox">
								<label for="choose-all-s" class="os-choose-all__checkbox-label">
									<div class="os-choose-all__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
								</label>
							</div>
						</div>
						<div class="os-vacancies__list">
							<div class="os-services__item">
								<input type="checkbox" name="consultation" value="Консультация менеджера по услуге" id="outstaffing1">
								<label class="os-services__label" for="outstaffing1">
									<span class="os-services__circle">
										<span class="os-services__name">Консультация<br>менеджера по услуге</span>
										<span class="os-services__img ico1"></span>
									</span>
								</label>
							</div>
							<div class="os-services__item">
								<input type="checkbox" name="registration-rf" value="Оформление сотрудников-резидентов по всей РФ" id="outstaffing2">
								<label class="os-services__label" for="outstaffing2">
									<span class="os-services__circle">
										<span class="os-services__name">Оформление<br>сотрудников-резидентов<br>по всей РФ</span>
										<span class="os-services__img ico2"></span>
									</span>
								</label>
							</div>
							<div class="os-services__item">
								<input type="checkbox" name="registration-mr" value="Оформление сотрудников-нерезидентов в Москве и МО" id="outstaffing3">
								<label class="os-services__label" for="outstaffing3">
									<span class="os-services__circle">
										<span class="os-services__name">Оформление<br>сотрудников-нерезидентов<br>в Москве и МО</span>
										<span class="os-services__img ico3"></span>
									</span>
								</label>
							</div>
							<div class="clearfix"></div>
						</div>
						<br>
						<button class="service__btn prmu-btn prmu-btn_normal pull-right">
							<span>ЗАКАЗАТЬ</span>
						</button>
					</div>
					<input type="hidden" name="step" value="2">
				</form>
			<?php else: ?>
				<br>
				<h2 class="outstaffing-service__title center">У ВАС НЕТ ОПУБЛИКОВАНЫХ ВАКАНСИЙ</h2>
				<div class="center">
					<br>
					<a href="<?=MainConfig::$PAGE_VACPUB?>" class="service__btn visible prmu-btn prmu-btn_normal">
						<span>ДОБАВИТЬ ВАКАНСИЮ</span>
					</a>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>