<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile('/js/jquery-ui-1.10.3.custom.min.js', CClientScript::POS_HEAD);
$docroot = $_SERVER['DOCUMENT_ROOT'];?>

<!-- MODAL WINDOW -->
<div class="modal hide fade" id="Modal" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal"><img src="/images/ico/close_btn_large.png" width="32">
        </button>
        </button>
        <h3 id="myModalLabel">Задать оценку:</h3>
    </div>
    <div class="modal-body">
        <div id="slider-range"></div>
        <label for="amount">Оценка в % от 0 до 100</label>
        <input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold;"/>
        <script>
            $("#slider-range").slider({
                range:"min",
                min:0,
                max:100,
                value:50,
                slide:function (event, ui) {
                    $("#amount").val(ui.value);
                }
            });
            $("#amount").val($("#slider-range").slider("value"));
        </script>
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger" data-dismiss="modal">Закрыть</button>
        <button class="btn btn-primary" data-dismiss="modal" onclick="rat()">Сохранить изменения</button>
    </div>
    <input type="hidden" id="pkid"/>
</div>

<h3>Оценки промоутеров</h3>
<div class="row">
    <div class="span4">
      <div class="photo"><img id="logo_ra" src="" style="width:190;height:auto"/></div>
      <br>

  <p id="info"></p>
  <h3 id="name_ra"></h3>
  <p id="info_ra"></p>
  <div id="list_ra" class="portlet-content"></div>

  </div>

<!-- Vacancy lists block -->
<div class="span8">
  <h3>Проекты</h3>
<?php
echo CHtml::form('/site/vacation','POST',array("id"=>"form"));
?>
  <input type="hidden" name="vid" id="vid" />
  <div id="list" style="overflow-y:auto; height:780px;"></div>
  
  <div id="pan_resume" style="display:none;">
  <?php include_once( $docroot . "/protected/views/frontend/site/resume/resume.php"); ?>
  
</div>

  </div><!-- pan2 end -->
  <div class="clear"></div>

</div></div>

<script type="text/javascript">
<?php Share::PrintMechJson();?>
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
var ra_id;
var vacation_id;
var prj_id;

function getRaInfo()
{
  if(!!uid)
  {
		  link = site_url+"?cmd=GET_RA_INFO&value=&uid="+uid+"&callback=?";
	    jsonp(link, function (data) {                      
         ShowRaInfo(data);
         getAllProjects();
      });
//        showPopUp("сетевая ошибка","Сервер не отвечает или ошибка связи!");
   }	
}

function getAllProjects()
{
  if(!!uid)
  {
      link = site_url+"?cmd=GET_RA_PROJECTS&value=&uid="+uid+"&id="+ra_id+"&callback=?";
	    jsonp(link, function (data) {                      
        parseView(data);
      });
   }	
}


 function showRatingEdit(id) {
    prj_id = id;
  }


function rat(val)
{
  if(!!uid)
  {
      val = val*20;
      var x = "prj_id:"+prj_id+",rating:"+val;
      var xen = encript(x,token);
      link = site_url+"?cmd=SET_RATING&mode=3&value="+xen+"&uid="+uid+"&callback=?";
	    jsonp(link, function (data) {                      
        close_pop_up('#pop-up-rating');
        $("#d_"+prj_id).html('<div style="width: '+val+'%"></div>');
        var txt = val/20+" из 15";
        $("#t_"+prj_id).html(txt);
      });
   }  
}

function ShowRaInfo(data)
{
  ra_id = data.id;
  $('#logo_ra').attr("src","/content/"+data.logo);
  $('#name_ra').text(data.name);
  var info = 'web: <b><a target="_blank" href="http://'+data.web+'">'+data.web+'</a></b><br/>'+
  'email: <b><a href="mailto:'+data.email+'">'+data.email+'</a><hr/>';
  $('#info_ra').html(info);
  AutoHeight();
}

