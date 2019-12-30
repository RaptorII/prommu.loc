<?php
if (Yii::app()->user->isGuest)
{
    echo $content;
}
else
{
  $hUrl = Yii::app()->homeUrl;
  $curId = $this->action->id;

  $model = new Feedback;
  $model = $model->getDatAdmin();

  $modelVac = new Vacancy;
  $modelVac = $modelVac->getVacAdmin();
  $counV = count($modelVac);

  $modelP = new Promo;
  $modelP = $modelP->getApplicAdmin();
  $counP = count($modelP);

  $modelR = new Employer;
  $modelR = $modelR->getEmplAdmin();
  $counR = count($modelR);// clear any default values

  $comments = new Comment();
  $arCommentsCnt = $comments->commentsCnt();
  $comments = new CommentsAboutUs();
  $arCommentsCnt['aboutus_reviews'] = $comments->commentsCnt();
  $arCommentsCnt['all'] += $arCommentsCnt['aboutus_reviews'];

  $arIdeas    = (new Ideas)->getNewIdeas();
  $arIdeasCnt = count($arIdeas);

  $arServiceCnt = Services::getAdminCnt();
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    if ($_SERVER['SERVER_NAME'] == 'prommu.dev') $icon = "fav-loc.ico";
    elseif ($_SERVER['SERVER_NAME'] == 'dev.prommu.com') $icon = "fav-dev.ico";
    else $icon = "favicon.ico";
    ?>
    <link rel="shortcut icon" href="/<?= $icon ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/morris/morris.css">
    <link rel="stylesheet"
          href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet"
          href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet"
          href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/main.js"></script>
    <script language="JavaScript" src="/js/topmenu_bo.js"></script>
    <?php
    $title = CHtml::encode($this->pageTitle);
    $title = $title == 'prommu.com' ? 'Администрирование PROMMU' : $title;
    ?>
    <title><?php echo $title; ?></title>

    <style>
        <?php
            /**
             * При наведении на счетчик должны выпадать
             * .dropdown:hover > .dropdown-menu
             * варианты новых не просмотренных данных
             * Если убрать - будут выпадать при клике
             */
        ?>
        .dropdown:hover > .dropdown-menu {
            display: block;
        }
        .dropdown-menu {
            margin: 0;
        }

        .navbar-nav>.user-menu>.dropdown-menu>li.user-header {
            padding: 5px 0;
            height: auto;
            text-align: left;
            background-color: #e1e3e9;
            position: relative;
        }
        .navbar-nav .user-header .label.label-danger{
            position: absolute;
            top: 10px;
            right: 5px;
            height: 15px;
            line-height: 14px;
        }
        .navbar-nav .user-header a{
            white-space:unset;
        }

        .navbar-nav .user-header {
            white-space: unset;
            padding: 0;
            height: auto;
        }
        .navbar-custom-menu>.navbar-nav>li>.dropdown-menu {
            max-height: 50vh;
            overflow-y: scroll;
        }
        .navbar-custom-menu>.navbar-nav>li>.dropdown-menu.dropdown-user {
            overflow-y: auto;
        }

        .navbar-custom-menu>.navbar-nav>li>.dropdown-menu.dropdown-user .user-header{
            text-align: center;
            background-color: #abb820;
        }

    </style>
</head>
<body class="skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">
    <header class="main-header">
        <a href="<?php echo Yii::app()->request->baseUrl; ?>" class="logo">
            <span class="logo-mini"><b>PRM</b></span>
            <span class="logo-lg"><b>PROMMU</b>.TAB</span>
        </a>


        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"></a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs"> Обратная связь</span>
                            <?php if (isset($model['cnt']) && $model['cnt'] != 0) { ?>
                                <span class="label label-danger"><?= $model['cnt'] ?></span>
                            <?php } ?>
                        </a>
                        <ul class="dropdown-menu">

                            <!-- The user image in the menu -->
                            <? foreach ($model['items'] as $id => $v) : ?>
                                <li class="user-header">
                                    <a
                                            href="/admin/site/<?= (!$v['type'] ? 'mail/' . $id : 'update/' . $v['chat']) ?>"
                                            rel="tooltip"
                                            data-placement="top"
                                            title="Ответить">
                                        <?= $id . ' - ' . $v['title'] ?>
                                        <b>(<?= $v['cnt'] ?>)</b>
                                    </a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </li>
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">Вакансии </span>
                            <?php if (isset($counV) && $counV != 0) { ?>
                                <span class="label label-danger"><?= $counV ?></span>
                            <?php } ?>
                        </a>

                        <ul class="dropdown-menu">
                            <? for ($i = 0; $i < $counV; $i++): ?>
                                <li class="user-header">

                                    <?
                                    echo '<a style="white-space: unset; background-color: #e1e3e9;" href="/admin/site/VacancyEdit/' . $modelVac[$i]['id'] . '" rel="tooltip" data-placement="top" title="Ответить">' . $modelVac[$i]['id'] . '-' . $modelVac[$i]['title'] . '</a>';
                                    ?>

                                </li>
                            <? endfor; ?>
                        </ul>
                    </li>
                   
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs"> Соискатели </span>
                            <?php if (isset($counP) && $counP != 0) { ?>
                                <span class="label label-danger"><?= $counP ?></span>
                            <?php } ?>
                        </a>

                        <ul class="dropdown-menu">
                            <? for ($i = 0; $i < $counP; $i++): ?>
                                <li class="user-header">

                                    <?
                                    echo '<a style=" white-space: unset;   background-color: #e1e3e9;" href="/admin/site/PromoEdit/' . $modelP[$i]['idus'] . '" rel="tooltip" data-placement="top" title="Просмотреть">' . $modelP[$i]['id'] . '-' . $modelP[$i]['firstname'] . ' ' . $modelP[$i]['lastname'] . '</a>';
                                    ?>

                                </li>
                            <? endfor; ?>
                        </ul>
                    </li>
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">Работодатели</span>
                            <?php if (isset($counR) && $counR != 0) { ?>
                                <span class="label label-danger"><?= $counR ?></span>
                            <?php } ?>
                        </a>
                        <ul class="dropdown-menu">
                            <? for ($i = 0; $i < $counR; $i++): ?>
                                <li class="user-header">

                                    <?
                                    echo '<a style="white-space: unset; background-color: #e1e3e9;" href="/admin/site/EmplEdit/' . $modelR[$i]['idus'] . '" rel="tooltip" data-placement="top" title="Ответить"><p>' . $modelR[$i]['id'] . '-' . $modelR[$i]['name'] . '</p></a>';
                                    ?>

                                </li>
                            <? endfor; ?>
                        </ul>
                    </li>
                    <? if ($arCommentsCnt['all']): ?>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="label label-danger"><?= $arCommentsCnt['all'] ?></span>
                                <span class="hidden-xs">Отзывы</span>
                            </a>
                            <ul class="dropdown-menu">
                                <? if ($arCommentsCnt['emp_reviews']): ?>
                                    <li class="user-header">
                                        <a href="/admin/comments?type=1" rel="tooltip" data-placement="top"
                                           title="Просмотреть">
                                            О соискателях (<?= $arCommentsCnt['emp_reviews'] ?>)
                                        </a>
                                    </li>
                                <? endif; ?>
                                <? if ($arCommentsCnt['app_reviews']): ?>
                                    <li class="user-header">
                                        <a href="/admin/comments?type=0" rel="tooltip" data-placement="top"
                                           title="Просмотреть">
                                            О работодателях (<?= $arCommentsCnt['app_reviews'] ?>)
                                        </a>
                                    </li>
                                <? endif; ?>
                                <? if ($arCommentsCnt['aboutus_reviews']): ?>
                                    <li class="user-header">
                                        <a href="/admin/reviews"
                                           rel="tooltip" data-placement="top" title="Просмотреть">
                                            О ресурсе (<?= $arCommentsCnt['aboutus_reviews'] ?>)
                                        </a>
                                    </li>
                                <? endif; ?>
                            </ul>
                        </li>
                    <? endif; ?>

                    <? if($arServiceCnt['cnt']>0): ?>
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="hidden-xs">Услуги</span>
                                <?php if (isset($arServiceCnt['cnt']) && $arServiceCnt['cnt'] != 0) { ?>
                                    <span class="label label-danger"><?= $arServiceCnt['cnt'] ?></span>
                                <?php } ?>
                            </a>
                            <ul class="dropdown-menu">
                                <? foreach ($arServiceCnt['items'] as $v): ?>
                                    <li class="user-header">
                                        <a href="<?=$v['link']?>">
                                            <? if(!empty($v['icon'])): ?>
                                                <i class="glyphicon <?=$v['icon']?>"></i>
                                            <? else: ?>
                                                <i class="glyphicon">@</i>
                                            <? endif; ?>
                                            <span><?=$v['name']?></span>
                                        </a>
                                        <span class="label label-danger"><?= $v['cnt'] ?></span>
                                        <?php if (isset($v['name']) && $v['name'] != 0) { ?>
                                            <span class="label label-danger"><?= $v['name'] ?></span>
                                        <?php } ?>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </li>
                    <? endif; ?>

                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">Идеи</span>
                            <?php if (isset($arIdeasCnt) && $arIdeasCnt != 0) { ?>
                                <span class="label label-danger"><?= $arIdeasCnt ?></span>
                            <?php } ?>
                        </a>
                        <ul class="dropdown-menu">
                            <? foreach ($arIdeas as $idea):  ?>
                                <li class="user-header">

                                    <a href="<?= $hUrl.'ideaedit/'.$idea['id']; ?>" rel="tooltip" data-placement="top">
                                        <?= $idea['name'] ?>
                                    </a>

                                </li>
                            <? endforeach; ?>
                        </ul>
                    </li>
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?
                            $model = new UserAdm();
                            $user = $model->getUser(Yii::app()->user->id);
                            ?>
                            <!-- The user image in the navbar-->
                            <img src="<?= $user['photo'] ?>" class="user-image" alt="<?= $user['fullname'] ?>">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?= $user['user']->name ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="<?= $user['photo'] ?>" class="img-circle" alt="<?= $user['fullname'] ?>">
                                <p><?= $user['fullname'] ?>
                                    <small><?= date("H:i:s") ?></small>
                                </p>
                            </li>

                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="/admin/site/AdminEdit/<?= Yii::app()->user->id ?>"
                                       class="btn btn-default btn-flat">Профиль</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo Yii::app()->homeUrl ?>site/logout"
                                       class="btn btn-default btn-flat">Выход</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <!-- <li>
                      <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li> -->
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?= $user['photo'] ?>" class="img-circle" alt="<?= $user['fullname'] ?>">
                </div>
                <div class="pull-left info">
                    <p><?= $user['fullname'] ?></p>
                    <!-- Status -->
                    <a href="/admin/site/AdminEdit/<?= Yii::app()->user->id ?>"><i
                                class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- Sidebar Menu -->

            <ul class="sidebar-menu">
                <li class="header">НАВИГАТОР</li>
                <?
                // Все пользователи
                ?>
                <? $bUsersSection = in_array($curId,['all_users','self_employed']); ?>
                <li class="treeview <?= ($bUsersSection ? 'active' : '') ?>">
                  <a href="#">
                    <i class="glyphicon glyphicon-list-alt"></i>
                    <span>Пользователи</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                  </a>
                  <ul class="treeview-menu<?= $bUsersSection ? ' menu-open' : '' ?>"<?= !$bUsersSection ? ' style="display:none"' : '' ?>>
                    <li class="<?= ($curId=='all_users' ? 'active' : '') ?>">
                      <a href="<?= $hUrl ?>all-users">
                        <i class="glyphicon glyphicon-list-alt"></i>
                        <span>Все пользователи</span>
                      </a>
                    </li>
                    <li class="<?= ($curId=='self_employed' ? 'active' : '') ?>">
                      <a href="<?= $hUrl ?>self-employed">
                        <i class="glyphicon glyphicon-barcode"></i>
                        <span>Проверка ИНН</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <?php
                // users
                ?>
                <?php
                $enableA = in_array($curId, ['users', 'PromoEdit']);
                $enableA = ($curId == 'wait' && $_GET['type'] == 2) ? true : $enableA;
                $enableA = ($curId == 'comments' && $_GET['type'] == 1) ? true : $enableA;
                $enableA = ($curId == 'sect' && $_GET['p'] == 'app') ? true : $enableA;
                $curId=='register' && Share::isApplicant($_GET['user']) && $enableA=true;
                $enableE = in_array($curId, ['empl', 'EmplEdit']);
                $enableE = ($curId == 'wait' && $_GET['type'] == 3) ? true : $enableE;
                $enableE = ($curId == 'comments' && $_GET['type'] == '0') ? true : $enableE;
                $enableE = ($curId == 'sect' && $_GET['p'] == 'emp') ? true : $enableE;
                $curId=='register' && Share::isEmployer($_GET['user']) && $enableE=true;
                ?>
                <li class="treeview <?= ($enableA ? 'active' : '') ?>">
                    <a href="#">
                        <i class="glyphicon glyphicon-user"></i>
                        <span>Соискатели</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu<?= $enableA ? ' menu-open' : '' ?>"<?= !$enableA ? ' style="display:none"' : '' ?>>
                      <li class="<?= (in_array($curId, ['users', 'PromoEdit']) ? 'active' : '') ?>">
                        <a href="<?= $hUrl ?>users">
                          <i class="glyphicon glyphicon-ok-circle"></i>
                          <span>Зарегистрированные</span>
                        </a>
                      </li>
                      <li class="<?=($curId=='register' && Share::isApplicant($_GET['user']) && $_GET['state']=='profile') ? 'active' : ''?>">
                        <a href="<?=$hUrl . 'register?user=2&state=profile'?>">
                          <i class="glyphicon glyphicon-registration-mark"></i>
                          <span>Активация профиля</span>
                        </a>
                      </li>
                      <li class="<?=($curId=='register' && Share::isApplicant($_GET['user']) && $_GET['state']=='avatar') ? 'active' : ''?>">
                        <a href="<?=$hUrl . 'register?user=2&state=avatar'?>">
                          <i class="glyphicon glyphicon-registration-mark"></i>
                          <span>Незаполненное фото</span>
                        </a>
                      </li>
                      <li class="<?=($curId=='register' && Share::isApplicant($_GET['user']) && $_GET['state']=='code') ? 'active' : ''?>">
                        <a href="<?=$hUrl . 'register?user=2&state=code'?>">
                          <i class="glyphicon glyphicon-registration-mark"></i>
                          <span>Не подтвердил код</span>
                        </a>
                      </li>
                      <li class="<?= ($curId == 'wait' && $_GET['type'] == 2 ? 'active' : '') ?>">
                        <a href="<?= $hUrl ?>wait?type=2">
                          <i class="glyphicon glyphicon-hourglass"></i>
                          <span>Брошенные(устар.)</span>
                        </a>
                      </li>
                      <li class="<?= ($curId == 'comments' && $_GET['type'] == 1 ? 'active' : '') ?>">
                        <a href="<?= $hUrl ?>comments?type=1">
                          <i class="glyphicon glyphicon-heart"></i>
                          <span>Отзывы</span>
                        </a>
                      </li>
                    </ul>
                </li>
                <li class="<?= ($enableE ? 'active' : '') ?>">
                    <a href="#">
                        <i class="glyphicon glyphicon-briefcase"></i>
                        <span>Работодатели</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu<?= $enableE ? ' menu-open' : '' ?>"<?= !$enableE ? ' style="display:none"' : '' ?>>
                      <li class="<?= (in_array($curId, ['empl', 'EmplEdit']) ? 'active' : '') ?>">
                        <a href="<?= $hUrl ?>empl">
                          <i class="glyphicon glyphicon-ok-circle"></i>
                          <span>Зарегистрированные</span>
                        </a>
                      </li>
                      <li class="<?=($curId=='register' && Share::isEmployer($_GET['user']) && $_GET['state']=='profile') ? 'active' : ''?>">
                        <a href="<?=$hUrl . 'register?user=3&state=profile'?>">
                          <i class="glyphicon glyphicon-registration-mark"></i>
                          <span>Активация профиля</span>
                        </a>
                      </li>
                      <li class="<?=($curId=='register' && Share::isEmployer($_GET['user']) && $_GET['state']=='avatar') ? 'active' : ''?>">
                        <a href="<?=$hUrl . 'register?user=3&state=avatar'?>">
                          <i class="glyphicon glyphicon-registration-mark"></i>
                          <span>Незаполненное фото</span>
                        </a>
                      </li>
                      <li class="<?=($curId=='register' && Share::isEmployer($_GET['user']) && $_GET['state']=='code') ? 'active' : ''?>">
                        <a href="<?=$hUrl . 'register?user=3&state=code'?>">
                          <i class="glyphicon glyphicon-registration-mark"></i>
                          <span>Не подтвердил код</span>
                        </a>
                      </li>
                      <li class="<?= ($curId == 'wait' && $_GET['type'] == 3 ? 'active' : '') ?>">
                        <a href="<?= $hUrl ?>wait?type=3">
                          <i class="glyphicon glyphicon-hourglass"></i>
                          <span>Брошенные</span>
                        </a>
                      </li>
                      <li class="<?= ($curId == 'comments' && $_GET['type'] == 0 ? 'active' : '') ?>">
                        <a href="<?= $hUrl ?>comments?type=0">
                          <i class="glyphicon glyphicon-heart"></i>
                          <span>Отзывы</span>
                        </a>
                      </li>
                    </ul>
                </li>
                <?php
                // vacancies
                ?>
                <?php
                $enable = in_array($curId, ['vacancy', 'vacancymail', 'VacancyEdit']);
                $enable = ($curId == 'vacancy' && $_GET['seo'] == 1) ? false : $enable;
                $enable = ($curId == 'sect' && $_GET['p'] == 'vac') ? true : $enable;
                ?>
                <li class="treeview<?= $enable ? ' active' : '' ?>">
                    <a href="#">
                        <i class="fa fa-pie-chart"></i>
                        <span>Вакансии</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu<?= $enable ? ' menu-open' : '' ?>"<?= !$enable ? ' style="display:none"' : '' ?>>
                        <li class="<?= ($curId == 'vacancy' ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>vacancy">
                                <i class="glyphicon glyphicon-ok-circle"></i>
                                <span>Действующие</span>
                            </a>
                        </li>
                        <li class="<?= ($curId == 'vacancymail' ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>vacancymail">
                                <i class="glyphicon glyphicon-hourglass"></i>
                                <span>Брошенные</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php
                // services
                ?>
                <?php
                  $enable = Yii::app()->controller->uniqueID==='service';
                  $action = Yii::app()->controller->action->id;
                  $service = Yii::app()->getRequest()->getParam('service');
                  $enable = ($curId == 'sect' && $_GET['p'] == 'service') ? true : $enable;
                ?>
                <li class="treeview<?= $enable ? ' active' : '' ?>">
                    <a href="#">
                        <i class="glyphicon glyphicon-shopping-cart"></i>
                        <span>Услуги</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu<?= $enable ? ' menu-open' : '' ?>"<?= !$enable ? ' style="display:none"' : '' ?>>
                        <li class="<?=($action=='service_order' ? 'active' : '')?>">
                          <a href="<?= $hUrl ?>service/service_order">
                            <i class="glyphicon glyphicon-envelope"></i>
                            <span>Заказ услуг гостями</span>
                          </a>
                        </li>
                        <li class="<?=($action=='service_cloud' && $service=='vacancy' ? 'active' : '')?>">
                            <a href="<?= $hUrl ?>service/service_cloud/vacancy">
                                <i class="glyphicon glyphicon-star-empty"></i>
                                <span><?=Services::getServiceName('vacancy')?></span>
                            </a>
                        </li>
                        <li class="<?=($action=='service_cloud' && $service=='email' ? 'active' : '')?>">
                            <a href="<?= $hUrl ?>service/service_cloud/email">
                                <i class="glyphicon">@</i>
                                <span><?=Services::getServiceName('email')?></span>
                            </a>
                        </li>
                        <li class="<?=($action=='service_cloud' && $service=='push' ? 'active' : '')?>">
                            <a href="<?= $hUrl ?>service/service_cloud/push">
                                <i class="glyphicon glyphicon-comment"></i>
                                <span><?=Services::getServiceName('push')?></span>
                            </a>
                        </li>
                        <li class="<?=($action=='service_cloud' && $service=='sms' ? 'active' : '')?>">
                            <a href="<?= $hUrl ?>service/service_cloud/sms">
                                <i class="glyphicon glyphicon-envelope"></i>
                                <span><?=Services::getServiceName('sms')?></span>
                            </a>
                        </li>
                        <li class="<?=($action=='service_cloud' && $service=='repost' ? 'active' : '')?>">
                            <a href="<?= $hUrl ?>service/service_cloud/repost">
                                <i class="glyphicon glyphicon-bullhorn"></i>
                                <span><?=Services::getServiceName('repost')?></span>
                            </a>
                        </li>
                        <li class="<? //=($curId=='vacancymail'?'active':'')?>">
                            <a href="#" onclick="alert('Страница в разработке'); return false">
                                <i class="glyphicon glyphicon-globe"></i>
                                <span>Геолокация</span>
                            </a>
                        </li>
                        <li class="<?=($action=='outstaffing' && $service=='outsourcing' ? 'active' : '')?>">
                            <a href="<?= $hUrl ?>service/outstaffing/outsourcing">
                                <i class="glyphicon glyphicon-check"></i>
                                <span><?=Services::getServiceName('outsourcing')?></span>
                            </a>
                        </li>
                        <li class="<?=($action=='outstaffing' && $service=='outstaffing' ? 'active' : '')?>">
                            <a href="<?= $hUrl ?>service/outstaffing/outstaffing">
                                <i class="glyphicon glyphicon-edit"></i>
                                <span><?=Services::getServiceName('outstaffing')?></span>
                            </a>
                        </li>
                        <li class="<?=($action=='card_request' ? 'active' : '')?>">
                            <a href="<?= $hUrl ?>service/card_request">
                                <i class="glyphicon glyphicon-credit-card"></i>
                                <span><?=Services::getServiceName('card')?></span>
                            </a>
                        </li>
                        <li class="<?=($action=='med_request' ? 'active' : '')?>">
                            <a href="<?= $hUrl ?>service/med_request">
                                <i class="glyphicon glyphicon-plus-sign"></i>
                                <span><?=Services::getServiceName('medbook')?></span>
                            </a>
                        </li>
                        <li class="<?=($action=='service_cloud' && $service=='api' ? 'active' : '')?>">
                            <a href="<?= $hUrl ?>service/service_cloud/api">
                                <i class="glyphicon glyphicon-cog"></i>
                                <span><?=Services::getServiceName('api')?></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php
                // ANALYTIC
                ?>
                <?php
                $enable = in_array($curId, ['analytic','marketinganalytic','analytic_byperiod']);
                $enable = ($curId == 'sect' && in_array($_GET['p'], ['analytic','marketinganalytic','analytic_byperiod'])) ? true : $enable;
                ?>
                <li class="treeview<?= $enable ? ' active' : '' ?>">
                    <a href="#">
                        <i class="glyphicon glyphicon-text-background"></i>
                        <span>Аналитика</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu<?= $enable ? ' menu-open' : '' ?>"<?= !$enable ? ' style="display:none"' : '' ?>>

                        <li class="<?= ($curId == 'marketinganalytic' ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>marketinganalytic">
                                <i class="glyphicon glyphicon-text-background"></i>
                                <span>
                                    Маркетологи
                                </span>
                            </a>
                        </li>
                        <li class="<?= ($curId == 'analytic_byperiod' && $_GET['type'] == 'all' ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>analytic-byperiod?type=all">
                                <i class="glyphicon glyphicon-text-background"></i>
                                <span>
                                    Общая
                                </span>
                            </a>
                        </li>

                        <?php if (1==1) { ?>
                            <li class="<?= ($curId == 'analyticbyparams' && $_GET['type'] == 'registrations' ? 'active' : '') ?>">
                                <a href="<?= $hUrl ?>analyticbyparams?type=registrations">
                                    <i class="glyphicon glyphicon-text-background"></i>
                                    <span>
                                    Регистрации
                                </span>
                                </a>
                            </li>
                        <?php } ?>

                        <li class="<?= ($curId == 'analyticbyparams' && $_GET['type'] == 'employer' ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>analyticbyparams?type=employer">
                                <i class="glyphicon glyphicon-text-background"></i>
                                <span>
                                    Работодатели
                                </span>
                            </a>
                        </li>
                        <li class="<?= ($curId == 'analyticbyparams' && $_GET['type'] == 'applicant' ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>analyticbyparams?type=applicant">
                                <i class="glyphicon glyphicon-text-background"></i>
                                <span>
                                    Соискатели
                                </span>
                            </a>
                        </li>
                        <li class="<?= ($curId == 'analyticbyparams' && $_GET['type'] == 'vacancy' ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>analyticbyparams?type=vacancy">
                                <i class="glyphicon glyphicon-text-background"></i>
                                <span>
                                    Вакансии
                                </span>
                            </a>
                        </li>

                        <?php
                        /**
                         * Old analitic left menu and urls
                         *
                         */
                        ?>
<!--                        -->
<!--                        <li class="--><?//= ($curId == 'analytic' && $_GET['subdomen'] == '0' ? 'active' : '') ?><!--">-->
<!--                            <a href="--><?//= $hUrl ?><!--analytic?subdomen=0">-->
<!--                                <i class="glyphicon glyphicon-text-background"></i>-->
<!--                                <span>PROMMU</span>-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li class="--><?//= ($curId == 'analytic' && $_GET['subdomen'] == '1' ? 'active' : '') ?><!--">-->
<!--                            <a href="--><?//= $hUrl ?><!--analytic?subdomen=1">-->
<!--                                <i class="glyphicon glyphicon-text-background"></i>-->
<!--                                <span>SPB.PROMMU</span>-->
<!--                            </a>-->
<!--                        </li>-->

                        <?php
                        /**
                         * End Old analitic
                         *
                         */
                        ?>


                    </ul>
                </li>
                <?php
                // СЕО
                ?>
                <?php
                $enable = in_array($curId, ['articlespages', 'seo', 'seo_registers', 'ab_testing']);
                $enable = ($curId == 'vacancy' && $_GET['seo'] == 1) ? true : $enable;
                $enable = ($curId == 'PageUpdate' && $_GET['pagetype'] == 'articles') ? true : $enable;
                $enable = ($curId == 'sect' && $_GET['p'] == 'seo') ? true : $enable;
                ?>
                <li class="treeview<?= $enable ? ' active' : '' ?>">
                    <a href="#">
                        <i class="glyphicon glyphicon-copyright-mark"></i>
                        <span>СЕО</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu<?= $enable ? ' menu-open' : '' ?>"<?= !$enable ? ' style="display:none"' : '' ?>>
                        <li class="<?= ($curId == 'vacancy' && $_GET['seo'] == 1 ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>vacancy?seo=1">
                                <i class="glyphicon glyphicon-list-alt"></i>
                                <span>SEO мониторинг</span>
                            </a>
                        </li>
                        <li class="<?= (($curId == 'articlespages' || ($curId == 'PageUpdate' && $_GET['pagetype'] == 'articles')) ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>articlespages">
                                <i class="glyphicon glyphicon-duplicate"></i>
                                <span>Статьи</span>
                            </a>
                        </li>
                        <li class="<?= ($curId == 'seo' ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>seo">
                                <i class="glyphicon glyphicon-filter"></i>
                                <span>Мета данные</span>
                            </a>
                        </li>
                        <li class="<?= ($curId == 'seo_registers' ? 'active' : '') ?>">
                          <a href="<?= $hUrl ?>seo_registers">
                            <i class="glyphicon glyphicon-registration-mark"></i>
                            <span>Регистрации</span>
                          </a>
                        </li>
                        <li class="<?= ($curId == 'ab_testing' ? 'active' : '') ?>">
                          <a href="<?= $hUrl ?>ab_testing">
                            <i class="glyphicon glyphicon-font"></i>
                            <span>AB тестирование</span>
                          </a>
                        </li>
                    </ul>
                </li>
                <?php
                // feedback
                ?>
                <li class="<?= (in_array($curId, ['feedback', 'mail']) ? 'active' : '') ?>">
                    <a href="<?= $hUrl ?>feedback">
                        <i class="glyphicon glyphicon-earphone"></i>
                        <span>Обратная связь</span>
                    </a>
                </li>
                <?php
                // monitoring
                ?>
                <li class="<?= ($curId == 'monitoring' ? 'active' : '') ?>">
                    <a href="<?= $hUrl ?>monitoring">
                        <i class="glyphicon glyphicon-scale"></i>
                        <span>Мониторинг работы API Zabbix</span>
                    </a>
                </li>
                <?php
                // additionally
                ?>
                <?php
                $enable = in_array($curId, ['newspages', 'admin', 'AdminEdit', 'faq', 'faqedit', 'forstudents', 'addfaq']);
                $enable = ($curId == 'PageUpdate' && in_array($_GET['pagetype'], ['news', 'about', 'prom', 'empl'])) ? true : $enable;
                $enable = ($curId == 'sect' && $_GET['p'] == 'add') ? true : $enable;
                ?>
                <li class="treeview<?= $enable ? ' active' : '' ?>">
                    <a href="#">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span>Дополнительно</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu<?= $enable ? ' menu-open' : '' ?>"<?= !$enable ? ' style="display:none"' : '' ?>>
                        <li class="<?= (($curId == 'PageUpdate' && $_GET['pagetype'] == 'about') ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>PageUpdate/7?lang=ru&pagetype=about">
                                <i class="glyphicon glyphicon-file"></i>
                                <span>О нас</span>
                            </a>
                        </li>
                        <li class="<?= ($curId == 'forstudents' ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>forstudents">
                                <i class="glyphicon glyphicon-file"></i>
                                <span>Работа для студентов</span>
                            </a>
                        </li>
                        <li class="<?= (($curId == 'PageUpdate' && $_GET['pagetype'] == 'empl') ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>PageUpdate/18?lang=ru&pagetype=empl">
                                <i class="glyphicon glyphicon-file"></i>
                                <span>Работодателям</span>
                            </a>
                        </li>
                        <li class="<?= (($curId == 'PageUpdate' && $_GET['pagetype'] == 'prom') ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>PageUpdate/19?lang=ru&pagetype=prom">
                                <i class="glyphicon glyphicon-file"></i>
                                <span>Соискателям</span>
                            </a>
                        </li>
                        <li class="<?= (($curId == 'newspages' || ($curId == 'PageUpdate' && $_GET['pagetype'] == 'news')) ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>newspages">
                                <i class="glyphicon glyphicon-flash"></i>
                                <span>Новости</span>
                            </a>
                        </li>
                        <li class="<?= (in_array($curId, ['faq', 'faqedit', 'addfaq']) ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>faq">
                                <i class="glyphicon glyphicon-info-sign"></i>
                                <span>FAQ</span>
                            </a>
                        </li>
                        <li class="<?= (in_array($curId, ['admin', 'AdminEdit']) ? 'active' : '') ?>">
                            <a href="<?= $hUrl ?>admin">
                                <i class="glyphicon glyphicon-sunglasses"></i>
                                <span>Администраторы</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php
                // ideas
                ?>
                <li class="<?= (in_array($curId, ['ideas', 'ideaedit']) ? 'active' : '') ?>">
                    <a href="<?= $hUrl ?>ideas">
                        <i class="glyphicon glyphicon-leaf"></i>
                        <span>Идеи/Предложения</span>
                    </a>
                </li>
                <li class="<?= (in_array($curId, ['stat', 'statedit']) ? 'active' : '') ?>">
                    <a href="<?= $hUrl ?>statist">
                        <i class="glyphicon glyphicon-leaf"></i>
                        <span>Статистика</span>
                    </a>
                </li>
                <?php
                // Notifications
                ?>
                <li class="<?= ($curId == 'notifications' ? 'active' : '') ?>">
                    <a href="<?= $hUrl ?>notifications">
                        <i class="glyphicon glyphicon-send"></i>
                        <span>Уведомления</span>
                    </a>
                </li>
                <?php
                // Settings
                ?>
                <li class="<?= ($curId == 'settings' ? 'active' : '') ?>">
                    <a href="<?= $hUrl ?>settings">
                        <i class="glyphicon glyphicon-cog"></i>
                        <span>Настройки сайта</span>
                    </a>
                </li>
                <?
                // System
                ?>
                <li class="<?= ($curId == 'system' ? 'active' : '') ?>">
                    <a href="<?= $hUrl ?>system">
                        <i class="glyphicon glyphicon-wrench"></i>
                        <span>Разработчикам</span>
                    </a>
                </li>
                <?
                // Reviews
                ?>
                <li class="<?= ($curId == 'reviews' ? 'active' : '') ?>">
                    <a href="<?= $hUrl ?>reviews">
                        <i class="glyphicon glyphicon-heart"></i>
                        <span>Отзывы о нас</span>
                    </a>
                </li>
                <?
                // File manager
                ?>
                <li class="<?= ($curId == 'filemanager' ? 'active' : '') ?>">
                    <a href="<?= $hUrl ?>filemanager">
                        <i class="glyphicon glyphicon-folder-open"></i>
                        <span>Файловый менеджер</span>
                    </a>
                </li>
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="content-header__block">
                <?php
                if (Yii::app()->controller->route !== 'site/index')
                    $this->breadcrumbs = array_merge(
                        array(Yii::t('zii', 'Главная') => Yii::app()->homeUrl),
                        $this->breadcrumbs
                    );

                $this->widget(
                    'zii.widgets.CBreadcrumbs',
                    array(
                        "links" => $this->breadcrumbs,
                        "homeLink" => false,
                        "tagName" => "div",
                        "separator" => " &gt; ",
                        "activeLinkTemplate" => "<span><a rel='nofollow' title='{label}' href='{url}'><span>{label}</span></a></span>",
                        "inactiveLinkTemplate" => "<span>{label}</span>"
                    )
                );
                ?>
                <h1>
                    <small>PROMMU AD.TAB version 1.0</small>
                </h1>
            </div>
        </section>

        <?php
        /*
          echo "<pre>";
          print_r(Yii::app()->controller->route);
          echo "</pre>";
        */
        ?>
        <!-- Main content -->
        <section class="content">
            <? foreach (Yii::app()->user->getFlashes() as $key => $message): ?>
                <div class="alert <?= $key ?>"><?= $message ?></div>
            <? endforeach; ?>
            <? echo $content; ?>
            <!-- Your Page Content Here -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:;">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="pull-right-container">
                  <span class="label label-danger pull-right">70%</span>
                </span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<?php } ?>

<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/knob/jquery.knob.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/fastclick/fastclick.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js/app.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/dist/js/demo.js"></script>

</body>
</html>

