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

    public function actionPages()
    {
        $model=new Pages;
        $id = 0;
        //if(isset($_POST['field_id'])) $id = $_POST['field_id'];
        if(self::isAuth())
            $this->render('pages/view',array('model'=>$model,'id'=>$id));
        //self::isAuth(array('pages/view',array('model'=>$model, 'id'=>$id)));
    }


    public function actionPagesForm()
    {
        $share = new Share;
        $lang = "ru";
        $pagetype = '';
        if(!empty($_POST['pagetype'])) $pagetype = $_POST['pagetype'];
        if(!empty($_GET['pagetype'])) $pagetype = $_GET['pagetype'];

        $model=new PagesContent;
        $id = 0;
        if(isset($_POST['id'])) $id = $_POST['id'];
        //print_r($_POST);die;
        if(isset($_POST['PagesContent']))
        {
            //print_r($_POST['PagesContent']);die;
            // Save to base
            $model->attributes=$_POST['PagesContent'];
            $model->SaveContent($id,$model,$_POST['PagesContent']['link'],$lang, $pagetype);
            if($_POST['pagetype'] == 'news') {
                $model = new Pages;
                $this->render('pages/newsview', array('model' => $model));
            } elseif($_POST['pagetype'] == 'articles') {
                $model = new Pages;
                $this->render('pages/artsview', array('model' => $model));
            }else 
            {


                $this->render('pages/view', array('model' => $model));
            }
            return;
        }
        if(self::isAuth())
           $this->render('pages/form',array('model'=>$model,'id'=>$id, 'pagetype'=>$pagetype));
        //self::isAuth(array('pages/form',array('model'=>$model, 'id'=>$id)));

    }
    
    public function actionComments()
    {
    
        if(self::isAuth()) { 

            $model = new Comment;
            $model->unsetAttributes(); 
            $model->search();
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
   

    public function actionArticlesPages()
    {
        $model=new Pages;
        $id = 0;
        if(strpos($this->user_access, "Статьи") === false) {
            $this->render('access');
            return;
        } 
        if(self::isAuth()){
            $title = 'Управление статьями';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('СЕО'=>array('sect?p=seo'),'1'=>$title); 
            $this->render('pages/artsview',array('model'=>$model,'id'=>$id));
        }
        //self::isAuth(array('pages/view',array('model'=>$model, 'id'=>$id)));
    }

    public function actionNewsPages()
    {
        $model=new Pages;
        $id = 0;
       if(strpos($this->user_access, "Новости") === false) {
            $this->render('access');
            return;
        } 
        if(self::isAuth()){
            $title = 'Управление новостями';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Дополнительно'=>array('sect?p=add'),'1'=>$title); 
            $this->render('pages/newsview',array('model'=>$model,'id'=>$id));
        }
        //self::isAuth(array('pages/view',array('model'=>$model, 'id'=>$id)));
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

    public function actionPageUpdate($id)
    {
        $model=new PagesContent;
        $pagetype = '';
        if(!empty($_POST['pagetype'])) $pagetype = $_POST['pagetype'];
        if(!empty($_GET['pagetype'])) $pagetype = $_GET['pagetype'];


        if(isset($_POST['PagesContent']))
        {
            
            $model->attributes=$_POST['PagesContent'];
            
            $model->SaveContent($id,$model,$_POST['PagesContent']['link'],"ru");
        
            if($pagetype == 'news') {
                $model = new Pages;
                $this->render('pages/newsview', array('model' => $model));
            } elseif($pagetype == 'articles'){
                $model = new Pages;
                $this->render('pages/artsview', array('model' => $model));
            }else{
                $this->render('pages/view', array('model' => $model));
            }

            return;
        }
        $this->setPageTitle('Настройка страницы сайта');
        $this->render('pages/form',array('model'=>$model,'id'=>$id, 'pagetype' =>$pagetype));
    }

    public function actionPageDelete($id)
    {
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

     public function actionSeos()
    {
        if(self::isAuth()) {
           
        $Api = new Api();
        //Словарь
        $dict = $Api->getPost();
        $city = $Api->getCity();
        //Поиск 
        $vacancy = $Api->getVacancy();
        $promo = $Api->getPromoSearch();
        $empl = $Api->getEmplSearch();
  

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
    if(strpos($this->user_access, "Вакансии") === false) {
            $this->render('access');
            return;
        } 
            $model = new Vacancy;
            $model->unsetAttributes();  // clear any default values
            $model->searchvac();
            $model->status=1;
            if(isset($_GET['Vacancy'])){
                $model->attributes=$_GET['Vacancy'];
            }
            if($_GET['seo']){ 
                $title = 'Вакансии';
                $this->setPageTitle($title);
                $this->breadcrumbs = array('СЕО'=>array('sect?p=seo'), '1'=>$title,);
                $this->render('vacancy/vacancy', array('model'=>$model));
            }
            else{
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


//========= C A R D S ===============

    // **** Управление пользователями (карточки) ****
    public function actionCards()
    {
        if(self::isAuth()) {
          if(strpos($this->user_access, "Карта") === false) {
            $this->render('access');
            return;
        } 
            $model = new UserCard('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['User'])){
                $model->attributes=$_GET['User'];
            }
            $title = 'Заказ карты PROMMU';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Услуги'=>array('sect?p=service'),'1'=>$title);
            $this->render('users/cardsview', array('model'=>$model));
        }

        //$this->render('users/view');
    }

    public function actionCardEdit($id)
    {
        $model = new CardRequest;

        if(isset($_POST['Card'])) {
            // обработка формы
            $model->updateCard($id, $_POST['Card']);
        }

            // --- вывод формы
        $data = $model->getCard($id);
        $title = "Редактирование заказа " . $id;
        $this->setPageTitle($title);
        $this->breadcrumbs = array(
            'Услуги'=>array('sect?p=service'),
            'Заказ карты PROMMU'=>array('cards'),
            '1'=>$title
        ); 
        $this->render('users/cardform', array('id'=>$id, 'data'=>$data));

    }

     // **** Управление пользователями (карточки) ****
    public function actionMedCards()
    {
        if(self::isAuth()) {
          if(strpos($this->user_access, "Карта") === false) {
            $this->render('access');
            return;
        } 
            $model = new MedCard('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['User'])){
                $model->attributes=$_GET['User'];
            }
            $title = 'Заказ медкниги';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Услуги'=>array('sect?p=service'),'1'=>$title);
            $this->render('users/medcardsview', array('model'=>$model));
        }

        //$this->render('users/view');
    }

    public function actionMedCardEdit($id)
    {
        $model = new MedRequest;

        if(isset($_POST['Card'])) {
            // обработка формы
            $model->updateCard($id, $_POST['Card']);
        }

            // --- вывод формы
        $data = $model->getCard($id);
        $title = "Редактирование заказа " . $id;
        $this->setPageTitle($title);
        $this->breadcrumbs = array(
            'Услуги'=>array('sect?p=service'),
            'Заказ медкниги'=>array('medcards'),
            '1'=>$title
        ); 
        $this->render('users/medcardform', array('id'=>$id, 'data'=>$data));

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
    


    public function actionExportCards()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        //my-grid_c0[]
        if(!empty($_POST['dvgrid_c0'])) {
            $checks = $_POST["dvgrid_c0"];
            //print_r($checks); die;
            $model = new CardRequest;
            $model->exportCSV($checks);
        } else {
            $model = new UserCard('search');
            $model->unsetAttributes();  // clear any default values
            $this->render('users/cardsview', array('model'=>$model));
        }
    }

    public function actionExportMedCards()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        //my-grid_c0[]
        if(!empty($_POST['dvgrid_c0'])) {
            $checks = $_POST["dvgrid_c0"];
            //print_r($checks); die;
            $model = new MedRequest;
            $model->exportCSV($checks);
        } else {
            $model = new MedCard('search');
            $model->unsetAttributes();  // clear any default values
            $this->render('users/cardsview', array('model'=>$model));
        }
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

    public function actionCardStatus($id)
    {
        $model = new CardRequest;
        $curr_status = intval($_POST['curr_status']);
        $model->CardStatus($id, $curr_status);
        // $model->unsetAttributes();  /


        $model1 = new UserCard('search');
        $model1->unsetAttributes();
         $this->render('users/cardsview', array('model'=>$model1));
    }

    public function actionMedCardStatus($id)
    {
        $model = new MedRequest;
        $curr_status = intval($_POST['curr_status']);
        $model->CardStatus($id, $curr_status);
        // $model->unsetAttributes();  /


        $model1 = new MedCard('search');
        $model1->unsetAttributes();
         $this->render('users/cardsview', array('model'=>$model1));
    }

    public function actionMedCardResetStatus($id)
    {
        $model = new MedRequest;
        $model->resetCardStatus($id);


        // --- вывод формы
        $model2 = new MedCard('search');
        //$model->unsetAttributes();  // clear any default values
        $this->render('users/cardsview', array('model'=>$model2));
    }

    public function actionCardResetStatus($id)
    {
        $model = new CardRequest;
        $model->resetCardStatus($id);


        // --- вывод формы
        $model2 = new UserCard('search');
        //$model->unsetAttributes();  // clear any default values
        $this->render('users/cardsview', array('model'=>$model2));
    }

  
    // Админка промоутер
    public function actionPromoEdit($id)
    {
        if(strpos($this->user_access, "Соискатели") === true) {
            $this->render('access');
            return;
        } 
        
        if(self::isAuth()) {
            $model = new User;
            if(!empty($_POST['User'])) {
                $model->updatePromo($_POST['User'], $id);
                $model = new Promo;
                $model->unsetAttributes();  // clear any default values
                $model->searchpr();
        
                $this->redirect(array('site/users'));
            }

            // --- вывод формы
            $data = $model->getUser($id);
            $title = 'Профиль соискателя';
            $this->setPageTitle($title);
            $this->breadcrumbs = array(
                'Соискатели' => array('sect?p=app'), 
                'Зарегистрированные'=>array('users'),
                '1'=>$title
            );
            $this->render('users/promoform', array('id'=>$id, 'data'=>$data));
        }
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
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        if(self::isAuth()) {
            $model = new Vacancy;
            if(!empty($_POST['Vacancy'])) {
                if($_POST['Vacancy']['ageto'] == 0) {
                    $_POST['Vacancy']['ageto'] == $_POST['Vacancy']['agefrom'];
                }
                $model->updateVacancy($_POST['Vacancy'], $id);

                $model->unsetAttributes();  // clear any default values
                $model->searchvac();
                $model->status=1;
                $this->render('vacancy/view', array('model'=>$model));

            }
            else{
            // --- вывод формы
                $data = $model->getVacancyData($id);
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
            $this->render('vacancy/vacancyform', array('id'=>$id, 'data'=>$data));
        }
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


    public function actionDeleteCard()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        
        if(!empty($_POST["dvgrid_c0"])) {
            $checks = $_POST["dvgrid_c0"];
            $model = new CardRequest;
            $model->deleteCard($checks);
            $this->redirect(array('site/cards'));
        } else {
            $model = new UserCard('search');
            $model->unsetAttributes();  // clear any default values
            $this->render('users/cardsview', array('model'=>$model));
        }
        
        
    }

    public function actionDeleteMedCard()
    {
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        
        if(!empty($_POST["dvgrid_c0"])) {
            $checks = $_POST["dvgrid_c0"];
            $model = new MedRequest;
            $model->deleteCard($checks);
            $this->redirect(array('site/medcards'));
        } else {
            $model = new MedCard('search');
            $model->unsetAttributes();  // clear any default values
            $this->render('users/medcardsview', array('model'=>$model));
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
        // if($this->user_access != 1) {
        //  $this->render('access');
        //  return;
        // }
        
            $model = new Vacancy;
            $model->deleteVacancy($id);
            // --- вывод формы
            $data = $model->searchvac();
            $model->status=1;
            $this->render('vacancy/view', array('model'=>$model));
        
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

   public function actionFeedbackDelete($id)
   {
       // if($this->user_access != 1) {
          //  $this->render('access');
          //  return;
       // }
       if(self::isAuth()) {
           $model = new Feedback;
           $curr_status = intval($_POST['curr_status']);
           $model->deleteFeedback($id);
          
          
           $this->redirect(array('site/feedback'));
       }
   }

   public function actionFeedbackModer($id)
   {
       
       if(self::isAuth()) {
           $model = new Feedback;
           $curr_status = intval($_POST['curr_status']);
           $model->ChangeModer($id, $curr_status);
           // $model->unsetAttributes();  // clear any default values
           // $model->search();
          
           $this->redirect(array('site/feedback'));
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
            $curr_status = $_POST['curr_status'];
            $model->VacancyModer($id, $curr_status);
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
            if(strpos($this->user_access, "Соискатели") === false) {
            $this->render('access');
            return;
        } 
            if(self::isAuth()) {

            $model = new Promo;
            $model->unsetAttributes();  // clear any default values
            $model->searchpr();
            if(isset($_GET['Promo'])){
                $model->attributes=$_GET['Promo'];
            }
            
            if($_GET['seo']){
                $title = 'Соискатели';
                $this->setPageTitle($title);
                $this->breadcrumbs = array('СЕО' => array('sect?p=seo'), '1'=>$title);
                $this->render('users/promo', array('model'=>$model));
            }
            else{
                $title = 'Зарегистрированные';
                $this->breadcrumbs = array('Соискатели' => array('sect?p=app'), '1'=>$title);
                $this->setPageTitle($title);
                $this->render('users/view', array('model'=>$model));
            }

        }

        //$this->render('users/view');
    }

    public function actionAnalytic()
    {

        if(strpos($this->user_access, "Аналитика") === false) {
            $this->render('access');
            return;
        } 
        if(self::isAuth()) {
            $api = new Api();
            $api->ideas();
            $model = new Analytic;
            $model->unsetAttributes();  // clear any default values
            $model->search();
            $model->active=1;
            $model->name != 'NO ACTIVE';
            $title = 'Общая аналитика';
            $brdcrmbs = 'Общая';
            if($_GET['subdomen']=='0') {
                $title = 'Аналитика Prommu';
                $brdcrmbs = 'Prommu';
                $model->subdomen=$_GET['subdomen'];
            }
            if($_GET['subdomen']=='1') {
                $title = 'Аналитика SPB';
                $brdcrmbs = 'SPB';
                $model->subdomen=$_GET['subdomen'];
            }
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Аналитика'=>array('sect?p=analytic'),'1'=>$brdcrmbs);          
            $this->render('analytic/index', array('model'=>$model));
        }
    }

    public function actionServicess()
    {

        if(strpos($this->user_access, "Услуги") === false) {
            $this->render('access');
            return;
        } 
            if(self::isAuth()) {
            $model = new ServiceOut();
            $model->unsetAttributes();  // clear any default values
            $model->search();
            $title = 'Услуги';
            if(isset($_GET['type'])) {
                switch ($_GET['type']) {
                    case 'outstaffing': $title = 'Заказ аутстаффинга'; break;
                    case 'outsourcing': $title = 'Заказ аутсорсинга'; break;
                    case 'api': $title = 'Заказ API'; break;

                }
                $model->type=$_GET['type'];
            }
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Услуги'=>array('sect?p=service'),'1'=>$title);
            $this->render('services/serv', array('model'=>$model));
        }
    }

    public function actionServices()
    {

       if(strpos($this->user_access, "Услуги") === false) {
            $this->render('access');
            return;
        } 
            if(self::isAuth()) {
            $model = new Service();
            $model->unsetAttributes();  // clear any default values
            $model->search();
            $title = 'Услуги';
            if(isset($_GET['type'])) {
                switch ($_GET['type']) {
                    case 'vacancy': $title = 'Премиум заявки'; break;
                    case 'sms': $title = 'СМС приглашения'; break;
                    case 'push': $title = 'PUSH приглашения'; break;
                    case 'email': $title = 'EMAIL приглашения'; break;
                    case 'repost': $title = 'Публикации в соцсетях'; break;
                }
                $model->type=$_GET['type'];
            }
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Услуги'=>array('sect?p=service'),'1'=>$title);
            $this->render('services/index', array('model'=>$model));
        }
    }

    public function actionFeedback()
    {

        if(strpos($this->user_access, "Обратная связь") === false) {
            $this->render('access');
            return;
        } 
            if(self::isAuth()) {
            $model = new FeedbackTreatment;
            $model->unsetAttributes();  // clear any default values
            $model->search();
            // $model->is_smotr=0;
            // if(isset($_GET['FeedbackTre'])){
            //  $model->attributes=$_GET['Feedback'];
            // }
            $title = 'Обратная связь';
            $this->setPageTitle($title);
            $this->breadcrumbs = array($title);
            $this->render('feedback/index', array('model'=>$model));
        }

    }

    public function actionMail($id)
    {
        
        if(!empty($_POST['Feedback']) ) {
            $model = new Feedback();
            $model->setFeedback($_POST['Feedback']);
            $mailer = new MailCloud();
            $mailer->mailerMail($_POST['Feedback']);
            $this->redirect(array('site/feedback'));
        }
            $model = new Feedback();
            $data = $model->getDatas($id);
            $title = 'Ответ на обращение';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('Обратная связь'=>array('feedback'),'1'=>$title);
            $this->render('feedback/mail', array('id'=>$id, 'data'=>$data));
    }

    public function actionUpdate($id)
    {
        
        if(!empty($_POST['Update']) ) {
            $model = new ImEmpl;
            $_POST['Update']['iduse'] = 2054;
            $model->insertChatMess($_POST['Update'], $id);

            $mailer = new MailCloud();
            $mailer->mailerMail($_POST['Update']);

            $model = new FeedbackTreatment;
            $model->unsetAttributes();  // clear any default values
            $model->search();
            $this->redirect(array("site/update/$id"));
            //$this->render('feedback/index', array('model'=>$model));
            }
            $sql = "SELECT ca.id, ca.message, ca.is_resp isresp, ca.is_read isread, ca.id_usp idusp, ca.id_use iduse, ca.files
                  , e.name nameto
                  , CONCAT(r.firstname, ' ', r.lastname) namefrom 
                  , DATE_FORMAT(ca.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(ca.crdate, '%H:%i:%s') crtime
                FROM chat ca
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$id}";
        /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $data = $res->queryAll();

            


            $this->render('feedback/update', array('id'=>$id, 'data'=>$data,));
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


        /*//my-grid_c0[]
        if(!empty($_POST['my-grid_c0'])) {
            $checks = $_POST["my-grid_c0"];
            //print_r($checks); die;
            $model = new CardRequest;
            $model->exportCSV($checks);
        } else {
            $model = new User('search');
            $model->unsetAttributes();  // clear any default values
            $this->render('users/cardsview', array('model'=>$model));
        }
        */
    }

    public function actionExportVacancy()
    {
        $model = new Vacancy;
        $res = $model->exportVacancyCSV();
        //print_r($res);
    }

    public function actionExportEmpl()
    {
        $model = new Employer;
        $res = $model->exportEmplCSV();
        //print_r($res);
    }
    public function actionSeo()
    {
        if(strpos($this->user_access, "SEO") === false) {
            $this->render('access');
            return;
        } 
        $model = new Seo('search');
        $model->unsetAttributes();
        if($_GET['seo']){
            $title = 'Страницы сайта';
            $this->setPageTitle($title);
            $this->breadcrumbs = array('СЕО'=>array('sect?p=seo'),'1'=>$title);
            $this->render('seo/seos', array('model' => $model));
        }
        else{
            $this->setPageTitle('Редактирование СЕО');
            $this->breadcrumbs = array('СЕО'=>array('sect?p=seo'),'1'=>'Фильтр');
            $this->render('seo/list', array('model' => $model));
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
        $model = new Seo;
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
        $model = new Seo;
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
        if(self::isAuth()) {
            $this->render('section', array()); 
        }
    }
}