<?
$bUrl = Yii::app()->baseUrl . '/theme/';
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/user-card.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/user-card.js', CClientScript::POS_END);

Yii::app()->getClientScript()->registerCssFile('/theme/css/private/page-prof-app.css');
Yii::app()->getClientScript()->registerScriptFile("/theme/js/private/page-prof-app.js", CClientScript::POS_END);


$user_id = Yii::app()->getRequest()->getParam('user_id');

/*
// избавляемся от доп запроса выбирая только нужных юзеров по пагинации
$main = Yii::app()->db->createCommand()
    ->select("isman,birthday,firstname,lastname,photo,email")
    ->from('user u')
    ->leftjoin('resume r', 'r.id_user=u.id_user')
    //->leftjoin('user_attribs ua', 'ua.id_us=u.id_user')
    ->where('u.id_user =:user_id', array(':user_id' => $user_id))
    //->order('pu.user desc')
    ->queryAll();

$contacts = Yii::app()->db->createCommand()
    ->select("uad.name, ua.val, ua.key")
    ->from('user_attribs ua')
    ->leftjoin('user_attr_dict uad', 'ua.key=uad.key')
    ->where("ua.id_us =:user_id AND ua.val<>''", array(':user_id' => $user_id))
    ->queryAll();

$project_info = Yii::app()->db->createCommand()
    ->select(
    //"DISTINCT(c.name)"
        "c.name city, p.project, p.name"
    )
    ->from('project_user pu')
    ->leftjoin('project p', 'p.project=pu.project')
    ->leftjoin('project_city pc', 'pc.project=pu.project')
    ->leftjoin('city c', 'pc.id_city=c.id_city')
    ->where('pu.user =:user_id', array(':user_id' => $user_id))
    ->order('pu.user desc')
    ->queryAll();

$viData = [];
foreach ($main as $key => $value) {
    $viData = $value;
}

foreach ($contacts as $key => $value){
    if($value['key']=='mob'){
        $viData['tel'] = $value['val'];
    }
    else{
        $viData['CONTACTS'][]=$value;
    }
}

$arProjects = [];
$arCities = [];
foreach ($project_info as $key => $value) {
    if (!in_array($value['name'], $arProjects)) {
        $arProjects[] = $value['name'];
    }
    if (!in_array($value['city'], $arCities)) {
        $arCities[] = $value['city'];
    }
}

$viData['PROJECT'] = $arProjects;
$viData['CITIES'] = $arCities;

$model= new Project();
$viData['PHOTO'] = $model -> getPhoto('2', $viData ,'medium');
*/
?>
<? /* ?>

<? */ ?>

<div class="container">
    <h1 class="user-profile-page__title">Профиль персонала - <?= $viData['firstname'] ?> <?= $viData['lastname'] ?></h1>
</div>

<div class="content-block">
    <div class="private-profile-page for-guest">
        <div class="ppp__logo">
            <div class="ppp__logo-main">
                <div
                   class="js-g-hashint ppp-logo-main__link ppp__logo-full tooltipstered">
                    <img src="<?=$viData['PHOTO']?>"
                         alt="<?= $viData['firstname'] ?> <?= $viData['lastname'] ?>"
                         class="ppp-logo-main__img">
                </div>

            </div>


            <div class="ppp__logo-more">
                <div class="clearfix"></div>
            </div>
            <div class="center-box">


                <div class="clearfix"></div>
            </div>
        </div>
        <div class="ppp__content">
            <h1 class="ppp__content-title"><?= $viData['firstname'] ?> <?= $viData['lastname'] ?></h1>
            <div class="ppp__module-title"><h2>ОСНОВНАЯ ИНФОРМАЦИЯ</h2></div>
            <div class="ppp__module">
                <div class="ppp__field">
                    <span class="ppp__field-name">Имя:</span>
                    <span class="ppp__field-val"><?= $viData['firstname'] ?></span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-name">Фамилия:</span>
                    <span class="ppp__field-val"><?= $viData['lastname'] ?></span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-name">Дата рождения:</span>
                    <span class="ppp__field-val"><?= DateTime::createFromFormat('Y-m-d', $viData['birthday'])->format('d/m/Y'); ?></span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-name">Пол:</span>
                    <span class="ppp__field-val">
                        <? if ($viData['isman'] == 0): ?>
                            женский
                        <? else: ?>
                            мужской
                        <? endif; ?>
                    </span>
                </div>
            </div>


            <div class="ppp__module-title"><h2>ПРОЕКТЫ</h2></div>
            <div class="ppp__module">
                <div class="ppp__period-list">
                     <? foreach ($viData['PROJECT'] as $key => $value): ?>
                        <div class="ppp__field">
                            <span class="ppp__field-val"><?= $value ?></span>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>

            <div class="ppp__module-title"><h2>ГОРОД</h2></div>
            <div class="ppp__module">
                <div class="ppp__period-list">
                    <? foreach ($viData['CITIES'] as $key => $value): ?>
                        <div class="ppp__field">
                            <span class="ppp__field-val"><?= $value ?></span>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>


            <div class="ppp__module-title"><h2>ДОЛЖНОСТЬ</h2></div>
            <div class="ppp__module">
                <div class="ppp__period-list">
                     <? foreach ($viData['MECH'] as $key => $value): ?>
                        <div class="ppp__field">
                            <span class="ppp__field-val"><?= $value ?></span>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>


            <div class="ppp__module-title"><h2>КОНТАКТЫ</h2></div>
            <div class="ppp__module">
                <div class="ppp__field">
                    <span class="ppp__field-name">Email:</span>
                    <span class="ppp__field-val"><?= $viData['email'] ?></span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-name">Телефон:</span>
                    <span class="ppp__field-val"><?= $viData['tel'] ?></span>
                </div>
                <div class="ppp__period-list">
                    <? foreach ($viData['CONTACTS'] as $key => $value): ?>
                        <? if ($value['VALUE']): ?>
                            <div class="ppp__field">
                                <div class="ppp__field-name ppp__field-fix"><?= $value['name'] ?></div>
                                <span class="ppp__field-val ppp__field-fix"><?= $value['val'] ?></span>
                            </div>
                        <? endif; ?>
                    <? endforeach; ?>
                </div>
            </div>

            <div class="ppp__module-title"><h2>обратная связь</h2></div>
            <div class="ppp__module">
                <form>
                    <textarea class="ppp__module-feedback" name="message"></textarea>
                    <div class="btn-auth btn-orange-wr">
                        <button class="hvr-sweep-to-right auth-form__btn" type="submit">Отправить</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

