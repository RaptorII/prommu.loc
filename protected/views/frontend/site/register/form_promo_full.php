<?php  
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD);
echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));
?>
<div class="mymenu">
  <ul id="breadcrumbs-one">
    <li class="active"><a href="/site/resume" class="current">Резюме</a></li>
    <li><a href="/site/Photo">Мои фото</a></li>
  </ul>
</div>

<div class="row" >
  <div class="span12">
  <h3>Резюме</h3>
  <div class="form-horizontal">
  

  <div class="accordion" id="accordion2">

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapseOne" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" >Параметры и размеры</a>
    </div>
    <div id="collapseOne" class="accordion-body collapse in">
      <div class="accordion-inner">


  <div class="control-group">
    <label class="control-label">Рост см * <span id="height_err" class="err badge badge-important">!</span></label>
    <div class="controls input-append">
      <input type="text" name="height" id="height" placeholder="172" class="number" style="width:40px;"/>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label">Размер одежды * <span id="size_err" class="err badge badge-important">!</span></label>
    <div class="controls input-append">
      <label class="radio">40(xs)<input type="radio" name="size" value="1" >&nbsp;&nbsp;</label>
      <label class="radio">42(s)<input type="radio" name="size" value="2" >&nbsp;&nbsp;</label>
      <label class="radio">44(m)<input type="radio" name="size" value="4" >&nbsp;&nbsp;</label>
      <label class="radio">46(l)<input type="radio" name="size" value="8" >&nbsp;&nbsp;</label>
      <label class="radio">48(xl)<input type="radio" name="size" value="16" ></label>
    </div>
  </div>


    </div>
    </div>
    </div>


    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse2" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" >Удобное время</a>
    </div>
    <div id="collapse2" class="accordion-body collapse">
      <div class="accordion-inner">

        <div class="control-group">
            <label class="control-label">Пн-Пт начало * <span id="twd_start_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="text" name="twd_start" id="twd_start"  placeholder="9.00" class="span1 number"/><span class="add-on">.00</span>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Пн-Пт конец * <span id="twd_end_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="text" name="twd_end" id="twd_end"  placeholder="18.00" class="span1 number" size="5"/><span class="add-on">.00</span>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Сб-Вс начало * <span id="twe_start_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="text" name="twe_start" id="twe_start"  placeholder="9.00" class="span1 number" size="5"/><span class="add-on">.00</span>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Сб-Вс конец * <span id="twe_end_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="text" name="twe_end" id="twe_end"  placeholder="16.00" class="span1 number" size="5"/><span class="add-on">.00</span>
            </div>
        </div>

      </div>
      </div>
      </div>


    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse3" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" >Удобные районы</a>
    </div>
    <div id="collapse3" class="accordion-body collapse">
      <div class="accordion-inner">

        <div class="control-group">
            <label class="control-label">Город * <span id="city_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="text" name="city" id="city" placeholder="Киев" />
            </div>
        </div>


          <?php
          echo FormHelper::ListCheckBoxMetro('Ближайшая ст. метро *');
          ?>
<!--
        <div class="control-group">
            <label class="control-label">Ближайшая ст. метро * <span id="metro_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <select multiple id="metro" name="metro" onchange="formValidate()"></select><br/> 

            </div>
        </div>
