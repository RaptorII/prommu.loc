<div class='row'>
  <div class='col-xs-12'>
    <br/><br/>
    <div class='header-021'>
      <b>Заявки на мои вакансии</b>
    </div>
    <br/>
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
                                    <img src="<?=Share::getPhoto($val['idusr'],2,$val['photo'],'small',$val['isman'])?>" alt="<?=$val['name']?>">
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