<?php
class AjaxController extends CController {
    // actionIndex вызывается всегда, когда action не указан явно.
    function actionIndex(){
        $input = Yii::app()->request->getPost('input');
        //$input = '123';
        // для примера будем приводить строку к верхнему регистру
        $output = mb_strtoupper($input, 'utf-8');

        // если запрос асинхронный, то нам нужно отдать только данные
        if(Yii::app()->request->isAjaxRequest){
            echo CHtml::encode($output);
            // Завершаем приложение
            Yii::app()->end();
        }
        else {
            // если запрос не асинхронный, отдаём форму полностью
            echo '{"result":"OK"}';
            /*
            $this->render('//site/menuTree/_form', array(
                'input'=>$input,
                'output'=>$output,
            ));
            */
        }
    }


    function actionListmenu(){
   		$share = new Share;
//   		$lang = $share->getLangAjax();
      $lang = $_GET['lang'];
   		$menu_type = $share->getMenuTypeAjax();
   		$menu = new Menu;
		if (Yii::app()->user->isGuest)
			echo "Access denied!";
		else {
		$html = $menu->getMenuListHtml($lang, $menu_type);
		echo $html;
		}
		Yii::app()->end();
	}

	function actionDeleteMenu(){
   		$share = new Share;
   		//$lang = $share->getLangAjax();
   		$lang = $_GET['lang'];
   		$menu_type = $share->getMenuTypeAjax();
   		$id = $_GET['id'];
   		$menu = new Menu;
		$html = $menu->delMenu($id, $lang, $menu_type);
		echo $html;
		Yii::app()->end();
	}


	function actionChangePosMenu(){
		$id = $_GET['id'];
		$switch = $_GET['switch'];
		$share = new Share;
   		//$lang = $share->getLangAjax();
   		$lang = $_GET['lang'];
	    $menu_type = $share->getMenuTypeAjax();
   		$menu = new Menu;
		$html = $menu->getMenuListOfPos($id,$switch,$lang,$menu_type);
		echo $html;
		Yii::app()->end();
	}

	public function actionUpload()
	{
		$this->render('nicUpload');
	}

	public function actionSetLang()
	{
		$lang = $_GET['lang'];
		Yii::app()->session['lang'] = $lang;
		echo $lang;
		Yii::app()->end();
	}

	public function actionEditLanguage()
	{
    $id        = intval($_GET['id']);
    $lang      = $_GET['lang'];
    $page      = $_GET['page'];
    $keyword   = $_GET['keyword'];
    $value     = $_GET['value'];

    $model = new Languages;
    if($id==0)
      echo $model->Add($lang, $page, $keyword, $value);
    else
      echo $model->Edit($id, $lang, $page, $keyword, $value);

	}

	public function actionGetLanguage()
	{
      $id     = intval($_GET['id']);
      $model  = new Languages;
      $res    = $model->GetRow($id);
      echo CJSON::encode($res);
	}


	public function actionDelLanguage(){
    $id = intval($_GET['id']);
    $model = new Languages;
		$res = $model->DeleteRow($id);
		echo $res;
	}


// ************* Cities ************************
	public function actionAddCity(){
		$name = $_GET['name'];
		$model = new City;
		$model->AddName($name);
		echo("Ok");
	}

	public function actionEditCity(){
		$name = $_GET['name'];
		$id = $_GET['id'];
		$model = new City;
		$model->EditName($name, $id);
		echo("Ok");
	}


    public function actionEditRating(){
        $name = $_GET['name'];
        $id = $_GET['id'];
        $val = $_GET['val'];
        $model = new PointRating();
        $model->EditPoint($name, $id, $val);
        echo("Ok");
    }

    public function actionAddRating(){
        $name = $_GET['name'];
        $val = $_GET['val'];
        $grp = $_GET['grp'];
        $model = new PointRating();
        $model->AddPoint($name, $val, $grp);
        echo("Ok");
    }

