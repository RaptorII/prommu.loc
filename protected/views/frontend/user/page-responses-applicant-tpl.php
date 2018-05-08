<?
/*
echo "<pre style='display:none'>";
print_r($viData); 
echo "</pre>";
*/
/*
<div class='row'>  
  <div class='col-xs-12'>
    <?php if($viData):?>
      <div class="responses">
        <div class="responses__header">
          <h1 class="responses__header-title">Выставить оценку / Оставить отзыв работодателю, у которого работали на вакансиях</h1>
        </div>
        <div class='filter btn-green-02-wr'>
          <a class="resp <?= (int)$activeFilterLink == 0 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'tab', '') ?>'>Мои заявки</a><a class="inv <?= (int)$activeFilterLink == 1 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'tab', 'invites') ?>'>Мои приглашения</a>
        </div>
        <div class="responses__list">
          <?php foreach($viData as $idus => $val): ?>
            <div class="responses__item">
              <a class="app-responses__item-title" href='<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $idus?>' target="_blank">
                <span class="app-responses__logo">
                    <img src="<?= Yii::app()->Controller->ViewModel->getHtmlLogo($val['logo'], ViewModel::$LOGO_TYPE_EMPL) ?>" alt="">
                </span>
                <span><?=$val['name']?></span>
              </a>
              <?php foreach ($val['resps'] as $id => $vac):?>
                <div class="app-responses__item-resps<?=$vac['status']=='4' ? ' active' : ''?>">
                  <div class="app-responses__content">
                    <span class="app-responses__cid js-hashint" title="номер заявки">(#<?=$vac['sid']?>) </span>
                    <?php if($vac['status'] == 6): ?>
                      <a class='black-orange js-hashint' href='<?=MainConfig::$PAGE_SETRATE . DS . $id?>' title="Оставить отзыв"><?= $vac['title'] ?></a>
                    <?php else: ?>
                      <span class='black-orange'><?= $vac['title'] ?></span>
                    <?php endif; ?>
                    <div class="app-responses__rdate js-hashint" title="Дата заявки"><?=$vac['rdate']?></div>
                    <div class="app-responses__bdate js-hashint" title='дата размещения вакансии'><?=$vac['bdate']?></div>
                  </div>
                  <div class="controls" data-sid="<?= $vac['sid'] ?>">
                    <?php if( $vac['status'] == 4 ): ?>
                      <div class="btn-green-02-wr"><a href="#" class="apply" data-status="Подтверждена обеими сторонами">Согласен работать</a></div>
                      <div class="btn-red-02-wr"><a href="#" class="js-cancel">Отклонить</a></div>
                    <?php endif; ?>
                    <?php if( $vac['status']==4 && (int)$activeFilterLink!=1 ): ?>
                      <span class="status hint js-hashint" title="Ваша заявка на вакансию подтверждена работодателем, нажмите согласен, если хотите работать на этой вакансии">Подтверждена</span>
                    <?php else: ?>
                      <span class="status hint"></span>
                    <?php endif; ?>
                    <?php if( $vac['status'] == 6 ): ?>
                      <a href="<?= MainConfig::$PAGE_SETRATE . DS . $id ?>" class="responses__btn">Оставить отзыв</a>
                    <?php endif;?>
                    <?php if( $vac['status'] < 4 ): ?>
                      <span> Заявка на вакансию подана </span>
                    <?php endif; ?>
                    <?php if( in_array($vac['status'], [5]) ): ?>
                      <span>Подтверждена обеими сторонами</span>
                    <?php endif; ?>
                    <?php if( in_array($vac['status'], [7]) ): ?>
                      <span>Вы выставили рейтинг по этой вакансии</span>
                    <?php endif; ?>
                  </div>
                </div>      
              <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
      </div>
      <br />
      <br />
      <?php
        // display pagination
        $this->widget('CLinkPager', array(
          'pages' => $pages,
          'htmlOptions' => array('class' => 'paging-wrapp'),
          'firstPageLabel' => '1',
          'prevPageLabel' => 'Назад',
          'nextPageLabel' => 'Вперед',
          'header' => '',
      )) ?>
    <?php else: ?>
      <div class="responses">
        <div class="responses__header">
          <h1 class="responses__header-title">Выставить оценку / Оставить отзыв работодателю, у которого работали на вакансиях</h1>
        </div>
        <div class='filter btn-green-02-wr'>
          <a class="resp <?= (int)$activeFilterLink == 0 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'tab', '') ?>'>Мои заявки</a><a class="inv <?= (int)$activeFilterLink == 1 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'tab', 'invites') ?>'>Мои приглашения</a>
        </div>
      </div> 
      <div class="reviews-lock">
        <h2 class="rev-lock__title">Уважаемый Соискатель,</h2>
        <p class="rev-lock__text">К сожалению Вы еще не были утверждены, ни одним Работодателем ни на одной вакансии.<br><br>Для того чтобы иметь возможность оставить отзыв или выставить Рейтинг - Вас должен утвердить Работодатель на опубликованную вакансию в Личном кабинете.<br><br>После завершения работы по выбранной вакансии Вы сможете оставить отзыв и оценить работодателя по вопросам которые больше всего интересуют соискателей временной работы - что в дальнейшем поможет другим Вашим коллегам и нашему сервису выявлять лучших либо блокировать недобросовестных Работодателей.</p>
        <br>
        <div class="row">
          <div class="col-xs-12 col-sm-6">
            <p class="rev-lock__text">Оцениваем Работодателя по таким вопросам:</p>
            <ul class="rev-lock__list">
              <li class="rev-lock__list-item"><span>Соблюдение сроков оплаты</span></li>
              <li class="rev-lock__list-item"><span>Размер оплаты</span></li>
              <li class="rev-lock__list-item"><span>Четкость постановки задач</span></li>
              <li class="rev-lock__list-item"><span>Четкость требований</span></li>
              <li class="rev-lock__list-item"><span>Контактность</span></li>
            </ul>
          </div>
          <div class="col-xs-12 col-sm-6"><div class="rev-lock__planet"></div></div>
        </div>
        <div class="rev-lock__logo"></div>
        <span class="rev-lock__signature">С наилучшими пожеланиями, команда Промму</span>   
      </div>
    <?php endif; ?>
  </div>
</div>
*/?>

 <div class='header-021'>
  <b>Мои заявки на вакансии</b>
  <!--<b class='-green'>8</b>-->
