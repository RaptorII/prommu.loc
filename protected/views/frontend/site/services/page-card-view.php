<?
  Yii::app()->getClientScript()->registerCssFile('/theme/css/services/services-card-page.css');
  Yii::app()->getClientScript()->registerScriptFile('/theme/js/services/services-card-page.js', CClientScript::POS_END);
?>
<?php if( $mess = Yii::app()->user->getFlash('Message') ): Yii::app()->user->setFlash('Message', '') ?>
    <div class="mess-box tmpl <?= $mess['type'] ?>" id="pr-card-mes"><?= $mess['message'] ?></div>
<?php endif; ?>
<?php
  if($id = Share::$UserProfile->id){
    $user = Yii::app()->db->createCommand()
        ->select('a.val phone, u.email, u.status')
        ->from('user u')
        ->leftJoin('user_attribs a', 'a.id_us=u.id_user AND a.id_attr=1')
        ->where('u.id_user=:idus', array(':idus' => $id))
        ->queryRow();

    if($user['status']==2){
      $res = Yii::app()->db->createCommand()
        ->select('r.firstname, r.lastname, r.birthday')
        ->from('resume r')
        ->where('r.id_user=:idus', array(':idus' => $id))
        ->queryRow();
    }
    if($user['status']==3){
      $res = Yii::app()->db->createCommand()
        ->select('e.firstname, e.lastname')
        ->from('employer e')
        ->where('e.id_user=:idus', array(':idus' => $id))
        ->queryRow();
    }
  }
  $arMonth = array(0=>'Январь',1=>'Февраль',2=>'Март',3=>'Апрель',4=>'Май',5=>'Июнь',6=>'Июль',7=>'Август',8=>'Сентябрь',9=>'Октябрь',10=>'Ноябрь',11=>'Декабрь');
  if(!empty($res['birthday']))
    $res['birthday'] = DateTime::createFromFormat('Y-m-d', $res['birthday'])->format('d.m.Y');
