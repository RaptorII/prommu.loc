<div class="form form-medium">
  <svg x="0" y="0" class="svg-bg" />
  <h2 class="form__header-h2">1й этап</h2>
  <h6 class="form__header-h6">Введите данные</h6>
  <div class="form__container">
    <? /*if(Share::$UserProfile->accessToFreeVacancy): ?>
      <div class="form__field form__field-first">
        <div class="form__field-content form__content-indent">Создание данной вакансии будет платной потому, что в период 30 дней вакансия уже добавлялась Вами</div>
        <label class="form__field-label text__nowrap">Стоимость</label>
        <div class="form__field-content form__content-indent form__content-hint" id="cost">
          <?=ServiceCloud::getCostForVacancyCreate(array_keys($model->dataOther->arSelectCity)) . ' руб.';?>
        </div>
      </div>
      <script>var arVacancyPrice = <?=json_encode(ServiceCloud::PAYMENT_FOR_CREATE)?></script>
    <? endif; */?>
    <?
    //
    ?>
    <div class="form__field form__field-first">
      <label class="form__field-label text__nowrap">Заголовок <span class="text__red">*</span></label>
      <div class="form__field-content form__content-indent form__content-hint">
        <? if($model->errors['title']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <input
          type="text"
          name="title"
          value="<?=$model->data->title?>"
          class="form__field-input prmu-required prmu-check<?=($model->errors['title']?' prmu-error':'')?>"
          data-params='{"limit":"<?=VacancyCheckFields::TITLE_LENGTH?>","parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}'
          autocomplete="off">
      </div>
      <div class="form__field-hint tooltip" title="Заголовок должен быть кратким и отражать суть вакансии. Например: Раздача листовок / Расклейка объявлений / Подработка промоутером / Аниматор на детский праздник"></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <label class="form__field-label text__nowrap">Должность <span class="text__red">*</span></label>
      <div class="form__field-content form__content-indent form__content-hint">
        <? if($model->errors['post']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <div class="form__field-input form__field-select prmu-required<?=($model->errors['post']?' prmu-error':'')?>" data-params='{"parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}' id="posts">
          <select name="post[]">
            <option value="" selected="selected" disabled="disabled"></option>
            <? foreach ($model->dataOther->posts as $v): ?>
              <option value="<?=$v['id']?>"<?=(array_key_exists($v['id'],$model->dataOther->arSelectPost)?' selected="selected"':'')?>><?=$v['name']?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form__field-hint tooltip" title="Выберите одну должность, которая необходима Вам для набора персонала. Если Вам необходимо подобрать несколько должностей Вы сможете дублировать размещенную вакансию и при этом изменить должность или другие параметры вакансии"></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <label class="form__field-label text__nowrap">Город <span class="text__red">*</span></label>
      <div class="form__field-content form__content-indent form__content-hint">
        <? if($model->errors['city']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <div class="form__field-input form__field-select prmu-required<?=($model->errors['city']?' prmu-error':'')?>" data-params='{"parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}' id="cities">
          <select name="city[]" multiple>
            <? if($model->dataOther->arSelectCity): ?>
              <? foreach ($model->dataOther->arSelectCity as $id => $v): ?>
                <option value="<?=$id?>" selected="selected"><?=$v?></option>
              <? endforeach; ?>
            <? endif; ?>
          </select>
        </div>
      </div>
      <div class="form__field-hint tooltip" title="Расширенный и точный список Городов и адресов можно очень легко и удобно добавить в режиме редактирования вакансии после сохранения"></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
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
    <p class="input">
      <button type="submit" class="btn-green form__submit">Продолжить</button>
    </p>
    <?
    //
    ?>
    <input type="hidden" name="step" value="<?=$model->step?>">
  </div>
</div>