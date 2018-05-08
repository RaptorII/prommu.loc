<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/page/vacation_view.js', CClientScript::POS_HEAD);
$docroot = $_SERVER['DOCUMENT_ROOT'];
 ?>

<h2>Личный кабинет</h2>
<div class="row">
<div class="span4">
  <h3>Мои РА (партнеры)</h3>
  <div class="photo"><img id="photo" src="/images/man.png"/></div>
  <br>
  <p id="info"></p>
  <h3><?php echo Share::lng('LKPR_ADV')?>:</h3><!--Рекламные агентства-->
  <p id="info_ra"></p>

     <a href="#" class="btn btn-success span2" onclick="showAgencyPanel()"><?php echo Share::lng('LKPR_ADD-ADV')?></a><!--Добавить агентство-->
  <br><br>
  <div id="list_ra" class="portlet-content"></div>
</div><!-- span3 end -->


<div class="span8">
<?php
echo CHtml::form('/site/vacation','POST',array("id"=>"form"));
?>
  <input type="hidden" name="vid" id="vid" />
  <div id="list_vac"></div>

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


  <div id="vacancy" style="display:none; ">
      <?php include_once( $docroot . "/protected/views/frontend/site/vacation/show2.php"); ?>
  </div><!-- pan_resume end -->
      <!--Создать вакансию-->

  <br><br>
  <div class="modal-footer">
      <a href="/site/vacation" class="btn btn-primary" TITLE="<?php echo Share::lng('LKPR_CRT-VACANCY')?>" id="btn_create_job"><?php echo Share::lng('LKPR_CRT-VACANCY')?></a>
      <a href="#" class="btn small" onclick="back()" style="display:none" id="btn_back"><?php echo Share::lng('AL_BACK')?></a>
  </div>

</div>

</div><!-- end row -->


<?php
echo CHtml::submitButton('submit',array("id"=>"btn_submit",'style'=>"visibility:hidden"));
echo CHtml::endForm(); 
?>

<script type="text/javascript">

<?php
Share::PrintMechJson();
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
                                                           