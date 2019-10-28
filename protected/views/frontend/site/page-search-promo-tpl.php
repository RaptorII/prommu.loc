<?php
$cookieView = Yii::app()->request->cookies['srch_a_view']->value; // вид, сохраненный в куках

foreach ($viData['posts'] as $p)
    if ($p['postself'] && in_array($p['id'], $_GET['posts'])) {
        Yii::app()->clientScript->registerMetaTag('noindex,nofollow', 'robots', null, array());
        break;
    }

Yii::app()->getClientScript()->registerScriptFile('/theme/js/page-promo-search.min.js', CClientScript::POS_END);
$this->renderPartial('../site/page-search-promo-tpl_css');
?>
<div class='row page-search-ankety'>
    <div class="psa__veil"></div>
    <div class="col-xs-12">
        <?php if (Share::$UserProfile->type == 3): ?>
            <div class="psa__header">
                <h1 class="psa__header-name"><?= Share::$UserProfile->exInfo->name ?></h1>
                <a class='psa__btn psa__header-btn btn__orange' href='<?= MainConfig::$PAGE_VACPUB ?>'>Добавить вакансию</a>
            </div>
        <?php endif; ?>
    </div>
    <?
    /*
    *		FILTER
    */
    ?>
    <div class='col-xs-12 col-sm-4 col-md-3'>
        <div class="psa__filter-vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
        <form action="/ankety" id="F1Filter" method="get">
            <div class='filter'>
                <div class='psa__filter-block filter-surname'>
                    <div class='psa__filter-name opened'>Фамилия</div>
                    <div class='psa__filter-content opened'>
                        <input name='qs' type='text' title="Введите фамилию" value="<?= $viData['qs'] ?>"
                               class="psa__input">
                        <div class="psa__filter-btn btn__orange">ОК</div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class='psa__filter-block filter-cities'>
                    <div class='psa__filter-name opened'>Город</div>
                    <div class='psa__filter-content opened'>
                        <?
                        if (in_array(Share::$UserProfile->type, [2, 3])) {
                            $arRes = Yii::app()->db->createCommand()
                                ->select('c.id_co country')
                                ->from('user_city uc')
                                ->join('city c', 'uc.id_city=c.id_city')
                                ->where('id_user=:id_user', array(':id_user' => Share::$UserProfile->id))
                                ->queryRow();
                        } else {
                            $geo = new Geo();
                            $arRes = $geo->getUserGeo();
                        }
                        ?>
                        <div class="fav__select-cities" id="filter-city" data-city="<?= $arRes['country'] ?>">
                            <ul class="filter-city-select">
                                <? if (isset($_GET['cities'])): ?>
                                    <? foreach ($_GET['cities'] as $key => $id): ?>
                                        <li>
                                            <?= $_GET['template_url_params']['cities'][$key] ?>
                                            <b></b>
                                            <input type="hidden" name="cities[]" value="<?= $id ?>">
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
                <div class='psa__filter-block filter-positions'>
                    <div class='psa__filter-name opened'>Должность</div>
                    <div class='psa__filter-content opened'>
                        <div class='right-box'>
                            <?php
                            $sel = 0;
                            foreach ($viData['posts'] as $p)
                                if ($p['selected']) $sel++;
                            ?>
                            <input name='posts-all' type='checkbox' id="psa-posts-all"
                                   class="psa__checkbox-input"<?= sizeof($viData['posts']) == $sel ? ' checked' : '' ?>>
                            <label class='psa__checkbox-label' for="psa-posts-all">Выбрать все / снять все</label>
                            <?php foreach ($viData['posts'] as $val): ?>
                                <input name='posts[]' value="<?= $val['id'] ?>" type='checkbox'
                                       id="psa-posts-<?= $val['id'] ?>"
                                       class="psa__checkbox-input" <?= $val['selected'] ? 'checked' : '' ?>>
                                <label class='psa__checkbox-label'
                                       for="psa-posts-<?= $val['id'] ?>"><?= $val['name'] ?></label>
                            <?php endforeach; ?>
                        </div>
                        <span class="more-posts">Показать все</span>
                    </div>
                </div>
                <div class='psa__filter-block filter-sex'>
                    <div class='psa__filter-name opened'>Пол</div>
                    <div class='psa__filter-content opened'>
                        <div class='right-box'>
                            <input name='sm' type='checkbox' value='1' class="psa__checkbox-input"
                                   id="psa-sex-m" <?= Yii::app()->getRequest()->getParam('sm') ? 'checked' : '' ?>>
                            <label class="psa__checkbox-label" for="psa-sex-m">Мужской</label>
                            <input name='sf' type='checkbox' value='1' class="psa__checkbox-input"
                                   id="psa-sex-w" <?= Yii::app()->getRequest()->getParam('sf') ? 'checked' : '' ?>>
                            <label class="psa__checkbox-label" for="psa-sex-w">Женский</label>
                        </div>
                    </div>
                </div>
                <div class='psa__filter-block filter-salary'>
                    <div class='psa__filter-name opened'>Заработная плата</div>
                    <div class='psa__filter-content opened'>
                        <div class="psa__salary">
                            <span class="psa__salary-name">В час</span>
                            <div class="psa__salary-block">
                                <label class="psa__salary-label">
                                    <span>от</span>
                                    <input name=sphf type='text' value="<?= ($_GET['sr'] == 1 ? $_GET['sphf'] : '') ?>"
                                           class="psa__input">
                                </label>
                                <label class="psa__salary-label">
                                    <span>до</span>
                                    <input name='spht' type='text'
                                           value="<?= ($_GET['sr'] == 1 ? $_GET['spht'] : '') ?>" class="psa__input">
                                </label>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="psa__salary">
                            <span class="psa__salary-name">В неделю</span>
                            <div class="psa__salary-block">
                                <label class="psa__salary-label">
                                    <span>от</span>
                                    <input name=spwf type='text' value="<?= ($_GET['sr'] == 2 ? $_GET['spwf'] : '') ?>"
                                           class="psa__input">
                                </label>
                                <label class="psa__salary-label">
                                    <span>до</span>
                                    <input name='spwt' type='text'
                                           value="<?= ($_GET['sr'] == 2 ? $_GET['spwt'] : '') ?>" class="psa__input">
                                </label>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="psa__salary">
                            <span class="psa__salary-name">В месяц</span>
                            <div class="psa__salary-block">
                                <label class="psa__salary-label">
                                    <span>от</span>
                                    <input name=spmf type='text' value="<?= ($_GET['sr'] == 3 ? $_GET['spmf'] : '') ?>"
                                           class="psa__input">
                                </label>
                                <label class="psa__salary-label">
                                    <span>до</span>
                                    <input name='spmt' type='text'
                                           value="<?= ($_GET['sr'] == 3 ? $_GET['spmt'] : '') ?>" class="psa__input">
                                </label>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="psa__salary">
                            <span class="psa__salary-name">За посещение</span>
                            <div class="psa__salary-block">
                                <label class="psa__salary-label">
                                    <span>от</span>
                                    <input name=spvf type='text' value="<?= ($_GET['sr'] == 4 ? $_GET['spvf'] : '') ?>"
                                           class="psa__input">
                                </label>
                                <label class="psa__salary-label">
                                    <span>до</span>
                                    <input name='spmt' type='text'
                                           value="<?= ($_GET['sr'] == 4 ? $_GET['spmt'] : '') ?>" class="psa__input">
                                </label>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <input id='psa-salary-type' name='sr' type='hidden'
                               value="<?= ($_GET['sr'] ? $_GET['sr'] : 1) ?>">
                        <div class="psa__filter-btn btn__orange">ОК</div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class='psa__filter-block filter-age'>
                    <label class='psa__filter-name opened'>Возраст</label>
                    <div class='psa__filter-content opened'>
                        <div class="psa__age">
                            <label class="psa__age-label">
                                <span>от</span>
                                <input name=af type='text' value="<?= $_GET['af'] ?>" class="psa__input">
                            </label>
                            <label class="psa__age-label">
                                <span>до</span>
                                <input name='at' type='text' value="<?= $_GET['at'] ?>" class="psa__input">
                            </label>
                            <div class="clearfix"></div>
                            <div class="psa__filter-btn btn__orange">ОК</div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class='psa__filter-block filter-additional'>
                    <div class='psa__filter-name opened'>Дополнительно</div>
                    <div class='psa__filter-content opened'>
                        <div class='right-box'>
                            <input name='mb' type='checkbox' value='1' class="psa__checkbox-input"
                                   id="psa-med" <?= Yii::app()->getRequest()->getParam('mb') ? 'checked' : '' ?>>
                            <label class="psa__checkbox-label" for="psa-med">Наличие медкнижки</label>
                            <input name='avto' type='checkbox' value='1' class="psa__checkbox-input"
                                   id="psa-auto" <?= Yii::app()->getRequest()->getParam('avto') ? 'checked' : '' ?>>
                            <label class="psa__checkbox-label" for="psa-auto">Наличие автомобиля</label>
                            <input name='smart' type='checkbox' value='1' class="psa__checkbox-input"
                                   id="psa-smart" <?= Yii::app()->getRequest()->getParam('smart') ? 'checked' : '' ?>>
                            <label class="psa__checkbox-label" for="psa-smart">Наличие смартфона</label>
                        </div>
                    </div>
                </div>
                <div class='psa__filter-block filter-card'>
                    <div class='psa__filter-name opened'>Карта</div>
                    <div class='psa__filter-content opened'>
                        <div class='right-box'>
                            <input id='psa-pcard' name='cardPrommu' value='1'
                                   type='checkbox' <?= Yii::app()->getRequest()->getParam('cardPrommu') ? 'checked' : '' ?>
                                   class="psa__checkbox-input">
                            <label class='psa__checkbox-label' for="psa-pcard">Банковская карта Prommu</label>
                            <input id='psa-card' name='card' value='1'
                                   type='checkbox' <?= Yii::app()->getRequest()->getParam('card') ? 'checked' : '' ?>
                                   class="psa__checkbox-input">
                            <label class='psa__checkbox-label' for="psa-card">Банковская карта</label>
                        </div>
                    </div>
                </div>
                <div class='psa__filter-block filter-card'>
                    <div class='psa__filter-name opened'>Налоговый статус</div>
                    <div class='psa__filter-content opened'>
                        <div class='right-box'>
                            <? $self_employed = Yii::app()->getRequest()->getParam('self_employed'); ?>
                            <input
                                id='tax_status_1'
                                name='self_employed'
                                value='0'
                                type='radio'
                                class="psa__checkbox-input"
                                <?=(!$self_employed ? 'checked' : '')?>>
                            <label class='psa__checkbox-label' for="tax_status_1">Физическое лицо</label>
                            <input
                                id='tax_status_2'
                                name='self_employed'
                                value='1'
                                type='radio'
                                class="psa__checkbox-input"
                                <?=($self_employed ? 'checked' : '')?>>
                            <label class='psa__checkbox-label' for="tax_status_2">Самозанятый</label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?
    /*
    *		CONTENT
    */
    ?>
    <div class="col-xs-12 col-sm-8 col-md-9" id="content">
        <?php if (!count($viData['promo'])): ?>
            <div class="psa__nothing">Нет подходящих соискателей</div>
        <?php else: ?>
            <div class='psa__view-block hidden-xs'>
                <a class='psa__view-table <?= ($cookieView == 'table' ? 'active' : '') ?> js-g-hashint'
                   href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'table') ?>'
                   title='Отображать таблицей'></a>
                <a class="psa__view-list <?= ($cookieView == 'list' ? 'active' : '') ?> js-g-hashint"
                   href='<?= $this->ViewModel->replaceInUrl(Yii::app()->request->url, 'view', 'list') ?>'
                   title='Отображать списком'></a>
                <div class="clearfix"></div>
            </div>
            <div class='questionnaire'>
                <div>
                    <?= $this->ViewModel->declOfNum($count, array('Найдена', 'Найдено', 'Найдено')) ?>
                    <b><?= $count ?></b>
                    <?= $this->ViewModel->declOfNum($count, array('Анкета', 'Анкеты', 'Анкет')) ?>
                </div>
            </div>
            <?php
            /*
            *   BM: list-view
            */
            ?>
            <?php if ($cookieView == 'list'): ?>
                <div class='list-view'>
                    <?php foreach ($viData['promo'] as $key => $val):
                        //$usrPrflApp = new UserProfileApplic(); // !!! Разобраться, когда закончим с новой регой
                        //$usrPrflApp = $usrPrflApp->getProfileData($val['id_user'],$val['id_user']);
                        $photos = $usrPrflApp['userInfo']['userPhotos'];
                        ?>
                        <div class='appl-list-item-box'>
                            <div class='row'>
                                <div class='col-xs-12 col-sm-4'>
                                    <div class='company-logo-wrapp'>
                                        <div class='company-logo'>
                                            <a href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>'>
                                                <img
                                                    alt='Соискатель <?= $val['firstname'] . ' ' . $val['lastname'] ?> prommu.com' 
                                                    src="<?=Share::getPhoto($val['id_user'],2,$val['photo'],'xmedium',$val['sex'])?>">
                                                <?php if ($val['is_online']): ?>
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
                                            <b class='green'><?= $val['comm'] ?></b> / <b
                                                    class='red'><?= $val['commneg'] ?></b>
                                        </div>
                                    </div>
                                    <br>
                                    <?php if ($val['ismed'] === '1' || $val['ishasavto'] === '1' || !empty($val['attribs']['self_employed'])): ?>
                                        <div class="med-avto">
                                            <?php if ($val['ismed'] === '1'): ?>
                                                <div class="ico ico-avto js-g-hashint" title="Есть автомобиль"></div>
                                            <?php endif; ?>
                                            <?php if ($val['ishasavto'] === '1'): ?>
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
                                        <h3>Город:
                                          <?
                                            $arCities = $val['city']
                                          ?>
                                            <small><?= join(', ', $val['city']) ?></small>
                                        </h3>
                                    </div>
                                    <?php if ($val['metroes']): ?>
                                        <div class='place'>
                                            <h3>Метро:
                                                <small><?= join(', ', $val['metroes']) ?></small>
                                            </h3>
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
                *   BM: table-view
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
                                    <a href="<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['id_user'] ?>">
                                        <b class="name"><?= $val['firstname'] . ' ' . $val['lastname'] . ', ' . $val['age'] ?></b>
                                    </a>
                                    <div class='tmpl-ph1'>
                                        <div class='med-avto'>
                                            <? if ($val['ishasavto'] === '1'): ?>
                                                <div class='ico ico-avto js-g-hashint' title='Есть автомобиль'></div>
                                            <? endif; ?>
                                            <? if ($val['ismed'] === '1'): ?>
                                                <div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>
                                            <? endif; ?>
                                            <? if(!empty($val['attribs']['self_employed'])): ?>
                                              <div class="ico ico-self-employed js-g-hashint" title="Самозанятый"></div>
                                            <? endif; ?>
                                        </div>
                                    </div>
                                    <div class='hr'>
                                        <?php if (is_numeric($val['comm'])): ?>
                                            <div class='comments js-g-hashint'
                                                 title='Отзывы положительные | отрицательные'>
                                                <span class='r1'><?= $val['comm'] ?></span> | <?= $val['commneg'] ?>
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
                        <?php if ($i % 2 == 0): ?>
                            <div class="clear visible-sm"></div>
                        <?php endif; ?>
                        <?php if ($i % 3 == 0): ?>
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
                    'cssFile' => false
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class='col-xs-12' id="psa-seo-text" class="psa__content"><?php
        if ($this->ViewModel->getViewData()->pageH1)
            echo $this->ViewModel->getViewData()->pageMetaKeywords;
        elseif (isset($seo['meta_keywords']))
            echo $seo['meta_keywords'];
        ?></div>
</div>