    public function actionSetActiveCity($id){
    $id = $_GET['id'];
		$model = new City;
		$res = $model->SetActive($id);
		if(!isset($res)) $res=0;
		echo($res);
	}

  public function actionGetCity() {
    $filter = $_GET['name_startsWith'];
    $model = new City;
		$res = $model->GetList(10, $filter);
		echo CJSON::encode($res);

	}

// ************* University ************************
	public function actionAddUniversity(){
		$name = $_GET['name'];
		$model = new University;
		$model->AddName($name);
		echo("Ok");
	}

	public function actionEditUniversity(){
		$name = $_GET['name'];
		$id = $_GET['id'];
		$model = new University;
		$model->EditName($name, $id);
		echo("Ok");
	}

	public function actionSetActiveUniversity($id){
    $id = $_GET['id'];
		$model = new University;
		$res = $model->SetActive($id);
		if(!isset($res)) $res=0;
		echo($res);
	}

  public function actionGetUniversity() {
    $filter = $_GET['name_startsWith'];
    $model = new University;
		$res = $model->GetList(10, $filter);
		echo CJSON::encode($res);

	}


// ************* Banners ************************
	public function actionAddBanners(){
		$name = $_GET['name'];
    $link = $_GET['linkbanner'];
    $file_banner = $_GET['filebanner'];
		$model = new Banners;
		$model->AddName($name, $link, $file_banner);
		echo("Ok");
	}

	public function actionEditBanners(){
		$name = $_GET['name'];
    $link = $_GET['linkbanner'];
    $file_banner = $_GET['filebanner'];
		$id = $_GET['id'];
		$model = new Banners;
		$model->EditName($name, $link, $file_banner, $id);
		echo("Ok");
	}

	public function actionSetActiveBanners($id){
    $id = $_GET['id'];
		$model = new Banners;
		$res = $model->SetActive($id);
		if(!isset($res)) $res=0;
		echo($res);
	}

  public function actionGetBanners() {
    $filter = $_GET['name_startsWith'];
    $model = new Banners;
		$res = $model->GetList(10, $filter);
		echo CJSON::encode($res);

	}

  public function actionGetRa() {
    $filter = $_GET['name_startsWith'];
    $model = new Ra;
		$res = $model->GetList(10, $filter);
		echo CJSON::encode($res);
  }

  public function actionGetTopMenu() {
     $lang = $_GET['lang'];
     $utype = $_GET['utype'];
     $mtype = 1;  // главное меню

     $menu = new Menu;

     if($utype>0)
     {
        echo $menu->getTwoTree(0, $lang, $mtype, $utype, 1);
     } else {
        echo $menu->getTree(0, $lang, $mtype, 0);
     }
  }


  // ****************** LANG *******************
  public function actionAddLang()
  {
    $key    = $_GET['key'];
    $grp    = $_GET['grp'];
    $words  = $_GET['words'];

    $key    = trim(mb_strtoupper($key));
    $grp    = trim(mb_strtoupper($grp));

    $block = explode('|', $words);
    $cmd = Yii::app()->db->createCommand();
    for($i=0; $i<count($block)-1; $i++)
    {
      $p=explode('::', $block[$i]);
      $cmd->insert('lang_turbo', array(
        'grp'=>$grp,
        'lang'=>$p[0],
        'key'=>$key,
        'value'=>$p[1],
      ));
    }
    echo '{"message":"OK"}';
  }


  public function actionDeleteLang()
  {
    $key    = $_GET['key'];
    $p=explode('_', $key);
    Yii::app()->db->createCommand()->delete('lang_turbo', '`key`=:k and `grp`=:grp', array(':k'=>$p[1], ':grp'=>$p[0]));
    echo '{"message":"OK"}';
  }

