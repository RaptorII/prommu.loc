<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PROMMU AD.TAB</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/plugins/iCheck/square/blue.css">
    <style>
        .has-feedback {
            position: relative;
            padding-bottom: 20px;
        }
        .errorMessage {
            position: absolute;
            height: 20px;
            bottom: 0;
            left: 0;
        }
    </style>
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        //'htmlOptions'=>array('class'=>'form-horizontal'),
    )); ?>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html"><b>PROMMU</b> AD.TAB</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Открыть сеанс</p>

        <form action="../../index2.html" method="post">
            <div class="form-group has-feedback">
                <?php echo $form->labelEx($model, 'username', array('class' => 'control-label')); ?>
                <?php echo $form->textField($model, 'username', array('class' => 'form-control', 'placeholder' => 'Ваш логин')); ?>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                <?php
                    echo $form->error($model, 'username');
                ?>
            </div>
            <div class="form-group has-feedback">
                <?php echo $form->labelEx($model, 'password', array('class' => 'control-label')); ?>
                <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => 'Ваш пароль')); ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <?php
                    echo $form->error($model, 'password');
                ?>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox"> Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<!--<script src="--><?php //echo Yii::app()->request->baseUrl; ?><!--/plugins/jQuery/jquery-2.2.3.min.js"></script>-->

<!-- Bootstrap 3.3.6 -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/bootstrap/js/bootstrap.min.js"></script>

<!-- iCheck -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/iCheck/icheck.min.js"></script>

<script>
    $(function () {
        $('.icheck').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
<?php $this->endWidget(); ?>
</body>
</html>
