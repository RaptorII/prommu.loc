<?php 
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD); 
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/page/employer.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile('/js/jquery-ui-1.10.3.custom.min.js', CClientScript::POS_HEAD);
$docroot = $_SERVER['DOCUMENT_ROOT'];
echo CHtml::form('/site/showResume','POST',array("id"=>"form"));
?>

<h3><?php echo Share::lng('SREM_TITLE')?></h3>
<div class="row" >
  <div class="span4">
   <div style="display:none;">
      <?php
    	  echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('model'=>$model, 'attribute'=>'access_time','language'=>'ru', 'id'=>'birthday'), true);
    	?>
   </div>
  <!-- Search filter -->
  <h3>Фильтр поиска:</h3>

  <div class="accordion" id="accordion2">

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapseOne" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" ><i class="icon-chevron-down"></i> Город</a>
    </div>
    <div id="collapseOne" class="accordion-body collapse in">
      <div class="accordion-inner">
      <input type="text" name="city" id="city" placeholder="пример: Киев" />
    </div>
    </div>
    </div>

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse2" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" ><i class="icon-chevron-down"></i> <?php echo Share::lng('SREM_SEX')?></a>
    </div>
    <div id="collapse2" class="accordion-body collapse">
      <div class="accordion-inner">
        <label class="radio"><?php echo Share::lng('SREM_MALE')?><input type="radio" value="1" name="ismale" /></label>
        <label class="radio"><?php echo Share::lng('SREM_FEMALE')?><input type="radio" value="0" name="ismale" /></label>
        <label class="radio"><?php echo Share::lng('SREM_ANY')?><input type="radio" value="2" name="ismale" checked /></label>
    </div>
    </div>
    </div>

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse3" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" ><i class="icon-chevron-down"></i> Возраст</a>
    </div>
    <div id="collapse3" class="accordion-body collapse">
      <div class="accordion-inner">
        от <input type="range" min="16" max="50" value="16" id="p_age_from" onchange="changeAge(0)" style="width:100px"><b id="l_age_from">16</b><br>
        до <input type="range" min="16" max="50" value="50" id="p_age_to" onchange="changeAge(1)" style="width:100px"><b id="l_age_to">50</b>
    </div>
    </div>
    </div>

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse4" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" ><i class="icon-chevron-down"></i> <?php echo Share::lng('SREM_HEIGHT')?></a>
    </div>
    <div id="collapse4" class="accordion-body collapse">
      <div class="accordion-inner">
        <input type="text" name="height" id="height" placeholder="172" class="number" style="width:40px;"/>
    </div>
    </div>
    </div>

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse5" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" ><i class="icon-chevron-down"></i> <?php echo Share::lng('SREM_DRESS')?></a>
    </div>
    <div id="collapse5" class="accordion-body collapse">
      <div class="accordion-inner">
      <label class="checkbox">40(xs)<input type="checkbox" name="fsize" value="1" ></label>
      <label class="checkbox">42(s) <input type="checkbox" name="fsize" value="2" ></label>
      <label class="checkbox">44(m) <input type="checkbox" name="fsize" value="3" ></label>
      <label class="checkbox">46(l) <input type="checkbox" name="fsize" value="4" ></label>
      <label class="checkbox">48(xl)<input type="checkbox" name="fsize" value="5" ></label>
    </div>
    </div>
    </div>


    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse6" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" ><i class="icon-chevron-down"></i> <?php echo Share::lng('SREM_EXPERIENCE')?></a>
    </div>
    <div id="collapse6" class="accordion-body collapse">
      <div class="accordion-inner">
      <label class="radio"><?php echo Share::lng('AL_YES')?><input type="radio" name="iswork_promo" checked value="1"></label>
      <label class="radio"><?php echo Share::lng('AL_NO')?><input type="radio" name="iswork_promo" value="0"></label>
    </div>
    </div>
    </div>

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse7" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" ><i class="icon-chevron-down"></i> <?php echo Share::lng('SREM_HBOOK')?></a>
    </div>
    <div id="collapse7" class="accordion-body collapse">
      <div class="accordion-inner">
      <label class="radio"><?php echo Share::lng('AL_YES')?><input type="radio" name="fismed" value="1"></label>
      <label class="radio"><?php echo Share::lng('AL_DNM')?><input type="radio" name="fismed" checked value="0"></label>
    </div>
    </div>
    </div>

</div>

  <a href="#" class="btn btn-inverse" onclick="ClearFilter()">Очистить фильтр</a>
  <a href="#" class="btn btn-success" onclick="Search()"><?php echo Share::lng('AL_SEARCH')?></a>
  <!---End search filter----->

  </div>

<!-- Vacancy lists block -->
<div class="span8"><!--pan2-->
    <input type="hidden" name="vid" id="vid" />
    <!-- Search resume block -->
    <h3 id="title_res">Результаты поиска</h3>
    <div id="list_vac"><br></div>

    <div id="pan_resume" style="display:none; ">
      <?php include_once( $docroot . "/protected/views/frontend/site/resume/resume.php"); ?>
    </div>

</div><!--span8 end-->


<script type="text/javascript">

var objForm = 
(
  {"total_err":0,"param":""},
  [
	{
	"name":"lastname",
	"value":"",
  "tp":0,
	"validate":1,  
	"errmess": "введите фамилию"
  }]);
var city_id=0; 
<?php
echo "m_fio = '".Share::lng('SREM_FIO')."';\r\n";
echo "m_city = '".Share::lng('SREM_CITY')."';\r\n";
echo "m_mech = '".Share::lng('SREM_MECH')."';\r\n";
echo "m_pay = '".Share::lng('SREM_PAYMENT')."';\r\n";
echo "m_age = '".Share::lng('SREM_AGE2')."';\r\n";
echo "m_date = '".Share::lng('SREM_DATE')."';\r\n";
echo "m_review = '".Share::lng('AL_REVIEW')."';\r\n";
Share::PrintMechJson();
echo "\r\n";
?>

</script>

<div class="clear"></div>
</div></div>
<?php
echo CHtml::submitButton('submit',array("id"=>"btn_submit",'style'=>"visibility:hidden"));
echo CHtml::endForm(); 
?>                                                           