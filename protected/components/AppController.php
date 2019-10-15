<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */

//namespace Controllers;

class AppController extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
//    public $layout = '/layouts/main';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /** @var ViewModel */
    public $ViewModel;



    function __construct($id, $module = null)
    {
        parent::__construct($id, $module);

        // read config
        MainConfig::$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'];

        $options = (new Options)->getByGroup('sitebase');
        MainConfig::$DEF_PAGE_LIMIT = $options['DEF_PAGE_LIMIT']->val;
        MainConfig::$DEF_PAGE_API_LIMIT = $options['DEF_API_PAGE_LIMIT']->val;
        //MainConfig::$AUTH_EXPIRE_TIME = $options['AUTH_EXPIRE_TIME']->val;
        //MainConfig::$AUTH_EXPIRE_TIME_LONG = $options['AUTH_EXPIRE_TIME_LONG']->val;
        MainConfig::$PROFILE_FILL_MAX = $options['PROFILE_FILL_MAX']->val;


        // mobile version
        //define("FLAG_ISMOBILE", isset($_COOKIE['mobileVer']) && ($_COOKIE['mobileVer'] == 1 || $_COOKIE['mobileVer'] == 3) ? 1 : 0);
        require_once 'Mobile_Detect.php';// for PHP detect device type
        $detect = new Mobile_Detect;
        define('MOBILE_DEVICE', $detect->isMobile());
/*
        if(!isset(Yii::app()->request->cookies['show_mob_mess']->value))
            Yii::app()->request->cookies['show_mob_mess'] = new CHttpCookie('show_mob_mess', 0);
        define('SHOW_APP_MESS', Yii::app()->request->cookies['show_mob_mess']->value);
*/
        // set lang
        $lang = Yii::app()->session['lang'];
        if (empty($lang)) {
            $lang = 'ru';
            Yii::app()->session['lang'] = 'ru';
        }

        // index page
        MainConfig::$PAGE_INDEX = Yii::app()->homeUrl;

        // проверка авторизации
        $this->doAuth();


        // модель данных для view шаблона
        if( Share::$UserProfile->type == 2 ) $view = new ViewModelApplic();
        elseif( Share::$UserProfile->type == 3 ) $view = new ViewModelEmpl();
        else $view = new ViewModel();
        $this->ViewModel = $view;


        // получаем css стили из manifest-a
        $this->obtainCss();
    }



    public function render($inView, $tplData = null, $viewData = array(), $return = false, $addParams = 0)
    {

        // данные для view
        foreach ($viewData as $key => $val)
        {
            $this->ViewModel->setViewData($key, $val);
        } // end foreach

        // include css file
        if( $viewData['css'] ) Yii::app()->getClientScript()->registerCssFile('/' . MainConfig::$PATH_CSS . '/' . Share::$cssAsset[$viewData['css']]);
        // set header html title
        if( $viewData['htmlTitle'] ) $this->setPageTitle($viewData['htmlTitle']);

        // custom seo

        $model = new Seo;
        if($seo = $model->exist(Yii::app()->getRequest()->getRequestUri()))
        {
            if($seo['meta_title'])
                $this->setPageTitle($seo['meta_title']);

            if($seo['meta_keywords'])
                $this->ViewModel->setViewData('pageMetaKeywords', $seo['meta_keywords']);

            if($seo['meta_description'])
                $this->ViewModel->setViewData('pageMetaDesription', $seo['meta_description']);

            if($seo['seo_h1'])
                $this->ViewModel->setViewData('pageH1', $seo['seo_h1']);
        }
        else if($seo = $model->existTemplate(Yii::app()->getRequest()->getRequestUri()))
        {
            if($seo['meta_title'])
                $this->setPageTitle($seo['meta_title']);

            if($seo['meta_keywords'])
                $this->ViewModel->setViewData('pageMetaKeywords', $seo['meta_keywords']);

            if($seo['meta_description'])
                $this->ViewModel->setViewData('pageMetaDesription', $seo['meta_description']);

            if($seo['seo_h1'])
                $this->ViewModel->setViewData('pageH1', $seo['seo_h1']);
        }

        // END custom seo

        $this->ViewModel->init();

        parent::render($inView, $tplData, $return);
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if( Yii::app()->getRequest()->getParam('api') )
            {
                echo json_encode(['error' => 1002, 'message' => 'some api error']);

            } elseif (Yii::app()->request->isAjaxRequest)
                    echo $error['message'];
            else
                $this->render('error', array('viData' => $error));
        }
    }


    protected function doAuth()
    {
        $params = (object)[];
        $params->login = filter_var(Yii::app()->getRequest()->getParam('login'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//        $params->login = isset($_POST['login']) ? $_POST['login'] : '';
        $params->passw = filter_var(Yii::app()->getRequest()->getParam('passw'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $params->remember = Yii::app()->getRequest()->getParam('remember');

        return (new Auth())->doAuth($params);
    }



    /**
     * @deprecated
     */
    protected function setBreadcrumbs($inTitle, $inLink)
    {
        $this->breadcrumbs = array($inTitle => array($inLink));
    }



    /**
     * Правильные хлебные крошки
     */
    protected function setBreadcrumbsEx($inParams)
    {
        $argv = func_get_args();
        foreach ($argv as $key => $val)
        {
            $this->breadcrumbs[$val[0]] = array($val[1]);
        } // end foreach
    }



    /**
     * получаем css стили из manifest-a
     */
    protected function obtainCss()
    {
        if( !($cssAsset = Yii::app()->cache->get('cssAsset')) ) //->get('cssAsset') )
        {
            $cssAsset = json_decode(file_get_contents('theme/css-manifest.json'));
            Yii::app()->cache->set('cssAsset', $cssAsset, 600, new CFileCacheDependency('theme/css-manifest.json'));
        } // endif

        Share::$cssAsset = get_object_vars($cssAsset);
    }
  /**
   * @param $inView - string
   * @throws CHttpException
   */
  public function renderRegister($inView, $tplData = null)
  {
    if(Yii::app()->controller->id!=='user' || Yii::app()->controller->action->id!=='register')
    {
      throw new CHttpException(404, 'Error');
    }

    $this->layout = '//user/register/index';
    $this->setPageTitle('Регистрация');
    $this->ViewModel->init();

    parent::render($inView, $tplData, false);
  }
}
