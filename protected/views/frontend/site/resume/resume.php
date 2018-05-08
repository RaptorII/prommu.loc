<?php  
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD);

Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.coverscroll.min.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerCssFile('/css/photo_gallery.css');

?>
  <h3 id="fio"></h3>

  <div id="container2">
       <img src="/images/man.png" title="title #1"/>
  </div>

  <div class="form-horizontal">

  <div class="accordion" id="acc">

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#coll1" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >Параметры и размеры</a>
    </div>

    <div id="coll1" class="accordion-body collapse in">
      <div class="accordion-inner">
      <?php
        echo FormHelper::ShowField('f_height', Share::lng('RES_HEIGHT'));
      ?>

    <div class="control-group">
      <label class="control-label">Размер одежды </label>
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
      <a href="#coll2" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >Удобное время</a>
    </div>
    <div id="coll2" class="accordion-body collapse">
      <div class="accordion-inner">

        <?php
          echo FormHelper::ShowField('twd_start', 'Пн-Пт начало');
          echo FormHelper::ShowField('twd_end', 'Пн-Пт конец');
          echo FormHelper::ShowField('twe_start', 'Сб-Вс начало');
          echo FormHelper::ShowField('twe_end', 'Сб-Вс конец');
        ?>
      </div>
      </div>
      </div>

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#coll3" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >Удобные районы</a>
    </div>
    <div id="coll3" class="accordion-body collapse">
      <div class="accordion-inner">

        <?php
          echo FormHelper::ShowField('bcity', 'Город');
        ?>
        <p><!--Ближайшая станция метро-->
          <label><?php echo Share::lng('RES_METRO')?>: </label><br>
            <div id="metro"></div>
        </p>

      </div>
      </div>
      </div>



    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#coll4" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >Интересующая работа</a>
    </div>
    <div id="coll4" class="accordion-body collapse">
      <div class="accordion-inner">

        <div class="control-group">
            <label class="control-label">Механики на которых готовы работать</label>
            <div class="controls input-append">
             <label class="checkbox"><?php echo Share::lng('AL_MECH-DISTR')?><input type="checkbox" id="ch_ready_work_1" value="1" name="ready_work" class="mech"/></label><!--Раздача листовок-->
             <label class="checkbox"><?php echo Share::lng('AL_MECH-SAMPLIN')?><input type="checkbox" id="ch_ready_work_2" value="2" name="ready_work" class="mech"/></label><!--Сэмплинг-->
             <label class="checkbox"><?php echo Share::lng('AL_MECH-DEGUSTA')?><input type="checkbox" id="ch_ready_work_3" value="3" name="ready_work" class="mech"/></label><!--Дегустация-->
             <label class="checkbox"><?php echo Share::lng('AL_MECH-EX')?><input type="checkbox" id="ch_ready_work_4" value="4" name="ready_work" class="mech"/></label><!--Выстаки/Презентации-->
             <label class="checkbox"><?php echo Share::lng('AL_MECH-HORECA')?><input type="checkbox" id="ch_ready_work_5" value="5" name="ready_work" class="mech"/></label><!--HoReCa-->
             <label class="checkbox"><?php echo Share::lng('AL_MECH-GWP')?><input type="checkbox" id="ch_ready_work_6" value="6" name="ready_work" class="mech"/></label><!--ПЗП-->
             <label class="checkbox"><?php echo Share::lng('AL_MECH-DISTR')?><input type="checkbox" id="ch_ready_work_7" value="7" name="ready_work" class="mech"/></label><!--Консультант-->
            </div>
        </div>

        <?php
          //Наличие мед. книжки
          echo FormHelper::ShowField('ismed', Share::lng('RES_HBOOK'));
        ?>
        <div id="med_block" style="display:none">
        <?php
          //Организация выдавшая книжку
          echo FormHelper::ShowField('med', Share::lng('RES_ORGBOOK'));
        ?>
        </div>

        <?php
          //Опыт работы в промо
          echo FormHelper::ShowField('isexpirience', Share::lng('RES_EXP'));
          //Мин. почасовая оплата
          echo FormHelper::ShowField('hpay', Share::lng('RES_HPAY'));
          //Готовность работать в зимнее время на улице
          echo FormHelper::ShowField('iswinter', Share::lng('RES_STREET'));
        ?>

      </div>
    </div>
    </div>



    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#coll5" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >О себе</a>
    </div>
    <div id="coll5" class="accordion-body collapse">
      <div class="accordion-inner">

       <div class="control-group">
          <label class="control-label">На каких механиках работали?</label>
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
<?php
          //Иностранный язык
          echo FormHelper::ShowField('language', Share::lng('RES_FLANG'));
          //Уровень языка
          echo FormHelper::ShowField('lang_level', Share::lng('RES_LANG-LEVEL'));
          //О себе
          echo FormHelper::ShowField('aboutme', Share::lng('RES_ABOUT'), 'show_txt');
?>

      </div>
    </div>
    </div>

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#coll6" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >Контакты</a>
    </div>
    <div id="coll6" class="accordion-body collapse">
      <div class="accordion-inner">

<?php
          //Email
          echo FormHelper::ShowField('email', Share::lng('AL_EMAIL'));
          //Phone
          echo FormHelper::ShowField('phone', Share::lng('AL_PHONE'));
?>


      </div>
    </div>
    </div>

</div><!-- end accordion -->

  <br><br>
  <div class="modal-footer">
      <a href='#'  onclick="hideResume()" class='btn btn-inverse' id='btn_cancel_form' ><?php echo Share::lng('AL_BACK')?></a>
 	</div>

</div><!-- end form -->

<!--</div> end span8 -->
<!--</div> end row -->
<script type="text/javascript">

var dataForm;
var run_parse=false; // flag of parse form
var photo = '';
var vid;
var id_user;
var fotorama;

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
  id_user = data.id_user; 
  $("#fio").html(data.fio);
  $("#f_height").text(data.height);
  $("#weight").text(data.weight);
  $('[name=size][value="' + data.size + '"]').attr('checked',true);
  $("#twd_start").text(data.work_twd_start.replace('.',':'));
  $("#twd_end").text(data.work_twd_end.replace('.',':'));
  $("#twe_start").text(data.work_twe_start.replace('.',':'));
  $("#twe_end").text(data.work_twe_end.replace('.',':'));
  
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
  $("#bcity").html(s);
  

  var val = "id:"+vid;
  console.log('vid:'+vid);
  var xval = encript(val,token);
	link = site_url+"?cmd=GET_RESUME_PHOTO&mode=3&value="+xval+"&uid="+uid+"&callback=?";
	jsonp(link, function (data) {
      var html = [];
      for(var i=0; i<data.length; i++)
      {
         html.push('<img src="/content/',data[i].photo,'" title="',(i+1),'"/>');
      }
      $('#container2').html(html.join(''));
      $('#container2').coverscroll();
  });

}

</script>