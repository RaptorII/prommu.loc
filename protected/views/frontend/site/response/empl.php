<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD); 
$docroot = $_SERVER['DOCUMENT_ROOT'];
?>
<div id="container_demo">
  <div id="wrapper">

<h3 align="center"><?php echo Share::lng('VR_TITLE')?></h3><!--Просмотры и отклики-->
<div id="list" style="overflow-y:auto; height:780px; margin-left:200px;"></div>


  
<div id="pan_resume" style="display:none; 
overflow:auto; 
         /*position: absolute;*/
         bottom:30px;
         margin-right:40px;
         margin-bottom:20px;
         width: 1000px;
         height: 780px;
         top:90px;
">
  <?php include_once( $docroot . "/protected/views/frontend/site/resume/resume.php"); ?>
  
</div>


</div></div>
<script type="text/javascript">
var vacation_id;
<?php
echo '
var actname = "'.Share::lng('VR_ACT').'";
var adv = "'.Share::lng('ANEM_ADV').'"; 
var pay = "'.Share::lng('SREM_PAYMENT').'";
var time = "'.Share::lng('LKPR_TERMS').'";
var visits = "'.Share::lng('VR_VISITS').'";
var resp = "'.Share::lng('VR_RESP').'";
';

?>

function getAllVacation()
{
  if(!!uid)
  {
      link = site_url+"?cmd=GET_ALL_VACATION&value=&uid="+uid+"&callback=?";
	    jsonp(link, function (data) {                      
        parseView(data);
      });
   }	
}

function parseView(data)                                                                                                                
{
  var html = [];
  
  for(var i=0;i<data.length;i++)
  {

    if(data[i].ispublic>0) {
      html.push('<div class="block_vac" style="background:#FFF;" id="b', data[i].id_jobs,'">');
      html.push('<h4>', urldecode(data[i].name_act), '</h4>');
      html.push('<table border="0">');
      //название акции
      html.push('<tr><td style="width:100px">',actname,'</td>');
      html.push('<td style="width:150px"><b>',urldecode(data[i].name_act),'</b></td>');
      
      html.push('<td style="width:100px">',adv,'</td>');
      html.push('<td>',urldecode(data[i].name_ra),'</td></tr>');

      //оплата в час
      html.push('<tr><td>',pay,'</td>');
      html.push('<td>',data[i].pay,'</td>');
      
      //сроки акции
      html.push('<td>',time,'</td>');
      html.push('<td>',data[i].date_begin,' - ',data[i].date_end,'</td></tr>');
      
      html.push('<tr>');
      //просмотров
      if(data[i].cnt_view==0)
        html.push('<td>',visits,'</td>');
      else
        html.push('<td><a href="javascript:viewPromo(0,',data[i].id_jobs,')">',visits,'</a></td>');
      
      html.push('<td>',data[i].cnt_view,'</td>');
      
      if(data[i].cnt_resp==0)
        html.push('<td>',resp,'</td>');
      else
        html.push('<td><a href="javascript:viewPromo(1,',data[i].id_jobs,')">',resp,'</a></td>');
              
      html.push('<td>',data[i].cnt_resp,'</td></tr>');
      
      html.push('<tr><td colspan="2" style="vertical-align:top"><div id="p0_',data[i].id_jobs,'"></div></td><td colspan="2" style="vertical-align:top"><div id="p1_',data[i].id_jobs,'"></div></td></tr>');
      html.push('</table>');

        html.push('<div style="display:inline">');
        html.push('<a href="javascript:viewVac(', data[i].id_jobs, ')" class="btn btn-info btn-mini">детали <i class="icon-chevron-down"></i></a>&nbsp;');
        html.push('</div><div class="clear"></div>');

      html.push('</div>');
     }

/*
      console.log("block_vac: "+i);
      html.push('<div class="block_vac" id="b', data[i].id_jobs, '">');
      html.push('<h4>', urldecode(data[i].name_act), '</h4>');
      html.push('<table border="0">');
      html.push('<tr><td style="width:130px">название акции</td>');
      html.push('<td><b>', urldecode(data[i].name_act), '</b></td></tr>');

      html.push('<tr><td>город</td>');
      html.push('<td>', data[i].city, '</td></tr>');

      html.push('<tr><td>оплата в час</td>');
      html.push('<td>', data[i].pay, '</td></tr>');

      html.push('<tr><td>сроки акции</td>');
      html.push('<td>', data[i].date_begin, ' - ', data[i].date_end, '</td></tr>');

      html.push('</table>');
      html.push('<div style="display:inline">');
      html.push('<a href="javascript:viewVac(', data[i].id_jobs, ')" class="btn btn-info btn-mini">детали <i class="icon-chevron-down"></i></a>&nbsp;');
      html.push('<a href="javascript:viewPromo(', data[i].id_jobs, ')" class="btn btn-primary btn-mini">участники <i class="icon-chevron-down"></i></a>');
      html.push('</div><div class="clear"></div>');
      html.push('<div id="p_', data[i].id_jobs, '"></div>');
      html.push('</div>');
*/
  }
  
  $("#list").html(html.join(''));
}

