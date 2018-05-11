<?php /* @var $this Controller */ ?>
<?php if(Yii::app()->user->isGuest) {echo $content;  } else {?>

<? $model = new Feedback;
   $model = $model->getDatAdmin();
   $counF = count($model); 

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
               <span class="label label-danger"><?=$counF?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <?for($i = 0; $i < $counF; $i++):?>
              <li style="padding:0px;height: auto;" class="user-header">
          
              
                <?if($model[$i]['type'] == 0){ 
                 echo '<a style=" white-space: unset;   background-color: #e1e3e9;" href="/admin/site/mail/' . $model[$i]['id']. '" rel="tooltip" data-placement="top" title="Ответить"><p>'.$model[$i]['id'].'-'.$model[$i]['theme'].'</p></a>';
               }
                 else {
                  echo '<a style=" white-space: unset;   background-color: #e1e3e9;" href="/admin/site/update/' . $model[$i]['chat'] . '" rel="tooltip" data-placement="top" title="Ответить"><p>'.$model[$i]['id'].'-'.$model[$i]['theme'].'</p></a> ';
                 }
                ?>
              
            
              </li>
              <? endfor;?>
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

      <!-- search form (Optional) -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <?php
        $hUrl = Yii::app()->homeUrl . $this->id . '/';
        $curId = $this->action->id;

echo "<pre style='display:none'>";
print_r($curId); 
echo "</pre>";
      ?>


      <ul class="sidebar-menu">
        <li class="header">НАВИГАТОР</li>
        <?php
        // users
        ?>
        <?php 
          $enable = in_array($curId, ['users','empl','wait','PromoEdit','EmplEdit','analytic','analyticspb']);
          $enableA = in_array($curId, ['users','PromoEdit']);
          $enableA = (in_array($curId, ['wait','analytic','analyticspb']) && $_GET['type']==2) ? true : $enableA;
          $enableE = in_array($curId, ['empl','EmplEdit']);
          $enableE = (in_array($curId, ['wait','analytic','analyticspb'])  && $_GET['type']==3) ? true : $enableE;
        ?>
        <li class="treeview<?=$enable ? ' active' : ''?>">
          <a href="#">
            <i class="glyphicon glyphicon-registration-mark"></i>
            <span>Регистрации</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu<?=$enable ? ' menu-open' : ''?>"<?=!$enable ? ' style="display:none"' : ''?>>
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
                <li class="<?=($curId=='analytic' && $_GET['type']==2?'active':'')?>">
                  <a href="<?=$hUrl?>analytic?type=2">
                    <i class="glyphicon glyphicon-text-background"></i>
                    <span>Аналитика</span>
                  </a>
                </li>
                <li class="<?=($curId=='analyticspb' && $_GET['type']==2?'active':'')?>">
                  <a href="<?=$hUrl?>analyticspb?type=2">
                    <i class="glyphicon glyphicon-text-background"></i>
                    <span>Аналитика spb</span>
                  </a>
                </li>
                <li class="">
                  <a href="#" onclick="alert('Страница в разработке'); return false">
                    <i class="glyphicon glyphicon-thumbs-up"></i>
                    <span>Оценка персонала</span>
                  </a>
                </li>
                <li class="">
                  <a href="#" onclick="alert('Страница в разработке'); return false">
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
                <li class="<?=($curId=='analytic' && $_GET['type']==3?'active':'')?>">
                  <a href="<?=$hUrl?>analytic?type=3">
                    <i class="glyphicon glyphicon-text-background"></i>
                    <span>Аналитика</span>
                  </a>
                </li>
                <li class="<?=($curId=='analyticspb' && $_GET['type']==3?'active':'')?>">
                  <a href="<?=$hUrl?>analyticspb?type=3">
                    <i class="glyphicon glyphicon-text-background"></i>
                    <span>Аналитика spb</span>
                  </a>
                </li>
                <li class="">
                  <a href="#" onclick="alert('Страница в разработке'); return false">
                    <i class="glyphicon glyphicon-thumbs-up"></i>
                    <span>Оценка персонала</span>
                  </a>
                </li>
                <li class="">
                  <a href="#" onclick="alert('Страница в разработке'); return false">
                    <i class="glyphicon glyphicon-heart"></i>
                    <span>Отзывы</span>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <?php
        // vacancies
        ?>
        <?php 
          $enable = in_array($curId, ['vacancy','vacancymail','VacancyEdit']);
          $enable = ($curId=='vacancy' && $_GET['seo']==1) ? false : $enable;
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
        <?php $enable = in_array($curId, ['services','servicess','cards','medcards']) ?>
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
            <li class="<?//=($curId=='vacancymail'?'active':'')?>">
              <a href="#" onclick="alert('Страница в разработке'); return false">
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
            <li class="<?=($curId=='cards'?'active':'')?>">
              <a href="<?php echo $hUrl?>cards">
                <i class="glyphicon glyphicon-credit-card"></i>
                <span>Карта Prommu</span>
              </a>
            </li>
            <li class="<?=($curId=='medcards'?'active':'')?>">
              <a href="<?php echo $hUrl?>medcards">
                <i class="glyphicon glyphicon-plus-sign"></i>
                <span>Мед. книга</span>
              </a>
            </li>
            <li class="<?//=($curId=='vacancymail'?'active':'')?>">
              <a href="#" onclick="alert('Страница в разработке'); return false">
                <i class="glyphicon glyphicon-cog"></i>
                <span>API</span>
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
                <span>SEO фильтр</span>
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
          $enable = in_array($curId, ['newspages','admin','AdminEdit']); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
          $enable = ($curId=='PageUpdate' && in_array($_GET['pagetype'],['news','about'])) ? true : $enable;
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
            <li class="<?//=($curId=='vacancy'?'active':'')?>">
              <a href="#" onclick="alert('Страница в разработке'); return false">
                <i class="glyphicon glyphicon-file"></i>
                <span>Работа для студентов</span>
              </a>
            </li>
            <li class="<?//=($curId=='vacancy'?'active':'')?>">
              <a href="#" onclick="alert('Страница в разработке'); return false">
                <i class="glyphicon glyphicon-file"></i>
                <span>Работодателям</span>
              </a>
            </li>
            <li class="<?//=($curId=='vacancy'?'active':'')?>">
              <a href="#" onclick="alert('Страница в разработке'); return false">
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
            <li class="<?//=($curId=='vacancy'?'active':'')?>">
              <a href="#" onclick="alert('Страница в разработке') return false">
                <i class="glyphicon glyphicon-file"></i>
                <span>ФАК</span>
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
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      
        <small>Система управления PROMMU AD.TAB version 1.0</small>
      </h1>
      <!-- <ol class="breadcrumb">pro
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol> -->
      
          
     
    </section>

    <!-- Main content -->
    <section class="content">
    
 <?php echo $content; ?> 

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

