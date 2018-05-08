function getEmplInfo()
{
  if(!!uid)
  {
		  link = site_url+"?cmd=GET_LK_EMPLOYER&value=&uid="+uid+"&callback=?";
	    jsonp(link, function (data) {                      
         ShowEmplInfo(data, true);
         getAllVacation();
      });
   }	
}
  
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

function getAgencySearch()
{
  if(!!uid)
  {
			var val = 'filter:'+$("#agency").val();
      var xen = encript(val,token);
      link = site_url+"?cmd=SEARCH_RA&mode=3&value="+xen+"&uid="+uid+"&callback=?";
			jsonp(link, function (data) {                      
        showAgencyList(data);
      });
   }	
}


function ShowEmplInfo(data, isfull)
{
   if(isfull)
   {
      var info = data.fio+'<br/>тел.: '+data.phone+'<br/>email: <a href="mailto:'+data.email+'">'+data.email+'</a>'; 
      $("#info").html(info);
      $('#photo').attr("src","/content/"+data.photo);
   }
   var html = [];
   html.push('<ul>');
   for(var i=0; i<data.ra.length; i++)
   {
      html.push('<li class="span2">',
      '<a target="_blank" href="http://',data.ra[i].web,
      '" title="',data.ra[i].name_ra,
      '"><img src="/content/',data.ra[i].logo,'"/></a><br/><b>',
      data.ra[i].name_ra,'</b>');
      html.push('<div class="productRate"><div style="width: ',data.ra[i].rating,'%"></div></div>','рейтинг <b>',data.ra[i].rating/20, '</b> из 15');
      html.push('</li>');
   }
   html.push('</ul>');
   $("#list_ra").html(html.join(''));
   
   //AutoHeight();
}

function parseView(data)                                                                                                                
{
  var html = [];
  for(var i=0;i<data.length;i++)
  {
      if(data[i].ispublic==0) {
        html.push('<div class="block_vac" style="opacity:0.5" id="b', data[i].id_jobs,'">');
      } else {
        html.push('<div class="block_vac" id="b', data[i].id_jobs,'">');
      }

      html.push('<h4>',urldecode(data[i].name_act),'</h4>');
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
      html.push('<div style="display:inline">');
      html.push('<a href="javascript:viewVac(',data[i].id_jobs,')" class="btn btn-info btn-mini">детали <i class="icon-chevron-down"></i></a>&nbsp;');
      html.push('<a href="javascript:editVac(',data[i].id_jobs,')" class="btn btn-primary btn-mini">',mes7,' <i class="icon-chevron-down"></i></a>&nbsp;');
      html.push('<a href="javascript:showVac(',data[i].id_jobs,')" class="btn btn-primary btn-mini">',mes8,' <i class="icon-chevron-down"></i></a>&nbsp;');
      html.push('<a href="javascript:disableVac(',data[i].id_jobs,')" class="btn btn-inverse btn-mini">',mes9,' <i class="icon-chevron-down"></i></a>&nbsp;');
      html.push('</div><div class="clear"></div>');
      html.push('<div id="p_',data[i].id_jobs,'"></div>');
      html.push('</div>');
  }
/*
  for(var i=0;i<data.length;i++)
  {
      if(data[i].ispublic==0) {
        html.push('<div class="block_vac" style="opacity:0.5" id="b', data[i].id_jobs,'">');      
      } else {
        html.push('<div class="block_vac" id="b', data[i].id_jobs,'">');
      }
      html.push('<table border="0">');
      html.push('<tr><td style="width:130px">',mes1,'</td>');
      html.push('<td><b>',urldecode(data[i].name_act),'</b></td></tr>');
      
      html.push('<tr><td>',mes2,'</td>');
      html.push('<td>',urldecode(data[i].name_ra),'</td></tr>');
      
      
      html.push('<tr><td>',mes4,'</td>');
      html.push('<td>',ShowMech(data[i].mech),'</td></tr>');
      
      html.push('<tr><td>',mes5,'</td>');
      html.push('<td>',data[i].pay,'</td></tr>');
      
      html.push('<tr><td>',mes6,'</td>');
      html.push('<td>',data[i].date_begin,' - ',data[i].date_end,'</td></tr>');
      
      html.push('<tr><td>',mes3,'</td>');
      html.push('<td>',data[i].date_public,'</td></tr></table>');
      html.push('</p><div style="display:inline"><a href="#" onclick="editVac(',data[i].id_jobs,')" class="btn small">',mes7,'</a>');
      if(data[i].ispublic==1)
        html.push('<a href="#" class="btn small" onclick="disableVac(',data[i].id_jobs,')" id="btn',data[i].id_jobs,'">',mes9,'</a>');
      
      html.push('<a href="#" class="btn small" onclick="showVac(',data[i].id_jobs,')" id="btn',data[i].id_jobs,'">',mes8,'</a>');
      html.push('</div><div class="clear"></div>');
      html.push('</div>');
  }
*/
  $("#list_vac").html(html.join(''));
  //AutoHeight();
}


