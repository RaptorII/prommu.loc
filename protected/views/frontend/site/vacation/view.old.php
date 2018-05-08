<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/page/vacation_view.js', CClientScript::POS_HEAD);
 ?>
<div id="container_demo" >
  <div id="wrapper">
    <h1><?php echo Share::lng('LKPR_TITLE')?></h1>

    <div class="pan1" style="width:200px;height:auto;">
      <div class="photo"><img id="photo" src="/images/man.png" height="180" style="max-width:200px"/></div>
      <br/>
      <p id="info"></p>
      <h3><?php echo Share::lng('LKPR_ADV')?>:</h3><!--Рекламные агентства-->
      <p align="center">
        <a href="#" class="btn small" onclick="showAgencyPanel()"><?php echo Share::lng('LKPR_ADD-ADV')?></a><!--Добавить агентство-->
      </p>
      <div id="list_ra" class="portlet-content"></div>  
    </div>

    <!-- Vacancy lists block -->
    <div class="pan2" style="width:620px;height:800px;">
      <?php
        echo CHtml::form('/site/vacation','POST',array("id"=>"form"));
      ?>
      <input type="hidden" name="vid" id="vid" />
      <div id="list" style="overflow-y:auto; height:780px;"></div>

      <!-- Search agency block -->
      <div id="searchAgency" style="display:none;">
        <p>
          <label for="agency" data-icon="u"><?php echo Share::lng('LKPR_SEARCH-ADV')?></label><!--Поиск агентства-->
          <input type="text" name="agency" id="agency" placeholder="<?php echo Share::lng('LKPR_ENTER')?>" style="width:80%"/><!--введите название-->
          <a href="#" class="btn small" onclick="getAgencySearch()"><?php echo Share::lng('AL_SEARCH')?></a>
        </p>
        <h1><?php echo Share::lng('LKPR_SEARCH-RES')?></h1><!--Результаты поиска-->
        <div id="list_search_ra"></div>    
      </div>

      <!-- Vacancy block -->
      <div id="vacancy" style="display:none;">
      </div>

      <!--Создать вакансию-->
      <a href="/site/vacation" class="btn small" TITLE="<?php echo Share::lng('LKPR_CRT-VACANCY')?>" id="btn_create_job"><?php echo Share::lng('LKPR_CRT-VACANCY')?></a>
      <a href="#" class="btn small" onclick="back()" style="display:none" id="btn_back"><?php echo Share::lng('AL_BACK')?></a>
    </div><!--end pan2 -->
    <div class="clear"></div>
  </div><!-- end wrapper -->
</div><!-- container_demo -->
<?php
echo CHtml::submitButton('submit',array("id"=>"btn_submit",'style'=>"visibility:hidden"));
echo CHtml::endForm(); 
?>

<script type="text/javascript">

<?php
echo 'var mechInfo = Array(
"-",
"'.Share::lng('AL_MECH-DISTR').'",
"'.Share::lng('AL_MECH-SAMPLIN').'",
"'.Share::lng('AL_MECH-DEGUSTA').'",
"'.Share::lng('AL_MECH-EX').'",
"'.Share::lng('AL_MECH-HORECA').'",
"'.Share::lng('AL_MECH-GWP').'",
"'.Share::lng('AL_MECH-DISTR').'" 
);';
echo "\r\n";
echo 'var mes1 = "'.Share::lng('LKPR_ACT-NAME').'";
  var mes2 = "'.Share::lng('LKPR_ADS-AGENCY').'";
  var mes3 = "'.Share::lng('LKPR_DATE-PUBLIC').'";
  var mes4 = "'.Share::lng('LKPR_MECH').'";
  var mes6 = "'.Share::lng('LKPR_TERMS').'";
  var mes7 = "'.Share::lng('AL_EDIT').'";
  var mes8 = "'.Share::lng('AL_REVIEW').'";
  var mes9 = "'.Share::lng('AL_SHUT-OFF').'";
  var mes5 = "'.Share::lng('LKPR_PAY').'";';
echo "\r\n";
echo 'var txt_join = "'.Share::lng('LKPR_JOIN').'";';
echo "\r\n";

?>

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
  
</script>
                                                           