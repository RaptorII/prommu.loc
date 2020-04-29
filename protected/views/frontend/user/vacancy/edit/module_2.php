<? $module = 2; ?>
<div class="personal__area--capacity">
  <div class="personal__area--capacity-name">Общая информация</div>
  <?
  //
  ?>
  <div class="module_info<?=$viData->error_moodule==$module?' block__hide':''?>">
    <? if($viData->data->is_actual_remdate && !count($viData->services->creation_vacancy->items)): ?>
      <a href="javascript:void(0)" class="personal__area--capacity-edit js-g-hashint" title="Редактировать"></a>
    <? endif; ?>
    <div class="group ppe__field">
      <div class="group__about">Заголовок</div>
      <div class="group__info"><?=$viData->data->title?></div>
    </div>
    <div class="group ppe__field">
      <div class="group__about">Должность</div>
      <div class="group__info"><?=$viData->attributes->items[reset($viData->data->post)]['name']?></div>
    </div>
    <div class="group ppe__field">
      <div class="group__about">Опыт работы</div>
      <div class="group__info"><?=Vacancy::EXPERIENCE[$viData->data->exp]?></div>
    </div>
    <div class="group ppe__field">
      <div class="group__about">Возраст</div>
      <div class="group__info"><?=VacancyView::getAge($viData->data->agefrom,$viData->data->ageto)?></div>
    </div>
    <div class="group ppe__field">
      <div class="group__about">Пол</div>
      <div class="group__info"><?=VacancyView::getGender($viData->data->isman, $viData->data->iswoman) ?></div>
    </div>
    <div class="group ppe__field">
      <div class="group__about">Тип работы</div>
      <div class="group__info"><?=Vacancy::WORK_TYPE[$viData->data->istemp]?></div>
    </div>
    <div class="group ppe__field">
      <div class="group__about">Дата публикации</div>
      <div class="group__info"><?=Share::getDate($viData->data->crdate_unix,'d.m.Y') . ' - ' . Share::getDate($viData->data->remdate_unix,'d.m.Y')?></div>
    </div>
  </div>
  <?
  //
  ?>
  <form class="module_form<?=$viData->error_moodule==$module?' block__visible':''?>" method="post" data-params='{"ajax":"true"}'>
    <a href="javascript:void(0)" class="personal__area--capacity-cancel js-g-hashint" title="Отмена"></a>
    <div class="form__container">
      <div class="form__field">
        <label class="form__field-label text__nowrap">Заголовок <span class="text__red">*</span></label>
        <div class="form__field-content form__content-indent form__content-hint">
          <? if($viData->errors['title']): ?>
            <span class="prmu-error-mess">Поле обязательно к заполнению</span>
          <? endif; ?>
          <input
            type="text"
            name="title"
            value="<?=$viData->data->title?>"
            class="form__field-input prmu-required prmu-check"
            data-params='{"limit":"<?=VacancyCheckFields::TITLE_LENGTH?>","parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}'
            autocomplete="off">
        </div>
        <div class="form__field-hint tooltip" title="Заголовок должен быть кратким и отражать суть вакансии. Например: Раздача листовок / Расклейка объявлений / Подработка промоутером / Аниматор на детский праздник"></div>
      </div>
      <div class="form__field">
        <label class="form__field-label text__nowrap">Должность <span class="text__red">*</span></label>
        <div class="form__field-content form__content-indent form__content-hint">
          <? if($viData->errors['post']): ?>
            <span class="prmu-error-mess">Поле обязательно к заполнению</span>
          <? endif; ?>
          <div class="form__field-input form__field-select prmu-required" data-params='{"parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}' id="posts">
            <select name="post[]">
              <? foreach ($viData->posts as $v): ?>
                <option value="<?=$v['id']?>"<?=(in_array($v['id'],$viData->data->post)?' selected':'')?>><?=$v['name']?></option>
              <? endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form__field-hint tooltip" title="Выберите одну должность, которая необходима Вам для набора персонала. Если Вам необходимо подобрать несколько должностей Вы сможете дублировать размещенную вакансию и при этом изменить должность или другие параметры вакансии"></div>
      </div>
      <div class="form__field">
        <label class="form__field-label text__nowrap">Опыт работы</label>
        <div class="form__field-content form__content-indent">
          <? if($viData->errors['exp']): ?>
            <span class="prmu-error-mess">Поле обязательно к заполнению</span>
          <? endif; ?>
          <div class="form__field-input form__field-select prmu-required" id="experience">
            <select name="exp">
              <? foreach (Vacancy::EXPERIENCE as $key => $v): ?>
                <option value="<?=$key?>"<?=$viData->data->exp==$key?' selected':''?>><?=$v?></option>
              <? endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="form__field">
        <label class="form__field-label text__nowrap">Возраст <span class="text__red">*</span></label>
        <div class="form__field-content form__content-indent">
          <? if($viData->errors['age']): ?>
            <span class="prmu-error-mess">Значение "От" должно быть больше 14 и меньше значения "До"</span>
          <? endif; ?>
          <div class="form__content-flex">
            <div class="form__content-2">
              <input
                type="text"
                name="age_from"
                value="<?=$viData->data->agefrom?>"
                class="form__field-input form__field-input-date prmu-required prmu-check<?=($viData->errors['age']?' prmu-error':'')?>"
                data-params='{"limit":"2","regexp":"\\D+","parent_tag":".form__field-content","message":"Значение \"От\" должно быть больше 14 и меньше значения \"До\""}'
                autocomplete="off">
            </div>
            <div class="form__field-dash">-</div>
            <div class="form__content-2">
              <input
                type="text"
                name="age_to"
                value="<?=$viData->data->ageto?>"
                class="form__field-input form__field-input-date prmu-check<?=(($viData->errors['age']&&$viData->data->ageto)?' prmu-error':'')?>"
                data-params='{"limit":"2","regexp":"\\D+"}'
                autocomplete="off">
            </div>
          </div>
        </div>
      </div>
      <div class="form__field">
        <label class="form__field-label text__nowrap">Пол <span class="text__red">*</span></label>
        <div class="form__field-content form__content-indent">
          <? if($viData->errors['gender']): ?>
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
                <?=$viData->data->isman?' checked="checked"':''?>>
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
                <?=$viData->data->iswoman?' checked="checked"':''?>>
              <label for="gender_woman" class="form__checkbox-label">Женщина</label>
            </div>
          </div>
        </div>
      </div>
      <div class="form__field">
        <label class="form__field-label text__nowrap">Тип работы</label>
        <div class="form__field-content form__content-indent">
          <? if($viData->errors['istemp']): ?>
            <span class="prmu-error-mess">Поле обязательно к заполнению</span>
          <? endif; ?>
          <div class="form__field-input form__field-select prmu-required<?=($viData->errors['istemp']?' prmu-error':'')?>" id="work_type">
            <select name="istemp">
              <? foreach (Vacancy::WORK_TYPE as $key => $v): ?>
                <option value="<?=$key?>"<?=$viData->data->istemp==$key?' selected':''?>><?=$v?></option>
              <? endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="form__field">
        <label class="form__field-label text__nowrap">Дата <span class="text__red">*</span></label>
        <div class="form__field-content form__content-flex form__content-hint" id="period">
          <div class="form__content-2 form__content-indent">
            <label class="form__field-date">
              <input
                type="text"
                name="bdate"
                value="<?=Share::getDate($viData->data->crdate_unix,'d.m.Y')?>"
                class="form__field-input form__field-input-date prmu-required<?=($viData->errors['bdate']?' prmu-error':'')?>"
                data-params='{"parent_tag":".form__content-2","message":"Поле обязательно к заполнению"}'
                autocomplete="off">
            </label>
          </div>
          <div class="form__field-dash">-</div>
          <div class="form__content-2 form__content-indent">
            <label class="form__field-date">
              <input
                type="text"
                name="edate"
                value="<?=Share::getDate($viData->data->remdate_unix,'d.m.Y')?>"
                class="form__field-input form__field-input-date prmu-required<?=($viData->errors['edate']?' prmu-error':'')?>"
                data-params='{"parent_tag":".form__content-2","message":"Поле обязательно к заполнению"}'
                autocomplete="off">
            </label>
          </div>
        </div>
      </div>
      <button type="submit" class="btn__orange">Сохранить</button>
    </div>
    <input type="hidden" name="module" value="<?=$module?>">
  </form>
</div>
<script>
  var vacancyBeginDate = <?=$viData->data->crdate_unix?>;
  var vacancyEndDate = <?=$viData->data->remdate_unix?>;
</script>