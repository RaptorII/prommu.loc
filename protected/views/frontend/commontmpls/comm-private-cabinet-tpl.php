<?php

Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/small-user-menu.js', CClientScript::POS_END);

$route = Yii::app()->controller->action->getId().'/'.ContentPlus::getActionID();

if ($route !== "about/empl" &&
    $route !== "about/prom") {

    if ($this->breadcrumbs):
        if( Yii::app()->controller->route !== 'site/index' )
            $this->breadcrumbs = array_merge(array(Yii::t('zii','Главная')=>Yii::app()->homeUrl), $this->breadcrumbs);

        $this->widget('zii.widgets.CBreadcrumbs', array(
            'links'=>$this->breadcrumbs,
            'homeLink'=>false,
            'tagName'=>'div',
            'separator'=>' &gt; ',
//        'activeLinkTemplate'=>'<a href="{url}">{label}</a> <span class="divider">/</span>',
            'activeLinkTemplate'=>'<a href="{url}">{label}</a>',
            'inactiveLinkTemplate'=>'<span>{label}</span>',
            'htmlOptions'=>array ('class'=>'container breadcrumbs')
        ));
    endif;

}

?>

<?php if( ($action = $this->action->getId()) == 'profile' ) $action = 'company-profile-own' ?>

<div id="DiContent" class="page-<?= $action ?> <?= $this->ViewModel->getViewData()->addContentClass ?><?= ContentPlus::getActionID() ? ' action-' . ContentPlus::getActionID() : '' ?>">

    <?php
    if ((strcasecmp($route, 'about/empl') == 0) ||
        (strcasecmp($route, 'about/prom') == 0)) {
        echo $content;
    } else { ?>
        <div class="container">
            <!--menu            -->
            <div class="row content-menu-box mt20">
                <div class="col-xs-12 mobile-none">
                    <div class="content-header">Личный кабинет работодателя</div>
                    <div class="content-header-line"></div>
                </div>
                <div class="col-xs-12 submenu-block">
                    <div class="personal-acc__menu">
                        <div class="personal-acc__menu-wrap">
                            <?php Yii::app()->getClientScript()->registerCssFile('/theme/css/private/private-menu.css'); ?>
                            <?php $mactive = ContentPlus::getActionID(); ?>
                            <?php foreach (Share::$viewData['menu'] as $key => $val): ?>
                                <?php if(!$val['hidden']): ?>
                                    <?
                                    $ep = filter_var(Yii::app()->getRequest()->getParam('ep'), FILTER_SANITIZE_NUMBER_INT);
                                    $active ='';
                                    if(strpos($val['link'], 'ep=1')>0)
                                        $ep ? $active='active' : $active='';
                                    else
                                        $active = strpos($val['link'], $mactive) === false ? '' : 'active';
                                    ?>
                                    <a href="/<?= $val['link'] ?>" class="pa__menu-link pa__menu-<?=$val['id']?> employer <?=$active?>">
                                        <span class="pa__menu-item">
                                            <span class="pa__menu-item-icon"><i></i></span>
                                            <span class="pa__menu-item-text"><?= $val['name'] ?></span>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--end menu            -->

            <div class="row content-header-box mt20">
                <div class="col-xs-12">
                    <div class="header-user-info">
                        <!--                    -->

                        <div class="small-menu__list">
                            <?
                            $arNotif = UserNotifications::getNotifications();
                            $link = MainConfig::$PAGE_RESPONSES;
                            $vacClass = $curUrl==$link ? ' current' : '';
                            $vacClass .= $arNotif['cnt'] ? ' active' : '';
                            ?>
                            <? if(Share::isApplicant()): ?>
                                <div class="small-menu__item<?=($curUrl==MainConfig::$PAGE_APPLICANT_VACS_LIST ? ' current' : '')?>">
                                    <a href="<?=MainConfig::$PAGE_APPLICANT_VACS_LIST?>" class="addvac">
                                        <span class="small-menu__circle">
                                            <span class="small-menu__icon icn-prj-prommu color-white"></span>
                                        </span>
                                        <span class="small-menu__name">ПРОЕКТЫ</span>
                                    </a>
                                </div>
                            <? endif; ?>
                            <div class="small-menu__item vacancy<?=$vacClass?>" id="sm-vac-cnt">
                                <a href="<?=$link?>">
                                    <span class="small-menu__circle">
                                        <b class="small-menu__cnt"><?=$arNotif['cnt']?></b>
                                        <span class="small-menu__icon icn-group-plus-two-prommu color-white"></span>
                                    </span>
                                    <span class="small-menu__name">ВАКАНСИИ</span>
                                </a>
                                <ul class="small-menu__submenu">
                                    <? if (!$arNotif['cnt']): ?>
                                        <li class="small-menu__submenu-nothing">Нет уведомлений</li>
                                    <? else: ?>
                                        <? foreach ($arNotif['items'] as $key => $n): ?>
                                            <li class="small-menu__submenu-item">
                                                           <span class="active">
                                                              <span><?= $n['name'] ?></span>
                                                             <i><?=($n['cnt']>100 ? '99+' : $n['cnt'])?></i>
                                                           </span>
                                            </li>
                                            <? foreach ($n['items'] as $v): ?>
                                                <li>
                                                    <a href="<?= $v['link'] ?>" class="active">
                                                        <span><?= $v['vacancy'] ?></span>
                                                        <? if ($v['cnt'] > 1): ?>
                                                            <i><?=($v['cnt']>100 ? '99+' : $v['cnt'])?></i>
                                                        <? endif; ?>
                                                    </a>
                                                </li>
                                            <? endforeach; ?>
                                        <? endforeach; ?>
                                    <? endif; ?>
                                </ul>
                            </div>
                            <?php $link = '/'.MainConfig::$PAGE_RATE ?>
                            <a href="<?=$link?>" class="small-menu__item rate<?=($curUrl==$link ? ' current' : '')?>" id="sm-rate-cnt">
                                <span class="small-menu__circle">
                                    <b class="small-menu__cnt">0</b>
                                    <i class="small-menu__icon icn-trend-prommu color-white"></i>
                                </span>
                                <span class="small-menu__name">ОТЗЫВЫ И РЕЙТИНГИ</span>
                            </a>

                            <!--                    -->
                            <?php $link = MainConfig::$PAGE_CHATS_LIST ?>
                            <a href="<?=$link?>" class="small-menu__item notice<?=(strpos($curUrl,$link)!==false ? ' current' : '')?>" id="sm-notice-cnt-m">
                                                        <span class="small-menu__circle">
                                                            <b class="small-menu__cnt">0</b>
                                                            <i class="small-menu__icon icn-envelope-prommu color-white"></i>
                                                        </span>
                                <span class="small-menu__name">СООБЩЕНИЯ</span>
                            </a>

                        </div>

                        <!--                    -->
                        <div class="clearfix"></div>
                        <div class="small-menu__profile">
                            <?php $user = Yii::app()->session['au_us_data']; ?>
                            <a class="small-menu__username" href="<?=MainConfig::$PAGE_PROFILE?>" data-id="<?=$user->id?>">
                                <span>
                                    <?php
                                    if($user->firstname || $user->lastname):
                                        echo $user->firstname . ' ' . $user->lastname;
                                    else:
                                        echo $user->name;
                                    endif;
                                    ?>
                                </span>
                            </a>
                            <a class="small-menu__btn" href="<?=MainConfig::$PAGE_LOGOUT?>">
                                <b class="header-info__btn">ВЫХОД</b>
                            </a>
                        </div>
                        <div class="clearfix"></div>
                        <!--                    -->
                    </div>
                </div>
            </div>

            <div class="content-block">
                <?= $content ?>
            </div>
        </div>
    <?php } ?>

</div>