<? Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'services/legal-fields.css'); ?>
<span class="payment-form__type-name">тип плательщика</span>
<label class="payment-form__radio-label">
  <input name="personal" type="radio" value="individual" class="payment-form__radio-input" checked>
  <span class="payment-form__radio-block"></span>
  <span class="payment-form__radio-name">Физическое лицо</span>
</label>
<br>
<label class="payment-form__radio-label">
  <input name="personal" type="radio" value="legal" class="payment-form__radio-input">
  <span class="payment-form__radio-block"></span>
  <span class="payment-form__radio-name">Юридическое лицо</span>
</label>
<div class="payment-form__legal" id="payment-legal">
  <span class="payment-form__type-name">ДАННЫЕ ЮРИДИЧЕСКОГО ЛИЦА</span>
  <label class="payment__label" title="Название предприятия">
    <input type="text" name="name" placeholder="Название предприятия" value="<?=$viData['user']['name']?>" class="payment__input" id="legal-name"/>
  </label>
  <label class="payment__label" title="ИНН">
    <input type="text" name="inn" placeholder="ИНН" class="payment__input" id="legal-inn" value="<?=$viData['user']['inn']?>"/>
  </label>
  <label class="payment__label" title="КПП">
    <input type="text" name="kpp" placeholder="КПП" class="payment__input" id="legal-kpp" />
  </label>
  <label class="payment__label" title="Email">
    <input type="text" name="email" placeholder="Email" class="payment__input" id="legal-email" value="<?=$viData['user']['email']?>"/>
  </label>
  <label class="payment__label" title="Индекс">
    <input type="text" name="index" placeholder="Индекс" class="payment__input" id="legal-index"/>
  </label>
  <label class="payment__label" title="Город">
    <input type="text" name="city" placeholder="Город" class="payment__input" id="legal-city"/>
  </label>
  <label class="payment__label" title="Улица, дом, строение, офис">
    <input type="text" name="detail" placeholder="Улица, дом, строение, офис" class="payment__input" id="legal-detail"/>
  </label>
  <label class="payment-form__radio-label">
    <input name="with_nds" type="radio" value="1" class="payment-form__radio-input" checked>
    <span class="payment-form__radio-block"></span>
    <span class="payment-form__radio-name">с НДС</span>
  </label>
  <br>
  <label class="payment-form__radio-label">
    <input name="with_nds" type="radio" value="2" class="payment-form__radio-input">
    <span class="payment-form__radio-block"></span>
    <span class="payment-form__radio-name">без НДС</span>
  </label>
</div>