  public function actionEditLang()
  {
    $key    = $_GET['key'];
    $grp    = $_GET['grp'];
    $lang   = $_GET['lang'];
    $word   = $_GET['word'];

    $key    = trim(mb_strtoupper($key));
    $grp    = trim(mb_strtoupper($grp));

    $cmd = Yii::app()->db->createCommand();
    $cmd->update('lang_turbo', array(
    'value'=>$word,
    ), '`key`=:k and `grp`=:grp and `lang`=:lang', array(':k'=>$key, ':grp'=>$grp, ':lang'=>$lang));
    echo '{"message":"OK"}';
  }


  public function actionGenerateLangCache()
  {
    $docroot = $_SERVER['DOCUMENT_ROOT'];
    $cmd2 = Yii::app()->db->createCommand();
    $cmd = Yii::app()->db->createCommand();
    $res2 = $cmd2->select("name, title")
        ->from('lang')
        ->order('name')
        ->queryAll();
    foreach($res2 as $rw_lang)
    {
      $res = $cmd->select("id, grp, lang, key, value")
        ->from('lang_turbo')
        ->where('lang = :lang', array(':lang'=>$rw_lang['name']))
        ->order('grp, key, lang')
        ->queryAll();

      // открываем файл, если файл не существует,
      //делается попытка создать его
      $fp = fopen($docroot."/langs/".$rw_lang['name'].".dat", "w");

      foreach($res as $rw)
      {
          // записываем в файл текст
          fwrite($fp, $rw['grp'].'_'.$rw['key']."\t".$rw['value']."\r\n");
      }

      // закрываем
      fclose($fp);

    }
    echo '{"message":"OK"}';
  }


//========= C A R D S ===============

    public function actionDeleteScan()
    {
        $key = isset($_GET['key']) ? $_GET['key'] : 0;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $model = new CardRequest();
        $data = $model->DeleteScan($key, $id);
        $html = $model->ConvertListToHtml($data);
        echo $html;
}


    public function actionAddScan()
    {
        $fname = isset($_GET['fname']) ? $_GET['fname'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $model = new CardRequest();
        $data = $model->AddScan($fname, $id);
        $html = $model->ConvertListToHtml($data);
        echo $html;
    }
    /*
    *   Изменение комментария идеи
    */
    public function actionChangeIdeaComment()
    {
      $id = Yii::app()->getRequest()->getParam('id');
      $event = Yii::app()->getRequest()->getParam('event');
      $model = new Ideas;
      if($event=='del') {
        echo $model->deleteComment($id);
      }
      if($event=='hidden') {
        echo $model->changeVisComment($id);
      }
    }
    /*
    *   Изменения СЕО параметра страницы 
    */
    public function actionChangePageSeoParam()
    {
      $id = Yii::app()->getRequest()->getParam('id');
      $param = Yii::app()->getRequest()->getParam('param');
      $value = Yii::app()->getRequest()->getParam('value');
      $model = new Seo;
      echo $model->changeSeoParams($id, $param, $value);
    }
    /*
    *   ОТправление письма работодателю от менеджера
    */
    public function actionSendPrivateManagerMail()
    {
      $id = Yii::app()->getRequest()->getParam('id');
      $param = Yii::app()->getRequest()->getParam('param');
      if($param=='accountmail'){
        $model = new User;
        $data = $model->getUserEmpl($id);
        $data['send-private-manager-mail'] = true;
        $result = $model->updateEmployer($data, $id);
        echo CJSON::encode($result);
      }
      else
        echo CJSON::encode(array('error'=>1,'sendmail'=>0));
    }
  /**
   * 
   */
  public function actionFeedback()
  {
    $result = array('error'=>true);
    $type = Yii::app()->getRequest()->getParam('type');
    $model = new FeedbackTemplate();

    switch ($type) {
      case 'add':
        $result = $model->addTemplate();
        break;
      case 'del':
        $result = $model->delTemplate();
        break;
    }

    echo CJSON::encode($result);
  }
}
