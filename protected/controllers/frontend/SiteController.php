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
            'yiiupload'=>array('class'=>'YiiUploadAction')
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        Share::$isHomePage = 1;
        // уже не надо
        //$city = Subdomain::getCity(Share::$UserProfile->type,Share::$UserProfile->id);
        $model = new PagesContent;
        $news = new News;
        $articles = new Articles;
        $action = ContentPlus::getActionID();
        $lang = Yii::app()->session['lang'];
        //
        $data = Cache::getData();

        if($data['data']===false)
        {
            $data['data']['content'] = $content = $model->getPageContent('about', $lang);
            $data['data']['vacs'] = $model->getVacaniesAppointments($lang);

            $data['data']['news'] = $news->getNews();
            $data['data']['articles'] = $articles->getArticles();
            $data['data']['couArt'] = $articles->getArticlesCount();
            Cache::setData($data);
        }
        // вакансии, соискатели и работодатели работают в рамках своей логики кэширования
        $data['data']['vacancies'] = $model->getVacanies($lang);
        $data['data']['companies'] = $model->getCompanies($lang);
        $data['data']['applicants'] = $model->getApplicants($lang);
        //
        $this->render(
          'index', 
          array('content' => $data['data']/*, 'city'=>$city*/)
        );
    }


    public function actionPage()
    {
        $action = ContentPlus::getActionID();
        if ($action != '') {
             $arNoIndex = array('help','support','students', 'razrabotchikam','contacts','tips1','about');
            if(in_array($action, $arNoIndex)){

                throw new CHttpException(404, 'Error');
            }
            elseif($action=='work-for-students'){
                $this->render($action . DS . Subdomain::getCacheData()->id);
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
      // проверка регистрации на завершенность
      $this->directToCompleteRegistration();
      //
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
                    $data['data']['redirect'] = '';
                    Cache::setData($data);
                }

                $this->renderPartial(
                    MainConfig::$VIEWS_SEARCH_EMPL_AJAX_BLOCK,
                    $data['data'], 
                    false, 
                    true
                );
            }
        }
        else{
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

            $h1 = (!empty($data['data']['seo']['seo_h1']) ? $data['data']['seo']['seo_h1'] : $title);

            $this->render($this->ViewModel->pageSearchEmpl,
                    $data['data'],
                    ['pageTitle' => '<h1>' . $h1 .'</h1>']
                );
        }
    }


    public function actionAnkety()
    {
        // проверка регистрации на завершенность
        $this->directToCompleteRegistration();
        //
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

                $SearchPromo = new SearchPromo();

                $data = Cache::getData();
                if($data['data']===false) {
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

                if(!empty($_GET)){
                    $data['data']['seo'] = $SearchPromo->getPromoSeo(
                        $_GET, 
                        MainConfig::$PAGE_SEARCH_PROMO, 
                        $data['data']['city']
                    );
                    $data['data']['seo']['url'] = $SearchPromo->buildPrettyUrl($_GET);
                }

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
                if($data['data']===false) {
                    $arAllId = $SearchPromo->searchPromosCount();
                    $data['data']['count'] = sizeof($arAllId);
                    $data['data']['pages'] = new CPagination($data['data']['count']);
                    $data['data']['pages']->pageSize = 21;
                    $data['data']['pages']->applyLimit($SearchPromo);
                    $data['data']['viData'] = $SearchPromo->getPromos($arAllId);
                    $Api = new Api;
                    $data['data']['datas'] = $Api->kew();
                    Cache::setData($data);
                }

                $data['data']['seo'] = $SearchPromo->getPromoSeo(
                            $_GET, 
                            MainConfig::$PAGE_SEARCH_PROMO, 
                            $data['data']['city']
                        );

                !empty($data['data']['seo']['meta_title']) && $title = $data['data']['seo']['meta_title'];
                !empty($data['data']['seo']['seo_h1']) && $h1 = $data['data']['seo']['seo_h1'];
                // устанавливаем отдельные хлебные крошки для должностей
                $arSelectPost = [];
                foreach ($data['data']['viData']['posts'] as $key => $v)
                {
                    $v['selected'] && $arSelectPost[] = $key;
                }
                if(count($arSelectPost)==1)
                {
                    $post = $data['data']['viData']['posts'][reset($arSelectPost)]['name'];
                    !empty($post) && $this->setBreadcrumbsEx([$post, Yii::app()->request->url]);      
                }

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
        $rq = Yii::app()->getRequest();
        $id = $rq->getParam('id');
        $id_user = Share::$UserProfile->id;
        // проверка регистрации на завершенность
        $this->directToCompleteRegistration();
        //
        if(isset($id)) // страница конкретной вакансии
        {
          $section = $rq->getParam('section');
          $module = $rq->getParam('module');
          $event = $rq->getParam('event');
          $arEvents = ['activate','create_city','delete_city','change_city','create_loc','edit_loc'];
          $view = '/user/vacancy/edit/index';//$this->ViewModel->pageVacancy;
          $model = new Vacancy;
          if($rq->isAjaxRequest && in_array($event,$arEvents)) // локации редактируем не так, как остальные модули
          {
            $module = ($event=='activate' ? 1 : 9);
            $model->setVacancy($id, Share::$UserProfile->id, $module);
          }
          $viData = $model->getVacancy($id);
          if($viData->is_owner)
          {
            if($rq->isAjaxRequest)
            {
              if(!in_array($module,[1,2,3,4,5,6,7,8,9]))
              {
                $viData->errors['access'] = true;
              }
              else
              {
                new VacancyEdit($viData);
                if(!count($viData->errors) && !in_array($event,$arEvents)) // ошибок нет и отменяем повторную запись для событий
                {
                  $model->setVacancy($id, Share::$UserProfile->id, $module, $viData->data);
                }
                else
                {
                  $viData->error_moodule = $module;
                }
                $this->renderPartial(
                  '../user/vacancy/edit/module_' . $module,
                  ['viData'=>$viData]
                );
                Yii::app()->end();
              }
            }
            elseif (in_array($event,['pay_change_city','pay_create_city']))
            {
              $orderId = VacancyEdit::checkPayment($id, Share::$UserProfile->id);
              if($orderId) // страница выбора типа оплаты
              {
                $this->redirect(MainConfig::$PAGE_PAYMENT . '?receipt=' . $orderId);
              }
              else
              {
                $this->redirect(MainConfig::$PAGE_VACANCY . DS . $id);
              }
            }
            else
            {
              $this->breadcrumbs = [
                "Мои вакансии" => MainConfig::PAGE_USER_VACANCIES_LIST,
                $viData->data->title
              ];
              $this->render($view, ['viData'=>$viData], ['htmlTitle'=>$viData->data->title]);
              Yii::app()->end();
            }

          }

          //
          //
          // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
          //
          //
          $view = $this->ViewModel->pageVacancy;
          $isOwner = Vacancy::hasAccess($id,$id_user);

            if($isOwner){ Yii::app()->session['editVacId'] = $id; }

            if(in_array(
                $section,
                [
                    MainConfig::$VACANCY_APPROVED,
                    MainConfig::$VACANCY_INVITED,
                    MainConfig::$VACANCY_RESPONDED,
                    MainConfig::$VACANCY_DEFERRED,
                    MainConfig::$VACANCY_REJECTED,
                    MainConfig::$VACANCY_REFUSED
                ]
                )) // секции конкретной вакансии
            {
              Share::isGuest() && $this->redirect(MainConfig::$PAGE_LOGIN);
              $data = $model->getVacancyView($id);
              if($data['error']==1 || $data['vac']['in_archive'])
                throw new CHttpException(404, 'Error');
              if($isOwner)
              {
                $data = $model->getInfo($id, false);
                // сбрасываем счетчики при наличии
                $arCounters = [];
                if($section==MainConfig::$VACANCY_RESPONDED)
                {
                  $arCounters[] = UserNotifications::$EMP_RESPONSES;
                }
                if($section==MainConfig::$VACANCY_APPROVED)
                {
                  $arCounters[] = UserNotifications::$EMP_APPROVAL;
                }
                if($section==MainConfig::$VACANCY_REFUSED)
                {
                  $arCounters[] = UserNotifications::$EMP_REFUSALS;
                }
                if(count($arCounters))
                {
                  UserNotifications::resetCounters($arCounters, $id);
                }
                $view = 'vacancy/index';
              }
              else
                throw new CHttpException(404, 'Error');
            }
            else
            {
                $data = $model->getVacancyView($id);

                if($isOwner) // для владельца проверка на отношение вакансии к архиву
                {
                  // сбрасываем счетчики при наличии
                  UserNotifications::resetCounters(
                    [UserNotifications::$EMP_START_VACANCY,UserNotifications::$EMP_END_VACANCY],
                    $id
                  );
                  $data['archive'] = $model->getEmpVacanciesIdList($id_user)['archive'];
                }

                if(Share::isApplicant())
                {
                  // сбрасываем счетчики для С при наличии
                  UserNotifications::resetCounters([UserNotifications::$APP_NEW_VACANCIES],$id);
                }

                if($data['error']==1 || $data['vac']['in_archive'])
                    throw new CHttpException(404, 'Error'); 
                // индексируем только если владелец вакансии с этого региона
                $res = Yii::app()->db->createCommand()
                    ->select('id_city')
                    ->from('user_city')
                    ->where('id_user=:id',array(':id'=>$data['vac']['idus']))
                    ->queryRow();

                if($res['id_city']>0 && !in_array($res['id_city'], Subdomain::getCacheData()->arCitiesIdes))
                    Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array());
            }
            //
            //
            $Termostat = new Termostat();
            $Termostat->setTermostat($id, $id_user?:0, 'vacancy' );
            $this->setBreadcrumbs($title = "Поиск вакансий", MainConfig::$PAGE_SEARCH_VAC);
            $this->render($view,['viData'=>$data, 'id'=>$id], ['pageTitle'=>$title]);
        }
        else
        {
            if(!isset(Yii::app()->request->cookies['vacancies_page_view']->value))
                Yii::app()->request->cookies['vacancies_page_view'] = new CHttpCookie('vacancies_page_view', 'list');

            if(Yii::app()->request->isAjaxRequest){
                if(isset($_GET['view']))
                    Yii::app()->request->cookies['vacancies_page_view'] = new CHttpCookie('vacancies_page_view', $_GET['view']);

                $SearchVac = (new SearchVac());

                $data = Cache::getData();
                if($data['data']===false) {
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
                    $data['data']['pages']->pageSize = 12;
                    $data['data']['pages']->applyLimit($SearchVac);
                    $data['data']['viData'] = $SearchVac->getVacations();
                    $data['data']['redirect'] = '';//Subdomain::ajaxFilterRedirect($_GET['cities'],Share::$UserProfile->id,Share::$UserProfile->type);
                    Cache::setData($data);
                }

                $data['data']['seo'] = $SearchVac->getVacancySeo(
                                                $_GET, 
                                                MainConfig::$PAGE_SEARCH_VAC, 
                                                $data['data']['city']
                                            );
                $data['data']['seo']['url'] = $SearchVac->buildPrettyUrl($_GET);

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
                        ->limit(1000);
                    $data['data']['arCities'] = $sql->queryAll();
                    $data['data']['count'] = $SearchVac->searchVacationsCount();
                    $data['data']['pages'] = new CPagination($data['data']['count']);
                    $data['data']['pages']->pageSize = 12;
                    $data['data']['pages']->applyLimit($SearchVac);
                    $data['data']['viData'] = $SearchVac->getVacations(); //take vacancions to page /vacancy

                    Cache::setData($data);
                }

                // search seo data
                $data['data']['seo'] = $SearchVac->getVacancySeo(
                                                $_GET, 
                                                MainConfig::$PAGE_SEARCH_VAC, 
                                                $data['data']['city']
                                            );
                !empty($data['data']['seo']['meta_title']) && $title = $data['data']['seo']['meta_title'];
                !empty($data['data']['seo']['seo_h1']) && $h1 = $data['data']['seo']['seo_h1'];
                // устанавливаем отдельные хлебные крошки для должностей
                $arSelectPost = [];
                foreach ($data['data']['viData']['posts'] as $key => $v)
                {
                    $v['selected'] && $arSelectPost[] = $key;
                }
                if(count($arSelectPost)==1)
                {
                    $post = $data['data']['viData']['posts'][reset($arSelectPost)]['name'];
                    !empty($post) && $this->setBreadcrumbsEx([$post, Yii::app()->request->url]);      
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
      if(Yii::app()->request->isAjaxRequest)
      {
        $event = Yii::app()->getRequest()->getParam('event');
        if($event=='service_users')
        {
          $this->renderPartial('services/list-service-users-ajax');
        }

        return;
      }

      Subdomain::guestRedirect(Share::$UserProfile->type);
      $id = filter_var(
        Yii::app()->getRequest()->getParam('id'),
        FILTER_SANITIZE_FULL_SPECIAL_CHARS
      );
      $services = new Services();
      $order = new PrommuOrder();
      $prices = $order->getPricesData();

      if( !empty($id) )
      {
          $data = $services->getServiceData($id);
          switch ($id)
          {
              case 'creation-vacancy':
              case 'premium-vacancy':
              case 'podnyatie-vacansyi-vverh':
              case 'personal-invitation':
              case 'email-invitation':
              case 'push-notification':
              case 'sms-informing-staff':
              case 'publication-vacancy-social-net':
              case 'personal-manager-outsourcing':
              case 'outstaffing':
              case 'api-key-prommu':
                  Share::isApplicant() && $this->redirect(MainConfig::$PAGE_SERVICES);
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
                  $data = $model->getPageContent($id, $lang);
                  $this->breadcrumbs = [$data['name']=>[MainConfig::$PAGE_PAGES . DS . $data['link']]];
                  $this->render(
                      MainConfig::$VIEWS_DB_PAGES,
                      ['content' => $data],
                      ['pageTitle' => $data['name'], 'htmlTitle' => $data['name']]
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
        $prices['prices'] = $order->getVacRegions($prices['prices']);
        $view = MainConfig::$VIEWS_SERVICES;
      }

      $seo = new Seo();
      $meta = $seo->exist(MainConfig::$PAGE_SERVICES);
      $this->setBreadcrumbs($meta['seo_h1'], MainConfig::$PAGE_SERVICES);

      if(!empty($id))
      {
        $url = MainConfig::$PAGE_SERVICES . DS . $id;
        $meta = $seo->exist($url);
        $this->setBreadcrumbsEx([$meta['seo_h1'], $url]);
      }

      $this->render(
        $view,
        ['viData'=>$data, 'id'=>$id, 'prices'=>$prices],
        [
          'pageTitle' => '<h1>' . $meta['seo_h1'] . '</h1>',
          'htmlTitle' => $meta['meta_title'],
          'pageMetaDesription' => htmlspecialchars_decode($meta['meta_description'])
        ]
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
          $data = [
            'viData' => $Feedback->getData(),
            'error' => $res['MESSAGE'],
            'model'=>(new FeedbackAF())
          ];
        }
        elseif(!Share::isGuest())
            $this->redirect(MainConfig::$PAGE_CHATS_LIST_FEEDBACK);
        else
            $this->redirect(MainConfig::$PAGE_FEEDBACK);
      }
      if(!$data)
          $data = ['viData'=>$Feedback->getData(), 'model'=>(new FeedbackAF())];

      $this->render(
        $this->ViewModel->pageFeedback,
        $data,
        [
          'htmlTitle' => $seo['meta_title'],
          'pageMetaDesription' => $seo['meta_description']
        ]
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

        // сбрасываем счетчики уведомлений ЛК
        if(Share::isApplicant())
        {
          UserNotifications::resetCounters([UserNotifications::$APP_NEW_RATING]);
        }
        if(Share::isEmployer())
        {
          UserNotifications::resetCounters([UserNotifications::$EMP_NEW_RATING]);
        }

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
                array(
                    'pageTitle' => $title,
                    'pageTitle' => '<h1>'.$title.'</h1>'
                )
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
      !Share::isGuest() && $this->redirect(MainConfig::$PAGE_PROFILE);

      $data = (new RestorePass())->passRestoreRequest();
      if(Yii::app()->getRequest()->isPostRequest && !$data['error'])
      {
        $this->redirect(MainConfig::$PAGE_PASS_RESTORE);
      }

      $title = 'Восстановление пароля';
      $this->setBreadcrumbsEx([$title, MainConfig::$PAGE_PASS_RESTORE]);

      $this->render(
        MainConfig::$VIEWS_PASS_RESTORE_FORM,
        ['viData' => $data],
        ['pageTitle' => $title, 'htmlTitle' => $title]
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

        //display($data);
        //die('service');
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
      $seo = (new Seo())->exist('/othercities');

      $this->setBreadcrumbsEx([$seo['seo_h1'], MainConfig::$PAGE_OTHERCITIES]);

      $this->render(
        MainConfig::$VIEW_OTHERCITIES,
        [],
        [
          'pageTitle' => '<h1>' . $seo['seo_h1'] . '</h1>',
          'htmlTitle' => $seo['meta_title'],
          'pageMetaDesription' => $seo['meta_description']
        ]
      );
    }
    /**
     *      Генерация YVL для яндекса
     */
    public function actionYandex_job()
    {
        $model = new Yandex();
        $result = $model->generateFile();
        echo intval($result);
    }
    /**
     *    Альфа/бета тестирование
     */
    public function actionAb()
    {
      $model = new AbTesting();
      $link = $model->getLink();
      if(!$link)
      {
        Yii::app()->end();
      }
      $this->redirect($link);
    }
}
