<div class="form form-medium">
  <a
    class="form__tutorial tooltip"
    href="/theme/pdf/Instruction-PROMMU-com-vac.pdf"
    target="_blank"
    title="Инструкция по созданию вакансии">
  </a>
  <svg x="0" y="0" class="svg-bg" />
  <h2 class="form__header-h2">5й этап</h2>
  <h6 class="form__header-h6">Введите данные</h6>
  <div class="form__container">
    <?
    //
    ?>
    <div class="form__field form__field-first">
      <div class="form__content-indent"><b>Дополнительные требования</b></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <div class="form__field-content form__content-indent form__content-hint">
        <input
          type="checkbox"
          name="medbook"
          value="1"
          id="medbook"
          class="form__field-checkbox"
          <?=isset($model->data->medbook)?' checked="checked"':''?>>
        <label for="medbook" class="form__checkbox-label">Медкнижка</label>
      </div>
      <div class="form__field-hint form__field-hint-chbox tooltip" title="При наличии этого параметра на вакансию смогут откликнуться кандидаты, у которых есть медицинская карта"></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <div class="form__field-content form__content-indent form__content-hint">
        <input
          type="checkbox"
          name="car"
          value="1"
          id="car"
          class="form__field-checkbox"
          <?=isset($model->data->car)?' checked="checked"':''?>>
        <label for="car" class="form__checkbox-label">Автомобиль</label>
      </div>
      <div class="form__field-hint form__field-hint-chbox tooltip" title="При наличии этого параметра на вакансию смогут откликнуться кандидаты, у которых есть автомобиль"></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <div class="form__field-content form__content-indent form__content-hint">
        <input
          type="checkbox"
          name="smartphone"
          value="1"
          id="smartphone"
          class="form__field-checkbox"
          <?=isset($model->data->smartphone)?' checked="checked"':''?>>
        <label for="smartphone" class="form__checkbox-label">Смартфон</label>
      </div>
      <div class="form__field-hint form__field-hint-chbox tooltip" title="При наличии этого параметра на вакансию смогут откликнуться кандидаты, у которых есть смартфон"></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <div class="form__field-content form__content-indent form__content-hint">
        <input
          type="checkbox"
          name="card_prommu"
          value="1"
          id="card_prommu"
          class="form__field-checkbox"
          <?=isset($model->data->card_prommu)?' checked="checked"':''?>>
        <label for="card_prommu" class="form__checkbox-label">Наличие банковской карты Prommu</label>
      </div>
      <div class="form__field-hint form__field-hint-chbox tooltip" title="При наличии этого параметра на вакансию смогут откликнуться кандидаты, у которых есть банковская карта Prommu"></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <div class="form__field-content form__content-indent form__content-hint">
        <input
          type="checkbox"
          name="card"
          value="1"
          id="card"
          class="form__field-checkbox"
          <?=isset($model->data->card)?' checked="checked"':''?>>
        <label for="card" class="form__checkbox-label">Наличие банковской карты</label>
      </div>
      <div class="form__field-hint form__field-hint-chbox tooltip" title="При наличии этого параметра на вакансию смогут откликнуться кандидаты, у которых есть банковская карта"></div>
    </div>
    <?
    //
    ?>
    <div class="form__field">
      <div class="form__content-flex form__flex-wrap">
        <div class="form__field-content form__content-indent"><b>Публикация в соцсетях</b></div>
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
              <label for="repost_vk" class="form__checkbox-label">Вконтакте<a href="<?=MainConfig::$PROMMU_VKONTAKTE?>" target="_blank" class="form__checkbox-label-link tooltip" title="Группа в ВК"></a></label>
              </label>
            </div>
            <div class="form__content-3 form__content-mfull">
              <input
                type="checkbox"
                name="repost[]"
                value="facebook"
                id="repost_facebook"
                class="form__field-checkbox"
                <?=in_array('facebook',$model->data->repost)?' checked="checked"':''?>>
              <label for="repost_facebook" class="form__checkbox-label">Facebook<a href="<?=MainConfig::$PROMMU_FACEBOOK?>" target="_blank" class="form__checkbox-label-link tooltip" title="Группа в Facebook"></a></label>
              </label>
            </div>
            <div class="form__content-3 form__content-mfull">
              <input
                type="checkbox"
                name="repost[]"
                value="telegram"
                id="repost_telegram"
                class="form__field-checkbox"
                <?=in_array('telegram',$model->data->repost)?' checked="checked"':''?>>
              <label for="repost_telegram" class="form__checkbox-label">Telegram<a href="<?=MainConfig::$PROMMU_TELEGRAM?>" target="_blank" class="form__checkbox-label-link tooltip" title="Telegram канал"></a></label>
            </div>
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
    <p class="separator text__center">
      <a href="javascript:void(0)" class="back__away" id="prev_step">НАЗАД</a>
    </p>
    <?
    //
    ?>
    <input type="hidden" name="step" value="<?=$model->step?>">
  </div>
</div>