<?
/**
 * Created by PhpStorm.
 * User: мвидео
 * Date: 17.09.2018
 * Time: 15:45
 */

$this->setBreadcrumbs($title, MainConfig::$PAGE_PROJECT_LIST);
$this->setPageTitle($title);
$bUrl = Yii::app()->baseUrl;
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/project-app.css');
?>
    <pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
        <? print_r($viData); ?>
    </pre>


    <div class="row projects">
        <div class="col-xs-12">



        </div>
    </div>