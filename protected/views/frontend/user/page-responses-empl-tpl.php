<?php 
/*
<div class='row'>

  <div class='col-xs-12'>
    <?php if($viData): ?>
      <div class="responses">
        <div class="responses__header">
          <h1 class="responses__header-title">Выставить оценку / Оставить отзыв персоналу, который работал на моих вакансиях</h1>
        </div>    
        <div class="responses__list">
          <?php foreach ($viData as $id => $vac): ?>
            <div class="responses__item">
              <a class='responses__item-title' href='<?=MainConfig::$PAGE_VACANCY . DS . $id?>' target="_blank"><?=$vac['title']?><span class="js-hashint responses__item-bdate" title="Дата публикации"><?=$vac['bdate']?></span><span class="responses__item-status js-hashint" title="Статус вакансии"><?=($vac['status'] ? 'Открытая вакансия' : 'Закрытая вакансия')?></span></a>
              <?php foreach ($vac['resps'] as $idus => $user):?>
                <div class="responses__item-resps<?=(($user['status']==0 && $user['isresponse']==1) ? ' active' : '')?>">
                  <div class="responses__resps-content">
                    <div class="responses__resps-logo">
                      <?php if( $user['id_vac'] ): ?>
                        <img src=<?echo DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$user['photo'] ? ($user['sex'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F) : $user['photo'].'100.jpg')?> alt="">
                        <span class="responses__cmplt-rate js-hashint" title="Рейтинг уже выставлен"></span>
                      <?php else: ?>
                        <a href="<?= MainConfig::$PAGE_SETRATE . DS . $id . DS . $idus ?>" class="js-hashint" title="Оставить отзыв соискателю">
                          <img src=<?echo DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$user['photo'] ? ($user['sex'] ? MainConfig::$DEF_LOGO : MainConfig::$DEF_LOGO_F) : $user['photo'].'100.jpg')?> alt="">
                        </a>
                      <?php endif; ?>
                    </div>
                    <div class="responses__resps-data">
                      <span class="js-hashint" title="номер заявки">(#<?=$user['sid']?>) </span>
                      <?php if( $user['id_vac'] ): ?>
                        <span class='black-orange'><?= $user['name'] ?></span>  
                      <?php else: ?>
                        <a class='black-orange js-hashint' href="<?= MainConfig::$PAGE_SETRATE . DS . $id . DS . $idus ?>" title="Оставить отзыв соискателю"><?= $user['name'] ?></a>  
                      <?php endif; ?>                 
                    </div>                    
                  </div>
                  <span class='responses__resps-date js-hashint' title="Дата заявки"> <?= $user['rdate'] ?> </span>
                  <div class="controls" data-sid="<?= $user['sid'] ?>">
                    <?php if( $user['status'] == '0'  ): ?>
                      <div class="btn-black-02-wr"><a href="#" class="view js-hashint" title="Отметить заявку как просмотренная">Просмотреть</a></div>
                    <?php endif; ?>
                    <?php if( in_array($user['status'], [6,7]) ): ?>
                        <?php if( $user['id_vac'] ): ?>
                          <span>Вы выставили рейтинг данному соискателю</span>
                        <?php else: ?>
                          <a href="<?= MainConfig::$PAGE_SETRATE . DS . $id . DS . $idus ?>" class="responses__btn js-hashint" title="Оставить отзыв соискателю">Оставить отзыв</a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if( $user['status'] == '1' || $user['status'] == '0' ): ?>
                      <div class="btn-green-02-wr">
                        <a href="#" class="apply js-hashint" title="Подтвердить заявку на вакансию">Утвердить</a>
                      </div>
                      <div class="btn-red-02-wr">
                        <a href="#" class="cancel js-hashint" title="Отклонить заявку на вакансию">Отклонить</a>
                      </div>
                    <?php endif; ?>
                    <?php if( $user['status'] != '4' && $user['status'] != 5 ): ?>
                      <span class="status hide hint js-hashint" title="Заявка на вакансию подтверждена, ожидайте ответа соискателя">Заявка на вакансию подтверждена</span>
                    <?php endif; ?>
                    <?php if( $user['isresponse'] == 1 && $user['status'] == 4 ): ?>
                      <div class="hint js-hashint" title="Заявка на вакансию подтверждена, ожидайте ответа соискателя">Заявка на вакансию подтверждена</div>
                    <?php elseif( $user['isresponse'] == 2 && in_array($user['status'], [2,4]) ): ?>
                      <div class="hint js-hashint" title="Вы отправили приглашение соискателю на вакансию, ожидайте его решения">Приглашение на вакансию отправлено</div>
                    <?php endif; ?>
                    <?php if( $user['isresponse'] == 1 && in_array($user['status'], [5]) ): ?>
                      <span>Заявка на вакансию подтверждена обеими сторонами</span>
                    <?php elseif( $user['isresponse'] == 2 && in_array($user['status'], [5]) ): ?>
                      <span>Приглашение на вакансию принято соискателем</span>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endforeach ?>
            </div>
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
      <div class="reviews-lock">
        <h2 class="rev-lock__title">Уважаемый работодатель,</h2>
        <p class="rev-lock__text">К сожалению Вы еще не опубликовали ни одной вакансии. (если вакансии есть опубликованные которые по времени еще актуальны - Вы еще не утвердили на свою вакансию ни одного Соискателя).<br><br>Для того чтобы иметь возможность оставить отзыв или выставить Рейтинг - Вам необходимо разместить вакансию в Личном кабинете и утвердить Соискателей, которые отозвались на нее.<br><br>После завершения работы по выбранной вакансии Вы сможете оставить отзыв и оценить всех работников по вопросам которые больше всего интересуют Работодателей - что в дальнейшем поможет другим Вашим коллегам и нашему сервису выявлять лучших или недобросовестных Соискателей.</p>
        <br>
        <div class="row rev-lock__emp">
          <div class="col-xs-12 col-sm-6">
            <p class="rev-lock__text">Оцениваем Соискателя по таким вопросам:</p>
            <ul class="rev-lock__list">
              <li class="rev-lock__list-item"><span>Качество выполненной работы</span></li>
              <li class="rev-lock__list-item"><span>Контактность</span></li>
              <li class="rev-lock__list-item"><span>Пунктуальность</span></li>
            </ul>
          </div>
          <div class="col-xs-12 col-sm-6">
            <div class="rev-lock__social"></div>
            <div class="rev-lock__planet"></div>
          </div>
        </div>
        <div class="rev-lock__logo"></div>
        <span class="rev-lock__signature">С наилучшими пожеланиями, команда Промму</span>   
      </div>
    <?php endif; ?>
  </div>
</div>
*/?>
<div class='row'>
  <div class='col-xs-12'>
    <div class='header-021'>
      <b>Заявки на мои вакансии</b>
    </div>
    <br />
    <br />
