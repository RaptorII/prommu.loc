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

        try
        {
          $options = (new Options)->getByGroup('sitebase');
        }
        catch (Exception $e)
        {
          Share::sendToTelegramBug("Внимание!!! Проблема с подключением к БД\n" . $e->getMessage());
          parent::renderFile(Yii::app()->getViewPath() . '/layouts/db_error.php');
          die();
        }

        MainConfig::$DEF_PAGE_LIMIT = $options['DEF_PAGE_LIMIT']->val;
        MainConfig::$DEF_PAGE_API_LIMIT = $options['DEF_API_PAGE_LIMIT']->val;
        //MainConfig::$AUTH_EXPIRE_TIME = $options['AUTH_EXPIRE_TIME']->val;
        //MainConfig::$AUTH_EXPIRE_TIME_LONG = $options['AUTH_EXPIRE_TIME_LONG']->val;
        MainConfig::$PROFILE_FILL_MAX = $options['PROFILE_FILL_MAX']->val;


        // mobile version
        require_once 'Mobile_Detect.php';// for PHP detect device type
        $detect = new Mobile_Detect;
        define('MOBILE_DEVICE', $detect->isMobile());
        // set lang
        $lang = Yii::app()->session['lang'];
        if (empty($lang))
        {
            $lang = 'ru';
            Yii::app()->session['lang'] = 'ru';
        }

        // index page
        MainConfig::$PAGE_INDEX = Yii::app()->homeUrl;

        // проверка авторизации
        $this->doAuth();

        // модель данных для view шаблона
        if( Share::$UserProfile->type == UserProfile::$APPLICANT ) $view = new ViewModelApplic();
        elseif( Share::$UserProfile->type == UserProfile::$EMPLOYER ) $view = new ViewModelEmpl();
        else $view = new ViewModel();
        $this->ViewModel = $view;
        //
        // если запущен механизм регистрации - то отправляем на регу, пока не зарегается
        $bSocialNetwork =
          Share::isSocialNetwork()
          ||
          strripos($_SERVER['REQUEST_URI'],MainConfig::$PAGE_MESSENGER)!==false
          ||
          strripos($_SERVER['REQUEST_URI'],MainConfig::$PAGE_MESSNOTEMAIL)!==false;

        if(!Share::isGuest() || $bSocialNetwork)
        {
          if($bSocialNetwork)
          {
            UserRegister::clearStep();
          }
          else
          {
            UserRegister::clearRegister();
          }
        }
        /*elseif ( // выключили полезный редиректик(
          strripos($_SERVER['REQUEST_URI'],MainConfig::$PAGE_REGISTER)===false
          &&
          UserRegister::beginRegister()
        )
        {
          $this->redirect(MainConfig::$PAGE_REGISTER);
        }*/
        //
        // получаем css стили из manifest-a
        $this->obtainCss();
        //
        //  UTM
        //
        if(!is_object(Yii::app()->session['utm']))
        {
          Yii::app()->session['utm'] = (object)[
            'transition' => '',
            'referer' => '',
            'campaign' => '',
            'content' => '',
            'keywords' => '',
            'pm_source' => '',
            'last_referer' => '',
            'point' => ''
          ];
        }
        $rq = Yii::app()->getRequest();
        $v = filter_var($rq->getParam('utm_source'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $yaRabota = $rq->getParam('_openstat');
        if(strripos($yaRabota,'rabota.yandex.ru')!==false)
        {
          Yii::app()->session['utm']->transition = 'rabota.yandex.ru';
        }
        elseif(!empty($v))
        {
          Yii::app()->session['utm']->transition = $v;
        }

        $v = filter_var($rq->getParam('utm_medium'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        !empty($v) && Yii::app()->session['utm']->referer = $v;

        $v = filter_var($rq->getParam('utm_campaign'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        !empty($v) && Yii::app()->session['utm']->campaign = $v;

        $v = filter_var($rq->getParam('utm_content'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        !empty($v) && Yii::app()->session['utm']->content = $v;

        $v = filter_var($rq->getParam('utm_term'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        !empty($v) && Yii::app()->session['utm']->keywords = $v;

        $v = filter_var($rq->getParam('pm_source'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        !empty($v) && Yii::app()->session['utm']->pm_source = $v;

        Yii::app()->session['utm']->last_referer = $_SERVER['HTTP_REFERER'];

        if(empty(Yii::app()->session['utm']->point))
        {
          Yii::app()->session['utm']->point = Subdomain::site() . $_SERVER['REQUEST_URI'];
        }
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
  /**
   * перенаправление на вьюху с завершением регистрации
   */
  protected function directToCompleteRegistration($arError = [])
  {
    $bSendToRegister = (Share::isGuest()
      ? false
      : (Share::$UserProfile->exInfo->isblocked==User::$ISBLOCKED_NOT_FULL_ACTIVE));

    if($bSendToRegister)
    {
      $this->setPageTitle('Завершение регистрации');
      $this->render(
        $this->ViewModel->lastRegisterForm,
        ['viData' => Share::$UserProfile->getProfileDataView(),'arError'=>$arError]
      );
      Yii::app()->end();
    }
  }

  protected function renderRegisterGTM()
  {
    $this->layout = '//layouts/after_register';
    // делаем заход на /user/lead лишь один раз. Иначе сразу на профиль
    if(UserRegisterPageCounter::isSetData(Share::$UserProfile->id) > 0)
    {
      $this->redirect(MainConfig::$PAGE_PROFILE);
    }
    parent::render('index');
  }
  /**
   * @param $model - object
   * @throws CHttpException
   */
  public function renderVacPub($model)
  {
    if(isset($model->errors['access'])) // вакансия не найдена
    {
      $this->renderMessage($model->errors['access']);
    }
    else // вакансия новая, либо из базы
    {
      if(Yii::app()->request->isAjaxRequest)
      {
        parent::renderPartial($model->getView(), ['model'=>$model]);
      }
      else
      {
        $this->layout = '//user/vacancy/create/index';
        $this->ViewModel->init();
        parent::render($model->getView(), ['model'=>$model], false);
      }
    }
  }
  /**
   * @param $message - string
   */
  public function renderMessage($message)
  {
    $this->render(
      '//layouts/message',
      ['message'=>$message]
    );
  }
}
