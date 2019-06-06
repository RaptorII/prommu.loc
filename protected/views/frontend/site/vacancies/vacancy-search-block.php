<? $cookieView = Yii::app()->request->cookies['vacancies_page_view']->value; ?>
<script type="text/javascript">
    var arSeo = <?=json_encode($seo)?>;
    var redirect = "<?=$redirect?>";
</script>
<?php if( !count($viData['vacs']) ): ?>
    <div class="psv__nothing">Нет подходящих вакансий</div>
<?php else: ?>
    <div class='psv__view-block hidden-xs'>
        <a class='psv__view-table <?=($cookieView=='table'?'active':'')?> js-g-hashint' href='<?=$this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'table') ?>' title='Отображать таблицей'></a>
        <a class="psv__view-list <?=($cookieView=='list'?'active':'')?> js-g-hashint" href='<?=$this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'list') ?>' title='Отображать списком'></a>
        <div class="clearfix"></div>
    </div>
    <?php // BM: list view ?>
    <?php if ($cookieView == 'list'): ?>
        <div class='psv__list-list hidden-xs'>
            <?php foreach ($viData['vacs'] as $key => $vac): ?>
                <div class="psv__list-item <?=($vac['ispremium']?'psv__list-item-premium':'')?>">
                    <div class="psv-list__logo">
                        <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>" class="psv-list__logo-link">
                            <img 
                                alt='Работодатель <?= $vac['coname'] ?> prommu.com'
                                src="<?=Share::getPhoto($vac['uid'], 3, $vac['logo'], 'xmedium')?>">
                        </a>
                        <a class="psv-list__logo-name" href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>"><?=$vac['coname']?></a>
                        <span class="psv-list__logo-crdate js-g-hashint" title="Дата публикации">
                            Дата публикации:  <?=$vac['crdate']?></span>
                    </div>
                    <div class="psv-list__content">
                        <a href="<?= MainConfig::$PAGE_VACANCY . DS . $vac['id'] ?>" class="psv-list__title">
                            <?= $vac['posts'] ? join(', ', $vac['posts']) : '' ?>
                            <?php if( $vac['shour'] > 0 || $vac['sweek'] > 0 || $vac['smonth'] > 0 || $vac['svisit'] > 0 ): ?>
                                <?php if($vac['shour'] > 0): ?>
                                    - <span class="payment"><?=$vac['shour'] . ' руб/час'?></span>
                                <?php elseif($vac['sweek'] > 0): ?>
                                    - <span class="payment"><?=$vac['sweek'] . ' руб/неделю'?></span>
                                <?php elseif($vac['smonth'] > 0): ?>
                                    - <span class="payment"><?=$vac['smonth'] . ' руб/мес'?></span>
                                <?php elseif($vac['svisit'] > 0): ?>
                                    - <span class="payment"><?=$vac['svisit'] . ' руб/посещение'?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </a>
                        <div class="psv-list__param-block">
                            <div class="psv__param ico1">
                                <div class="psv-list__param-name">
                                    <b>Краткое описание вакансии:</b>
                                </div>
                                <div class="psv-list__param-val"><?=$vac['title']?></div>
                            </div>
                            <?  // эти константы взяты наобум по стилям   
                                if($vac['isman']) $sex = 6;
                                if($vac['iswoman']) $sex = 7; 
                                if($vac['isman'] && $vac['iswoman']) $sex = 2;
                            ?>
                            <div class="psv__param ico<?=$sex?>">
                                <div class="psv-list__param-name">
                                    <b>Пол:</b>
                                </div>
                                <div class="psv-list__param-val"><?
                                if($sex==2) echo "Мужчины и женщины";
                                elseif($sex==6) echo "Мужчины";
                                else echo "Женщины";
                                ?></div>
                            </div>
                            <div class="psv__param ico3">
                                <div class="psv-list__param-name">
                                    <b>Город:</b>
                                </div>
                                <div class="psv-list__param-val">
                                <?php
                                    echo join(', ', $vac['city']).'.';
                                ?>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <div class="psv__param ico4">
                                <div class="psv-list__param-name">
                                    <b>Вид занятости:</b>
                                </div>
                                <div class="psv-list__param-val"><?=$vac['istemp'] ? 'Постоянная' : 'Временная'?></div>
                            </div>
                            <div class="psv__param ico5">
                                <div class="psv-list__param-name">
                                    <b>Открыта по:</b>
                                </div>
                                <div class="psv-list__param-val"><?=$vac['remdate']?></div>
                            </div>                                    
                        </div>
                        <div class="psv-list__content-btns">
                            <a href="<?=MainConfig::$PAGE_VACANCY . DS . $vac['id']?>" class="psv-list__link">Подробнее</a>
                            <? if(Share::isApplicant()): ?>
                                <a href="javascript:void(0)" class="psv-list__link psv-list__responce-btn" data-id="<?=$vac['id']?>">ОТКЛИКНУТЬСЯ</a>
                            <? endif; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php endforeach;?>
            <script type="text/javascript">
                var $G_PAGE_VIEW = 2;
                var G_DEF_LOGO = '<?= MainConfig::$DEF_LOGO_EMPL ?>';
            </script>
        </div>
        <? // mob version ?>
        <div class="row psv__table-list hidden-sm hidden-md hidden-lg hidden-xl">
            <?php $cnt=1; ?>
            <?php foreach($viData['vacs'] as $key => $vac): ?>
                <div class="col-xs-12 col-sm-6 col-md-4 psv__table-item">
                    <a href="<?= MainConfig::$PAGE_VACANCY . DS . $vac['id'] ?>" class="psv-table__title<?=($vac['ispremium']?' psv-table__title-premium js-g-hashint" title="Премиум вакансия"':'"')?>><?php 
                        if( $vac['shour'] > 0 || $vac['sweek'] > 0 || $vac['smonth'] > 0 || $vac['svisit'] > 0 ){
                            if($vac['shour'] > 0)
                                echo join(', ', $vac['posts']) . " - " . $vac['shour'] . ' руб/час';
                            elseif($vac['sweek'] > 0)
                                echo join(', ', $vac['posts']) . " - " . $vac['sweek'] . ' руб/неделю';
                            elseif($vac['smonth'] > 0)
                                echo join(', ', $vac['posts']) . " - " . $vac['smonth'] . ' руб/мес';
                            elseif($vac['svisit'] > 0)
                                echo join(', ', $vac['posts']) . " - " . $vac['svisit'] . ' руб/посещение';
                        }?>
                    </a>
                    <div class="psv__table-block <?=($vac['ispremium']?'psv__table-premium':'')?>">
                        <div class="psv-table__param-block">
                            <?  // эти константы взяты наобум по стилям   
                                if($vac['isman']) $sex = 6;
                                if($vac['iswoman']) $sex = 7; 
                                if($vac['isman'] && $vac['iswoman']) $sex = 2;
                            ?>
                            <div class="psv__param ico<?=$sex?>">
                                <div class="psv-table__param-name">
                                    <b>Пол:</b>
                                </div>
                                <div class="psv-table__param-val"><?
                                if($sex==2) echo "Мужчины и женщины";
                                elseif($sex==6) echo "Мужчины";
                                else echo "Женщины";
                                ?></div>
                            </div>
                            <div class="psv__param ico3">
                                <div class="psv-table__param-name">
                                    <b>Город:</b>
                                </div>
                                <?php
                                if (count($vac['city'])>2) {
                                    ?>
                                    <div class="psv-table__param-val psv-table__city"
                                         data-city="<?= join(', ', $vac['city']) ?>">
                                        <?php
                                        echo join(', ', array_slice($vac['city'], 0, 2)).'...';
                                        ?>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="psv-table__param-val">
                                        <?= join(', ', array_slice($vac['city'], 0, 2));?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="clearfix"></div>
                            </div>
                            <div class="psv__param ico4">
                                <div class="psv-table__param-name">
                                    <b>Вид занятости:</b>
                                </div>
                                <div class="psv-table__param-val"><?=$vac['istemp'] ? 'Постоянная' : 'Временная'?></div>
                            </div>
                            <div class="psv__param ico5">
                                <div class="psv-table__param-name">
                                    <b>Открыта по:</b>
                                </div>
                                <div class="psv-table__param-val"><?=$vac['remdate']?></div>
                            </div>
                            <div class="psv__param ico1">
                                <div class="psv-table__param-name">
                                    <b>Краткое описание вакансии:</b>
                                </div>
                                <div class="psv-table__param-val"><?=$vac['title']?></div>
                            </div>                                   
                        </div>
                        <div class="psv-table__logo">
                            <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>" class="psv-table__logo-link">
                                <img 
                                    alt='Работодатель <?= $vac['coname'] ?> prommu.com'
                                    src="<?=Share::getPhoto($vac['uid'], 3, $vac['logo'], 'small')?>">
                            </a>
                            <a class="psv-table__logo-name" href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>"><?=$vac['coname']?></a>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                        <a href="<?=MainConfig::$PAGE_VACANCY . DS . $vac['id']?>" class="psv-table__link">Подробнее</a>
                        <a href="javascript:void(0)" class="psv-table__link psv-list__responce-btn" data-id="<?=$vac['id']?>">ОТКЛИКНУТЬСЯ</a>
                    </div>                            
                </div>
                <?php if( $cnt % 2 == 0 ): ?>
                    <div class="psv__separator2"></div>
                <?php endif; ?>
                <?php if( $cnt % 3 == 0 ): ?>
                    <div class="psv__separator3"></div>
                <?php endif; ?>
                <?php $cnt++; ?>
            <?php endforeach; ?>
        </div>
    <?php // BM: table view  ?>
    <?php else: ?>
        <div class="row psv__table-list">
            <?php $cnt=1; ?>
            <?php foreach($viData['vacs'] as $key => $vac): ?>
                <div class="col-xs-12 col-sm-6 col-md-4 psv__table-item">
                    <div class="psv__table-item-wrap">
                        <a href="<?= MainConfig::$PAGE_VACANCY . DS . $vac['id'] ?>" class="psv-table__title<?=($vac['ispremium']?' psv-table__title-premium js-g-hashint" title="Премиум вакансия"':'"')?>><?php
                            if( $vac['shour'] > 0 || $vac['sweek'] > 0 || $vac['smonth'] > 0 || $vac['svisit'] > 0 ){
                                if($vac['shour'] > 0)
                                    echo join(', ', $vac['posts']) . " - " . $vac['shour'] . ' руб/час';
                                elseif($vac['sweek'] > 0)
                                    echo join(', ', $vac['posts']) . " - " . $vac['sweek'] . ' руб/неделю';
                                elseif($vac['smonth'] > 0)
                                    echo join(', ', $vac['posts']) . " - " . $vac['smonth'] . ' руб/мес';
                                elseif($vac['svisit'] > 0)
                                    echo join(', ', $vac['posts']) . " - " . $vac['svisit'] . ' руб/посещение';
                            }?>
                        </a>
                        <div class="psv__table-block <?=($vac['ispremium']?'psv__table-premium':'')?>">
                            <div class="psv-table__param-block">
                                <?  // эти константы взяты наобум по стилям
                                    if($vac['isman']) $sex = 6;
                                    if($vac['iswoman']) $sex = 7;
                                    if($vac['isman'] && $vac['iswoman']) $sex = 2;
                                ?>
                                <div class="psv__param ico<?=$sex?>">
                                    <div class="psv-table__param-name">
                                        <b>Пол:</b>
                                    </div>
                                    <div class="psv-table__param-val"><?
                                    if($sex==2) echo "Мужчины и женщины";
                                    elseif($sex==6) echo "Мужчины";
                                    else echo "Женщины";
                                    ?></div>
                                </div>
                                <div class="psv__param ico3">
                                    <div class="psv-table__param-name">
                                        <b>Город:</b>
                                    </div>
                                    <?php
                                        if (count($vac['city'])>2) {
                                            ?>
                                            <div class="psv-table__param-val psv-table__city"
                                                 data-city="<?= join(', ', $vac['city']) ?>">
                                                <?php
                                                echo join(', ', array_slice($vac['city'], 0, 2)).'...';
                                                ?>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="psv-table__param-val">
                                                <?= join(', ', array_slice($vac['city'], 0, 2));?>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="psv__param ico4">
                                    <div class="psv-table__param-name">
                                        <b>Вид занятости:</b>
                                    </div>
                                    <div class="psv-table__param-val"><?=$vac['istemp'] ? 'Постоянная' : 'Временная'?></div>
                                </div>
                                <div class="psv__param ico5">
                                    <div class="psv-table__param-name">
                                        <b>Открыта по:</b>
                                    </div>
                                    <div class="psv-table__param-val"><?=$vac['remdate']?></div>
                                </div>
                                <div class="psv__param ico1">
                                    <div class="psv-table__param-name">
                                        <b>Краткое описание вакансии:</b>
                                    </div>
                                    <div class="psv-table__param-val"><?=$vac['title']?></div>
                                </div>
                            </div>
                            <div class="psv-table__logo">
                                <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>" class="psv-table__logo-link">
                                    <img
                                        alt='Работодатель <?= $vac['coname'] ?> prommu.com'
                                        src="<?=Share::getPhoto($vac['uid'], 3, $vac['logo'], 'small')?>">
                                </a>
                                <a class="psv-table__logo-name" href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>"><?=$vac['coname']?></a>
                                <div class="clearfix"></div>
                            </div>
                            <div class="clearfix"></div>
                            <a href="<?=MainConfig::$PAGE_VACANCY . DS . $vac['id']?>" class="psv-table__link">Подробнее</a>
                            <a href="javascript:void(0)" class="psv-table__link psv-list__responce-btn" data-id="<?=$vac['id']?>">ОТКЛИКНУТЬСЯ</a>
                        </div>
                    </div>
                </div>
                <?php if( $cnt % 2 == 0 ): ?>
                    <div class="psv__separator2"></div>
                <?php endif; ?>
                <?php if( $cnt % 3 == 0 ): ?>
                    <div class="psv__separator3"></div>
                <?php endif; ?>
                <?php $cnt++; ?>
            <?php endforeach; ?>
        </div>
        <script type="text/javascript"> var $G_PAGE_VIEW = 1;</script>
    <?php endif; ?>
    <div class='paging-wrapp'>
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
    </div>      
<?php endif; ?>