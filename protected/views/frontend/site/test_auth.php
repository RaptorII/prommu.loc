<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
'Login',
);
?>

<h1>Authorize</h1>

<p>Please fill out the following form with your login credentials:</p>
date now : <?= date("d.m.Y") ?>
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'login-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

    <div class="row">
        <input type="text" name="login" id="login" value="<?php echo $res['login'];?>">
    </div>


    <div class="row">
        <input type="text" name="passw" id="passw" value="">
    </div>

    <div class="row buttons">
    <?php
    echo CHtml::submitButton('Authorize');
    $this->endWidget();
    ?>
</div>

<?php
/**
 * Created by PhpStorm.
 * User: wind
 * Date: 11.02.16
 * Time: 23:30
 */
print_r($res);