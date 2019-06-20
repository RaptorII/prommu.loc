<?php
$cookieView = Yii::app()->request->cookies['srch_a_view']->value; // вид, сохраненный в куках

foreach ($viData['posts'] as $p)
    if ($p['postself'] && in_array($p['id'], $_GET['posts'])) {
        Yii::app()->clientScript->registerMetaTag('noindex,nofollow', 'robots', null, array());
        break;
    }
//if(!(MOBILE_DEVICE && !SHOW_APP_MESS)):
?>
<?php Yii::app()->getClientScript()->registerScriptFile('/theme/js/page-promo-search.min.js', CClientScript::POS_END); ?>
<style type="text/css">
#DiContent.page-ankety .page-search-ankety .psv__salary-label{width:50%;float:left;padding-left:35px;position:relative}.psv__salary{width:100%;display:table;margin-bottom:5px}.psv__salary-name{width:90px;display:table-cell;font-size:14px;color:#a0a0a0;vertical-align:middle;line-height:35px;text-align:left}.psv__salary-block{width:calc(100% - 90px);display:table-cell}#DiContent .page-search-ankety .psv__input,.psv__content,.psv__salary-name{font-family:RobotoCondensed-Regular,verdana,arial}#DiContent.page-ankety .page-search-ankety .filter .filter-label.filter-cities{padding-top:0}#DiContent.page-ankety .filter .filter-label:first-of-type{padding-top:10px}#DiContent.page-ankety .filter-salary .filter-content label{display:block;text-align:left}.psa__btn:before,.psa__checkbox-label:after,.psa__filter-btn:before,.psa__header-name:before,.psa__veil:before{content:''}#psa-additional,.psa__veil{display:none}.page-search-ankety .select2-container--default .select2-selection--multiple{width:100%;min-height:35px;padding:0 15px;background-color:transparent;border:1px solid #ebebeb;font-size:14px;color:#646464;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;border-radius:0}#DiContent .page-search-ankety .psa__input,#DiContent .psa__checkbox-label,.psa__content{font-family:RobotoCondensed-Regular,verdana,arial}.page-search-ankety .select2-container--default.select2-container--focus .select2-selection--multiple{border:1px solid #ebebeb;outline:0}.page-search-ankety .select2-container--default .select2-selection--multiple .select2-selection__choice{background-color:rgba(52,52,52,.6);color:#fff;border-color:#858585}#DiContent.page-vacancy .page-search-ankety .filter .filter-name,.psa__filter-name{border:1px solid #e3e3e3}#DiContent.page-vacancy .page-search-ankety .filter .filter-content label{font-size:14px;color:#a0a0a0;font-family:RobotoCondensed-Regular,verdana,arial;text-transform:none}.psa__filter-btn,.psa__filter-name{text-transform:uppercase;cursor:pointer}.page-search-ankety{position:relative}.psa__veil{position:absolute;top:0;right:0;bottom:0;left:0;z-index:3;background-color:rgba(255,255,255,.7)}.psa__veil:before{width:130px;height:130px;position:absolute;top:150px;left:-65px;background:url(/theme/pic/vacancy/loading.gif) no-repeat;margin-left:50%}.psa__nothing{color:#343434;font-size:18px}.psa__view-block{margin-bottom:15px}.psa__view-list,.psa__view-table{width:19px;height:15px;display:block;margin-left:8px;float:right;background:url(/theme/pic/vacancy/srch-vac-sprite.png) no-repeat}.psa__view-list{background-position:0 0}.psa__view-table{background-position:0 -30px}.psa__view-list.active{background-position:0 -15px}.psa__view-table.active{background-position:0 -45px}.psa__filter-name{display:block;position:relative;margin-bottom:10px;padding:5px 15px;font-size:14px}.psa__filter-name.opened{background:#e3e3e3;border-color:#e3e3e3}.psa__filter-name:not(.opened):hover{border-color:#abb820}.psa__filter-name:after{content:' ';position:absolute;right:15px;top:0;border:10px solid transparent;border-bottom:9px solid #e3e3e3}.psa__filter-name.opened:after{bottom:0;top:auto;border:10px solid transparent;border-top:9px solid #fff}.psa__filter-content{display:none;padding-bottom:35px;text-align:center}.psa__filter-content.opened{display:block}#DiContent .page-search-ankety .psa__input{height:35px;border:1px solid #ebebeb;background-color:transparent;padding:0 15px;color:#343434;font-size:14px}.psa__input:focus{outline:0}.psa__filter-btn{display:block;margin:10px 0 0;width:100px;max-width:220px;line-height:30px;text-align:center;background:#ff8300;color:#fff;position:relative;z-index:1;float:right;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}#DiContent .page-search-ankety .psa__btn,.psa__filter-btn:before{-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out}.psa__filter-btn:before{position:absolute;z-index:-1;top:0;left:0;right:0;bottom:0;background:#bbc823;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;transition:all .3s ease-out}.psa__filter-btn:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}#DiContent .psa__checkbox-input{display:none}#DiContent .psa__checkbox-label{display:block;position:relative;height:29px;line-height:29px;padding-right:35px;cursor:pointer;font-size:14px;color:#a0a0a0;text-transform:none}.psa__checkbox-label:after{display:block;position:absolute;width:30px;height:29px;right:0;top:0;background:url(/theme/pic/ico-form.png) 0 -116px no-repeat}.psa__checkbox-label:hover:after{background:url(/theme/pic/ico-form.png) 0 -435px no-repeat}input:checked+.psa__checkbox-label:after{background-position:0 -87px}.psa__content{margin-top:0;font-size:16px}#DiContent.page-ankety .page-search-ankety .filter{display:block}.filter-positions .psa__filter-content.opened{height:170px;position:relative;overflow:hidden}.more-posts{width:100%;position:absolute;bottom:0;left:0;color:#abb820;cursor:pointer;text-align:right;padding-right:55px;line-height:35px;font-size:16px;background:0 0;background:-moz-linear-gradient(top,transparent 0,#fff 50%,#fff 100%);background:-webkit-linear-gradient(top,transparent 0,#fff 50%,#fff 100%);background: url(/theme/pic/vacancy/trnspToWhiteGRT.png) 0 -65px repeat-x;background-size: 100px;}.more-posts:hover{font-weight:700}.psa__header{padding:20px 0 15px;border-bottom:1px solid #d6d6d6;margin-bottom:20px}.psa__header-name{margin:0 0 20px;display:block;color:#343434;font-size:18px;text-decoration:underline;vertical-align:middle;text-align:center}.psa__header-name:before{display:inline-block;width:27px;height:27px;background:url(/theme/pic/private/vac-list-user-icon.png) no-repeat;vertical-align:middle;margin-right:5px}#DiContent .page-search-ankety .psa__btn{line-height:30px;display:block;margin:0 auto;padding:0;background:#ff8300;color:#fff;text-align:center;text-transform:uppercase;font-size:14px;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;position:relative;z-index:1;border:none;transition:all .3s ease-out}.psa__btn:before{position:absolute;z-index:-1;top:0;left:0;right:0;bottom:0;background:#abb837;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.psa__btn:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}#DiContent .page-search-ankety .psa__header-btn{width:195px}.psa__header-title{min-height:19px;margin:0 0 20px;display:block;color:#343434;font-size:18px}.filter-positions .psa__checkbox-label:nth-child(2){margin-bottom:20px}@media (min-width:768px){.psa__header{padding:20px 0 15px 80px}.psa__header-name{margin:0;display:inline-block;text-align:left}#DiContent .page-search-ankety .psa__header-btn{margin:0 30px;display:inline-block}}.psa__filter-vis{text-align:center;margin-bottom:20px;border:3px solid #abb820;cursor:pointer;color:#616161;line-height:35px;position:relative}.psa__filter-vis:after,.psa__filter-vis:before{content:'';width:0;height:0;display:block;position:absolute;top:10px;border-left:20px solid transparent;border-right:20px solid transparent;border-bottom:15px solid #abb820}.psa__filter-vis:before{left:10px}.psa__filter-vis:after{right:10px}.psa__filter-vis.active:after,.psa__filter-vis.active:before{border-bottom:initial;border-top:15px solid #abb820}#DiContent .table-view .comm-logo>a:first-child{display:block;border-radius:50%;border:2px solid #cbd880;position:relative;overflow:hidden}.promo-list__item-onl{width:80%;height:50px;position:absolute;left:10%;bottom:-24px;background-color:#fff;border-radius:50%;border:1px solid #abb820}.promo-list__item-onl span,.promo-list__item-onl span:hover{color:#abb820;font-size:12px;font-weight:700;position:relative;padding-right:13px}.promo-list__item-onl span:after{content:'';width:10px;height:10px;position:absolute;right:0;top:4px;display:block;border-radius:50%;background-color:#abb820}#DiContent .company-logo>a{border-radius:50%;border:2px solid #abb820;overflow:hidden;display:block;position:relative}#DiContent.page-ankety .list-view .company-logo-wrapp img{width:200px;height:200px}@media (min-width:768px){#DiContent.page-ankety .list-view .company-logo-wrapp img{width:115px;height:115px}}@media (min-width:992px){#DiContent.page-ankety .list-view .company-logo-wrapp img{width:171px;height:171px}}@media (min-width:1200px){#DiContent.page-ankety .list-view .company-logo-wrapp img{width:200px;height:200px}}.select-list{max-height:300px;overflow-y:auto;padding:0;margin:0;border-top:none;list-style:none;background-color:rgba(52,52,52,.6);position:absolute;top:100%;left:-1px;right:-1px;z-index:2;font-family:RobotoCondensed-Regular,verdana,arial;font-size:14px;color:#fff}.select-list li{width:100%;line-height:30px;padding:3px 6px;cursor:pointer;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.select-list li:hover{background-color:rgba(255,255,255,.2)}#filter-city{position:relative}#filter-city .select-list li{padding:0 15px;text-align:left}#DiContent #filter-city .filter-city-select input{padding:0;border:none;background:0 0;margin:2px 0 2px 6px;height:29px}#DiContent #filter-city .filter-city-select input:focus{outline:0}.filter-city-select.load:after{content:'';width:20px;height:20px;right:3px;background:url(/theme/pic/loading1.gif) no-repeat;background-size:cover;top:7px;position:absolute}.city-select,.filter-city-select li:not([data-id="0"]){display:inline-block;padding:3px 20px 3px 5px;margin:2px 0 2px 6px;background-color:rgba(52,52,52,.6);color:#fff;line-height:18px;border-radius:5px;position:relative}.filter-city-select li:not([data-id="0"]){line-height:23px}.filter-city-select li[data-id="0"]{width:10px}.filter-city-select{display:flex;flex-direction:row;justify-content:start;flex-wrap:wrap;margin:0;list-style:none;border:1px solid #ebebeb;position:relative;padding:0 25px 0 15px}.city-select b,.filter-city-select b{width:19px;height:19px;display:block;position:absolute;top:2px;right:0;font-style:normal;text-align:center;cursor:pointer}.filter-city-select b{top:5px}.city-select b:before,.filter-city-select b:before{content:'\2716';display:block;position:absolute;top:0;right:0;bottom:0;left:0;line-height:20px}#DiContent .project__index-time input{text-align:center;padding:0 16px 0 6px}.psa__salary{width:100%;display:table;margin-bottom:5px}.psa__salary-name{width:90px;display:table-cell;font-size:14px;color:#a0a0a0;vertical-align:middle;line-height:35px;text-align:left}.psa__salary-block{width:calc(100% - 90px);display:table-cell}#DiContent.page-ankety .page-search-ankety .filter-salary label,.psa__age-label span{font-size:14px;color:#a0a0a0;font-family:RobotoCondensed-Regular,verdana,arial;text-transform:none}.psa__age-label span,.psa__salary-label span{width:30px;text-align:right;position:absolute;left:0;top:0;line-height:35px}#DiContent .page-search-ankety .psa__age-label .psa__input,#DiContent .page-search-ankety .psa__salary-block .psa__input{width:100%;padding:0 5px}#DiContent.page-ankety .page-search-ankety .psa__age-label,#DiContent.page-ankety .page-search-ankety .psa__salary-label{width:50%;float:left;padding-left:35px;position:relative}
</style>
<?php
// если не моб устройство
// endif;
?>
<div class='row page-search-ankety'>
    <div class="psa__veil"></div>
    <div class="col-xs-12">
        <?php if (Share::$UserProfile->type == 3): ?>
            <div class="psa__header">
                <h1 class="psa__header-name"><?= Share::$UserProfile->exInfo->name ?></h1>
                <a class='psa__btn psa__header-btn' href='<?= MainConfig::$PAGE_VACPUB ?>'>Добавить вакансию</a>
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
                        <div class="psa__filter-btn">ОК</div>
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
                        <div class="psa__filter-btn">ОК</div>
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
                            <div class="psa__filter-btn">ОК</div>
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
                                                <?php if ($val['is_online']): ?>
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
                                            <b class='green'><?= $val['comm'] ?></b> / <b
                                                    class='red'><?= $val['commneg'] ?></b>
                                        </div>
                                    </div>
                                    <br>
                                    <?php if ($val['ismed'] === '1' || $val['ishasavto'] === '1'): ?>
                                        <div class="med-avto">
                                            <?php if ($val['ismed'] === '1'): ?>
                                                <div class="ico ico-avto js-g-hashint" title="Есть автомобиль"></div>
                                            <?php endif; ?>
                                            <?php if ($val['ishasavto'] === '1'): ?>
                                                <div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class='vacancies'>
                                        <h3>Целевые вакансии:</h3>
                                        <?php
                                        $curr = array('руб/час', 'руб/нед', 'руб/мес', 'руб/пос',);
                                        foreach ($val['post'] as $key2 => $val2):
                                            ?>
                                            <?= $val2[0] ?>
                                            <div class='price'><?= $val2[1] . ' ' . $curr[$val2[2]] ?></div><br>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class='place'>
                                        <h3>Город:
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