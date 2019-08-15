<?php
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile('/admin/css/app-profile.css');
$gcs->registerCoreScript('jquery');
$gcs->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
// Magnific Popup
$gcs->registerCssFile('/jslib/magnific-popup/magnific-popup-min.css');
$gcs->registerScriptFile('/jslib/magnific-popup/jquery.magnific-popup.min.js', CClientScript::POS_END);
$bUrl = Yii::app()->request->baseUrl;
Yii::app()->getClientScript()->registerCssFile($bUrl . '/css/template.css');

$fdbck = new Feedback();
$fdbck = $fdbck->getUserFeedbacks($data['id_user']);//(15642);//

echo '<div class="row">';
echo '<div class="col-xs-12 col-sm-9 col-sm-offset-3 col-md-6 col-md-offset-2">'
    . '<h2>Редактирование соискателя #' . $data['id_user'] . '</h2>'
    . '</div>';
echo '<div class="col-xs-12"><div class="row">';
    echo '<div class="col-xs-12 col-sm-3 col-md-2">'
            . '<ul class="nav user__menu" role="tablist" id="tablist">'
                . '<li class="active"><a href="#tab_profile" aria-controls="tab_profile" role="tab" data-toggle="tab">Общее</a></li>'
                . '<li><a href="#tab_seo" aria-controls="tab_seo" role="tab" data-toggle="tab">СЕО</a></li>'
                . '<li><a href="#tab_photo" aria-controls="tab_photo" role="tab" data-toggle="tab">Фото</a></li>'
                . '<li><a href="#tab_vacs" aria-controls="tab_vacs" role="tab" data-toggle="tab">Отработанные вакансии</a></li>'
                . '<li><a href="#tab_rating" aria-controls="tab_rating" role="tab" data-toggle="tab">Рейтинг</a></li>'
                . '<li><a href="#tab_message" aria-controls="tab_rating" role="tab" data-toggle="tab">Сообщения</a></li>'
            . '</ul>'
        . '</div>';
    echo '<div class="col-xs-12 col-sm-9 col-md-6">';
        echo CHtml::form($id,'post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
            echo '<div class="tab-content">';
/* 
*       TAB PROFILE 
*/
echo '<div role="tabpanel" class="tab-pane fade active in" id="tab_profile">';
    echo '<h3>Общее</h3>';
    echo '<div class="row"><div class="col-xs-12 col-sm-6 user__logo">'
            . CHtml::image($data['src'])
        . '</div><div class="col-xs-12 col-sm-6 user__moder">'
            . '<h4>МОДЕРАЦИЯ</h4>'
            . '<div class="control-group">'                
                . CHtml::CheckBox(
                    'User[ismoder]',
                    $data['ismoder'],
                    array('value'=>'1')
                )
                . '<label class="control-label" for="User_ismoder">Промодерировано</label>'
            . '</div>';

            echo '<div class="control-group">'
                . '<label class="control-label">Видимость</label><div class="controls input-append">'
                    . CHtml::radioButtonList(
                        'User[isblocked]',
                        $data['isblocked'],
                        array(
                            0 => 'полностью активен',
                            1 => 'заблокирован',
                            2 => 'ожидает активации',
                            3 => 'активирован, но не заполнил все необходимые поля',
                            4 => 'приостановка показа',
                        ),
                        array()
                    )
                . '</div></div>';
    echo '</div></div>';


    echo '<br/><h4>ОСНОВНАЯ ИНФОРМАЦИЯ</h4>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Имя</label>'
                . CHtml::textField(
                    'User[firstname]', 
                    $data['firstname'], 
                    array('class'=>'form-control')
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Фамилия</label>'
                . CHtml::textField(
                    'User[lastname]', 
                    $data['lastname'], 
                    array('class'=>'form-control')
                )
            . '</div>';
    echo '<div class="d-flex small-bl">';
        echo '<div class="control-group">'
            . '<label class="control-label">Дата рождения (дд.мм.гггг)</label>'
            . $this->widget(
                'zii.widgets.jui.CJuiDatePicker', 
                array(
                    'name'=>'User[birthday]', 
                    'value'=>$data['birthday'], 
                    'language'=>'ru', 
                    'id'=>'birthday',
                    'options'=>array(
                        'dateFormat'=>'dd.mm.yy', 
                        'minDate'=>'12.12.1942'
                    )
                ), 
                true
            )
            . '</div>' 
            . '<div class="control-group"><b class="user_years">' . $data['years'] . '</b></div>';
    echo '</div>';
    echo '<div class="control-group">'
        . '<label class="control-label">Пол</label><div class="controls input-append">'
        . CHtml::radioButtonList(
                'User[isman]',
                $data['isman'],
                array('1'=>'Парень','0'=>'Девушка'),
                array()
            )
        . '</div></div>';
    
    echo '<div class="row"><div class="col-xs-12 col-sm-4">';
        echo '<div class="control-group">'
            . '<span class="glyphicon glyphicon-' . ($data['ismed']?'check':'unchecked') . '"></span>'
            . ' <label class="control-label">Медкнижка</label>'
            . '</div>'
            . '<div class="control-group">'
            . '<span class="glyphicon glyphicon-' . ($data['ishasavto']?'check':'unchecked') . '"></span>'
            . ' <label class="control-label">Автомобиль</label>'
            . '</div>'
            . '<div class="control-group">'
            . '<span class="glyphicon glyphicon-' . ($data['smart']?'check':'unchecked') . '"></span>'
            . ' <label class="control-label">Смартфон</label>'
            . '</div>';
    echo '</div><div class="col-xs-12 col-sm-8">';
        echo '<div class="control-group">'
            . '<span class="glyphicon glyphicon-' . ($data['cardPrommu']?'check':'unchecked') . '"></span>'
            . ' <label class="control-label">Карта Prommu</label>'
            . '</div>'
            . '<div class="control-group">'
            . '<span class="glyphicon glyphicon-' . ($data['card']?'check':'unchecked') . '"></span>'
            . ' <label class="control-label">Обычная карта</label>'
            . '</div>';
    echo '</div></div><br/>';

    if(!empty($data['attr']['self_employed']))
    {
        echo '<h4>НАЛОГОВЫЙ СТАТУС</h4>';
        echo '<div class="row"><div class="col-xs-12">';
            echo '<div class="control-group">'
                . '<span class="glyphicon glyphicon-check"></span>'
                . ' <label class="control-label">Самозанятый</label>'
                . '</div>';
        echo '</div></div><br/>';
    }


    echo '<h4>КОНТАКТНАЯ ИНФОРМАЦИЯ</h4>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Телефон</label>'
                . CHtml::textField(
                    'User[mob]',
                    $data['attr']['mob'], 
                    array(
                        'class'=>'form-control',
                        //'disabled'=>true
                        'readonly'=>'false',
                    )
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Email</label>'
                . CHtml::textField(
                    'User[email]', 
                    $data['email'], 
                    array('class'=>'form-control')
                )
            . '</div>';
    echo '<div class="row"><div class="col-xs-12 col-sm-3">';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Skype</label>'
                . CHtml::textField(
                    'attr[skype]', 
                    $data['attr']['skype'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Однокласники</label>'
                . CHtml::textField(
                    'attr[ok]', 
                    $data['attr']['ok'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Viber</label>'
                . CHtml::textField(
                    'attr[viber]', 
                    $data['attr']['viber'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Googleallo</label>'
                . CHtml::textField(
                    'attr[googleallo]', 
                    $data['attr']['googleallo'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '</div><div class="col-xs-12 col-sm-3">';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">VK</label>'
                . CHtml::textField(
                    'attr[vk]', 
                    $data['attr']['vk'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Mail</label>'
                . CHtml::textField(
                    'attr[mail]', 
                    $data['attr']['mail'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Whatsapp</label>'
                . CHtml::textField(
                    'attr[whatsapp]', 
                    $data['attr']['whatsapp'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Другое</label>'
                . CHtml::textField(
                    'attr[custcont]', 
                    $data['attr']['custcont'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '</div><div class="col-xs-12 col-sm-3">';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Facebook</label>'
                . CHtml::textField(
                    'attr[fb]', 
                    $data['attr']['fb'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Google</label>'
                . CHtml::textField(
                    'attr[google]', 
                    $data['attr']['google'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Telegram</label>'
                . CHtml::textField(
                    'attr[telegram]', 
                    $data['attr']['telegram'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '</div></div><br/>';


    echo '<h4>ЦЕЛЕВАЯ ВАКАНСИЯ</h4>';
    echo '<div class="row">';
    if(sizeof($data['posts']) && $data['user_posts']){
        foreach ($data['posts'] as $post){
            echo '<div class="col-xs-12 col-sm-6"><div class="post_item">'
                . '<b>Должность: </b><span>' . $post['val'] . '</span><br/>'
                . '<b>Ожидаемая оплата, руб: </b><span>' . $post['pay'] . '</span><br/>'
                . '<span>' . $post['pt'] . '</span><br/>'
                . '<b>Опыт работы: </b><span>' . $post['pname'] . '</span><br/>'
                . '</div></div>';
        }
    }
    else{
        echo '<div class="col-xs-12">Пока нет вакансий</div><br>';
    }
    echo '</div>';


    echo '<h4>УДОБНОЕ МЕСТО И ВРЕМЯ РАБОТЫ</h4>';
    echo '<div class="row">';
    if(sizeof($data['user_cities'])){
        foreach ($data['user_cities'] as $city)
        {
            echo '<div class="col-xs-12"><div class="loc_item">'
                . '<b>Город: </b><span>' . $city['name'] . '</span><br/>';
            if($city['ismetro'])
            {
                if(sizeof($data['user_metros']))
                {
                    $arMetroes = array();
                    foreach($data['user_metros'] as $idMetro => $metro)
                        if($metro['idcity']==$city['id'])
                            $arMetroes[] = $metro['name'];
                    echo '<b>Метро: </b><span>' . implode(', ', $arMetroes) . '</span><br/>';   
                }         
            }
            echo '<b>Дни недели: </b><br/>';
            foreach($data['days'] as $idDay => $day)
            {
                echo '<b>' . $day . ': </b><span>';
                if(sizeof($data['worktime'][$city['id']][$idDay])){
                    $t = $data['worktime'][$city['id']][$idDay];
                    echo 'Время дня - ' . 'С ' 
                        . explode(':', $t['timeb'])[0] 
                        . ' до ' . explode(':', $t['timee'])[0];
                }
                echo '</span><br/>';
            }
            echo '</div></div>';
        }
    }
    else{
        echo '<div class="col-xs-12">Данных нет</div><br>';
    }
    echo '</div>';


    echo '<h4>ВНЕШНИЕ ДАННЫЕ</h4>';
    echo '<div class="row"><div class="col-xs-6 col-sm-3">';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Рост</label>'
                . CHtml::textField(
                    'attr[manh]', 
                    $data['attr']['manh'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Вес</label>'
                . CHtml::textField(
                    'attr[weig]', 
                    $data['attr']['weig'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '</div><div class="col-xs-6 col-sm-3">';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Цвет волос</label>'
                . CHtml::textField(
                    'attr[hcolor]', 
                    $data['attr']['hcolor'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Длина волос</label>'
                . CHtml::textField(
                    'attr[hlen]', 
                    $data['attr']['hlen'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '</div><div class="col-xs-6 col-sm-3">';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Цвет глаз</label>'
                . CHtml::textField(
                    'attr[ycolor]', 
                    $data['attr']['ycolor'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Размер груди</label>'
                . CHtml::textField(
                    'attr[chest]', 
                    $data['attr']['chest'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '</div><div class="col-xs-6 col-sm-3">';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Объем талии</label>'
                . CHtml::textField(
                    'attr[waist]', 
                    $data['attr']['waist'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Объем бедер</label>'
                . CHtml::textField(
                    'attr[thigh]', 
                    $data['attr']['thigh'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '</div></div><br/>';


    echo '<h4>ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ</h4>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">Образование</label>'
                . CHtml::textField(
                    'attr[edu]', 
                    $data['attr']['edu'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
        . '<label class="control-label">Иностранные языки</label>'
            . CHtml::textArea(
                'User[lang]', 
                is_array($data['attr']['lang']) ? implode(', ', $data['attr']['lang']) : '', 
                array('class'=>'form-control','disabled'=>true)
            )
        . '</div>';
    echo '<div class="control-group small-bl">'
        . '<label class="control-label">О себе</label>'
            . CHtml::textArea(
                'User[aboutme]', 
                $data['aboutme'], 
                array('class'=>'form-control')
            )
        . '</div>';
echo '</div>';
/*
*       SEO
*/
echo '<div role="tabpanel" class="tab-pane fade" id="tab_seo">';
    echo '<h3>СЕО</h3>';
    echo '<div class="control-group">'
        . CHtml::CheckBox(
            'User[index]', 
            $data['index'], 
            array('value'=>'1')
        )
        . '<label class="control-label">Запрет индексации</label>'
        . '</div>';

    echo '<div class="control-group medium-bl">'
        . '<label class="control-label">meta_title</label>';
        $text = html_entity_decode($data['meta_title']);
        $text = strip_tags($text);     
        /*echo CHtml::textArea(
            'User[meta_title]', 
            $text, 
            array(
                'rows' => 2, 
                'cols' => 40,
                'class'=>'form-control'
            )
        )*/
        echo $text . '</div>';

    echo '<div class="control-group medium-bl">'
        . '<label class="control-label">meta_h1</label>';
        $text = html_entity_decode($data['meta_h1']);
        $text = strip_tags($text);     
        /*echo CHtml::textArea(
            'User[meta_h1]', 
            $text, 
            array(
                'rows' => 2, 
                'cols' => 40,
                'class'=>'form-control'
            )
        )*/
        echo $text . '</div>';

    echo '<div class="control-group medium-bl">'
        . '<label class="control-label">meta_description</label>';
        $text = html_entity_decode($data['meta_description']);
        $text = strip_tags($text);     
        /*echo CHtml::textArea(
            'User[meta_description]', 
            $text, 
            array(
                'rows' => 3, 
                'cols' => 40,
                'class'=>'form-control'
            )
        )*/
        echo $text . '</div>';

    echo '<div class="control-group medium-bl">'
        . '<label class="control-label">Комментарий администратора</label>';
        $text = html_entity_decode($data['comment']);
        $text = strip_tags($text);   
        echo CHtml::textArea(
            'User[comment]', 
            $text, 
            array(
                'rows' => 3, 
                'cols' => 50,
                'class'=>'form-control'
            )
        )
        . '</div>';
echo '</div>';
/*
*       PHOTOS
*/
echo '<div role="tabpanel" class="tab-pane fade" id="tab_photo">';
    echo '<h3>Фото</h3><div class="row photo-list">';
    if(sizeof($data['photos']))
    {
        foreach ($data['photos'] as $key => $item)
        {
            echo '<div class="col-xs-12 col-sm-6 col-md-4 photos__item">'
                . '<a class="photos__item-link" href="' . $item['orig'] 
                . '" title="' . $item['signature'] . '">' 
                . CHtml::image($item['photo'],$item['signature'])
                . '</a>'
                . '</div>';
        }        
    }
    else{
        echo '<div class="col-xs-12">У соискателя нет загруженных фото</div>';
    }
    echo '</div>';
echo '</div>';
/*
*       POSITIONS
*/
echo '<div role="tabpanel" class="tab-pane fade" id="tab_vacs">';
    echo '<h3>Отработанные вакансии: ' . $data['jobs_cnt'] . '</h3><div class="row">';
    if($data['jobs_cnt']>0)
    {
        echo '<div class="col-xs-12">';
        foreach ($data['jobs'] as $job)
        {
            echo '<div class="job_item">' 
                . '<b>Вакансия: </b><a href="' . $job['link'] . '" target="_blank">' . $job['title'] . '</a><br/>'
                . '<b>Дата завершения: </b><span>' . $job['remdate'] . '</span><br/>'
                . '<b>Работодатель: </b><a href="' . $job['empl'] . '" target="_blank">' . $job['name'] . '</a>'
                . '</div>';
        }
        echo '</div>';  
    }
    else
    {
        echo '<div class="col-xs-12">соискатель еще не работал</div>';
    }
echo '</div></div>';
/* 
*       RATING 
*/
echo '<div role="tabpanel" class="tab-pane fade in" id="tab_rating">';
    echo '<h3>Рейтинг</h3><div class="row"><div class="col-xs-12">';
    echo Share::getRating($data['rate'],$data['rate_neg']) . '<br><br>';
    echo '<table class="table table-bordered custom-table">
            <thead>
              <tr>
                <th>Параметр</th>
                <th>Описание</th>
                <th>Значения</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Качество выполненной работы(рейтинг)</td>
                <td>за одну отработанную вакансию</td>
                <td>+50, 0, -50</td>
              </tr>
              <tr>
                <td>Контактность(рейтинг)</td>
                <td>за одну отработанную вакансию</td>
                <td>+50, 0, -50</td>
              </tr>
              <tr>
                <td>Пунктуальность(рейтинг)</td>
                <td>за одну отработанную вакансию</td>
                <td>+50, 0, -50</td>
              </tr>
              <tr><td colspan="3"></td></tr>
              <tr>
                <td>Отзыв</td>
                <td>за одну отработанную вакансию</td>
                <td>+40, -40</td>
              </tr>
              <tr><td colspan="3"></td></tr>
              <tr>
                <td>Время на сайте</td>
                <td>за время с начала регистрации</td>
                <td>< 1 года = +2<br>1 - 2 года = +3<br>> 2 лет = +5</td>
              </tr>
              <tr><td colspan="3"></td></tr>
              <tr>
                <td>Отработанных вакансий</td>
                <td>за завершенные вакансии, в которых утвержден соискатель</td>
                <td>1-3в. = +1<br>4-10в. = +2<br>11-25в. = +3<br>26-50в. = +4<br>>50в. = +5</td>
              </tr>
              <tr><td colspan="3"></td></tr>
              <tr>
                <td>Параметр профиля "Фото"</td>
                <td>за загруженные фото</td>
                <td>1 фото = 1<br>> 1 фото = 2</td>
              </tr>
              <tr>
                <td>Подтверждение Email</td>
                <td>за подтвержденную почту</td>
                <td>+2</td>
              </tr>
              <tr>
                <td>Подтверждение телефона</td>
                <td>за подтвержденный телефон</td>
                <td>+2</td>
              </tr>
            </tbody>
          </table>';
    echo '</div></div>';
echo '</div>';

echo '<div role="tabpanel" class="tab-pane fade in" id="tab_message">';
    echo '<h3>Сообщения</h3>'
        .'<div class="row">'
        .'<div class="col-xs-12"><ul>';
        /**
         * Messages for chosenone user
         */
        echo '<div class="col-xs-12"><ul>';
        foreach ($fdbck as $val) :
            echo '<li class="user-header">'
                .    '<a  href="/admin/site/' . (!$val['type'] ? 'mail/' . $id : 'update/' . $val['chat']) . '"'
                .    '   rel="tooltip"'
                .    '   data-placement="top"'
                .    '   title="Ответить">';
            echo     $val["id"] . ' - ' . $val["theme"]
                .    '</a>'
                .'</li>';
        endforeach;
        echo '</ul></div>';
        /**/
    echo '</div>';
echo '</div>';

            /*
            *
            */
            /*
            *
            */
            echo '</div><br/><br/>'; //tab-content
            echo '<div style="float:right;  display:inline;">'
                . '<a href="/admin/notifications/0?type=message&id_user=' . $data['id_user'] . '" class="btn btn-success">Написать пользователю</a>'
                . '&nbsp;&nbsp;'
                . CHtml::submitButton(
                    'Сохранить',
                    array(
                        "class"=>"btn btn-success", 
                        "id"=>"btn_submit"
                    )
                )
                . '&nbsp;&nbsp;'
                . '<a href="/admin/users" class="btn btn-warning" id="btn_cancel">Отмена</a>';
            echo '</div>';
        echo CHtml::endForm();
    echo '</div>';
echo '</div>';
?>
<script>
    function Del(key)
    {
        if(confirm('Вы действительно хотите удалить документ '+key))
        {
            $.ajax({
                type:'GET',
                url:'/admin/ajax/DeleteScan?key='+key+'&id=<?php echo $data['id']?>',
                cache: false,
                dataType: 'text',
                success:function (data) {
                    $("#lst_scan").html(data);
                },
                error: function(data){
                    alert("Download error!");
                }
            });
        }
    }


    function Add(fname)
    {
            $.ajax({
                type:'GET',
                url:'/admin/ajax/AddScan?id=<?php echo $data['id']?>&fname='+fname,
                cache: false,
                dataType: 'text',
                success:function (data) {
                    $("#lst_scan").html(data);
                },
                error: function(data){
                    alert("Download error!");
                }
            });

    }



    function ajaxFileUpload()
    {

        $.ajaxFileUpload
        (
            {
                url:'/uploads/doajaxdocupload.php',
                secureuri:false,
                fileElementId:'fileToUpload',
                dataType: 'json',
                data:{name:'logan', id:'id'},
                success: function (data, status)
                {
                    if(typeof(data.error) != 'undefined')
                    {
                        if(data.error != '')
                        {
                            alert(data.error);
                        }else
                        {
                            //alert(data.name);
                            Add(data.name);

                        }
                    }
                },
                error: function (data, status, e)
                {
                    alert(e);
                }
            }
        )

        return false;

    }

    $(function(){
        $('.photo-list').magnificPopup({
            delegate: '.photos__item-link',
            type: 'image',
            gallery: {
                enabled: true,
                preload: [0, 2],
                navigateByImgClick: true,
                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
                tPrev: '',
                tNext: '',
                tCounter: '<span class="mfp-counter">%curr% / %total%</span>'
            }
        });
    });
</script>