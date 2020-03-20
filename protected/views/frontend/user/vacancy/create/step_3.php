<div class="form form-medium">
  <svg x="0" y="0" class="svg-bg" />
  <h2 class="form__header-h2">3й этап</h2>
  <h6 class="form__header-h6">Введите данные</h6>
  <div class="form__container">
    <?
    //
    ?>
    <div class="form__field form__field-first">
      <label class="form__field-label text__nowrap">Заработная плата <span class="text__red">*</span></label>
      <div class="form__field-content form__content-indent form__content-hint">
        <? if($model->errors['salary']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <div class="form__content-flex">
          <div class="form__content-2">
            <input
              type="text"
              name="salary"
              value="<?=$model->data->salary?>"
              class="form__field-input prmu-required prmu-check<?=($model->errors['salary']?' prmu-error':'')?>"
              data-params='{"limit":"6","regexp":"\\D+","parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}'
              autocomplete="off">
          </div>
          <div class="form__field-dash"> </div>
          <div class="form__content-2">
            <? !isset($model->data->salary_type) && $model->data->salary_type=key(reset(Vacancy::SALARY_TYPE)); // По умолчанию 'руб/час' ?>
            <div class="form__field-input form__field-select prmu-required<?=($model->errors['salary_type']?' prmu-error':'')?>" id="salary">
              <select name="salary_type">
                <? foreach (Vacancy::SALARY_TYPE as $key => $v): ?>
                  <option value="<?=$key?>"<?=$model->data->salary_type==$key?' selected="selected"':''?>><?=$v?></option>
                <? endforeach; ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="form__field-hint tooltip" title="Подсказка будет подтягиваться соответственно региону"></div>
    </div>
    <?
    //
    ?>
    <?
    $arSalary = Vacancy::getAllAttributes()->lists['paylims'];
    !isset($model->data->salary_time) && $model->data->salary_time=key(reset($arSalary)); // По умолчанию 'На следующий день'
    ?>
    <div class="form__field">
      <label class="form__field-label text__nowrap">Сроки оплаты</label>
      <div class="form__field-content form__content-indent">
        <? if($model->errors['salary_time']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <div class="form__field-input form__field-select prmu-required<?=($model->errors['salary_time']?' prmu-error':'')?>" id="salary_time">
          <select name="salary_time">
            <? foreach ($arSalary as $key => $v): ?>
              <option value="<?=$key?>"<?=$model->data->salary_time==$key?' selected="selected"':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <label class="form__field-label text__nowrap">Комментарии</label>
      <div class="form__field-content form__content-indent">
        <textarea
          class="form__field-input form__textarea"
          name="salary_comment"><?=$model->data->salary_comment?></textarea>
      </div>
    </div>
    <?
    //
    ?>
    <p class="input">
      <button type="submit" class="btn-green form__submit">Продолжить</button>
    </p>
    <?
    //
    ?>
    <input type="hidden" name="step" value="<?=$model->step?>">
  </div>
</div>