</div>
<br />
<br />

<div class='filter btn-green-02-wr'>
  <a class="resp <?= (int)$activeFilterLink == 0 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'tab', '') ?>'>Мои заявки</a><a class="inv <?= (int)$activeFilterLink == 1 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'tab', 'invites') ?>'>Мои приглашения</a>
</div> 
  <div class="responses">
    <?php foreach ($viData['resps'] as $key => $val): ?>
      <?php if( $val['status'] == 3 ) continue; ?>
      <div class="row <?= $val['status'] == '4' ? '-new' : '' ?>">
          <div class="border">
              <?php if( $val['status'] == '4' ): ?>
                  <div class="label-new">Новая</div>
              <?php endif; ?>
              <div class="border2">
                  <div class="border3">
                      <div class="inner">
                          <div class="col-xs-12 col-sm-5 empl">
                              <div class="logo">
                                  <img src="<?= Yii::app()->Controller->ViewModel->getHtmlLogo($val['logo'], ViewModel::$LOGO_TYPE_EMPL) ?>" alt="">
                              </div>
                            <div class="empl-data">
                                <div class='fio'>
                                  <a class='black-orange' href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['idusr'] ?>'><?= $val['name'] ?></a>
                                </div>
                              <span class="js-hashint" title="номер заявки">( #<?= $val['sid'] ?> )</span>
                              <span class='rdate js-hashint' title="Дата заявки"> <?= $val['rdate'] ?> </span>
                            </div>
                            <div class="hr"></div>
                          </div>

                          <div class="col-xs-12 col-sm-7">
                            <div class='title'>
                              <a class='black-orange' href='<?= MainConfig::$PAGE_VACANCY . DS . $val['id'] ?>'><?= $val['title'] ?></a>
                              <span class="bdate js-hashint" title='дата размещения вакансии'>(<?= $val['bdate'] ?>)</span>
                            </div>
                              <div class="controls" data-sid="<?= $val['sid'] ?>">
                                <?php if( $val['status'] == 4 ): ?>
                                  <div class="btn-green-02-wr"><a href="#" class="apply" data-status="Подтверждена обеими сторонами">Согласен работать</a></div>
                                  <!--<span class="status js-applied" style="display: none">Подтверждена обеими сторонами</span>-->
                                  <div class="btn-red-02-wr"><a href="#" class="js-cancel">Отклонить</a></div>
                                <?php endif; ?>
                                <?php if( $val['status'] == 4 && (int)$activeFilterLink != 1 ): ?>
                                  <span class="status hint js-hashint" title="Ваша заявка на вакансию подтверждена работодателем, нажмите согласен, если хотите работать на этой вакансии">Подтверждена</span>&nbsp;&nbsp;
                                <?php else: ?>
                                  <span class="status hint"></span>
                                <?php endif; ?>
                                <?php /*if( $val['status'] == 6 ): ?>
                                  <div class="btn-black-02-wr"><a href="<?= MainConfig::$PAGE_SETRATE . DS . $val['id'] ?>" class="comment">Оставить отзыв</a></div>&nbsp;&nbsp;
                                <?php endif;*/ ?>
                                <?php if( $val['status'] < 4 ): ?>
                                  <span class="status"> Заявка на вакансию подана </span>
                                <?php endif; ?>
                                <?php if( in_array($val['status'], [5]) ): ?>
                                  <span class="status">Подтверждена обеими сторонами</span>
                                <?php endif; ?>
                                <?php /*if( in_array($val['status'], [7]) ): ?>
                                  <span class="status">Вы выставили рейтинг по этой вакансии</span>
                                <?php endif;*/ ?>
                              </div>
                          </div>
                          <div class="clear"></div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    <?php endforeach; ?>
  </div>
  <br />
  <br />
  <?php
    // display pagination
    $this->widget('CLinkPager', array(
      'pages' => $pages,
      'htmlOptions' => array('class' => 'paging-wrapp'),
      'firstPageLabel' => '1',
      'prevPageLabel' => 'Назад',
      'nextPageLabel' => 'Вперед',
      'header' => '',
  )) ?>