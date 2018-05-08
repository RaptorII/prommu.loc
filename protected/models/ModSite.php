<?php
class ModSite {

  /**
   * без параметров, все данные подготовлены в ContentPlus
   */
  function Init() {
    // получить Template
    $block = array('A','B','C','D','E','F','G');
    if(ContentPlus::getTemplate()!='') {
      //$tpl = ContentPlus::getTemplate();
      //$maxBlocks = ContentPlus::getMaxBlock();
      // прочитать контент
      $rows = Yii::app()->db->createCommand('SELECT p.id_content, c.id, c.title, c.html FROM page_content p RIGHT JOIN content c ON c.id = p.id_content WHERE id_page = '.ContentPlus::getPageId().' ORDER BY c.id')
    	->queryAll();

      // парсинг шаблона
      $i=0;
      foreach($rows as $row) {
        ContentPlus::setContent($block[$i], $row['html']);
        $i++;
      }

    }
  }


  /**
   * Это часть для админ панели
   * смена настроек модуля
   */
  function Setup() {
    echo '<h3>Настройки модуля :: '.ContentPlus::getModuleTitle().'</h3>';
    //echo $_SERVER['DOCUMENT_ROOT']."/content";
    $this->GenerateFormSetup();
  }


  /** Форма настроек модуля
   * (индвидуальна для каждого модуля)
   */
  function GenerateFormSetup() {
    $setup = ContentPlus::getSetup();
    if($setup=='') {
      $setup = array('logo_css'=>'', 'js_footer'=>'', 'logo_file'=>'',
              'phone1'=>'', 'phone2'=>'', 'icq'=>'', 'skype'=>'',
              'email'=>'', 'address'=>'');
    } else {
      if(!isset($setup["logo_css"]))  $setup["logo_css"] = '';
      if(!isset($setup["js_footer"])) $setup["js_footer"] = '';
      if(!isset($setup["logo_file"])) $setup["logo_file"] = '';
      if(!isset($setup["phone1"]))    $setup["phone1"] = '';
      if(!isset($setup["phone2"]))    $setup["phone2"] = '';
      if(!isset($setup["icq"]))       $setup["icq"] = '';
      if(!isset($setup["skype"]))     $setup["skype"] = '';
      if(!isset($setup["email"]))     $setup["email"] = '';
      if(!isset($setup["address"]))   $setup["address"] = '';
    }

    Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);

    echo '<form class="form-horizontal" method="post" enctype="multipart/form-data">';
    echo FormHelper::TextArea('logo_css', $setup["logo_css"], 'Логотип css', 'CSS', 'span7', '');
    echo FormHelper::TextArea('js_footer',$setup["js_footer"],'JS счетчики', 'Java Script', 'span7', '');
    echo FormHelper::ImageUpload('logo_filename', $setup["logo_file"], 'Изображение логотипа',1);
    echo FormHelper::TextField('phone1', $setup["phone1"], 'Телефон 1', '095 1000001', '', '', 'icon-bell');
    echo FormHelper::TextField('phone2', $setup["phone2"], 'Телефон 2', '095 1000002', '', '', 'icon-bell');
    echo FormHelper::TextField('icq', $setup["icq"], 'Icq', 'Icq', '', '', 'icon-globe');
    echo FormHelper::TextField('skype', $setup["skype"], 'Skype', 'Skype', '', '', 'icon-globe');
    echo FormHelper::TextField('email', $setup["email"], 'Email', 'Email', '', '', 'icon-envelope');
    echo FormHelper::TextField('address', $setup["address"], 'Адрес', 'Адрес', '', '', 'icon-tags');

    echo FormHelper::Hidden('op', 'SAVE');
    echo FormHelper::Hidden('id', '');
    echo FormHelper::Hidden('photo_name', $setup["logo_file"]);
    echo FormHelper::Hidden('op', 'SAVE');

    echo FormHelper::SubmitPanel();
    echo '</form>';

    echo FormHelper::ImageUploadScript();
  }


  /** Сохраняем форму настроек модуля
   * (индвидуальна для каждого модуля)
   */
  function Save() {
     $setup = array();
     $setup['logo_css'] = $_POST['logo_css'];
     $setup['js_footer'] = $_POST['js_footer'];
     $setup['logo_file'] = $_POST['photo_name'];
     $setup['phone1'] = $_POST['phone1'];
     $setup['phone2'] = $_POST['phone2'];
     $setup['icq'] = $_POST['icq'];
     $setup['skype'] = $_POST['skype'];
     $setup['email'] = $_POST['email'];
     $setup['address'] = $_POST['address'];

     if(ContentPlus::UpdateSetup($setup)) {
        echo '<div class="alert alert-success">Данные успешно сохранены</div>';
     }
  }


}
