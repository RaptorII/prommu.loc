<?php 
  $bUrl = Yii::app()->baseUrl;
  Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/vacpub/style.css');
  Yii::app()->getClientScript()->registerScriptFile($bUrl.'/jslib/nicedit/nicEdit.js', CClientScript::POS_END); 
  Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/vacpub/script.js', CClientScript::POS_END);
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
      <input type="text" name="vacancy-title" class="fav__input fav__required" placeholder="Заголовок вакансии *" id="va-vac-title">   
    </label>
    <?
    // Должность
    ?>
    <div class="fav__label fav__select-hint">
      <span class="fav__hint fav__hint-vacname">Выберите одну или несколько должностей, которые необходимы Вам для запуска проекта</span>
      <div class="fav__select-posts">
        <span>Должность *</span>
        <ul id="av-posts-select"></ul>
        <ul id="av-posts-list">
          <li data-id="0">
            <input type="text" name="p" autocomplete="off">
            <span id="add-new-vac">Новая должность</span>
          </li>
        </ul>
      </div>
    </div>
    <?
    // Опыт работы
    ?>
    <div class="fav__label fav__select">
      <?php $arExp = array(1=>'Без опыта',2=>'До 1 месяца',3=>'От 1 до 3 месяцев',4=>'От 3 до 6 месяцев',5=>'От 6 до 12 месяцев',6=>'От 1 года до 2-х',7=>'Более 2-х лет') ?>
    <input type="text" name="str-expirience" class="fav__input fav__required" placeholder="Опыт работы *" id="av-expirience" disabled>
    <div class="fav__veil" id="av-expirience-veil"></div>
    <ul class="fav__select-list" id="av-expirience-list">
      <i class="fav__select-list-icon">OK</i>
            <?php foreach ($arExp as $k => $v): ?>
                <li>
                    <input type="radio" name="expirience" value="<?=$k?>" id="expirience-<?=$k?>" data-name="<?=$v?>">
                    <label for="expirience-<?=$k?>"><table><td><p><?=$v?></p><td><b></b></table></label>
                </li>  
            <?php endforeach; ?>
    </ul>
    </div>
    <?
    // Описание
    ?>
    <span class="fav__hint fav__hint-expirience">Опишите суть того, что предстоит делать персоналу, которого Вы ищете, согласно подсказок справа</span>
    <label class="fav__label fav__label-textarea">
      <span class="fav__hint fav__hint-vacname">Подсказка:<br>Механика: раздача листовок согласно адресной программы;<br>Возраст: от 18 лет;<br>Активные ответственные девушки.</span>
      <div id="av-requirements-panel"><span class="fav__input-name">Описание вакансии (требования) *</span></div>
      <textarea name="requirements" class="fav__input" id="av-requirements"></textarea>     
    </label>
    <?
    // Обязанности
    ?>
    <label class="fav__label fav__label-textarea">
      <span class="fav__hint fav__hint-vacname">Подсказка:<br>Раздача листовок для целевой аудитории: девушкам от 20 до 35 лет</span>
      <div id="av-duties-panel"><span class="fav__input-name">Обязанности</span></div>
      <textarea name="duties" class="fav__input" id="av-duties"></textarea>
    </label>
    <?
    // Условия
    ?>
    <label class="fav__label fav__label-textarea">
      <span class="fav__hint fav__hint-vacname">Подсказка:<br>1. Работа на улице в соответствии с графиком и адресом<br>2. Выплата зп по окончанию проекта на банковскую карту</span>
      <div id="av-conditions-panel"><span class="fav__input-name">Условия</span></div>
      <textarea name="conditions" class="fav__input" id="av-conditions"></textarea>
    </label>
    <div class="fav__label" data-focus="Значение 'от' должно быть больше 14 и меньше значения 'до'">
      <?
      // Возраст
      ?>
      <div class="fav__left-block">
        <span class="fav__input-name">Возраст</span>
        <label class="fav__label-age">
          <span class="fav__input-name">от*</span>
          <input type="text" name="age-from" class="fav__input fav__required fav__input-mini" id="av-age-from">
        </label>
        <label class="fav__label-age">
          <span class="fav__input-name">до</span>
          <input type="text" name="age-to" class="fav__input fav__input-mini" id="av-age-to">
        </label>    
      </div>
      <?
      // Пол
      ?>
      <div class="fav__right-block">
        <span class="fav__input-name">Пол *</span>
        <input type="checkbox" name="mans" class="fav__input fav__required fav__hidden" id="av-sex-man" value="1">
        <label class="fav__label-checkbox" for="av-sex-man">Мужчина</label>   
        <input type="checkbox" name="wonem" class="fav__input fav__required fav__hidden" id="av-sex-woman" value="1">
        <label class="fav__label-checkbox" for="av-sex-woman">Женщина</label>   
      </div>
      <div class="clearfix"></div>
    </div>  
    <?
    // Прочее
    ?>
    <div class="fav__label fav__third-list">
      <div class="fav__third-item">
        <input type="checkbox" name="ismed" class="fav__input fav__hidden" id="av-med-note" value="1">
        <label class="fav__label-checkbox" for="av-med-note">Медкнижка</label>    
      </div>
      <div class="fav__third-item">
        <input type="checkbox" name="isavto" class="fav__input fav__hidden" id="av-auto" value="1">
        <label class="fav__label-checkbox" for="av-auto">Автомобиль</label>    
      </div>
      <div class="fav__third-item">
        <input type="checkbox" name="smart" class="fav__input fav__hidden" id="av-smart" value="1">
        <label class="fav__label-checkbox" for="av-smart">Смартфон</label>    
      </div>
    </div>
    <div class="fav__label">
      <div class="fav__both-item">
        <input type="checkbox" name="card-prommu" class="fav__input fav__hidden" id="av-card-prommu" value="1">
        <label class="fav__label-checkbox" for="av-card-prommu">Наличие банковской карты Prommu</label>    
      </div>
      <div class="fav__both-item">
        <input type="checkbox" name="bank-card" class="fav__input fav__hidden" id="av-bank-card" value="1">
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
            <input type="text" class="fav__input fav__required fav__input-salary" name="salary-rub-hour"/>
            <span class="fav__input-name">руб / час</span>
          </label>
          <label class="fav__label-salary fav__quarter-item">
            <input type="text" class="fav__input fav__required fav__input-salary" name="salary-rub-week"/>
            <span class="fav__input-name">руб / неделя</span>
          </label>
          <label class="fav__label-salary fav__quarter-item">
            <input type="text" class="fav__input fav__required fav__input-salary" name="salary-rub-month"/>
            <span class="fav__input-name">руб / месяц</span>
          </label>
          <label class="fav__label-salary fav__quarter-item">
            <input type="text" class="fav__input fav__required fav__input-salary" name="salary-rub-visit"/>
            <span class="fav__input-name">руб / посещение</span>
          </label>
        </div>
    </div>
    <?
    // Сроки оплаты
    ?>
    <div class="fav__label fav__select">
      <input type="text" name="str-paylims" class="fav__input fav__required" placeholder="Сроки оплаты *" id="av-paylims" disabled>
      <input type="hidden" name="paylimit" id="av-custom-paylims">
      <div class="fav__veil" id="av-paylims-veil"></div>
      <ul class="fav__select-list" id="av-paylims-list">
        <i class="fav__select-list-icon">OK</i>
        <?php foreach ($viData['userDictionaryAttrs'] as $val):?>
          <?php if( $val['idpar']==131 ): ?>
            <li>
              <input type="radio" name="user-attribs[paylims]" value="<?= $val['id'] ?>" id="paylims-<?=$val['id']?>" data-name="<?=$val['name']?>">
              <label for="paylims-<?=$val['id']?>">
                <table><td><p><?=$val['name']?></p><td><b></b></table>
              </label>
            </li>
          <?php endif; ?>
        <?php endforeach;?>
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
      <textarea name="user-attribs[salary-comment]" class="fav__input" placeholder="Комментарии"></textarea>
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
    <?
    // Дата начала работ
    ?>
    <div class="fav__label fav__select">
        <span class="fav__hint fav__hint-vacname">Укажите дату начала работы по проекту без привязки к городу</span>
        <input type="text" name="cibdate" class="fav__input fav__required" placeholder="Дата начала работ *" id="av-cibdate">
        <div class="fav__veil" id="av-cibdate-veil"></div>
        <div class="fav__calendar">
            <div id="av-begin-err">Дата начала не корректна</div>              
            <table id="calendar-begin" class="fav__calendar-table">
                <thead>
                    <tr><td class="month-left">‹<td colspan="5" class="month-name"><td class="month-right">›</tr>
                    <tr><td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс</tr>
                <tbody>
            </table>
        </div>
    </div>
    <?
    // Дата окончания работ
    ?>
    <div class="fav__label fav__select">
        <span class="fav__hint fav__hint-vacname">Укажите дату последнего дня работы по проекту без привязки к городу</span>  
        <input type="text" name="ciedate" class="fav__input fav__required" placeholder="Дата окончания работ *" id="av-ciedate">
        <div class="fav__veil" id="av-ciedate-veil"></div>
        <div class="fav__calendar">
            <div id="av-end-err">Дата окончания не корректна</div>
            <table id="calendar-end" class="fav__calendar-table">
                <thead>
                    <tr><td class="month-left">‹<td colspan="5" class="month-name"><td class="month-right">›</tr>
                    <tr><td>Пн<td>Вт<td>Ср<td>Чт<td>Пт<td>Сб<td>Вс</tr>
                <tbody>
            </table>
        </div>
    </div>
    <?
    // Временная работа или постоянная
    ?>
    <div class="fav__label fav__third-list">
      <div class="fav__third-item"><span class="fav__input-name">Тип работы:</span></div>
      <div class="fav__third-item">
        <input type="radio" name="busyType" class="fav__input fav__hidden" id="av-busy-temp" value="0" checked>
        <label class="fav__label-checkbox" for="av-busy-temp">Временная</label>
      </div>
      <div class="fav__third-item">
        <input type="radio" name="busyType" class="fav__input fav__hidden" id="av-busy-full" value="1">
        <label class="fav__label-checkbox" for="av-busy-full">Постоянная</label>
      </div>
    </div>
    <?
    // Публикация в соцсетях
    ?>
    <span class="fav-b1__title"><h3>ОПУБЛИКОВАТЬ ВАКАНСИЮ В ГРУППЕ PROMMU СОЦИАЛЬНЫХ СЕТЕЙ</h3></span>
    <div class="fav__both-item fav__item-social">
      <input type="checkbox" name="vk" class="fav__input fav__hidden" id="av-vk" value="1">
      <label class="fav__label-checkbox" for="av-vk">Вконтакте</label>    
    </div>
    <div class="fav__both-item fav__item-social">
      <input type="checkbox" name="fb" class="fav__input fav__hidden" id="av-fb" value="1">
      <label class="fav__label-checkbox" for="av-fb">Facebook</label>    
    </div>
    <div class="fav__both-item fav__item-social">
      <input type="checkbox" name="tl" class="fav__input fav__hidden" id="av-tl" value="5">
      <label class="fav__label-checkbox" for="av-tl">Telegram</label>    
    </div>
    <br>
    <span class="fav__hint fav__hint-epilog">Все поля необходимо заполнить. После сохранения, в режим редактирования, вы сможете добавить дополнительную информацию и опубликовать вакансию.</span>
    <input type="hidden" name="blockpub" value="pub"/>
    <input type="hidden" name="save" value="1"/>
    <input type="hidden" name="date-autounpublish" id="av-pdate">
    <button type="submit" class="fav__submit">сохранить</button>
    <script type="text/javascript">G_VARS.FLAG_PUB_VAC = 1;</script>
</form>