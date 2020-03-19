<? $module = 3; ?>
<div class="personal__area--capacity-name">Кто нужен</div>
<?
//
?>
<div class="module_info<?=$viData->error_moodule==$module?' block__hide':''?>">
  <a href="javascript:void(0)" class="personal__area--capacity-edit js-g-hashint" title="Редактировать"></a>
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
  <? if(!$viData->data->add_props): ?>
    <div class="form__field">
      <div class="form__content-flex form__field-first form__flex-vmiddle">
        <div class="form__content-indent form__content-hint">Дополнительные параметры не заполнены...</div>
        <div class="form__field-hint tooltip" title="Заполнив данные поля Вы сможете отсеять запросы по персоналу который не подходит под данные параметры, тем самым сэкономите время себе и соискателям"></div>
      </div>
    </div>
  <? endif; ?>
  <? if(isset($viData->data->properties['manh'])): ?>
    <div class="group ppe__field">
      <div class="group__about">Рост от (см)</div>
      <div class="group__info"><?=$viData->data->properties['manh']['value']?></div>
    </div>
  <? endif; ?>
  <? if(isset($viData->data->properties['weig'])): ?>
    <div class="group ppe__field">
      <div class="group__about">Вес от (кг)</div>
      <div class="group__info"><?=$viData->data->properties['weig']['value']?></div>
    </div>
  <? endif; ?>
  <? if(isset($viData->data->properties['hcolor'])): ?>
    <div class="group ppe__field">
      <div class="group__about">Цвет волос</div>
      <div class="group__info"><?=$viData->data->properties['hcolor']['value']?></div>
    </div>
  <? endif; ?>
  <? if(isset($viData->data->properties['hlen'])): ?>
    <div class="group ppe__field">
      <div class="group__about">Длина волос</div>
      <div class="group__info"><?=$viData->data->properties['hlen']['value']?></div>
    </div>
  <? endif; ?>
  <? if(isset($viData->data->properties['ycolor'])): ?>
    <div class="group ppe__field">
      <div class="group__about">Цвет глаз</div>
      <div class="group__info"><?=$viData->data->properties['ycolor']['value']?></div>
    </div>
  <? endif; ?>
  <? if(isset($viData->data->properties['chest'])): ?>
    <div class="group ppe__field">
      <div class="group__about">Объем груди</div>
      <div class="group__info"><?=$viData->data->properties['chest']['value']?></div>
    </div>
  <? endif; ?>
  <? if(isset($viData->data->properties['waist'])): ?>
    <div class="group ppe__field">
      <div class="group__about">Объем талии</div>
      <div class="group__info"><?=$viData->data->properties['waist']['value']?></div>
    </div>
  <? endif; ?>
  <? if(isset($viData->data->properties['thigh'])): ?>
    <div class="group ppe__field">
      <div class="group__about">Объем бедер</div>
      <div class="group__info"><?=$viData->data->properties['thigh']['value']?></div>
    </div>
  <? endif; ?>
  <br>
