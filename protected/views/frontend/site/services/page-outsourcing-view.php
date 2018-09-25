<?
  Yii::app()->getClientScript()->registerCssFile('/theme/css/services/services-outstaffing-page.css');
  Yii::app()->getClientScript()->registerScriptFile('/theme/js/services/services-outstaffing-page.js', CClientScript::POS_END);
?>
<div class="row">
	<div class="col-xs-12 outstaffing-service">
		<?php if(Yii::app()->getRequest()->isPostRequest): ?>
			<?php if(Yii::app()->getRequest()->getParam('step')==2): ?>	
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
					<?php foreach (Yii::app()->getRequest()->getParam('vacancy') as $vacancy): ?>
						<input type="hidden" name="vacancy[]" value="<?=$vacancy?>">
					<?php endforeach ?>
					<input type="hidden" name="account" value="<?=Share::$UserProfile->id?>">
					<input type="hidden" name="consultation" value="<?=Yii::app()->getRequest()->getParam('consultation')?>">
					<input type="hidden" name="advertising" value="<?=Yii::app()->getRequest()->getParam('advertising')?>">
					<input type="hidden" name="control" value="<?=Yii::app()->getRequest()->getParam('control')?>">
					<input type="hidden" name="service" value="outsourcing">
					<button class="outstaffing-service__btn payment-button">СОХРАНИТЬ</button>
				</form>
			<?php else: ?>
				<h2 class="outstaffing-service__title">ВЫБЕРИТЕ УСЛУГИ АУТСОРСИНГА</h2>
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
							<input type="checkbox" name="consultation" value="Консультация по набору персонала на нужную Вам вакансию" id="outsourcing1">
							<label class="os-services__label" for="outsourcing1">
								<span class="os-services__circle">
									<span class="os-services__name">Консультация<br>по набору персонала<br>на нужную Вам вакансию</span>
									<span class="os-services__img outsourcing__img ico1"></span>
								</span>
							</label>
						</div>
						<div class="os-services__item">
							<input type="checkbox" name="advertising" value="Организация проведения различных видов рекламы" id="outsourcing2">
							<label class="os-services__label" for="outsourcing2">
								<span class="os-services__circle">
									<span class="os-services__name">Организация проведения<br>различных видов рекламы</span>
									<span class="os-services__img outsourcing__img ico2"></span>
								</span>
							</label>
						</div>
						<div class="os-services__item">
							<input type="checkbox" name="control" value="Организация контроля проведения проекта" id="outsourcing3">
							<label class="os-services__label" for="outsourcing3">
								<span class="os-services__circle">
									<span class="os-services__name">Организация контроля<br>проведения проекта</span>
									<span class="os-services__img outsourcing__img ico3"></span>
								</span>
							</label>
						</div>
						<div class="clearfix"></div>
					</div>
					<?php foreach (Yii::app()->getRequest()->getParam('vacancy') as $vacancy): ?>
						<input type="hidden" name="vacancy[]" value="<?=$vacancy?>">
					<?php endforeach ?>
					<input type="hidden" name="step" value="2">
					<button class="outstaffing-service__btn payment-button">ЗАКАЗАТЬ</button>
				</form>
			<?php endif; ?>
		<?php else: ?>
			<?php if( $viData['vacs'] ): ?>
				<h2 class="outstaffing-service__title">КАКИЕ ВАКАНСИИ НУЖДАЮТСЯ В УСЛУГАХ АУТСОРСИНГА?</h2>
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
							<label class="os-vacancies__item outsourcing__item">
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
								<input type="checkbox" name="consultation" value="Консультация по набору персонала на нужную Вам вакансию" id="outsourcing1">
								<label class="os-services__label" for="outsourcing1">
									<span class="os-services__circle">
										<span class="os-services__name">Консультация<br>по набору персонала<br>на нужную Вам вакансию</span>
										<span class="os-services__img outsourcing__img ico1"></span>
									</span>
								</label>
							</div>
							<div class="os-services__item">
								<input type="checkbox" name="advertising" value="Организация проведения различных видов рекламы" id="outsourcing2">
								<label class="os-services__label" for="outsourcing2">
									<span class="os-services__circle">
										<span class="os-services__name">Организация проведения<br>различных видов рекламы</span>
										<span class="os-services__img outsourcing__img ico2"></span>
									</span>
								</label>
							</div>
							<div class="os-services__item">
								<input type="checkbox" name="control" value="Организация контроля проведения проекта" id="outsourcing3">
								<label class="os-services__label" for="outsourcing3">
									<span class="os-services__circle">
										<span class="os-services__name">Организация контроля<br>проведения проекта</span>
										<span class="os-services__img outsourcing__img ico3"></span>
									</span>
								</label>
							</div>
							<div class="clearfix"></div>
						</div>
						<button class="outstaffing-service__btn payment-button">ЗАКАЗАТЬ</button>					
					</div>
					<input type="hidden" name="step" value="2">
				</form>
			<?php else: ?>
				<h2 class="outstaffing-service__title">У ВАС НЕТ ОПУБЛИКОВАНЫХ ВАКАНСИЙ</h2>
				<a href="<?=MainConfig::$PAGE_VACPUB?>" class="outstaffing-service__btn visible">ДОБАВИТЬ ВАКАНСИЮ</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>