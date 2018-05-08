<?php  
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD);
echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));
?>
<div id="container_demo" >
  <div id="wrapper">
  <h1>Резюме</h1>
  <div class="pan1" style="height:1046px">
  
  <h3 id="fio"></h3>
  <div class="photo"><img id="photo" src="/images/man.png"/></div>
  
  
  <p>
    <label>Возраст: </label><b id="weight"></b>&nbsp;&nbsp;&nbsp;
    <label>Рост см: </label>
    <b id="height"></b>    
  </p>

  <p>
    <label for="firstname">Размер одежды: </label><br/>
      40(xs)<input type="radio" name="size" value="1" disabled>&nbsp;&nbsp;
      42(s)<input type="radio" name="size" value="2" disabled>&nbsp;&nbsp;
      44(m)<input type="radio" name="size" value="4" disabled>&nbsp;&nbsp;
      46(l)<input type="radio" name="size" value="8" disabled>&nbsp;&nbsp;
      48(xl)<input type="radio" name="size" value="16" disabled>

  </p>

   <p>
     <label for="ch_work_1">Удобный график работы (время): &nbsp;&nbsp;</label><span class="err" id="err_time"></span><br/>
     <table style="width:320px">
     <tr>
        <td><label for="twd_start" data-icon="s">Пн-Пт начало * </label><span class="err" id="twd_start_err"></span>
            <input type="text"  disabled name="twd_start" id="twd_start"  placeholder="9.00" style="width:40px;" class="number"/></td>
        <td><label for="twd_end" data-icon="s">Пн-Пт конец * </label><span class="err" id="twd_end_err"></span>
            <input type="text"  disabled name="twd_end" id="twd_end"  placeholder="18.00" style="width:40px;" class="number"/><td/>
    </tr>
     <tr>
        <td><label for="twe_start" data-icon="s">Сб-Вс начало * </label><span class="err" id="twe_start_err"></span>
            <input type="text"  disabled name="twe_start" id="twe_start" placeholder="11.00" style="width:40px;" class="number"/></td>
        <td><label for="twe_end" data-icon="s">Сб-Вс конец * </label><span class="err" id="twe_end_err"></span>
            <input type="text"  disabled name="twe_end" id="twe_end" placeholder="20.00" style="width:40px;" class="number"/><td/>
    </tr>
    </table>    
  </p>

  <p>
    <label for="ismed">Наличие мед. книжки: </label><b id="ismed"></b>
  </p>
  <p id="med_block" style="display:none">
    <label for="med" >Организация выдавшая книжку: </label><br/>
    <b id="med"></b>    
  </p>
  <div class="clear"></div>
  <hr>

  <p>
    <label>Город: </label>
      <b id="city"></b>   
  </p>

  <p>
    <label>Ближайшая ст. метро: </label><br/>
      <div id="metro"></div>
  </p>
 
 <p style="display:none">
  <label for="birthday">Дата рождения: </label>
  <b id="birthday"></b>
  </p> 
   

</div><!-- pan1 -->  
      
