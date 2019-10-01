<? $cookieView = Yii::app()->request->cookies['srch_a_view']->value; ?>
<script type="text/javascript">
    var arSeo = <?=json_encode($seo)?>;
    var redirect = "<?=$redirect?>";
</script>
<?php if( !count($viData['promo']) ): ?>
    <div class="psa__nothing">Нет подходящих соискателей</div>
<?php else: ?>
    <div class='psa__view-block hidden-xs'>
        <a class='psa__view-table <?=($cookieView=='table'?'active':'')?> js-g-hashint' href='<?=$this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'table') ?>' title='Отображать таблицей'></a>
        <a class="psa__view-list <?=($cookieView=='list'?'active':'')?> js-g-hashint" href='<?=$this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'list') ?>' title='Отображать списком'></a>
        <div class="clearfix"></div>
    </div>
    <div class='questionnaire'>
        <div>
            <?= $this->ViewModel->declOfNum($count, array('Найдена', 'Найдено', 'Найдено')) ?>
            <b><?= $count ?></b>
            <?= $this->ViewModel->declOfNum($count, array('Анкета', 'Анкеты', 'Анкет'))?>
        </div>
    </div>
    <?php
    /*
    *
    *   BM: list-view
    *
    */
    ?>
    <?php if($cookieView== 'list'): ?>
        <div class='list-view'>
            <?php foreach ($viData['promo'] as $key => $val): ?>
                <div class='appl-list-item-box'>
                    <div class='row'>
                        <div class='col-xs-12 col-sm-4'>
                            <div class='company-logo-wrapp'>
                                <div class='company-logo'>
                                    <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'>
                                        <img 
                                            alt='Соискатель <?= $val['firstname'] . ' ' . $val['lastname'] ?> prommu.com' 
                                            src="<?=Share::getPhoto($val['id_user'],2,$val['photo'],'xmedium',$val['sex'])?>">
                                        <?php if($val['is_online']): ?>
                                            <span class="promo-list__item-onl"><span>В сети</span></span>
                                        <?php endif; ?> 
                                    </a>
                                </div>

                                <div class="alib__img">
                                  <? $i = 0;
                                  if (count($photos) > 1) { ?>
                                    <? foreach ($photos as $photo): ?>
                                      <?
                                      $srcImg = Share::getPhoto($val['id_user'], 3, $photo['photo'], 'small');
                                      ?>
                                      <? if ($i <= 1): ?>
                                        <span class="alib__img-wrap">

                                                              <img src="<?= $srcImg ?>" alt="Соискатель <?= $viData['userInfo']['name'] ?> prommu.com">
                                                          </span>
                                      <? endif; ?>
                                      <? if ($i > 1): ?>
                                        <a class="alib__img-link" href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>' data-title="Смотреть больше фото">
                                          <span class="alib__img-more" >+</span>
                                        </a>
                                        <? break; ?>
                                      <? endif; ?>
                                      <? $i++; ?>
                                    <? endforeach;
                                  } ?>
                                  <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class='col-xs-12 col-sm-8'>
                            <h2>
                                <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'><?= $val['firstname'] . ' ' . $val['lastname'] . ', ' . $val['age'] ?></a>
                            </h2>
                            <div class='charac clearfix'>
                                <div class='rate'>
                                    Рейтинг:
                                    <span class="js-g-hashint" title="Всего"><?=($val['rate'] + $val['rate_neg'])?></span>
                                    (<b class="-green js-g-hashint" title="Положительный"><?=$val['rate']?></b> 
                                    / <b class="-red js-g-hashint" title="Отрицательный"><?=$val['rate_neg']?></b>)
                                </div>
                                <div class='comments' title="положительные / отрицательные">
                                    Отзывы:
                                    <b class='green'><?= $val['comm'] ?></b> / <b class='red'><?= $val['commneg'] ?></b>
                                </div>
                            </div>
                            <br>
                            <?php if( $val['ismed'] === '1' || $val['ishasavto'] === '1' || !empty($val['attribs']['self_employed'])): ?>
                                <div class="med-avto">
                                    <?php  if( $val['ismed'] === '1' ): ?>
                                        <div class="ico ico-avto js-g-hashint" title="Есть автомобиль"></div>
                                    <?php endif; ?>
                                    <?php if( $val['ishasavto'] === '1' ): ?>
                                        <div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>
                                    <?php endif; ?>
                                    <? if(!empty($val['attribs']['self_employed'])): ?>
                                      <div class="ico ico-self-employed js-g-hashint" title="Самозанятый"></div>
                                    <? endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class='vacancies'>
                                <h3>Целевые вакансии:</h3>
                                <?php
                                $curr = array('руб/час', 'руб/нед', 'руб/мес', 'руб/пос',);
                                $j=0;
                                foreach ($val['post'] as $val2):
                                    if ($j<2) {
                                        $j++;
                                        ?>
                                        <?= $val2['name'] ?>
                                        <div class='price'>
                                            <?= $val2['pay'] . ' ' . $curr[$val2['pay_type']] ?>
                                        </div><br>
                                        <?php
                                    }
                                    if ($j==2){
                                        echo '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve" style="width: 20px; height: 20px;">

<path transform="rotate(90 256 256)" d="M90,256c0,91.755,74.258,166,166,166c91.755,0,166-74.259,166-166c0-91.755-74.258-166-166-166   C164.245,90,90,164.259,90,256z M462,256c0,113.771-92.229,206-206,206S50,369.771,50,256S142.229,50,256,50S462,142.229,462,256z    M199.955,168.598l32.263-32.107L352.154,257L232.218,377.51l-32.263-32.107L287.937,257L199.955,168.598z"/>

</svg>';
                                        $j++;
                                    }
                                    ?>
                                <?php endforeach; ?>
                                <?php
                                if ($j>=2) {
                                    echo'<div class="over__hint">';
                                    foreach ($val['post'] as $key2 => $val2): ?>
                                        <?= $val2['name']; ?>
                                        <div class='price'>
                                            <?= $val2['pay'] . ' ' . $curr[$val2['pay_type']] ?>
                                        </div><br>
                                    <?php
                                    endforeach;
                                    echo '</div>';
                                }

                                ?>

                            </div>
                            <div class='place'>
                                <h3>Город: <small><?=join(', ',$val['city'])?></small></h3>
                            </div>
                            <?php if( $val['metroes'] ): ?>
                                <div class='place'>
                                    <h3>Метро: <small><?=join(', ',$val['metroes'])?></small></h3>
                                </div>
                            <?php endif; ?>
                            <? if (!empty($val['time_on_site'])): ?>
                              <div class='place'>
                                <h3>На сайте: <small><?=$val['time_on_site']?></small></h3>
                              </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
    <?php
    /*
    *
    *   BM: table-view
    *
    */
    ?>
        <div class='row vacancy table-view'><?php
            $i = 1;
            foreach ($viData['promo'] as $key => $val): ?>
                <div class='col-xs-12 col-sm-6 col-md-4'>    
                    <div class='comm-logo-wrapp'>
                        <div class='comm-logo'>
                            <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user']?>">
                                <img 
                                    alt='<?="Соискатель {$val['firstname']} {$val['lastname']} prommu.com "?>'
                                    src="<?=Share::getPhoto($val['id_user'],2,$val['photo'],'xmedium',$val['sex'])?>">
                                <?php if($val['is_online']): ?>
                                    <span class="promo-list__item-onl"><span>В сети</span></span>
                                <?php endif; ?>
                            </a>
                            <br>
                            <br>
                            <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user']?>">
                                <b class="name"><?=$val['firstname'] . ' ' . $val['lastname'] . ', ' . $val['age']?></b>
                            </a>
                            <div class='tmpl-ph1'>
                                <div class='med-avto'>
                                    <?if($val['ishasavto'] === '1'):?>
                                        <div class='ico ico-avto js-g-hashint' title='Есть автомобиль'></div>
                                    <?endif;?>
                                    <?if($val['ismed'] === '1'):?>
                                        <div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>
                                    <?endif;?>
                                    <? if(!empty($val['attribs']['self_employed'])): ?>
                                      <div class="ico ico-self-employed js-g-hashint" title="Самозанятый"></div>
                                    <? endif; ?>
                                </div>
                            </div>
                            <div class='hr'>
                                <?php if( is_numeric($val['comm']) ): ?>
                                    <div class='comments js-g-hashint' title='Отзывы положительные | отрицательные'>
                                        <span class='r1'><?=$val['comm']?></span> | <?=$val['commneg']?>
                                    </div>
                                <?php endif; ?>
                                <div class='rate'>
                                    <span class="js-g-hashint" title="Всего"><?=($val['rate'] + $val['rate_neg'])?></span>
                                    (<span class="-green js-g-hashint" title="Положительный"><?=$val['rate']?></span> 
                                    / <span class="-red js-g-hashint" title="Отрицательный"><?=$val['rate_neg']?></span>)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if( $i % 2 == 0 ): ?>
                    <div class="clear visible-sm"></div>
                <?php endif; ?>
                <?php if( $i % 3 == 0 ): ?>
                    <div class="clear visible-md visible-lg"></div>
                <?php endif; ?>
                <?php
                    $i++;
                    endforeach;
        ?></div>
    <?php endif; ?> 
    <div class='paging-wrapp'>
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
<?php endif; ?>