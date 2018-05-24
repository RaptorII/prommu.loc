<style type="text/css">
    .small-bl{
        max-width: 300px;
    }
    #birthday{
        border-color: #d2d6de;
        display: block;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }
    .d-flex{
        display: flex;
        justify-content: space-between;
    }
    h4{
        font-size: 18px;
    }
    .user__moder .control-group *{ vertical-align: top; }
    #User_ismoder{ margin-right: 5px; }
    .user__logo img{
        display: block;
        width: 100%;
        height: auto;
        border-radius: 50%;
    }
    #User_isblocked label{ display: inline }
    #tablist{
        background-color: #222d32;

    }
    #tablist li{

    }
    #tablist a{
        font-weight: bold;
        color: #b8c7ce;
        border-left: 3px solid transparent;      
    }
    #tablist .active a,
    #tablist a:hover{
        color: #fff;
        background: #1e282c;
        border-left-color: #3c8dbc;
    }
</style>
<?php
Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
/*
echo '<pre>';
print_r($data);
echo '</pre>';
*/

echo '<div class="row"><div class="col-xs-12"><div class="row">';
    echo '<div class="col-xs-12 col-sm-3 col-md-2">'
            . '<ul class="nav user__menu" role="tablist" id="tablist">'
                . '<li class="active"><a href="#tab_profile" aria-controls="tab_profile" role="tab" data-toggle="tab">Общая</a></li>'
                . '<li><a href="#tab_seo" aria-controls="tab_seo" role="tab" data-toggle="tab">СЕО</a></li>'
                . '<li><a href="#tab_photo" aria-controls="tab_photo" role="tab" data-toggle="tab">Фото</a></li>'
                . '<li><a href="#tab_vacs" aria-controls="tab_vacs" role="tab" data-toggle="tab">Вакансии</a></li>'
            . '</ul>'
        . '</div>';
    echo '<div class="col-xs-12 col-sm-9 col-md-10">';
        echo '<div class="tab-content">';
            echo '<div role="tabpanel" class="tab-pane fade active in" id="tab_profile">';

  




    echo '<h3>Редактирование соискателя #'.$data['id_user'].'</h3>';
    echo CHtml::form($id,'post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
        echo '<div class="row"><div class="col-xs-12 col-sm-6 user__logo">'
                . CHtml::image('https://prommu.com/images/applic/'.$data['photo'].'400.jpg')
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
                . '</div>';
            echo '<div class="control-group">'
                . '<label class="control-label">Пол</label><div class="controls input-append">'
                . CHtml::radioButtonList(
                        'isman',
                        $data['isman'],
                        array('1'=>'Парень','0'=>'Девушка'),
                        array()
                    )
                . '</div></div>';
        echo '</div>';
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
        echo '<h4>УДОБНОЕ МЕСТО И ВРЕМЯ РАБОТЫ</h4>';
        echo '<h4>ВНЕШНИЕ ДАННЫЕ</h4>';


        echo '<h4>ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ</h4>';
        echo '<div class="control-group small-bl">'
            . '<label class="control-label">О себе</label>'
                . CHtml::textArea(
                    'User[aboutme]', 
                    $data['aboutme'], 
                    array('class'=>'form-control')
                )
            . '</div>';


















echo '<h4 style="font-size: 40px;font-weight: 100;">МЕТА</h4>';

echo '<div class="control-group">
      <label class="control-label">meta_title</label>
        <div class="controls input-append">';
$text = html_entity_decode($data['meta_title']);
$text = strip_tags($text);     
echo CHtml::textArea('User[meta_title]', $text, array('rows' => 2, 'cols' => 40,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">meta_h1</label>
        <div class="controls input-append">';
$text = html_entity_decode($data['meta_h1']);
$text = strip_tags($text);     
echo CHtml::textArea('User[meta_h1]', $text, array('rows' => 2, 'cols' => 40,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">meta_description</label>
        <div class="controls input-append">';
$text = html_entity_decode($data['meta_description']);
$text = strip_tags($text);     
echo CHtml::textArea('User[meta_description]', $text, array('rows' => 3, 'cols' => 40,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Запрет индексации</label>
        <div class="controls input-append">';
echo CHtml::CheckBox('User[index]', $data['index'], array('value'=>'1' 
));
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Комментария администратора</label>
        <div class="controls input-append">';
$text = html_entity_decode($data['comment']);
$text = strip_tags($text);   
echo CHtml::textArea('User[comment]', $text, array('rows' => 3, 'cols' => 50,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';





echo '</div></div>';


echo '<div class="span11">';
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Сохранить',array("class"=>"btn btn-success", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo '<a href="/admin/site/Users" class="btn btn-warning" id="btn_cancel">Отмена</a>';
//echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));


echo CHtml::endForm();
echo '</div>';
echo '<div role="tabpanel" class="tab-pane fade" id="tab_seo">2</div>
    <div role="tabpanel" class="tab-pane fade" id="tab_photo">3</div>
    <div role="tabpanel" class="tab-pane fade" id="tab_vacs">4</div>';

echo '</div></div></div></div></div>';
?>
</div>

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
</script>