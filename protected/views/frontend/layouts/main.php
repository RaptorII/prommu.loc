<?

	header('Content-Type: text/html; charset=utf-8');
	$baseUrl = Yii::app()->baseUrl;
	$curUrl = Yii::app()->request->requestUri;
	$SubdomainCache = Subdomain::getCacheData();
	$seoCacheId = $SubdomainCache->host . '/mainSeo' . $curUrl;
	$arSeo = Cache::getData($seoCacheId);
	if($arSeo['data']===false) {
		$arSeo['data'] = (new Seo)->exist($curUrl);
		Cache::setData($arSeo, 604800); // кешируем на неделю
	}
	if(isset($arSeo['data']['id']) && !$arSeo['data']['index'])
		Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array());
	$arSeo = $arSeo['data'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
		<? $this->renderPartial('../layouts/main_css'); // css MAIN STYLES ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php if(MOBILE_DEVICE): //mob device ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php endif;?>
    <?
			$title = $curUrl=='/' 
				? $arSeo['meta_title'] 
				: CHtml::encode($this->pageTitle);
    ?>
    <title><?php echo $title?></title>
    <?php if($this->ViewModel->getViewData()->pageMetaDesription): ?>
        <meta name="description" content="<?php echo $this->ViewModel->getViewData()->pageMetaDesription; ?>" />
    <?php endif; ?>
    <meta name="unitpay-verification" content="f984e7100a3d07777d78f3bc1afdb8" />
    <meta name="language" content="ru"/>
    <?//<meta name="google-site-verification" content="bEdRPmKNiZRvzw8kRs4jC-Jjijv52z8i3Uxo_Va-nXk"/>?>
    <meta name="google-site-verification" content="c2duy0oE7VkxAtjVxH--abHQtP-aYvzCQERllgdLOOQ"/>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <meta property="og:image" content="https://prommu.com/images/logo.png" />
    <?php /*if(MOBILE_DEVICE && !SHOW_APP_MESS): // mob device without show message ?>
        <?php
            Yii::app()->getClientScript()->registerScriptFile($baseUrl . '/theme/js/dist/libs.js', CClientScript::POS_END );
            Yii::app()->getClientScript()->registerScriptFile($baseUrl . '/theme/js/app-mob-mess.min.js', CClientScript::POS_END );
        ?>
    <?php else:*/ // other devices ?>
        <?php
					$controller = Yii::app()->controller->route;
					$action =  substr($controller, strpos($controller, '/'));
					$action=='/index' && $action = '/';
					$script = Yii::app()->getClientScript();
        	$arOptimizPages = array(DS, MainConfig::$PAGE_WORK_FOR_STUDENTS, MainConfig::$PAGE_SEARCH_EMPL, MainConfig::$PAGE_VACANCY, MainConfig::$PAGE_SEARCH_PROMO, MainConfig::$PAGE_SERVICES);
        		// array with optimization
            $script->registerScriptFile($baseUrl . '/theme/js/dist/libs.js', CClientScript::POS_HEAD);
            $script->registerScriptFile($baseUrl . '/jslib/sourcebuster.min.js', in_array($action, $arOptimizPages) 
            	? CClientScript::POS_END 
            	: CClientScript::POS_HEAD);
            $script->registerScriptFile($baseUrl . '/theme/js/dev/index.min.js', in_array($action, $arOptimizPages) 
            	? CClientScript::POS_END 
            	: CClientScript::POS_BEGIN);

					// FANCYBOX
					$script->registerScriptFile(MainConfig::$JS . 'dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
					$script->registerCssFile(MainConfig::$JS . 'dist/fancybox/jquery.fancybox.css');


            if(!in_array($action, $arOptimizPages)){
                $script->registerCssFile($baseUrl.'/jslib/bootstrap-datepicker/css/bootstrap-datepicker.min.css');
                $script->registerCssFile($baseUrl . '/' . MainConfig::$PATH_CSS . '/' . Share::$cssAsset['modalwindow.css']);
                $script->registerCssFile($baseUrl.'/theme/css/main-page.css');
                $script->registerScriptFile($baseUrl . '/theme/js/dist/jquery.maskedinput.min.js', CClientScript::POS_HEAD);
            }

            if( ($action = $this->action->getId()) == 'profile' && Share::$UserProfile->type == 3 )

                $action = 'company-profile-own';
            $G_PAGE = isset($this->ViewModel->getViewData()->pageKind) 
            ? $this->ViewModel->getViewData()->pageKind 
            : $action;

            $setup = ContentPlus::getActionInfo('ModSite');
            $lang = Share::getLangSelected();
            Share::getLanguages('login', $lang);
            $menu = new Menu;

            function checkParent($res, $id)
            {
                $result = false;
                foreach ($res as $row)
                    if ($row['parent_id'] == $id) 
                        $result = true;

                return $result;
            }

            function ShowChildren($res, $id)
            {
                foreach ($res as $row)
                    if ($row['parent_id'] == $id)
                        echo '<li><a href="' . $row['link'] . '">' . $row['name'] . '</a></li>' . "\r\n";
            }

            function ShowRootMenu($lang, $menu, $SubdomainCache)
            {
								$menuCacheId = $SubdomainCache->host . '/mainMenu';
								$res = Cache::getData($menuCacheId);
								if($res['data']===false) {
									$res['data'] = $menu->getTreeDB(0, $lang, 1, 0); // главное верхнее меню
									Cache::setData($res, 604800); // кешируем на неделю
								}
								$res = $res['data'];
                echo '<ul class="top-menu-wr__menu csson">';
                if($_SERVER['REQUEST_URI'] != '/'){
                    echo '<li class="item top-menu-wr__main-level"><a href="/">Главная</a></li>';
                }
                if(Share::isGuest())
                {
                    if($_SERVER['REQUEST_URI'] != MainConfig::$PAGE_LOGIN)
                    {
                        echo '<li class="item top-menu-wr__login-level"><a href="' . MainConfig::$PAGE_LOGIN . '">Вход</a></li>';
                    }
                    echo '<li class="item top-menu-wr__login-level"><a href="' . MainConfig::$PAGE_REGISTER . '">Регистрация</a></li>';
                }
                else
                {
                    echo '<li class="item top-menu-wr__login-level"><a href="' . MainConfig::$PAGE_LOGOUT . '">Выход</a></li>';
                }

                $mactive = ContentPlus::getActionID();
                foreach ($res as $row)
                {
                    $cl = "";
                    if ( $mactive && strpos($_SERVER['REQUEST_URI'], $row['link']) !== false ) $cl = " active";
                    if ($row['parent_id'] == 0) {
                        if (checkParent($res, $row['id'])) {
                            echo '<li class="item ' . $cl . ' top-menu-wr__submenu"><a href="' . DS . $row['link'] . '" class="top-menu-wr__submenu">' . $row['name'] . '</a>' . "\r\n";
                            echo '<ul>';
                            ShowChildren($res, $row['id']);
                            echo '</ul></li>';
                        }
                        else
                            echo '<li class="item ' . $cl . '"><a href="' . $row['link'] . '">' . $row['name'] . '</a></li>' . "\r\n";
                    }
                }
                echo '</ul>';
            }
            $iduser = Share::$UserProfile->id;
        ?>
        <? if($iduser):?>
            <script src="/firebase.js"></script>
            <script src="/app.js"></script>
        <? endif;?>
        <script>
            var FLAG_MOBILE = <?= MOBILE_DEVICE ? 1 : 0 ?>;
            var G_PAGE = '<?= $G_PAGE ?>';
            var G_USER_TYPE = '<?= Share::$UserProfile->type ?>';
            var G_ACTION_ID = '<?= ContentPlus::getActionID() ?>';
            var G_LOCALE = 'ru';
            var G_SITE = '<?= Subdomain::getSiteName() ?>';
        </script>
		<?php 
			// если не моб устройство
			//endif; 
		?>
    <? $this->renderPartial('../layouts/header_partial/' . $SubdomainCache->id); // data for every site ?>
</head>
<body class="<?= $this->ViewModel->getViewData('addBodyClass') ?>">
    <? $this->renderPartial('../layouts/body_partial/' . $SubdomainCache->id); // data for every site ?>
        <div class="hint-box"><b class="tri"></b><span></span></div>
        <div id="DiLoading"><img src="<?= MainConfig::$IMG_LOADING2 ?>" alt=""></div>
        <div class="error-hint-box"><div class="help-box"><span></span></div></div>
        <div id="DiSiteWrapp">
            <div id="DiTop">
                <div id="DiLogoWrapp" class="container-fluid">
                    <div class="container airSticky_stop-block">
                        <div class="row">
                            <div class="col-xs-12">
                                <a class="logo" href="/"></a>
                                <div id="description">
                                  <? if(empty(Yii::app()->request->pathInfo)): ?>
                                      <h1><?=$arSeo['seo_h1']?></h1>
                                  <? else: ?>
                                      <noindex><?=$SubdomainCache->label?></noindex>
                                  <? endif;?>
                                </div>
                                <?php if( Yii::app()->session['au_us_type'] < 2 ): ?>
                                  <div class="enter">
                                    <a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_LOGIN)?>" class="btn__orange"><span>Вход</span></a>
                                    <a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER)?>" class="btn__orange"><span>Регистрация</span></a>
                                  </div>
                                <?php else: ?>
                                    <div class="small-menu">
                                        <div class="small-menu__list">
                                            <? if(Share::isEmployer()): ?>
	                                            <div class="small-menu__item<?=($curUrl==MainConfig::$PAGE_VACPUB ? ' current' : '')?>">
	                                              <a href="<?=MainConfig::$PAGE_VACPUB?>" class="addvac">
	                                                <span class="small-menu__circle">
                                                        <span class="small-menu__icon icn-plus-prommu color-white"></span>
                                                    </span>
	                                              	<span class="small-menu__name">ДОБАВИТЬ ВАКАНСИЮ</span>
	                                              </a>
	                                            </div>
                                          	<? endif; ?>
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
                                                    <span class="small-menu__icon icn-trend-prommu color-white"></span>
                                                </span>
                                                <span class="small-menu__name">ОТЗЫВЫ И РЕЙТИНГИ</span>
                                            </a>
                                            <?php $link = MainConfig::$PAGE_CHATS_LIST ?>
                                            <a href="<?=$link?>" class="small-menu__item notice<?=(strpos($curUrl,$link)!==false ? ' current' : '')?>" id="sm-notice-cnt">
                                                <span class="small-menu__circle">
                                                    <b class="small-menu__cnt">0</b>
                                                    <span class="small-menu__icon icn-envelope-prommu color-white"></span>
                                                </span>
                                                <span class="small-menu__name">СООБЩЕНИЯ</span>
                                            </a>
                                            <?php $link = MainConfig::$PAGE_SETTINGS ?>
                                            <a href="<?=$link?>" class="small-menu__item settings<?=($curUrl==$link ? ' current' : '')?>">
                                                <span class="small-menu__circle">
                                                    <span class="small-menu__icon icn-cogs-double-prommu color-white"></span>
                                                </span>
                                                <span class="small-menu__name">НАСТРОЙКИ</span>
                                            </a>
                                        </div>
                                        <div class="small-menu__profile">
                                            <?php $user = Yii::app()->session['au_us_data']; ?>
                                            <a class="small-menu__username" href="<?=MainConfig::$PAGE_PROFILE?>" data-id="<?=$user->id?>"><span><?php
                                                if($user->firstname || $user->lastname):
                                                    echo $user->firstname . ' ' . $user->lastname;
                                                else:
                                                    echo $user->name;
                                                endif;
                                            ?></span></a>
                                            <a class="small-menu__btn" href="<?=MainConfig::$PAGE_LOGOUT?>"><b>ВЫХОД</b></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- root menu -->
                <div id="DiTopMenuWrapp">
                    <div class="container">
                        <div class="top-menu-wr -autopopup">
                            <span class="top-menu-wr__sandwich">
                                <span class="sandwich__line"></span>
                            </span>
                            <?php ShowRootMenu($lang, $menu, $SubdomainCache); ?>
                        </div>
                        <?
                        if(empty(Yii::app()->request->pathInfo)){
                            $title = '<span class="top-menu-wr__logo"></span>';
                        }
                        else{
                            $title = (!empty($this->ViewModel->getViewData()->htmlTitle) ? $this->ViewModel->getViewData()->htmlTitle : '<span class="top-menu-wr__logo"></span>');
                        }
                        ?>
                        <div class="mob-header__title"><?=$title?></div>
                    </div>
                </div>
                <?php
                    if( Share::$isHomePage ){ echo $content; }
                    else { include_once __DIR__ . '/../' . MainConfig::$VIEWS_COMM_CONTENT_TPL . '.php'; } // endif
                ?>
            </div>
            
            <div id="DiFooter">
                <div id="footer-head" class="container">
                  <!--Header of footer-->
                    <div class="row">
                      <div class="col-xs-12">
                          <a class="logo" href="/"></a>
                          <div id="description">
                              <span><noindex>Сервис №1 в поиске временной работы и персонала для BTL и Event-мероприятий</noindex></span>
                          </div>
                          <div class="enter">
                            <a class="feedback btn__green" href="<?=MainConfig::$PAGE_FEEDBACK?>">Обратная связь</a>
                            <?php if(!in_array(Share::$UserProfile->type, [2,3])): ?>
                                <a href="<?= MainConfig::$PAGE_LOGIN ?>" class="reg btn__orange">
                                    <span>Вход</span>
                                </a>
                                <a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER)?>" class="reg btn__orange">
                                    <span>Регистрация</span>
                                </a>
	                        <? endif; ?>
                          </div>
                      </div>
                    </div>
                </div>
                <div id="footer-content" class="">
                  <div class="container">
                    <div class="row">
                      <!--Content of footer-->
                              <div class="<?//* col-sm-7 col-lg-6*/?>col-xs-12 col-sm-9 col-lg-9 footer__big-menu">
		                          <div class="menu row">
		                              <div class="col-sm-6 col-lg-2">
		                                <a href="<?=MainConfig::$PAGE_VACANCY?>" rel="nofollow" class="footer__big-menu-link"><ins>Вакансии</ins></a>
		                                <a href="<?=MainConfig::$PAGE_SEARCH_PROMO?>" rel="nofollow" class="footer__big-menu-link"><ins>Анкеты</ins></a>
		                                <a href="<?=MainConfig::$PAGE_SEARCH_EMPL?>" rel="nofollow" class="footer__big-menu-link"><ins>Компании</ins></a>
		                              </div>
		                              <div class="col-sm-6 col-lg-4">
		                                <a href="<?=MainConfig::$PAGE_WORK_FOR_STUDENTS?>" rel="nofollow" class="footer__big-menu-link"><ins>Работа для студентов</ins></a>
		                                <a href="<?=MainConfig::$PAGE_SERVICES?>" rel="nofollow" class="footer__big-menu-link"><ins>Услуги</ins></a>
		                                <a href="<?=MainConfig::$PAGE_IDEAS_LIST?>" rel="nofollow" class="footer__big-menu-link"><ins>Идеи и предложения</ins></a>
		                              </div>
		                              <div class="col-sm-6 col-lg-2">
		                                <a href="<?=MainConfig::$PAGE_ABOUT?>" rel="nofollow" class="footer__big-menu-link"><ins>О нас</ins></a>
		                                <a href="<?=MainConfig::$PAGE_FAQ?>" rel="nofollow" class="footer__big-menu-link"><ins>FAQ</ins></a>
		                                <a href="<?=MainConfig::$PAGE_NEWS?>" rel="nofollow" class="footer__big-menu-link"><ins>Новости</ins></a>
		                              </div>
		                              <div class="col-sm-6 col-lg-4">
		                                <a href="<?=MainConfig::$PAGE_ARTICLES?>" rel="nofollow" class="footer__big-menu-link"><ins>Полезные статьи</ins></a>
		                                <a href="<? echo '/' . MainConfig::$PAGE_SITEMAP?>" class="footer__big-menu-link"><ins>Карта сайта</ins></a>
		                                <a href="<?=MainConfig::$PAGE_CONDITIONS?>" rel="nofollow" class="footer__big-menu-link"><ins>Правила Сервиса</ins></a>
		                              </div>
		                              <div class="clearfix"></div>
		                          </div>
                              </div>
                              <div class="hidden-xs hidden-sm col-md-1"></div>
                              <div class="col-xs-5 col-sm-3 col-lg-4 footer__download hidden-xs hidden-sm hidden-md hidden-lg">
                                <!--<p>Скачать в:</p>-->
                                <!--<div class="app-links">-->
                                <!--  <a href="<?=MainConfig::$LINK_TO_PLAYMARKET?>" rel="nofollow" class="app-bottom-link" target="_blank"></a>-->
                                <!--  <a href="<?=MainConfig::$LINK_TO_APP_STORE?>" rel="nofollow" class="appstore-bottom-link" target="_blank"></a>-->
                                <!--</div>-->
                              </div>
                              <div class="col-xs-12 col-sm-3 col-lg-2 join-us">
                                <div class="social footer__social">
                                    <!--noindex-->
                                    <p class="footer__social-text">Присоединяйтесь к нам: </p>
                                    <div class="social_icons">
                                        <a href="<?=MainConfig::$PROMMU_FACEBOOK?>" rel="nofollow" class="icon icon55 js-g-hashint" title="Facebook" target="_blank">
                                            <span class="icn-facebook-icon-prommu color-white"></span>
                                        </a>
                                        <a href="<?=MainConfig::$PROMMU_VKONTAKTE?>" rel="nofollow" class="icon icon55 js-g-hashint" title="Vkontakte" target="_blank">
                                            <span class="icn-vk-icon-prommu color-white"></span>
                                        </a>
                                        <a href="<?=MainConfig::$PROMMU_TELEGRAM?>" rel="nofollow" class="icon icon55 js-g-hashint" title="Telegram" target="_blank">
                                            <span class="icn-telegram-icon color-white"></span>
                                        </a>
                                    </div>
                                    <!--/noindex-->
                                </div>
                              </div>
                              <div class="clearfix"></div>
                    </div>
                  </div>
                </div>
                <div id="footer-tegs" class="container">
                    <!--Tegs-->
                    <div class="row footer__module">
                        <? foreach ($SubdomainCache->data as $id => $site): ?>
                            <? if($site['in_footer'] && $id!=$SubdomainCache->id): ?>
                                <a href="<?=$site['url']?>" class="footer__cities-link"><?=$site['city']?></a>
                            <? endif; ?>
                        <? endforeach; ?>
                        <a href="<?=MainConfig::$PAGE_OTHERCITIES?>" class="footer__cities-link" rel="nofollow" >Работа в других городах</a>
                    </div>
                </div>
                <div id="copyright" class="">
                  <!--noindex--><p class="footer__slogan">&copy; PROMMU <br><span>-</span> Сервис №1 в поиске временной работы и персонала для BTL и Event-мероприятий, <?=date('Y')?></p><!--/noindex-->
                </div>
            </div>
        </div>    
