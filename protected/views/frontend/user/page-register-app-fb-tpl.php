<div class='row'>
  <div class='col-xs-12 register-wrapp'>
<?php 
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/theme/css/register-form.css');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/private/page-register-fb.js', CClientScript::POS_END);
$this->setBreadcrumbs($title = 'Регистрация', $this->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '1')));
$this->pageTitle = $title;
?>

    <?php if($_GET['type'] == 3):?>
      <h1 class='big'>Введите название компании и email </h1>
    <? else:?>
    <h1 class='big'>Введите email </h1>
  <? endif;?>

    <form action='/user/messenger?lname=<?=$viData['lname'];?>&fname=<?=$viData['fname'];?>&gender=<?=$viData['gender'];?>&birthday=<?=$viData['birthday'];?>&photos=<?=$viData[0]?>&type=<?=$_GET['type'];?>' id='F1registerAppl' method='get'>
        <input type="hidden" name="lname" value="<?=$viData['lname'];?>" />
        <input type="hidden" name="fname" value="<?=$viData['fname'];?>"/>
        <input type="hidden" name="gender" value="<?=$viData['gender'];?>"/>
        <input type="hidden" name="birthday" value="<?=$viData['birthday'] ?>" />
        <input type="hidden" name="type" value="<?=$_GET['type']?>" /> 
        <input type="hidden" name="photos" value="<?=$viData[0]?>" />
        <input type="hidden" name="messenger" value="<?=$viData['id']?>"/>
        
       
      <div class='register'>

      <?php if($_GET['type'] == 3):?>
        <div class="clearfix"></div>
        <label for='EdEmail'>Название компании</label>
          <span class="red"><?= $viData['hint'] ?></span>
        <input id='EdName' name='name' type='text' value="<?= $viData['name'] ?>">
      <? endif;?>
        <div class="clearfix"></div>
        <label for='EdEmail'>Электронный адрес</label>
        <?php if( $viData['element'] == 'email' ): ?>
          <span class="red"><?= $viData['hint'] ?></span>
        <?php endif; ?>
        <input id='EdEmail' name='email' type='text' value="<?= $viData['email'] ?>" class="register-fb__email">
        <?php if($_GET['type'] != 3 && !$viData['gender']):?>
         <!--  <div class="clearfix"></div> -->
        <label class="reg-form__label com5">
                   <label for='EdEmail'>Пол</label>
                    <label class='reg-form__label-radio' title="мужской">
                        <input name='gender' type='radio' value='1' checked="checked">
                        <span class="reg-form__radio"><span></span></span>
                        М
                    </label>
                    <label class='reg-form__label-radio' title="женский">
                        <input name='gender' type='radio' value='0'>
                        <span class="reg-form__radio"><span></span></span>
                        Ж
                    </label>
                </label>
    <?php endif; ?>
    <div class="clearfix"></div>
        <div class='btn-reg btn-orange-wr'>
          <button class='hvr-sweep-to-right btn__orange' type='submit' id="reg-fb-btn">Зарегистрироваться</button>
        </div>
      </div>
      <input type="hidden" class="referer" name="referer" value="">
      <input type="hidden" class="transition" name="transition" value="">
      <input type="hidden" class="canal" name="canal" value="">
      <input type="hidden" class="campaign" name="campaign" value="">
                <input type="hidden" class="content" name="content" value="">
                <input type="hidden" class="keywords" name="keywords" value="">
                <input type="hidden" class="point" name="point" value="">
                <input type="hidden" class="last_referer" name="last_referer" value="">
    </form>

    <small>
      Нажав кнопку зарегистрироваться, я тем самым подтверждаю, что принимаю
      <a href='/<?= MainConfig::$PAGE_PAGES ?>/conditions'>условия использования</a>
      сайта
    </small>

  </div>
</div>
<? 
// var_dump($_GET);
  if($_GET['service'] == 'vkontakte'){
   $code = $_GET['code'];
   $userInfo = json_decode(file_get_contents("https://www.googleapis.com/plus/v1/people/112936596524568030502?key=AIzaSyALUe2QiFEtuT7kQ2giwjPQOc_Mw-ZEovc"));

        // if (isset($userInfo['response'][0]['email'])) {
        //     $userInfo = $userInfo['response'][0];
          //  var_dump($userInfo);
       // }

}

?>

