<?
$this->setBreadcrumbsEx(
    array('Мои проекты', MainConfig::$PAGE_PROJECT_LIST),
    array($viData['project']['name'], "/")
);
$this->setPageTitle($viData['project']['name']);

$bUrl = Yii::app()->baseUrl . '/theme/';
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/additional.js', CClientScript::POS_END);


Yii::app()->getClientScript()->registerCssFile($bUrl . '/css/projects/project-app.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/js/projects/project-app.js', CClientScript::POS_END);


/***********FANCYBOX************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . 'js/dist/fancybox/jquery.fancybox.css');
/***********FANCYBOX************/
/***********MAP************/
Yii::app()->getClientScript()->registerScriptFile('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyC9M8BgorAu7Sn226LNP2rteTF5gO7KjLc');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/route-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/universal-map.css');
/***********MAP************/
?>


<?/*<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData); ?>
</pre>*/?>

<div class="filter__veil"></div>

<div class="row project">
    <div class="col-xs-12">
        <? require 'nav.php'; ?>
    </div>
</div>

<div class="project__module" data-id="<?= $project ?>">
    <?php if (sizeof($viData['items']) > 0): ?>
    <div class="tasks__list">
        <?/* require __DIR__ . '/filter.php'; // ФИЛЬТР */?>
        <div class="tasks" id="ajax-content">
            <? require __DIR__ . '/route-ajax.php'; // СПИСОК ?>
        </div>
    </div>
    <?php else: ?>
        <br><br><h2 class="center">Не найдено локаций</h2>
    <?php endif; ?>
</div>