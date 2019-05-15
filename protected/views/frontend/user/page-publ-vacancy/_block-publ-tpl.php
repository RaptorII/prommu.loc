<?
  $bUrl = Yii::app()->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCssFile($bUrl . MainConfig::$CSS . 'vacpub/style.css');
  $gcs->registerCssFile($bUrl . MainConfig::$CSS . 'dist/jquery-ui.min.css'); 
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/nicEdit.js', CClientScript::POS_END); 
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'vacpub/script.js', CClientScript::POS_END);

  $arCopy = $viData['copy_vacacancy']['vac'];
  $arCopyAttr = $viData['copy_vacacancy']['vacAttribs'];
  $bCopy = isset($viData['copy_vacacancy']);
  /*if($bCopy) // при дублировании можно выбрать 1 город из вакансии(если больше - надо дорабатывать скрипт)
  {
    $arC = reset($arCopy['city']);
    $viData['usercity'] = array(
        'id'=>$arC[4],
        'name'=>$arC[0],
        'id_co'=>$arC['id_co']
      );
  }*/
?>
<script type="text/javascript">
  var arPosts = <?=json_encode($viData['posts'])?>;
  var arSelectCity = <?=json_encode($viData['usercity'])?>;
</script>
<h2 class="add-vacancy__title">Добавить вакансию</h2>
<hr class="add-vacancy__line">
<form action="" method="post" id="F1vacancy" class="form-add-vacancy">
    <span class="fav-b1__title fav-b1__title-who"><h3>Какой специалист нужен и с какими параметрами?</h3></span>
    <?
    // Заголовок
    ?>
    <label class="fav__label">
      <span class="fav__hint fav__hint-vacname">Заголовок должен быть кратким и отображать суть вакансии. Пример: Раздача листовок</span>
      <input type="text" name="vacancy-title" class="fav__input fav__required" placeholder="Заголовок вакансии *" id="va-vac-title" value="<?=$bCopy ? $arCopy['title'] : ''?>">   
    </label>
    <?
    // Должность
    ?>
    <div class="fav__label fav__select-hint">
      <span class="fav__hint fav__hint-vacname">Выберите одну или несколько должностей, которые необходимы Вам для запуска проекта</span>
      <div class="fav__select-posts">
        <? if($bCopy): // дублировать ?>
          <span style="display:none">Должность *</span>
          <ul id="av-posts-select">
            <? foreach ($arCopy['post'] as $key => $post): ?>
              <li data-id="<?=$key?>"><?=$post?><i></i>
                <input type="hidden" name="posts[]" value="<?=$key?>">
              </li>
            <? endforeach; ?>
          </ul>
          <ul id="av-posts-list">
            <li data-id="0">
              <input type="text" name="p" autocomplete="off">
              <span id="add-new-vac">Новая должность</span>
            </li>
          </ul>
        <? else: ?>
          <span>Должность *</span>
          <ul id="av-posts-select"></ul>
          <ul id="av-posts-list">
            <li data-id="0">
              <input type="text" name="p" autocomplete="off">
              <span id="add-new-vac">Новая должность</span>
            </li>
          </ul>
        <? endif; ?>
      </div>
    </div>
    <?
    // Опыт работы
    ?>
    <div class="fav__label fav__select">
      <?php $arExp = array(1=>'Без опыта',2=>'До 1 месяца',3=>'От 1 до 3 месяцев',4=>'От 3 до 6 месяцев',5=>'От 6 до 12 месяцев',6=>'от 1 до 2-х лет',7=>'Более 2-х лет') ?>
    <input
      type="text" 
      name="str-expirience" 
      class="fav__input fav__required" 
      placeholder="Опыт работы *" 
      id="av-expirience" 
      value="<?=$bCopy ? $arExp[$arCopy['exp']] : ''?>"
      disabled>
    <div class="fav__veil" id="av-expirience-veil"></div>
    <ul class="fav__select-list" id="av-expirience-list">
      <i class="fav__select-list-icon">OK</i>
        <? foreach ($arExp as $k => $v): ?>
          <li>
            <input 
              type="radio"
              name="expirience"
              value="<?=$k?>"
              id="expirience-<?=$k?>"
              data-name="<?=$v?>"
              <?=$arCopy['exp']==$k ? 'checked' : ''?>>
            <label for="expirience-<?=$k?>"><table><td><p><?=$v?></p><td><b></b></table></label>
          </li>  
        <? endforeach; ?>
    </ul>
    </div>
    <?
    // Описание
    ?>
    <span class="fav__hint fav__hint-expirience">Опишите суть того, что предстоит делать персоналу, которого Вы ищете, согласно подсказок справа</span>
    <label class="fav__label fav__label-textarea">
      <span class="fav__hint fav__hint-vacname">Подсказка:<br>Механика: раздача листовок согласно адресной программы;<br>Возраст: от 18 лет;<br>Активные ответственные девушки.</span>
      <div id="av-requirements-panel"><span class="fav__input-name">Описание вакансии (требования) *</span></div>
      <textarea name="requirements" class="fav__input" id="av-requirements"><?echo ($bCopy ? $arCopy['requirements'] : '')?></textarea>
    </label>
    <?
    // Обязанности
    ?>
    <label class="fav__label fav__label-textarea">
      <span class="fav__hint fav__hint-vacname">Подсказка:<br>Раздача листовок для целевой аудитории: девушкам от 20 до 35 лет</span>
      <div id="av-duties-panel"><span class="fav__input-name">Обязанности</span></div>
      <textarea name="duties" class="fav__input" id="av-duties"><?echo ($bCopy ? $arCopy['duties'] : '')?></textarea>
    </label>
    <?
    // Условия
    ?>
    <label class="fav__label fav__label-textarea">
      <span class="fav__hint fav__hint-vacname">Подсказка:<br>1. Работа на улице в соответствии с графиком и адресом<br>2. Выплата зп по окончанию проекта на банковскую карту</span>
      <div id="av-conditions-panel"><span class="fav__input-name">Условия</span></div>
      <textarea name="conditions" class="fav__input" id="av-conditions"><?echo ($bCopy ? $arCopy['conditions'] : '')?></textarea>
    </label>
    <div class="fav__label" data-focus="Значение 'от' должно быть больше 14 и меньше значения 'до'">
      <?
      // Возраст
      ?>
      <div class="fav__left-block">
        <span class="fav__input-name">Возраст</span>
        <label class="fav__label-age">
          <span class="fav__input-name">от*</span>
          <input 
            type="text" 
            name="age-from" 
            class="fav__input fav__required fav__input-mini" 
            id="av-age-from"
            value="<?=$arCopy['agefrom']>0 ? $arCopy['agefrom'] : '' ?>">
        </label>
        <label class="fav__label-age">
          <span class="fav__input-name">до</span>
          <input 
            type="text" 
            name="age-to" 
            class="fav__input fav__input-mini" 
            id="av-age-to"
            value="<?=$arCopy['ageto']>0 ? $arCopy['ageto'] : '' ?>">
        </label>    
      </div>
      <?
      // Пол
      ?>
      <div class="fav__right-block">
        <span class="fav__input-name">Пол *</span>
        <input 
        type="checkbox" 
        name="mans" 
        class="fav__input fav__required fav__hidden" 
        id="av-sex-man" 
        value="1"
        <?=$arCopy['isman'] ? 'checked' : ''?>>
        <label class="fav__label-checkbox" for="av-sex-man">Мужчина</label>   
        <input 
        type="checkbox" 
        name="wonem" 
        class="fav__input fav__required fav__hidden" 
        id="av-sex-woman" 
        value="1"
        <?=$arCopy['iswoman'] ? 'checked' : ''?>>
        <label class="fav__label-checkbox" for="av-sex-woman">Женщина</label>   
      </div>
      <div class="clearfix"></div>
    </div>  
    <?
    // Прочее
    ?>
    <div class="fav__label fav__third-list">
      <div class="fav__third-item">
        <input 
        type="checkbox" 
        name="ismed" 
        class="fav__input fav__hidden" 
        id="av-med-note" 
        value="1"
        <?=$arCopy['ismed'] ? 'checked' : ''?>>
        <label class="fav__label-checkbox" for="av-med-note">Медкнижка</label>    
      </div>
      <div class="fav__third-item">
        <input 
        type="checkbox" 
        name="isavto" 
        class="fav__input fav__hidden" 
        id="av-auto"
        value="1"
        <?=$arCopy['isavto'] ? 'checked' : ''?>>
        <label class="fav__label-checkbox" for="av-auto">Автомобиль</label>    
      </div>
      <div class="fav__third-item">
        <input 
        type="checkbox" 
        name="smart" 
        class="fav__input fav__hidden" 
        id="av-smart" 
        value="1"
        <?=$arCopy['smart'] ? 'checked' : ''?>>
        <label class="fav__label-checkbox" for="av-smart">Смартфон</label>    
      </div>
    </div>
    <div class="fav__label">
      <div class="fav__both-item">
        <input 
        type="checkbox" 
        name="card-prommu" 
        class="fav__input fav__hidden" 
        id="av-card-prommu" 
        value="1"
        <?=$arCopy['cardPrommu'] ? 'checked' : ''?>>
        <label class="fav__label-checkbox" for="av-card-prommu">Наличие банковской карты Prommu</label>    
      </div>
      <div class="fav__both-item">
        <input 
        type="checkbox" 
        name="bank-card" 
        class="fav__input fav__hidden" 
        id="av-bank-card" 
        value="1"
        <?=$arCopy['card'] ? 'checked' : ''?>>
        <label class="fav__label-checkbox" for="av-bank-card">Наличие банковской карты</label>    
      </div>
      <div class="clearfix"></div>
    </div>
    <?
    // Заработная плата
    ?>
    <span class="fav-b1__title"><h3>Оплата</h3></span>
    <div class="fav__label fav__salary">
        <div class="fav__input-name fav__salary-name">Заработная плата *</div>
        <div class="fav__salary-inputs">
          <label class="fav__label-salary fav__quarter-item">     
            <input 
              type="text" 
              class="fav__input fav__required fav__input-salary" 
              name="salary-rub-hour"
              value="<?=$arCopy['shour']>0 ? $arCopy['shour'] : '' ?>">
            <span class="fav__input-name">руб / час</span>
          </label>
          <label class="fav__label-salary fav__quarter-item">
            <input 
              type="text" 
              class="fav__input fav__required fav__input-salary" 
              name="salary-rub-week"
              value="<?=$arCopy['sweek']>0 ? $arCopy['sweek'] : '' ?>">
            <span class="fav__input-name">руб / неделя</span>
          </label>
          <label class="fav__label-salary fav__quarter-item">
            <input 
              type="text" 
              class="fav__input fav__required fav__input-salary" 
              name="salary-rub-month"
              value="<?=$arCopy['smonth']>0 ? $arCopy['smonth'] : '' ?>">
            <span class="fav__input-name">руб / месяц</span>
          </label>
          <label class="fav__label-salary fav__quarter-item">
            <input
              type="text" 
              class="fav__input fav__required fav__input-salary" 
              name="salary-rub-visit"
              value="<?=$arCopy['svisit']>0 ? $arCopy['svisit'] : '' ?>">
            <span class="fav__input-name">руб / посещение</span>
          </label>
        </div>
    </div>
    <?
    // Сроки оплаты
    ?>
    <div class="fav__label fav__select">
      <?
        $paylims = '';
        foreach ($arCopyAttr as $key => $item)
            if (in_array($key, [130, 132, 133, 134, 163]))
                $paylims = $item['name'];
        $custPay = false;
        foreach ($arCopyAttr as $key => $item)
            if (in_array($key, [164])) {
                $paylims = $item['val'];
                $custPay = true;
            }
        $key = $this->ViewModel->isInArray($arCopyAttr, 'idpar', 131);
      ?>
      <input 
        type="text" 
        name="str-paylims" 
        class="fav__input fav__required" 
        placeholder="Сроки оплаты *" 
        id="av-paylims" 
        value="<?=$bCopy ? $paylims : ''?>"
        disabled>
      <input 
        type="hidden" 
        name="paylimit" 
        id="av-custom-paylims"
        value="<?=$custPay ? $paylims : ''?>">
      <div class="fav__veil" id="av-paylims-veil"></div>
      <ul class="fav__select-list" id="av-paylims-list">
        <i class="fav__select-list-icon">OK</i>
        <? foreach ($viData['userDictionaryAttrs'] as $val):?>
          <? if( $val['idpar']==131 ): ?>
            <li>
              <input 
              type="radio" 
              name="user-attribs[paylims]" 
              value="<?= $val['id'] ?>" 
              id="paylims-<?=$val['id']?>" 
              data-name="<?=$val['name']?>"
              <?=($arCopyAttr[$key]['id_attr']==$val['id'] ? 'checked' : '')?>>
              <label for="paylims-<?=$val['id']?>">
                <table><td><p><?=$val['name']?></p><td><b></b></table>
              </label>
            </li>
          <? endif; ?>
        <? endforeach;?>
        <? if ($custPay): // свой вариант ?>
          <li>
            <input type="radio" name="user-attribs[paylims]" value="164"
              id="paylims-164"
              checked>
            <label for="paylims-164">
              <table>
                <td><p><?=$paylims?></p>
                <td><b></b>
              </table>
            </label>
          </li>
        <? endif; ?>
        <li class="fav__exp-new">
          <input type="text" name="inp-new-term" placeholder="Свой вариант" id="inp-new-term">
          <span id="add-new-term">Добавить</span>
        </li>
      </ul>
    </div>
    <?
    // Коммент по ЗП
    ?>
    <div class="fav__label">
      <textarea name="user-attribs[salary-comment]" class="fav__input" placeholder="Комментарии"><?=$bCopy ? $arCopyAttr[165]['val'] : ''?></textarea>
    </div>

    <span class="fav-b1__title"><h3>АДРЕС И ВРЕМЯ РАБОТЫ</h3></span>
    <span class="fav__hint fav__hint-index"><span class="red">ВНИМАНИЕ !!! </span>Расширенный и точный список адресов, даты и время работы можно очень легко и удобно добавить в режиме редактирования вакансии после сохранения</span>
    <?
    // ГОРОД
    ?>
    <div class="fav__label">
      <span class="fav__hint fav__hint-vacname">Добавьте все города, в которых Вам необходимо набрать нужный персонал</span>
      <div class="fav__select-cities" id="multyselect-cities"></div>
    </div>
    <div class="fav__date row">
      <div class="fav__hint col-xs-6">Укажите дату начала работы по проекту без привязки к городу</div>
      <div class="fav__hint col-xs-6">Укажите дату последнего дня работы по проекту без привязки к городу</div>
      <div class="clearfix"></div>
       <?
      // Дата начала работ
      ?>     
      <div class="fav__label fav__select col-xs-6">
        <input type="text" name="cibdate" id="calendar_cibdate" class="fav__input fav__calendar fav__required" autocomplete="off">
      </div>
      <?
      // Дата окончания работ
      ?>
      <div class="fav__label fav__select col-xs-6">
          <input type="text" name="ciedate" id="calendar_ciedate" class="fav__input fav__calendar fav__required" autocomplete="off">
      </div>
    </div>
    <?
    // Временная работа или постоянная
    ?>
    <div class="fav__label fav__third-list">
      <div class="fav__third-item"><span class="fav__input-name">Тип работы:</span></div>
      <div class="fav__third-item">
        <input 
            type="radio" 
            name="busyType" 
            class="fav__input fav__hidden" 
            id="av-busy-temp" 
            value="0" 
            <?=($bCopy && $arCopy['istemp'] ? '' : 'checked')?>>
        <label class="fav__label-checkbox" for="av-busy-temp">Временная</label>
      </div>
      <div class="fav__third-item">
        <input 
          type="radio" 
          name="busyType" 
          class="fav__input fav__hidden" 
          id="av-busy-full" 
          value="1"
          <?=($bCopy && $arCopy['istemp'] ? 'checked' : '')?>>
        <label class="fav__label-checkbox" for="av-busy-full">Постоянная</label>
      </div>
    </div>
    <?
    // Публикация в соцсетях
    ?>
    <span class="fav-b1__title"><h3>ОПУБЛИКОВАТЬ ВАКАНСИЮ В ГРУППЕ PROMMU СОЦИАЛЬНЫХ СЕТЕЙ</h3></span>
    <div class="fav__both-item fav__item-social">
      <input 
        type="checkbox" 
        name="vk" 
        class="fav__input fav__hidden" 
        id="av-vk" 
        value="1"
        <?=substr($arCopy['repost'], 0, 1)=='1' ? 'checked' : ''?>>
      <label class="fav__label-checkbox" for="av-vk">Вконтакте</label>    
    </div>
    <div class="fav__both-item fav__item-social">
      <input 
        type="checkbox" 
        name="fb" 
        class="fav__input fav__hidden" 
        id="av-fb" 
        value="1"
        <?=substr($arCopy['repost'], 1, 1)=='1' ? 'checked' : ''?>>
      <label class="fav__label-checkbox" for="av-fb">Facebook</label>    
    </div>
    <div class="fav__both-item fav__item-social">
      <input 
      type="checkbox" 
      name="tl" 
      class="fav__input fav__hidden" 
      id="av-tl" 
      value="5"
      <?=substr($arCopy['repost'], 2, 1)=='1' ? 'checked' : ''?>>
      <label class="fav__label-checkbox" for="av-tl">Telegram</label>    
    </div>
    <br>
    <span class="fav__hint fav__hint-epilog">Все поля необходимо заполнить. После сохранения, в режим редактирования, вы сможете добавить дополнительную информацию и опубликовать вакансию.</span>
    <input type="hidden" name="blockpub" value="pub"/>
    <input type="hidden" name="save" value="1"/>
    <input type="hidden" name="date-autounpublish" id="av-pdate">
    <div class="center">
      <button type="submit" class="fav__submit prmu-btn prmu-btn_normal">
        <span>СОХРАНИТЬ</span>
      </button>
    </div>
    <script type="text/javascript">G_VARS.FLAG_PUB_VAC = 1;</script>
</form>