<?php if( $viData['resps'] ): ?>
    <div class="responses">
      <?php foreach ($viData['resps'] as $key => $val): ?>
        <?php if( $val['status'] == 3 ) continue; ?>
        <div class="row <?= $val['status'] == '0' && $val['isresponse'] == 1 ? '-new' : '' ?>">
            <div class="border">
                <?php if( $val['status'] == '0' && $val['isresponse'] == 1 ): ?>
                    <div class="label-new">Новая</div>
                <?php endif; ?>
                <div class="border2">
                    <div class="border3">
                        <div class="inner">
                            <div class="col-xs-12 col-sm-5 empl">
                                <div class="logo">
                                    <img src="<?= Yii::app()->Controller->ViewModel->getHtmlLogo($val['photo'], ViewModel::$LOGO_TYPE_APPLIC) ?>" alt="">
                                </div>
                              <div class="empl-data">
                                  <div class='fio'>
                                    <a class='black-orange' href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['idusr'] ?>'><?= $val['firstname'] ?> <?= $val['lastname'] ?></a>
                                  </div>
                                <span class="js-hashint" title="номер заявки">( #<?= $val['sid'] ?> )</span>
                                <span class='rdate js-hashint' title="Дата заявки"> <?= $val['rdate'] ?> </span>
                                <?php if( $val['vstatus'] ): ?>
                                    <span class='status js-hashint -opened' title="Опубликована" >Открытая</span>
                                <?php else: ?>
                                    <span class='status js-hashint' title="Снята с публикации">Закрытая</span>
                                <?php endif; ?>
                              </div>
                              <div class="hr"></div>
                            </div>

                            <div class="col-xs-12 col-sm-7 vac">
                              <div class='title'>
                                <a class='black-orange' href='<?= MainConfig::$PAGE_VACANCY . DS . $val['id'] ?>'><?= $val['title'] ?></a>
                                <span class="bdate js-hashint" title='дата размещения вакансии'>(<?= $val['bdate'] ?>)</span>
                              </div>
                                <div class="controls" data-sid="<?= $val['sid'] ?>">
                                  <?php if( $val['status'] == '0'  ): ?>
                                    <div class="btn-black-02-wr"><a href="#" class="view js-hashint" title="Отметить заявку как просмотренная">Просмотреть</a></div>
                                  <?php endif; ?>
                                  <?php /*if( in_array($val['status'], [6,7]) ): ?>
                                      <?php if( $val['id_vac'] ): ?>
                                        <div class="status">Вы выставили рейтинг данному соискателю</div>
                                      <?php else: ?>
                                        <div class="btn-black-02-wr"><a href="<?= MainConfig::$PAGE_SETRATE . DS . $val['id'] . DS . $val['idusr'] ?>" class="comment">Оставить отзыв</a></div>&nbsp;&nbsp;
                                      <?php endif; ?>
                                  <?php endif;*/ ?>
                                  <?php if( $val['status'] == '1' || $val['status'] == '0' ): ?>
                                    <div class="btn-green-02-wr"><a href="#" class="apply js-hashint" title="Подтвердить заявку на вакансию">Утвердить</a></div>
                                    <div class="btn-red-02-wr"><a href="#" class="cancel js-hashint" title="Отклонить заявку на вакансию">Отклонить</a></div>
                                  <?php endif; ?>
                                  <?php if( $val['status'] != '4' && $val['status'] != 5 ): ?>
                                    <span class="status hide hint js-hashint" title="Заявка на вакансию подтверждена, ожидайте ответа соискателя">Заявка на вакансию подтверждена</span>
                                  <?php endif; ?>
                                  <?php if( $val['isresponse'] == 1 && $val['status'] == 4 ): ?>
                                    <div class="status hint js-hashint" title="Заявка на вакансию подтверждена, ожидайте ответа соискателя">Заявка на вакансию подтверждена</div>
                                  <?php elseif( $val['isresponse'] == 2 && in_array($val['status'], [2,4]) ): ?>
                                    <div class="status hint js-hashint" title="Вы отправили приглашение соискателю на вакансию, ожидайте его решения">Приглашение на вакансию отправлено</div>
                                  <?php else: ?>
                                  <?php endif; ?>
                                  <?php if( $val['isresponse'] == 1 && in_array($val['status'], [5]) ): ?>
                                    <div class="status">Заявка на вакансию подтверждена обеими сторонами</div>
                                  <?php elseif( $val['isresponse'] == 2 && in_array($val['status'], [5]) ): ?>
                                    <div class="status">Приглашение на вакансию принято соискателем</div>
                                  <?php endif; ?>
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
<?php else: ?>
    <br />
    Нет заявок
<?php endif; ?>
    <br />
    <br />

    <?php // display pagination
      $this->widget('CLinkPager', array(
        'pages' => $pages,
        'htmlOptions' => array('class' => 'paging-wrapp'),
        'firstPageLabel' => '1',
        'prevPageLabel' => 'Назад',
        'nextPageLabel' => 'Вперед',
        'header' => '',
    )) ?>

  </div>
</div>