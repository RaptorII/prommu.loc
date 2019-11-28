<?php
//
$bUrl = Yii::app()->request->baseUrl;
$gcs = Yii::app()->getClientScript();
//
$gcs->registerCssFile($bUrl . '/css/emp-profile.css');
$gcs->registerCssFile($bUrl . '/css/template.css');
$gcs->registerScriptFile($bUrl . '/js/users/item-emp.js', CClientScript::POS_END);
// Magnific Popup
$gcs->registerCssFile('/jslib/magnific-popup/magnific-popup-min.css');
$gcs->registerScriptFile('/jslib/magnific-popup/jquery.magnific-popup.min.js', CClientScript::POS_END);
//
$anchor = Yii::app()->request->getParam('anchor');

echo '<div class="row">';
echo '<div class="col-xs-12 col-sm-9 col-sm-offset-3 col-md-6 col-md-offset-2">'
    . '<h2>Редактирование работодателя #' . $data['id_user'] . '</h2>'
    . '</div>';
echo '<div class="col-xs-12"><div class="row">';
    echo '<div class="col-xs-12 col-sm-3 col-md-2">'
            . '<ul class="nav user__menu" role="tablist" id="tablist">'
                . '<li class="' . (($anchor=='tab_profile' || empty($anchor)) ? 'active' : '') . '"><a href="#tab_profile" aria-controls="tab_profile" role="tab" data-toggle="tab">Общее</a></li>'
                . '<li class="' . ($anchor=='tab_photo' ? 'active' : '') . '"><a href="#tab_photo" aria-controls="tab_photo" role="tab" data-toggle="tab">Фото</a></li>'
                . '<li class="' . ($anchor=='tab_vacs' ? 'active' : '') . '"><a href="#tab_vacs" aria-controls="tab_vacs" role="tab" data-toggle="tab">Вакансии</a></li>'
                . '<li class="' . ($anchor=='tab_archive' ? 'active' : '') . '"><a href="#tab_archive" aria-controls="tab_archive" role="tab" data-toggle="tab">Архив</a></li>'
                . '<li class="' . ($anchor=='tab_rating' ? 'active' : '') . '"><a href="#tab_rating" aria-controls="tab_rating" role="tab" data-toggle="tab">Рейтинг</a></li>'
                . '<li class="' . ($anchor=='tab_feedback' ? 'active' : '') . '"><a href="#tab_feedback" aria-controls="tab_feedback" role="tab" data-toggle="tab">Обратная связь</a></li>'
            . '</ul>'
        . '</div>';
    echo '<div class="col-xs-12 col-sm-9 col-md-6">';
        echo CHtml::form($id,'post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
            echo '<div class="tab-content">';
/* 
*       TAB PROFILE 
*/

echo '<div role="tabpanel" class="tab-pane fade' . (($anchor=='tab_profile' || empty($anchor)) ? ' active in' : '') . '" id="tab_profile">';
    echo '<h3>Общее</h3>';
    echo '<div class="row"><div class="col-xs-12 col-sm-6 user__logo">'
            . CHtml::image($data['src'],$data['name'])
        . '</div><div class="col-xs-12 col-sm-6 user__moder">'
            . '<h4>МОДЕРАЦИЯ</h4>'
            . '<div class="control-group">'                
            . CHtml::radioButtonList(
                    'User[ismoder]',
                    $data['ismoder'],
                    User::getAdminArrIsmoder(),
                    []
                )
            . '</div>';

            echo '<div class="control-group">'
                . '<label class="control-label">Видимость</label><div class="controls input-append">'
                    . CHtml::radioButtonList(
                        'User[isblocked]',
                        $data['isblocked'],
                        User::getAdminArrIsblocked(),
                        []
                    )
                . '</div></div>';
    echo '</div></div>';


    echo '<br/><h4>ОСНОВНАЯ ИНФОРМАЦИЯ</h4>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Название компании</label>'
                . CHtml::textField(
                    'User[name]', 
                    html_entity_decode($data['name']), 
                    array('class'=>'form-control')
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">ИНН</label>'
                . CHtml::textField(
                    'User[inn]',
                    $data['attr']['inn'],
                    array('class'=>'form-control')
                )
            . '</div>';
    echo '<div class="control-group">'
        . '<label class="control-label">Тип компании</label><div class="controls input-append">'
            . CHtml::radioButtonList(
                'User[type]',
                $data['type'],
                array(
                    102 => 'Прямой работодатель',
                    103 => 'Рекламное агенство',
                    104 => 'Кадровое агенство',
                    105 => 'Рекрутинговое агенство',
                    106 => 'Модельное агенство',
                    135 => 'Не выбран'
                ),
                array()
            )
        . '</div></div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Город</label>'
            . '<div>' . $data['cities'] . '</div>'
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Сайт</label>'
                . CHtml::textField(
                    'User[site]', 
                    html_entity_decode($data['attr']['site']), 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';


    echo '<br/><h4>КОНТАКТНАЯ ИНФОРМАЦИЯ</h4>';
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
echo '<div class="control-group small-bl">'
            . '<label class="control-label">Контактное лицо</label>'
                . CHtml::textField(
                    'User[contact]',
                    $data['contact'],
                    array('class'=>'form-control')
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Email</label>'
                . CHtml::textField(
                    'User[email]', 
                    $data['email'], 
                    array('class'=>'form-control','disabled'=>false)
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Телефон</label>'
                . CHtml::textField(
                    'User[mob]', 
                    $data['attr']['mob'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Viber</label>'
                . CHtml::textField(
                    'User[viber]', 
                    $data['attr']['viber'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">WhatsApp</label>'
                . CHtml::textField(
                    'User[whatsapp]', 
                    $data['attr']['whatsapp'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Telegram</label>'
                . CHtml::textField(
                    'User[telegram]', 
                    $data['attr']['telegram'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Google Allo</label>'
                . CHtml::textField(
                    'User[googleallo]', 
                    $data['attr']['googleallo'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">Должность</label>'
                . CHtml::textField(
                    'User[post]', 
                    $data['attr']['post'], 
                    array('class'=>'form-control','disabled'=>true)
                )
            . '</div>';
    echo '<div class="control-group small-bl">'
            . '<label class="control-label">О компании</label>'
                . CHtml::textArea(
                    'User[aboutme]',
                    $data['aboutme'],
                    [
                        'class'=>'form-control',
                        //'disabled'=>true,
                    ]
                )
            . '</div>';
echo '</div>';
/* 
*       PHOTO
*/
echo '<div role="tabpanel" class="tab-pane fade' . ($anchor=='tab_photo' ? ' active in' : '') . '" id="tab_photo">';
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
*       VACS 
*/
echo '<div role="tabpanel" class="tab-pane fade' . ($anchor=='tab_vacs' ? ' active in' : '') . '" id="tab_vacs">';
    echo '<h3>Вакансии</h3><div class="row">';
    if(sizeof($data['vacancies']))
    {
        foreach ($data['vacancies'] as $key => $item)
        {
            echo '<div class="col-xs-12">'
                . '<div class="vac__item">'
                . '<b>Заголовок: </b><a target="_blank" href="' . $item['link'] . '" target="_blank">' . $item['title'] . '</a><br/>'
                . '<b>Откликов: </b><span>' . $item['isresp'][1] . '</span><br/>'
                . '<b>Просмотров: </b><span>' . $data['analytic'][$item['id']] . '</span><br/>';

            $arTemp = array();
            foreach ($data['responses'] as $r){
                if($r['id_vac']!=$item['id'])
                    continue;
                if(!empty($r['name'])){
                    if( $r['status'] == 1)  // Отложенные
                        $arTemp['aside'][] = '<a target="_blank" href="' 
                            . $r['profile'] . '" target="_blank">' . $r['name'] . '</a> '; 

                    elseif( $r['status'] == 3 && $r['isresponse'] == 2 ) // Отказавшиеся
                        $arTemp['refuse'][] = '<a target="_blank" href="' 
                            . $r['profile'] . '" target="_blank">' . $r['name'] . '</a> ';

                    elseif( $r['status'] == 3 ) // Отклоненные
                        $arTemp['reject'][] = '<a target="_blank" href="' 
                            . $r['profile'] . '" target="_blank">' . $r['name'] . '</a> ';

                    elseif( $r['status'] == 5 ) // Утвержденные
                        $arTemp['approv'][] = '<a target="_blank" href="' 
                            . $r['profile'] . '" target="_blank">' . $r['name'] . '</a> ';

                    elseif( in_array($r['status'], [0,2,4,6,7]) ) // Откликнувшиеся
                        $arTemp['resp'][] = '<a target="_blank" href="' 
                            . $r['profile'] . '" target="_blank">' . $r['name'] . '</a> ';                   
                }  
            }
            if(isset($arTemp['approv']))
                echo '<b>Утвержденные: </b>' . implode(', ', $arTemp['approv']) . '<br/>';

            if(!empty($arTemp['resp']))
                echo '<b>Откликнувшиеся: </b>' . implode(', ', $arTemp['resp']) . '<br/>';

            if(!empty($arTemp['aside']))
                echo '<b>Отложенные: </b>' . implode(', ', $arTemp['aside']) . '<br/>';

            if(!empty($arTemp['reject']))
                echo '<b>Отклоненные: </b>' . implode(', ', $arTemp['reject']) . '<br/>';

            if(!empty($arTemp['refuse']))
                echo '<b>Отказавшиеся: </b>' . implode(', ', $arTemp['refuse']) . '<br/>';
            unset($arTemp);

            echo '</div></div>';
        }        
    }
    else{
        echo '<div class="col-xs-12">У работодателя нет активных вакансий</div>';
    }
    echo '</div>';
echo '</div>';
/* 
*       ARCHIVE 
*/
echo '<div role="tabpanel" class="tab-pane fade' . ($anchor=='tab_archive' ? ' active in' : '') . '" id="tab_archive">';
    echo '<h3>Архив</h3><div class="row">';
    if(sizeof($data['vacancies_arch']))
    {
        foreach ($data['vacancies_arch'] as $key => $item)
        {
            echo '<div class="col-xs-12">'
                . '<div class="vac__item">'
                . '<b>Заголовок: </b><a target="_blank" href="' . $item['link'] . '" target="_blank">' . $item['title'] . '</a><br/>'
                . '<b>Откликов: </b><span>' . $item['isresp'][1] . '</span><br/>'
                . '<b>Просмотров: </b><span>' . $data['analytic'][$item['id']] . '</span><br/>';

            $arTemp = array();
            foreach ($data['responses'] as $r){
                if($r['id_vac']!=$item['id'])
                    continue;
                if(!empty($r['name'])){
                    if( $r['status'] == 1)  // Отложенные
                        $arTemp['aside'][] = '<a target="_blank" href="' 
                            . $r['profile'] . '" target="_blank">' . $r['name'] . '</a> '; 

                    elseif( $r['status'] == 3 && $r['isresponse'] == 2 ) // Отказавшиеся
                        $arTemp['refuse'][] = '<a target="_blank" href="' 
                            . $r['profile'] . '" target="_blank">' . $r['name'] . '</a> ';

                    elseif( $r['status'] == 3 ) // Отклоненные
                        $arTemp['reject'][] = '<a target="_blank" href="' 
                            . $r['profile'] . '" target="_blank">' . $r['name'] . '</a> ';

                    elseif( $r['status'] == 5 ) // Утвержденные
                        $arTemp['approv'][] = '<a target="_blank" href="' 
                            . $r['profile'] . '" target="_blank">' . $r['name'] . '</a> ';

                    elseif( in_array($r['status'], [0,2,4,6,7]) ) // Откликнувшиеся
                        $arTemp['resp'][] = '<a target="_blank" href="' 
                            . $r['profile'] . '" target="_blank">' . $r['name'] . '</a> ';                   
                }  
            }
            if(isset($arTemp['approv']))
                echo '<b>Утвержденные: </b>' . implode(', ', $arTemp['approv']) . '<br/>';

            if(!empty($arTemp['resp']))
                echo '<b>Откликнувшиеся: </b>' . implode(', ', $arTemp['resp']) . '<br/>';

            if(!empty($arTemp['aside']))
                echo '<b>Отложенные: </b>' . implode(', ', $arTemp['aside']) . '<br/>';

            if(!empty($arTemp['reject']))
                echo '<b>Отклоненные: </b>' . implode(', ', $arTemp['reject']) . '<br/>';

            if(!empty($arTemp['refuse']))
                echo '<b>Отказавшиеся: </b>' . implode(', ', $arTemp['refuse']) . '<br/>';
            unset($arTemp);

            echo '</div></div>';
        }        
    }
    else{
        echo '<div class="col-xs-12">У работодателя нет активных вакансий</div>';
    }
    echo '</div>';
echo '</div>';
/* 
*       RATING 
*/
echo '<div role="tabpanel" class="tab-pane fade' . ($anchor=='tab_rating' ? ' active in' : '') . '" id="tab_rating">';
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
                <td>Соблюдение сроков оплаты(рейтинг)</td>
                <td>за одну отработанную вакансию</td>
                <td>+10, 0, -10</td>
              </tr>
              <tr>
                <td>Размер оплаты</td>
                <td>за одну отработанную вакансию(рейтинг)</td>
                <td>+10, 0, -10</td>
              </tr>
              <tr>
                <td>Четкость постановки задач(рейтинг)</td>
                <td>за одну отработанную вакансию</td>
                <td>+10, 0, -10</td>
              </tr>
              <tr>
                <td>Четкость требований(рейтинг)</td>
                <td>за одну отработанную вакансию</td>
                <td>+10, 0, -10</td>
              </tr>
              <tr>
                <td>Контактность(рейтинг)</td>
                <td>за одну отработанную вакансию</td>
                <td>+10, 0, -10</td>
              </tr>
              <tr><td colspan="3"></td></tr>
              <tr>
                <td>Отзыв</td>
                <td>за одну отработанную вакансию</td>
                <td>+20, -20</td>
              </tr>
              <tr><td colspan="3"></td></tr>
              <tr>
                <td>Время на сайте</td>
                <td>за время с начала регистрации</td>
                <td>кол-во лет * 5</td>
              </tr>
              <tr><td colspan="3"></td></tr>
              <tr>
                <td>Отработанных вакансий</td>
                <td>за завершенную вакансию(бонус за каждую такую вакансию)</td>
                <td>+1</td>
              </tr>
              <tr><td colspan="3"></td></tr>
              <tr>
                <td>Параметр профиля "Сайт"</td>
                <td>за заполненное поле</td>
                <td>+1</td>
              </tr>
              <tr>
                <td>Параметр профиля "Инн"</td>
                <td>за заполненное поле</td>
                <td>+2</td>
              </tr>
              <tr>
                <td>Параметр профиля "Юридический адрес"</td>
                <td>за заполненное поле</td>
                <td>+2</td>
              </tr>
              <tr>
                <td>Параметр профиля "Городской телефон"</td>
                <td>за заполненное поле</td>
                <td>+2</td>
              </tr>
              <tr>
                <td>Параметр профиля "Мессенджер"(viber)</td>
                <td>за заполненное поле</td>
                <td>+1</td>
              </tr>
              <tr>
                <td>Параметр профиля "Мессенджер"(whatsapp)</td>
                <td>за заполненное поле</td>
                <td>+1</td>
              </tr>
              <tr>
                <td>Параметр профиля "Мессенджер"(telegram)</td>
                <td>за заполненное поле</td>
                <td>+1</td>
              </tr>
              <tr>
                <td>Параметр профиля "Мессенджер"(googleallo)</td>
                <td>за заполненное поле</td>
                <td>+1</td>
              </tr>
              <tr>
                <td>Параметр профиля "Лого"</td>
                <td>за загруженное фото</td>
                <td>+2</td>
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
/*
*       Feedback
*/
echo '<div role="tabpanel" class="tab-pane fade' . ($anchor=='tab_feedback' ? ' active in' : '') . '" id="tab_feedback">';
echo '<h3>Обратная связь</h3><div class="row"><div class="col-xs-12" id="messages_tab_content">';
$_GET['FeedbackTreatment']['pid'] = $data['id_user'];
$this->renderPartial('feedback/list-profile');
echo '</div></div>';
echo '</div>';






            /*
            *
            */
            echo '</div><br/><br/>'; //tab-content
            echo '<div style="float:right;  display:inline;">'
                . '<a href="/admin/notifications/0?type=message&id_user=' . $data['id_user'] . '" class="btn btn-success">Уведомление</a>'
                . '&nbsp;&nbsp;'
                . CHtml::submitButton(
                    'Сохранить',
                    array(
                        "class"=>"btn btn-success", 
                        "id"=>"btn_submit"
                    )
                )
                . '&nbsp;&nbsp;'
                . '<a href="/admin/site/empl" class="btn btn-warning" id="btn_cancel">Отмена</a>';
            echo '</div>';
        echo CHtml::endForm();
    echo '</div>';
echo '</div>';
?>