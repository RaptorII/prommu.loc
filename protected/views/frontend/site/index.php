<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

// ----- Auth social network ----
if($auth_soc==1)
{
  echo '<script>
  $(document).ready(function() {
  AccessEmail("'.$email.'");
  });
  </script>
  ';
}

//print  "<pre> \$action1 :"
//      . print_r($action, 1)."\n"
//      ."</pre>"; exit;
if($action=='' || $action=='#' || $action=='index' )
{
  include_once( DOCROOT . "/protected/views/frontend/site/page-index.tpl.php");

//  include_once( DOCROOT . "/protected/views/frontend/site/top_vacancy.php");
//  include_once( DOCROOT . "/protected/views/frontend/site/promo_anket_list.php");
  //Share::setup('LANG_DEFAULT');
}
else
{
  echo $content['content']['html'];
//	echo '<div id="dyn_page">';
//	echo '</div>';
}
?>