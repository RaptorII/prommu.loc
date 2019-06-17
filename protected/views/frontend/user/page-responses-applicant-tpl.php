<style type="text/css">
	#DiContent.page-responses .responses .row.-new .border{ margin: 0 }
	@media (min-width: 768px){
		#DiContent.page-responses .responses>div:nth-child(even) .row,
		#DiContent.page-responses .responses>div:nth-child(even) .row .inner{ background: #f2f2f2; }
	}
	.content-block{ position: relative; }
	.content-block.load:before{
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		bottom: 0;
		right: 0;
		background: rgba(255,255,255,.7) url(/theme/pic/vacancy/loading.gif) center center no-repeat;
		background-size: 80px;
		z-index: 21;
	}
</style>
<br/>
<div class='header-021'>
  <b>Мои заявки на вакансии</b>
</div>
<br/>
<div class='filter btn-green-02-wr'>
  <a class="resp <?= (int)$activeFilterLink == 0 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'tab', '') ?>'>Мои заявки</a><a class="inv <?= (int)$activeFilterLink == 1 ? 'active' : 'gray-green' ?>" href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'tab', 'invites') ?>'>Мои приглашения</a>
</div> 
  <div class="responses">
    <?php foreach ($viData['resps'] as $key => $val): ?>
      <?php if( $val['status'] == 3 ) continue; ?>
      <div class="col-xs-12">
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
                                  <img src="<?=Share::getPhoto($val['idusr'],3,$val['logo'])?>" alt="<?=$val['name']?>">
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
      </div>
    <?php endforeach; ?>
    <div class="clear"></div>
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