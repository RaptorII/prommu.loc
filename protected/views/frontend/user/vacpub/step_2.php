<div class="form form-medium">
  <svg x="0" y="0" class="svg-bg" />
  <h2 class="form__header-h2">2й этап</h2>
  <h6 class="form__header-h6">Введите данные</h6>
  <div class="form__container">
    <?
    //
    ?>
    <? !isset($model->data->istemp) && $model->data->istemp=0; // По умолчанию временная ?>
    <div class="form__field form__field-first">
      <label class="form__field-label text_nowrap">Тип работы</label>
      <div class="form__field-content form__content-indent">
        <? if($model->errors['istemp']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <div class="form__field-input form__field-select prmu-required<?=($model->errors['istemp']?' prmu-error':'')?>" id="work_type">
          <select name="istemp">
            <? foreach (Vacancy::WORK_TYPE as $key => $v): ?>
              <option value="<?=$key?>"<?=$model->data->istemp==$key?' selected="selected"':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <?
    //
    ?>
    <? !isset($model->data->exp) && $model->data->exp=1; // По умолчанию без опыта ?>
    <div class="form__field">
      <label class="form__field-label text_nowrap">Опыт работы</label>
      <div class="form__field-content form__content-indent">
        <? if($model->errors['exp']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <div class="form__field-input form__field-select prmu-required<?=($model->errors['exp']?' prmu-error':'')?>" id="experience">
          <select name="exp">
            <? foreach (Vacancy::EXPERIENCE as $key => $v): ?>
              <option value="<?=$key?>"<?=$model->data->exp==$key?' selected="selected"':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <?
    //
    ?>
    <? !isset($model->data->self_employed) && $model->data->self_employed=0; // По умолчанию без опыта ?>
    <div class="form__field">
      <label class="form__field-label text_nowrap">Налоговый статус</label>
      <div class="form__field-content form__content-indent">
        <? if($model->errors['self_employed']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <div class="form__field-input form__field-select prmu-required<?=($model->errors['self_employed']?' prmu-error':'')?>" id="self_employed">
          <select name="self_employed">
            <? foreach (Vacancy::SELF_EMPLOYED as $key => $v): ?>
              <option value="<?=$key?>"<?=$model->data->self_employed==$key?' selected="selected"':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <label class="form__field-label text_nowrap">Возраст <span class="text_red">*</span></label>
      <div class="form__field-content form__content-indent" id="age">
        <? if($model->errors['age']): ?>
          <span class="prmu-error-mess">Значение "От" должно быть больше 14 и меньше значения "До"</span>
        <? endif; ?>
        <div class="form__content-flex">
          <div class="form__content-2">
            <input
              type="text"
              name="age_from"
              value="<?=$model->data->age_from?:''?>"
              class="form__field-input form__field-input-date prmu-required prmu-check<?=($model->errors['age']?' prmu-error':'')?>"
              data-params='{"limit":"2","regexp":"\\D+","parent_tag":".form__field-content","message":"Значение \"От\" должно быть больше 14 и меньше значения \"До\""}'
              autocomplete="off">
          </div>
          <div class="form__field-dash">-</div>
          <div class="form__content-2">
            <input
              type="text"
              name="age_to"
              value="<?=$model->data->age_to?:''?>"
              class="form__field-input form__field-input-date prmu-check<?=(($model->errors['age']&&$model->data->ageTo>0)?' prmu-error':'')?>"
              data-params='{"limit":"2","regexp":"\\D+"}'
              autocomplete="off">
          </div>
        </div>
      </div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <label class="form__field-label text_nowrap">Пол <span class="text_red">*</span></label>
      <div class="form__field-content form__content-indent">
        <? if($model->errors['gender']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <div class="form__content-flex">
          <div class="form__content-2">
            <input
              type="checkbox"
              name="gender[]"
              value="man"
              id="gender_man"
              class="form__field-checkbox prmu-required"
              data-params='{"parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}'
              <?=(!isset($model->data->gender)||in_array('man',$model->data->gender))?' checked="checked"':''?>>
            <label for="gender_man" class="form__checkbox-label">Мужчина</label>
          </div>
          <div class="form__content-2">
            <input
              type="checkbox"
              name="gender[]"
              value="woman"
              id="gender_woman"
              class="form__field-checkbox prmu-required"
              data-params='{"parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}'
              <?=(!isset($model->data->gender)||in_array('woman',$model->data->gender))?' checked="checked"':''?>>
            <label for="gender_woman" class="form__checkbox-label">Женщина</label>
          </div>
        </div>
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
<? if(Yii::app()->request->url == MainConfig::$PAGE_VACPUB ): ?>
  <script>
    window.history.pushState("object or string", "page name", "<?=MainConfig::$PAGE_VACPUB . DS . $model->vacancy ?>");
  </script>
<? endif; ?>