-->
        <div class="control-group">
          <label class="control-label"></label>
          <div class="controls input-append">
              <a href="#" onclick="disableMetro(); return false;" class="btn btn-inverse">Отменить все станции</a>
            </div>
        </div>


      </div>
    </div>
    </div>



    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse4" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" >Интересующая работа</a>
    </div>
    <div id="collapse4" class="accordion-body collapse">
      <div class="accordion-inner">

          <?php
          echo FormHelper::ListCheckBoxDB('ready_work', 'Механики на которых готовы работать *', 'mech');
          ?>
        <!--
        <div class="control-group">
            <label class="control-label">Механики на которых готовы работать * <span id="ready_work_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
             <label class="checkbox">Раздача листовок<input type="checkbox" id="ch_ready_work_1" value="1" name="ready_work" class="mech"/></label>
             <label class="checkbox">Сэмплинг<input type="checkbox" id="ch_ready_work_2" value="2" name="ready_work" class="mech"/></label>
             <label class="checkbox">Дегустация<input type="checkbox" id="ch_ready_work_3" value="3" name="ready_work" class="mech"/></label>
             <label class="checkbox">Выстаки/Презентации<input type="checkbox" id="ch_ready_work_4" value="4" name="ready_work" class="mech"/></label>
             <label class="checkbox">HoReCa<input type="checkbox" id="ch_ready_work_5" value="5" name="ready_work" class="mech"/></label>
             <label class="checkbox">ПЗП<input type="checkbox" id="ch_ready_work_6" value="6" name="ready_work" class="mech"/></label>
             <label class="checkbox">Консультант<input type="checkbox" id="ch_ready_work_7" value="7" name="ready_work" class="mech"/></label>
            </div>
        </div>
        -->

        <div class="control-group">
          <label class="control-label">Наличие мед. книжки * <span id="ismed_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <label class="radio">Да<input type="radio" name="ismed" value="1" onchange="medLogic()"></label>
            <label class="radio">Нет<input type="radio" name="ismed" checked value="2" onchange="medLogic()"></label>
          </div>
        </div>

      <div id="med_block" style="display:none">
        <div class="control-group">
          <label class="control-label">Организация выдавшая книжку * <span id="med_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="med" id="med" placeholder="пример: ООО «Дезсервис»" />
            </div>
        </div>
      </div>


        <div class="control-group">
          <label class="control-label">Опыт работы в промо?* <span id="isexpirience_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
          <label class="radio">Да<input type="radio" name="isexpirience" value="1" onchange="mechLogic()"></label>
          <label class="radio">Нет<input type="radio" name="isexpirience" value="2" onchange="mechLogic()"></label>
          </div>
        </div>


        <div class="control-group">
          <label class="control-label">Ваша минимальная почас. оплата * <span id="hpay_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="hpay" id="hpay" placeholder="10.00" class="number span1" /><span class="add-on">.00</span>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label">Готовность работать в зимнее время на улице? * <span id="iswinter_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
          <label class="radio">Да<input type="radio" name="iswinter" value="1"></label>
          <label class="radio">Нет<input type="radio" name="iswinter" value="2"></label>
          </div>
        </div>


      </div>
    </div>
    </div>



    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse5" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" >О себе</a>
    </div>
    <div id="collapse5" class="accordion-body collapse">
      <div class="accordion-inner">

       <?php
          echo FormHelper::ListCheckBoxDB('mech', 'На каких механиках работали? *', 'mech');
       ?>
<!--
       <div class="control-group">
          <label class="control-label">На каких механиках работали? * <span id="mech_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
           <label class="checkbox"><input type="checkbox" id="ch_mech_0" value="0" name="mech" class="mech"/>Без опыта работы</label>
           <label class="checkbox"><input type="checkbox" id="ch_mech_1" value="1" name="mech" class="mech"/>Раздача листовок</label>
           <label class="checkbox"><input type="checkbox" id="ch_mech_2" value="2" name="mech" class="mech"/>Сэмплинг</label>
           <label class="checkbox"><input type="checkbox" id="ch_mech_3" value="3" name="mech" class="mech"/>Дегустация</label>
           <label class="checkbox"><input type="checkbox" id="ch_mech_4" value="4" name="mech" class="mech"/>Выстаки/Презентации</label>
           <label class="checkbox"><input type="checkbox" id="ch_mech_5" value="5" name="mech" class="mech"/>HoReCa</label>
           <label class="checkbox"><input type="checkbox" id="ch_mech_6" value="6" name="mech" class="mech"/>ПЗП</label>
           <label class="checkbox"><input type="checkbox" id="ch_mech_7" value="7" name="mech" class="mech"/>Консультант</label>
         </div>
       </div>