function viewVac(id) {
    $("#b" + id + " table").toggle("slow");
}

function viewPromo(mode, id)
{
  link = site_url+"?cmd=RESPONSE_PROMO_LIST&value=&id="+id+"&mode="+mode+"&callback=?";
	jsonp(link, function (data) {
    //alert(data[0].firstname);
    GenerateListPromo(id, data, mode); 
  });		
}

function GenerateListPromo(vac_id, data, mode)
{
  html = [];
  html.push('<ol>');
  for(var i=0; i<data.length; i++)
  {
    html.push('<li><a href="#" onclick="showResume(',data[i].id,', ',data[i].isresponse,', ',vac_id,')">');
    if(data[i].isresponse==1)
      html.push('<b>',data[i].lastname,' ',data[i].firstname,'</b></a>');      
    else
    if(data[i].isresponse==3)
    {
      html.push('<b class="red">',data[i].lastname,' ',data[i].firstname,'</b></a>');
      html.push('&nbsp;&nbsp;<a href="#" id="tk',data[i].id_promo,'" onclick="takeIn(',data[i].id_promo,', ',vac_id,')">зачислить в штат</a>');
    }
    else
      html.push(data[i].lastname,' ',data[i].firstname,'</a>');
      
    //if(data[i].isresponse==3)
      
      
    html.push('</li>');    
  }
  html.push('</ol>');
  $("#p"+mode+"_"+vac_id).html(html.join(''));
}

function acceptResume()
{
  if(!!uid)
  {
    var val = "id:"+id_user+",vac:"+vacation_id;
    var xen = encript(val,token);
    link = site_url+"?cmd=ACCEPT_RESUME&mode=3&value="+xen+"&uid="+uid+"&callback=?";
	  jsonp(link, function (data) {
    if(data.value==1)
    {
      showPopUp('','Промоутеру отправлен запрос');
    }
    });
  }		
}

function takeIn(id, vac_id)
{
//  alert(resume_id);
  if(!!uid)
  {
    var val = "id:"+id+",vac:"+vac_id;
    var xen = encript(val,token);
    link = site_url+"?cmd=ACCEPT_RESUME&mode=3&value="+xen+"&uid="+uid+"&callback=?";
	  jsonp(link, function (data) {
    if(data.value==1)
    {
      $("#tk"+id).hide();
      showPopUp('','Промоутеру отправлено уведомление');
    }
    });
  }		
  
}

function showResume(id, isresponse, vac_id)
{
  $("#title_res").hide();
  vid = id;
  vacation_id = vac_id;
  if(isresponse==1)
  {
    $("#btn_accept").show();
  } else {
    $("#btn_accept").hide();  
  }
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