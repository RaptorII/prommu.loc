<?php
/**
 * Admin page [MODULE]
 */
/*
  ContentPlus::clearContent();
  ContentPlus::setContent('A','<h3>Block A</h3>');
  //ContentPlus::setContent('B','<h3>Block B</h3>');
  ContentPlus::setTemplate('<table border="1"><tr><td>[A]</td><td>[B]</td></tr></table>');
  $block = 'B';
  eval('$md = new ModTxt; $md->Init("'.$block.'");');
  //$md->Init($block);
  ContentPlus::showPage();

  $action = ContentPlus::getActionID();
*/
  $op = '';
  $action = ContentPlus::getActionID();
  //echo '<h3>action='.$action.'</h3>';
  ContentPlus::readModule();

  if(ContentPlus::isModule()) {
    if(isset($_POST['op'])) $op = $_POST['op'];
    if($op=='SAVE') {
      eval('$md = new '.ContentPlus::getModuleName().'; $md->Save();');
    }
    eval('$md = new '.ContentPlus::getModuleName().'; $md->Setup();');
    return;
  }

  echo '<div class="alert alert-error">Функционал не поддерживается!</div>';

?>