-->
       <div class="control-group">
          <label class="control-label">Иностранный язык * <span id="language_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
          <SELECT id="language" name="language">
          <OPTION VALUE="--">No speak (Не владею)</OPTION>
          <OPTION VALUE="en">English (Английский)</OPTION>
          <OPTION VALUE="de">Deutsch (Немецкий)</OPTION>
          <OPTION VALUE="fr">French (Французкий)</OPTION>
          <OPTION VALUE="pl">Polski (Польский)</OPTION>
          <OPTION VALUE="zh">Chinese (Китайский)</OPTION>
          <OPTION VALUE="ja">Japanese (Японский)</OPTION>
          <OPTION VALUE="ko">Korean (Корейский)</OPTION>
          <OPTION VALUE="tu">Turkish (Турецкий)</OPTION>
          </SELECT>
         </div>
       </div>

       <div class="control-group">
          <label class="control-label">Уровень языка * <span id="lang_level_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
          <SELECT id="lang_level" name="lang_level">
          <OPTION VALUE="0">No speak (Не владею)</OPTION>
          <OPTION VALUE="1">Beginner, Elementary</OPTION>
          <OPTION VALUE="2">Pre-Intermediate</OPTION>
          <OPTION VALUE="3">Intermediate</OPTION>
          <OPTION VALUE="4">Upper-Intermediate</OPTION>
          <OPTION VALUE="5">Advanced</OPTION>
          <OPTION VALUE="6">Proficiency </OPTION>
          </SELECT>
         </div>
       </div>

       <div class="control-group">
          <label class="control-label">О себе (личные качества) * <span id="aboutme_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <textarea name="aboutme" id="aboutme" placeholder="пример: не курю ..." style="height:150px;"></textarea>
         </div>
       </div>

      </div>
    </div>
    </div>

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse6" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" >Контакты</a>
    </div>
    <div id="collapse6" class="accordion-body collapse">
      <div class="accordion-inner">

       <div class="control-group">
          <label class="control-label">Е-майл * <span id="email_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="email" id="email" placeholder="xxxx@mail.ru"  class="asci_only"/>
      </div>
      </div>


      </div>
    </div>
    </div>

</div><!-- end accordion -->

  <div class="alert alert-error err" id="total_err">
    Не все поля заполнены! Проверьте фому.
  </div>

    <div class="modal-footer">
			<a href='/' class='btn btn-inverse' id='btn_cancel_form' >Отмена</a>
      <a href='javascript:Save();' class='btn btn-primary' id='btn_save_form' >Сохранить</a>
    </div>

</div><!-- pan1 -->  
</div><!-- wrapper end -->
</div><!-- container end -->


<div style="display:none">
<!-- Для работы UI интерфейса динам. списка городов -->
      <?php
    	  echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('model'=>$model, 'attribute'=>'access_time','language'=>'ru', 'id'=>'birthday'), true);
    	?>
</div>


   
<?php echo CHtml::endForm(); ?>

<script type="text/javascript">

var dataForm;
var run_parse=false; // flag of parse form
var photo = '';
var objForm = (
  {"total_err":0,"param":""},
  [
	{
	"name":"height",
	"value":"",
  "tp":0,
	"validate":1,  
	"errmess": "введите рост"
  },  
	{
	"name":"size",
	"value":"",
  "tp":1,
  "validate":1,
	"errmess": "выберите размер"
  },  
	{
	"name":"twd_start",
	"value":"",
  "tp":0,
	"validate":1,
	"errmess": "?"
  },  
	{
	"name":"twd_end",
	"value":"",
  "tp":0,
	"validate":1,
	"errmess": "?"
  },
  {  
	"name":"twe_start",
	"value":"",
  "tp":0,
	"validate":1,
	"errmess": "?"
  },  
	{
	"name":"twe_end",
	"value":"",
  "tp":0,
	"validate":1,
	"errmess": "?"
  },
	{
	"name":"ismed",
	"value":"",
  "tp":1,
	"validate":1,
	"errmess": "?"
  },
	{
	"name":"med",
	"value":"",
  "tp":0,
	"validate":0,
	"errmess": "Введите название"
  },    
	{
	"name":"city",
	"value":"",
  "tp":0,
	"validate":1,
	"errmess": "Введите город"
  },    
	{
	"name":"metro",
	"value":"",
  "tp":2,
	"validate":1,
	"errmess": "Выберите метро"
  },
	{
	"name":"isexpirience",
	"value":"",
  "tp":1,
	"validate":1,
	"errmess": "?"
  },
  {
	"name":"mech",
	"value":"",
  "tp":2,
	"validate":1,
	"errmess": "Выберите механику"
  },
  {
	"name":"email",
	"value":"",
  "tp":0,
	"validate":1,
	"errmess": "Введите email"
  },
  {
	"name":"hpay",
	"value":"",
  "tp":0,
	"validate":1,
	"errmess": "Введите оплату"
  },
  {
	"name":"iswinter",
	"value":"",
  "tp":1,
	"validate":1,
	"errmess": "?"
  },
  {
	"name":"ready_work",
	"value":"",
  "tp":2,
	"validate":1,
	"errmess": "?"
  },
  {
	"name":"aboutme",
	"value":"",
  "tp":0,
	"validate":1,
	"errmess": "Заполните это поле"
  },
  {
	"name":"language",
	"value":"",
  "tp":4,
	"validate":1,
	"errmess": "Выберите язык"
  },
  {
	"name":"lang_level",
	"value":"",
  "tp":4,
	"validate":1,
	"errmess": "Выберите уровень"
  }  
]);

