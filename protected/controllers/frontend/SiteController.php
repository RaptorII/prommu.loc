<?php

class SiteController extends AppController
{
    function __construct($id,$module=null)
    {
        parent::__construct($id,$module);
        // set lang
        $lang = Yii::app()->session['lang'];
        if (empty($lang)) {
            $lang = 'ru';
            Yii::app()->session['lang'] = 'ru';
        }

        // проверка авторизации
        $this->doAuth();
    }


    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),

            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        Share::$isHomePage = 1;
        $city = Subdomain::getCity(Share::$UserProfile->type,Share::$UserProfile->id);
        $Vacancy = new Vacancy();
        $Vacancy->chkVacsEnds();   
        $model = new PagesContent;
        $news = new News;
        $articles = new Articles;
        $action = ContentPlus::getActionID();
        $lang = Yii::app()->session['lang'];

        $data['content'] = $content = $model->getPageContent('about', $lang);
        $data['vacancies'] = $model->getVacanies($lang);
        $data['vacs'] = $model->getVacaniesAppointments($lang);
        $data['applicants'] = $model->getApplicants($lang);
        $data['companies'] = $model->getCompanies($lang);
        $data['news'] = $news->getNews();
        $data['articles'] = $articles->getArticles();
        $data['couArt'] = $articles->getArticlesCount();
        //
        $time .= 'L02.' . microtime(true);
        //
        if(MainConfig::$DEBUG_TIMER)
            Yii::app()->request->cookies['index_timers'] = new CHttpCookie('index_timers', $time);
        //
        $this->render(
          'index', 
          array('content' => $data, 'city'=>$city)
        );
    }


    public function actionPage()
    {
        $action = ContentPlus::getActionID();
        if ($action != '') {
            if($action=='help' || $action=='support' || $action=='students'){
                throw new CHttpException(404, 'Error');
            }
            elseif($action=='work-for-students'){
                $this->render(MainConfig::$VIEWS_WORK_FOR_STUDENTS, array(), array());
            }
            else{
                $model = new PagesContent;
                $lang = Yii::app()->session['lang'];
                $content = $model->getPageContent($action, $lang);
                $title = $content['name'];
                $this->breadcrumbs = array($title => array(MainConfig::$PAGE_PAGES . DS . $content['link']));
                $this->render(MainConfig::$VIEWS_DB_PAGES, array('content' => $content), array('pageTitle' => $title));
            }
        }
        else
            $this->render('error');
    }


    public function actionPhoto()
    {
        if (isset($_POST['op'])) {
            if ($_POST['op'] != '') {
                $id = intval($_POST['id']);
                $op = $_POST['op'];
                if ($op == 'DEL') {
                    $res = Yii::app()->db->createCommand()
                        ->select("id, photo")
                        ->from('photo')
                        ->where("id = :id", array(':id' => $id))
                        ->queryRow();
                    $path = $_SERVER['DOCUMENT_ROOT'] . '/content/' . $res['photo'];
                    try
                    {
                        if (file_exists($path)) {
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/content/' . $res['photo']);
                            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/content/thumbs/' . $res['photo']))
                                unlink($_SERVER['DOCUMENT_ROOT'] . '/content/thumbs/' . $res['photo']);
                            // delete from DB
                            $res = Yii::app()->db->createCommand()
                                ->delete('photo', 'id=:id', array(':id' => $id));
                        }
                    } catch (Exception $e) {
                    }
                } else if ($op == 'ADD') {
                    $uid = Share::getUserID();
                    $photo_name = $_POST['photo_name'];
                    Yii::app()->db->createCommand()
                        ->insert('photo', array(
                        'photo' => $photo_name,
                        'id_user' => $uid,
                    ));
                }
            }
        }
        $this->render('register/form_photo');
    }


    public function actionSearchEmpl()
    {
        if(Yii::app()->request->isAjaxRequest){
            if(Yii::app()->getRequest()->isPostRequest){
                $SearchEmpl = (new SearchEmpl());
                $data = $SearchEmpl->searchFilterData();
                $this->renderPartial(MainConfig::$VIEWS_SEARCH_EMPL_AJAX_FILTER, array('viData' => $data), false, true);                
            }
            else{
                if(
                    (!isset($_GET['cities']) && !isset($_GET['cotype']))||
                    (sizeof($_GET['cities'])==1 && $_GET['cities'][0]==Subdomain::getId() && !isset($_GET['cotype']))
                )// for SEO MOSCOW
                    $seo = (new Seo)->exist(MainConfig::$PAGE_SEARCH_EMPL);

                $SearchEmpl = (new SearchEmpl());
                $arCount = $SearchEmpl->searchEmployersCount();

                if(!empty($_GET) && sizeof($arCount)){
                    $id = isset($_GET['cities'][0]) ? $_GET['cities'][0] : 0;
                    $arSID = Subdomain::getIdies();
                    if(!in_array($id, $arSID))
                        Yii::app()->request->cookies['srch_e_city'] = new CHttpCookie('srch_e_city', $id);
                    Yii::app()->request->cookies['srch_e_res'] = new CHttpCookie('srch_e_res', 0);
                }

                $pages = new CPagination(sizeof($arCount));
                $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
                $pages->applyLimit($SearchEmpl);
                $data = $SearchEmpl->getEmployers(1);
                $data['count'] = $arCount;
                //$redirectUrl = Subdomain::ajaxFilterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);
                $redirectUrl = '';

                $this->renderPartial(
                    MainConfig::$VIEWS_SEARCH_EMPL_AJAX_BLOCK,
                    array(
                        'viData' => $data, 
                        'pages' => $pages,
                        'seo' => $seo,
                        'redirect' => $redirectUrl
                    ), 
                    false, 
                    true
                );
            }
        }
        else{
            //Subdomain::filterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);

            $this->setBreadcrumbs($title = "Поиск работодателей", MainConfig::$PAGE_SEARCH_EMPL);

            $SearchEmpl = (new SearchEmpl());
            $data['filter'] = $SearchEmpl->searchEmplForFilter(); // данные для фильтра
            //
            //  КУКИ
            // для редиректа, если в выбранном ранее городе нет резлультата 0 - данных нет, 1 - есть
            /*if(!isset(Yii::app()->request->cookies['srch_e_res']->value))
                Yii::app()->request->cookies['srch_e_res'] = new CHttpCookie('srch_e_res', 0);
            $cooHCRes = Yii::app()->request->cookies['srch_e_res']->value;
            // город для фильтра 0 - нет города, >0 - id города
            if(!isset(Yii::app()->request->cookies['srch_e_city']->value)){ 
                $cooCity = Yii::app()->request->cookies['city']->value;
                Yii::app()->request->cookies['srch_e_city'] = new CHttpCookie('srch_e_city', 0);
                $arSID = Subdomain::getIdies();
                foreach($data['filter']['cities'] as $id => $city)
                    if($cooCity==$city['seo']){// ищем ID города из куки
                        if(!in_array($id, $arSID))
                            Yii::app()->request->cookies['srch_e_city'] = new CHttpCookie('srch_e_city', $id);
                        break;
                    }
            }
            $cooHCity = Yii::app()->request->cookies['srch_e_city']->value;*/
            //
            //  РЕДИРЕКТЫ
            //
            /*if($cooHCity && empty($_GET) && $cooHCRes!=1){ // переход на выбранный город
                $this->redirect(MainConfig::$PAGE_SEARCH_EMPL . '?cities[]=' . $cooHCity);
                exit();
            }*/

            $arCount = $SearchEmpl->searchEmployersCount(); // кол-во найденных
            // 
            /*if($cooHCity==$_GET['cities'][0] && sizeof($_GET['cities'])==1 && !sizeof($arCount)){ 
                Yii::app()->request->cookies['srch_e_res'] = new CHttpCookie('srch_e_res', 1);
                $this->redirect(MainConfig::$PAGE_SEARCH_EMPL);
                exit();
            }
            //записываем выбранный город
            if(!empty($_GET) && sizeof($arCount)){
                $id = isset($_GET['cities'][0]) ? $_GET['cities'][0] : 0;
                $arSID = Subdomain::getIdies();
                if(!in_array($id, $arSID))
                    Yii::app()->request->cookies['srch_e_city'] = new CHttpCookie('srch_e_city', $id);
                Yii::app()->request->cookies['srch_e_res'] = new CHttpCookie('srch_e_res', 0);
            }*/

            $pages = new CPagination(sizeof($arCount));
            $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
            $pages->applyLimit($SearchEmpl);
            $data = array_merge($SearchEmpl->getEmployers(1), $data);

            if( 
                (sizeof($_GET['cities'])==1 && $_GET['cities'][0]==Subdomain::getId()) || 
                (!isset($_GET['cities']) && !isset($_GET['cotype'])) 
            ){// for SEO MOSCOW
                $seo = (new Seo)->exist(MainConfig::$PAGE_SEARCH_EMPL);
                $title = $seo['meta_title'];
                $h1 = $seo['seo_h1'];
            }    

            $this->render($this->ViewModel->pageSearchEmpl,
                    array('viData' => $data, 
                        'pages' => $pages,
                        'count' => sizeof($arCount),
                        'seo' => $seo
                    ),
                    array(
                        'htmlTitle' => $title,
                        'pageTitle' => '<h1>' . ($h1 ? $h1 : $title) .'</h1>'
                    )
                );
        }
    }




    public function actionAnkety()
    {
    $time = 'L00.' . microtime(true);

        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
    $time .= 'L01.' . microtime(true).'-ID-'.$id;
        if(!empty($id))
        {
            if( $id < 1 ) 
                $this->redirect(MainConfig::$PAGE_LOGIN);

            if( $id == Share::$UserProfile->id ) 
                $this->redirect($this->createUrl(MainConfig::$PAGE_PROFILE));

            $Profile = (new ProfileFactory())->makeProfile(array('id' => $id));

            if( $Profile instanceof UserProfile )
            {
                $data = $Profile->getProfileDataView();
                $page = $Profile->viewTpl;
        $time .= 'L02.' . microtime(true);
            }
            else
            {
               $data = array('error' => 1, "message" => "Пользователь не найден");
               throw new CHttpException(404, 'Error'); 
            } // endif

            // проверка на мой профиль
            $flagOwnProfile = $id == Share::$UserProfile->id;

            Yii::app()->getClientScript()->registerCssFile("/jslib/magnific-popup/magnific-popup-min.css");
            Yii::app()->getClientScript()->registerScriptFile("/jslib/magnific-popup/jquery.magnific-popup.min.js", CClientScript::POS_END);

            $strBreadcrumb = '';
            if( $Profile->type == 2 ){
                $strBreadcrumb = $fio = array_values($data['userInfo']['userAttribs'])[0]['firstname'] . ' ' . array_values($data['userInfo']['userAttribs'])[0]['lastname'];
                $strBreadcrumb = 'Профиль соискателя - ' . $strBreadcrumb;
                $this->setBreadcrumbs($title = "Поиск соискателей", MainConfig::$PAGE_SEARCH_PROMO);
        $time .= 'L03.' . microtime(true);
            }
            if( $Profile->type == 3 ){
                $Termostat = new Termostat();
                $Termostat->setTermostat($id, Share::$UserProfile->id ? Share::$UserProfile->id : 0, 'empl' );

                $strBreadcrumb = $data['userInfo']['name'];
                $strBreadcrumb = 'Профиль работодателя - ' . $strBreadcrumb;
                $this->setBreadcrumbs("Поиск работодателей", MainConfig::$PAGE_SEARCH_EMPL);            
        $time .= 'L04.' . microtime(true);
            }
            if(strlen($strBreadcrumb) > 0){
                $strBreadcrumb = html_entity_decode($strBreadcrumb);
                $this->setBreadcrumbsEx(array($strBreadcrumb, MainConfig::$PAGE_PROFILE_COMMON . DS . $id)); 
            }    
            $this->ViewModel->setViewData('pageTitle', '<h1>' . $strBreadcrumb . '</h1>');
            // индексируем только города субдомена
            $arCities = Subdomain::getCitiesIdies(false, 'arr');
            reset($data['userInfo']['userCities'][0]);
            $idCity = key($data['userInfo']['userCities'][0]);
            if($idCity>0 && !in_array($idCity, $arCities))
                Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array());

            $this->render('../' . MainConfig::$DIR_VIEWS_USER . DS . $page,
                    array('viData' => $data,
                            'idus' => $id,
                            'flagOwnProfile' => $flagOwnProfile,
                            'Profile' => $Profile,
                        ),
                    array('pageTitle' => $s1)
                );
        }
        elseif($id==='0'){
            throw new CHttpException(404, 'Error'); 
        }
        else
        {
            if(!isset(Yii::app()->request->cookies['srch_a_view']->value))
                Yii::app()->request->cookies['srch_a_view'] = new CHttpCookie('srch_a_view', 'list');

            if(Yii::app()->request->isAjaxRequest){
                if(isset($_GET['view'])){
                    Yii::app()->request->cookies['srch_a_view'] = new CHttpCookie('srch_a_view', $_GET['view']);
                }

                $SearchPromo = (new SearchPromo());

                if(!empty($_GET)){
                    $seo = $SearchPromo->getPromoSeo($_GET, MainConfig::$PAGE_SEARCH_PROMO, Subdomain::getId()); // Moscow ID  
                    $seo['url'] = $SearchPromo->buildPrettyUrl($_GET);
                }         

                $count = $SearchPromo->searchPromosCount();

                //записываем выбранный город
                if(!empty($_GET) && $count){
                    $id = isset($_GET['cities'][0]) ? $_GET['cities'][0] : 0;
                    $arSID = Subdomain::getIdies();
                    if(!in_array($id, $arSID))
                        Yii::app()->request->cookies['srch_a_city'] = new CHttpCookie('srch_a_city', $id);
                    Yii::app()->request->cookies['srch_a_res'] = new CHttpCookie('srch_a_res', 0);
                }

                $pages=new CPagination($count);
                $pages->pageSize = 21;
                $pages->applyLimit($SearchPromo);
                $data = $SearchPromo->getPromos();
                //$redirectUrl = Subdomain::ajaxFilterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);
                $redirectUrl = '';

                $this->renderPartial(
                    MainConfig::$VIEWS_SEARCH_PROMO_AJAX,
                    array(
                        'viData' => $data, 
                        'pages' => $pages,
                        'count' => $count,
                        'seo' => $seo,
                        'redirect' => $redirectUrl
                    ), 
                    false, 
                    true
                );
        $time .= 'L05.' . microtime(true);
            }
            else{
                //Subdomain::filterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);

                $this->setBreadcrumbs($title = "Поиск соискателей", MainConfig::$PAGE_SEARCH_PROMO);

                $SearchPromo = (new SearchPromo());
                // build pretty URL
                if(!(MOBILE_DEVICE && !SHOW_APP_MESS)){ // optimization for mobile devices
                    if(!isset($_GET['seo_builded2']) && sizeof($_GET))
                    {
                        $url = $SearchPromo->buildPrettyUrl($_GET);
                        $this->redirect($url);
                        exit();
                    }
                    // search Cities
                    /*$Q1 = Yii::app()->db->createCommand()
                        ->select('t.id_city id, t.name, t.seo_url')
                        ->from('city t')
                        ->limit(10000);
                    $arCities = $Q1->queryAll();
                    //
                    //  КУКИ
                    // для редиректа, если ранее выбран город  1 - данных нет, 0 - есть
                    if(!isset(Yii::app()->request->cookies['srch_a_res']->value))
                        Yii::app()->request->cookies['srch_a_res'] = new CHttpCookie('srch_a_res', 0);
                    $cooHCRes = Yii::app()->request->cookies['srch_a_res']->value;
                    // город для фильтра 0 - нет города, >0 - id города
                    if(!isset(Yii::app()->request->cookies['srch_a_city']->value)){ 
                        $cooCity = Yii::app()->request->cookies['city']->value;
                        Yii::app()->request->cookies['srch_a_city'] = new CHttpCookie('srch_a_city', 0);
                        $arSID = Subdomain::getIdies();
                        foreach ($arCities as $k => $city)
                            if($cooCity==$city['seo_url']){
                                if(!in_array($id, $arSID))
                                    Yii::app()->request->cookies['srch_a_city'] = new CHttpCookie('srch_a_city', $id);
                                $cityName = $city['name'];
                                $cityId = $city['id'];
                                break;
                            }
                    }
                    $cooHCity = Yii::app()->request->cookies['srch_a_city']->value;
                    //
                    //  РЕДИРЕКТЫ
                    // 
                    if($cooHCity && empty($_GET) && $cooHCRes!=1){ // переход на выбранный город
                        $_GET['cities'][] =  $cooHCity;
                        $url = $SearchPromo->buildPrettyUrl($_GET);
                        $this->redirect($url);
                        exit();
                    }*/

                    $count = $SearchPromo->searchPromosCount();
                    // переход на общую, если в выбранном городе нет данных
                    /*if($cooHCity==$_GET['cities'][0] && sizeof($_GET['cities'])==1 && !$count){ 
                        Yii::app()->request->cookies['srch_a_res'] = new CHttpCookie('srch_a_res', 1);
                        $this->redirect(MainConfig::$PAGE_SEARCH_PROMO);
                        exit();
                    }
                    //записываем выбранный город
                    if(!empty($_GET) && $count){
                        $id = isset($_GET['cities'][0]) ? $_GET['cities'][0] : 0;
                        $arSID = Subdomain::getIdies();
                        if(!in_array($id, $arSID))
                            Yii::app()->request->cookies['srch_a_city'] = new CHttpCookie('srch_a_city', $id);
                        Yii::app()->request->cookies['srch_a_res'] = new CHttpCookie('srch_a_res', 0);
                    }*/

                    $sID = Subdomain::getId();
                    $seo = $SearchPromo->getPromoSeo($_GET, MainConfig::$PAGE_SEARCH_PROMO, $sID); // Moscow ID    
                    if(is_array($seo)){
                        $title = $seo['meta_title'];
                        $h1 = $seo['seo_h1'];
                    }

                    $pages=new CPagination($count);
                    $pages->pageSize = 21;
                    $pages->applyLimit($SearchPromo);
                    $data = $SearchPromo->getPromos();

                    $Api = new Api();
                    $post = $Api->kew();
                }

        //$time .= 'L14.' . microtime(true);
        //if (MainConfig::$DEBUG_TIMER)
        //          Yii::app()->request->cookies['ankety_timers'] = new CHttpCookie('ankety_timers', $time);


                $this->render($this->ViewModel->pageSearchPromo,
                        array(
                            'viData' => $data, 
                            'pages' => $pages,
                            'datas' => $post,
                            'count' => $count,
                            //'arCities' => $arCities,
                            //'cityName' => $cityName,
                            //'cityId' => $cityId,
                            'seo' => $seo
                        ),
                        array(
                            'htmlTitle' => $title,
                            'pageTitle' => '<h1>' . ($h1 ? $h1 : $title) .'</h1>'
                        )
                    );
            }
        }
    }


    public function actionResponseEmpl()
    {
        $this->render('response/empl');
    }

    public function actionResponsePromo()
    {
        $this->render('response/promo');
    }

    public function actionRating()
    {
        $this->render('rating/view');
    }

    /**
     * @deprecated
     */
    public function actionRatingEmpl()
    {
        $this->render('rating/empl');
    }

    public function actionRatingPromo()
    {
        $this->render('rating/promo');
    }



    public function actionSitepage()
    {
        if (isset($_GET['uid'])) {
            $uid = $_GET['uid'];
            $uid = str_replace('/','',$uid);
            $res = Yii::app()->db->createCommand()
                ->select("u.id_user, r.id_ra")
                ->rightJoin('ra r', 'r.id_user = u.id_user')
                ->from('user_work u')
                ->where('uid = :uid', array(':uid' => $uid))
                ->queryRow();
            $id = $res['id_ra'];

            $model = RaContent::model()->findByPk($id);
            if (!isset($model->content)) $model = new RaContent;
            if (isset($_POST['RaContent'])) {
                $model->attributes = $_POST['RaContent'];
                $model->id_ra = $id;
                $model->save();
            }
            $this->render('pages/form', array('id' => $id, 'model' => $model));
        } else {
            $this->actionIndex();
        }
    }


    ///Отображение вакансий
    public function actionVacancy()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        
        if( Yii::app()->getRequest()->isPostRequest && Yii::app()->getRequest()->getParam('mess') ){
            $res = (new VacDiscuss())->postMessage();
            if(!$res['error'] )
                $this->redirect("/vacancy/$id" . '?info=dialog');
        }

        if(empty($id)){
            $tmp = explode('/', $_SERVER['REQUEST_URI']);
            if(sizeof($tmp)==3){
                $id = (int)$tmp[2];
                if(!$id && sizeof($_GET)==3 && $_GET['sex']==3)
                    throw new CHttpException(404, 'Error');
            }
        }
        else{
            $this->redirect(MainConfig::$PAGE_SEARCH_VAC);
        }


        if(!empty($id))
        {
            $Termostat = new Termostat();
            $Termostat->setTermostat($id, Share::$UserProfile->id ? Share::$UserProfile->id : 0, 'vacancy' );
            if( Yii::app()->getRequest()->isPostRequest && Yii::app()->getRequest()->getParam('mess') )
            {
                $res = (new VacDiscuss())->postMessage();
                if(!$res['error'])
                    $this->redirect("/vacancy/$id" . '#tabs');
            }


            if(strpos($_SERVER['REQUEST_URI'], 'site/vacancy/') > -1)
            {
                header('Location: '.str_replace('site/vacancy/', 'vacancy/', $_SERVER['REQUEST_URI']));
                exit();
            }

            $vac = (new Vacancy())->getVacancyView($id);
            // if vacancy from subdomains
            /*if(sizeof($vac['vac']['city'])==1) {
                $arSID = Subdomain::getIdies();
                foreach ($vac['vac']['city'] as $id => $c)
                    if(in_array($id, $arSID)) {
                        $arSub = Subdomain::getData(true);
                        header("Location: " . $arSub[$id]['url'] . MainConfig::$PAGE_VACANCY . DS . $id);
                        exit();
                    }
            }*/

            if(isset($vac['error']) && $vac['error'] == 1)
            {
                throw new CHttpException(404, 'Error'); 
            }

            $this->setBreadcrumbs($title = "Поиск вакансий", MainConfig::$PAGE_SEARCH_VAC);

            if(Share::$UserProfile->type==3 && $vac['vac']['idus']==Share::$UserProfile->id){
                Yii::app()->session['editVacId'] = $id;  
            }

            // индексируем только если владелец вакансии с этого региона
            $arCities = Subdomain::getCitiesIdies(false, 'arr');
            $res = Yii::app()->db->createCommand()
                ->select('id_city')
                ->from('user_city')
                ->where('id_user=:id',array(':id'=>$vac['vac']['idus']))
                ->queryRow();

            $view = $this->ViewModel->pageVacancy;

            if(Yii::app()->getRequest()->getParam('info') && $vac['vac']['idus']==Share::$UserProfile->id){
                !in_array(Share::$UserProfile->type, [2,3]) && $this->redirect(MainConfig::$PAGE_LOGIN);
                $view = MainConfig::$VIEWS_VAC_TAB_RESP;
            }

            $this->render($view,
                array('viData' => $vac, 'id' => $id),
                array('pageTitle' => $title)
            );
        }
        else
        {
            if(!isset(Yii::app()->request->cookies['vacancies_page_view']->value))
                Yii::app()->request->cookies['vacancies_page_view'] = new CHttpCookie('vacancies_page_view', 'list');

            if(Yii::app()->request->isAjaxRequest){
                if(isset($_GET['view'])){
                    Yii::app()->request->cookies['vacancies_page_view'] = new CHttpCookie('vacancies_page_view', $_GET['view']);
                }

                $SearchVac = (new SearchVac());
                $sID = Subdomain::getId();
                $seo = $SearchVac->getVacancySeo($_GET, MainConfig::$PAGE_SEARCH_VAC, $sID); // Moscow ID
                $seo['url'] = $SearchVac->buildPrettyUrl($_GET);
                $count = $SearchVac->searchVacationsCount();
                //записываем выбранный город
                if(!empty($_GET) && $count){
                    $id = isset($_GET['cities'][0]) ? $_GET['cities'][0] : 0;
                    $arSID = Subdomain::getIdies();
                    if(!in_array($id, $arSID))
                        Yii::app()->request->cookies['srch_v_city'] = new CHttpCookie('srch_v_city', $id);
                    Yii::app()->request->cookies['srch_v_res'] = new CHttpCookie('srch_v_res', 0);
                }
                $pages=new CPagination($count);
                $pages->pageSize = 24;
                $pages->applyLimit($SearchVac);
                $data = $SearchVac->getVacations();
                //$redirectUrl = Subdomain::ajaxFilterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);
                $redirectUrl = '';

                $this->renderPartial(
                    MainConfig::$VIEWS_SEARCH_VAC_AJAX,
                    array(
                        'viData' => $data, 
                        'pages' => $pages,
                        'count' => $count,
                        'seo' => $seo,
                        'redirect' => $redirectUrl
                    ), 
                    false, 
                    true
                );
            }
            else{
                //Subdomain::filterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);

                if($_SERVER['REQUEST_URI'] == '/vacancy/about')
                    throw new CHttpException(404, 'Error');
                $this->setBreadcrumbs($title = "Поиск вакансий", MainConfig::$PAGE_SEARCH_VAC);

                $SearchVac = (new SearchVac());

                if(
                    (!isset($_GET['seo_builded2']) && sizeof($_GET))
                    ||
                    ((sizeof($_GET['template_url_params']['cities'])>1 || sizeof($_GET['template_url_params']['occupations'])>1) && strpos($_SERVER['REQUEST_URI'],'?')===false)
                ) // build pretty URL
                {   
                    $url = $SearchVac->buildPrettyUrl($_GET);
                    $this->redirect($url);
                    exit();
                }
                // cities for select
                /*$Q1 = Yii::app()->db->createCommand()
                    ->select('t.id_city id, t.name, t.seo_url')
                    ->from('city t')
                    ->where("t.id_co = 1")
                    ->limit(10000);
                $arCities = $Q1->queryAll();*/
                //
                //  КУКИ
                // для редиректа, если в ранее выбран город  1 - данных нет, 0 - есть
                /*if(!isset(Yii::app()->request->cookies['srch_v_res']->value))
                    Yii::app()->request->cookies['srch_v_res'] = new CHttpCookie('srch_v_res', 0);
                $cooHCRes = Yii::app()->request->cookies['srch_v_res']->value;
                // город для фильтра 0 - нет города, >0 - id города
                if(!isset(Yii::app()->request->cookies['srch_v_city']->value)){ 
                    $cooCity = Yii::app()->request->cookies['city']->value;
                    Yii::app()->request->cookies['srch_v_city'] = new CHttpCookie('srch_v_city', 0);
                    $arSID = Subdomain::getIdies();
                    foreach ($arCities as $k => $city)
                        if($cooCity==$city['seo_url']){// ищем ID города из куки
                            if(!in_array($city['id'], $arSID))
                                Yii::app()->request->cookies['srch_v_city'] = new CHttpCookie('srch_v_city', $id);
                            $cityName = $city['name'];
                            $cityId = $city['id'];
                            break;
                        }
                }
                $cooHCity = Yii::app()->request->cookies['srch_v_city']->value;
                //
                //  РЕДИРЕКТЫ
                // переход на выбранный город
                if($cooHCity && empty($_GET) && $cooHCRes!=1){
                    $_GET['cities'][] =  $cooHCity;
                    $url = $SearchVac->buildPrettyUrl($_GET);
                    $this->redirect($url);
                    exit();
                }*/

                $count = $SearchVac->searchVacationsCount();
                // переход на общую страницу, если в родном городе нет данных
                /*if($cooHCity==$_GET['cities'][0] && sizeof($_GET['cities'])==1 && !$count){
                    Yii::app()->request->cookies['srch_v_res'] = new CHttpCookie('srch_v_res', 1);
                    $this->redirect(MainConfig::$PAGE_SEARCH_VAC);
                    exit();
                }
                //записываем выбранный город
                if(!empty($_GET) && $count){
                    $id = isset($_GET['cities'][0]) ? $_GET['cities'][0] : 0;
                    $arSID = Subdomain::getIdies();
                    if(!in_array($id, $arSID))
                        Yii::app()->request->cookies['srch_v_city'] = new CHttpCookie('srch_v_city', $id);
                    Yii::app()->request->cookies['srch_v_res'] = new CHttpCookie('srch_v_res', 0);
                }*/
                // search seo data
                $sID = Subdomain::getId();
                $seo = $SearchVac->getVacancySeo($_GET, MainConfig::$PAGE_SEARCH_VAC, $sID); // Moscow ID    
                if(is_array($seo)){
                    $title = $seo['meta_title'];
                    $h1 = $seo['seo_h1'];
                }
                $mod = $SearchVac->getVacations();
                $pages=new CPagination($count);
                $pages->pageSize = 24;
                $pages->applyLimit($SearchVac);
                $data = $SearchVac->getVacations();


                $this->render(
                    $this->ViewModel->pageSearchVac,
                    array(
                        'viData' => $data,
                        'pages' => $pages, 
                        'count' => $count,
                        //'arCities' => $arCities,
                        'cityName' => $cityName,
                        'cityId' => $cityId,
                        'seo' => $seo
                    ),
                    array(
                        'htmlTitle' => $title,
                        'pageTitle' => '<h1>' . ($h1 ? $h1 : $title) .'</h1>'
                    )
                );
            }
        }
    }

    /**
     * ПРосмотреть профиль пользователя
     */
    public function actionUserprofile()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        if( $id < 1 ) $this->redirect(MainConfig::$PAGE_LOGIN);

        if( $id == Share::$UserProfile->id ) $this->redirect($this->createUrl(MainConfig::$PAGE_PROFILE));

        $Profile = (new ProfileFactory())->makeProfile(array('id' => $id));
        if(isset($Profile->error))
        {
            throw new CHttpException(404, 'Error'); 
            // exit(); 
        }

        if( $Profile instanceof UserProfile )
        {
            $data = $Profile->getProfileDataView();
            $page = $Profile->viewTpl;
        }

        // проверка на мой профиль
        $flagOwnProfile = $id == Share::$UserProfile->id;


        Yii::app()->getClientScript()->registerCssFile("/jslib/magnific-popup/magnific-popup-min.css");
        Yii::app()->getClientScript()->registerScriptFile("/jslib/magnific-popup/jquery.magnific-popup.min.js", CClientScript::POS_END);


        if( $Profile->type == 2 ) $s1 = 'Профиль соискателя';
        if( $Profile->type == 3 ) $s1 = 'Профиль работодателя';
        $this->setBreadcrumbsEx(array($s1, MainConfig::$PAGE_PROFILE_COMMON . DS . $id));
        $this->setPageTitle($s1);

        $this->render('../' . MainConfig::$DIR_VIEWS_USER . DS . $page,
                array('viData' => $data,
                        'idus' => $id,
                        'flagOwnProfile' => $flagOwnProfile,
                        'Profile' => $Profile,
                    ),
                array('pageTitle' => $s1)

                );
    }



    /**
     * Проверка на окончание вакансий
     */
    public function actionChkVacsEnds()
    {
        (new Vacancy())->chkVacsEnds();
        Yii::app()->end();
    }

    ///Отображение статьи
   public function actionArticles()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id', 0), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $Articles = new Articles();
        $title = "Полезные статьи портала Prommu";
        $pageH1 = "<h1>Полезные статьи портала Prommu</h1>";
        $description = 'Статьи о поиске временной работы, специальностях и проведении  BTL и Event-мероприятий';
        $this->setBreadcrumbs($title, MainConfig::$PAGE_ARTICLES);

        if( $id )
        {
            $data = $Articles->getArticlesSingle($id);

            if(!isset($data['data']['id'])){
                throw new CHttpException(404, 'Error'); 
            }
            if(strlen($data['data']['meta_title']) > 0){
                $title = html_entity_decode($data['data']['meta_title']);
            }
            $pageH1 = html_entity_decode($data['data']['name']);
            $this->setBreadcrumbsEx(array($pageH1, MainConfig::$PAGE_ARTICLES . DS . $id));
            $pageH1 = '<h1>' . $pageH1 . '</h1>';
            if(strlen($data['data']['meta_description']) > 0){
                $description = html_entity_decode($data['data']['meta_description']);
            }
            $data['last'] = $Articles->getLastArticles($id);
            $page = MainConfig::$VIEWS_ARTICLES_SINGLE;
        }
        else
        {
            // results per page
            $count = $Articles->getArticlesCount();
            $pages=new CPagination($count);
            $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
            $pages->applyLimit($Articles);
            $data = $Articles->getArticles();
            $page = MainConfig::$VIEWS_ARTICLES;
        } // endif   

        $this->render($page, 
            array('viData' => $data, 'pages' => $pages),
            array(
                'pageTitle' => $pageH1, 
                'htmlTitle' => $title,
                'pageMetaDesription' => $description
            )
        );
    }

    /**
     * Отзывы
     */
    public function actionComments()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $type = filter_var(Yii::app()->getRequest()->getParam('view', ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if( $id < 1 ) $this->redirect(MainConfig::$PAGE_COMMENTS . DS . Share::$UserProfile->id);

        // фильтруем положительные/отрицательные
        $activeFilterLink = 0;
        if( $type == 'p' ) $activeFilterLink = 1 ;
        elseif( $type == 'n' )  $activeFilterLink = 2 ;

        // Профиль соискателя
        // TODO: переделать на фабрику ProfileFactory
        $ids = UserProfile::getUserType($id);
        $profType = $ids['type'];
        $Comments = ($profType) == 2 ? new CommentsApplic() : new CommentsEmpl();

        // results per page
        $count = $Comments->getCommentsCount();
        $pages=new CPagination($count);
        $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
        $pages->applyLimit($Comments);

        $data = $Comments->getComments();
        $page = MainConfig::$VIEWS_COMMENTS;

        $this->render($page, array('viData' => $data,
                'pages' => $pages,
                'activeFilterLink' => $activeFilterLink,
                'profType' => $profType,
            ));
    }


    /**
     * Получение файлов диалога
     */
    public function actionImfiles()
    {
        (new ImFiles())->getFile();
        Yii::app()->end();
    }

    public function actionMedical(){
         $medical = $_POST;
         // var_dump($medical);
         $MedRequest = new MedRequest();
         $MedRequest->setCard($medical);
         $url = '/services';
         $this->redirect($url);

    }


    /**
     * Услуги
     */
    public function actionServices()
    {
        Subdomain::guestRedirect(Share::$UserProfile->type);
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $services = new Services();
        $title = 'Услуги портала Prommu.com';
        $this->setBreadcrumbs($title, MainConfig::$PAGE_SERVICES);
        $pricess = new PrommuOrder();
        $prices = $pricess->getPricesData();
        if( $id )
        {
            $data = $services->getServiceData($id);
            switch ($id){
                case 'premium-vacancy':
                case 'email-invitation':
                case 'push-notification':
                case 'sms-informing-staff':
                case 'publication-vacancy-social-net':
                case 'geolocation-staff':
                case 'personal-manager-outsourcing':
                case 'outstaffing':
                case 'api-key-prommu':
                    $view = MainConfig::$VIEWS_SERVICE_VIEW; 
                    break;
                case 'prommu_card':
                    if( Yii::app()->getRequest()->getParam('save') ) {
                        $services->orderPrommu();
                        $this->redirect(MainConfig::$PAGE_SERVICES_CARD_PROMMU);
                    }
                    $Upluni = new Uploaduni();
                    $data = array_merge($data, $Upluni->init());
                    $view = MainConfig::$VIEWS_CARD_PROMMU;
                    break;
                case 'medical-record':
                    $view = MainConfig::$VIEWS_SERVICE_MEDICAL; 
                    break;                   
                default:
                    throw new CHttpException(404, 'Error'); 
                    break;
            }        
            if(strlen($data['service']['meta_title']) > 0){
                $title = htmlspecialchars_decode($data['service']['meta_title']);
                $this->setBreadcrumbsEx(array($title, $_SERVER['REQUEST_URI']));
            }
            if(strlen($data['service']['meta_description']) > 0){
                Yii::app()->clientScript->registerMetaTag(
                    htmlspecialchars_decode($data['service']['meta_description']), 
                    'description'
                );
            }
        }
        else
        {
            $data = $services->getServices();
            $view = MainConfig::$VIEWS_SERVICES;
        }

        $this->render(
            $view, 
            array('viData' => $data, 'id' => $id, 'prices' => $prices),
            array(
                'pageTitle' => '<h1>'.$title.'</h1>', 
                'htmlTitle' => $title
            )
        );
    }

    public function actionAbout(){

        $this->setBreadcrumbs('О сервисе', MainConfig::$PAGE_ABOUT);

        $section = filter_var(Yii::app()->getRequest()->getParam('section'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(!empty($section)){
            if($section != 'news' && !empty($id)){
                throw new CHttpException(404, 'Error'); 
            }
            if($section == 'prom')
            {
                $model = new PagesContent;
                $lang = Yii::app()->session['lang'];
                $data = $model->getPageContent($section, Yii::app()->session['lang']);
                $this->render(
                    MainConfig::$VIEWS_PROMO_INFO, 
                    array('viData' => $data),
                    array()
                );
            }
            elseif($section == 'empl')
            {
                $model = new PagesContent;
                $lang = Yii::app()->session['lang'];
                $data = $model->getPageContent($section, Yii::app()->session['lang']);
                $this->render(
                    MainConfig::$VIEWS_EMPL_INFO, 
                    array('viData' => $data),
                    array()
                );
            }
            elseif($section == 'faqv')
            {
                $faq = new Faq();
                $data = $faq->getFaq();
                $title = "FAQ: вопрос - ответ";
                $description = Yii::app()->db->createCommand()
                    ->select("meta_description")
                    ->from('seo')
                    ->where("url = :url", array(':url' => '/faqv'))
                    ->queryRow()['meta_description'];
                $this->setBreadcrumbsEx(array($title, MainConfig::$PAGE_FAQ));
                $this->render(
                    MainConfig::$VIEWS_FAQ, 
                    array('viData' => $data),
                    array(
                        'pageTitle' => '<h1>'.$title.'</h1>', 
                        'htmlTitle' => $title,
                        'pageMetaDesription' => $description
                    )
                );
            }
            elseif($section == 'news')
            {
                $News = new News();
                $title = "Новости портала Prommu";
                $pageH1 = "Новости портала Prommu";
                $this->setBreadcrumbsEx(array($title, MainConfig::$PAGE_NEWS));
                if($id)
                {
                    $data = $News->getNewsSingle($id);
                    if(!isset($data['data']['id'])){
                        throw new CHttpException(404, 'Error'); 
                    }
                    if(strlen($data['data']['meta_title']) > 0){
                        $title = html_entity_decode($data['data']['meta_title']);
                    }
                    $pageH1 = $data['data']['name'];
                    $this->setBreadcrumbsEx(array($pageH1, MainConfig::$PAGE_NEWS . DS . $id));
                    if(strlen($data['data']['meta_description']) > 0){
                        Yii::app()->clientScript->registerMetaTag(html_entity_decode($data['data']['meta_description']), 'description');
                    }          
                    $data['last'] = $News->getLastNews($id);
                    $page = MainConfig::$VIEWS_NEWS_SINGLE;
                }
                else
                {
                    $count = $News->getNewsCount();
                    $pages=new CPagination($count);
                    $pages->pageSize = MainConfig::$DEF_PAGE_LIMIT;
                    $pages->applyLimit($News);

                    $data = $News->getNews();
                    $page = MainConfig::$VIEWS_NEWS;
                }
                $this->render($page, 
                    array('viData' => $data, 'arResult' => $id, 'pages' => $pages),
                    array(
                        'pageTitle' => '<h1>'.$pageH1.'</h1>',
                        'htmlTitle' => $title,
                    )
                );            
            }
            else
            {                  
                throw new CHttpException(404, 'Error'); 
            }
        }
        else{
            $model = new PagesContent;
            $lang = Yii::app()->session['lang'];
            $content = $model->getPageContent('about', $lang);
            $this->setBreadcrumbs($content['name'], MainConfig::$PAGE_ABOUT);
            $this->render(
                MainConfig::$VIEWS_DB_PAGES, 
                array('content' => $content), 
                array(
                    'pageTitle' => '<h1>'.$content['name'].'</h1>',
                    'htmlTitle' => $content['name']
                )
            );
        }
    }

    /**
     * Обратная связь
     */
    public function actionFeedback()
    {
        $title = 'Обратная связь';
        $this->setBreadcrumbs($title, MainConfig::$PAGE_FEEDBACK);
        // save data
        $Feedback = new Feedback();
        if( Yii::app()->getRequest()->isPostRequest && Yii::app()->getRequest()->getParam('name') )
        {
            $res = $Feedback->saveData();
            if($res['ERROR']){
                $data = array(
                        'viData' => $Feedback->getData(), 
                        'error' => $res['MESSAGE'],
                        'model'=>(new FeedbackAF())
                    );
            }
            else{
                $this->redirect(MainConfig::$PAGE_FEEDBACK);
            }
        }
        if(!$data)
            $data = array('viData'=>array(), 'model'=>(new FeedbackAF()));

        $this->render($this->ViewModel->pageFeedback, $data, array('htmlTitle' => $title));
    }

    /**
     * Генерация Sitemap
     */
    public function actionSitemap()
    {
        $Sitemap = new Sitemap();
        $Sitemap->actionGenerate();

    }


    public function actionRate()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id', Share::$UserProfile->id), FILTER_SANITIZE_NUMBER_INT);

        if( !$id ) $this->redirect(Yii::app()->homeUrl);

        $Profile = (new ProfileFactory())->makeProfile(array('id' => $id));
        if(isset($Profile->error))
        {
            header("HTTP/1.1 301 Moved Permanently"); 
            header("Location: /404"); 
            exit(); 
        }

        $Profile->setUserData();

        if( !$Profile instanceof UserProfile && $Profile->error < 0 )
            if( Share::$UserProfile->type == 0 ) $this->redirect(Yii::app()->homeUrl);
            else $this->redirect($this->createUrl(MainConfig::$PAGE_PROFILE)) ;


        $Rate = $Profile->makeRate(array('id' => $id, 'userProfile' => Share::$UserProfile));
        $data = $Rate->getViewData();

        $this->setBreadcrumbsEx(array('Рейтинг пользователя', MainConfig::$PAGE_PROFILE_COMMON . DS . $id));

        $this->render($Rate->viewTpl,
            array('viData' => $data,
                'IS_OWN' => $id == Share::$UserProfile->id,
                'Profile' => $Profile,
                ));
    }



    public function actionMap()
    {
        $data = (new Sitemap())->getHtmlMapData();

        $this->setBreadcrumbsEx([$title = "Карта сайта", DS . MainConfig::$PAGE_SITEMAP]);
        $this->render(MainConfig::$VIEWS_SITEMAP,
                array('viData' => $data['data']
                    , 'pages' => $data['pages']
                    , 'count' => $data['count'] ),
                array('pageTitle' => $title)
        );
    }


    public function actionApi_help()
    {
        $data = (new ApiHelp())->getHelpData();
        $codes = (new ApiHelp())->getErrorCodes();

        Yii::app()->getClientScript()->registerCssFile('/' . MainConfig::$PATH_CSS . '/' . Share::$cssAsset['api-help.css']);
        Yii::app()->getClientScript()->registerScriptFile("/theme/js/dev/pages/api_help.js", CClientScript::POS_END);
        Yii::app()->getClientScript()->registerCssFile('/theme/css/page-api-help.css');
        $title = 'API справка';
        $this->setPageTitle($title);
        $this->render('/api/'.MainConfig::$VIEWS_API_HELP,
            ['viData' => compact('data', 'codes')],
            ['pageTitle' => $title]
        );
    }

   

    public function actionPhone()
    {
        
        if($_GET['code'] != ''){
             $res = Yii::app()->db->createCommand()
            ->select('code, type')
            ->from('activate')
             ->where('code=:code', array(':code'=>$_GET['code']))
            ->queryRow();


            if($res['code'] == $_GET['code']){
            $id = $_GET['id'];
                
                if($res['type'] == 2){
                    $resume = Yii::app()->db->createCommand()
                    ->select('firstname, lastname, isman')
                    ->from('resume_reserv')
                    ->where(array('and', 'id_user=:id_user', 'city=:city'), array(':id_user' => $_GET['id'], ':city' => ''))
                    ->queryRow();

                    $token = md5($res['email'] . date("d.m.Y H:i:s") . ($res['passw']));
                    $uid = md5($_GET['id']);
                    $password = $res['passw'];
                    $res['fname'] = $resume['firstname'];
                    $res['lname'] = $resume['lastname'];
                    $res['isman'] = $resume['isman'];
                }
                else{
                    $employer = Yii::app()->db->createCommand()
                    ->select('name')
                    ->from('employer_reserv')
                    ->where('id_user = :id_user', array(':id_user' => $_GET['id']))
                    ->queryRow();

                    $token = md5($res['email'] . date("d.m.Y H:i:s") . ($res['passw']));
                    $uid = md5($_GET['id']);
                    $password = $res['passw'];
                    $res['name'] = $employer['name'];
                }

            $Auth = new Auth();
            $Auth->userActivateInsertUpdate(array('id_user' => $_GET['id'],
                'token' => $token,
                'data' => json_encode($res),
                'dt_create' => date('Y-m-d H:i:s'),
            ));

            $referer = $_GET['referer'];
            $transition = $_GET['transition'];
            $canal = $_GET['canal'];
            $campaign = $_GET['campaign'];
            $content = $_GET['content'];
            $keywords = $_GET['keywords'];
            $point = $_GET['point'];
            $last_referer = $_GET['last_referer'];
            $type = $res['type'];

            $analytData = array('id_us' => $_GET['id'],
                        'name' => 'NO ACTIVE',
                        'date' =>  date('Y-m-d H:i:s'),
                        'type' => $res['type'],
                        'referer' => $referer,
                        'canal' => $canal,
                        'campaign' => $campaign,
                        'content' => $content, 
                        'keywords' => $keywords,
                        'point' => $point, 
                        'transition' => $transition,
                        'last_referer' => $last_referer,
                        'active' => 0,
                        'admin' => 0,
                        'subdomen' => 0,
                    );


            $res = Yii::app()->db->createCommand()
                        ->insert('analytic', $analytData);
            
            $link  = 'https://prommu.com' . MainConfig::$PAGE_ACTIVATE . "/?type=$type&t=" . $token . "&uid=" . $_GET['id'].'&phone=1&referer='.$referer."&keywords=".$keywords."&transition=".$transition."&canal=".$canal."&campaign=".$campaign."&content=".$content."&point=".$point."&last_referer=".$last_referer;;
            $this->redirect( $link);

            } else {
                $this->redirect(Yii::app()->createAbsoluteUrl(MainConfig::$PAGE_CODES.'/?error=1&id='.$_GET['id'].'&phone='.$_GET['phone'])); 
            }
        }
        
        else if($_POST['name'] != ''){
            // captcha
            /*$recaptcha = Yii::app()->getRequest()->getParam('g-recaptcha-response');
            $captchaMess = false;
            if(!empty($recaptcha))
            {
                $google_url="https://www.google.com/recaptcha/api/siteverify";
                $secret='6Lf2oE0UAAAAAPkKWuPxJl0cuH7tOM2OoVW5k6yH';
                $url=$google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$_SERVER['REMOTE_ADDR'];
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
                $res = curl_exec($curl);
                curl_close($curl);
                $res = json_decode($res, true);//reCaptcha введена
                if(!$res['success']) // wrong captcha
                    $captchaMess = 'Вы допустили ошибку при прохождении проверки "Я не робот"';
            }
            else{
                $captchaMess = 'Необходимо пройти проверку "Я не робот"';
            }

            if($captchaMess){
                $type = Yii::app()->getRequest()->getParam('p');
                $_GET['p'] = $type;
                $view = $type==2 ? MainConfig::$VIEWS_REGISTER_COMPANY : MainConfig::$VIEWS_REGISTER_APPLICANT;
                $data = array(
                    'error' => 1, 
                    'message' => $captchaMess, 
                    'inputData' => filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS)
                );
                
                $this->render('/user/'.$view, array('viData' => $data), array('nobc' => '1'));
                exit();
            }*/
            //
            $email = $_POST['email'];
          if( (new User())->find("email = '{$email}'") )
            {
             $this->redirect(Yii::app()->createAbsoluteUrl("/message")); 
            }
            else{



        $code = rand(111111, 999999);
        $data['login'] = $_POST['email'];
        $data['passw'] = md5($_POST['pass']);
        $data['email'] = $_POST['email'];
        $phone = $_POST['email'];
      
        $data['isblocked'] = 3;
        $data['access_time'] = date('Y-m-d H:i:s');
        $data['crdate'] = date('Y-m-d H:i:s');
        $data['mdate'] = date('Y-m-d H:i:s');
        $data['ismoder'] = '0';
        $data['confirmPhone'] = '1';
        $data['confirmEmail'] = '1';
        $data['messenger'] = $code;
        $Auth = new Auth();
       
        if($_POST['type'] == 2) {
         $data['status'] = 2;
         $res = Yii::app()->db->createCommand()
            ->insert('user', $data);
         $pid = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();

        $insData = array('id_user' => $pid,
                        'firstname' => ucfirst($_POST['name']),
                        'lastname' =>  ucfirst($_POST['lname']),
                        'isman' => $_POST['sex'],
                        'birthday' => date('Y-m-d'),
                        'date_public' => date('Y-m-d H:i:s'),
                        'mdate' => date('Y-m-d H:i:s'),
                    );
      
          
        $res = Yii::app()->db->createCommand()
                        ->insert('resume_reserv', $insData);
        $pids = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
        $sID = Subdomain::getId();
        $res = Yii::app()->db->createCommand()
                        ->insert('user_city', array('id_user' => $pid,
                            'id_resume' => $pids,
                                'id_city' => $sID,
                            ));

        $rest = Yii::app()->db->createCommand()
                        ->insert('user_attribs', array('id_us' => $pid,
                            'key' => 'mob',
                            'id_attr' => 1, 
                            'val' => $_POST['email'],
                            ));

        }
        elseif($_POST['type'] == 3){
            $data['status'] = 3;

         $res = Yii::app()->db->createCommand()
            ->insert('user', $data);
         $pid = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();

      
         $res = Yii::app()->db->createCommand()
                        ->insert('employer_reserv', array('id_user' => $pid,
                                'name' => ucfirst($_POST['name']),
                                'crdate' => date('Y-m-d H:i:s'),
                            ));

        $pids = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
        $rest = Yii::app()->db->createCommand()
                        ->insert('user_attribs', array('id_us' => $pid,
                            'key' => 'mob',
                            'id_attr' => 1,
                            'val' => $_POST['email'],
                            ));

        }
        
        $rest = Yii::app()->db->createCommand()
                        ->insert('activate', array('id' => $code,
                            'id' => $code,
                            'code' => $code,
                            'phone' => $_POST['email'],
                            'date' => date("Y-m-d h-i-s"),
                            'type' => $_POST['type'],
                            ));

        file_get_contents("https://prommu.com/api.teles/?phone=$phone&code=$code");
        $this->redirect(Yii::app()->createAbsoluteUrl(MainConfig::$PAGE_CODES.'/?id='.$pid.'&phone='.$phone)); 
        }
      
        }
        else{
            $this->redirect(Yii::app()->createAbsoluteUrl(MainConfig::$PAGE_INDEX));
        }
    }

    public function actionCodes(){

       
         if(Share::$UserProfile->type == 2 || Share::$UserProfile->type == 3){
            $this->redirect(MainConfig::$PAGE_PROFILE);
        }else {
              $title = 'Подтверждение регистрации';
        $this->setPageTitle($title);
        $this->render(MainConfig::$VIEWS_CODES);
        }
    
    }

    public function actionMessage(){

        $title = 'Система уведомлений';
        $this->setPageTitle($title);
        $this->render(MainConfig::$VIEWS_SITE_MESSAGE);
    
    }

    /**
     * Форма запроса восстановления пароля
     */
    public function actionPass_restore()
    {
        !in_array(Share::$UserProfile->type, [2,3]) ?: $this->redirect(MainConfig::$PAGE_PROFILE);

        $email = Yii::app()->getRequest()->getParam('email');

        if( Yii::app()->getRequest()->getPost('email') )
        {
            $res = (new RestorePass())->passRestoreRequest();

            $error = $res['error'];
           // $message = $res['message'];

            if( $error == 1 )
            {
                $this->redirect(Yii::app()->createAbsoluteUrl(MainConfig::$PAGE_PASS_RESTORE));
            }
            else
            {
            } // endif
        }
        else
        {
        } // endif

        $this->setBreadcrumbsEx(array($title = 'Восстановление пароля', MainConfig::$PAGE_PASS_RESTORE));

        $this->render(MainConfig::$VIEWS_PASS_RESTORE_FORM,
            ['viData' => compact(['email', 'message', 'error'])],
            ['pageTitle' => $title, 'htmlTitle' => $title, 'css' => 'pass-restore.css']
        );
    }



    /**
     * Форма смены пароля
     */
    public function actionNew_pass()
    {
        if( Yii::app()->getRequest()->getPost('pass') )
        {
            $res = (new RestorePass())->changePass();
            if( $res['error'] == 1 ) $this->redirect(Yii::app()->createAbsoluteUrl(MainConfig::$PAGE_PASS_RESTORE));
        }
        else
        {   
            
            $res = (new RestorePass())->newPassTokenCheck();
        } // endif


        $this->setBreadcrumbsEx(array($title = 'Восстановление пароля', MainConfig::$PAGE_NEW_PASS));

        $this->render(MainConfig::$VIEWS_NEW_PASS_FORM,
            ['viData' => $res],
            ['pageTitle' => $title, 'htmlTitle' => $title, 'css' => 'pass-restore.css']
        );
    }

    /*
    *   Заказ услуги со страницы вакансии
    */
    public function actionOrderService()
    {
        in_array(Share::$UserProfile->type, [2,3]) ? : $this->redirect(MainConfig::$PAGE_INDEX);

        $data['service'] = Yii::app()->getRequest()->getParam('service');

        $this->render(MainConfig::$VIEW_ORDER_SERVICE, ['viData' => $data]);
    }

    /*
    *   Идеи и предложения
    */
    public function actionIdeas()
    {
        Subdomain::guestRedirect(Share::$UserProfile->type);

        $model = new Ideas;
        $view = MainConfig::$VIEW_IDEAS_LIST;
        $id = Yii::app()->getRequest()->getParam('id');

        if(
            Yii::app()->getRequest()->getParam('new-idea') 
            && 
            in_array(Share::$UserProfile->type, [2,3])
            ) { // создание идеи
            $model->setIdeas();
            Yii::app()->user->setFlash(
                'success', 
                array(
                    'event' => 'new',
                    'type' => Yii::app()->getRequest()->getParam('type')
                )
            );
            $this->redirect(MainConfig::$PAGE_IDEAS_LIST);
            exit;
        }
        if(Yii::app()->getRequest()->getParam('filter-ideas')) { // фильтр идей
            $this->renderPartial(
                MainConfig::$VIEW_IDEAS_AJAX_FILTER, 
                array('viData' => $model->getIdeas()), 
                false,
                true
            );
            exit;
        }
        if(strlen($id)>0) {
            if($id=='new' && in_array(Share::$UserProfile->type, [2,3])) { // новая заявка
                $view = MainConfig::$VIEW_IDEA_NEW;
                $data = $model->getParams();
            }
            elseif($id>0) { // существующая заявка
                $view = MainConfig::$VIEW_IDEA;
                $data = $model->getIdea($id);
                if(!sizeof($data)) {
                    $this->redirect(MainConfig::$PAGE_IDEAS_LIST);
                    exit;
                }
                if(Yii::app()->getRequest()->getParam('sort-comments')) {
                    $this->renderPartial(
                        MainConfig::$VIEW_IDEAS_COMMENTS_AJAX_ORDER, 
                        array('viData' => $model->getIdea($id)), 
                        false,
                        true
                    );
                    exit;
                }
            }
            else{
                $this->redirect(MainConfig::$PAGE_IDEAS_LIST);
                exit;
            }
        }
        else{
            $data = $model->getIdeas();
        }
        $this->render($view, array('viData' => $data));
    }
    /*
    *
    */
    public function actionOthercities()
    {
        $this->render(MainConfig::$VIEW_OTHERCITIES, array());
    }
}
