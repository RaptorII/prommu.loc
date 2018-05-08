<?php
Yii::app()->getClientScript()->registerScriptFile('http://maps.google.com/maps/api/js?sensor=false', CClientScript::POS_HEAD);
echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));
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
          // Должность (new)
      echo FormHelper::ListRadioDB('app', 'Должность *', 'appointment', 9);

          // Тип занятости (new)
      echo FormHelper::ListRadioMemo('empl_type', 'Тип занятости *', Share::$empl_type, 0);

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
              <a href="#collapse10" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Узкие
                  требования</a>
          </div>
          <div id="collapse10" class="accordion-body collapse">
              <div class="accordion-inner">
                  <?php
                  echo FormHelper::ListRadioDB('narrow_req', 'Узкие требования *', 'narrow_req', 0);
                  echo FormHelper::ListCheckBoxDB('jobs_lang', 'Знание языка *', 'jobs_lang', 0);
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
          echo FormHelper::ShowField('address', 'Адрес', 'hidden');
        ?>
      <!-- MAP  -->

              <div id="map_canvas" style="margin:10px 0 10px 0" class="lifted">
                   <img src="/images/handyicon.png">
                    <!-- Здесь разместим карту --->
              </div>
        <?php
          echo FormHelper::ShowField('phone', 'Телефон', 'hidden');
          echo FormHelper::ShowField('email', 'Email', 'hidden');
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


  <br><br>
  <div class="modal-footer">
      <a href='/'  class='btn btn-inverse' id='btn_cancel_form' >Назад</a>
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
			link = site_url+"?cmd=GET_VACATION_PUBLIC&value=&id="+id+"&callback=?";
			jsonp(link, function (data) {              
        parseForm(data);
      });
}

function parseForm(data) {
console.log('11111111 date_begin:'+data.date_begin);
  //vac.ra_id = data.id_ra;
  $("#mcity").html(data.city);
  $("#address").html(data.address);
  $("#age_from").html(data.age_from);
  $("#age_to").html(data.age_to);

  
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
  
  $("#twd_start").val(data.work_twd_start);
  $("#twd_end").val(data.work_twd_end);
  $("#twe_start").val(data.work_twe_start);
  $("#twe_end").val(data.work_twe_end);
  $("#money").html(data.pay);

  $("#action_money_dt").html(data.date_pay);
  $("#description").html(checkBR(urldecode(data.description)));
  $("#requirements").html(checkBR(urldecode(data.req)));
  $("#responsibility").html(checkBR(urldecode(data.resp)));

  if(!!uid)
  {
     $("#fio").html(data.fio);
     $("#email").html('<a href="mailto:'+data.email+'">'+data.email+"</a>");
     $("#phone").html(data.phone1+"; "+data.phone2);
     $("div .hidden").hide();
  }

  /*
  var elm = DecodeToBite(data.mech);
  for(var i=0; i<elm.length; i++) {
     $("#ch_mech_"+elm[i]).attr("checked",true);
  }
  */


  if(data.address!='')
  {
      var text;
      if(!!uid)
      {
         text = urldecode(data.name_act) +"\r\n"+ data.phone1+"; "+data.phone2 + "\r\n" + data.email;
      } else {
         text = urldecode(data.name_act);
      }
      ReadMap(data.city+', '+data.address, text);
  }

  $("#app_" + data.appoint).attr('checked', true);
  SetDisableList('app', data.appoint);
/*
  $("*[name=lb_app]").hide();
  $("#lb_app_" + data.appoint).show();
  $("input[name=app]:checked").show();
  $("input[name=app]:checked").attr("disabled","disabled");
*/

  $("#empl_type_" + data.empl_type).attr('checked', true);
  SetDisableList('empl_type', data.empl_type);
  /*
  $("*[name=lb_empl_type]").hide();
  $("#lb_empl_type_" + data.empl_type).show();
  $("input[name=empl_type]:checked").show();
  $("input[name=empl_type]:checked").attr("disabled","disabled");
*/




    $("#narrow_req_" + data.narrow_req).attr('checked', true);
    SetDisableList('narrow_req', data.narrow_req);
    /*
    $("*[name=narrow_req]").hide();
    $("*[name=lb_narrow_req]").hide();
    $("#lb_narrow_req_" + data.narrow_req).show();
    $("input[name=narrow_req]:checked").show();
    $("input[name=narrow_req]:checked").attr("disabled","disabled");
    */

    $("*[name=jobs_lang]").hide();
    $("*[name=lb_jobs_lang]").hide();
    SetCheckBoxList(data.jobs_lang, "jobs_lang");

    $("*[name=mech_ids]").hide();
    $("*[name=lb_mech_ids]").hide();
    SetCheckBoxList(data.mech, "mech_ids");

    //$("input[name=app]").attr("disabled","disabled");

    $("input[name=empl_type]").attr("disabled","disabled");
    $("input[name=narrow_req]").attr("disabled","disabled");
    $("input[name=jobs_lang]").attr("disabled","disabled");
    $("input[name=mech]").attr("disabled","disabled");
}

/*
function parseForm(data) {
  vac.id_city = data.id_city;
  vac.ra_id = data.id_ra;
  $("#city").val(data.city);
}
*/

function SetCheckBoxList(data, name) {
    if (data.length > 1) {
        $("input[name="+name+"]:checked").removeAttr('checked');
        var arr = data.split('-');
        for (var key in arr) {
            $("#"+name+"_" + arr[key]).attr('checked', true);
            $("#lb_"+name+"_" + arr[key]).show();
            $("#"+name+"_" + arr[key]).show();
        }
    }
}

function SetDisableList(name, id) {
    $("*[name="+name+"]").hide();
    $("*[name=lb_"+name+"]").hide();
    $("#lb_"+name+"_" + id).show();
    $("input[name="+name+"]:checked").show();
    $("input[name="+name+"]:checked").attr("disabled","disabled");
}

<?php
if($vid!='')
{
    echo "vid=$vid;";
}
?>                                

$(document).ready(function() {
  getForm(vid);
});
</script>