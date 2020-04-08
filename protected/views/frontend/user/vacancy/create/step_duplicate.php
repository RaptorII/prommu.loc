<div class="form form-medium">
  <svg x="0" y="0" class="svg-bg" />
  <h2 class="form__header-h2">Дублирование вакансии</h2>
  <h6 class="form__header-h6">Введите данные</h6>
  <div class="form__container">
    <?
    //
    ?>
    <div class="form__field form__field-first">
      <label class="form__field-label text__nowrap">Дата <span class="text__red">*</span></label>
      <div class="form__field-content form__content-flex form__content-hint" id="period">
        <div class="form__content-2 form__content-indent">
          <? if($model->errors['bdate']): ?>
            <span class="prmu-error-mess">Поле обязательно к заполнению</span>
          <? endif; ?>
          <label class="form__field-date">
            <input
              type="text"
              name="bdate"
              value="<?=$model->data->bdate?>"
              class="form__field-input form__field-input-date prmu-required<?=($model->errors['bdate']?' prmu-error':'')?>"
              data-params='{"parent_tag":".form__content-2","message":"Поле обязательно к заполнению"}'
              autocomplete="off">
          </label>
        </div>
        <div class="form__content-2 form__content-indent">
          <? if($model->errors['edate']): ?>
            <span class="prmu-error-mess">Поле обязательно к заполнению</span>
          <? endif; ?>
          <label class="form__field-date">
            <input
              type="text"
              name="edate"
              value="<?=$model->data->edate?>"
              class="form__field-input form__field-input-date prmu-required<?=($model->errors['edate']?' prmu-error':'')?>"
              data-params='{"parent_tag":".form__content-2","message":"Поле обязательно к заполнению"}'
              autocomplete="off">
          </label>
        </div>
      </div>
      <div class="form__field-hint tooltip" title="Расширенный список дат и времени работы можно очень легко и удобно добавить в режиме редактирования вакансии после сохранения"></div>
    </div>
    <?
    //
    ?>
    <hr>
    <div class="form__field">
      <div class="form__field-content form__content-indent form__field-first">
        <input
          type="checkbox"
          name="premium"
          value="1"
          id="premium"
          class="form__field-checkbox"
          checked="checked">
        <label for="premium" class="form__checkbox-label"><b>ПРЕМИУМ ВАКАНСИЯ</b></label>
        <div class="form__description">
          <p class="text__justify">Премиум вакансии ускоряют процесс подбора персонала в несколько раз. Такие вакансии выделяются рамкой и демонстрируются вне очереди на главной странице сайта и в списке вакансий, не опускаясь ниже первой страницы. Привлекает максимальное количество откликов со стороны Соискателей</p>
          <div class="form__content-flex form__flex-vmiddle">
            <input
              type="number"
              name="premium_period"
              value="2"
              class="form__field-input"
              id="premium_input">
            <div>дня за <span id="premium_price"></span> руб.</div>
          </div>
          <div>
            <? foreach ($model->dataOther->arSelectCity as $key => $v): ?>
              <input
                type="checkbox"
                name="premium_region[]"
                value="<?=$key?>"
                id="premium_city_<?=$key?>"
                class="form__field-checkbox"
                checked="checked">
              <label for="premium_city_<?=$key?>" class="form__checkbox-label"><?=$v?></label>
            <? endforeach; ?>
          </div>
          <p class="text__justify">Более <span id="premium_percent"></span>% вакансий за <span id="premium_days"></span> дня <b>Премиум размещения</b> набирают достаточное количество откликов, чтобы найти сотрудников и закрыть вакансию</p>
        </div>
      </div>
    </div>
    <?
    //
    ?>
    <p class="input">
      <button type="submit" class="btn-green form__submit">Создать</button>
    </p>
    <?
    //
    ?>
    <input type="hidden" name="step" value="<?=$model->step?>">
  </div>
</div>
<script>
  new ServicePremium({
    period:2,
    prices:<?=json_encode($model->dataOther->prices)?>
  });
</script>