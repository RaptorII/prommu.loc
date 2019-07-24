<?php if( $this->breadcrumbs ):
    if( Yii::app()->controller->route !== 'site/index' )
        $this->breadcrumbs = array_merge(array(Yii::t('zii','Главная')=>Yii::app()->homeUrl), $this->breadcrumbs);

    $this->widget('zii.widgets.CBreadcrumbs', array(
        'links'=>$this->breadcrumbs,
        'homeLink'=>false,
        'tagName'=>'div',
        'separator'=>' &gt; ',
        'activeLinkTemplate'=>'<a href="{url}">{label}</a>',
        'inactiveLinkTemplate'=>'<span>{label}</span>',
        'htmlOptions'=>array ('class'=>'container breadcrumbs')
    )); ?>
<?php endif; ?>

<?php if( ($action = $this->action->getId()) == 'profile' ) $action = 'applicant-profile-own' ?>
<?php Yii::app()->getClientScript()->registerCssFile('/theme/css/private/private-menu.css'); ?>
<div id="DiContent" class="page-<?= $action ?> <?= $this->ViewModel->getViewData()->addContentClass ?><?= ContentPlus::getActionID() ? ' action-' . ContentPlus::getActionID() : '' ?>">
    <div class="container">
        <div class="row content-header-box mt20">
            <?php if( $action == 'applicant-profile-own' &&  Share::$UserProfile->exInfo->isblocked == 3 ): ?>
                <div class="center red">Необходимо перейти в редактирование профиля и заполнить необходимые поля. После этого ваш профиль будет отображаться в общем списке соискателей и поиске на сайте, а также вы сможете откликаться на понравившиеся вакансии. <? echo Share::$UserProfile->checkRequiredFields()['mess']?></div>
            <?php endif; ?>
            <div class="col-xs-12">
                <div class="header-user-info">
                    <!--                    -->
                    <?php $link = MainConfig::$PAGE_CHATS_LIST ?>
                    <a href="<?=$link?>" class="small-menu__item notice<?=(strpos($curUrl,$link)!==false ? ' current' : '')?>" id="sm-notice-cnt">
                                                <span class="small-menu__circle">
                                                    <b class="small-menu__cnt">0</b>
                                                    <span class="small-menu__icon icn-envelope-prommu color-white"></span>
                                                </span>
                        <span class="small-menu__name">СООБЩЕНИЯ</span>
                    </a>
                    <!--                    -->
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
                <div class="content-header">Личный кабинет соискателя</div>
                <div class="content-header-line"></div>
            </div>
        </div>
        <div class="row content-menu-box mt20">       
            <div class="col-xs-12 submenu-block">
                <div class="personal-acc__menu">
                    <div class="personal-acc__menu-wrap">
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
                                <a href="/<?= $val['link'] ?>" class="pa__menu-link pa__menu-<?=$val['id']?> applicant <?= $active ?>">
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

        <div class="content-block">
            <?= $content ?>
        </div>
    </div>
</div>