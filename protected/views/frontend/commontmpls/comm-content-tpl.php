<?php
/**
 * Date: 15.02.2016
 * Time: 14:34
 */
//print  "<pre> \'here' :"
//      . print_r('here', 1)."\n"
//      . print_r(Share::$userType, 1)."\n"
//      ."</pre>";exit;
$userType = Share::$UserProfile->type;
if( $userType == 2 )
{
     include_once __DIR__.DS.MainConfig::$VIEWS_COMM_PRIVATE_CABINET_APPLIC_TPL.'.php';
}
elseif( $userType == 3 )
{
     include_once __DIR__.DS.MainConfig::$VIEWS_COMM_PRIVATE_CABINET_TPL.'.php';
}
else
{
     include_once __DIR__.DS.MainConfig::$VIEWS_COMM_PAGES_TPL.'.php';
} // endif