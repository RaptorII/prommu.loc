<?php
Yii::app()->getClientScript()->registerCssFile('/admin/css/app-profile.css');
Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
// Magnific Popup
Yii::app()->getClientScript()->registerCssFile('/jslib/magnific-popup/magnific-popup-min.css');
Yii::app()->getClientScript()->registerScriptFile('/jslib/magnific-popup/jquery.magnific-popup.min.js', CClientScript::POS_END);

echo "<pre style='display:none'>";
print_r($data); 
echo "</pre>";

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
                'isman',
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


    echo '<h4>КОНТАКТНАЯ ИНФОРМАЦИЯ</h4>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Телефон</label>'
                . CHtml::textField(
                    'attr[mob]', 
                    $data['attr']['mob'], 
                    array(
                        'class'=>'form-control',
                        'disabled'=>true
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
        echo CHtml::textArea(
            'User[meta_title]', 
            $text, 
            array(
                'rows' => 2, 
                'cols' => 40,
                'class'=>'form-control'
            )
        )
        . '</div>';

    echo '<div class="control-group medium-bl">'
        . '<label class="control-label">meta_h1</label>';
        $text = html_entity_decode($data['meta_h1']);
        $text = strip_tags($text);     
        echo CHtml::textArea(
            'User[meta_h1]', 
            $text, 
            array(
                'rows' => 2, 
                'cols' => 40,
                'class'=>'form-control'
            )
        )
        . '</div>';

    echo '<div class="control-group medium-bl">'
        . '<label class="control-label">meta_description</label>';
        $text = html_entity_decode($data['meta_description']);
        $text = strip_tags($text);     
        echo CHtml::textArea(
            'User[meta_description]', 
            $text, 
            array(
                'rows' => 3, 
                'cols' => 40,
                'class'=>'form-control'
            )
        )
        . '</div>';

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
                . '<a class="photos__item-link" href="' . $item['orig'] . '">' 
                . CHtml::image($item['photo'])
                . '</a>'
                . '</div>';
        }        
    }
    else{
        echo '<p>У соискателя нет загруженных фото</p>';
    }
    echo '</div>';
echo '</div>';
/*
*       POSITIONS
*/
echo '<div role="tabpanel" class="tab-pane fade" id="tab_vacs">';
    echo '<h3>Отработанные вакансии</h3><div class="row">';
    if($data['jobs_cnt']>0)
    {
        echo '<div class="col-xs-12">Отработанных проектов: ' . $data['jobs_cnt'] . '</div>'
            . '<div class="col-xs-12">';
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
            *
            */
            echo '</div><br/><br/>'; //tab-content
            echo '<div style="float:right;  display:inline;">'
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