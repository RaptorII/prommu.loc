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
                            <?php if( $val['ismed'] === '1' || $val['ishasavto'] === '1' ): ?>
                                <div class="med-avto">
                                    <?php  if( $val['ismed'] === '1' ): ?>
                                        <div class="ico ico-avto js-g-hashint" title="Есть автомобиль"></div>
                                    <?php endif; ?>
                                    <?php if( $val['ishasavto'] === '1' ): ?>
                                        <div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class='vacancies'>
                                <h3>Целевые вакансии:</h3>
                                <?php
                                    $curr = array('руб/час', 'руб/нед', 'руб/мес', 'руб/пос');
                                    foreach ($val['post'] as $key2 => $val2):
                                ?>
                                    <?= $val2[0] ?>
                                    <div class='price'><?= $val2[1] . ' ' . $curr[$val2[2]] ?></div><br>
                                <?php endforeach; ?>
                            </div>
                            <div class='place'>
                                <h3>Город: <small><?=join(', ',$val['city'])?></small></h3>
                            </div>
                            <?php if( $val['metroes'] ): ?>
                                <div class='place'>
                                    <h3>Метро: <small><?=join(', ',$val['metroes'])?></small></h3>
                                </div>
                            <?php endif; ?>
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