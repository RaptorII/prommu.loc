<div class="form form-medium">
  <svg x="0" y="0" class="svg-bg" />
  <h2 class="form__header-h2">4й этап</h2>
  <h6 class="form__header-h6">Введите данные</h6>
  <div class="form__container">
    <?
    //
    ?>
    <div class="form__field">
      <label class="form__field-label text_nowrap">Описание <span class="text_red">*</span></label>
      <div class="form__field-content form__content-indent form__content-hint form__field-niceditor">
        <? if($model->errors['requirements']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <div id="requirements_panel" class="form__textarea-panel"></div>
        <textarea
          class="form__field-input form__textarea prmu-required"
          id="requirements"
          data-params='{"parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}'
          name="requirements"><?=$model->data->requirements?></textarea>
      </div>
      <div class="form__field-hint tooltip" title="Подсказка:<br>Раздача листовок согласно адресной программы;<br>Возраст: от 18 лет;<br>Активные ответственные девушки и парни;<br>Одеты опрятно;<br>Ответственные;<br>Коммуникабельные;<br>Веселые"></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <label class="form__field-label text_nowrap">Обязанности</label>
      <div class="form__field-content form__content-indent form__content-hint form__field-niceditor">
        <div id="duties_panel" class="form__textarea-panel"></div>
        <textarea
          class="form__field-input form__textarea"
          id="duties"
          name="duties"><?=$model->data->duties?></textarea>
      </div>
      <div class="form__field-hint tooltip" title="Подсказка:<br>Раздача листовок только для целевой аудитории: девушкам от 20 до 35 лет. Листовки забрать на офисе в районе локации за 15 минут до старта работы"></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <label class="form__field-label text_nowrap">Условия</label>
      <div class="form__field-content form__content-indent form__content-hint form__field-niceditor">
        <div id="conditions_panel" class="form__textarea-panel"></div>
        <textarea
          class="form__field-input form__textarea"
          id="conditions"
          name="conditions"><?=$model->data->conditions?></textarea>
      </div>
      <div class="form__field-hint tooltip" title="Подсказка:<br>Работа на улице в соответствии с графиком и адресом; Выплата ЗП по окончанию проекта на банковскую карту согласно отработанных часов"></div>
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