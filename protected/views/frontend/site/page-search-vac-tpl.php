<?php
$cookieView = Yii::app()->request->cookies['vacancies_page_view']->value; // вид, сохраненный в куках

foreach ($viData['posts'] as $p)
    if($p['postself'] && array_key_exists($p['id'], $_GET['post'])) {
        Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array());
        break;
    }

//if(!(MOBILE_DEVICE && !SHOW_APP_MESS)):

Yii::app()->getClientScript()->registerScriptFile('/theme/js/page-vac-search.min.js', CClientScript::POS_END);
$this->renderPartial('../site/page-search-vac-tpl_css');
?>
<div class='row page-search-vacancy'>
    <div class="psv__veil"></div>
    <div class="col-xs-12">
      <?php if (Share::isEmployer()): ?>
        <div class="psv__header">
          <h1 class="psv__header-name"><?= Share::$UserProfile->exInfo->name ?></h1>
          <?=VacancyView::createVacancyLink('Добавить вакансию','psv__btn psv__header-btn btn__orange')?>
        </div>
      <?php endif; ?>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-3">
        <div class="psv__filter-vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
        <form action="" id="F1Filter" method="get">
            <div class='filter'>
                <div class='filter-label filter-cities clearfix'>
                    <label class='filter-name opened'>Город</label>
                    <div class='filter-content opened'>
                        <?
                        if(in_array(Share::$UserProfile->type, [2,3])) {
                            $arRes = Yii::app()->db->createCommand()
                                ->select('c.id_co country')
                                ->from('user_city uc')
                                ->join('city c', 'uc.id_city=c.id_city')
                                ->where('id_user=:id_user', array(':id_user' => Share::$UserProfile->id))
                                ->queryRow();
                        }
                        else {
                            $geo = new Geo();
                            $arRes = $geo->getUserGeo();
                        }
                        ?>
                        <div class="fav__select-cities" id="filter-city" data-city="<?=$arRes['country']?>">
                            <ul class="filter-city-select">
                                <? if(isset($_GET['cities'])): ?>
                                    <? foreach ($_GET['cities'] as $key => $id): ?>
                                        <li>
                                            <?=$_GET['template_url_params']['cities'][$key]?>
                                            <b></b>
                                            <input type="hidden" name="cities[]" value="<?=$id?>">
                                        </li>
                                    <? endforeach; ?>
                                <? endif; ?>
                                <li data-id="0">
                                    <input type="text" name="fc" class="city-inp" autocomplete="off">
                                </li>
                            </ul>
                            <ul class="select-list"></ul>
                        </div>
                    </div>
                </div>
                <div class='filter-label filter-dolj'>
                    <label class='filter-name opened<?//= $viData['selected']['posts'] || $viData['poself'] ? 'opened' : '' ?>'>Должность</label>
                    <div class='filter-content opened<?//= $viData['selected']['posts'] || $viData['poself']? 'opened' : '' ?>'>
                        <div class='right-box'>
                            <?php
                            $sel = 0;
                            foreach($viData['posts'] as $p)
                                if($p['selected']) $sel++;
                            ?>
                            <input id='psv-posts-all' name='poall' type='checkbox' class="psv__checkbox-input"<?=sizeof($viData['posts'])==$sel ?' checked':''?>>
                            <label class='psv__checkbox-label psv__posts-all' for="psv-posts-all">Выбрать все / снять все</label>
                            <?php foreach($viData['posts'] as $val): ?>
                                <input name='post[<?= $val['id'] ?>]' type='checkbox' id="psv-posts-<?=$val['id']?>" class="psv__checkbox-input"<?= $val['selected'] ? ' checked' : '' ?>>
                                <label class='psv__checkbox-label' for="psv-posts-<?=$val['id']?>"><?= $val['name'] ?></label>
                            <?php endforeach; ?>
                        </div>
                        <?/*?>
                        <div class='self-dolj'>
                            <label>Свой вариант</label>
                            <input name='poself' type='text' value="<?= $viData['poself'] ?>" class="psv__input">
                            <div class="psv__filter-btn btn__orange">ОК</div>
                            <div class="clearfix"></div>
                        </div>
                        <?*/?>
                        <div class="clearfix"></div>
                        <span class="more-posts">Показать все</span>
                    </div>
                </div>

                <div class='filter-label filter-busy'>
                    <label class='filter-name opened<?//= $viData['bt'] == '1' || $viData['bt'] == '2' ? 'opened' : '' ?>'>Вид занятости</label>
                    <div class='filter-content opened<?//= $viData['bt'] == '1' || $viData['bt'] == '2' ? 'opened' : '' ?>'>
                        <div class='radio-box right-box'>
                            <input id='RB1busy' name='bt' type='radio' value='1' <?= $viData['bt'] == '1' ? 'checked' : '' ?> class="psv__radio-input">
                            <label for='RB1busy' class='psv__radio-label'>Временная</label>
                            <input id='RB2busy' name='bt' type='radio' value='2' <?= $viData['bt'] == '2' ? 'checked' : '' ?> class="psv__radio-input">
                            <label for='RB2busy' class='psv__radio-label'>Постоянная</label>
                            <input id='RB3busy' name='bt' type='radio' value='3' <?= $viData['bt'] == '3' || empty($viData['bt']) ? 'checked' : '' ?> class="psv__radio-input">
                            <label for='RB3busy' class='psv__radio-label'>Не важно</label>
                        </div>
                    </div>
                </div>
                <div class='filter-label filter-salary'>
                    <?php //$flag = $viData['sphf'] || $viData['spht'] || $viData['spwf'] || $viData['spwt'] || $viData['spmf'] || $viData['spmt'] ?>
                    <label class='filter-name opened<?//= $flag ? 'opened' : '' ?>'>Заработная плата</label>
                    <div class='filter-content opened<?//= $flag ? 'opened' : '' ?>'>
                        <div class="psv__salary">
                            <span class="psv__salary-name">В час</span>
                            <div class="psv__salary-block">
                                <label class="psv__salary-label">
                                    <span>от</span>
                                    <input name=sphf type='text' value="<?=($viData['sr']==1 ? $viData['sphf'] : '')?>" class="psv__input">
                                </label>
                                <label class="psv__salary-label">
                                    <span>до</span>
                                    <input name='spht' type='text' value="<?=($viData['sr']==1 ? $viData['spht'] : '')?>" class="psv__input">
                                </label>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="psv__salary">
                            <span class="psv__salary-name">В неделю</span>
                            <div class="psv__salary-block">
                                <label class="psv__salary-label">
                                    <span>от</span>
                                    <input name=spwf type='text' value="<?=($viData['sr']==2 ? $viData['spwf'] : '')?>" class="psv__input">
                                </label>
                                <label class="psv__salary-label">
                                    <span>до</span>
                                    <input name='spwt' type='text' value="<?=($viData['sr']==2 ? $viData['spwt'] : '')?>" class="psv__input">
                                </label>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="psv__salary">
                            <span class="psv__salary-name">В месяц</span>
                            <div class="psv__salary-block">
                                <label class="psv__salary-label">
                                    <span>от</span>
                                    <input name=spmf type='text' value="<?=($viData['sr']==3 ? $viData['spmf'] : '')?>" class="psv__input">
                                </label>
                                <label class="psv__salary-label">
                                    <span>до</span>
                                    <input name='spmt' type='text' value="<?=($viData['sr']==3 ? $viData['spmt'] : '')?>" class="psv__input">
                                </label>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="psv__salary">
                            <span class="psv__salary-name">За посещение</span>
                            <div class="psv__salary-block">
                                <label class="psv__salary-label">
                                    <span>от</span>
                                    <input name=spvf type='text' value="<?=($viData['sr']==4 ? $viData['spvf'] : '')?>" class="psv__input">
                                </label>
                                <label class="psv__salary-label">
                                    <span>до</span>
                                    <input name='spvt' type='text' value="<?=($viData['sr']==4 ? $viData['spvt'] : '')?>" class="psv__input">
                                </label>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <input id='psv-salary-type' name='sr' type='hidden' value="<?=($viData['sr'] ? $viData['sr'] : 1)?>">
                        <div class="psv__filter-btn btn__orange">ОК</div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class='filter-label filter-sex'>
                    <label class='filter-name opened<?//= $viData['sex'] == '1' || $viData['sex'] == '2' ? 'opened' : '' ?>'>Пол</label>
                    <div class='filter-content opened<?//= $viData['sex'] == '1' || $viData['sex'] == '2' ? 'opened' : '' ?>'>
                        <div class='radio-box right-box'>
                            <input name='sex' type='radio' value='1' class="psv__radio-input" id="psv-sex-m" <?= $viData['sex'] == '1' ? 'checked' : '' ?>>
                            <label class="psv__radio-label" for="psv-sex-m">Мужской</label>

                            <input name='sex' type='radio' value='2' class="psv__radio-input" id="psv-sex-w" <?= $viData['sex'] == '2' ? 'checked' : '' ?>>
                            <label class="psv__radio-label" for="psv-sex-w">Женский </label>

                            <input name='sex' type='radio' value='3' class="psv__radio-input" id="psv-sex-n" <?= $viData['sex'] == '3' || empty($viData['sex']) ? 'checked' : '' ?>>
                            <label class="psv__radio-label" for="psv-sex-n">Не важно</label>
                        </div>
                    </div>
                </div>
                <div class='filter-label filter-age'>
                    <label class='filter-name opened<?//= $viData['af'] || $viData['at'] ? 'opened' : '' ?>'>Возраст</label>
                    <div class='filter-content opened<?//= $viData['af'] || $viData['at'] ? 'opened' : '' ?>'>
                        <div class="psv__age">
                            <label class="psv__age-label">
                                <span>от</span>
                                <input name=af type='text' value="<?= $viData['af'] ?>" class="psv__input">
                            </label>
                            <label class="psv__age-label">
                                <span>до</span>
                                <input name='at' type='text' value="<?= $viData['at'] ?>" class="psv__input">
                            </label>
                            <div class="clearfix"></div>
                            <div class="psv__filter-btn btn__orange">ОК</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class='filter-label filter-smart'>
                    <label class='filter-name opened'>Смартфон</label>
                    <div class='filter-content opened'>
                        <div class='radio-box right-box'>
                            <input id='psv-smart' name='smart' value='1' type='checkbox' <?= Yii::app()->getRequest()->getParam('smart') ? 'checked' : '' ?> class="psv__checkbox-input">
                            <label class='psv__checkbox-label' for="psv-smart">Наличие смартфона</label>
                        </div>
                    </div>
                </div>
                <div class='filter-label filter-smart'>
                    <label class='filter-name opened'>Налоговый статус</label>
                    <div class='filter-content opened'>
                        <div class='radio-box right-box'>
                            <input
                                    id='tax_status_1'
                                    name='self_employed'
                                    value='0'
                                    type='radio'
                                    class="psv__checkbox-input"
                                <?= !Yii::app()->getRequest()->getParam('self_employed') ? 'checked' : '' ?>>
                            <label class='psv__checkbox-label' for="tax_status_1">Физическое лицо</label>
                            <input
                                    id='tax_status_2'
                                    name='self_employed'
                                    value='1'
                                    type='radio'
                                    class="psv__checkbox-input"
                                <?= Yii::app()->getRequest()->getParam('self_employed') ? 'checked' : '' ?>>
                            <label class='psv__checkbox-label' for="tax_status_2">Самозанятый</label>
                        </div>
                    </div>
                </div>
                <div class='filter-label filter-card'>
                    <label class='filter-name opened'>Карта</label>
                    <div class='filter-content opened'>
                        <div class='radio-box right-box'>
                            <input id='psv-pcard' name='pcard' value='1' type='checkbox' <?= Yii::app()->getRequest()->getParam('pcard') ? 'checked' : '' ?> class="psv__checkbox-input">
                            <label class='psv__checkbox-label' for="psv-pcard">Банковская карта Prommu</label>
                            <input id='psv-card' name='bcard' value='1' type='checkbox' <?= Yii::app()->getRequest()->getParam('bcard') ? 'checked' : '' ?> class="psv__checkbox-input">
                            <label class='psv__checkbox-label' for="psv-card">Банковская карта</label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-xs-12 col-sm-8 col-md-9" id="content">
        <?php if( !count($viData['vacs']) ): ?>
            <div class="psv__nothing">Нет подходящих вакансий</div>
        <?php else: ?>

        <a class="download__btn download__btn-flt-left download__btn-inst"
           href='/theme/pdf/Instruction-PROMMU-com-app-vac.pdf'
           target="_blank"
           title="Скачать иструкцию пользования сервисом PROMMU.com">
            <span class="btn-inst__txt">
                Инструкция <br> по поиску вакансий
            </span>
        </a>
        <div class="clearfix"></div>

        <div class='psv__view-block hidden-xs'>
            <a class='psv__view-table <?=($cookieView=='table'?'active':'')?> js-g-hashint' href='<?=$this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'table') ?>' title='Отображать таблицей'></a>
            <a class="psv__view-list <?=($cookieView=='list'?'active':'')?> js-g-hashint" href='<?=$this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'list') ?>' title='Отображать списком'></a>
            <div class="clearfix"></div>
        </div>
        <?php // BM: list view ?>
        <?php if ($cookieView == 'list'): ?>
        <div class='psv__list-list hidden-xs hidden-sm'>

            <?php foreach ($viData['vacs'] as $key => $vac): ?>
                <div class="psv__list-item <?=($vac['ispremium']?'psv__list-item-premium':'')?>">
                    <div class="psv-list__logo">
                        <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>" class="psv-list__logo-link">
                            <img
                                    alt='Работодатель <?= $vac['coname'] ?> prommu.com'
                                    src="<?=Share::getPhoto($vac['uid'], 3, $vac['logo'], 'xmedium')?>">
                        </a>
                        <a class="psv-list__logo-name" href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vac['uid']?>"><?=$vac['coname']?></a>

                        <span class="psv-list__logo-crdate js-g-hashint" title="Дата публикации">Дата публикации: <?=$vac['crdate']?></span>
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
                                <div class="psv-list__param-val"><?=join(', ', $vac['city'])?></div>
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
        <div class="row psv__table-list hidden-md hidden-lg hidden-xl">
            <?php $cnt=1; ?>
            <?php foreach($viData['vacs'] as $key => $vac): ?>
            <div class="col-xs-12 col-sm-6 psv__table-item">
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
    <?php // BM: table view  ?>
    <?php else: ?>
    <div class="row psv__table-list">
        <?php $cnt=1; ?>
        <?php foreach($viData['vacs'] as $key => $vac): ?>
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 psv__table-item">
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
            'cssFile' => false
        ));
        ?>
    </div>
<?php endif; ?>
</div>
<div class='col-xs-12' id="psv-seo-text" class="psv__content"><?php
    if($this->ViewModel->getViewData()->pageH1)
        echo $this->ViewModel->getViewData()->pageMetaKeywords;
    elseif(isset($seo['meta_keywords']))
        echo $seo['meta_keywords'];
    ?></div>
</div>