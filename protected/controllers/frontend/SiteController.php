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
        //
        $data = Cache::getData();
        if($data['data']===false) {
            $data['data']['content'] = $content = $model->getPageContent('about', $lang);
            $data['data']['vacancies'] = $model->getVacanies($lang);
            $data['data']['vacs'] = $model->getVacaniesAppointments($lang);     
            $data['data']['applicants'] = $model->getApplicants($lang);
            $data['data']['companies'] = $model->getCompanies($lang);
            $data['data']['news'] = $news->getNews();
            $data['data']['articles'] = $articles->getArticles();
            $data['data']['couArt'] = $articles->getArticlesCount();
            Cache::setData($data);
        }
        //
        $this->render(
          'index', 
          array('content' => $data['data'], 'city'=>$city)
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
            $SearchEmpl = new SearchEmpl();
            if(Yii::app()->getRequest()->isPostRequest){
                $data = $SearchEmpl->searchFilterData();
                $this->renderPartial(MainConfig::$VIEWS_SEARCH_EMPL_AJAX_FILTER, array('viData' => $data), false, true);                
            }
            else{
                $data = Cache::getData();
                if($data['data']===false) {
                    if(
                        (!isset($_GET['cities']) && !isset($_GET['cotype']))||
                        (sizeof($_GET['cities'])==1 && $_GET['cities'][0]==$data['data']['city'] && !isset($_GET['cotype']))
                    )
                        $data['data']['seo'] = (new Seo)->exist(MainConfig::$PAGE_SEARCH_EMPL);

                    $arCount = $SearchEmpl->searchEmployersCount();
                    $cnt = sizeof($arCount);

                    if(!empty($_GET) && $cnt){
                        $id = isset($_GET['cities'][0]) ? $_GET['cities'][0] : 0;
                        $arSID = Subdomain::getCacheData()->idies;
                        if(!in_array($id, $arSID))
                            Yii::app()->request->cookies['srch_e_city'] = new CHttpCookie('srch_e_city', $id);
                        Yii::app()->request->cookies['srch_e_res'] = new CHttpCookie('srch_e_res', 0);
                    }

                    $data['data']['pages'] = new CPagination($cnt);
                    $data['data']['pages']->pageSize = MainConfig::$DEF_PAGE_LIMIT;
                    $data['data']['pages']->applyLimit($SearchEmpl);
                    $data['data']['viData'] = $SearchEmpl->getEmployers(1);
                    $data['data']['viData']['count'] = $arCount;
                    $data['data']['redirect'] = '';//Subdomain::ajaxFilterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);
                    Cache::setData($data);
                }
                 //var_dump($data['data']);
                $this->renderPartial(
                    MainConfig::$VIEWS_SEARCH_EMPL_AJAX_BLOCK,
                    $data['data'], 
                    false, 
                    true
                );
            }
        }
        else{
            //Subdomain::filterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);
            $this->setBreadcrumbs($title = "Поиск работодателей", MainConfig::$PAGE_SEARCH_EMPL);

            $data = Cache::getData();
            if($data['data']===false) {
                $SearchEmpl = new SearchEmpl();
                $data['data']['filter'] = $SearchEmpl->searchEmplForFilter(); // данные для фильтра
                $arCount = $SearchEmpl->searchEmployersCount(); // кол-во найденных
                $data['data']['count'] = sizeof($arCount);
                $data['data']['pages'] = new CPagination($data['data']['count']);
                $data['data']['pages']->pageSize = MainConfig::$DEF_PAGE_LIMIT;
                $data['data']['pages']->applyLimit($SearchEmpl);
                $data['data']['viData'] = array_merge($SearchEmpl->getEmployers(1), $data['data']);
                if( 
                    (sizeof($_GET['cities'])==1 && $_GET['cities'][0]==$data['data']['city']) || 
                    (!isset($_GET['cities']) && !isset($_GET['cotype'])) 
                ){
                    $data['data']['seo'] = (new Seo)->exist(MainConfig::$PAGE_SEARCH_EMPL);
                    $title = $data['data']['seo']['meta_title'];
                    $h1 = $data['data']['seo']['seo_h1'];
                }
                Cache::setData($data);
            }
            //var_dump($data['data']);
            $this->render($this->ViewModel->pageSearchEmpl,
                    $data['data'],
                    array(
                        'htmlTitle' => $title,
                        'pageTitle' => '<h1>' . ($h1 ? $h1 : $title) .'</h1>'
                    )
                );
        }
    }


    public function actionAnkety()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
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
            }
            if( $Profile->type == 3 ){
                $Termostat = new Termostat();
                $Termostat->setTermostat($id, Share::$UserProfile->id ? Share::$UserProfile->id : 0, 'empl' );

                $strBreadcrumb = $data['userInfo']['name'];
                $strBreadcrumb = 'Профиль работодателя - ' . $strBreadcrumb;
                $this->setBreadcrumbs("Поиск работодателей", MainConfig::$PAGE_SEARCH_EMPL);
            }
            if(strlen($strBreadcrumb) > 0){
                $strBreadcrumb = html_entity_decode($strBreadcrumb);
                $this->setBreadcrumbsEx(array($strBreadcrumb, MainConfig::$PAGE_PROFILE_COMMON . DS . $id)); 
            }    
            $this->ViewModel->setViewData('pageTitle', '<h1>' . $strBreadcrumb . '</h1>');
            // индексируем только города субдомена
            $arCities = Subdomain::getCacheData()->arCitiesIdes;
            reset($data['userInfo']['userCities'][0]);
            $idCity = key($data['userInfo']['userCities'][0]);
            if($idCity>0 && !in_array($idCity, $arCities))
                Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array());
            //var_dump($data);
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
                if(isset($_GET['view']))
                    Yii::app()->request->cookies['srch_a_view'] = new CHttpCookie('srch_a_view', $_GET['view']);

                $data = Cache::getData();
                if($data['data']===false) {
                    $SearchPromo = new SearchPromo();
                    if(!empty($_GET)){
                        $data['data']['seo'] = $SearchPromo->getPromoSeo(
                            $_GET, 
                            MainConfig::$PAGE_SEARCH_PROMO, 
                            $data['data']['city']
                        );
                        $data['data']['seo']['url'] = $SearchPromo->buildPrettyUrl($_GET);
                    }
                    $arAllId = $SearchPromo->searchPromosCount();
                    $data['data']['count'] = sizeof($arAllId);
                    //записываем выбранный город
                    if(!empty($_GET) && $data['data']['count']){
                        $id = isset($_GET['cities'][0]) ? $_GET['cities'][0] : 0;
                        $arSID = Subdomain::getCacheData()->idies;
                        if(!in_array($id, $arSID))
                            Yii::app()->request->cookies['srch_a_city'] = new CHttpCookie('srch_a_city', $id);
                        Yii::app()->request->cookies['srch_a_res'] = new CHttpCookie('srch_a_res', 0);
                    }
                    $data['data']['pages'] = new CPagination($data['data']['count']);
                    $data['data']['pages']->pageSize = 21;
                    $data['data']['pages']->applyLimit($SearchPromo);
                    $data['data']['viData'] = $SearchPromo->getPromos($arAllId);
                    $data['data']['viData']['promos'] = $arAllId;
                    $data['data']['redirect'] = '';//Subdomain::ajaxFilterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);
                    Cache::setData($data);
                }
                //var_dump($data['data']);
                $this->renderPartial(
                    MainConfig::$VIEWS_SEARCH_PROMO_AJAX,
                    $data['data'], 
                    false, 
                    true
                );
            }
            else{
                //Subdomain::filterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);
                $this->setBreadcrumbs($title = "Поиск соискателей", MainConfig::$PAGE_SEARCH_PROMO);
                $SearchPromo = (new SearchPromo());
                if(!isset($_GET['seo_builded2']) && sizeof($_GET))
                {
                    $url = $SearchPromo->buildPrettyUrl($_GET);
                    $this->redirect($url);
                    exit();
                }

                $data = Cache::getData();
                //$data['data'] = false;
                if($data['data']===false) {
                    $arAllId = $SearchPromo->searchPromosCount();
                    $data['data']['count'] = sizeof($arAllId);
                    $data['data']['seo'] = $SearchPromo->getPromoSeo(
                                $_GET, 
                                MainConfig::$PAGE_SEARCH_PROMO, 
                                $data['data']['city']
                            );
                    if(is_array($data['data']['seo'])){
                        $title = $data['data']['seo']['meta_title'];
                        $h1 = $data['data']['seo']['seo_h1'];
                    }
                    $data['data']['pages'] = new CPagination($data['data']['count']);
                    $data['data']['pages']->pageSize = 21;
                    $data['data']['pages']->applyLimit($SearchPromo);
                    $data['data']['viData'] = $SearchPromo->getPromos($arAllId);
                    $Api = new Api;
                    $data['data']['datas'] = $Api->kew();
                    Cache::setData($data);
                }
                //var_dump( $data['data']);
                $this->render($this->ViewModel->pageSearchPromo,
                        $data['data'],
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

            if(isset($vac['error']) && $vac['error'] == 1)
            {
                throw new CHttpException(404, 'Error'); 
            }

            $this->setBreadcrumbs($title = "Поиск вакансий", MainConfig::$PAGE_SEARCH_VAC);

            if(Share::$UserProfile->type==3 && $vac['vac']['idus']==Share::$UserProfile->id){
                Yii::app()->session['editVacId'] = $id;  
            }
            /*
            // индексируем только если владелец вакансии с этого региона
            $arCities = Subdomain::getCitiesIdies(false, 'arr');
            $res = Yii::app()->db->createCommand()
                ->select('id_city')
                ->from('user_city')
                ->where('id_user=:id',array(':id'=>$vac['vac']['idus']))
                ->queryRow();
            */
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
                if(isset($_GET['view']))
                    Yii::app()->request->cookies['vacancies_page_view'] = new CHttpCookie('vacancies_page_view', $_GET['view']);

                $data = Cache::getData();
                if($data['data']===false) {
                    $SearchVac = (new SearchVac());
                    $data['data']['seo'] = $SearchVac->getVacancySeo(
                                                    $_GET, 
                                                    MainConfig::$PAGE_SEARCH_VAC, 
                                                    $data['data']['city']
                                                );
                    $data['data']['seo']['url'] = $SearchVac->buildPrettyUrl($_GET);
                    $data['data']['count'] = $SearchVac->searchVacationsCount();
                    //записываем выбранный город
                    if(!empty($_GET) && $data['data']['count']){
                        $id = isset($_GET['cities'][0]) ? $_GET['cities'][0] : 0;
                        $arSID = Subdomain::getCacheData()->idies;
                        if(!in_array($id, $arSID))
                            Yii::app()->request->cookies['srch_v_city'] = new CHttpCookie('srch_v_city', $id);
                        Yii::app()->request->cookies['srch_v_res'] = new CHttpCookie('srch_v_res', 0);
                    }
                    $data['data']['pages'] = new CPagination($data['data']['count']);
                    $data['data']['pages']->pageSize = 24;
                    $data['data']['pages']->applyLimit($SearchVac);
                    $data['data']['viData'] = $SearchVac->getVacations();
                    $data['data']['redirect'] = '';//Subdomain::ajaxFilterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);
                    Cache::setData($data);
                }

                $this->renderPartial(
                    MainConfig::$VIEWS_SEARCH_VAC_AJAX,
                    $data['data'], 
                    false, 
                    true
                );
            }
            else{
                //Subdomain::filterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);
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

                $data = Cache::getData();
                if($data['data']===false) {
                    // cities for select
                    $sql = Yii::app()->db->createCommand()
                        ->select('t.id_city id, t.name, t.seo_url')
                        ->from('city t')
                        ->where("t.id_co = 1")
                        ->limit(10000);
                    $data['data']['arCities'] = $sql->queryAll();
                    $data['data']['count'] = $SearchVac->searchVacationsCount();
                    // search seo data
                    $data['data']['seo'] = $SearchVac->getVacancySeo(
                                                    $_GET, 
                                                    MainConfig::$PAGE_SEARCH_VAC, 
                                                    $data['data']['city']
                                                );
                    if(is_array($data['data']['seo'])){
                        $title = $data['data']['seo']['meta_title'];
                        $h1 = $data['data']['seo']['seo_h1'];
                    }
                    $data['data']['pages'] = new CPagination($data['data']['count']);
                    $data['data']['pages']->pageSize = 24;
                    $data['data']['pages']->applyLimit($SearchVac);
                    $data['data']['viData'] = $SearchVac->getVacations();
                    Cache::setData($data);
                }

                $this->render(
                    $this->ViewModel->pageSearchVac,
                    $data['data'],
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
        $seo = (new Seo())->exist('/articles');
        $title = $seo['meta_title'];
        $pageH1 = $seo['seo_h1'];
        $description = $seo['meta_description'];
        $this->setBreadcrumbs($pageH1, MainConfig::$PAGE_ARTICLES);

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
                'pageTitle' => '<h1>' . $pageH1 . '</h1>', 
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
        $type = Share::$UserProfile->type;
        Subdomain::guestRedirect($type);
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $services = new Services();
        $PrommuOrder = new PrommuOrder();
        $prices = $PrommuOrder->getPricesData();

        if( $id )
        {
            $data = $services->getServiceData($id);
            switch ($id){
                case 'creation-vacancy':
                case 'premium-vacancy':
                case 'email-invitation':
                case 'push-notification':
                case 'sms-informing-staff':
                case 'publication-vacancy-social-net':       
                case 'personal-manager-outsourcing':
                case 'outstaffing':
                case 'api-key-prommu':
                    if($type==2)
                        $this->redirect(DS.MainConfig::$PAGE_SERVICES);
                    $view = MainConfig::$VIEWS_SERVICE_VIEW; 
                    break; 
                case 'geolocation-staff':
                case 'prommu_card':
                case 'medical-record':
                    $view = MainConfig::$VIEWS_SERVICE_VIEW; 
                    break;
                case 'conditions':
                    $model = new PagesContent;
                    $lang = Yii::app()->session['lang'];
                    $content = $model->getPageContent($id, $lang);
                    $this->breadcrumbs = array($content['name'] => array(MainConfig::$PAGE_PAGES . DS . $content['link']));
                    $this->render(
                        MainConfig::$VIEWS_DB_PAGES, 
                        array('content' => $content), 
                        array('pageTitle' => $content['name'], 'htmlTitle' => $content['name'])
                    );
                    return;
                    break;                  
                default:
                    throw new CHttpException(404, 'Error'); 
                    break;
            }
        }
        else
        {
            $data = $services->getServices();
            $PrommuOrder = new PrommuOrder();
            $prices['prices'] = $PrommuOrder->getVacRegions($prices['prices']);
            $view = MainConfig::$VIEWS_SERVICES;
        }

        $seoModel = new Seo();
        $seo = $seoModel->exist(DS.MainConfig::$PAGE_SERVICES);
        $this->setBreadcrumbs($seo['seo_h1'], MainConfig::$PAGE_SERVICES);

        if($id) {
            $url = DS . MainConfig::$PAGE_SERVICES . DS . $id;
            $seo = $seoModel->exist($url);
            $this->setBreadcrumbsEx([$seo['seo_h1'], $url]);
        }

        $this->render(
            $view, 
            array('viData' => $data, 'id' => $id, 'prices' => $prices),
            array(
                'pageTitle' => '<h1>' . $seo['seo_h1'] . '</h1>', 
                'htmlTitle' => $seo['meta_title'],
                'pageMetaDesription' => htmlspecialchars_decode($seo['meta_description'])
            )
        );
    }

    public function actionAbout(){
        $section = filter_var(Yii::app()->getRequest()->getParam('section'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lang = Yii::app()->session['lang'];
        $model = new PagesContent;
        $data = ['content' => $model->getPageContent('about', $lang)];
        $view = MainConfig::$VIEWS_DB_PAGES;
        $seo = array(
            'pageTitle' => '<h1>' . $data['content']['name'] . '</h1>',
            'htmlTitle' => $data['content']['meta_title'],
            'pageMetaDesription' => $data['content']['meta_description']
        );
        $this->setBreadcrumbs($data['content']['name'], MainConfig::$PAGE_ABOUT);

        if(!empty($section)) {
            switch ($section) {
                case 'prom':
                case 'empl':
                    $data = ['viData' => $model->getPageContent($section, $lang)];
                    $view = $section=='prom'
                        ? MainConfig::$VIEWS_PROMO_INFO
                        : MainConfig::$VIEWS_EMPL_INFO;
                    $seo = array(
                        'pageTitle' => '<h1>' . $data['viData']['name'] . '</h1>',
                        'htmlTitle' => $data['viData']['meta_title'],
                        'pageMetaDesription' => $data['viData']['meta_description']
                    );
                    $url = $section=='prom' 
                        ? MainConfig::$PAGE_PROMO_INFO 
                        : MainConfig::$PAGE_EMPL_INFO;
                    $this->setBreadcrumbsEx([$data['viData']['name'], $url]);
                    if( !empty($id) )
                        throw new CHttpException(404, 'Error'); 
                    break;

                case 'faqv':
                    $data = ['viData' => (new Faq())->getFaq()];
                    $view = MainConfig::$VIEWS_FAQ;
                    $meta = $model->getPageContent($section, $lang);
                    $seo = array(
                        'pageTitle' => '<h1>' . $meta['name'] . '</h1>',
                        'htmlTitle' => $meta['meta_title'],
                        'pageMetaDesription' => $meta['meta_description']
                    );
                    $this->setBreadcrumbsEx([$meta['name'], MainConfig::$PAGE_FAQ]);
                    if( !empty($id) )
                        throw new CHttpException(404, 'Error'); 
                    break;
                
                case 'news':
                    $News = new News();
                    $meta = (new Seo())->exist('/about/news');
                    $this->setBreadcrumbsEx(array($meta['seo_h1'], MainConfig::$PAGE_NEWS));
                    if($id) {
                        $data = ['arResult' => $id];
                        $news = $News->getNewsSingle($id);
                        if(!isset($news['data']['id']))
                            throw new CHttpException(404, 'Error');

                        $seo = array(
                            'pageTitle' => '<h1>' . html_entity_decode($news['data']['name']) . '</h1>',
                            'htmlTitle' => html_entity_decode($news['data']['meta_title']),
                            'pageMetaDesription' => html_entity_decode($news['data']['meta_description'])
                        );
                        $news['last'] = $News->getLastNews($id);
                        $view = MainConfig::$VIEWS_NEWS_SINGLE;
                        $this->setBreadcrumbsEx([
                                html_entity_decode($news['data']['name']), 
                                MainConfig::$PAGE_NEWS . DS . $id
                            ]);
                        $data['viData'] = $news;
                    }
                    else {
                        $data = ['pages' => new CPagination($News->getNewsCount())];
                        $data['pages']->pageSize = MainConfig::$DEF_PAGE_LIMIT;
                        $data['pages']->applyLimit($News);
                        $data['viData'] = $News->getNews();
                        $view = MainConfig::$VIEWS_NEWS;
                        $seo = array(
                            'pageTitle' => '<h1>' . $meta['seo_h1'] . '</h1>',
                            'htmlTitle' => $meta['meta_title'],
                            'pageMetaDesription' => $meta['meta_description']
                        );                     
                    }
                    break;

                default:
                    throw new CHttpException(404, 'Error'); 
                    break;   
            }
        }

        $this->render($view, $data, $seo);   
    }

    /**
     * Обратная связь
     */
    public function actionFeedback()
    {
        $seo = (new Seo())->exist(Yii::app()->request->requestUri);
        $this->setBreadcrumbs($seo['seo_h1'], MainConfig::$PAGE_FEEDBACK);
        $Feedback = new Feedback();

        if( Yii::app()->getRequest()->isPostRequest && Yii::app()->getRequest()->getParam('name') )
        {
            $res = $Feedback->saveData();
            if($res['ERROR'])
            {
                $data = array(
                        'viData' => $Feedback->getData(), 
                        'error' => $res['MESSAGE'],
                        'model'=>(new FeedbackAF())
                    );
            }
            elseif(in_array(Share::$UserProfile->type, [2,3]))
                $this->redirect(MainConfig::$PAGE_CHATS_LIST_FEEDBACK);
            else
                $this->redirect(MainConfig::$PAGE_FEEDBACK);
        }
        if(!$data)
            $data = array('viData'=>$Feedback->getData(), 'model'=>(new FeedbackAF()));

        $this->render(
                $this->ViewModel->pageFeedback, 
                $data, 
                array(
                    'htmlTitle' => $seo['meta_title'],
                    'pageMetaDesription' => $seo['meta_description']
                ) 
            );
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
        $sID = Subdomain::getCacheData()->id;
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
