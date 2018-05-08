<h4 class="bb">ТОП 10 ВАКАНСИЙ</h4>
<div id="vac">
<!--
<table class="top_vac" id="vac">
<tr>
<th style="width:168px">Компания</th>
<th class="ev">Вакансия</th>
<th>Требования</th>
<th class="ev" style="width:94px">Регион</th>
<th style="width:94px">Зарплата</th>
</tr>
-->
<?php

?>
</div>
<!--/table-->
<form id="form" method="post">
<input type="hidden" id="vid" name="vid">
<?php echo CHtml::submitButton('submit',array("style"=>"display:none", "id"=>"btn_submit")); ?>
</form>

<div class="clear"></div>
<script type="text/javascript">

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

	$(function(){
//		$("[rel='tooltip']").tooltip({
//			delay: { show: 100, hide: 500 }
//		});
//		$("[rel='popover']").popover();
	});


function getAllVacation()
{
      var link = site_url+"?cmd=GET_TOP_VACATION&callback=?";
	    jsonp(link, function (data) {
        parseView(data);
      });
}

function parseView(data)
{
  var html = [];
  var yn = ['Нет','Да'];

  //html.push('<tr><th style="width:168px" class="lifted">Компания</th><th class="lifted ev" style="width:254px">Вакансия</th><th class="lifted">Требования</th><th class="ev lifted" style="width:94px">Регион</th><th style="width:94px" class="lifted">Зарплата</th></tr>');
  var tr;
  for(var i=0;i<data.length;i++)
  {
      html.push('<div class="top_vac_card">');
      html.push('<div style="width:140px; height:120px; float: left; position: relative;">');
      html.push('<img src="/content/',data[i].logo, '" height="64" style="max-width:100px;"/>');
      html.push('</div><div class="card_txt">');
      html.push('<b>',urldecode(data[i].name_ra),'</b><br/>');
      html.push('<button class="btn btn-small" rel="popover" data-placement="bottom" title="',urldecode(data[i].name_act),'" data-content="',urldecode(data[i].req),'">Описание вакансии</button>');
      html.push('<p>Город <b>',data[i].city,'</b><br/>');
      html.push(time, ' <b>',data[i].date_begin,' - ',data[i].date_end,'</b><br/>');
      html.push('Возраст <b>',data[i].age_from,' - ',data[i].age_to,'</b></p>');
      html.push('<a href="#" onclick=showVac(',data[i].id_jobs,')>Подробнее</a>');
      //html.push('<p>',checkBR(urldecode(data[i].req)), '</p>');
      html.push('</div>');
      html.push('<div class="card_pay">',data[i].pay,'</div>');
      html.push('</div>');
/*
      if(i%2)
        html.push('<tr>');
      else
       html.push('<tr class="even">');

      html.push('<td style="width:150px"><b>',urldecode(data[i].name_ra),'</b>');
      html.push('<br/><img src="/content/',data[i].logo,
      '" height="64" style="max-width:200px;"/>');
      html.push('<br/><br/>Работодатель:<br/><span class="bld">',data[i].fio,'</span><br/>email: <span class="bld">',data[i].email, '</span><br/>тел. 1: <span class="bld">',data[i].phone1, '</span><br/>тел. 2: <span class="bld">',data[i].phone2,'</span>');
      html.push('</td>');

      html.push('<td class="ev"><h4 class="bb"><a href="#" onclick=showVac(',data[i].id_jobs,')>',urldecode(data[i].name_act),'</a></h4>');

      //сроки акции
      html.push('<table class="bb">');
      if(i%2)
        tr = '<tr>';
      else
        tr = '<tr class="even">';

      html.push(tr,'<td>',time,'</td>');
      html.push('<td class="bld">',data[i].date_begin,' - ',data[i].date_end,'</td></tr>');
      html.push(tr,'<td>работа на улице</td>');
      html.push('<td class="bld">',yn[data[i].iswork_street],'</td></tr>');

      html.push(tr,'<td>возраст</td>');
      html.push('<td class="bld">от ',data[i].age_from,' до ',data[i].age_to,'</td></tr>');
      html.push('</table>');

      html.push('<td class="bld">',checkBR(urldecode(data[i].req)),'</td>');

      // city
      html.push('<td class="ev bld">',data[i].city,'</td>');

      //оплата в час
      html.push('<td><span class="bld">',data[i].pay,'</span> в час</td>');
      html.push('</tr>');
*/
  }

  $("#vac").html(html.join(''));
//  $("[rel='popover']").popover();
}

$(document).ready(function() {
  getAllVacation();
});

function showVac(id)
{
   $("#vid").val(id);
   $("#form").attr("action","/site/VacationShowPublic");
   $("#btn_submit").click();

}
</script>