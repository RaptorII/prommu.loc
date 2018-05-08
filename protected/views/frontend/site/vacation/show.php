<?php
echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));
?>
<div id="container_demo" >
  <div id="wrapper">
    <h1>Просмотр вакансии</h1>
  <div class="pan1">
  <p>
      <h3 id="vacation_name" ></h3>
  </p>
  
  
  <p>
    <label for="ra" >Рекламное агентство: </label>
    <b id="ra_name" ></b>
  </p>

  <p>
      <label>Начало акции: </label>
      <b id="action_begin" ></b>
  <p>      
      <label>Конец акции: </label>
      <b id="action_end" ></b>
  </p>

  <p>
      <label>Город: </label>
      <b id="city"></b>   
  </p>
  
  <p>
     <label for="ch_mech_1">Механика акции: </label><br/>
     <input type="checkbox" id="ch_mech_1" onchange="scanMech()" class="mech" disabled/>Раздача листовок<br/>
     <input type="checkbox" id="ch_mech_2" onchange="scanMech()" class="mech" disabled/>Сэмплинг<br/>
     <input type="checkbox" id="ch_mech_3" onchange="scanMech()" class="mech" disabled/>Дегустация<br/>
     <input type="checkbox" id="ch_mech_4" onchange="scanMech()" class="mech" disabled/>Выстаки/Презентации<br/>
     <input type="checkbox" id="ch_mech_5" onchange="scanMech()" class="mech" disabled/>HoReCa<br/>
     <input type="checkbox" id="ch_mech_6" onchange="scanMech()" class="mech" disabled/>ПЗП<br/>
     <input type="checkbox" id="ch_mech_7" onchange="scanMech()" class="mech" disabled/>Консультант<br/>     
  </p>
  
  <p>
     <label for="ch_work_1">Место работы: </label><br/>
     <input type="checkbox" id="ch_work_1" onchange="scanWork()"  disabled class="work"/>Помещение<br/>
     <input type="checkbox" id="ch_work_2" onchange="scanWork()"  disabled class="work"/>Улица<br/>
  </p>
  
  <p>
     <label for="ch_work_1">График работы (время): &nbsp;&nbsp;</label><br/>
     <table style="width:300px">
     <tr>
        <td><label for="twd_start" data-icon="s">Пн-Пт начало</label>
            <input type="text" disabled name="twd_start" id="twd_start" onchange="checkTime()" placeholder="9.00" style="width:40px;" class="nm"/></td>
        <td><label for="twd_end" data-icon="s">Пн-Пт конец</label>
            <input type="text" disabled name="twd_end" id="twd_end" onchange="checkTime()" placeholder="18.00" style="width:40px;" class="nm"/><td/>
    </tr>
     <tr>
        <td><label for="twe_start" data-icon="s">Сб-Вс начало</label>
            <input type="text" disabled name="twe_start" id="twe_start" onchange="checkTime()" placeholder="11.00" style="width:40px;" class="nm"/></td>
        <td><label for="twe_end" data-icon="s">Сб-Вс конец</label>
            <input type="text" disabled name="twe_end" id="twe_end" onchange="checkTime()"  placeholder="20.00" style="width:40px;" class="nm"/><td/>
    </tr>
    </table>    
  </p>
  <hr>
  
  <p>
  <label for="money">Оплата за 1 час: </label> 
      <b id="money"/></b>
  </p>
  <p>
      <label>Срок выплаты (не позднее):</label>
      <b id="action_money_dt"></b>
  </p>

  <p>
  <label >Возраст от: </label>
      <b id="age_from" ></b>
  </p>

  <p>
  <label >Возраст до: </label>
      <b id="age_to" ></b>
  </p>
  
  <p>
  <label >Контактный телефон: </label>
      <b id="phone" ></b>
  </p>
  
  <p>
  <label >Контактное лицо: </label>
      <b id="fio" ></b>
  </p>

  <p>
  <label>Email: </label>
      <b id="email"></b>
  </p>
  
  </div><!-- end panel -->
  <div class="pan1">
 
  <p>
      <label><h4>Описание акции: </h4></label>
      <p class="show_txt" id="description"></p>
      <hr/>            
  </p>
  
  <p>
      <label><h4>Требования к промоутеру: </h4></label>
      <p class="show_txt" id="requirements"></p>
      <hr/>                        
  </p>
  
  <p>
      <label><h4>Обязанности: </h4></label>
      <p class="show_txt" id="responsibility"></p>
      <hr/>                                    
  </p>
  
  </div><!-- end panel --> 
   
  </div>  
  
  <div class="clear"></div>
  </div>
  <br/><br/>
  <div class="btn_panel">
			<a href='/site/VacationList' class='btn small' style="float:right; padding:0px;" id='btn_cancel_form' >Назад</a>
 	</div>

  
<?php echo CHtml::endForm(); ?>

<script type="text/javascript">
var mode_vacation;
var vid;
var vac = {
  vacation_name: "",
  action_begin: "",
  action_end: "",
  action_money_dt: "",
  email: "",
  phone: "",
  city: 0,
  mech_ids: "",
  description: "",
  requirements: "",
  responsibility: "",
  iswork_room: 0,
  iswork_street: 0,
  money: 0,
  ra_id: 0,
  age_from: 0,
  age_to:0  
};


function getForm(id) {
  //alert(1);
  if(!!uid)
  {
			link = site_url+"?cmd=GET_VACATION&value=&uid="+uid+"&id="+id+"&callback=?";
			jsonp(link, function (data) {              
        parseEditForm(data);
      });
   }		  
}

function parseEditForm(data) {
console.log('date_begin:'+data.date_begin);
  $("#email").html('<a href="mailto:'+data.email+'">'+data.email+"</a>");
  $("#phone").html(data.phone1+"; "+data.phone2);
  //vac.ra_id = data.id_ra;
  $("#city").html(data.city);
  $("#age_from").html(data.age_from);
  $("#age_to").html(data.age_to);

  
  $("#action_begin").html(data.date_begin);
  $("#action_end").html(data.date_end);
  $("#vacation_name").html(urldecode(data.name_act));
  $("#ra_name").html(urldecode(data.name_ra));
  vac.ra_id = data.id_ra;
  vac.iswork_room=data.iswork_room;
  vac.iswork_street=data.iswork_street; 
  if(data.iswork_room=='1')
  {
     $("#ch_work_1").attr("checked",true);     
  }
  if(data.iswork_street=='1') {
     $("#ch_work_2").attr("checked",true);
  }
  
  $("#twd_start").val(data.work_twd_start);
  $("#twd_end").val(data.work_twd_end);
  $("#twe_start").val(data.work_twe_start);
  $("#twe_end").val(data.work_twe_end);
  $("#money").html(data.pay);
  $("#fio").html(data.fio);
  $("#action_money_dt").html(data.date_pay);
  $("#description").html(checkBR(urldecode(data.description)));
  $("#requirements").html(checkBR(urldecode(data.req)));
  $("#responsibility").html(checkBR(urldecode(data.resp)));
  
  var elm = DecodeToBite(data.mech);
  for(var i=0; i<elm.length; i++) {
     $("#ch_mech_"+elm[i]).attr("checked",true);
  }
  
}


function parseForm(data) {
  $("#email").val(data.email);
  $("#phone").val(data.phone1+"; "+data.phone2);
  vac.id_city = data.id_city;
  vac.ra_id = data.id_ra;
  $("#city").val(data.city);
}


<?php
if($vid!='')
{
    echo "vid=$vid;";
}
?>                                

</script>