function parseView(data)                                                                                                                
{
  var html = [];
  
  for(var i=0;i<data.length;i++)
  {
/*
      html.push('<div class="block_vac" id="b', data[i].id_jobs,'" style="background:#FFF">');
      html.push('<table border="0">');
      html.push('<tr><td style="width:130px">название акции</td>');
      html.push('<td><b>',urldecode(data[i].name_act),'</b></td></tr>');
      
      html.push('<tr><td>город</td>');
      html.push('<td>',data[i].city,'</td></tr>');
     
      html.push('<tr><td>механика</td>');
      html.push('<td>',ShowMech(data[i].mech),'</td></tr>');
      
      html.push('<tr><td>оплата в час</td>');
      html.push('<td>',data[i].pay,'</td></tr>');
      
      html.push('<tr><td>сроки акции</td>');
      html.push('<td>',data[i].date_begin,' - ',data[i].date_end,'</td></tr>');
      
      html.push('</table>');
      html.push('<div style="display:inline"><a href="#" onclick="viewPromo(',data[i].id_jobs,')" class="btn small">участники</a>');
      html.push('</div><div class="clear"></div>');
      html.push('<div id="p_',data[i].id_jobs,'"></div>');
      html.push('</div>');
*/
      html.push('<div class="block_vac" id="b', data[i].id_jobs, '">');
      html.push('<h4>', urldecode(data[i].name_act), '</h4>');
      html.push('<table border="0">');
      html.push('<tr><td style="width:130px">название акции</td>');
      html.push('<td><b>', urldecode(data[i].name_act), '</b></td></tr>');

      html.push('<tr><td>город</td>');
      html.push('<td>', data[i].city, '</td></tr>');

      html.push('<tr><td>механика</td>');
      html.push('<td>', ShowMech(data[i].mech), '</td></tr>');

      html.push('<tr><td>оплата в час</td>');
      html.push('<td>', data[i].pay, '</td></tr>');

      html.push('<tr><td>сроки акции</td>');
      html.push('<td>', data[i].date_begin, ' - ', data[i].date_end, '</td></tr>');

      html.push('</table>');
      html.push('<div class="productRate" id="d_', data[i].prj_id, '"><div style="width: ', data[i].rating, '%"></div></div>');
      html.push('<div style="display:inline">');
      html.push('<a href="javascript:viewVac(', data[i].id_jobs, ')" class="btn btn-info btn-mini">детали <i class="icon-chevron-down"></i></a>&nbsp;');
      html.push('<a href="javascript:viewPromo(', data[i].id_jobs, ')" class="btn btn-primary btn-mini">участники <i class="icon-chevron-down"></i></a>');
      html.push('</div><div class="clear"></div>');
      html.push('<div id="p_', data[i].id_jobs, '"></div>');
      html.push('</div>');

  }
  
  $("#list").html(html.join(''));
  AutoHeight();
}

function viewVac(id) {
    $("#b" + id + " table").toggle("slow");
}

function viewPromo(id)
{
  link = site_url+"?cmd=GET_RA_PROMO_LIST&value=&id="+id+"&callback=?";
	jsonp(link, function (data) {
    GenerateListPromo(id, data); 
  });		
}

function GenerateListPromo(vac_id, data)
{
  html = [];
  html.push('<ol class="rating">');
  for(var i=0; i<data.length; i++)
  {                            
    html.push('<li><div class="productRate" id="d_',data[i].prj_id,'"><div style="width: ',data[i].rating,'%"></div></div>');
    html.push('<a href="#" onclick="showResume(',data[i].id,', ',vac_id,')">');
    html.push(data[i].lastname,' ',data[i].firstname,'</a> <small><span id="t_',data[i].prj_id,'">',data[i].rating/20,' из 15 </span>');
      html.push('&nbsp;&nbsp;<a href="#Modal" data-toggle="modal" onclick="showRatingEdit(', data[i].prj_id, ')" class="btn btn-success btn-small">изменить</a></small>');
    html.push('</li>');    
  }
  html.push('</ol>');
  $("#p_"+vac_id).html(html.join(''));
}

function AutoHeight(){}

function showResume(id, vac_id)
{
  $("#title_res").hide();
  vid = id;
  vacation_id = vac_id;
/*
  if(isresponse==1)
  {
    $("#btn_accept").show();
  } else {
    $("#btn_accept").hide();  
  }
*/  
  getForm_Resume();
  //$("#list").hide();
  $("#list").css("display","none");
  $("#pan_resume").show(500);
  //AutoHeight(1);
}

function hideResume()
{
  $("#title_res").show();
  $("#pan_resume").hide();
  $("#list").show(500);
  //AutoHeight(0);
}

</script>