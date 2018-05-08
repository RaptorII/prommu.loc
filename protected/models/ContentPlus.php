<?php
class ContentPlus {
  static private $content = array();
  static private $template = '';
  static private $setup = '';
  static private $actionId;
  static private $pageId = 0;
  static private $ismodule = '0';
  static private $module = '';
  static private $module_title = '';
  static private $maxblock = 0;


  static function clearContent() {
    self::$content = array();
  }

  static function setContent($block, $content) {
    self::$content[$block] = $content;
  }

  static function getContent($block) {
    return self::$content;
  }

  /* --- Example ---
  <div class="row">
  <div class="well span4 offset4">
    <legend>[LEGEND]</legend>
    <div class="control-group">[A]<div>
  </div>
  </div>
  */
  static function setTemplate($code) {
    self::$template = $code;
  }

  static function getTemplate() {
    return self::$template;
  }


  static function showPage() {
    $buf = self::$template;
    while($value = current(self::$content)) {
      $key = key(self::$content);
      $buf = str_replace('['.$key.']', $value, $buf);
      //echo '<p>'.key(self::$content).'</p>';
      next(self::$content);
    }
    echo $buf;
  }

  /** --- Получить по url ЧПУ страницу ---
   *  Step 1
   */
  static function getActionID() {
    $url = explode('?', Yii::app()->request->requestUri)[0];
    $uri = explode('/',$url);
    self::$actionId = $uri[count($uri)-1];
    return self::$actionId;
  }

  static function getActionInfo($action) {
    self::$actionId = $action;
    self::readModule();
    return self::$setup;
  }

  /** --- Получить по url ЧПУ страницу ---
   * Step 2
   */
  static function getPageOfAction() {
    if(isset(self::$actionId)) {
      $row = Yii::app()->db->createCommand('SELECT p.id, p.ismodule, m.name, m.title, m.template FROM page p LEFT JOIN modules m ON m.id = id_module WHERE link = \''.self::$actionId.'\'')
    	->queryRow();
    }

    self::$ismodule = $row['ismodule'];
    //self::$maxblock = (int)$row['max_blocks'];

    if($row['ismodule']=='1') {
      self::$module = $row['name'];
      self::$module_title = $row['title'];
    }
    else
      self::$module = '';

    self::$template = base64_decode($row['template']);
    self::$pageId = (int)$row['id'];

    //echo 'ID='.$row['id'].' | module = '.$row['name'];
  }

  static function readModule() {
    if(isset(self::$actionId)) {
      $row = Yii::app()->db->createCommand('SELECT name, title, template, setup FROM modules WHERE name = \''.self::$actionId.'\'')->queryRow();
      self::$ismodule = '1';
      self::$module = $row['name'];
      self::$module_title = $row['title'];
      //self::$maxblock = $row['max_blocks'];
      if($row['template']!='') self::$template = base64_decode($row['template']);
      if($row['setup']!='') self::$setup = unserialize(base64_decode($row['setup']));
    }
  }


  static function getSetup() {
    return self::$setup;
  }

  static function UpdateSetup($setup) {
    if(isset(self::$actionId)) {
       self::$setup = $setup;
       $setup = base64_encode(serialize($setup));
		   Yii::app()->db->createCommand()->update('modules', array(
    		'setup'=>$setup
		   ), 'name=:name', array(':name'=>self::$actionId));
       return true;
    }
    return false;
  }

  static function getModuleName() {
    return self::$module;
  }

  static function isModule() {
    if(self::$ismodule=='1')
      return true;
    else
      return false;
  }

  static function getMaxBlock() {
    return self::$maxblock;
  }

  static function getPageId() {
    return self::$pageId;
  }

  static function getModuleTitle() {
    return self::$module_title;
  }
}
?>