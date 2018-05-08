<meta name="robots" content="noindex">
<?php if( $viData['error'] ): ?>
  <div class="comm-mess-box"><?= $viData['message'] ?></div>
<?php else: ?>
  <div class='row'>
    <div class='col-xs-12'>
        <?php if( $IS_OWN ): ?>
        <?php else: ?>
            <h2>Рейтинг пользователя <?= $Profile->exInfo->name ?></h2>
        <?php endif; ?>

      <div class='header-021 -green'>
        Общий рейтинг
        <span class='star'></span>
        <?= $viData['rating']['countRate'] ?>
      </div>
      <br />
      <p>Рейтинг Работодателя показывает его порядочность и отношение к работникам, которых Работодатель набирал на свои проекты. Рейтинг выставляется Соискателем после завершения работы по вакансии, и вычисляется по всем вакансиям, на которые Работодатель набирал персонал</p>
      <br />
      <div class='row'>
        <div class='col-xs-12'>
          <table class='rate'>
            <?php foreach ($viData['rating']['pointRate'] as $key => $val): ?>
              <tr>
                <td class='val'>
                  <?= $val[0] - $val[1] ?> (
                  <span class='good' title='отлично'><?= $val[0] ?></span>
                  /
                  <span class='bad' title='плохо'><?= $val[1] ?></span>
                  )
                </td>
                <td class='progress'>
                  <div class='progr-line <?= $val[0] > $val[1] ? 'progress-green' : 'progress-red' ?>' style="width: <?= $val[0] - $val[1] == 0 ? 0 : abs($val[0] - $val[1]) * 100 / $viData['rating']['maxPointRate'] ?>%;">&nbsp;</div>
                  <div class='text'><?= $viData['rating']['rateNames'][$key] ?></div>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
      <br />
      <br />

      <?php if( $IS_OWN && $viData['rateByUser'] ): ?>
          <div class='header-021 -green'>
            Рейтинг выставленный соискателями
          </div>
          <br />
          <div class='row'>
                <?php foreach ($viData['rateByUser'] as $key => $val): ?>
                    <?php $debug && ($debug++); !$debug && $debug = 1;  ?>
                    <div class="rate-wrapper col-xs-12 col-sm-6 col-lg-4">
                        <div class="rate-block clearfix <?= array_values($val)[0]['new'] < 0 ? '-new' : '' ?> <?= in_array($debug, [1,2]) ? '-new':'' ?>">
                            <div class="inner">
                                <?php if( array_values($val)[0]['new'] < 0 || in_array($debug, [1,2]) ): ?>
                                  <div class="new-labl">Новый</div>
                                <?php endif; ?>
                                <div class="logo">
                                    <a href="<?= MainConfig::$PAGE_PROFILE_COMMON . DS . array_values($val)[0]['idus'] ?>">
                                        <img src="<?= $this->ViewModel->getHtmlLogo(array_values($val)[0]['photo'], ViewModel::$LOGO_TYPE_APPLIC) ?>" alt="">
                                    </a>
                                </div>
                                <div class="company">
                                    <div class="fio">
                                        <a href="<?= MainConfig::$PAGE_PROFILE_COMMON . DS . array_values($val)[0]['idus'] ?>" class="black-green">
                                            <?= array_values($val)[0]['fio'] ?>
                                        </a>
                                    </div>
                                    <div class="rates">
                                        <?php foreach ($val as $key2 => $val2): ?>
                                            <div class="point <?= "p" . $val2['point'] ?> js-g-hashint -js-g-hintleft" title="Оценка <?= (int)$val2['point'] === 1 ? 'положительная' : ((int)$val2['point'] === 0 ? 'нейтральная' : 'отрицательная') ?>"><?= $viData['rating']['rateNames'][$key2] ?></div><br />
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
          </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>
