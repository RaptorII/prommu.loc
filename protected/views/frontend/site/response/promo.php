<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD); 
$docroot = $_SERVER['DOCUMENT_ROOT'];
?>
<div id="container_demo">
  <div id="wrapper">

<h3 align="center">Отклики по вакансиям</h3>
<div id="mess_w" class="mess_line" style="display:none">Нет откликов и уведомлений</div>
<div id="list" style="overflow-y:auto; height:780px; margin-left:200px;"></div>


  
<div id="pan_vacation" style="display:none;">
  <?php include_once( $docroot . "/protected/views/frontend/site/vacation/show2.php"); ?>
  
</div>


</div></div>
<script type="text/javascript">
var vacation_id;
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
var ra_id=0;


function getAllVacation()
{
  if(!!uid)
  {
      link = site_url+"?cmd=GET_ALL_VACATION_P&value=&uid="+uid+"&callback=?";
	    jsonp(link, function (data) {                      
        parseView(data);
      });
   }	
}

function parseView(data)                                                                                                                
{
  var html = [];
  
  if(data.length==0)
  {
    $("#mess_w").show();
    return;
  }
  
  for(var i=0;i<data.length;i++)
  {
      if(data[i].ispublic>0) {            
      html.push('<div class="block_vac" style="background:#FFF;" id="b', data[i].id_jobs,'">');
      html.push('<table border="0">');
      html.push('<tr><td style="width:100px">название акции</td>');
      html.push('<td style="width:150px"><b>',urldecode(data[i].name_act),'</b></td>');
      
      html.push('<td style="width:100px">рекл. агентство</td>');
      html.push('<td>',urldecode(data[i].name_ra),'</td></tr>');
      
      
//      html.push('<tr><td>механика</td>');
//      html.push('<td>',ShowMech(data[i].mech),'</td></tr>');
      
      html.push('<tr><td>оплата в час</td>');
      html.push('<td>',data[i].pay,'</td>');
      
      html.push('<td>сроки акции</td>');
      html.push('<td>',data[i].date_begin,' - ',data[i].date_end,'</td></tr>');
      
      html.push('<tr><td>дата публикации</td>');
      html.push('<td>',data[i].date_public,'</td>');
      
      html.push('<td>контакт. лицо</td>');
      html.push('<td>',data[i].fio,'</td></tr>');
      
      html.push('<tr><td>email</td>');
      html.push('<td>',data[i].email,'</td>');
      
      html.push('<td>телефон</td>');
      html.push('<td>',data[i].phone1,'; ',data[i].phone2,'</td></tr>');

      html.push('</table>');

      
      html.push('<div style="display:inline"><a href="#" onclick="showVac(',data[i].id_jobs,')" class="btn btn-info btn-mini">просмотр</a> ');
      if(data[i].isresponse==2)
      {
        html.push('<a href="#" class="btn btn-success btn-mini" onclick="acceptOrg(',data[i].id_jobs,')" id="btn',data[i].id_jobs,'">добавить данные в органайзер</a>');
        html.push('<img src="/images/ico/anm.gif" /><strong>Поздравляем, вы зачислены в штат</strong></h3>');
      }
      else
        html.push('<a href="#" class="btn small" onclick="acceptVac(',data[i].id_jobs,')" id="btn',data[i].id_jobs,'">подтвердить</a>');
      html.push('</div>');
      console.log("isresponse:"+data[i].isresponse);
      }
  }
  
  $("#list").html(html.join(''));
}

function viewPromo(mode, id)
{
  link = site_url+"?cmd=RESPONSE_PROMO_LIST&value=&id="+id+"&mode="+mode+"&callback=?";
	jsonp(link, function (data) {
    GenerateListPromo(id, data, mode); 
  });		
}


function acceptVac(id)
{
  if(!!uid)
  {
    var val = "vac:"+id;
    var xen = encript(val,token);
    link = site_url+"?cmd=ACCEPT_VAC3&mode=3&value="+xen+"&uid="+uid+"&callback=?";
	  jsonp(link, function (data) {
    if(data.value==1)
    {
      showPopUp('','Работодателю отправлен запрос');
      getAllVacation();
    }
    });
  }		
}


function acceptOrg(id)
{
  if(!!uid)
  {
    var val = "vac:"+id;
    var xen = encript(val,token);
    link = site_url+"?cmd=ACCEPT_VAC5&mode=3&value="+xen+"&uid="+uid+"&callback=?";
	  jsonp(link, function (data) {
    if(data.value==1)
    {
      //showPopUp('','Работодателю отправлен запрос');
      //getAllVacation();
      location.href="/site/Organizer/";
    }
    });
  }		
}


function showVac(id)
{
  $("#title_res").hide();
  vid = id;
  $("#btn_response").hide();
  getForm(id);
  $("#list").css("display","none");
  $("#pan_vacation").show(500);
}

function hideVac()
{
  $("#title_res").show();
  $("#pan_vacation").hide();
  $("#list").show(500);
}


</script>