</div>
<?
//
?>
<form class="module_form<?=$viData->error_moodule==$module?' block__visible':''?>" method="post" data-params='{"ajax":"true"}'>
  <div class="form__field">
    <label class="form__field-label text__nowrap">Должность <span class="text__red">*</span></label>
    <div class="form__field-content form__content-indent form__content-hint">
      <? if($viData->errors['post']): ?>
        <span class="prmu-error-mess">Поле обязательно к заполнению</span>
      <? endif; ?>
      <div class="form__field-input form__field-select prmu-required" data-params='{"parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}' data-search="true" id="posts2">
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
      <div class="form__field-input form__field-select prmu-required" id="experience2">
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
            id="gender_man2"
            class="form__field-checkbox prmu-required"
            data-params='{"parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}'
            <?=$viData->data->isman?' checked="checked"':''?>>
          <label for="gender_man2" class="form__checkbox-label">Мужчина</label>
        </div>
        <div class="form__content-2">
          <input
            type="checkbox"
            name="gender[]"
            value="woman"
            id="gender_woman2"
            class="form__field-checkbox prmu-required"
            data-params='{"parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}'
            <?=$viData->data->iswoman?' checked="checked"':''?>>
          <label for="gender_woman2" class="form__checkbox-label">Женщина</label>
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
      <div class="form__field-input form__field-select prmu-required<?=($viData->errors['istemp']?' prmu-error':'')?>" id="work_type2">
        <select name="istemp">
          <? foreach (Vacancy::WORK_TYPE as $key => $v): ?>
            <option value="<?=$key?>"<?=$viData->data->istemp==$key?' selected':''?>><?=$v?></option>
          <? endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <div class="form__field form__field-additional">
    <div class="form__content-2 form__content-flex form__flex-vmiddle">
      <label class="form__field-label text__nowrap">Рост от (см)</label>
      <div class="form__field-content form__content-indent">
        <input
          type="text"
          name="attributes[manh]"
          value="<?=$viData->data->properties['manh']['value']?>"
          class="form__field-input prmu-check"
          data-params='{"limit":"3","regexp":"\\D+"}'
          autocomplete="off">
      </div>
    </div>
    <div class="form__content-2 form__content-flex form__flex-vmiddle">
      <label class="form__field-label text__nowrap">Вес от (кг)</label>
      <div class="form__field-content form__content-indent">
        <input
          type="text"
          name="attributes[weig]"
          value="<?=$viData->data->properties['weig']['value']?>"
          class="form__field-input prmu-check"
          data-params='{"limit":"3","regexp":"\\D+"}'
          autocomplete="off">
      </div>
    </div>
  </div>
  <div class="form__field form__field-additional">
    <div class="form__content-2 form__content-flex form__flex-vmiddle">
      <label class="form__field-label text__nowrap">Цвет волос</label>
      <div class="form__field-content form__content-indent">
        <div class="form__field-input form__field-select" id="hcolor">
          <select name="attributes[hcolor]">
            <option value="" <?=!isset($viData->data->properties['hcolor'])?'selected="selected" disabled="disabled"':''?>></option>
            <? foreach ($viData->attributes->lists['hcolor'] as $key => $v): ?>
              <option value="<?=$key?>"<?=$viData->data->properties['hcolor']['id']==$key?' selected':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form__content-2 form__content-flex form__flex-vmiddle">
      <label class="form__field-label text__nowrap">Длина волос</label>
      <div class="form__field-content form__content-indent">
        <div class="form__field-input form__field-select" id="hlen">
          <select name="attributes[hlen]">
            <option value="" <?=!isset($viData->data->properties['hlen'])?'selected="selected" disabled="disabled"':''?>></option>
            <? foreach ($viData->attributes->lists['hlen'] as $key => $v): ?>
              <option value="<?=$key?>"<?=$viData->data->properties['hlen']['id']==$key?' selected':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="form__field form__field-additional">
    <div class="form__content-2 form__content-flex form__flex-vmiddle">
      <label class="form__field-label text__nowrap">Цвет глаз</label>
      <div class="form__field-content form__content-indent">
        <div class="form__field-input form__field-select" id="ycolor">
          <select name="attributes[ycolor]">
            <option value="" <?=!isset($viData->data->properties['ycolor'])?'selected="selected" disabled="disabled"':''?>></option>
            <? foreach ($viData->attributes->lists['ycolor'] as $key => $v): ?>
              <option value="<?=$key?>"<?=$viData->data->properties['ycolor']['id']==$key?' selected':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form__content-2 form__content-flex form__flex-vmiddle">
      <label class="form__field-label text__nowrap">Объем груди</label>
      <div class="form__field-content form__content-indent">
        <div class="form__field-input form__field-select" id="chest">
          <select name="attributes[chest]">
            <option value="" <?=!isset($viData->data->properties['chest'])?'selected="selected" disabled="disabled"':''?>></option>
            <? foreach ($viData->attributes->lists['chest'] as $key => $v): ?>
              <option value="<?=$key?>"<?=$viData->data->properties['chest']['id']==$key?' selected':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="form__field form__field-additional">
    <div class="form__content-2 form__content-flex form__flex-vmiddle">
      <label class="form__field-label text__nowrap">Объем талии</label>
      <div class="form__field-content form__content-indent">
        <div class="form__field-input form__field-select" id="waist">
          <select name="attributes[waist]">
            <option value="" <?=!isset($viData->data->properties['waist'])?'selected="selected" disabled="disabled"':''?>></option>
            <? foreach ($viData->attributes->lists['waist'] as $key => $v): ?>
              <option value="<?=$key?>"<?=$viData->data->properties['waist']['id']==$key?' selected':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form__content-2 form__content-flex form__flex-vmiddle">
      <label class="form__field-label text__nowrap">Объем бедер</label>
      <div class="form__field-content form__content-indent">
        <div class="form__field-input form__field-select" id="thigh">
          <select name="attributes[thigh]">
            <option value="" <?=!isset($viData->data->properties['thigh'])?'selected="selected" disabled="disabled"':''?>></option>
            <? foreach ($viData->attributes->lists['thigh'] as $key => $v): ?>
              <option value="<?=$key?>"<?=$viData->data->properties['thigh']['id']==$key?' selected':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </div>
      </div>
    </div>
  </div>



  <div class="form__container">
    <button type="submit" class="btn__orange">Сохранить</button>
  </div>
  <input type="hidden" name="module" value="<?=$module?>">
</form>
