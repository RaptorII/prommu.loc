<?php

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
      public $user_access = 1;
     
     public function init()
     {
        $user_id = Yii::app()->user->getId();
      $m = new UserAdm();
      $this->user_access = $m->getAccess($user_id);
     }
     
    public function actions()
    {           
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
            'yiichat'=>array('class'=>'YiiChatAction'),
            'yiiupload'=>array('class'=>'YiiUploadAction')
        );
        
    }
    
    public function actionIndex()
    {
       if(self::isAuth()){
        $data = 'ccccddddddddd';

          $this->render('index',array('content' => ''));
      }
    }

    public function isAuth()
    {
        if (Yii::app()->user->isGuest)
        {
            
            $this->actionLogin();
            Yii::app()->end();
        }
        return true;

    }
    /**
     * @param $module - string
     */
    private function checkAccess($module=false)
    {
        if(
            Yii::app()->user->isGuest
            ||
            ($module && strpos($this->user_access, $module)===false)
        )
        {
            $this->actionLogin();
            Yii::app()->end();
        }
    }
    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model=new LoginForm;
    //echo "LOGIN";die;
        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }


    public function actionMenuform()
    {
        $model=new MenuTree;
        $this->render('menu/view',array('model'=>$model));
    }


    public function actionMenu()
    {
        $model=new MenuTree;
        if($this->user_access != 1) {
                $this->render('access');
                    return;
      }
        if(isset($_POST['field_id']))
        {
            $id = $_POST['field_id'];
            $share = new Share;
            $menu_type = $share->getMenuType();
        $lang = $share->getLang();
            $parent = 0;
            if(isset($_POST['field_parent'])) $parent = $_POST['field_parent'];
            if(isset($_POST['field_op'])) {
                if($_POST['field_op']=='FORM')
                {
                    if(self::isAuth())
                        $this->render('menu/form',array('model'=>$model, 'id'=>$id, 'menu_type'=>$menu_type, 'menu_parent'=>$parent));
                    //self::isAuth('menu/form',array('model'=>$model, 'id'=>$id));
                    return;
                }
                else if($_POST['field_op']=='EDIT'){
                    $md = new Menu;
                    if($id>0)
                        $md->updateMenu($lang, $id); // Сохранить в базу [UPDATE]
                    else
                    {
                        $md->newMenu($lang, $id, $parent, $menu_type); // Сохранить в базу [NEW]
                    }
                    if(self::isAuth())
                        $this->render('menu/view',array('model'=>$model, 'menu_type'=>$menu_type));
                    //self::isAuth('menu/view',array('model'=>$model));
                    return;
                }
                else
                {
                    if(self::isAuth())
                        $this->render('menu/view',array('model'=>$model, 'menu_type'=>$menu_type));
                    //self::isAuth('menu/view',array('model'=>$model));
                    return;
                }
            }
            if(self::isAuth())
                $this->render('menu/form',array('model'=>$model, 'id'=>$id));
            //self::isAuth('menu/form',array('model'=>$model, 'id'=>$id));
            return;
        }
        if(self::isAuth())
            $this->render('menu/view',array('model'=>$model,'menu_type'=>1));
 
    }
    /**
     * 
     */
    public function actionPages()
    {
        $this->checkAccess();

        $this->render('pages/view',['model'=>new Pages(), 'id'=>0]);
    }
    /**
     * 
     */
    public function actionPagesForm()
    {
      $this->checkAccess();

      $rq = Yii::app()->getRequest();
      $id = intval($rq->getParam('id'));
      $type = $rq->getParam('pagetype');
      $params = $rq->getParam('PagesContent');
      $model = new PagesContent;

      if (count($params))
      {
        $model->SaveContent($id, $params, $type);
        Yii::app()->user->setFlash('success', 'Данные успешно сохранены');

        if($type=='news')
            $this->redirect('/admin/newspages');
        elseif($type=='articles')
            $this->redirect('/admin/articlespages');
        else
            $this->redirect('/admin/pages');
      }
      else
      {
        $this->setPageTitle('Настройка страницы сайта');
        $this->render('pages/form',['model'=>$model,'id'=>$id, 'pagetype'=>$type]);
      }
    }
    
    public function actionComments()
    {
    
        if(self::isAuth()) { 

            $model = new Comment;
            $model->unsetAttributes(); 
            $model->search();
            $model->setViewed($_GET['type']);
            $model->iseorp=$_GET['type'];
         
            $title = 'Отзывы';
            if($_GET['type']=='1')
                $this->breadcrumbs = array('Соискатели' => array('sect?p=app'), '1'=>$title);
            if($_GET['type']=='0')
                $this->breadcrumbs = array('Работодатели' => array('sect?p=emp'), '1'=>$title);
            $this->setPageTitle($title);

            $this->render('rating/view', array('model'=>$model));
        }
    } 

    public function actionCommentModer($id)
    {
       // if($this->user_access != 1) {
          //  $this->render('access');
          //  return;
       // }
       if(self::isAuth()) {
           $model = new Comment;
           $curr_status = intval($_POST['curr_status']);
           $model->ChangeModer($id, $curr_status);
           $model->unsetAttributes();  // clear any default values
           $model->search();

           $this->render('rating/view', array('model'=>$model));
       }
   }
    /**
     * 
     */
    public function actionArticlesPages()
    {
            $this->checkAccess('Статьи');

            $title = 'Управление статьями';
            $this->setPageTitle($title);
            $this->breadcrumbs = ['СЕО'=>['sect?p=seo'],'1'=>$title]; 
            $this->render('pages/artsview',['model'=>new Pages(),'id'=>0]);
    }
    /**
     * 
     */
    public function actionNewsPages()
    {
            $this->checkAccess('Новости');

            $title = 'Управление новостями';
            $this->setPageTitle($title);
            $this->breadcrumbs = ['Дополнительно'=>['sect?p=add'],'1'=>$title]; 
            $this->render('pages/newsview',['model'=>new Pages(),'id'=>0]);
    }

    public function actionMenuTree()
    {
        if($this->user_access != 1) {
                $this->render('access');
                    return;
      }
        
        $model=new MenuTree;
        if(isset($_POST['MenuTree']))
        {
          $model->attributes=$_POST['MenuTree'];
          //if($model->validate()) $model->calcdemo($model->attributes,'');
        }
        if(self::isAuth())
            $this->render('menuTree/view',array('model'=>$model));
        //self::isAuth(array('menuTree/view',array('model'=>$model)));
    }
    /**
     * 
     */
    public function actionPageUpdate($id)
    {
      $this->checkAccess();

      $rq = Yii::app()->getRequest();
      $type = $rq->getParam('pagetype');
      $params = $rq->getParam('PagesContent');
      $model = new PagesContent;

      if(count($params))
      {
        $model->SaveContent($id, $params);
        Yii::app()->user->setFlash('success', 'Данные успешно сохранены');

        if($type=='news')
          $this->redirect('/admin/newspages');
        elseif($type=='articles')
          $this->redirect('/admin/articlespages');
        else
          $this->redirect('/admin/pages');
      }
      else
      {
        $this->setPageTitle('Настройка страницы сайта');
        $this->render('pages/form',['model'=>$model,'id'=>$id, 'pagetype' =>$type]);
      }
    }
    /**
     * 
     */
    public function actionPageDelete($id)
    {
      $this->checkAccess();

      $model= new PagesContent;
      $model->DeleteContent($id);
      $this->render('pages/view',array('model'=>$model->getAllPages()));
    }
    
    // **** Управление пользователями (блокировка, смена пароля) ****
    public function actionEmpl()
    {
        if(self::isAuth()) {
            if(strpos($this->user_access, "Работодатели") === false) {
            $this->render('access');
            return;
        } 

            $model = new Employer;
            $model->unsetAttributes();  // clear any default values
            $model->status=3;
            $model->searchempl();
            if(isset($_GET['Employer'])){
                $model->attributes=$_GET['Employer'];
            }
            
            if(Yii::app()->getRequest()->getParam('export_xls')=='Y')
            {
                $model = new Employer();
                $data = $model->exportEmployers();
             
                if(!$data)
                {
                    $this->redirect(['empl']);
                }
                Yexcel::makeExcel($data['head'],$data['items'],'export_empks',$data['autosize']); 
            }
            
            
            $title = 'Зарегистрированные';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Работодатели' => array('sect?p=emp'), '1'=>$title);
            $this->render('users/viewempl',array('model'=>$model));
        }

        //$this->render('users/view');
    }

     public function actionAdmin()
    {   
        
            if(strpos($this->user_access, "Администраторы") === false) {
            $this->render('access');
            return;
        } 
        if(self::isAuth()) {
            // if($this->user_access != 1) {
            //     $this->render('access');
            //     return;
            // }

            $model = new UserAdm;
            $model->unsetAttributes();  // clear any default values
            // $model->status=3;
            $model->search();
            // if(isset($_GET['Employer'])){
            //     $model->attributes=$_GET['Employer'];
            // }
            $title = 'Администраторы';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Дополнительно'=>array('sect?p=add'),'1'=>$title); 
            $this->render('admin/view', array('model'=>$model));
        }

        //$this->render('users/view');
    }

     public function actionAdminEdit($id)
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        if(self::isAuth()) {
            $model = new UserAdm;
            if(!empty($_POST['UserAdm'])) {
                $model->updateAdmin($_POST['UserAdm'], $id);
                $model = new UserAdm;
                $model->unsetAttributes();  // clear any default values
                $model->search();
        
                $this->redirect(array('site/admin'));
            }

            // --- вывод формы
            $data = $model->getAdmin($id);
            $title = "Редактирование администратора '" . $data['login'] . "'";
            $this->setPageTitle($title);
            $this->breadcrumbs = array(
                'Дополнительно'=>array('sect?p=add'),
                'Администраторы'=>array('admin'),
                '1'=>$title
            ); 
            $this->render('admin/adminform', array('id'=>$id, 'data'=>$data));
        }
    }

    /**
     * Not used. MB kill?
     * 30.05.2019 Karpenko MV
     * @return array model
     */
    public function actionSeos() {
        if(self::isAuth()) {

            $Api = new Api();
            //Словарь
            $dict = $Api->getPost();
            $city = $Api->getCity();
            //Поиск
            $vacancy = $Api->getVacancy();
            $promo = $Api->getPromoSearch();
            $empl = $Api->getEmplSearch();

            $items = [
                'empl' =>  $empl,
                'promo' =>  $promo,
                'vacancy' =>  $vacancy,
                'city' =>  $city,
                'dict' =>  $dict,
            ];

            $this->render('stat/seos', array('items'=>$items));
        }
    }


    public function actionMonitoring()
    {
        if(self::isAuth()) {
            if(strpos($this->user_access, "Мониторинг") === false) {
                $this->render('access');
                return;
            }
        
            $section = file_get_contents('https://prommu.com/protected/runtime/application.log');
            $section = explode("---", $section);
            $j = 0;
            for($i = 0; $i < count($section); $i ++) {
                if(strpos($section[$i], "rss") === false){
                    $items[$j] = $section[$i];
                    $j++;
                } else unset($section[$i]);  
                
            }

            if($section == "") $section = "Проблем не обнаружено";
            $title = 'Мониторинг ошибок сайта';
            $this->setPageTitle($title);
            $this->breadcrumbs = array($title);
            $this->render('stat/view', array('items'=>$items));
        }
    }

    public function actionReport($id) {
        if(self::isAuth()) {
            if($this->user_access != 1) {
                $this->render('access');
                return;
            }

            $model = new ApiLogs;

            $day = 1;
            if(isset($_GET['day'])){
                $day=intval($_GET['day']);
            }
            $items = $model->Export($day, $id);
            $dt = new Datetime();
            $dt_string = $dt->format('Y-m-d\TH:i:s\Z');
            include Yii::getPathOfAlias('application')."/views/backend/site/stat/export.php";
            //include "/home/work/promo/www/protected/views/backend/site/stat/export.php";
            $object = array(
                "fileName" => $id."_".$dt_string.".xls",
                "fileSize" => 0,
                "content" => ""
            );

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            header("Content-Type: application/x-msexcel");
            header("Content-Disposition: attachment; filename=\"" . iconv('UTF-8', 'CP1251', $object['fileName']) . "\";");
            header("Content-Transfer-Encoding:­ binary");
            exportExcelXML($items, $object);
            header("Content-Length: " . $object["fileSize"]);

            echo $object["content"];
            //$this->render('stat/export', array('items'=>$items));

        }
    }




    public function actionUserUpdate($id)
    {
        $model = new User;
        if($id==0)
            $model->newUser();
        else
        {
            $model = User::model()->findByPk($id);
        }

        if(isset($_POST['User'])){
            // обработка формы
            //$model = new User;
            $model->attributes = $_POST['User'];
            if($model->validate())
            {
                if ($model->model()->count("login = :login", array(':login' => $model->login))) {
                    // Указанный логин уже занят. Создаем ошибку и передаем в форму
                    $model->addError('login', 'Логин уже занят');
                    $this->render('users/form', array('id'=>$id, 'model'=>$model));
                    return;
                }
                $model->UpdateUser($id, $model->attributes);
                $this->redirect(array('site/users/view'));
                return;
            }
        }
        // --- вывод формы
        $this->render('users/form', array('id'=>$id, 'model'=>$model));
    }

  public function actionLanguages()
  {
        if(self::isAuth()) {
          $model = new Languages('search');
          $model->unsetAttributes();  // clear any default values
      if(isset($_GET['Languages'])){
            $model->attributes=$_GET['Languages'];
      }
            $this->render('languages/ajxform', array('model'=>$model));
        }

  }

    public function actionVacancy()
    {
        $this->checkAccess('Вакансии');

        $model = new Vacancy;
        $model->unsetAttributes();  // clear any default values
        $model->searchvac();
        $model->status=1;

        if(isset($_GET['Vacancy']))
        {
            $model->attributes=$_GET['Vacancy'];
        }
        if($_GET['seo'])
        { 
            $title = 'Вакансии';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('СЕО'=>array('sect?p=seo'), '1'=>$title,);
            $this->render('vacancy/vacancy', array('model'=>$model));
        }
        else
        {
            if(Yii::app()->getRequest()->getParam('export_xls')=='Y')
            {
                $model = new Vacancy();
                $data = $model->exportVacancies();
                if(!$data)
                {
                    $this->redirect(['vacancy']);
                }
                Yexcel::makeExcel($data['head'],$data['items'],'export_vacancies',$data['autosize']); 
            }
            
            $title = 'Действующие';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Вакансии'=>array('sect?p=vac'), '1'=>$title,);
            $this->render('vacancy/view', array('model'=>$model));
        }
    }           
    
    public function actionVacCloud()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        
        if(!empty($_POST["dvgrid_c0"])) {
            $checks = $_POST["dvgrid_c0"];
            $model = new MailCloud;
            $model->mailerVac($checks);
        }
        $model = new Vacancy;
            $model->unsetAttributes();  // clear any default values
            $model->searchvac();
            $model->status=0;
            if(isset($_GET['Vacancy'])){
                $model->attributes=$_GET['Vacancy'];
            }
            $this->render('vacancy/mail', array('model'=>$model));
    }

    public function actionVacancyMail()
    {
    // if($this->user_access != 1) {
    //          $this->render('access');
    //              return;
    // }
    $model = new Vacancy;
            $model->unsetAttributes();  // clear any default values
            $model->searchvac();
            $model->status=0;
            if(isset($_GET['Vacancy'])){
                $model->attributes=$_GET['Vacancy'];
            }
            $title = 'Брошенные';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Вакансии'=>array('sect?p=vac'), '1'=>$title,);
            $this->render('vacancy/mail', array('model'=>$model));
        }           
    



  public function actionCities()
    {
    // if($this->user_access != 1) {
    //          $this->render('access');
    //              return;
    // }
        if(self::isAuth()) {
          $model = new City('search');
          $model->unsetAttributes();  // clear any default values
      if(isset($_GET['City'])){
            $model->attributes=$_GET['City'];
      }
            $this->render('cities/view', array('model'=>$model));
        }           
    }



  
  public function actionUniversity()
    {
    if($this->user_access != 1) {
                $this->render('access');
                    return;
    }

        if(self::isAuth())
            $this->render('university/view');
    }
  
  public function actionBanners()
    {
        //$model=new Championsips;
        if(self::isAuth())
            $this->render('banners/view');
    }

  /**
   * Modules setup
   */
  public function actionModule() {
        if($this->user_access != 1) {
                $this->render('access');
                    return;
    }
    $this->render('module');
  }

    public function actionMailCloudAll()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        $checks = Yii::app()->db->createCommand("SELECT u.id_user  FROM user u WHERE u.isblocked = 2")->queryRow();
        print_r($checks);
        $model = new MailCloud;
        $model->mailer($checks);
        // my-grid_c0[]
        // if(!empty($_POST['my-grid_c0'])) {
        //  $checks = $_POST["my-grid_c0"];
        //  $model = new MailCloud;
        //  $model->mailer($checks);
        // }
        $model = new User;
        $model->unsetAttributes();
        $model->isblocked=2;  // clear any default values
        $model->search();
        $this->render('wait/view', array('model'=>$model));
    }

    public function actionMailCloud()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        
        if(!empty($_POST["dvgrid_c0"])) {
            $checks = $_POST["dvgrid_c0"];
            $model = new MailCloud;
            $model->mailer($checks);
        }
        $model = new User;
        $model->unsetAttributes();
        $model->isblocked=2;  // clear any default values
        $model->search();
        $this->render('wait/view', array('model'=>$model));
    }

    public function actionExportAnalytic()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        $model = new Analytic;
        $model->exportAnalytic();

    }
  
    // Админка промоутер
    public function actionPromoEdit($id)
    {
      $this->checkAccess("Соискатели");

      $model = new User;

      if(!empty($_POST['User']))
      {
          $model->updatePromo($_POST['User'], $id);
          $this->redirect(['site/users']);
      }

      (new Promo)->setViewed($id);
      // --- вывод формы
      $data = $model->getUser($id);
      $title = 'Профиль соискателя';
      $this->setPageTitle($title);
      $this->breadcrumbs = array(
          'Соискатели' => array('sect?p=app'),
          'Зарегистрированные'=>array('users'),
          '1'=>$title
      );
      $this->render('users/promoform', ['id'=>$id, 'data'=>$data]);
    }

   public function actionVacancyCreate()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        if(self::isAuth()) {
        $model = new Vacancy;
        $model->createVac();
        $model->unsetAttributes();
            $this->render('vacancy/view', array('model'=>$model));

       }
        
    }

    public function actionVacancyEdit($id)
    {
        $this->checkAccess('Вакансии');

        $model = new Vacancy;
        if(!empty($_POST['Vacancy'])) // сохранение
        {
            $model->updateVacancy($id, $_POST['Vacancy']);
            Yii::app()->user->setFlash('success', 'Вакансия изменена');
            $this->redirect(['site/vacancy']);
        }
        else
        {
            $model->setViewed($id);
            $data = $model->getVacancyAdmin($id);
            $title = 'Редактирование вакансии '.$id;
            $this->setPageTitle($title);
            $this->breadcrumbs = $data['vac']['status'] != 0
                ? array(
                    'Вакансии'=>array('sect?p=vac'),
                    'Действующие'=>array('vacancy'), 
                    '1'=>$title,
                )
                : array(
                    'Вакансии'=>array('sect?p=vac'),
                    'Брошенные'=>array('vacancymail'), 
                    '1'=>$title,
                );
            $this->render('vacancy/vacancyform', ['viData'=>$data]);
        }
    }

    public function actionDeleteAnalytic()
    {
        if(strpos($this->user_access, "Аналитика") === false) {
            $this->render('access');
            return;
        } 
        else{
        if(!empty($_POST["dvgrid_c0"])) {
            $checks = $_POST["dvgrid_c0"];
            $model = new Analytic;
            $model->deleteAnalytic($checks);
        }
        $this->redirect(array('site/analytic'));
    }

    }

    public function actionDeleteEmpl()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        
        if(!empty($_POST["dvgrid_c0"])) {
            $checks = $_POST["dvgrid_c0"];
            $model = new Employer;
            $model->deleteEmployer($checks);
        }
        if(self::isAuth()) {
            $model = new Employer;
            $model->unsetAttributes();  // clear any default values
            $model->status=3;
            $model->searchempl();
            if(isset($_GET['Employer'])){
                $model->attributes=$_GET['Employer'];
            }
            $this->render('users/viewempl', array('model'=>$model));
        }
        
    }

    public function actionDeletePromo()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        
        if(!empty($_POST["dvgrid_c0"])) {
            $checks = $_POST["dvgrid_c0"];
            $model = new Promo;
            $model->deletePromo($checks);
        }
        if(self::isAuth()) {
            $model = new Promo;
            $model->unsetAttributes();  // clear any default values
            $model->searchpr();
            if(isset($_GET['Promo'])){
                $model->attributes=$_GET['Promo'];
            }
            $this->render('users/view', array('model'=>$model));
        }
        
    }

     public function actionVacancyDelete($id)
    {
        if(!self::isAuth() /*|| strpos($this->user_access, "vacancy") == false*/)
        {
            $this->render('access');
            return;
        }

        $model = new Vacancy;
        $type = 'success';
        $id_vac = Yii::app()->getRequest()->getParam('id');
        $id_user = Yii::app()->getRequest()->getParam('id_user');
        $arRes = $model->vacDelete($id_vac,$id_user);
 
        $arRes['error'] != 0 && $type = 'danger';
        Yii::app()->user->setFlash($type, $arRes['message']);
        $this->redirect(['site/vacancy']);    
    }

    public function actionEmplEdit($id)
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        if(self::isAuth()) {
            $model = new User;
            if(!empty($_POST['User'])) {

                $model->updateEmployer($_POST['User'], $id);
                $this->redirect(array('site/empl'));
            }

            (new Employer)->setViewed($id);
            // --- вывод формы
            $data = $model->getUserEmpl($id);
            $title = 'Профиль работодателя';
            $this->setPageTitle($title);
            $this->breadcrumbs = array(
                'Работодатели' => array('sect?p=emp'), 
                'Зарегистрированные'=>array('empl'),
                '1'=>$title
            );

            $this->render('users/emplform', array('id'=>$id, 'data'=>$data));
        }
    }
    
    public function actionAnalytCreate()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        if(self::isAuth()) {
            // $model = new User;
            // if(!empty($_POST['User'])) {

            //     $model->updateEmployer($_POST['User'], $id);
            //     $this->redirect(array('site/empl'));
            // }

            // --- вывод формы
            // $data = $model->getUserEmpl($id);
            // $title = 'Профиль работодателя';
            // $this->setPageTitle($title);
            // $this->breadcrumbs = array(
            //     'Работодатели' => array('sect?p=emp'), 
            //     'Зарегистрированные'=>array('empl'),
            //     '1'=>$title
            // );

            $this->render('analytic/create', array());
        }
    }

   public function actionPromoChangeModer($id)
   {
       // if($this->user_access != 1) {
          //  $this->render('access');
          //  return;
       // }
       if(self::isAuth()) {
           $model = new Promo;
           $curr_status = intval($_POST['curr_status']);
           $model->ChangeModer($id, $curr_status);
           $model->unsetAttributes();  // clear any default values
           $model->searchpr();
           /*
           if(isset($_GET['User'])){
               $model->attributes=$_GET['User'];
           }
           */
           $this->render('users/view', array('model'=>$model));
       }
   }

    public function actionVacancyChangeModer($id)
    {
        
        if(self::isAuth()) {
            $model = new Vacancy;
            $curr_status = intval($_POST['curr_status']);
            $model->ChangeModer($id, $curr_status);
            $model->unsetAttributes();  // clear any default values
            $model->searchvac();
            $model->status=1;
            $this->render('vacancy/view', array('model'=>$model));
        }
    }

   public function actionVacancyModer($id)
    {
        
        if(self::isAuth()) {
            $model = new Vacancy;
            $model->updateVacancy($id, array('cur_status'=>$_POST['curr_status']));
            $model->unsetAttributes();  // clear any default values
            $model->searchvac(); 
            $model->status=1;
            $this->render('vacancy/view', array('model'=>$model));
        }
    }

    public function actionEmplChangeModer($id)
    {
    
        if(self::isAuth()) {
            $model = new Employer;
            $curr_status = intval($_POST['curr_status']);
            $model->ChangeModer($id, $curr_status);
            $model->unsetAttributes();  // clear any default values
            $model->searchempl();
            
            if(isset($_GET['Employer'])){
                $model->attributes=$_GET['Employer'];
            }
            
            $this->render('users/viewempl', array('model'=>$model));
        }
    }


    // **** Управление пользователями (блокировка, смена пароля) ****

    public function actionWait()
    {
        if(strpos($this->user_access, "Брошенные регистрации") === false) {
            $this->render('access');
            return;
        } 
        if(self::isAuth()) {
            $model = new User;
            $model->unsetAttributes();
            $title = 'Брошенные регистрации';
            if(!empty($_POST["dvgrid_c0"])) {
                    $checks = $_POST["dvgrid_c0"];
                    $model = new MailCloud;
                    $model->mailer($checks);
                }
            if(isset($_GET['type'])) {
                if($_GET['type']==2){
                    $title = 'Брошенные';
                    $this->breadcrumbs = array('Соискатели' => array('sect?p=app'), '1'=>$title);
                }
                else{
                    $title = 'Брошенные';
                    $this->breadcrumbs = array('Работодатели' => array('sect?p=emp'), '1'=>$title);
                }
                $model->status=$_GET['type'];
            }
            $this->setPageTitle($title);
            $model->isblocked=2;  // clear any default values
            $model->search();
            if(isset($_GET['User'])){
                $model->attributes=$_GET['User'];
            }
            $this->render('wait/view', array('model'=>$model));
        }
    }

    public function actionUsers()
    {
      self::checkAccess("Соискатели");

      $model = new Promo;
      $model->unsetAttributes();  // clear any default values
      $model->searchpr();
      if(isset($_GET['Promo']))
      {
        $model->attributes=$_GET['Promo'];
      }
      // excel
      if(Yii::app()->getRequest()->getParam('export_xls')=='Y')
      {
        $data = $model->exportPromos();
        if(!$data)
        {
          $this->redirect(['users']);
        }
        Yexcel::makeExcel($data['head'],$data['items'],'export_promos',$data['autosize']);
      }

      if($_GET['seo'])
      {
        $title = 'Соискатели';
        $this->setPageTitle($title);
        $this->breadcrumbs = ['СЕО' => ['sect?p=seo'], $title];
        $this->render('users/promo', ['model'=>$model]);
      }
      else
      {
        $title = 'Зарегистрированные';
        $this->breadcrumbs = ['Соискатели' => ['sect?p=app'], $title];
        $this->setPageTitle($title);
        $this->render('users/view', ['model'=>$model]);
      }
    }

    public function actionStatist()
    {

        if(strpos($this->user_access, "Аналитика") === false) {
            $this->render('access');
            return;
        } 
        
        if(self::isAuth()) {
            
            $model = new Analytic;
            $domain = new Subdomain();
            
            $stat =  $model->getAnalityc();
            $model->unsetAttributes();  // clear any default values
            $model->search();
            $model->active=1;
            $model->name != 'NO ACTIVE';
            $title = 'Общая Статистика';
            $brdcrmbs = 'Общая';
            $title = 'Статистика Prommu';
            $brdcrmbs = 'Prommu';
            $model->subdomen=$_GET['subdomen'];
            
            if(Yii::app()->getRequest()->getParam('export_xls')=='Y')
            {
                $model = new Analytic();
                $data = $model->exportAnalytic();
              
                if(!$data)
                {
                    $this->redirect(['statist']);
                }
                Yexcel::makeExcel($data['head'],$data['items'],'export_statist',$data['autosize']); 
            }
            
            
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Статистика'=>array('sect?p=analytic'),'1'=>$brdcrmbs);          
            $this->render('analytic/statist', array('model'=>$model, 'stat'=>$stat, 'domains' => $domain->getData()));
        }
    }
    
    public function actionAnalytic()
    {

        if(strpos($this->user_access, "Аналитика") === false) {
            $this->render('access');
            return;
        } 
        if(self::isAuth()) {
         

            $rq = Yii::app()->getRequest();
            if(!$rq->isPostRequest)
            {
                  $command =  Yii::app()->getRequest()->getParam('command');

                  switch ($command) {
                      case 'new':

                            $view = 'analytic/create';
                            $data = [];

                            $title = 'Создание заявки аналитики';
                            $this->setPageTitle($title);
                            $this->breadcrumbs = array($title);

                            $bUrl = Yii::app()->request->baseUrl;
                            $gcs = Yii::app()->getClientScript();
                            $gcs->registerCssFile($bUrl . '/css/template.css');

                            $this->render($view, ['viData' => $data]); 

                          break;
                      
                      default:
                          # code...
                          break;
                  }
            }
            else //if (!isset($_POST['is_analitic']))
            {
                $model = new Analytic;
                $model->setRequest($_POST);
            }
            
            // $api = new Api();
            // $api->ideas();
            $model = new Analytic;
            $domain = new Subdomain();
            $stat = $model->getAnalityc();
            $model->unsetAttributes();  // clear any default values
            $model->search();
            $model->active=1;
            $model->name != 'NO ACTIVE';

            switch ($_GET['subdomen']) {
                case '':
                    $title = 'Общая';
                    $brdcrmbs = 'Общая аналитика';
                    $model->subdomen = $_GET['subdomen'];
                    break;
                case 0:
                    $title = 'Работодатели';
                    $brdcrmbs = 'Работодатели';
                    $model->subdomen = $_GET['subdomen'];
                    break;
                case 1:
                    $title = 'Соискатели';
                    $brdcrmbs = 'Соискатели';
                    $model->subdomen = $_GET['subdomen'];
                    break;
                case 2:
                    $title = 'Вакансии';
                    $brdcrmbs = 'Вакансии';
                    $model->subdomen = $_GET['subdomen'];
                    break;
            }

//            echo'<pre>';
//            print_r($stat);
            //print_r($_POST);
//            print_r($_GET);
//            die('die');

            $this->setPageTitle($title);
            $this->breadcrumbs = array('Аналитика'=>array('sect?p=analytic'),'1'=>$brdcrmbs);          
            $this->render('analytic/index', [
                'model' => $model,
                'stat'  => $stat,
                'domains' => $domain->getData(),
//                'filters' => [
//                    'promo'    => Yii::app()->getRequest()->getParam('promo'),
//                    'employer' => Yii::app()->getRequest()->getParam('employer'),
//                    'vacancy'  => Yii::app()->getRequest()->getParam('vacancy'),
//                ],
            ]);
        }
    }

    public function actionMarketingAnalytic() {

        if(strpos($this->user_access, "Аналитика") === false) {
            $this->render('access');
            return;
        }

        if(self::isAuth()) {

            $title = 'Маркетологи';
            $brdcrmbs = 'Аналитика для маркетологов';

            // вовод старого виджета маркетологам
            // Ден сказал хз зачем

            $rq = Yii::app()->getRequest();
            if(!$rq->isPostRequest)
            {
                $command =  Yii::app()->getRequest()->getParam('command');
                echo($command);

                switch ($command) {
                    case 'new':
                        die();
                        $view = 'analytic/create';
                        $data = [];

                        $title = 'Создание заявки аналитики';
                        $this->setPageTitle($title);
                        $this->breadcrumbs = array($title);

                        $bUrl = Yii::app()->request->baseUrl;
                        $gcs = Yii::app()->getClientScript();
                        $gcs->registerCssFile($bUrl . '/css/template.css');

                        $this->render($view, ['viData' => $data]);

                        break;

                    default:
                        # code...
                        break;
                }
            }
            else
            {
                $model = new Analytic;
                $model->setRequest($_POST);
            }

            $model = new Analytic;
            $domain = new Subdomain();
            $model->unsetAttributes();  // clear any default values
            $model->search();
            $model->active=1;
            $model->name != 'NO ACTIVE';

            $this->setPageTitle($title);
            $this->breadcrumbs = array('Аналитика'=>array('sect?p=analytic'),'1'=>$brdcrmbs);

            $this->render('analytic/marketing_analytic',
                [
                'model' => $model,
                'domains' => $domain->getData(),
                'data'  => '',
                ]
            );

        }
    }

    public function actionFeedback()
    {
      $title = 'Обратная связь';
      $this->checkAccess($title);
      $rq = Yii::app()->getRequest();
      $id = $rq->getParam('id');
      if($rq->isPostRequest && $id!=='0')
      {
        $arRes = ['message' => 'Ошибка изменения данных'];
        $data = $rq->getParam('data');
        $data = json_decode($data, true, 5, JSON_BIGINT_AS_STRING);
        $model = new Feedback;
        $arRes['message'] = $model->ChangeModer($data['id'], $data['value']);
        echo CJSON::encode($arRes);
        Yii::app()->end();
      }
      if($id==='0')
      {
        $model = new Feedback;

        if($rq->isPostRequest)
        {
          $data = $model->setNewAdminAppeal($rq->getParam('Feedback'));
          if(!$data['errors'])
          {
            $this->redirect($rq->getParam('back')==='profile'
              ? $data['receiver']['profile_admin'] . '?anchor=tab_feedback'
              : '/admin/feedback'
            );
          }
        }
        else
        {
          $data = $model->getDataForNewAdminAppeal();
        }
        $this->setPageTitle($title);
        $this->breadcrumbs = [$title];
        $this->render('feedback/new',['viData'=>$data]);
      }
      else
      {
        $this->setPageTitle($title);
        $this->breadcrumbs = [$title];
        $this->render('feedback/list-feedback');
      }
    }

    public function actionMail($id)
    {
      $this->checkAccess('Обратная связь');

      if(!empty($_POST['Feedback']) )
      {
        $model = new Feedback();
        $model->setFeedback($_POST['Feedback']);
        $mailer = new MailCloud();
        $mailer->mailerMail($_POST['Feedback']);
        $this->redirect(array('site/feedback'));
      }
      $model = new Feedback();
      $data = $model->getDatas($id);
      $model->setStatusReaded($id,'feedback');
      $title = 'Ответ на обращение';
      $this->setPageTitle($title);
      $this->breadcrumbs = ['Обратная связь'=>['feedback'], $title];
      $this->render('feedback/mail', ['id'=>$id, 'data'=>$data]);
    }

    public function actionUpdate($id)
    {
      $this->checkAccess('Обратная связь');

      if(!empty($_POST['Update']) )
      {
        $idus = $_POST['Update']['idusp'];
        $model = Share::isApplicant($_POST['usertype']) ? (new ImApplic) : (new ImEmpl);
        $model->recordAdminMessage($id, $_POST['Update']['message'], $idus);
        $_POST['Update']['iduse'] = Im::$ADMIN_APPLICANT;
        $mailer = new MailCloud();
        $mailer->mailerMail($_POST['Update']);
        $model = new FeedbackTreatment;
        $model->unsetAttributes();  // clear any default values
        $model->search();
        $this->redirect(["site/update/$id"]);
      }
      $model = new Feedback;
      $data = $model->getAdminData($id);
      $model->setStatusReaded($id,'chat');
      $title = 'Ответ на обращение';
      $this->setPageTitle($title);
      $this->breadcrumbs = ['Обратная связь'=>['feedback'], $title];
      $this->render('feedback/update', ['id'=>$id, 'data'=>$data]);
    }
    


    public function actionPromoBlocked($id)
    {
        $model = new Promo;
        $curr_status = intval($_POST['curr_status']);
        $model->blockedPromo($id,$curr_status);
        $model->unsetAttributes();  // clear any default values
        $model->searchpr();
        //$model->UpdateUser($id, $model->attributes);
        //$this->redirect(array('site/users/view'));
        $this->render('users/view', array('model'=>$model));
    }


    public function actionEmplBlocked($id)
    {
        $model = new Employer;
        $curr_status = intval($_POST['curr_status']);
        $model->blocked($id,$curr_status);
        $model->unsetAttributes();  // clear any default values
        $model->searchempl();
        $this->render('users/viewempl', array('model'=>$model));
    }

    public function actionExportPromo()
    {
        
        $model = new User;
        $model->exportPromoCSV();
        //$model->unsetAttributes();  // clear any default values
        //$model->status=2;
        //$model->search();
    }

    public function actionExportEmpl()
    {
        $model = new Employer;
        $res = $model->exportEmplCSV();
        //print_r($res);
    }
    public function actionSeo()
    {
        if(!self::isAuth() || strpos($this->user_access, "SEO") === false)
        {
            $this->render('access');
            return;
        }

        $rq = Yii::app()->getRequest();
        $bUrl = Yii::app()->request->baseUrl;
        $gcs = Yii::app()->getClientScript();
        $id = $rq->getParam('id');

        $model = new Seo;
        $data = array();

        if(isset($id))
        {
            if($rq->getParam('delete')==1)
            {
                $model->deleteItem($rq);
                $this->redirect(['seo']); 
            }

            if($rq->isPostRequest)
            {
                $data = $model->setDataItem($rq);
                $data['redirect'] && $this->redirect(['seo']);
            }
            else
            {
                $data = $model->getDataItem($rq);
            }

            $gcs->registerCssFile($bUrl . '/css/template.css');
            $this->setPageTitle('Редактирование страницы');
            $this->breadcrumbs = array(
                'СЕО'=>['sect?p=seo'],
                'Мета данные'=>['/seo'],
                '2'=>'Редактирование страницы'
            );
            $this->render('seo/item',['viData'=>$data,'model'=>$model]); 
        }
        else
        {
            $data['domain'] = Subdomain::domain();
            $data['subdomains'] = Subdomain::getCacheData()->data;
            $data['dir'] = $rq->getParam('dir')=='asc' ? 'desc' : 'asc';
            $data['head'] = array(
                    'id' => 'ID',
                    'url' => 'Url',
                    'meta_title' => 'Title',
                    'mdate' => 'Дата изменения'
                );

            $this->setPageTitle('Мета данные');
            $this->breadcrumbs = array('СЕО'=>['sect?p=seo'],'1'=>'Мета данные');

            if($rq->isAjaxRequest)
            {
                $this->renderPartial('seo/list_table',['viData'=>$data,'model'=>$model]);
            }
            else
            {
                $bUrl = Yii::app()->request->baseUrl;
                $gcs = Yii::app()->getClientScript();
                $gcs->registerCssFile($bUrl . '/css/template.css');
                $this->render('seo/list',['viData'=>$data,'model'=>$model]); 
            }
        }
    }

    /**
     * Добавление SEO-тегов для URL
     */
    public function actionSeoAdd()
    {
        $this->render('seo/add-edit');
    }

    /**
     * Добавление SEO-тегов для URL
     */
    public function actionSeoEdit($id)
    {
         if($_GET['subdomen'] == 'spb'){
            $model = new Seo_spb('search'); 
        } else $model = new Seo('search');
        $item = $model->exist($id);

        if(!$item)
            $this->redirect(array('site/seo'));

        $this->render('seo/add-edit', array('item' => $item));
    }

    /**
     * Сохранение SEO-тегов для URL
     */
    public function actionSeoSave()
    {
         if($_GET['subdomen'] == 'spb'){
            $model = new Seo_spb('search'); 
        } else $model = new Seo('search');
        $data = $_POST['data'];
        
    
        if($model->exist($data['url']))
        {       
                $model = Seo::model()->findByPk($data['id']);
                $model->attributes=$_POST['data'];
                
                $model->save();
                // $model = Seo::model()->findByPk($data['id']);

                // $model->attributes=$data->attributes;
                // $model->url = $data['url'];
                // $model->meta_title = $data['meta_title'];
                // $model->meta_description = $data['meta_description'];
                // $model->meta_keywords = $data['meta_keywords'];
                // $model->seo_h1 = $data['seo_h1'];
                // $model->save();
            
            //  $model->updateExist($data);
        }
        else
        {
            // новая запись
            if($data['url'] && !$model->exist($data['url']))
            $model->attributes=$_POST['data'];
            $model->save();
        
            // if($data['url'] && !$model->exist($data['url']))
            //  $model->saveNew($data);
        }

        $this->redirect(array('site/seo'));
    }

    /**
     * Удаление SEO-тегов для URL
     */
    public function actionSeoDelete($id)
    {
        $model = new Seo;
        $model->deleteSeo($id);
        
        $this->render('seo/list', array('model' => $model));
    }
    /*
    *   FAQ
    */
    public function actionFaq()
    {
        if(self::isAuth()) {
            $model = new Faq();
            $model->unsetAttributes();  // clear any default values
            $model->search();
            $title = 'Список елементов FAQ';
            $brdcrmbs = 'FAQ';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Дополнительно'=>array('sect?p=add'),'1'=>$brdcrmbs); 
            $this->render('faq/list', array('model' => $model));
        }
    }
    /*
    *   элемент FAQ
    */
    public function actionFaqedit($id)
    {
        if(self::isAuth() && $id>0 && is_numeric($id)) {
            $model = new Faq();
            if(Yii::app()->getRequest()->isPostRequest){
                $model->changeFaqItem($id);
                $this->redirect('/admin/site/faq');
            }
            else{
                $data = $model->getFaqItem($id);
                $title = 'Редактирование элемента '.$id;
                $this->setPageTitle($title);
                $this->breadcrumbs = array(
                    'Дополнительно'=>array('sect?p=add'),
                    'FAQ'=>array('faq'),
                    '1'=>$title
                ); 
                $this->render('faq/item', array('data' => $data, 'id'=>$id));               
            }

        }
    }
    /*
    *   добавить элемент FAQ
    */
    public function actionAddfaq()
    {
        if(self::isAuth()) {
            $model = new Faq();
            if(Yii::app()->getRequest()->isPostRequest){
                $model->addFaqItem();
                $this->redirect('/admin/site/faq');
            }
            else{
                $title = 'Добавление элемента FAQ';
                $this->setPageTitle($title);
                $this->breadcrumbs = array(
                    'Дополнительно'=>array('sect?p=add'),
                    'FAQ'=>array('faq'),
                    '1'=>$title
                );
                $this->render('faq/item');
            }
        }
    }
    /*
    *   удалить элемент FAQ
    */
    public function actionFaqDelete($id)
    {
        if(self::isAuth() && $id>0 && is_numeric($id)) {
            $model = new Faq;
            $model->deleteFaq($id);
            $this->redirect('/admin/site/faq');
        }
    }
    /*
    *  страница РАБОТА ДЛЯ СТУДЕНТОВ
    */
    public function actionForstudents()
    {
        if(self::isAuth()) {
            $model = new Seo;
            if(Yii::app()->getRequest()->isPostRequest){
               $model->updateExist($_POST);
               Yii::app()->user->setFlash('success',[]);
            }
            $data = $model->exist('/work-for-students');
            $title = 'Работа для студентов';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Дополнительно'=>array('sect?p=add'),'1'=>$title); 
            $this->render('pages/for-students', array('data' => $data));
        }
    }
    /*
    *   IDEAS
    */
    public function actionIdeas()
    {
        if(self::isAuth()) {
            $model = new Ideas();
            $model->unsetAttributes();
            $model->search();
            $title = 'Идеи и предложения';
            $this->setPageTitle($title);
            $this->breadcrumbs = array($title);
            $this->render('ideas/list', array('model' => $model));
        }
    }
    /*
    *   элемент IDEA
    */
    public function actionIdeaedit($id)
    {
        if(self::isAuth() && $id>0 && is_numeric($id)) {
            $model = new Ideas();
            if(Yii::app()->getRequest()->getParam('event')=='idea'){
                $model->changeIdea($id);
                $this->redirect('/admin/site/ideas');
            }
            if(Yii::app()->getRequest()->getParam('event')=='comment'){
                $model->setComment(1);
                $this->redirect('/admin/site/ideas');
            }
            else{
                $model->setViewed($id);
                $data = $model->getIdeaForAdmin($id);
                $title = 'Редактирование идеи/предложения '.$id;
                $this->setPageTitle($title);
                $this->breadcrumbs = array('Идеи и предложения'=>array('ideas'),'1'=>$title); 
                $this->render('ideas/item', array('data' => $data, 'id'=>$id));               
            }

        }
    }
    /*
    *   удалить элемент IDEA
    */
    public function actionIdeaDelete($id)
    {
        if(self::isAuth() && $id>0 && is_numeric($id)) {
            $model = new Ideas;
            $model->deleteIdea($id);
            $this->redirect('/admin/site/faq');
        }
    }
    /*
    *   промежуточный раздел
    */
    public function actionSect()
    {
        $this->checkAccess();
        $this->render('section'); 
    }
    /**
     *      Раздел настроек
     */
    public function actionSettings()
    {
        $this->checkAccess();
        $title = 'Настройки';
        $this->setPageTitle($title);
        $this->breadcrumbs = array('1'=>$title);

        $rq = Yii::app()->getRequest();
        if($rq->isPostRequest)
        {
            $model = new Settings;
            $data = $model->setData($rq);
        }

        $this->render('settings/index', array('data' => $data));
    }
    /**
     *      Раздел уведомлений
     */
    public function actionNotifications()
    {
        $this->checkAccess();

        $rq = Yii::app()->getRequest();

        $id = $rq->getParam('id');
        $type = $rq->getParam('type');
        
        $title = 'Уведомления';
        $arBread[$title] = ['notifications'];

        switch ($type)
        {
          case 'system': $model = new Mailing; break;
          case 'event': $model = new MailingEvent; break;
          case 'letter': $model = new MailingLetter; break;
          case 'template': $model = new MailingTemplate; break;
          case 'message': $model = new AdminMessage; break;
        }

        if(isset($id))
        {
            $title = ($id>0 ? 'Редактирование ' : 'Создание ') . $model->pageTitle;
            $type=='system' && $title = $model->pageTitle;
            if(!$rq->isPostRequest)
            {
                $data = $model->getData($id);
            }
            else
            {
                $data = $model->setData($rq);
                var_dump($data);
                $data['redirect'] && $this->redirect(['notifications','anchor'=>'tab_'.$type]);
            }

            $data['id'] = $id;
            array_push($arBread, $title);
        }
        else
        {
            $model = (object) ['view'=>'notifications/index'];
            $data = [];
        }

        $this->setPageTitle($title);
        $this->breadcrumbs = $arBread;

        $bUrl = Yii::app()->request->baseUrl;
        $gcs = Yii::app()->getClientScript();
        $gcs->registerCssFile($bUrl . '/css/template.css');

        $this->render($model->view, ['viData' => $data]);
    }
    /*
    *   Технический раздел
    */
    public function actionSystem()
    {
        $this->checkAccess();

        $rq = Yii::app()->getRequest();

        $id = $rq->getParam('id');
        $type = $rq->getParam('type');

        $title = 'Разработчикам';
        $arBread[$title] = ['system'];

        switch ($type)
        {
            case 'review': $model = new CodeReview($id); break;
        }

        if(isset($id))
        {
            $title = $model->pageTitle;
            $view = $model->view;
            if(!$rq->isPostRequest)
            {
                $data = $model->getData($id);
            }
            else
            {
                $data = $model->setData($rq);
                $data['redirect'] && $this->redirect(['system']);
            }
            $data['id'] = $id;
            array_push($arBread, $title);
        }
        else
        {
            $view = 'system/list';
            $data = [];
        }

        $this->setPageTitle($title);
        $this->breadcrumbs = $arBread;
        $bUrl = Yii::app()->request->baseUrl;
        $gcs = Yii::app()->getClientScript();
        $gcs->registerCssFile($bUrl . '/css/template.css');

        $this->render($view, ['viData' => $data]);       
    }
    /**
     * Отзывы о нас
     */
    public function actionReviews()
    {
        $this->checkAccess();

        $rq = Yii::app()->getRequest();
        $id = $rq->getParam('id');

        $title = 'Отзывы о нас';
        $arBread[$title] = ['reviews'];

        if(isset($id))
        {
            $title = 'Просмотр отзыва';
            $view = 'reviews/item';
            $model = new CommentsAboutUs($id);
            $data = $model->getData($id);
            $data['id'] = $id;
            array_push($arBread, $title);
        }
        else
        {
            $view = 'reviews/list';
            $data = [];
        }

        $this->setPageTitle($title);
        $this->breadcrumbs = $arBread;
        $bUrl = Yii::app()->request->baseUrl;
        $gcs = Yii::app()->getClientScript();
        $gcs->registerCssFile($bUrl . '/css/template.css');

        $this->render($view, ['viData' => $data]);  
    }
    /**
     * Файловый менеджер
     */
    public function actionFilemanager()
    {
        $this->checkAccess();
        $title = 'Файловый менеджер';
        $this->setPageTitle($title);
        $this->breadcrumbs = [1 => $title];
        $this->render('filemanager/index'); 
    }
    /**
     * Все пользователи
     */
    public function actionAll_users()
    {
        $this->checkAccess();
        $title = 'Все пользователи';
        $this->setPageTitle($title);
        $this->breadcrumbs = [1 => $title];
        if(Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial('users/list-all-ajax');
        }
        else
        {
            $this->render('users/list-all');
        }
    }
    /*
     * Проверка самозанятого
     */
    public function actionSelf_employed()
    {
      $this->checkAccess();
      $title = 'Проверка ИНН';
      $this->setPageTitle($title);
      $this->breadcrumbs = [1 => $title];
      $this->render('self_employed/index');
    }
    /*
    *   екшен для метода Analytic->exportAnalytic
    */
    public function actionAnalytic_byperiod()
    {
        // доступ только админам
        $this->checkAccess();
        //
        $model = new Analytic();
        $rq = Yii::app()->getRequest();
        $arRes = [
            'type' => $rq->getParam('type'),
            'filter' => [
                'bdate' => $rq->getParam('export_beg_date'),
                'edate' => $rq->getParam('export_end_date'),
                'status' => $rq->getParam('export_status'),
                'domain' => $rq->getParam('export_domain')
            ]
        ];
        //
        if(Yii::app()->request->isAjaxRequest) // обновления по аякс
        {
            $arRes['items'] = $model->exportAnalytic();
            $this->renderPartial('analytic/list-ajax',['viData'=>$arRes]);
        }
        else // обновление всей страницы
        {
            // изначально параметры задаем сами. Ищем данные за сегодня
            $_GET['export_beg_date'] = $arRes['filter']['bdate'] = date('d.m.Y');
            $_GET['export_end_date'] = $arRes['filter']['edate'] = date('d.m.Y');
            $_GET['export_domain'] = 'all';
            // список доменов для фильтра
            $arRes['domains'] = array_merge(
                [(array)Subdomain::domain()],
                Subdomain::getData()
            );
            //
            switch($arRes['type'])
            {
                case 'all':
                    $title = 'Общая аналитика'; 
                    $_GET['export_status']='empl_all'; 
                    break;
                case 'applicant':
                    $title = 'Аналитика по соискателям'; 
                    $_GET['export_status']='promo_all'; 
                    break;
                case 'employer':
                    $title = 'Аналитика по работодателям'; 
                    $_GET['export_status']='empl_all'; 
                    break;
                case 'vacancy': 
                    $title = 'Аналитика по вакансиям'; 
                    $_GET['export_status']='vac_all';
                    break;
                default: 
                    $this->redirect('/admin/analytic?subdomen='); 
                    break;
            }
            //
            $arRes['items'] = $model->exportAnalytic();

            $this->setPageTitle($title);
            $this->breadcrumbs = [$title];
            $this->render('analytic/list',['viData'=>$arRes]);
        }
    }

    public function actionAnalyticByparams() {

        $this->checkAccess();

        $rq = Yii::app()->getRequest();
        $arRes = [
            'type' => $rq->getParam('type'),
            'filter' => [
                'bdate' => $rq->getParam('export_beg_date'),
                'edate' => $rq->getParam('export_end_date'),
                'status' => $rq->getParam('export_status'),
                'head' => $rq->getParam('export_head'),
                'domain' => $rq->getParam('export_domain')
            ]
        ];

        switch($arRes['type'])
        {
            case 'applicant':
                $title = 'Аналитика по соискателям';
                // мускул-запрос по параметрам соискателя
                $model = new Promo();
                $arRes['items'] = $model->exportPromosTable($arRes['filter']);
                break;
            case 'employer':
                $title = 'Аналитика по работодателям';
                // мускул-запрос по параметрам соискателя
                $model = new Employer();
                $arRes['items'] = $model->exportEmployersTable($arRes['filter']);
                break;
            case 'vacancy':
                $title = 'Аналитика по вакансиям';
                // мускул-запрос по параметрам соискателя
                $model = new Vacancy();
                $arRes['items'] = $model->exportVacanciesTable($arRes['filter']);
                break;
            case 'registrations':
                $title = 'Аналитика по регистрациям';

                // add domains to filter
                $arRes['domains'] = array_merge(
                    [(array)Subdomain::domain()],
                    Subdomain::getData()
                );
                // мускул-запрос по параметрам регистрации
                $model = new Analytic();
                $arRes['items'] = $model->exportAnalyticTable($arRes['filter']);
                break;
            default:
                $this->redirect('/admin/analytic?subdomen=');
                // редирект на мейн анал*, пусть будет, пока будет
                break;
        }

//            echo'<pre>';
//            print_r($arRes['filter']);
//            echo'</pre>';
//            die('die');

        if (Yii::app()->request->isAjaxRequest) { // обновления по аякс

            $this->renderPartial('analytic/listparams-ajax',['viData'=>$arRes]);

        } else {

            $_GET['export_beg_date'] = $arRes['filter']['bdate'] = date('d.m.Y');
            $_GET['export_end_date'] = $arRes['filter']['edate'] = date('d.m.Y');

            $this->setPageTitle($title);
            $this->breadcrumbs = [$title];
            $this->render('analytic/list_params',
                [
                    'viData' => $arRes,
                ]
            );
        }
    }
  /**
   *  Зарегистрированные по новой реге
   */
  public function actionRegister()
  {
    $this->checkAccess();

    $arBread = [];
    $model = new UserRegisterAdmin();

    if(Share::isEmployer($model->getType))
    {
      $arBread['Работодатели'] = ['sect?p=emp'];
    }
    else
    {
      $arBread['Соискатели'] = ['sect?p=app'];
    }

    $url = '/admin/register?user=';
    switch ($model->getState)
    {
      case UserRegisterAdmin::$STATE_PROFILE:
        $title = 'Активация профиля';
        $arBread[$title] = $url .
          (Share::isEmployer($model->getType) ? '3&state=profile' : '2&state=profile');
        break;
      case UserRegisterAdmin::$STATE_AVATAR:
        $title = 'Незаполненное фото';
        $arBread[$title] = $url .
          (Share::isEmployer($model->getType) ? '3&state=avatar' : '2&state=avatar');
        break;
      case UserRegisterAdmin::$STATE_CODE:
        $title = 'Не подтвердил код';
        $arBread[$title] = $url .
          (Share::isEmployer($model->getType) ? '3&state=code' : '2&state=code');
        break;
    }

    if(intval($model->getId))
    {
      $title = 'Просмотр регистрации #' . $model->getId;
      array_push($arBread,$title);
    }

    $this->setPageTitle($title);
    $this->breadcrumbs = $arBread;
    $this->render($model->view, ['model'=>$model]);
  }
}