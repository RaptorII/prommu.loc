<script type="text/javascript">
  var arSeo = <?=json_encode($seo)?>;
  var arNewData = <?=json_encode($viData['count'])?>;
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
                                <img alt='Работодатель <?= $val['name'] ?> prommu.com' src='<?= DS . MainConfig::$PATH_EMPL_LOGO . DS . (!$val['logo'] ?  'logo.png' : ($val['logo']) . '100.jpg') ?>'>
                                <?php if($val['is_online']): ?>
                                  <span class="empl-list__item-onl"><span>В сети</span></span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <br>
                        <br>
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
                        <div class="com-rate" title="Положительный отрицательный">
                            Рейтинг: <span class="pos"><?= $val['rate'] ?></span>/<span
                                class="neg"><?= abs($val['rate_neg']) ?></span>
                            <div class="btn-rate-details btn-white-green-wr"><a href="#" data-id="<?= $val['id_user'] ?>" title="Показать подробный рейтинг">Подробнее</a>
                            </div>
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