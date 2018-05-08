<?php  
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.coverscroll.min.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerCssFile('/css/photo_gallery.css');
echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));
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
          echo FormHelper::ShowField('city', 'Город');
        ?>
        <!--Ближайшая станция метро-->
          <label><?php echo Share::lng('RES_METRO')?>: </label><br>
            <div id="metro"></div>


      </div>
      </div>
      </div>



    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#coll4" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >Интересующая работа</a>
    </div>
    <div id="coll4" class="accordion-body collapse">
      <div class="accordion-inner">

          <?php
          echo FormHelper::ListCheckBoxDB('ready_work', 'Механики на которых готовы работать', 'mech', 0);
          //Наличие мед. книжки
          echo FormHelper::ShowField('ismed', Share::lng('RES_HBOOK'));
          echo '<div id="med_block" style="display:none">';

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
          <?php
          echo FormHelper::ListCheckBoxDB('mech', 'На каких механиках работали?', 'mech', 0);
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
          echo FormHelper::ShowField('email', Share::lng('AL_EMAIL'), 'hidden');
          //Phone
          echo FormHelper::ShowField('phone', Share::lng('AL_PHONE'), 'hidden');
?>


      </div>
    </div>
    </div>

</div><!-- end accordion -->

  <br><br>
  <div class="modal-footer">
      <a href='/'  class='btn btn-inverse' id='btn_cancel_form' ><?php echo Share::lng('AL_BACK')?></a>
 	</div>

</div><!-- end form -->

   
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
  GetMemberToken();
  if(!!uid)
  {
      var xval = encript("id:"+vid,token);
      link = site_url+"?cmd=GET_RESUME_PROMO&mode=3&value="+xval+"&uid="+uid+"&callback=?";
      jsonp(link, function (data) {
        dataForm = data;
        // Load Metro List;
        run_parse=true;
        getListMetro(data.id_city);
      });
  }
  else {
      var val = "id:"+vid;
			link = site_url+"?cmd=GET_RESUME_PROMO_PUBLIC&id="+vid+"&callback=?";
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
  $("#f_height").html(data.height);
  $("#weight").html(data.weight);
  $('[name=size][value="' + data.size + '"]').attr('checked',true);
  $("#twd_start").html(data.work_twd_start);
  $("#twd_end").html(data.work_twd_end);
  $("#twe_start").html(data.work_twe_start);
  $("#twe_end").html(data.work_twe_end);
  
  if(data.id_medical=='null' || data.id_medical=='0' ) 
    $("#ismed").html('Нет');
  else {
    $("#ismed").html('Да');
    if(!!uid) {
       $("#med").html(data.med);
       $("#med_block").show();
    }
  }
  
  // METRO (No)
  var hm=[];
  if(!!uid)
  {
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
  }

    
  setValueObjForm('isexpirience',parseInt(data.isexpirience));
  var k = parseInt(data.isexpirience);
  if(k==0) 
      $("#isexpirience").html('Нет');
  else
      $("#isexpirience").html('Да');  

  
  if(k==1)
  {
     // Show Mech
     SetCheckBoxList(data.mech, 'mech');
     $("input[name=mech]").attr("disabled","disabled");
     $("input[name=size]").attr("disabled","disabled");
     $("#mech_block").show();
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
    SetCheckBoxList(data.ready_work, 'ready_work');
    $("input[name=ready_work]").attr("disabled","disabled");


  var s = urldecode(data.aboutme);
  $('#aboutme').html(checkBR(s));
  
  
  s = urldecode(data.city);
  $("#city").html(s);
  

  if(data.photo=="" || data.photo==undefined)
      $("#photo").attr({src:"/images/man.png"});
  else
  {      
      $("#photo").attr({src:"/content/"+data.photo});
      photo = data.photo;
  }

  if(!!uid)
  {
     $("#phone").html(data.phone);
     $("#email").html(data.email);

     $("div .hidden").hide();
  }

  var val = "id:"+vid;
  console.log('vid:'+vid);
  if(photo.substring(0,1)=='!')
    $('#container2').html('<img src="http://'+photo.substring(1)+'" title=""/>');
  else
    $('#container2').html('<img src="/content/'+photo+'" title=""/>');

  if(token!=undefined) {
  var xval = encript(val,token);
	link = site_url+"?cmd=GET_RESUME_PHOTO&mode=3&value="+xval+"&uid="+uid+"&callback=?";
	jsonp(link, function (data) {
    var html = [];
    if(data.length>0) {
      for(var i=0; i<data.length; i++)
      {
         html.push('<img src="/content/',data[i].photo,'" title="',(i+1),'"/>');
      }
      $('#container2').html(html.join(''));
      $('#container2').coverscroll();
    }
  });
  }
  $('#container2').coverscroll();
}


$(document).ready(function() {
  getForm_Resume();
});
    
</script>