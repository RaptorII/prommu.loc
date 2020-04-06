<div class="form form-medium">
  <svg x="0" y="0" class="svg-bg" />
  <h2 class="form__header-h2">5й этап</h2>
  <h6 class="form__header-h6">Введите данные</h6>
  <div class="form__container">
    <?
    //
    ?>
    <? if(!Share::$UserProfile->accessToFreeVacancy): ?>
      <div class="form__field">
        <div class="form__field-content form__content-indent form__field-first">
          <b>РАЗМЕЩЕНИЕ ВАКАНСИИ: <?=ServiceCloud::getCostForVacancyCreate(array_keys($model->dataOther->arSelectCity)) . ' руб.';?></b>
        </div>
      </div>
      <hr>
    <? endif; ?>
    <?
    //
    ?>
    <div class="form__field">
      <div class="form__field-content form__content-indent form__field-first">
        <div class="form__content-flex">
          <div class="form__content-3">
            <input
              type="checkbox"
              name="medbook"
              value="1"
              id="medbook"
              class="form__field-checkbox"
              <?=isset($model->data->medbook)?' checked="checked"':''?>>
            <label for="medbook" class="form__checkbox-label">Медкнижка</label>
          </div>
          <div class="form__content-3">
            <input
              type="checkbox"
              name="car"
              value="1"
              id="car"
              class="form__field-checkbox"
              <?=isset($model->data->car)?' checked="checked"':''?>>
            <label for="car" class="form__checkbox-label">Автомобиль</label>
          </div>
          <div class="form__content-3">
            <input
              type="checkbox"
              name="smartphone"
              value="1"
              id="smartphone"
              class="form__field-checkbox"
              <?=isset($model->data->smartphone)?' checked="checked"':''?>>
            <label for="smartphone" class="form__checkbox-label">Смартфон</label>
          </div>
        </div>
      </div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <div class="form__field-content form__content-indent form__field-first">
        <div class="form__content-flex form__flex-wrap">
          <div class="form__content-2 form__content-mfull">
            <input
              type="checkbox"
              name="card_prommu"
              value="1"
              id="card_prommu"
              class="form__field-checkbox"
              <?=isset($model->data->card_prommu)?' checked="checked"':''?>>
            <label for="card_prommu" class="form__checkbox-label">Наличие банковской карты Prommu</label>
          </div>
          <div class="form__content-2 form__content-mfull">
            <input
              type="checkbox"
              name="card"
              value="1"
              id="card"
              class="form__field-checkbox"
              <?=isset($model->data->card)?' checked="checked"':''?>>
            <label for="card" class="form__checkbox-label">Наличие банковской карты</label>
          </div>
        </div>
      </div>
    </div>
    <?
    //
    ?>
    <hr>
    <div class="form__field">
      <div class="form__content-flex form__flex-wrap">
        <div class="form__field-content form__content-indent form__field-first"><b>ПУБЛИКАЦИЯ В СОЦСЕТЯХ</b></div>
        <div class="form__field-content form__content-indent form__field-first">
          <div class="form__content-flex form__flex-wrap">
            <div class="form__content-3 form__content-mfull">
              <input
                type="checkbox"
                name="repost[]"
                value="vk"
                id="repost_vk"
                class="form__field-checkbox"
                <?=in_array('vk',$model->data->repost)?' checked="checked"':''?>>
              <label for="repost_vk" class="form__checkbox-label">Вконтакте</label>
            </div>
            <div class="form__content-3 form__content-mfull">
              <input
                type="checkbox"
                name="repost[]"
                value="facebook"
                id="repost_facebook"
                class="form__field-checkbox"
                <?=in_array('facebook',$model->data->repost)?' checked="checked"':''?>>
              <label for="repost_facebook" class="form__checkbox-label">Facebook</label>
            </div>
            <div class="form__content-3 form__content-mfull">
              <input
                type="checkbox"
                name="repost[]"
                value="telegram"
                id="repost_telegram"
                class="form__field-checkbox"
                <?=in_array('telegram',$model->data->repost)?' checked="checked"':''?>>
              <label for="repost_telegram" class="form__checkbox-label">Telegram</label>
            </div>
          </div>
        </div>
      </div>
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
    period:<?=$model->dataOther->period?>,
    prices:<?=json_encode($model->dataOther->prices)?>
  });
</script>