function viewVac(id)
{
  $("#b"+id+" table").toggle("slow");
}

/*
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
*/

function editVac(id)
{
  $("#vid").val(id);
  $("#btn_submit").click();
}

function showVac(id)
{
/*
  $("#vid").val(id);
  $("#form").removeAttr("action");
  $("#form").attr("action","/site/vacationShow");
  $("#btn_submit").click();
*/
  $("#title_res").hide();
  vid = id;
  getForm(id);
  //getForm_Resume();
  $("#list_vac").css("display","none");
  $("#btn_response").hide();
  $("#vacancy").show(500);

}

function hideVac()
{
  $("#title_res").show();
  $("#vacancy").hide();
  $("#list_vac").show(500);
}


function disableVac(id)
{
  // UnPublic vacation
  var val = "id:"+id;
  var xen = encript(val,token);
  link = site_url+"?cmd=UNPUBLIC_VACATION&mode=3&uid="+uid+"&value="+xen+"&callback=?";

  		jsonp(link, function (data) {
        if(data.value>0)
        {
          $("#b"+id).css("opacity","0.5");
          $("#btn"+id).css("display","none");
          showPopUp("Сообщение", "Вакансия снята с публикации");
        } else {
          alert("Ошибка связи!");
        } 
      }); 
}

function showAgencyPanel()
{
  $("#btn_create_job").hide();
  $("#btn_back").show();
  $("#list_vac").hide(500);
  $("#searchAgency").show(500);
}

function back()
{
  $("#btn_create_job").show();
  $("#btn_back").hide();
  $("#searchAgency").hide(500);
  $("#list_vac").show(500);
  //AutoHeight();
}

function addAgency(id)
{
  if(!!uid)
  {
			var val = 'id:'+id;
      var xen = encript(val,token);
      link = site_url+"?cmd=JOIN_RA&mode=3&value="+xen+"&uid="+uid+"&callback=?";
			jsonp(link, function (data) {
        $("#r"+id).hide();                      
        ShowEmplInfo(data, false);
      });
   }
}

function showAgencyList(data)
{
  var html=[];
  for(var i=0;i<data.length;i++)
  {
    html.push('<div class="block_vac" id="b', data[i].id_ra,'">');
    html.push('<table><tr id="r',data[i].id_ra,'">');
    html.push('<td width="250">',data[i].name,'<br/>');
    html.push('<a href="',data[i].web,' target="_blank">',data[i].web,'</a><br/>');
    html.push('<a href="#" class="btn small" onclick="addAgency(',data[i].id_ra,')">',txt_join,'</a></td>');
    html.push('<td><a target="_blank" href="http://',data[i].web,
      '" title="',data[i].name,
      '"><img src="/content/',data[i].logo,
      '" height="64" style="max-width:200px;"/></a></td>');    
    html.push('</tr></table></div');
  }
  $("#list_search_ra").html(html.join(''));
}
