<?php
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'phone-codes/style.css'); 
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'phone-codes/script.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'services/services-med-page.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'services/services-med-page.js', CClientScript::POS_END);
  $model = new MedRequest();
	$arRes = $model->getUserData(Share::$UserProfile->id, Share::$UserProfile->type);
?>
<? if(!empty($arRes['phone-code'])): ?>
		<script type="text/javascript">
			selectPhoneCode = <?=$arRes['phone-code']?>;
		</script>
<? endif; ?>
<div class="service-med-record">
	<div class="row">
		<div class="hidden-xs col-sm-4">
			<div class="smr__girl"></div>
		</div>
		<div class="col-xs-12 col-sm-8">
			<h1 class="smr__title">ДЛЯ ОФОРМЛЕНИЯ МЕДИЦИНСКОЙ КНИЖКИ, ПОЖАЛУЙСТА, УКАЖИТЕ СЛЕДУЮЩИЕ ДАННЫЕ</h1>
			<form method="POST" action="/medical" id="smr-form">
				<label class="smr__label">
					<input type="text" name="surname" class="smr__input smr__required" placeholder="Фамилия" id="smr-surname" value="<?=$arRes['firstname']?>">
				</label>
				<label class="smr__label">
					<input type="text" name="name" class="smr__input smr__required" placeholder="Имя" id="smr-name" value="<?=$arRes['lastname']?>">
				</label>
				<label class="smr__label">
					<input type="text" name="patronymic" class="smr__input smr__required" placeholder="Отчество" id="smr-patronymic">
				</label>
				<label class="smr__label">
					<input id='phone-code' type="text" name="phone" placeholder="Мобильный телефон" value="<?=$arRes['phone']?>" class="smr__required">
				</label>
				<label class="smr__label">
					<input type="text" name="email" class="smr__input smr__required" placeholder="Email" id="smr-mail" value="<?=$arRes['email']?>">
				</label>
				<label class="smr__label smr__select-block">
					<select name="adres" class="smr__input smr__select smr__required">
						<option value="На Новослободской">На Новослободской</option>
						<option value="На Таганке">На Таганке</option>
						<option value="На Киевской">На Киевской</option>
						<option value="На Курской">На Курской</option>
					</select>
				</label>
				<label class="smr__label smr__select-block">
					<select name="pay" class="smr__input smr__select smr__required">
						<option value="Сервису Промму">Сервису Промму</option>
						<option value="Наличными в Медицинском центре">Наличными в Медицинском центре</option>
					</select>
				</label>
				<label class="smr__label">
					<textarea class="smr__input smr__textarea" placeholder="Комментарий" name="comment"></textarea>
				</label>
				<button type="submit" class="service__btn prmu-btn prmu-btn_normal" id="service__btn">
					<span>Отправить заявку</span>
				</button>
			</form>
		</div>
	</div>
</div>