function medLogic()
{
  var val = $("input[name=ismed]:checked").val();
  var idx = 0;
  // check index
    for(var i=0; i<objForm.length;i++)
    {
      if(objForm[i].name=='med') {
          idx = i;
          break;
      }
    }

  if(val=='1') {
    $("#med_block").show();
    objForm[idx].validate=1;
    $("#med").attr("onchange","formValidate()");    
  } else {
    $("#med_block").hide();
    objForm[idx].validate=0;
    $("#med").removeAttr("onchange");
  }
  
  //pause(100);
  formValidate();
}

function mechLogic()
{
  var val = $("input[name=isexpirience]:checked").val();
  var idx = 0;
  // check index
    for(var i=0; i<objForm.length;i++)
    {
      if(objForm[i].name=='mech') {
          idx = i;
          break;
      }
    }

  if(val=='1') {
    $("#mech_block").show();
    objForm[idx].validate=1;
    //$("#mech").attr("onchange","formValidate()");    
  } else {
    $("#mech_block").hide();
    objForm[idx].validate=0;
    //$("#med").removeAttr("onchange");
  }
  
  //pause(100);
  formValidate();
}

function disableMetro()
{
  $('[name=metro]').removeAttr('checked');
}

function test()
{
  //var sel = $("#metro option:selected").val();
  
  var sel = []; 
  $('#metro :selected').each(function(i, selected){ 
  sel[i] = $(selected).val(); 
  });
  
  console.log(sel);
}

function Save()
{

  formValidate();
  if(objForm.total_err==0)
  {
    var val = objForm.param+"photo:"+photo;
    console.log("[save] token:"+token);
    if(token!=undefined)
    {
        // Update
      var xen = encript(val,token);
      link = site_url+"?cmd=UPDATE_RESUME_PROMO&mode=3&uid="+uid+"&value="+xen+"&callback=?";
			jsonp(link, function (data) {
        if(data.value>0)
        {
          showPopUp("Сообщение", "Данные успешно обновлены");          
        } else {
          showPopUp("Ошибка сети", "Запрос не принят! Ошибка сети, либо данные некорректны");
        } 
      });
    } 
    else
    {
      //ShowAuth();
      showPopUp('Нет ключа авторизации','пожалуйста залогиньтесь');
    }

  }	else {
      showPopUp('Валидация формы','Заполнены не все поля!');
  }
}


// .... load metro list
function getListMetro(id)
{
		link = site_url+"?cmd=GET_METRO_LIST&mode=1&id="+id+"&callback=?";
			jsonp(link, function (data) { 
        //alert(data.message+", ip="+ip);
        if(data!=undefined)
        {
          showListMetro(data);
        }
      });
}

function showListMetro(data)
{
  var html = [];
  for(var i=0; i<data.length; i++)
  {
      //html.push('<OPTION VALUE="',data[i].id,'">',data[i].name,'</OPTION>');
      html.push('<label class="checkbox">', data[i].name, '<input type="checkbox" value="', data[i].id, '" name="metro" id="metro_',
      data[i].id, '"/></label>');
  }
  $("#metro_lst").html(html.join(''));  
  if(run_parse) {run_parse=false;parseForm(dataForm);}
}

function getForm_Resume() {
  if(!!uid)
  {
			link = site_url+"?cmd=GET_RESUME_PROMO&uid="+uid+"&callback=?";
			jsonp(link, function (data) {
        dataForm = data;
        // Load Metro List;
        getListMetro(data.id_city);
        //parseForm(data);
      });
   }		
}


