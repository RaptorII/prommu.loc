<?php /* @var $this Controller */ ?>
<?php if(Yii::app()->user->isGuest) {echo $content;  } else {?>

<? $model = new Feedback;
   $model = $model->getDatAdmin(); 

   $modelVac = new Vacancy;
   $modelVac = $modelVac->getVacAdmin();
   $counV = count($modelVac); 

   $modelP = new Promo;
   $modelP = $modelP->getApplicAdmin();
   $counP = count($modelP); 

   $modelS = new PrommuOrder;
   $modelS = $modelS->getOrderAdmin();
   $counS = count($modelS); 


   $modelR = new Employer;
   $modelR = $modelR->getEmplAdmin();
   $counR = count($modelR);// clear any default values
 
  $comments = new Comment();
  $arCommentsCnt = $comments->commentsCnt();
  $comments = new CommentsAboutUs();
  $arCommentsCnt['aboutus_reviews'] = $comments->commentsCnt();
  $arCommentsCnt['all'] += $arCommentsCnt['aboutus_reviews'];

  $arIdeas = (new Ideas)->getCntForAdmin();

  $hUrl = Yii::app()->homeUrl;
  $curId = $this->action->id;
 ?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="language" content="en" />
  <?php
    if( $_SERVER['SERVER_NAME'] == 'prommu.dev')  $icon = "fav-loc.ico";
    elseif( $_SERVER['SERVER_NAME'] == 'dev.prommu.com')  $icon = "fav-dev.ico";
    else $icon = "favicon.ico";
  ?>
  <link rel="shortcut icon" href="/<?= $icon ?>" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/bootstrap/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/dist/css/skins/skin-blue.min.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/morris/morris.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/main.js"></script>
  <script language="JavaScript" src="/js/topmenu_bo.js"></script>
  <?php
    $title = CHtml::encode($this->pageTitle);
    $title = $title=='prommu.com' ? 'Администрирование PROMMU' : $title;
  ?>
  <title><?php echo $title; ?></title>
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
       
         


          <!-- Notifications Menu -->
          <!-- <li class="dropdown notifications-menu">
           
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
               
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
     
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li> -->
          <!-- Tasks Menu -->
          <!-- <li class="dropdown tasks-menu">
            
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                
                <ul class="menu">
                  <li>
                    <a href="#">
                     
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                       <div class="progress xs">
                    
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
        
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li> -->
           <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"> Обратная связь</span>
               <span class="label label-danger"><?=$model['cnt']?></span>
            </a>
            <ul class="dropdown-menu" style="max-height:520px;overflow:overlay">
              <!-- The user image in the menu -->
              <? foreach ($model['items'] as $id => $v) :?>
                <li style="padding:0px;height: auto;" class="user-header">
                  <a 
                    style="white-space:unset;background-color:#e1e3e9" 
                    href="/admin/site/<?=(!$v['type'] ? 'mail/' . $id : 'update/' . $v['chat'])?>" 
                    rel="tooltip" 
                    data-placement="top" 
                    title="Ответить">
                    <p style="margin:0;text-align:left"><?=$id . ' - ' . $v['title']?> <b>(<?=$v['cnt']?>)</b></p>
                  </a>
                </li>
              <? endforeach ;?>
            </ul>
          </li>
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <span class="label label-danger"><?=$counV?></span>
              <!-- The user image in the navbar-->
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">Вакансии </span>
            </a>
           
            <ul class="dropdown-menu">
              <?for($i = 0; $i < $counV; $i++):?>
              <li style="padding:0px;height: auto;" class="user-header">
          
              
                <?
                 echo '<a style=" white-space: unset;   background-color: #e1e3e9;" href="/admin/site/VacancyEdit/' . $modelVac[$i]['id']. '" rel="tooltip" data-placement="top" title="Ответить"><p>'.$modelVac[$i]['id'].'-'.$modelVac[$i]['title'].'</p></a>';
                ?>
              
            
              </li>
              <? endfor;?>
            </ul>
          </li>
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="label label-danger"><?=$counP?></span>
              <!-- The user image in the navbar-->
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">Соискатели </span>
            </a>
            
            <ul class="dropdown-menu">
             <?for($i = 0; $i < $counP; $i++):?>
              <li style="padding:0px;height: auto;" class="user-header">
          
              
                <?
                 echo '<a style=" white-space: unset;   background-color: #e1e3e9;" href="/admin/site/PromoEdit/' . $modelP[$i]['idus']. '" rel="tooltip" data-placement="top" title="Ответить"><p>'.$modelP[$i]['id'].'-'.$modelP[$i]['firstname'].' '.$modelP[$i]['lastname'].'</p></a>';
                ?>
              
            
              </li>
              <? endfor;?>
            </ul>
          </li>
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="label label-danger"><?=$counR?></span>
              <!-- The user image in the navbar-->
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">Работодатели </span>
            </a>
            <ul class="dropdown-menu">
              <?for($i = 0; $i < $counR; $i++):?>
              <li style="padding:0px;height: auto;" class="user-header">
          
              
                <?
                 echo '<a style=" white-space: unset;   background-color: #e1e3e9;" href="/admin/site/EmplEdit/' . $modelR[$i]['idus']. '" rel="tooltip" data-placement="top" title="Ответить"><p>'.$modelR[$i]['id'].'-'.$modelR[$i]['name'].'</p></a>';
                ?>
              
            
              </li>
              <? endfor;?>
            </ul>
          </li>
          <? if($arCommentsCnt['all']): ?>
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               <span class="label label-danger"><?=$arCommentsCnt['all']?></span>
                <span class="hidden-xs">Отзывы</span>
              </a>
              <ul class="dropdown-menu">
                <? if($arCommentsCnt['emp_reviews']): ?>
                  <li style="padding:0px;height: auto;" class="user-header">
                    <a style="white-space:unset;background-color:#e1e3e9;" href="/admin/comments?type=1" rel="tooltip" data-placement="top" title="Просмотреть">
                      <p>О соискателях (<?=$arCommentsCnt['emp_reviews']?>)</p>
                    </a>
                  </li>
                <? endif; ?>
                <? if($arCommentsCnt['app_reviews']): ?>
                  <li style="padding:0px;height: auto;" class="user-header">
                    <a style="white-space:unset;background-color:#e1e3e9;" href="/admin/comments?type=0" rel="tooltip" data-placement="top" title="Просмотреть">
                      <p>О работодателях (<?=$arCommentsCnt['app_reviews']?>)</p>
                    </a>
                  </li>
                <? endif; ?>
                <? if($arCommentsCnt['aboutus_reviews']): ?>
                  <li style="padding:0px;height: auto;" class="user-header">
                    <a style="white-space:unset;background-color:#e1e3e9;" href="/admin/reviews" rel="tooltip" data-placement="top" title="Просмотреть">
                      <p>О ресурсе (<?=$arCommentsCnt['aboutus_reviews']?>)</p>
                    </a>
                  </li>
                <? endif; ?>
              </ul>
            </li>
          <? endif; ?>
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <span class="label label-danger"><?= $counS ?></span>
              <span class="hidden-xs">Услуги</span>
            </a>
            <ul class="dropdown-menu">
              <?for($i = 0; $i <  $counS; $i++):?>
              <li style="padding:0px;height: auto;" class="user-header">
          
              
                <?
                if($modelS[$i]['type'] == "vacancy")
                  { 
                     $modelS[$i]['type'] = "Услуга Премиум Вакансия";
                  }
                elseif($modelS[$i]['type'] == "sms"){
                  $modelS[$i]['type'] = "Услуга Смс Рассылка";
                }

                 echo '<a style=" white-space: unset;   background-color: #e1e3e9;" href="/admin/site/services/" rel="tooltip" data-placement="top" title="Ответить"><p>Вакансия '.$modelS[$i]['id'].'-'.$modelS[$i]['type'].'</p></a>';
                
                ?>
              
            
              </li>
              <? endfor;?>
            </ul>
          </li>
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
             <span class="label label-danger"><?=($arIdeas['ideas']+$arIdeas['comments'])?></span>
              <span class="hidden-xs">Идеи</span>
            </a>
            <ul class="dropdown-menu">
              <li style="padding:0px;height:auto;" class="user-header">
                <a style="white-space:unset;background-color:#e1e3e9;text-align:left" href="<?=$hUrl?>ideas" rel="tooltip" data-placement="top">Идеи: (<?=$arIdeas['ideas']?>)</a>
              </li>
              <li style="padding:0px;height:auto;" class="user-header">
                <a style=" white-space:unset;background-color:#e1e3e9;text-align:left" href="<?=$hUrl?>ideas" rel="tooltip" data-placement="top">Комментарии: (<?=$arIdeas['comments']?>)</a>
              </li>
            </ul>
          </li>
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="<?php echo Yii::app()->request->baseUrl; ?>/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">PROMMU ADMIN</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  PROMMU ADMIN - АДМИНИСТРАТОР САЙТА
                  <small><?php echo $today = date("H:i:s"); ?> </small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                  </div>
              </li> -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo Yii::app()->homeUrl?>site/logout"  class="btn btn-default btn-flat">Выход</a>
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
          <img src="<?php echo Yii::app()->request->baseUrl; ?>/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>PROMMU ADMIN</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- Sidebar Menu -->

      <ul class="sidebar-menu">
        <li class="header">НАВИГАТОР</li>
        <?php
        // users
        ?>
        <?php 
          $enableA = in_array($curId, ['users','PromoEdit']);
          $enableA = ($curId=='wait' && $_GET['type']==2) ? true : $enableA;
          $enableA = ($curId=='comments' && $_GET['type']==1) ? true : $enableA;
          $enableA = ($curId=='sect' && $_GET['p']=='app') ? true : $enableA;
          $enableE = in_array($curId, ['empl','EmplEdit']);
          $enableE = ($curId=='wait'  && $_GET['type']==3) ? true : $enableE;
          $enableE = ($curId=='comments'  && $_GET['type']=='0') ? true : $enableE;
          $enableE = ($curId=='sect'  && $_GET['p']=='emp') ? true : $enableE;
        ?>
        <li class="treeview <?=($enableA?'active':'')?>">
          <a href="#">
            <i class="glyphicon glyphicon-user"></i>
            <span>Соискатели</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu<?=$enableA ? ' menu-open' : ''?>"<?=!$enableA ? ' style="display:none"' : ''?>>
            <li class="<?=(in_array($curId,['users','PromoEdit'])?'active':'')?>">
              <a href="<?=$hUrl?>users">
                <i class="glyphicon glyphicon-ok-circle"></i>
                <span>Зарегистрированные</span>
              </a>
            </li>
            <li class="<?=($curId=='wait' && $_GET['type']==2?'active':'')?>">
              <a href="<?=$hUrl?>wait?type=2">
                <i class="glyphicon glyphicon-hourglass"></i>
                <span>Брошенные</span>
              </a>
            </li>

             <li class="<?=($curId=='comments' && $_GET['type']==1?'active':'')?>">
              <a href="<?=$hUrl?>comments?type=1">
                <i class="glyphicon glyphicon-heart"></i>
                <span>Отзывы</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="<?=($enableE?'active':'')?>">
          <a href="#">
            <i class="glyphicon glyphicon-briefcase"></i>
            <span>Работодатели</span>
          <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu<?=$enableE ? ' menu-open' : ''?>"<?=!$enableE ? ' style="display:none"' : ''?>>
            <li class="<?=(in_array($curId,['empl','EmplEdit'])?'active':'')?>">
              <a href="<?=$hUrl?>empl">
                <i class="glyphicon glyphicon-ok-circle"></i>
                <span>Зарегистрированные</span>
              </a>
            </li>
            <li class="<?=($curId=='wait' && $_GET['type']==3?'active':'')?>">
              <a href="<?=$hUrl?>wait?type=3">
                <i class="glyphicon glyphicon-hourglass"></i>
                <span>Брошенные</span>
              </a>
            </li>
            <li class="<?=($curId=='comments' && $_GET['type']==0?'active':'')?>">
              <a href="<?=$hUrl?>comments?type=0">
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
          $enable = in_array($curId, ['vacancy','vacancymail','VacancyEdit']);
          $enable = ($curId=='vacancy' && $_GET['seo']==1) ? false : $enable;
          $enable = ($curId=='sect' && $_GET['p']=='vac') ? true : $enable;
        ?>
        <li class="treeview<?=$enable ? ' active' : ''?>">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Вакансии</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu<?=$enable ? ' menu-open' : ''?>"<?=!$enable ? ' style="display:none"' : ''?>>
            <li class="<?=($curId=='vacancy'?'active':'')?>">
              <a href="<?=$hUrl?>vacancy">
                <i class="glyphicon glyphicon-ok-circle"></i>
                <span>Действующие</span>
              </a>
            </li>
            <li class="<?=($curId=='vacancymail'?'active':'')?>">
              <a href="<?=$hUrl?>vacancymail">
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
          $enable = in_array($curId, ['services','servicess','cards','CardEdit','medcards','MedCardEdit']);
          $enable = ($curId=='sect' && $_GET['p']=='service') ? true : $enable;
        ?>
        <li class="treeview<?=$enable ? ' active' : ''?>">
          <a href="#">
            <i class="glyphicon glyphicon-shopping-cart"></i>
            <span>Услуги</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu<?=$enable ? ' menu-open' : ''?>"<?=!$enable ? ' style="display:none"' : ''?>>
            <li class="<?=($curId=='services'&&$_GET['type']=='vacancy' ? 'active' : '')?>">
              <a href="<?=$hUrl?>services?type=vacancy">
                <i class="glyphicon glyphicon-star-empty"></i>
                <span>Премиум</span>
              </a>
            </li>
            <li class="<?=($curId=='services'&&$_GET['type']=='email' ? 'active' : '')?>">
              <a href="<?=$hUrl?>services?type=email">
                <i class="glyphicon">@</i>
                <span>Электронная почта</span>
              </a>
            </li>
            <li class="<?=($curId=='services'&&$_GET['type']=='push' ? 'active' : '')?>">
              <a href="<?=$hUrl?>services?type=push">
                <i class="glyphicon glyphicon-comment"></i>
                <span>PUSH уведомления</span>
              </a>
            </li>
            <li class="<?=($curId=='services'&&$_GET['type']=='sms' ? 'active' : '')?>">
              <a href="<?=$hUrl?>services?type=sms">
                <i class="glyphicon glyphicon-envelope"></i>
                <span>SMS информирование</span>
              </a>
            </li>
            <li class="<?=($curId=='services'&&$_GET['type']=='repost' ? 'active' : '')?>">
              <a href="<?=$hUrl?>services?type=repost">
                <i class="glyphicon glyphicon-bullhorn"></i>
                <span>Соцсети</span>
              </a>
            </li>
            <li class="<?//=($curId=='vacancymail'?'active':'')?>">
              <a href="#" onclick="alert('Страница в разработке'); return false">
                <i class="glyphicon glyphicon-globe"></i>
                <span>Геолокация</span>
              </a>
            </li>
            <li class="<?=($curId=='servicess'&&$_GET['type']=='outsourcing' ? 'active' : '')?>">
              <a href="<?=$hUrl?>servicess?type=outsourcing">
                <i class="glyphicon glyphicon-check"></i>
                <span>Аутсорсинг</span>
              </a>
            </li>
            <li class="<?=($curId=='servicess'&&$_GET['type']=='outstaffing' ? 'active' : '')?>">
              <a href="<?=$hUrl?>servicess?type=outstaffing">
                <i class="glyphicon glyphicon-edit"></i>
                <span>Аутстаффинг</span>
              </a>
            </li>
            <li class="<?=(in_array($curId,['cards','CardEdit'])?'active':'')?>">
              <a href="<?=$hUrl?>cards">
                <i class="glyphicon glyphicon-credit-card"></i>
                <span>Карта Prommu</span>
              </a>
            </li>
            <li class="<?=(in_array($curId,['medcards','MedCardEdit'])?'active':'')?>">
              <a href="<?=$hUrl?>medcards">
                <i class="glyphicon glyphicon-plus-sign"></i>
                <span>Мед. книга</span>
              </a>
            </li>
            <li class="<?=($curId=='servicess'&&$_GET['type']=='api' ? 'active' : '')?>">
              <a href="<?=$hUrl?>servicess?type=api">
                <i class="glyphicon glyphicon-cog"></i>
                <span>API</span>
              </a>
            </li>
          </ul>
        </li>
        <?php
        // ANALYTIC
        ?>
        <?php
          $enable = $curId=='analytic';
          $enable = ($curId=='sect' && $_GET['p']=='analytic') ? true : $enable;
        ?>
        <li class="treeview<?=$enable ? ' active' : ''?>">
          <a href="#">
            <i class="glyphicon glyphicon-text-background"></i>
            <span>Аналитика</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu<?=$enable ? ' menu-open' : ''?>"<?=!$enable ? ' style="display:none"' : ''?>>
            <li class="<?=($curId=='analytic'&&$_GET['subdomen']==''?'active':'')?>">
              <a href="<?=$hUrl?>analytic?subdomen=">
                <i class="glyphicon glyphicon-text-background"></i>
                <span>Общая</span>
              </a>
            </li>
            <li class="<?=($curId=='analytic'&&$_GET['subdomen']=='0'?'active':'')?>">
              <a href="<?=$hUrl?>analytic?subdomen=0">
                <i class="glyphicon glyphicon-text-background"></i>
                <span>PROMMU</span>
              </a>
            </li>
            <li class="<?=($curId=='analytic'&&$_GET['subdomen']=='1'?'active':'')?>">
              <a href="<?=$hUrl?>analytic?subdomen=1">
                <i class="glyphicon glyphicon-text-background"></i>
                <span>SPB.PROMMU</span>
              </a>
            </li>
          </ul>
        </li>
        <?php
        // СЕО
        ?>
        <?php
          $enable = in_array($curId, ['articlespages','seo']);
          $enable = ($curId=='vacancy' && $_GET['seo']==1) ? true : $enable;
          $enable = ($curId=='PageUpdate' && $_GET['pagetype']=='articles') ? true : $enable;
          $enable = ($curId=='sect' && $_GET['p']=='seo') ? true : $enable;
        ?>
        <li class="treeview<?=$enable ? ' active' : ''?>">
          <a href="#">
            <i class="glyphicon glyphicon-copyright-mark"></i>
            <span>СЕО</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu<?=$enable ? ' menu-open' : ''?>"<?=!$enable ? ' style="display:none"' : ''?>>
            <li class="<?=($curId=='vacancy'&&$_GET['seo']==1?'active':'')?>">
              <a href="<?=$hUrl?>vacancy?seo=1">
                <i class="glyphicon glyphicon-list-alt"></i>
                <span>SEO мониторинг</span>
              </a>
            </li>
            <li class="<?=(($curId=='articlespages'||($curId=='PageUpdate'&&$_GET['pagetype']=='articles')) ? 'active' : '')?>">
              <a href="<?=$hUrl?>articlespages">
                <i class="glyphicon glyphicon-duplicate"></i>
                <span>Статьи</span>
              </a>
            </li>
            <li class="<?=($curId=='seo'?'active':'')?>">
              <a href="<?=$hUrl?>seo">
                <i class="glyphicon glyphicon-filter"></i>
                <span>Мета данные</span>
              </a>
            </li>
          </ul>
        </li>
        <?php
        // feedback
        ?>
        <li class="<?=(in_array($curId, ['feedback','mail'])?'active':'')?>">
          <a href="<?=$hUrl?>feedback">
            <i class="glyphicon glyphicon-earphone"></i> 
            <span>Обратная связь</span>
          </a>
        </li>
        <?php
        // monitoring
        ?>
        <li class="<?=($curId=='monitoring'?'active':'')?>">
          <a href="<?=$hUrl?>monitoring">
            <i class="glyphicon glyphicon-scale"></i>
            <span>Мониторинг работы API Zabbix</span>
          </a>
        </li>
        <?php
        // additionally
        ?>
        <?php
          $enable = in_array($curId, ['newspages','admin','AdminEdit','faq','faqedit','forstudents','addfaq']); 
          $enable = ($curId=='PageUpdate' && in_array($_GET['pagetype'],['news','about','prom','empl'])) ? true : $enable;
          $enable = ($curId=='sect' && $_GET['p']=='add') ? true : $enable;
        ?>
        <li class="treeview<?=$enable ? ' active' : ''?>">
          <a href="#">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Дополнительно</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu<?=$enable ? ' menu-open' : ''?>"<?=!$enable ? ' style="display:none"' : ''?>>
            <li class="<?=(($curId=='PageUpdate' && $_GET['pagetype']=='about') ? 'active' : '')?>">
              <a href="<?=$hUrl?>PageUpdate/7?lang=ru&pagetype=about">
                <i class="glyphicon glyphicon-file"></i>
                <span>О нас</span>
              </a>
            </li>
            <li class="<?=($curId=='forstudents'?'active':'')?>">
              <a href="<?=$hUrl?>forstudents">
                <i class="glyphicon glyphicon-file"></i>
                <span>Работа для студентов</span>
              </a>
            </li>
            <li class="<?=(($curId=='PageUpdate' && $_GET['pagetype']=='empl') ? 'active' : '')?>">
              <a href="<?=$hUrl?>PageUpdate/18?lang=ru&pagetype=empl">
                <i class="glyphicon glyphicon-file"></i>
                <span>Работодателям</span>
              </a>
            </li>
            <li class="<?=(($curId=='PageUpdate' && $_GET['pagetype']=='prom') ? 'active' : '')?>">
              <a href="<?=$hUrl?>PageUpdate/19?lang=ru&pagetype=prom">
                <i class="glyphicon glyphicon-file"></i>
                <span>Соискателям</span>
              </a>
            </li>
            <li class="<?=(($curId=='newspages'||($curId=='PageUpdate'&&$_GET['pagetype']=='news')) ? 'active' : '')?>">
              <a href="<?=$hUrl?>newspages">
                <i class="glyphicon glyphicon-flash"></i>
                <span>Новости</span>
              </a>
            </li>
            <li class="<?=(in_array($curId,['faq','faqedit','addfaq'])?'active':'')?>">
              <a href="<?=$hUrl?>faq">
                <i class="glyphicon glyphicon-info-sign"></i>
                <span>FAQ</span>
              </a>
            </li>
            <li class="<?=(in_array($curId,['admin','AdminEdit'])?'active':'')?>">
              <a href="<?=$hUrl?>admin">
                <i class="glyphicon glyphicon-sunglasses"></i>
                <span>Администраторы</span>
              </a>
            </li>   
          </ul>
        </li>
        <?php
        // ideas
        ?>
        <li class="<?=(in_array($curId, ['ideas','ideaedit'])?'active':'')?>">
          <a href="<?=$hUrl?>ideas">
            <i class="glyphicon glyphicon-leaf"></i>
            <span>Идеи/Предложения</span>
          </a>
        </li>
        <?php
        // Notifications
        ?>
        <li class="<?=($curId=='notifications'?'active':'')?>">
          <a href="<?=$hUrl?>notifications">
            <i class="glyphicon glyphicon-send"></i>
            <span>Уведомления</span>
          </a>
        </li>
        <?php
        // Settings
        ?>
        <li class="<?=($curId=='settings'?'active':'')?>">
          <a href="<?=$hUrl?>settings">
            <i class="glyphicon glyphicon-cog"></i>
            <span>Настройки сайта</span>
          </a>
        </li>  
        <?
        // System
        ?>
        <li class="<?=($curId=='system'?'active':'')?>">
          <a href="<?=$hUrl?>system">
            <i class="glyphicon glyphicon-wrench"></i>
            <span>Разработчикам</span>
          </a>
        </li>
        <?
        // Reviews
        ?>
        <li class="<?=($curId=='reviews'?'active':'')?>">
          <a href="<?=$hUrl?>reviews">
            <i class="glyphicon glyphicon-heart"></i>
            <span>Отзывы о нас</span>
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
          if( Yii::app()->controller->route !== 'site/index' )
            $this->breadcrumbs = array_merge(
                array(Yii::t('zii','Главная')=>Yii::app()->homeUrl), 
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
        <h1><small>PROMMU AD.TAB version 1.0</small></h1>
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
      <? foreach(Yii::app()->user->getFlashes() as $key => $message): ?>
        <div class="alert <?=$key?>"><?=$message?></div>
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

