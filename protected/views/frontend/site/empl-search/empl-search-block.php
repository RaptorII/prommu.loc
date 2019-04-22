<script type="text/javascript">
  var arSeo = <?=json_encode($seo)?>;
  var arNewData = <?=json_encode($viData['count'])?>;
  var redirect = "<?=$redirect?>";
</script>
<?php if( !count($viData['empls']) ): ?>
  <div class="pse__nothing">Нет подходящих компаний</div>
<?php else: ?>
  <div class='questionnaire'>
    <div>
      Найдено
      <b><?=sizeof($viData['count'])?></b>
      <span class='hidden-xs'>зарегистрированных</span>
      работодателей
    </div>
  </div>
  <?php /* BM: list view */ ?>
  <div class='list-view'>
    <?php foreach ($viData['empls'] as $key => $val): ?>
        <div class='company-list-item-box'>
            <div class='row'>
                <div class='col-xs-12 col-sm-3 col-lg-2'>
                    <div class='company-logo-wrapp'>
                        <div class='company-logo'>
                            <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'>
                                <img 
                                  alt='Работодатель <?= $val['name'] ?> prommu.com' 
                                  src="<?=Share::getPhoto(3, $val['logo'])?>">
                                <?php if($val['is_online']): ?>
                                  <span class="empl-list__item-onl"><span>В сети</span></span>
                                <?php endif; ?>
                            </a>
                        </div>

                    </div>


                    <div style="text-align: center;margin-top: 10px;margin-bottom: 10px;">
                        <? if ($val['is_online']): ?>
                            <span style="color:#abb820"><i style="
                display: inline-block;
                width: 8px;
                height: 8px;
                background: #abb820;
                border-radius: 50%;
                margin-right: 8px;
            "></i>В сети</span>
                        <? else: ?>

                            <span style="color:#D6D6D6"><i style="
                display: inline-block;
                width: 8px;
                height: 8px;
                background: #D6D6D6;
                border-radius: 50%;
                margin-right: 8px;
            "></i>Был(а) на сервисе: <?= date_format(date_create($val['mdate']), 'd.m.Y'); ?></span>
                        <? endif; ?>
                    </div>


                </div>
                <div class='col-xs-12 col-sm-9 col-lg-10'>
                    <div class='title-block'>
                        <div class='expirience'><?php /* $val['exp'] */ ?></div>
                        <h2>
                            <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'><?= $val['name'] ?></a>
                            <small>(№ <?= $val['id'] ?>)</small>
                        </h2>
                    </div>
                    <div class="rate-block">
                        <div class='rate'>
                          Рейтинг:
                          <span class="js-g-hashint" title="Всего"><?=($val['rate'] + $val['rate_neg'])?></span>
                          (<b class="-green js-g-hashint" title="Положительный"><?=$val['rate']?></b> 
                          / <b class="-red js-g-hashint" title="Отрицательный"><?=$val['rate_neg']?></b>)
                        </div>
                        <table class='rate hide-rate'>
                            <thead>
                            <tr class="rate-tpl">
                                <td class='val'>
                                    <span class="num"></span> (
                                    <span class='good' title='отлично'></span>
                                    /
                                    <span class='bad' title='плохо'></span>
                                    )
                                </td>
                                <td class='progress'>
                                    <div class='progr-line' style="">&nbsp;</div>
                                    <div class='text'><!-- ratename--></div>
                                </td>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <br>
                    <div class='place'>
                        <h3>
                            Город:
                            <small><?= join(', ', array_values($val['city'])) ?></small>
                        </h3>
                    </div>
                    <?php if ($val['metroes']): ?>
                        <div class='place'>
                            <h3>
                                Метро:
                                <small><?= join(', ', array_values($val['metroes'])) ?></small>
                            </h3>
                        </div>
                    <?php endif; ?>
                    <div class='type'>
                      <?php if(isset($val['tname'])): ?>
                        <h3>Работодатель: <small><?= $val['tname'] ?></small></h3>
                      <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-xs-12 col-md-8 col-md-push-4'>
                    <div class='btn-more btn-white-green-wr'>
                        <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'>Подробнее</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
  </div>
  <script type="text/javascript">G_VARS.DEF_LOGO_EMPL = '<?= MainConfig::$DEF_LOGO_EMPL ?>'</script>
  <br>
  <br>
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
<?php endif; ?>