<?php
    $lang = Share::getLangSelected();
    Share::getLanguages('register',$lang);

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
));
echo "<h3>".Share::lword('STEP1')."</h3>";
echo "<hr>";
echo '<div id="container_demo" >'.
'<div id="wrapper">'.
"<table>
<tr>
<td>".$form->labelEx($model,'email', array("class="=>"uname", "data-icon"=>"e"))."<br/>".
$form->textField($model,'email', array("placeholder"=>"пример: sergey78@mail.com")).
$form->error($model,'email').
"</td>
<tr>

<tr>
<td>".$form->labelEx($model,'login', array("data-icon"=>"u"))."<br/>".
$form->textField($model,'login', array("placeholder"=>"пример: sergey78")).
$form->error($model,'login').
"</td>
<tr>

<tr>
<td>".$form->labelEx($model,'passw', array("data-icon"=>"p"))."<br/>".
$form->passwordField($model,'passw', array("placeholder"=>"пример: X8de66")).
$form->error($model,'passw').
"</td>
<tr>

<tr>
<td>".$form->labelEx($model,'confirm')."<br/>".
$form->passwordField($model,'confirm').
$form->error($model,'confirm').
"</td>
<tr>

<tr>
<td>".$form->labelEx($model,'status')."<br/>".
CHtml::dropDownList('User[status]', $model->status,
             array('0' => Share::lword('OFF'), '1' => Share::lword('PROMOUTER'), '2' => Share::lword('EMPLOYER'), '3' => Share::lword('ADVERTISING_AGENCY'))).
"</td>
<tr>

</table>";
echo CHtml::submitButton(Share::lword('NEXT'), array( "id"=>"btn_submit"));
$this->endWidget();

echo "<br/><br/><h3>".Share::lword('WHAT')."</h3>";
echo "<hr>";
echo "<p>".Share::lword('WHAT_TEXT')."</p>";
echo CHtml::link(Share::lword('BACK'), Yii::app()->createUrl("site/page/about"), array('class'=>'btn') ); 
?>
</div>
</div>