<div class="form form-medium">
  <a
    class="form__tutorial form__tutorial-hint"
    href="/theme/pdf/Instruction-PROMMU-com-vac.pdf"
    target="_blank"
    title="Инструкция по созданию вакансии">
  </a>
  <svg x="0" y="0" class="svg-bg" />
  <h2 class="form__header-h2">6й этап</h2>
  <h6 class="form__header-h6">Введите данные</h6>
  <div class="form__container">
    <?
    //
    ?>
    <? $cost = ServiceCloud::getCostForVacancyCreate($model->data->city);?>
    <? if(!Share::$UserProfile->accessToFreeVacancy && $cost>0): ?>
      <div class="form__field">
        <div class="form__field-content form__content-indent form__field-first">
          <b>РАЗМЕЩЕНИЕ ВАКАНСИИ: <?=$cost . ' руб.';?></b>
        </div>
      </div>
      <hr>
    <? endif; ?>
    <?
    //
    ?>
    <div class="form__field-content<?=((!Share::$UserProfile->accessToFreeVacancy && $cost>0)?'':' form__field-first')?>">
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
            value="<?=($model->dataOther->period<2?$model->dataOther->period:2)?>"
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
    <hr>
    <?
    //
    ?>
    <p class="text__justify">
    Поля обозначенные * обязательные к заполнению. После сохранения вакансии, Вы перейдете в режим редактирования, где сможете добавить дополнительную информацию:
    <ul>
      <li>по параметрам соискателей (рост, вес, цвет волос и т.д.)</li>
      <li>данных по городу (адреса точек, названия локаций, периоды работы)</li>
      <li>и многое другое что поможет Вам в кратчайшие сроки найти необходимый персонал</li>
    </ul>
    </p>
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
    period:<?=$model->dataOther->period?>,
    prices:<?=json_encode($model->dataOther->prices)?>
  });
</script>