<?php if( $mess = Yii::app()->user->getFlash('Message') ): Yii::app()->user->setFlash('Message', null) ?>
    <script type="text/javascript">var flashMes = "<?=$mess['message']?>"</script>
<?php endif; ?>
<?php if( $viData['error'] ): ?>
    <div class='wrapper'>
        <div class='row'>
            <div class='col-xs-12 col-sm-4 col-lg-3'>
                <br />
                <?= $viData['message'] ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php 
    function endingR($num){
        $num = (int)$num;  
        if ($num == 1 || ($num>11 && $num%10==1)) return ' года';
        else return ' лет';
    }
    //
    // Установка метаданных и заголовка
    //
    $vacancies = strtolower(join(', ', $viData['vac']['post'])); // вакансия(и)
    $city = ' в ' . current($viData['vac']['city'])[0] . '(е) '; // город для заголовка
    $employ = $viData['vac']['istemp'] ? 'Постоянная' : 'Временная';// вид занятости
    if( $viData['vac']['shour'] > 0 ) $wage = $viData['vac']['shour'] . ' руб/час' ;
    elseif( $viData['vac']['sweek'] > 0 ) $wage = $viData['vac']['sweek'] . ' руб/неделю' ;
    elseif( $viData['vac']['smonth'] > 0 ) $wage = $viData['vac']['smonth'] . ' руб/мес' ;
    elseif( $viData['vac']['svisit'] > 0 ) $wage = $viData['vac']['svisit'] . ' руб/посещение' ;
    else $wage = 'по договоренности';   // зп
    $sex = ($viData['vac']['isman'] ? 'юноши' : '')
        . ($viData['vac']['isman'] && $viData['vac']['iswoman'] ? ', ' : '')
        . ($viData['vac']['iswoman'] ? 'девушки' : ''); // пол
    $years = '';
    if($viData['vac']['agefrom'] || $viData['vac']['ageto']){
        $years = ($viData['vac']['agefrom'] ? 'от ' . $viData['vac']['agefrom'] : '')
            . ($viData['vac']['ageto'] ? ' до ' . $viData['vac']['ageto'] : '') 
            . 'лет';    // возраст
    }
    $strBreadcrumb = 'Вакансия - ' . $vacancies . ' - оплата ' . $wage;
    $strTitleH1 = '<h1>' . $strBreadcrumb . '</h1>';


    // закрываем от индексации
    if($viData['vac']['index']){
        Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array());
    }
    // устанавливаем title
    if(!empty($viData['vac']['meta_title'])){
        $this->pageTitle = $viData['vac']['meta_title'];
    }
    else{
        $this->pageTitle = "Вакансия " . $vacancies . $city . " - поиск работы на Prommu.com";
    }
    // устанавливаем h1
    if(!empty($viData['vac']['meta_h1'])){
        $this->ViewModel->setViewData('pageTitle', '<h1>' . $viData['vac']['meta_h1'] . '</h1>');
        $this->setBreadcrumbsEx(array($viData['vac']['meta_h1'], $_SERVER['REQUEST_URI']));
    }
    else{
        $this->ViewModel->setViewData('pageTitle', $strTitleH1);
        $this->setBreadcrumbsEx(array($strBreadcrumb, $_SERVER['REQUEST_URI']));
    }
    // устанавливаем description
    if(!empty($viData['vac']['meta_description'])){
        Yii::app()->clientScript->registerMetaTag($viData['vac']['meta_description'], 'description');
    }
    else{
        Yii::app()->clientScript->registerMetaTag($employ . " вакансия от " . htmlspecialchars_decode(trim($viData['vac']['coname'])) . ": " . htmlspecialchars_decode($vacancies) . ", " . current($viData['vac']['city'])[0] . ", " . $wage . ", Возраст: " . $years . ", Пол: " . $sex, 'description');
    }
    ?>    
    <script type="text/javascript">$(function(){ G_VARS.idVac = '<?= $viData['vac']['id'] ?>'; })</script>
     <?php if( Share::$UserProfile->type == 2 ){
        $result = Yii::app()->db->createCommand()
                ->select("u.id_user, u.login, u.passw, u.email, u.access_time, u.status, u.isblocked, u.ismoder,
                r.firstname, r.lastname")
            ->leftjoin('resume r', 'r.id_user=u.id_user')
                ->from('user u')
                ->where('u.id_user=:id', array(':id'=>Share::$UserProfile->id))
                ->queryRow();
        $ismoder = $result['ismoder'];
     }

     ?>
    <?php $flagEdit = (Share::$UserProfile->type == 3 && Share::$UserProfile->exInfo->id == $viData['vac']['idus']) ?>

    <?
    /*
    *
    *       ЗАРЕГЕННЫЙ ВЛАДЕЛЕЦ ВАКАНСИИ
    *
    */
    ?>
    <?php if(Share::$UserProfile->type == 3 && Share::$UserProfile->exInfo->id == $viData['vac']['idus']): ?>
        <?php
            $bUrl = Yii::app()->baseUrl;
            Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/vacedit/main.css');
            Yii::app()->getClientScript()->registerScriptFile($bUrl.'/jslib/nicedit/nicEdit.js', CClientScript::POS_END);
            Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/vacedit/main.js', CClientScript::POS_END);
            $name = Share::$UserProfile->exInfo->name;
            //
            $Q1 = Yii::app()->db->createCommand()
              ->select('m.id, m.id_city, m.name')
              ->from('metro m')
              ->limit(10000);
            $arMetroes = $Q1->queryAll();
            // оптимизируем массив метро для JS
            $arTemp = array();
            foreach ($arMetroes as $m){
              $arTemp[$m['id_city']][$m['id']] = $m['name'];
            }
            $arMetroes = $arTemp;

            // ищем самую позднюю дату
            $v = $viData['vac'];
            $begWorkDate = reset($v['city'])[1]; // дата начала первого города
            $endWorkDate = reset($v['city'])[2]; // дата окончания первого города
            foreach ($v['city'] as $c){
                if(strtotime($c[1])<strtotime($begWorkDate))
                    $begWorkDate = $c[1];
                if(strtotime($c[2])>strtotime($endWorkDate))
                    $endWorkDate = $c[2];
                if(isset($v['location'][$c[3]]))
                    foreach ($v['location'][$c[3]] as $l)
                        if(isset($v['loctime'][$l['id']]))
                            foreach ($v['loctime'][$l['id']] as $t){
                                if(strtotime($t[0])<strtotime($begWorkDate))
                                    $begWorkDate = $t[0];
                                if(strtotime($t[1])>strtotime($endWorkDate))
                                    $endWorkDate = $t[1];
                            }
            }
        ?>
        <script type="text/javascript">
            var arMetroes = <?=json_encode($arMetroes);?>;
            var arPosts = <?=json_encode($viData['posts']);?>;
        </script>
        <div class='row employer-reg-vacansy'>
            <form action="<?=MainConfig::$PAGE_VACANCY_EDIT . DS . $viData['vac']['id'] ?>" method="POST" id="reg-vac-form">
                <input type="hidden" name="block" value='vacpage'>
                <input type="hidden" name="save" value='1'>
                <div class="col-xs-12">
                    <div class="erv__header">
                        <?php if(!$viData['vac']['status']): ?>
                            <a href='/<?= MainConfig::$PAGE_VACACTIVATE . "?id={$viData['vac']['id']}" ?>' class="erv__header-btn">Опубликовать вакансию</a>
                        <?php else: ?>
                            <a href='/<?= MainConfig::$PAGE_VACACTIVATE . "?id={$viData['vac']['id']}&d=1" ?>' class="erv__header-btn">Снять с публикации</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class='col-xs-12 col-sm-4 col-lg-3'>
                    <img src="<?=DS . MainConfig::$PATH_EMPL_LOGO . DS . (!Share::$UserProfile->exInfo->logo ?  'logo.png' : (Share::$UserProfile->exInfo->logo) . '400.jpg')?>" class="erv__logo js-g-hashint" title="<?=$name?>"/>
                    <div class="clearfix"></div>
                    <span class="erv__tab-link link5">Просмотров (<?=$viData['views']?>)</span><br>
                    <a href="?info=resp" class="erv__tab-link link1">Откликнувшиеся (<?=$viData['vacResponses']['counts'][4] ?: 0?>)</a><br>
                    <a href="?info=approv" class="erv__tab-link link7">Утвержденные (<?=$viData['vacResponses']['counts'][8] ?: 0?>)</a><br>
                    <a href="?info=aside" class="erv__tab-link link3">Отложенные (<?=$viData['vacResponses']['counts'][1]?>)</a><br>
                    <a href="?info=reject" class="erv__tab-link link2">Отклоненные (<?=$viData['vacResponses']['counts'][3]?>)</a><br>
                    <a href="?info=refuse" class="erv__tab-link link6">Отказавшиеся (<?=$viData['vacResponses']['counts'][5]?>)</a><br>
                    <a href="?info=dialog" class="erv__tab-link link4">Чат вакансии (<?=$viData['vacResponses']['countsDiscuss']?>)</a><br>
                    <a href='/<?= MainConfig::$PAGE_VACDELETE . "?id={$viData['vac']['id']}&page={$viData['vac']['status']}" ?>' class="erv__publ-btn" id="rv-vac-del">Удалить вакансию</a>
                    <div class="erv__logo-chat-block">
                        <a href="<?=MainConfig::$PAGE_IM . '?vac'?>" class="evl__chat">Чат по вакансиям</a><br>
                        <a href="<?=MainConfig::$PAGE_IM?>" class="evl__chat">Личный чат</a>
                    </div>
                </div>
                <div class='col-xs-12 col-sm-8 col-lg-9'>
                    <h1 class="erv__title"><?=$viData['vac']['title']?></h1>
                    <?php if( $flagEdit && !$viData['vac']['status'] ): ?>
                        <span class="erv__not-publ">Вакансия не опубликована</span>
                    <?php endif; ?>
                    <div class="erv__title-module">
                    	<div>
		                    <div class="erv__salary">
		                        <div class="erv__salary-block">Оплата: </div>
		                        <div class="erv__salary-block">
		                            <?php if( $viData['vac']['shour'] > 0 ): ?>
		                                <span class="-green"><?=$viData['vac']['shour']?> руб/час</span><br/>
		                            <?php endif; ?>
		                            <?php if( $viData['vac']['sweek'] > 0 ): ?>
		                                <span class="-green"><?=$viData['vac']['sweek']?> руб/неделю</span><br/>
		                            <?php endif; ?>
		                            <?php if( $viData['vac']['smonth'] > 0 ): ?>
		                                <span class="-green"><?=$viData['vac']['smonth']?> руб/мес</span><br/>
		                            <?php endif; ?>
		                            <?php if( $viData['vac']['svisit'] > 0 ): ?>
		                                <span class="-green"><?=$viData['vac']['svisit']?> руб/посещение</span><br/>
		                            <?php endif; ?>
		                        </div>
		                    </div>
		                    <div class='erv__publ-date'>Дата публикации: <?=$viData['vac']['crdate']?><br>Дата начала работы: <span id="rv-vac-bdate"><?=$begWorkDate?></span><br>Дата завершения работы: <span id="rv-vac-edate"><?=$endWorkDate?></span></div>                    		
                    	</div>
                        <div class="erv__services">
                            <?php if($viData['vac']['ismoder']==100): // отображать услуги только для промодерированных вкансий ?>
                                <?php if(!empty($viData['vac']['vk_link'])): ?>
                                    <a href="<?=$viData['vac']['vk_link']?>" class="erv__services-soc">Вакансия в ВК</a>
                                <?php elseif(substr($viData['vac']['repost'], 0,1)=='0'): ?>
                                    <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$viData['vac']['id']}&soc=1&page=0"?>" class="erv__services-soc">Опубликовать в ВК</a>
                                <?php endif; ?>
                                <?php if(!empty($viData['vac']['fb_link'])): ?>
                                    <a href="<?=$viData['vac']['fb_link']?>" class="erv__services-soc">Вакансия в Facebook</a>
                                <?php elseif(substr($viData['vac']['repost'], 1,1)=='0'): ?>
                                    <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$viData['vac']['id']}&soc=2&page=0"?>" class="erv__services-soc">Опубликовать в Facebook</a>
                                <?php endif; ?>
                                <?php if(!empty($viData['vac']['tl_link'])): ?>
                                    <a href="<?=$viData['vac']['tl_link']?>" class="erv__services-soc">Вакансия в Telegram</a>
                                <?php elseif(substr($viData['vac']['repost'], 2,1)=='0'): ?>
                                    <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$viData['vac']['id']}&soc=3&page=0"?>" class="erv__services-soc">Опубликовать в Telegram</a>
                                <?php endif; ?>
                                <span>Закажите услугу для вакансии</span>
                                <?php if(!$viData['vac']['ispremium']): // если вакансия НЕ премиум ?>
                                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$viData['vac']['id']}&service=premium"?>" class="erv__services-link erv__services-premium js-g-hashint" title="Установить Премиум статус"></a>
                                <?php endif; ?>
                                <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$viData['vac']['id']}&service=sms"?>" class="erv__services-link erv__services-sms js-g-hashint" title="СМС информирование"></a>
                                <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$viData['vac']['id']}&service=email"?>" class="erv__services-link erv__services-email js-g-hashint" title="EMAIL информирование"></a>
                                <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$viData['vac']['id']}&service=outsourcing"?>" class="erv__services-link erv__services-atsrc js-g-hashint" title="Аутсорсинг"></a>
                                <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$viData['vac']['id']}&service=outstaffing"?>" class="erv__services-link erv__services-outstf js-g-hashint" title="Аутстаффинг"></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="erv__subtitle erv__subtitle-who"><h2>Какой специалист нужен и с какими параметрами?</h2></div>
                    <div class="erv__module">
                        <a href="<?= MainConfig::$PAGE_VACANCY_EDIT . DS . $viData['vac']['id'] ?>?bl=1" class="erv__module-reg js-g-hashint" title="Править данные"></a>
                        <?
                        //  Название
                        ?>
                        <label class="erv__label" data-info="Заголовок вакансии * (не более 70 символов)">
                            <input type="text" name="vacancy-title" class="erv__input erv__required" placeholder="Заголовок вакансии *" id="rv-vac-title" value="<?=$viData['vac']['title']?>">
                        </label>
                        <?
                        //  Должности
                        ?>
                        <div class="erv__label">
                            <div class="fav__select-posts">
                                <span<? if(sizeof($viData['vac']['post'])) echo " style='display:none'";?>>Должность *</span>
                                <ul id="ev-posts-select">
                                    <?php foreach($viData['vac']['post'] as $k => $v): ?>
                                        <li data-id="<?=$k?>"><?=$v?><i></i><input type="hidden" name="posts[]" value="<?=$k?>"></li>
                                    <?php endforeach; ?>
                                </ul>
                                <ul id="ev-posts-list"><li data-id="0"><input type="text" name="p"></li></ul>
                            </div>
                        </div>
                        <?
                        //  Опыт работы
                        ?>
                        <div class="erv__label erv__select" data-info="Опыт работы *">
                            <?php $arExp = array(1=>'Без опыта',2=>'До 1 месяца',3=>'От 1 до 3 месяцев',4=>'От 3 до 6 месяцев',5=>'От 6 до 12 месяцев',6=>'От 1 года до 2-х',7=>'Более 2-х лет') ?>
                            <input type="text" name="str-expirience" class="erv__input erv__required" placeholder="Опыт работы *" id="rv-expirience" value="<?=$arExp[$viData['vac']['exp']]?>" disabled>
                            <div class="erv__veil" id="rv-expirience-veil"></div>
                            <ul class="erv__select-list" id="rv-expirience-list">
                                <i class="erv__select-list-icon">OK</i>
                                <?php foreach ($arExp as $k => $v): ?>
                                    <li>
                                        <input type="radio" name="expirience" value="<?=$k?>" id="expirience-<?=$k?>" data-name="<?=$v?>" <?=($viData['vac']['exp']==$k ?'checked':'')?>>
                                        <label for="expirience-<?=$k?>"><table><td><p><?=$v?></p><td><b></b></table></label>
                                    </li>  
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?
                        //  Возраст
                        ?>
                        <div class="erv__label erv__label-tbl" data-focus="Значение 'от' должно быть больше 14 и меньше значения 'до'">
                            <div>
                                <span class="erv__input-name">Возраст</span>
                                <label class="erv__label-age">
                                    <span class="erv__input-name">от*</span>
                                    <input type="text" name="age-from" class="erv__input erv__required erv__input-mini" id="rv-age-from" value="<?=$viData['vac']['agefrom']?>">
                                </label>
                                <label class="erv__label-age">
                                    <span class="erv__input-name">до</span>
                                    <input type="text" name="age-to" class="erv__input erv__required erv__input-mini" id="rv-age-to" value="<?=($viData['vac']['ageto']>0?$viData['vac']['ageto']:'')?>">
                                </label>    
                            </div>
                            <?
                            //  ПОЛ
                            ?>
                            <div>
                                <span class="erv__input-name">Пол *</span>
                                <input type="checkbox" name="mans" class="erv__input erv__required erv__hidden" id="rv-sex-man" value="1" <?=($viData['vac']['isman'] ? 'checked' : '')?>>
                                <label class="erv__label-checkbox" for="rv-sex-man">Мужчина</label>   
                                <input type="checkbox" name="wonem" class="erv__input erv__required erv__hidden" id="rv-sex-woman" value="1" <?=($viData['vac']['iswoman'] ? 'checked' : '')?>>
                                <label class="erv__label-checkbox" for="rv-sex-woman">Женщина</label>
                            </div>
                        </div>
                        <?
                        //  Прочее
                        ?>
                        <div class="erv__label erv__label-tbl">
                            <div>
                                <input type="checkbox" name="ismed" class="erv__input erv__hidden" id="rv-med-note" value="1" <?=($viData['vac']['ismed'] ? 'checked' : '')?>>
                                <label class="erv__label-checkbox" for="rv-med-note">Медкнижка</label>    
                            </div>
                            <div>
                                <input type="checkbox" name="isavto" class="erv__input erv__hidden" id="rv-auto" value="1" <?=($viData['vac']['isavto'] ? 'checked' : '')?>>
                                <label class="erv__label-checkbox" for="rv-auto">Автомобиль</label>    
                            </div>
                            <div>
                                <input type="checkbox" name="smart" class="erv__input erv__hidden" id="rv-smart" value="1" <?=($viData['vac']['smart'] ? 'checked' : '')?>>
                                <label class="erv__label-checkbox" for="rv-smart">Смартфон</label>    
                            </div>
                        </div>
                        <?
                        //  Характеристики
                        ?>
                        <div class="erv__label">
                            <label class='erv__label-half' data-info="Рост от (см)">
                                <input type="text" name="user-attribs[manh]" class="erv__input" placeholder="Рост от (см)" value="<?=$viData['vacAttribs'][9]['val']?>" id="rv-user-height">
                            </label>
                            <label class='erv__label-half' data-info="Вес от (кг)">
                                <input type="text" name="user-attribs[weig]" class="erv__input" placeholder="Вес от (кг)" value="<?=$viData['vacAttribs'][10]['val']?>" id="rv-user-weight">
                            </label>
                            <div class="clearfix"></div>
                        </div>

                        <div class="erv__label">
                            <label class='erv__label-half erv__select' data-info="Цвет волос">
                                <?php
                                    $propName = '';
                                    foreach ($viData['vacAttribs'] as $prop)
                                        if($prop['idpar']==11)
                                            $propName = $prop['name'];
                                ?>
                                <input type="text" name="user-attribs-hcolor" class="erv__input" id="rv-hcolor" placeholder="Цвет волос" value="<?=$propName?>" disabled>        
                                <div class="erv__veil" id="rv-hcolor-veil"></div>
                                <ul class="erv__select-list" id="rv-hcolor-list">
                                    <i class="erv__select-list-icon">OK</i>
                                    <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                                        <?php if( $prop['idpar'] == 11 ): ?>
                                            <li>
                                                <input type="radio" name="user-attribs[hcolor]" value="<?=$prop['id']?>" id="hcolor-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                                                <label for="hcolor-<?=$prop['id']?>">
                                                    <table><td><p><?=$prop['name']?></p><td><b></b></table>
                                                </label>           
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </label>
                            <label class='erv__label-half erv__select' data-info="Длина волос">
                                <?
                                    $propName = '';
                                    foreach ($viData['vacAttribs'] as $prop)
                                        if($prop['idpar']==12)
                                            $propName = $prop['name'];
                                ?>
                                <input type="text" name="user-attribs-hlen" class="erv__input" id="rv-hlen" placeholder="Длина волос" value="<?=$propName?>" disabled>        
                                <div class="erv__veil" id="rv-hlen-veil"></div>
                                <ul class="erv__select-list" id="rv-hlen-list">
                                    <i class="erv__select-list-icon">OK</i>
                                    <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                                        <?php if( $prop['idpar'] == 12 ): ?>
                                            <li>
                                                <input type="radio" name="user-attribs[hlen]" value="<?=$prop['id']?>" id="hlen-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                                                <label for="hlen-<?=$prop['id']?>">
                                                    <table><td><p><?=$prop['name']?></p><td><b></b></table>
                                                </label>           
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </label>
                            <div class="clearfix"></div>
                        </div>

                        <div class="erv__label">
                            <label class='erv__label-half erv__select' data-info="Цвет глаз">
                                <?
                                    $propName = '';
                                    foreach ($viData['vacAttribs'] as $prop)
                                        if($prop['idpar']==13)
                                            $propName = $prop['name'];
                                ?>
                                <input type="text" name="user-attribs-ycolor" class="erv__input" id="rv-ycolor" placeholder="Цвет глаз" value="<?=$propName?>" disabled>
                                <div class="erv__veil" id="rv-ycolor-veil"></div>
                                <ul class="erv__select-list" id="rv-ycolor-list">
                                    <i class="erv__select-list-icon">OK</i>
                                    <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                                        <?php if( $prop['idpar'] == 13 ): ?>
                                            <li>
                                                <input type="radio" name="user-attribs[ycolor]" value="<?=$prop['id']?>" id="ycolor-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                                                <label for="ycolor-<?=$prop['id']?>">
                                                    <table><td><p><?=$prop['name']?></p><td><b></b>
                                                    </table>
                                                </label>           
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </label>
                            <label class='erv__label-half erv__select' data-info="Размер груди">
                                <?
                                    $propName = '';
                                    foreach ($viData['vacAttribs'] as $prop)
                                        if($prop['idpar']==14)
                                            $propName = $prop['name'];
                                ?>
                                <input type="text" name="user-attribs-chest" class="erv__input" id="rv-chest" placeholder="Размер груди" value="<?=$propName?>" disabled>
                                <div class="erv__veil" id="rv-chest-veil"></div>
                                <ul class="erv__select-list" id="rv-chest-list">
                                    <i class="erv__select-list-icon">OK</i>
                                    <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                                        <?php if( $prop['idpar'] == 14 ): ?>
                                            <li>
                                                <input type="radio" name="user-attribs[chest]" value="<?=$prop['id']?>" id="chest-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                                                <label for="chest-<?=$prop['id']?>">
                                                    <table><td><p><?=$prop['name']?></p><td><b></b></table>
                                                </label>           
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </label>
                            <div class="clearfix"></div>
                        </div>

                        <div class="erv__label">
                            <label class='erv__label-half erv__select' data-info="Объем талии">
                                <?
                                    $propName = '';
                                    foreach ($viData['vacAttribs'] as $prop)
                                        if($prop['idpar']==15)
                                            $propName = $prop['name'];
                                ?>
                                <input type="text" name="user-attribs-waist" class="erv__input" id="rv-waist" placeholder="Объем талии" value="<?=$propName?>" disabled>    
                                <div class="erv__veil" id="rv-waist-veil"></div>
                                <ul class="erv__select-list" id="rv-waist-list">
                                    <i class="erv__select-list-icon">OK</i>
                                    <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                                        <?php if( $prop['idpar'] == 15 ): ?>
                                            <li>
                                                <input type="radio" name="user-attribs[waist]" value="<?=$prop['id']?>" id="waist-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                                                <label for="waist-<?=$prop['id']?>">
                                                    <table><td><p><?=$prop['name']?></p><td><b></b></table>
                                                </label>           
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </label>
                            <label class='erv__label-half erv__select' data-info="Объем бедер">
                                <?
                                    $propName = '';
                                    foreach ($viData['vacAttribs'] as $prop)
                                        if($prop['idpar']==16)
                                            $propName = $prop['name'];
                                ?>
                                <input type="text" name="user-attribs-thigh" class="erv__input" id="rv-thigh" placeholder="Объем бедер" value="<?=$propName?>" disabled>        
                                <div class="erv__veil" id="rv-thigh-veil"></div>
                                <ul class="erv__select-list" id="rv-thigh-list">
                                    <i class="erv__select-list-icon">OK</i>
                                    <?php foreach ($viData['userDictionaryAttrs'] as $prop): ?>
                                        <?php if( $prop['idpar'] == 16 ): ?>
                                            <li>
                                                <input type="radio" name="user-attribs[thigh]" value="<?=$prop['id']?>" id="thigh-<?=$prop['id']?>" <?= $this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $prop['id']) ? 'checked' : '' ?> data-name="<?=$prop['name']?>">
                                                <label for="thigh-<?=$prop['id']?>">
                                                    <table><td><p><?=$prop['name']?></p><td><b></b></table>
                                                </label>           
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </label>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <?
                    //  ОПЛАТА
                    ?>
                    <div class="erv__subtitle"><h2>ОПЛАТА ЗА ПРОЕКТ</h2></div>
                    <div class="erv__module">
                        <a href="<?= MainConfig::$PAGE_VACANCY_EDIT . DS . $viData['vac']['id'] ?>?bl=2" class="erv__module-reg js-g-hashint" title="Править данные"></a>
                        <div class="erv__label erv__salary">
                            <div class="erv__input-name erv__salary-name">Заработная плата *</div>
                            <div class="erv__salary-inputs">
                                <label class="erv__input-name erv__quarter-item">     
                                    <input type="text" class="erv__input erv__required erv__input-salary" name="salary-rub-hour" value="<?=$viData['vac']['shour']>0 ? intval($viData['vac']['shour']) : ''?>"/>
                                    <span class="erv__input-name"><?=(!$viData['vac']['istemp'] ? 'руб / час' : 'за проект')?></span>
                                </label>
                                <label class="erv__input-name erv__quarter-item">
                                    <input type="text" class="erv__input erv__required erv__input-salary" name="salary-rub-week" value="<?=$viData['vac']['sweek']>0 ? intval($viData['vac']['sweek']) : ''?>"/>
                                    <span class="erv__input-name">руб / неделя</span>
                                </label>
                                <label class="erv__input-name erv__quarter-item">
                                    <input type="text" class="erv__input erv__required erv__input-salary" name="salary-rub-month" value="<?=$viData['vac']['smonth']>0 ? intval($viData['vac']['smonth']) : ''?>"/>
                                    <span class="erv__input-name">руб / месяц</span>
                                </label>
                                <label class="erv__input-name erv__quarter-item">
                                    <input type="text" class="erv__input erv__required erv__input-salary" name="salary-rub-visit" value="<?=$viData['vac']['svisit']>0 ? intval($viData['vac']['svisit']) : ''?>"/>
                                    <span class="erv__input-name">руб / посещение</span>
                                </label>
                            </div>
                        </div>
        
                        <div class="erv__label erv__select" data-info="Сроки оплаты *">
                            <?php
                                $paylims = '';
                                foreach($viData['vacAttribs'] as $key => $item)
                                    if(in_array($key, [130,132,133,134,163]))
                                        $paylims = $item['name'];
                                $custPay = false;
                                foreach($viData['vacAttribs'] as $key => $item)
                                    if(in_array($key, [164])){
                                        $paylims = $item['val'];
                                        $custPay = true;
                                    }
                            ?>
                            <input type="text" name="str-paylims" class="erv__input erv__required" placeholder="Сроки оплаты *" id="rv-paylims" value="<?=$paylims?>" disabled>
                            <input type="hidden" name="paylimit" id="ev-custom-paylims" value="<?=($custPay ? $paylims : '')?>">
                            <div class="erv__veil" id="rv-paylims-veil"></div>
                            <ul class="erv__select-list" id="rv-paylims-list">
                                <i class="erv__select-list-icon">OK</i>
                                <?php foreach ($viData['userDictionaryAttrs'] as $val):?>
                                    <?php if( $val['idpar'] == 131 ): ?>
                                        <li>
                                            <input type="radio" name="user-attribs[paylims]" value="<?= $val['id'] ?>" id="paylims-<?=$val['id']?>" <?=($this->ViewModel->isInArray($viData['vacAttribs'], 'id_attr', $val['id']))  ? 'checked' : ''?> data-name="<?=$val['name']?>">
                                            <label for="paylims-<?=$val['id']?>">
                                                <table><td><p><?=$val['name']?></p><td><b></b></table>
                                            </label>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach;?>
                                <?php if($custPay): // свой вариант ?>
                                    <li>
                                        <input type="radio" name="user-attribs[paylims]" value="164" id="paylims-164" checked>
                                        <label for="paylims-164">
                                            <table><td><p><?=$paylims?></p><td><b></b></table>
                                        </label>
                                    </li>

                                <?php endif; ?>
                                <li class="erv__exp-new">
                                  <input type="text" name="inp-new-term" placeholder="Свой вариант" id="inp-new-term">
                                  <span id="add-new-term">Добавить</span>
                                </li>
                            </ul>
                        </div>
                        <div class="erv__label" data-info="Комментарии">
                            <textarea name="user-attribs[salary-comment]" class="erv__input"><?=$viData['vacAttribs'][165]['val']?></textarea>
                        </div>
                        <div class="erv__label erv__label-tbl">
                            <div>
                                <input type="checkbox" name="card-prommu" class="erv__input erv__hidden" id="rv-card-prommu" value="1" <?=($viData['vac']['cardPrommu'] ? 'checked' : '')?>>
                                <label class="erv__label-checkbox" for="rv-card-prommu">Наличие банковской карты Prommu</label>    
                            </div>
                            <div>
                                <input type="checkbox" name="bank-card" class="erv__input erv__hidden" id="rv-bank-card" value="1" <?=($viData['vac']['card'] ? 'checked' : '')?>>
                                <label class="erv__label-checkbox" for="rv-bank-card">Наличие банковской карты</label>    
                            </div>
                        </div>
                    </div>
                    <?
                    /*
                    *   INDEX
                    */
                    ?>
                    <div class="erv__subtitle"><h2>АДРЕС, МЕСТО И ВРЕМЯ РАБОТЫ</h2></div>
                    <div class="erv__module" id="city-module" data-co="<?=$viData['vac']['id_co']?>">
                        <a href="<?= MainConfig::$PAGE_VACANCY_EDIT . DS . $viData['vac']['id'] ?>?bl=3" class="erv__module-reg js-g-hashint" title="Править данные"></a>
                        <?$count=1?>
                        <?php foreach ($viData['vac']['city'] as $city): ?>
                            <div class="erv-city__item" 
                                data-id="<?=$city[3]?>" 
                                data-idcity="<?=$city[4]?>"
                                data-bdate="<?=$city[1]?>"
                                data-edate="<?=$city[2]?>"
                                >   
                                <div class="erv-city__calendar"></div>
                                <span class="erv-city__close"></span>
                                <div class="erv-city__item-veil"></div>
                                <div class="erv-city__label erv-city__label-city">
                                    <span class="erv-city__label-name"><span>Город <i><?=$count?></i>:</span></span>
                                    <div class="erv-city__label-input">
                                        <span class="city-select"><?=$city[0]?><b></b></span>
                                        <input type="text" name="city[name][]" value="<?=$city[0]?>" class="erv__input city-input" autocomplete="off">
                                        <ul class="city-list"></ul>
                                    </div>
                                </div> 
                                <?php if(isset($viData['vac']['location'][$city[3]])): ?>
                                    <?php foreach ($viData['vac']['location'][$city[3]] as $loc): ?>
                                        <div class="erv-city__location"
                                            data-idloc="<?=$loc['id']?>"
                                            data-idcity="<?=$loc['idcity']?>"
                                            data-name="<?=$loc['name']?>"
                                            data-index="<?=$loc['addr']?>"
                                            data-metro="<?=(is_array($loc['metro']) ? implode(',', array_keys($loc['metro'])) : 'null')?>">
                                            <span class="erv-city__close"></span>
                                            <div class="erv-city__item-veil"></div>
                                            <label class="erv-city__label erv-city__label-lname">
                                                <span class="erv-city__label-name"><span>Название локации:</span></span>
                                                <span class="erv-city__label-input">
                                                    <input type="text" name="city[lname][]" value="<?=$loc['name']?>" class="erv__input locname-input" placholder="Название локации">
                                                </span>
                                            </label>
                                            <label class="erv-city__label erv-city__label-lindex">
                                                <span class="erv-city__label-name"><span>Адрес локации:</span></span>
                                                <span class="erv-city__label-input">
                                                    <input type="text" name="city[lindex][]" value="<?=$loc['addr']?>" class="erv__input index-input" placholder="Адрес локации">
                                                </span>
                                            </label>
                                            <?php if(is_array($loc['metro'])): ?>
                                                <div class="erv-city__label erv-city__label-lmetro">
                                                    <span class="erv-city__label-name"><span>Метро:</span></span>
                                                    <span class="erv-city__label-input">
                                                        <ul class="ev-metro-select" data-idcity="<?=$loc['idcity']?>">
                                                            <?php foreach ($loc['metro'] as $id => $name): ?>
                                                                <li data-id="<?=$id?>"><?=$name?><b></b><input type="hidden" name="city[metro][]" value="<?=$id?>"></li>
                                                            <?php endforeach; ?>
                                                            <li data-id="0"><input type="text" name="m"></li>
                                                        </ul>
                                                        <ul class="metro-list"></ul>
                                                    </span>
                                                </div>    
                                            <?php endif; ?>
                                            <?php if(isset($viData['vac']['loctime'][$loc['id']])): ?>
                                                <? $day = 60*60*24; ?>
                                                <?php foreach ($viData['vac']['loctime'][$loc['id']] as $time): ?>
                                                    <div class="erv-city__time"
                                                        data-bdate="<?=$time[0]?>"
                                                        data-edate="<?=$time[1]?>"
                                                        data-btime="<?=$time[2]?>"
                                                        data-etime="<?=$time[3]?>">
                                                        <span class="erv-city__close"></span>
                                                        <div class="erv-city__item-veil"></div>
                                                        <div class="erv-city__label erv-city__label-ltime">
                                                            <span class="erv-city__label-name"><span>Дата работы:</span></span>
                                                            <span class="erv-city__label-input city-period">
                                                                <table>
                                                                    <? 
                                                                        if(strtotime($time[0])!=strtotime($time[1]))
                                                                            echo 'c ' . date('d.m.y', strtotime($time[0])) . ' по ' . date('d.m.y', strtotime($time[1])) . ' ' . $time[2] . '-' . $time[3]; 
                                                                        else
                                                                            echo date('d.m.y', strtotime($time[0])) . ' ' . $time[2] . '-' . $time[3]; 
                                                                    ?>
                                                                    <?/*
                                                                        $temp = strtotime($time[0]) - $day;
                                                                        do{ $temp += $day;
                                                                    ?>  
                                                                        <tr>
                                                                            <td><?=date("d.m.Y", $temp)?></td>
                                                                            <td><?=$time[2]?></td>
                                                                            <td>-</td>
                                                                            <td><?=$time[3]?></td>
                                                                        </tr>
                                                                    <?php } while ($temp != strtotime($time[1])); */?>
                                                                </table>
                                                            </span>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <span class="erv-city__button add-per-btn">Добавить период</span>
                                            <div class="clearfix"></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?> 
                                <span class="erv-city__button add-loc-btn">Добавить локацию</span>
                                <div class="clearfix"></div>  
                            </div>
                            <?$count++?>
                        <?php endforeach; ?>
                        <span class="erv-city__button add-city-btn">Добавить город</span>
                    </div>
                    <?
                    /*
                    *   DESCRIPTION
                    */
                    ?>
                    <div class="erv__subtitle"><h2>ОПИСАНИЕ ВАКАНСИИ</h2></div>
                    <div class="erv__module">
                       <div class="erv__label erv__label-tbl">
                            <div>
                                <input type="radio" name="busyType" class="erv__input erv__hidden" id="rv-busy-temp" value="0" <?=(!$viData['vac']['istemp'] ? 'checked' : '')?>>
                                <label class="erv__label-checkbox" for="rv-busy-temp">Временная работа</label>    
                            </div>
                            <div>
                                <input type="radio" name="busyType" class="erv__input erv__hidden" id="rv-busy-full" value="1" <?=($viData['vac']['istemp'] ? 'checked' : '')?>>
                                <label class="erv__label-checkbox" for="rv-busy-full">Постоянная работа</label>    
                            </div>
                        </div>
                        <a href="<?= MainConfig::$PAGE_VACANCY_EDIT . DS . $viData['vac']['id'] ?>?bl=4" class="erv__module-reg js-g-hashint" title="Править данные"></a>
                        <label class="erv__label erv__label-textarea">
                            <div id="rv-requirements-panel">
                                <span class="erv__input-name">Описание вакансии (требования) *</span>
                            </div>
                            <textarea name="requirements" class="erv__input" id="rv-requirements"><?=$viData['vac']['requirements']?></textarea>
                        </label>
                        <label class="erv__label erv__label-textarea"> 
                            <div id="rv-duties-panel">
                                <span class="erv__input-name">Обязанности</span>
                            </div>
                            <textarea name="duties" class="erv__input" id="rv-duties"><?=$viData['vac']['duties']?></textarea>
                        </label>
                        <label class="erv__label erv__label-textarea">
                            <div id="rv-conditions-panel">
                                <span class="erv__input-name">Условия</span>
                            </div>
                            <textarea name="conditions" class="erv__input" id="rv-conditions"><?=$viData['vac']['conditions']?></textarea>
                        </label>
                    </div>
                    <button class="erv__button">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
    <? // блоки для управления локациями
    require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/site/vacancies/vacancy-edit-blocks.php'; ?>
    <?php else: ?>
    <?
    /*
    *
    *   НЕЗАРЕГЕНЫЙ ПОЛЬЗОВАТЕЛЬ
    *
    */
    ?>
        <?php 
            Yii::app()->getClientScript()->registerScriptFile('/theme/js/page-vacancy-single.js', CClientScript::POS_END);
            Yii::app()->getClientScript()->registerCssFile('/theme/css/page-vacancy-single.css');
            $vacancy = $viData['vac'];
        ?>
        <div class='row single-vacancy'>
            <div class='col-xs-12 single-vacancy__num'>№ <?=$vacancy['id'] ?></div>
            <div class='col-xs-12 col-sm-4 single-vacancy__logo'>
                <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $vacancy['idus']?>" class="sv__logo-link">
                    <img src="<?=DS . MainConfig::$PATH_EMPL_LOGO . DS . (!$vacancy['logo'] ? 'logo.png' : ($vacancy['logo']) . '400.jpg')?>" class="sv__logo-img" alt="<?="Работодатель {$vacancy['coname']} prommu.com"?>">
                </a>
                <div class="sv__logo-name"><?=$vacancy['coname']?></div>
                <div class="sv__logo-name js-g-hashint" title="Дата публикации"><?=$vacancy['crdate']?></div>
                <?php if (Share::$UserProfile->type == 2 && in_array($viData['response']['status'], [4,5,6,7])): ?>
                    <a href="?info=dialog" class="erv__tab-link link4 <?=$viData['vacResponses']['countsDiscuss'] ? 'active' : ''?>">Чат вакансии</a><br>
                <?php endif; ?>
                <?php if($vacancy['iscontshow']): ?>
                    <script type="text/javascript">
                        $(function(){ 
                            G_VARS.eid = parseInt('<?= $viData['vac']['eid'] ?>');
                            G_VARS.idvac = parseInt('<?= $viData['vac']['id'] ?>');
                        })
                    </script>
                <?php endif; ?>
            </div>
            <div class='col-xs-12 col-sm-8 single-vacancy__data'>
                <div class="sv__data-main">
                    <h2 class="sv__data-vacansies"><?=join(', ', $vacancy['post'])?></h2>
                    <div class="sv__data-payment">
                        <?if($vacancy['shour'] > 0):?>
                            <span class="sv__data-payment-item"><?=$vacancy['shour'] . ' руб/час'?></span>
                        <?endif;?>
                        <?if($vacancy['sweek'] > 0):?>
                            <span class="sv__data-payment-item"><?=$vacancy['sweek'] . ' руб/неделю'?></span>
                        <?endif;?>
                        <?if($vacancy['smonth'] > 0):?>
                            <span class="sv__data-payment-item"><?=$vacancy['smonth'] . ' руб/мес'?></span>
                        <?endif;?>
                        <?if($vacancy['svisit'] > 0):?>
                            <span class="sv__data-payment-item"><?=$vacancy['svisit'] . ' руб/посещение'?></span>
                        <?endif;?>
                    </div>
                    <span></span>
                </div>
                <h2 class="sv__data-main sv__data-title"><?=$vacancy['title']?></h2>
                <div class="sv__data-main sv__data-pubdate">
                    <!--span class="sv__data-pubdate-item">Дата публикации:<b><?=$vacancy['crdate']?></b></span><br-->             
                    <?php // ищем самую позднюю дату
                        $v = $viData['vac'];
                        $begWorkDate = reset($v['city'])[1]; // дата начала первого города
                        $endWorkDate = reset($v['city'])[2]; // дата окончания первого города
                        foreach ($v['city'] as $c){
                            if(strtotime($c[1])<strtotime($begWorkDate))
                                $begWorkDate = $c[1];
                            if(strtotime($c[2])>strtotime($endWorkDate))
                                $endWorkDate = $c[2];
                            if(isset($v['location'][$c[3]]))
                                foreach ($v['location'][$c[3]] as $l)
                                    if(isset($v['loctime'][$l['id']]))
                                        foreach ($v['loctime'][$l['id']] as $t){
                                            if(strtotime($t[0])<strtotime($begWorkDate))
                                                $begWorkDate = $t[0];
                                            if(strtotime($t[1])>strtotime($endWorkDate))
                                                $endWorkDate = $t[1];
                                        }
                        }
                    ?>
                    <span class="sv__data-pubdate-item">Дата начала работы:<b><?=$begWorkDate?></b></span><br>
                    <span class="sv__data-pubdate-item">Дата завершения работы:<b><?=$viData['vac']['remdate']?></b></span>
                </div>
                <span class="sv__data-subtitle"><h3>КТО НУЖЕН и с какими параметрами</h3></span>
                <div class="sv__attributes">
                    <?php if($vacancy['isman'] || $vacancy['iswoman']): ?>
                        <?  // эти константы взяты наобум по стилям       
                            if($vacancy['iswoman']) $sex = 1;
                            if($vacancy['isman']) $sex = 15;
                            if($vacancy['isman'] && $vacancy['iswoman']) $sex = 16;
                        ?>
                        <div class="sv__attributes-item ico<?=$sex?>">
                            <div class="sv__attributes-name"><b>Пол:</b></div>
                            <div class="sv__attributes-val"><? 
                            if($sex==16) echo "Мужчины и женщины";
                            elseif($sex==15) echo "Мужчины";
                            else echo "Женщины";
                            ?></div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php if($vacancy['agefrom'] || $vacancy['ageto']): ?>
                        <div class="sv__attributes-item ico2">
                            <div class="sv__attributes-name"><b>Возраст:</b></div>
                            <div class="sv__attributes-val"><?
                                if($vacancy['agefrom'] && $vacancy['ageto'])
                                    echo 'от ' . $vacancy['agefrom'] . ' до ' . $vacancy['ageto'] . endingR($vacancy['ageto']);
                                elseif($vacancy['agefrom'])
                                    echo 'от ' . $vacancy['agefrom'] . endingR($vacancy['agefrom']);
                                elseif($vacancy['ageto'])
                                    echo 'до ' . $vacancy['ageto'] . endingR($vacancy['ageto']);
                            ?></div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php if($vacancy['exp']): ?>
                        <div class="sv__attributes-item ico3">
                            <?php $arExp = array(1=>'Без опыта',2=>'До 1 месяца',3=>'От 1 до 3 месяцев',4=>'От 3 до 6 месяцев',5=>'От 6 до 12 месяцев',6=>'От 1 года до 2-х',7=>'Более 2-х лет') ?>
                            <div class="sv__attributes-name"><b>Опыт работы:</b></div>
                            <div class="sv__attributes-val"><?=$arExp[$vacancy['exp']]?></div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <div class="sv__attributes-item ico4">
                        <div class="sv__attributes-name"><b>Вид занятости:</b></div>
                        <div class="sv__attributes-val"><?=$vacancy['istemp'] ? 'Постоянная' : 'Временная' ?></div>
                        <div class="clearfix"></div>
                    </div>

                    <?php $attr = $viData['vacAttribs']; ?>
                    <?php if($attr[10]['val']): ?>
                        <div class="sv__attributes-item ico5">
                            <div class="sv__attributes-name"><b>Вес:</b></div>
                            <div class="sv__attributes-val">от <?=$attr[10]['val']?> кг.</div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php if($attr[9]['val']): ?>
                    <div class="sv__attributes-item ico6">
                        <div class="sv__attributes-name"><b>Рост:</b></div>
                        <div class="sv__attributes-val">от <?=$attr[9]['val']?> см.</div>
                        <div class="clearfix"></div>
                    </div>
                    <?php endif; ?>
                    <?php if($data = $viData['vacAttribs'][$this->ViewModel->isInArray($viData['vacAttribs'], 'idpar', 14)]['name']): ?>
                        <div class="sv__attributes-item ico7">
                            <div class="sv__attributes-name"><b>Объем груди:</b></div>
                            <div class="sv__attributes-val"><?=$data?></div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php if($data = $viData['vacAttribs'][$this->ViewModel->isInArray($viData['vacAttribs'], 'idpar', 15)]['name']): ?>
                        <div class="sv__attributes-item ico8">
                            <div class="sv__attributes-name"><b>Объем талии:</b></div>
                            <div class="sv__attributes-val"><?=$data?></div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php if($data = $viData['vacAttribs'][$this->ViewModel->isInArray($viData['vacAttribs'], 'idpar', 11)]['name']): ?>
                        <div class="sv__attributes-item ico9">
                            <div class="sv__attributes-name"><b>Цвет волос:</b></div>
                            <div class="sv__attributes-val"><?=$data?></div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php if($data = $viData['vacAttribs'][$this->ViewModel->isInArray($viData['vacAttribs'], 'idpar', 13)]['name']): ?>
                        <div class="sv__attributes-item ico10">
                            <div class="sv__attributes-name"><b>Цвет глаз:</b></div>
                            <div class="sv__attributes-val"><?=$data?></div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php if($data = $viData['vacAttribs'][$this->ViewModel->isInArray($viData['vacAttribs'], 'idpar', 12)]['name']): ?>
                        <div class="sv__attributes-item ico14">
                            <div class="sv__attributes-name"><b>Длина волос:</b></div>
                            <div class="sv__attributes-val"><?=$data?></div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php if($data = $viData['vacAttribs'][$this->ViewModel->isInArray($viData['vacAttribs'], 'idpar', 16)]['name']): ?>
                        <div class="sv__attributes-item ico13">
                            <div class="sv__attributes-name"><b>Объем бедер:</b></div>
                            <div class="sv__attributes-val"><?=$data?></div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="sv__checkbox-attr">
                    <?php if($vacancy['ismed']): ?>
                        <div class="sv__checkbox-attr-item med">Медкнижка</div>
                    <?php endif; ?>
                    <?php if($vacancy['isavto']): ?>
                        <div class="sv__checkbox-attr-item auto">Автомобиль</div>
                    <?php endif; ?>
                    <?php if($vacancy['smart']): ?>
                        <div class="sv__checkbox-attr-item smart">Смартфон</div>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div>

                <span class="sv__data-subtitle"><h3>ОПЛАТА ЗА ПРОЕКТ</h3></span>
                <div class="sv__attributes">
                    <div class="sv__attributes-item ico11">
                        <div class="sv__attributes-name"><b>Заработная плата:</b></div>
                        <div class="sv__attributes-val">
                            <?if($vacancy['shour'] > 0):?>
                                <span><?=$vacancy['shour'] . (!$viData['vac']['istemp']?' руб/час':' за проект')?></span><br>
                            <?endif;?>
                            <?if($vacancy['sweek'] > 0):?>
                                <span><?=$vacancy['sweek'] . ' руб/неделю'?></span><br>
                            <?endif;?>
                            <?if($vacancy['smonth'] > 0):?>
                                <span><?=$vacancy['smonth'] . ' руб/мес'?></span><br>
                            <?endif;?>
                            <?if($vacancy['svisit'] > 0):?>
                                <span><?=$vacancy['svisit'] . ' руб/посещение'?></span><br>
                            <?endif;?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php if($data = $viData['vacAttribs'][$this->ViewModel->isInArray($viData['vacAttribs'], 'idpar', 131)]): ?>
                        <div class="sv__attributes-item ico12">
                            <div class="sv__attributes-name"><b>Сроки оплаты:</b></div>
                            <div class="sv__attributes-val"><?=$data['name']?></div>
                            <div class="clearfix"></div>
                        </div>
                    <?php endif; ?>
                    <?php if($viData['vacAttribs'][165]['val']): ?>
                        <div class="sv__textblock">
                            <div class="sv__textblock-title ico3"><span>Описание по оплате</span></div>
                            <div class="sv__textblock-text"><?=$viData['vacAttribs'][165]['val']?></div>
                        </div>
                    <?php endif; ?>       
                </div>
                <span class="sv__data-subtitle"><h3>АДРЕС И ВРЕМЯ РАБОТЫ</h3></span>            
                <?
                    $count=1;
                    $arTemp = array();
                ?>
                <hr class="sv__data-line">
                <?php foreach ($viData['vac']['city'] as $city): ?>
                    <div class="sv__data-city city">
                        <span class="sv__data-city-name"><span>Город <?=$count?>:</span></span>
                        <span class="sv__data-city-val"><?=$city[0]?></span>
                        <div class="clearfix"></div>
                    </div>
                    <span class="sv__data-calendar" data-id="<?=$city[3]?>">
                        <span class="sv__data-calendar-name">Календарь</span>
                        <div></div>
                    </span>
                    <hr class="sv__data-line">
                    <?php if(isset($viData['vac']['location'][$city[3]])): ?>
                        <?php foreach ($viData['vac']['location'][$city[3]] as $loc): ?>
                            <? $arTemp[$city[3]][$loc['id']] = $loc; ?>
                            <div class="sv__data-city loc">
                                <span class="sv__data-city-name"><span>Название локации:</span></span>
                                <span class="sv__data-city-val"><?=$loc['name']?></span>
                                <div class="clearfix"></div>
                            </div>
                            <div class="sv__data-city addr">
                                <span class="sv__data-city-name"><span>Адрес локации:</span></span>
                                <span class="sv__data-city-val"><?=$loc['addr']?></span>
                                <div class="clearfix"></div>
                            </div>
                            <?php if(is_array($loc['metro'])): ?>
                                <div class="sv__data-city addr">
                                    <span class="sv__data-city-name"><span>Метро:</span></span>
                                    <span class="sv__data-city-val"><?=implode(',<br>', $loc['metro'])?></span>
                                    <div class="clearfix"></div>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($viData['vac']['loctime'][$loc['id']])): ?>
                                <? $day = 60*60*24; ?>
                                <?php foreach ($viData['vac']['loctime'][$loc['id']] as $time): ?>
                                    <? $arTemp[$city[3]][$loc['id']]['time'] = $time; ?>
                                    <div class="sv__data-city time">
                                        <span class="sv__data-city-name"><span>Дата работы:</span></span>
                                        <span class="sv__data-city-val">
                                            <table>
                                                <? 
                                                    if(strtotime($time[0])!=strtotime($time[1]))
                                                        echo 'c ' . date('d.m.y', strtotime($time[0])) . ' по ' . date('d.m.y', strtotime($time[1])) . ' ' . $time[2] . '-' . $time[3]; 
                                                    else
                                                        echo date('d.m.y', strtotime($time[0])) . ' ' . $time[2] . '-' . $time[3]; 
                                                ?>
                                                <?/*    отказались от такой красоты
                                                    $temp = strtotime($time[0]) - $day;
                                                    do{ $temp += $day;
                                                ?>  
                                                    <tr>
                                                        <td><?=date("d.m.Y", $temp)?> г.,</td>
                                                        <td><?=$time[2]?></td>
                                                        <td>-</td>
                                                        <td><?=$time[3]?></td>
                                                    </tr>
                                                <?php } while ($temp != strtotime($time[1])); */?>
                                            </table>                                            
                                        </span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr class="sv__data-line">
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?> 
                    <?$count++?>
                <?php endforeach; ?>
                <script type="text/javascript"> var arLoc = <?=json_encode($arTemp)?>// массив локаций для JS</script>
                <span class="sv__data-subtitle"><h3>ОПИСАНИЕ ВАКАНСИИ, ОПЫТ РАБОТЫ</h3></span>
                <?php if($vacancy['requirements']): ?>
                    <div class="sv__textblock">
                        <div class="sv__textblock-title ico1"><span>Требования</span></div>
                        <div class="sv__textblock-text"><?=html_entity_decode($vacancy['requirements'])?></div>
                    </div>
                <?php endif; ?>
                <?php if($vacancy['conditions']): ?>
                    <div class="sv__textblock">
                        <div class="sv__textblock-title ico2"><span>Условия</span></div>
                        <div class="sv__textblock-text"><?=html_entity_decode($vacancy['conditions'])?></div>
                    </div>
                <?php endif; ?>
                <?php if($vacancy['duties']): ?>
                    <div class="sv__textblock">
                        <div class="sv__textblock-title ico3"><span>Обязанности</span></div>
                        <div class="sv__textblock-text"><?=html_entity_decode($vacancy['duties'])?></div>
                    </div>
                <?php endif; ?>
                <?php if( $viData['response']['response'] > 0 && $ismoder != 0): ?>
                    <div class='btn-response btn-orange-fix-wr'>
                        <a class='hvr-sweep-to-right' href='#'>Откликнуться на вакансию</a>
                    </div>
                <?php endif; ?>
                <?php if(Share::$UserProfile->type < 2/*Share::$UserProfile->type==2 &&  $ismoder!=1*/): ?>
                    <div class='btn-register btn-orange-fix-wr'>
                        <a class='hvr-sweep-to-right' href='<?= MainConfig::$PAGE_REGISTER ?>?p=1' title="">Откликнуться на вакансию</a>
                    </div>
                   
                <?php endif; ?>

                <?php /*if( Share::$UserProfile->type < 2 ): ?>
                    <div class='btn-register btn-orange-fix-wr'>
                        <a class='hvr-sweep-to-right' href='<?= MainConfig::$PAGE_REGISTER ?>?p=1'>Откликнуться на вакансию</a>
                    </div>
                <?php endif; */?>

                <div class="resp-message"><?= $viData['response']['message'] ?></div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
