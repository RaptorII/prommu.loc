function changeAge(m)
{
      var size = 0;
      if(m==0)
      {
         size = $("#p_age_from").val();
         $("#l_age_from").text(size);
         //$("#age_from").val(size);
         if(parseInt(size) > parseInt($("#p_age_to").val()) ) 
         {
            $("#p_age_to").val(size);
            $("#l_age_to").text(size);
         }
      }
      else
      {
         size = $("#p_age_to").val();
         $("#l_age_to").text(size);
         //$("#age_to").val(size);
         if( parseInt(size) < parseInt($("#p_age_from").val()) ) 
         {
            $("#p_age_from").val(size);
            $("#l_age_from").text(size);
         }         
      }
} 

function Search()
{
  hideResume();
  if(!!uid)
  {
			console.log('city:'+$("#city").val());      
      if($("#city").val()=='') city_id=0;
      var age_from = $("#p_age_from").val();
      var age_to = $("#p_age_to").val();
      var ismale = $("input[name=ismale]:checked").val();
      var iswork_promo = $("input[name=iswork_promo]:checked").val();
      var ismed = $("input[name=fismed]:checked").val();
      var size = 0;
      
      //--size--
      var sel = '';
          $("input[name=fsize]:checked").each(function(i, selected){ 
          sel += '-'+$(selected).val(); 
          });
          if(sel!='')
          {
            size = EncodeBiteToIntN(sel.substring(1));
          }
      
      var val = 'city_id:'+city_id+',age_from:'+age_from+',age_to:'+age_to+',ismale:'+ismale+',size:'+size+',iswork_promo:'+iswork_promo+',ismed:'+ismed;
      var xen = encript(val,token);
      link = site_url+"?cmd=SEARCH_EMPL_VAC&mode=3&value="+xen+"&uid="+uid+"&callback=?";
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
      if(data[i].ispublic==0) {
        html.push('<div class="block_vac" style="opacity:0.5" id="b', data[i].id,'">');
      } else {
        html.push('<div class="block_vac" id="b', data[i].id,'">');
      }

      html.push('<h4>',urldecode(data[i].fio),'</h4>');

      html.push('<table border="0">');
//      html.push('<tr><td style="width:130px">',m_fio,'</td>'); //ФИО
//      html.push('<td><b>',urldecode(data[i].fio),'</b></td></tr>');
      
      html.push('<tr><td style="width:130px">',m_city,'</td>'); //город
      html.push('<td>',urldecode(data[i].city),'</td></tr>');
      
      
      html.push('<tr><td>',m_mech,'</td>'); //механика
      html.push('<td>',ShowMech(data[i].mech),'</td></tr>');
      
      html.push('<tr><td>',m_pay,'</td>'); //оплата в час
      html.push('<td>',data[i].pay,'</td></tr>');
      
      html.push('<tr><td>',m_age,'</td>'); //возраст
      html.push('<td>',data[i].weight,'</td></tr>');
      
      html.push('<tr><td>',m_date,'</td>'); //дата размещения
      html.push('<td>',data[i].date_public,'</td></tr></table>');

      html.push('<div style="display:inline">');
      html.push('<a href="javascript:viewVac(',data[i].id,')" class="btn btn-info btn-mini">детали <i class="icon-chevron-down"></i></a>&nbsp;');
      html.push('<a href="javascript:showResume(',data[i].id,')" class="btn btn-primary btn-mini">анкета <i class="icon-chevron-down"></i></a>');
      html.push('</div><div class="clear"></div>');
      html.push('<div id="p_',data[i].id_jobs,'"></div>');
      html.push('</div>');

  }
  
  $("#list_vac").html(html.join(''));
}

function viewVac(id)
{
  $("#b"+id+" table").toggle("slow");
}

function showResume(id)
{
  //$("#vid").val(id);
  //$("#btn_submit").click();
  $("#title_res").hide();
  vid = id;
  getForm_Resume();
  //$("#list").hide();
  $("#list_vac").css("display","none");
  $("#pan_resume").show(500);
  //AutoHeight(1);
}

function hideResume()
{
  $("#title_res").show();
  $("#pan_resume").hide();
  $("#list_vac").show(500);
  //AutoHeight(0);
}

function view(el)
{
  $("#"+el).toggle(); 
}

function ShowMech(val)
{
  var txt = []
  var arrM = DecodeToBiteN(parseInt(val));
  for(i=0; i<arrM.length; i++)
  {
    txt.push(mechInfo[arrM[i]], '; ');
  }
  return(txt.join(''));
}


$(function() {	
  $("#city").autocomplete({
    source: function(request,response) {

		link = site_url+"?cmd=GET_CITY_LIST&mode=1&filter="+request.term+"&callback=?";
			jsonp(link, function (data) { 
        //alert(data.message+", ip="+ip);
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
      city_id = ui.item.id; 
      //$("#lcountry").text('#'+ui.item.id);
    }
});
});