?>
<div class='row'>
  <div class='col-xs-12 prommucard'>
    <div class="container-fluid">
      <div class="row">
        <div class="hidden-xs hidden-sm col-md-4">
          <div class="pr-card__left-img"></div>
        </div>
        <div class="col-xs-12 col-md-8">
          <div class="row">
            <h1 class="pr-card__title">Заполните, пожалуйста, форму заявки</h1>
            <hr class="pr-card__hr">
            <form action="" id="F1cardOrder" method="POST" class="pr-card__form">
              <?if(!in_array($user['status'], [2,3])):?>
                <div class="pr-card__checkbox">
                  <input id="type" name="applicant" value="true" type="checkbox">
                  <label for="type" class="pr-card__checkbox-label">
                    <div class="pr-card__checkbox-switch" data-checked="Работодатель" data-unchecked="Соискатель"></div>
                  </label>
                </div>
                <div class="pr-card__form-select-block type">
                  <select name="post" class="pr-card__form-select pr-card__form-input required-inp" id="type-select">
                    <option value="" disabled selected>Должность *</option>
                    <?foreach ($viData['posts'] as $key => $val):?>
                      <option value="<?= $val['val'] ?>"><?= $val['val'] ?></option>
                    <?endforeach;?>
                  </select>         
                </div>
                <div class="clearfix"></div>
              <?endif;?>
              <?if($user['status']==2):?>
                <div class="pr-card__form-select-block">
                  <select name="post" class="pr-card__form-select pr-card__form-input required-inp">
                    <option value="" disabled selected>Должность *</option>
                    <?foreach ($viData['posts'] as $key => $val):?>
                      <option value="<?= $val['val'] ?>"><?= $val['val'] ?></option>
                    <?endforeach;?>
                  </select>
                </div>
                <input name="applicant" value="true" type="hidden">              
              <?endif;?>
              <label class="pr-card__form-label">
                <input type="text" name="fff"  data-field-check='name:Фамилия,empty' value="<?=$res['lastname']?>" placeholder="Фамилия *" class="pr-card__form-input required-inp" id="pr-card-surname"/>
              </label>
              <label class="pr-card__form-label">
                <input type="text" name="nnn"  data-field-check='name:Имя,empty' value="<?=$res['firstname']?>" placeholder="Имя *" class="pr-card__form-input required-inp" id="pr-card-name"/>
              </label>
              <label class="pr-card__form-label">
                <input type="text" name="ooo"  data-field-check='name:Отчество,empty' placeholder="Отчество *" class="pr-card__form-input required-inp" id="pr-card-patronymic"/>
              </label>
              <div class="pr-card__calendar">
                <div class="pr-card__calendar-label">Дата рождения *</div>
                <span class="pr-card__calendar-result" id="birthday-res"><?=$res['birthday']?></span>
                <table id="birthday" class="pr-card__calendar-table">
                  <thead>
                    <tr>
                      <td colspan="5">
                        <div class="pr-card__form-select-block">
                          <select class="pr-card__form-select pr-card__form-input">
                            <?foreach ($arMonth as $i => $month):?>
                              <option value="<?=$i?>"><?=$month?></option>
                            <?endforeach?>
                          </select>
                        </div>
                      </td>
                      <td colspan="2"><input type="text" value="" class="pr-card__form-input"></td>
                    </tr>
                    <tr><td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td></tr>
                  <tbody>
                </table>
                <input type="hidden" name="birthday" id="birthday-inp" class="required-inp" value="<?=$res['birthday']?>">
              </div>
              <label class="pr-card__form-label" data-info='Место рождения (индекс, страна, область, город, улица, № дома) *'>
                <input type="text" placeholder="Место рождения (индекс, страна, область, город, улица, № дома) *" name="bornplace" data-field-check='name:Место рождения,empty' class="pr-card__form-input required-inp"/>
              </label>
              <label class="pr-card__form-label">
                <input type="text" name="tel" id="pr-card-phone" data-field-check='name:Мобильный телефон,empty' placeholder="Мобильный телефон *" value="<?=$user['phone']?>" class="pr-card__form-input required-inp"/>
              </label>
              <label class="pr-card__form-label">
                <input type="text" name="email" id="pr-card-mail" data-field-check='name:Email,empty' placeholder="Email *" value="<?=$user['email']?>" class="pr-card__form-input required-inp"/>
              </label>
              <label class="pr-card__form-label">
                <input type="text" name="doc-ser" id="pr-card-serial" data-field-check='name:Серия паспорта,empty' placeholder="Серия, номер паспорта *" class="pr-card__form-input required-inp"/>
              </label>
              <label class="pr-card__form-label">
                <input type="text" name="doc-org" data-field-check='name:Кем выдан паспорт,empty' placeholder="Кем выдан паспорт *" class="pr-card__form-input required-inp"/>
              </label>
              <div class="pr-card__calendar">
                <div class="pr-card__calendar-label">Дата выдачи паспорта *</div>
                <span class="pr-card__calendar-result" id="passport-res"></span>
                <table id="passport" class="pr-card__calendar-table">
                  <thead>
                    <tr>
                      <td colspan="5">
                        <div class="pr-card__form-select-block">
                          <select class="pr-card__form-select pr-card__form-input">
                            <?foreach ($arMonth as $i => $month):?>
                              <option value="<?=$i?>"><?=$month?></option>
                            <?endforeach?>
                          </select>
                        </div>
                      </td>
                      <td colspan="2"><input type="text" value="" class="pr-card__form-input"></td>
                    </tr>
                    <tr><td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td></tr>
                  <tbody>
                </table>
                <input type="hidden" name="doc-date" id="passport-inp" class="required-inp">
              </div>
              <label class="pr-card__form-label">
                <input type="text" name="docorgcode" id="pr-card-code" data-field-filter='digits:-;max:8' placeholder="Код подразделения *" class="pr-card__form-input required-inp"/>
              </label>
              <label class="pr-card__form-label" data-info="Адрес прописки (индекс, страна, область, город, улица, № дома) *">
                <input type="text" name="regaddr" id="EdRegaddr" data-field-check='name:Адрес прописки,empty' placeholder="Адрес прописки (индекс, страна, область, город, улица, № дома) *" class="pr-card__form-input required-inp"/>
              </label>
              <div class="pr-card__form-btn" id="copy-index">АДРЕС ПРОЖИВАНИЯ СОВПАДАЕТ С АДРЕСОМ РЕГИСТРАЦИИ</div>
              <label class="pr-card__form-label" data-info="Адрес проживания (индекс, страна, область, город, улица, № дома) *">
                <input type="text" name="addr" id="EdAddr" data-field-check='name:Адрес фактического проживания,empty' placeholder="Адрес проживания (индекс, страна, область, город, улица, № дома) *" class="pr-card__form-input required-inp"/>
              </label>
              <label class="pr-card__form-label">
                <textarea name="comment" placeholder="Комментарий" class="pr-card__form-input pr-card__form-textarea"></textarea>
              </label>
              <input type="hidden" name="save" id="HiId" value="1"/>
            </form>


            <form method="post" enctype="multipart/form-data" id="F2upload" class="pr-card__upload">
              <h3 class="pr-card__upload-title">Загрузить скан копии Паспорта</h3>
              <div class="pr-card__upload-text">(главная страница и страница регистрации) Изображение загружаемое на сайт не должно превышать размер 1 Мб и быть максимум 2500х2500 пикселей. Тип файла для загрузки: JPG или PNG</div>
              <div id="DiImgs">
                <?php foreach ($viData['files'] as $key => $val): ?>
                    <div class="doc-scan uni-img-block">
                        <span class="uni-delete" data-id="<?= $key ?>"></span>
                        <a href="<?= $val['orig'] ?>" class="uni-img-link" target="_blank">
                            <img src="<?= $val['tb'] ?>" alt="" class="uni-img">
                        </a>
                    </div>
                <?php endforeach; ?>
              </div>
              <div class="clear"></div>
              <div class="message -red"></div>
              <input type="file" name="img" id="UplImg" multiple="true">
              <div class="btn-upload">
                  <button type="button" class="pr-card__upload-btn">Выбрать и загрузить</button>
                  <span class="loading-ico"><img src="/theme/pic/loading2.gif" alt=""></span>
              </div>
              <input type="hidden" name="MAX_FILE_SIZE" value="1048576"  multiple="true">
            </form>
            <span class="pr-card__btn off" id="pr-card-btn">ЗАКАЗАТЬ КОРПОРАТИВНУЮ КАРТУ PROMMU</span>
          </div>
        </div>
        <div class="clearfix"></div>        
      </div>
    </div>
  </div>