<div class="pan2">
  <p>
    <label>Опыт работы в промо: </label><b id="isexpirience"></b>
  </p>
  <table>
  <tr>
  <td>
  <p id="mech_block">
     <label for="ch_mech_1"><i>Опыт работы на механиках: </i></label><br/>
     <input type="checkbox" id="ch_mech_1" value="1" name="mech" class="mech" disabled/>Раздача листовок<br/>
     <input type="checkbox" id="ch_mech_2" value="2" name="mech" class="mech" disabled/>Сэмплинг<br/>
     <input type="checkbox" id="ch_mech_3" value="3" name="mech" class="mech" disabled/>Дегустация<br/>
     <input type="checkbox" id="ch_mech_4" value="4" name="mech" class="mech" disabled/>Выстаки/Презентации<br/>
     <input type="checkbox" id="ch_mech_5" value="5" name="mech" class="mech" disabled/>HoReCa<br/>
     <input type="checkbox" id="ch_mech_6" value="6" name="mech" class="mech" disabled/>ПЗП<br/>
     <input type="checkbox" id="ch_mech_7" value="7" name="mech" class="mech" disabled/>Консультант<br/>     
  </p>
  </td><td>
    <p>
     <label for="ch_ready_work_1"><i>Механики на которых готовы работать: </i></label><br/>
     <input type="checkbox" id="ch_ready_work_1" value="1" name="ready_work" class="mech" disabled/>Раздача листовок<br/>
     <input type="checkbox" id="ch_ready_work_2" value="2" name="ready_work" class="mech" disabled/>Сэмплинг<br/>
     <input type="checkbox" id="ch_ready_work_3" value="3" name="ready_work" class="mech" disabled/>Дегустация<br/>
     <input type="checkbox" id="ch_ready_work_4" value="4" name="ready_work" class="mech" disabled/>Выстаки/Презентации<br/>
     <input type="checkbox" id="ch_ready_work_5" value="5" name="ready_work" class="mech" disabled/>HoReCa<br/>
     <input type="checkbox" id="ch_ready_work_6" value="6" name="ready_work" class="mech" disabled/>ПЗП<br/>
     <input type="checkbox" id="ch_ready_work_7" value="7" name="ready_work" class="mech" disabled/>Консультант<br/>     
  </p>
 </td></tr></table> 
<hr>

  <p>
    <label>Мин. почасовая оплата: </label><b id="hpay"></b>   
  </p>
  
  <p>
    <label>Готовность работать в зимнее время на улице: </label><b id="iswinter"></b>
  </p>


  <p>
    <label for="language" >Иностранный язык: </label><b id="language"></b>
  </p>
  
  <p>
    <label for="lang_level" >Уровень языка: </label><b id="lang_level"></b>
  </p>
  
  <label>О себе: </label>
  <p id="aboutme" class="show_txt"> 
  
  <p>
  <label>Email: </label>
      <b id="email"></b>
  </p>

  <p>
  <label>Телефон: </label>
      <b id="phone"></b>
  </p>
  

</div><!-- pan2 end -->

  <div class="clear"></div>
  
</div><!-- wrapper end -->
</div><!-- container end -->

  <div class="clear"></div>
<br/><br/>
   	<div class="btn_panel">
			<a href='/site/page/about' class='btn small' style="float:right; padding:0px;" id='btn_cancel_form' >Отмена</a>
      <a href='#' class='btn small' style="float:right; padding:0px;" id='btn_save_form' onclick="Save();">Сохранить</a>
 	</div>

   
<?php echo CHtml::endForm(); ?>

<script type="text/javascript">

var dataForm;
var run_parse=false; // flag of parse form
var photo = '';
<?php
if($vid!='')
{
    echo "vid=$vid;";
}
?>                                

var objForm = (
  {"total_err":0,"param":""},
  [
	{
	"name":"height",
	"value":"",
  "tp":0,
	"validate":1,  
	"errmess": "введите рост"
  }  
  
]);

var metro_list = [];
var lang_list = [
  {"key":"--", "value":"No speak (Не владею)"},
  {"key":"en", "value":"English (Английский)"},
  {"key":"de", "value":"Deutsch (Немецкий)"},
  {"key":"fr", "value":"French (Французкий)"},
  {"key":"pl", "value":"Polski (Польский)"},
  {"key":"zh", "value":"Chinese (Китайский)"},
  {"key":"ja", "value":"Japanese (Японский)"},
  {"key":"ko", "value":"Korean (Корейский)"},
  {"key":"tu", "value":"Turkish (Турецкий)"}
]
  
var lang_level = [
  {"key":0, "value":"No speak (Не владею)"},
  {"key":1, "value":"Beginner, Elementary"},
  {"key":2, "value":"Pre-Intermediate"},
  {"key":3, "value":"Intermediate"},
  {"key":4, "value":"Upper-Intermediate"},
  {"key":5, "value":"Advanced"},
  {"key":6, "value":"Proficiency"}
]


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
  for(var i=0; i<data.length; i++)
  {      
      var m = {"id":data[i].id,"name":data[i].name,};
      metro_list.push(m);
  } 
  if(run_parse) {run_parse=false;parseForm(dataForm);}
}

