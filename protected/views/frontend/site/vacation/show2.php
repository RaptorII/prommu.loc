<?php
Yii::app()->getClientScript()->registerScriptFile('http://maps.google.com/maps/api/js?sensor=false', CClientScript::POS_HEAD);
//echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));
//echo '<h3>'.Share::lng('VAC_ADV').'</h3>';
?>
  <h3 id="vacation_name"></h3>

  <div class="accordion" id="acc">
  <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse_c1" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >Вакансия</a>
    </div>
    <div id="collapse_c1" class="accordion-body collapse in">
      <div class="accordion-inner">
    <?php
      echo FormHelper::ShowField('vacation_name2', 'Название вакансии (Акции)');
      echo FormHelper::ShowField('description', 'Описание акции');
      echo FormHelper::ShowField('money', 'Оплата за 1 час');
    ?>
      </div>
    </div>
  </div>

  <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse_c2" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >Требования к кандидату</a>
    </div>
    <div id="collapse_c2" class="accordion-body collapse">
      <div class="accordion-inner">

          <?php
          echo FormHelper::ListCheckBoxDB('mech_ids', 'Механика акции *', 'mech', 0);
?>
        <div class="control-group">
          <label class="control-label">Место работы</label>
          <div class="controls input-append">
           <label class="checkbox"><input type="checkbox" id="ch_work_1"  name="work" class="work" value="1"/>Помещение</label>
           <label class="checkbox"><input type="checkbox" id="ch_work_2"  name="work" class="work" value="2"/>Улица</label>
          </div>
        </div>

        <?php
          echo FormHelper::ShowField('agefrom', 'Возраст от');
          echo FormHelper::ShowField('ageto', 'Возраст до');
          echo FormHelper::ShowField('requirements', 'Требования к промоутеру');
          echo FormHelper::ShowField('responsibility', 'Обязанности');
        ?>
      </div>
    </div>
  </div>

  <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse_c3" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >Контактная информация</a>
    </div>
    <div id="collapse_c3" class="accordion-body collapse">
      <div class="accordion-inner">

        <?php
          echo FormHelper::ShowField('ra_name', 'Название Рекламного агентства');
          echo FormHelper::ShowField('mcity', 'Регион/Город');
          echo FormHelper::ShowField('address', 'Адрес');
        ?>
      <!-- MAP  -->

              <div id="map_canvas" style="margin:10px 0 10px 0" class="lifted">
                   <img src="/images/handyicon.png">
                    <!-- Здесь разместим карту --->
              </div>
        <?php
          echo FormHelper::ShowField('phone', 'Телефон');
          echo FormHelper::ShowField('email', 'Email');
        ?>

      </div>
    </div>
  </div>

  <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapse_c4" class="accordion-toggle" data-toggle="collapse" data-parent="#acc" >Дата и время работы</a>
    </div>
    <div id="collapse_c4" class="accordion-body collapse">
      <div class="accordion-inner">
<?php
  echo FormHelper::ShowField('action_begin', 'начало акции');
  echo FormHelper::ShowField('action_end', 'конец акции');
  echo FormHelper::ShowField('action_money_dt', 'Срок выплаты (не позднее)');
  echo FormHelper::ShowField('grf111', 'График работы', '', 'font-weight: bold');
  echo FormHelper::ShowField('twd_start', 'Пн-Пт начало');
  echo FormHelper::ShowField('twd_end', 'Пн-Пт конец');
  echo FormHelper::ShowField('twe_start', 'Сб-Вс начало');
  echo FormHelper::ShowField('twe_end', 'Сб-Вс конец');
?>

      </div>
    </div>
  </div>

</div><!-- End Accordion -->

<div class="alert" style="display: block;" id="mess_resp">
    Вы уже откликнулись на эту вакансию <b id="dt_resp">1</b>
</div>
  <br><br>
  <div class="modal-footer">
      <a href='#' onclick="hideVac()" class='btn btn-inverse' id='btn_cancel_form' >Назад</a>
      <a href='#' onclick="responseVac()" class='btn btn-primary' id='btn_response' >Откликнуться</a>
 	</div>

  
<?php
//echo CHtml::endForm();
?>

<script type="text/javascript">
var mode_vacation;
var vid;
var id_jobs;
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
  age_to: 0  
};


function responseVac()
{
  if(!!uid)
  {
			link = site_url+"?cmd=RESPONSE_VACATION&value=&uid="+uid+"&id="+id_jobs+"&callback=?";
			jsonp(link, function (data) {              
        if(data.value>0)
        {
          showPopUp("Ответ сервера","Запрос принят");
        } else {
          showPopUp("Ответ сервера","Запрос не принят");        
        }
      });
   }		    
}

function getForm(id) {
  if(!!uid)
  {
			link = site_url+"?cmd=GET_VACATION&value=&uid="+uid+"&id="+id+"&callback=?";
			jsonp(link, function (data) {              
        parseEditForm(data);
      });
   }		  
}

function parseEditForm(data) {
  id_jobs = data.id_jobs;
  $("#email").html('<a href="mailto:'+data.email+'">'+data.email+"</a>");
  $("#phone").html(data.phone1+"; "+data.phone2);
  $("#mcity").html(data.city);
  $("#address").html(data.address);
  $("#agefrom").html(data.age_from);
  $("#ageto").html(data.age_to);
  $("#action_begin").html(data.date_begin);
  $("#action_end").html(data.date_end);
  $("#vacation_name").html(urldecode(data.name_act));
  $("#vacation_name2").html(urldecode(data.name_act));
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
  
  $("#twd_start").html(data.work_twd_start);
  $("#twd_end").html(data.work_twd_end);
  $("#twe_start").html(data.work_twe_start);
  $("#twe_end").html(data.work_twe_end);
  $("#money").html(data.pay);
  $("#fio").html(data.fio);
  $("#action_money_dt").html(data.date_pay);
  $("#description").html(checkBR(urldecode(data.description)));
  $("#requirements").html(checkBR(urldecode(data.req)));
  $("#responsibility").html(checkBR(urldecode(data.resp)));
  SetCheckBoxList(data.mech, 'mech_ids');
  $("input[name=mech_ids]").attr("disabled","disabled");
  $("input[name=work]").attr("disabled","disabled");

  if(data.address!='')
  {
      var text = urldecode(data.name_act) +"\r\n"+ data.phone1+"; "+data.phone2 + "\r\n" + data.email;
      ReadMap(data.city+', '+data.address, text);
  }

  // Buttons block
  if(data.isresponse > 0) {
      $("#btn_response").hide();
      $("#dt_resp").text(data.dt_resp);
      $("#mess_resp").show();
  } else {
      $("#btn_response").show();
      $("#mess_resp").hide();
  }
}


function parseForm(data) {
  $("#email").val(data.email);
  $("#phone").val(data.phone1+"; "+data.phone2);
  vac.id_city = data.id_city;
  vac.ra_id = data.id_ra;
  $("#city").val(data.city);
}



</script>