function parseForm(data) {
  $("#height").val(data.height);
  setValueObjForm('height',data.height);
  
  $('[name=size][value="' + data.size + '"]').attr('checked',true);
  setValueObjForm('size',data.size);  
  
  $("#twd_start").val(data.work_twd_start);
  setValueObjForm('twd_start',data.work_twd_start);  
  
  $("#twd_end").val(data.work_twd_end);
  setValueObjForm('twd_end',data.work_twd_end);
  
  $("#twe_start").val(data.work_twe_start);
  setValueObjForm('twe_start',data.work_twe_start);
  
  $("#twe_end").val(data.work_twe_end);
  setValueObjForm('twe_end',data.work_twe_end);
  
  
  $('[name=ismed]').removeAttr('checked');
  if(data.id_medical=='0' || data.id_medical=='null')
  {
      setValueObjForm('ismed',2);
      $('[name=ismed][value="2"]').attr('checked',true);
  } else {
      setValueObjForm('ismed',1);
      $('[name=ismed][value="1"]').attr('checked',true);
      $("#med").val(data.med);
      $("#med_block").show();  
  }
  
  
  // METRO
  for(var i=0; i<data.metro.length; i++)
  {
     //$("#metro>option[value='"+data.metro[i]+"']").attr("selected", "selected");
     $('[name=metro][value="'+data.metro[i]+'"]').attr('checked',true);
  }
  
    
  setValueObjForm('isexpirience',parseInt(data.isexpirience));
  var k = parseInt(data.isexpirience);
  if(k==0) k=2; // 0 - No, 1 -Yes
  else k=1;
  $('[name=isexpirience]').removeAttr('checked');
  $('[name=isexpirience][value="'+k+'"]').attr('checked',true);
  
  //var mh = parseInt(data.mech);
  var arr_mech = data.mech.split('-');
  setValueObjForm('mech',arr_mech);


//  if(k==1)
//  {
    // Show Mech
    $("#mech_block").show();
    //var arr_mech = DecodeToBiteN(mh);
    $('[name=mech]').removeAttr('checked');
    for(var i=0; i<arr_mech.length; i++) 
    {
      $('#mech_'+arr_mech[i]).attr('checked',true);
    }
//  }
  
  $('#language [value="'+data.language+'"]').attr('selected',true);
  setValueObjForm('language',data.language);
  
  $('#lang_level [value="'+data.lang_level+'"]').attr('selected',true);
  setValueObjForm('lang_level',data.lang_level);
  
  $('#hpay').val(data.hpay);
  setValueObjForm('hpay',data.hpay);
  
  k = parseInt(data.iswinter);
  if(k==0) k=2; // 0 - No, 1 -Yes
  else k=1;
  $('[name=iswinter]').removeAttr('checked');
  $('[name=iswinter][value="'+k+'"]').attr('checked',true);
  
  // ReadyWork
  //mh = parseInt(data.ready_work);
  //setValueObjForm('ready_work',mh);
  //var iVal = DecodeToBiteN(mh);
  var iVal = data.ready_work.split('-');
  $('[name=ready_work]').removeAttr('checked');
  for(var i=0; i<iVal.length; i++) 
  {
    console.log('=> #ready_work_'+iVal[i]);
    $('#ready_work_'+iVal[i]).attr('checked',true);
  }

  var s = urldecode(data.aboutme);
  $('#aboutme').val(s);
  setValueObjForm('aboutme',s);
  
  s = urldecode(data.email);
  $('#email').val(s);
  setValueObjForm('email',s);
  
  s = urldecode(data.city);
  $("#city").val(s);
  setValueObjForm('city',s);
  
  if(data.photo=="")
      $("#photo").attr({src:"/images/man.png"});
  else
  {      
      if(data.photo.substring(0,1)=='!') {
        $("#photo").attr({src:"http://"+data.photo.substring(1)});
      }
      else {
        $("#photo").attr({src:"/content/"+data.photo});      
      }
      photo = data.photo;
  }
  
  formValidate();
}


	function ajaxFileUpload()
	{
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});

		$.ajaxFileUpload
		(
			{
				url:'/uploads/doajaxfileupload.php',
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
							//alert(data.msg);
							$('#msg').html(data.msg);
              photo = data.name;
              $('#photo').attr("src","/content/"+data.name);
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
  
  
$(function() {	
  $("#city").autocomplete({
    source: function(request,response) {
		var link = site_url+"?cmd=GET_CITY_LIST&mode=1&filter="+request.term+"&callback=?";
			jsonp(link, function (data) { 
        response($.map(data, function(item) {
            return {
              label: item.name,
              value: item.name,
              id: item.id
            }
          })); 
        });
    },
    minLength: 1,
    select: function(event,ui) {
      getListMetro(ui.item.id);
    }
  });
});

</script>