<?
//
//
//
foreach(Yii::app()->user->getFlashes() as $key => $message)
{
    if($key==='prommu_flash')
    { ?><div class="prmu__popup prommu_flash"><? echo $message;?></div><? }
}
?>
</body>
</html>
<?  
//  $sql = "SELECT odate, id_user
//          FROM user
//          WHERE is_online = 1";
//  $users = Yii::app()->db->createCommand($sql)->queryAll();

// $count = count($users);

// for ($i=0; $i < $count ; $i++) { 
// 	$to_time = strtotime(date("Y-m-d h-i-s"));
//  $from_time = $users[$i]['odate'];
//  $odate =  round(abs($to_time - $from_time) / 60,2);

// 	if($odate > 15) {

//         Yii::app()->db->createCommand()
//             ->update('user', array(
//     	'is_online' => 0,),
// 		'id_user=:id', array(':id'=>$users[$i]['id_user']));
        
//     }
//     else {

//       	Yii::app()->db->createCommand()
//             ->update('user', array(
//     		'odate'=>date("Y-m-d h-i-s"),
//     		'is_online' => 1,),
// 			'id_user=:id', array(':id'=>$users[$i]['id_user']));
//     }
// }

// if( Share::$UserProfile->id) {


//       	Yii::app()->db->createCommand()
//             ->update('user', array(
//     		'odate'=>date("Y-m-d h-i-s"),
//     		'is_online' => 1,),
// 			'id_user=:id', array(':id'=>Share::$UserProfile->id));
  
// }
?>
<script>
/*$(document).ready(function(){
    setTimeout(function(){
        var name = "_ga"
        var matches = document.cookie.match(new RegExp(
            name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        matches = decodeURIComponent(matches);
        var tmp = matches.split('.');
        var cid = tmp[2] + '.' + tmp[3];
        cid = cid.split(',');
        cid = cid[0];

        if (cid == "undefined.undefined") {
            var tracker = ga.getAll()[0];
            cid = tracker.get('clientId');
            console.log("cid from gtm!");
        } else {
            console.log("cid from cookie!");
        }

        $.ajax({
            type: 'GET',
            url: '/ajax/createclient/?client='+cid,
            data: cid,
            success: function(res){
            }
        });

    }, 3000);
});*/
</script>