</div>
</div> <?   // content-block ?>
</div> <?   // container ?>
<hr class="pr-card__bottom-hr">
<div class="container"> <?   // container ?>
  <div class="content-block pr-card__bottom"> <?   // content-block ?>
    <div class="col-xs-12 col-sm-6 pr-card__bottom-img"></div>
    <div class="col-xs-12 col-sm-6 pr-card__bottom-descr">
      <h2 class="pr-card__bottom-title">Корпоративная карта "Промму"</h2>
      <hr class="pr-card__bottom-hr">
      <div class="pr-card__bottom-text">удобный и экономный способ своевременно получать зарплату и распоряжаться своими деньгами в любом месте и в любое время.</div>   
    </div>
    <div class="clearfix"></div>
    <div class="pr-card__bottom-epilog">Не нужно ехать в офис рекламного агентства или прямого работодателя, а все что нужно только наличие банкомата поблизости. Карту выпускает Московский кредитный банк, имеющий разветвленную сеть банкоматов и офисов обслуживания, и взымающий минимальную комиссию за снятие в банкоматах сторонних банков.</div>
    <div class="order-success-tpl tmpl">Ваш заказ оформлен успешно, наш менеджер свяжется с вами</div>
    <div class="doc-scan doc-scan-tpl">
        <span class="uni-delete"></span>
        <a href="" class="uni-img-link" target="_blank">
            <img src="" alt="" class="uni-img">
        </a>
    </div>

<script type="text/javascript">
  jQuery(function($){
    G_VARS.uniFiles = <?= json_encode($_SESSION['uploaduni']) ?>;
  });
</script>
<?if(!in_array($user['status'], [2,3])){
  require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/site/services/popups.php';
}?>