function getForm_Resume() {
  if(!!uid)
  {
      var val = "id:"+vid;
      console.log('vid:'+vid);
      var xval = encript(val,token);
			link = site_url+"?cmd=GET_RESUME_PROMO&mode=3&value="+xval+"&uid="+uid+"&callback=?";
			jsonp(link, function (data) {
        dataForm = data;
        // Load Metro List;
        run_parse=true;
        getListMetro(data.id_city);
        //parseForm(data);
      });
   }		
}

function parseForm(data) {
  
  $("#fio").html(data.fio);
  $("#height").html(data.height);
  $("#weight").html(data.weight);
  $('[name=size][value="' + data.size + '"]').attr('checked',true);
  $("#twd_start").val(data.work_twd_start);
  $("#twd_end").val(data.work_twd_end);
  $("#twe_start").val(data.work_twe_start);
  $("#twe_end").val(data.work_twe_end);
  
  if(data.id_medical=='null' || data.id_medical=='0' ) 
    $("#ismed").html('Нет');
  else {
    $("#ismed").html('Да');
    $("#med").html(data.med);
    $("#med_block").show();
  }
  
  // METRO
  var hm=[];
  for(var i=0; i<data.metro.length; i++)
  {
     for(var j=0; j<metro_list.length; j++)
     {
        if(data.metro[i]==metro_list[j].id)
        {
          hm.push('<li>',metro_list[j].name,'</li>');
          console.log('metro:'+metro_list[j].name);
          break;
        }
     }
  }
  $("#metro").html('<ul>'+hm.join('')+'</ul>');
  
    
  setValueObjForm('isexpirience',parseInt(data.isexpirience));
  var k = parseInt(data.isexpirience);
  if(k==0) 
      $("#isexpirience").html('Нет');
  else
      $("#isexpirience").html('Да');  

  
  var mh = parseInt(data.mech);
  setValueObjForm('mech',mh);
  if(k==1)
  {
    // Show Mech
    $("#mech_block").show();
    var arr_mech = DecodeToBiteN(mh);
    $('[name=mech]').removeAttr('checked');
    for(var i=0; i<arr_mech.length; i++) 
    {
      $('[name=mech][value="'+arr_mech[i]+'"]').attr('checked',true);
    }
  }
  
  // LANGUAGE
  for(var i=0; i<lang_list.length; i++)
  {
    if(lang_list[i].key==data.language)
    {
      $('#language').html(lang_list[i].value);
      break;
    }
  }

  for(var i=0; i<lang_level.length; i++)
  {
    if(lang_level[i].key==parseInt(data.lang_level))
    {
      $('#lang_level').html(lang_level[i].value);
      break;
    }
  }
  
  $('#hpay').html(data.hpay);
  k = parseInt(data.iswinter);
  if(k==0) 
    $("#iswinter").html('Нет');
  else
    $("#iswinter").html('Да');  
  
  // ReadyWork
  mh = parseInt(data.ready_work);
  setValueObjForm('ready_work',mh);
  var iVal = DecodeToBiteN(mh);
  $('[name=ready_work]').removeAttr('checked');
  for(var i=0; i<iVal.length; i++) 
  {
    $('[name=ready_work][value="'+iVal[i]+'"]').attr('checked',true);
  }

  var s = urldecode(data.aboutme);
  $('#aboutme').html(checkBR(s));
  
  
  $("#email").html('<a href="mailto:'+data.email+'">'+data.email+"</a>");
  $("#phone").html(data.phone);
  
  s = urldecode(data.city);
  $("#city").html(s);
  
  if(data.photo=="" || data.photo==undefined)
      $("#photo").attr({src:"/images/man.png"});
  else
  {      
      $("#photo").attr({src:"/content/"+data.photo});
      photo = data.photo;
  }
  AutoHeight();
}


function AutoHeight()
{
  $(".pan1").css("height","auto");  
  
  var p1 = $(".pan1").height();
  var p2 = $(".pan2").height();

  if(p2>p1) {
    $(".pan1").height(p2);
    $("#list").height(p2-40);
  } else { 
    $(".pan2").height(p1);
    $("#list").height(p1-40);
  }
}  
    
</script>