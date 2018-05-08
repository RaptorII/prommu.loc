<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">
       <script type="text/javascript">
    $(document).ready(function() {  $(".templatingSelect2").select2() });
    </script>
<?php if( $viData['premium'] ): ?> 
<div class='row'>
  <div class='col-xs-4 register-wrapp'>
    <h1 class='big'>Проверка данных заказа</h1>

   
    <form action='/user/order' id='F1registerAppl' method='post'>
          
      <?
      unset($viData['premium']);

      foreach ($viData as $key => $value): ?>
      <input type="text" class="vacancy" name="vacanc[]" value="<?=$value ?>">
      <input type="date" class="date" name="from[]">
      <input type="date" class="date" name="to[]">
      <input type="hidden" name="account" value="<?= Share::$UserProfile->id?>">

    <? endforeach;?>

        <div class='btn-reg btn-orange-wr'>
          <button class='hvr-sweep-to-right' type='submit'>Оплатить</button>
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

  </div>
</div>
 <?php elseif($viData['sms']): ?>
  <div class='col-xs-4 register-wrapp'>
    <h1 class='big'>Выбор соискателей</h1>

   

    <form action='/user/order' id='F1registerAppl' method='post'>
          
      <?
      unset($viData['sms']);
      foreach ($viData as $key => $value): ?>
      <input type="hidden" class="vacancy" name="vacsms[]" value="<?=$value ?>">
      <select class='templatingSelect2 ' id='CB2city' name='user[]' multiple="multiple">
          <? for($i = 0; $i <  count($User); $i ++):?>
          <? $firstname = $User[$i]['firstname']; 
             $lastname = $User[$i]['lastname'];
          ?>
          <option value="<?= $User[$i]['idus'];?>" ><?= $firstname." ".$lastname?></option>
        <? endfor;?>
            </select>
      <input type="hidden" name="account" value="<?= Share::$UserProfile->id?>">
      <input type="text" name="text" value="">

    <? endforeach;?>

        <div class='btn-reg btn-orange-wr'>
          <button class='hvr-sweep-to-right' type='submit'>Оплатить</button>
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

  </div>
</div>
<? endif;?>