<?php  
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
//Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/bootstrap-collapse.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD);
echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));
$docroot = $_SERVER['DOCUMENT_ROOT'];

$photo = '/images/man.png';
$first_name = '';
$last_name = '';
$city_id = '';
$sex = '';
$bdate = '';
$city_name = '';
$has_mobile = '';
$education = '';
$nickname = '';
$cname = '';
$cid = '';
$email = '';
if(isset(Yii::app()->session['cname']))
  $cname = Yii::app()->session['cname'];

if(isset(Yii::app()->session['cid']))
{
  $cid = Yii::app()->session['cid'];
  if(Yii::app()->session['cname'] == 'vk')
  {
    require $docroot.'/uploads/vkapi.class.php';
    $api_id = Share::setup('VK_ID');  // 3857698; Insert here id of your application
    $secret_key = Share::setup('VK_KEY'); // yPdNpQmGqzHac1kfLUCL; Insert here secret key of your application

    $VK = new vkapi($api_id, $secret_key);
    $prof = $VK->api('getProfiles', array('uids' => $cid, 'fields' =>'first_name,last_name,photo_big,status,screen_name, city, country, sex, bdate, has_mobile, education, contacts,nickname'));
    $adsd = sizeOf($prof['response']);
    for ($d = 0; $d < $adsd; $d++) {
        $photo = $prof['response'][$d]['photo_big'];
        $first_name = $prof['response'][$d]['first_name'];
        $last_name = $prof['response'][$d]['last_name'];
        $city_id = $prof['response'][$d]['city'];
        $sex = $prof['response'][$d]['sex'];
        if(isset($prof['response'][$d]['bdate']))
          $bdate = $prof['response'][$d]['bdate'];
        if(isset($prof['response'][$d]['home_phone']))
          $has_mobile = $prof['response'][$d]['home_phone'];
        if(isset($prof['response'][$d]['university_name']))
          $education = $prof['response'][$d]['university_name'];
        $nickname = $prof['response'][$d]['nickname'];        
    }
    
    $res = Yii::app()->db->createCommand()
    ->select('name')
    ->from('city')
    ->where('cid=:id', array(':id'=>$city_id))
    ->limit(1)
    ->queryRow();
    
    $city_name = $res['name'];    

  } 
  
  if(Yii::app()->session['cname'] == 'fb')
  {
    if(isset($fc_users_getinfo))
    {
        $photo = $last_name = $fc_users_getinfo[0]['pic_big'];
        $first_name = $fc_users_getinfo[0]['first_name'];
        $last_name = $fc_users_getinfo[0]['last_name'];
        if(isset($fc_users_getinfo[0]['current_location']))
          $city_name = $fc_users_getinfo[0]['current_location']['city'];
        if($fc_users_getinfo[0]['sex']=='male') $sex=0; else $sex=1;
        $dt = strtotime($fc_users_getinfo[0]['birthday']);
        $bdate = gmdate('d.m.Y',$dt+86400);
        $email = $fc_users_getinfo[0]['email'];
    }
    else {
      Yii::app()->session['cname'] = null;
      Yii::app()->session['cid'] = null;
    }
  }


}
?>
<h3>Форма регистрации промоутера</h3>

<div class="row" >
  <div class="span3">
  <div id="msg"></div>

  <div class="photo"><img id="photo" src="<?php echo $photo ?>"/></div>
  <input id="fileToUpload" type="file" name="fileToUpload" title="Выберите файл" style="display:none" onchange="ajaxFileUpload()"/>
  <input type="hidden" id="photo_file">
<?php
  echo '<img id="loading" src="'.Yii::app()->homeUrl.'images/loading.gif" style="display:none;">';
?>
  <button class="btn btn-success" id="buttonUpload" onclick="return Upload();" title="Загрузить">Загрузить</button>


 </div>

  <div class="span9">
  <div class="form-horizontal">

  <div class="accordion" id="accordion2">

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapseOne" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" >Основная информация</a>
    </div>
    <div id="collapseOne" class="accordion-body collapse in">
      <div class="accordion-inner">

       <div class="control-group">
          <label class="control-label">Фамилия * <span id="lastname_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
              <input type="text" name="lastname" id="lastname" placeholder="Фамилия" value="<?php echo $last_name ?>"/>
          </div>
       </div>

       <div class="control-group">
          <label class="control-label">Имя * <span id="firstname_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="firstname" id="firstname" placeholder="Имя" value="<?php echo $first_name ?>"/>
          </div>
       </div>

       <div class="control-group">
          <label class="control-label">Отчество * <span id="surname_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="surname" id="surname" placeholder="Отчество"/>
          </div>
       </div>


       <div class="control-group">
          <label class="control-label">Дата рождения * <span id="birthday_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <?php
          	  echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('model'=>$model, 'attribute'=>'access_time','language'=>'ru', 'id'=>'birthday',
              'options'=>array('showButtonPanel'=>'true','changeYear'=>'true','yearRange'=>'-70:+1')), true);
            	if($bdate!='')
              {
                echo '<script type="text/javascript">
                $("#birthday").val("'.$bdate.'");
                </script>';
              }
            ?>
          </div>
       </div>

       <div class="control-group">
          <label class="control-label">Пол * </label>
          <div class="controls input-append">
            <label class="radio">Мужской<input type="radio" name="ismale" id="ismale_1" checked value="1"></label>
            <label class="radio">Женский<input type="radio" name="ismale" id="ismale_0" value="0"></label>
          </div>
       </div>
<?php
      if($sex!='')
      {
        $m = $sex^1;
        echo '<script type="text/javascript">
        $("#ismale_'.$m.'").attr("checked", true);
        </script>';
      }
?>


      </div>
    </div>
    </div>

  <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapseTwo" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Образование</a>
    </div>
    <div id="collapseTwo" class="accordion-body collapse">
      <div class="accordion-inner">

         <div class="control-group">
          <label class="control-label">Учебное заведение * <span id="education_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="education" id="education" placeholder="ВУЗ" value="<?php echo $education ?>"/>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label">Образование * <span id="education_type_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <label class="radio">Высшее<input id="education_type_1" type="radio" name="education_type" value="1"></label>
            <label class="radio">Среднее<input id="education_type_2" type="radio" name="education_type" value="2" ></label>
            <label class="radio">Начальное<input id="education_type_3" type="radio" name="education_type" value="3" ></label>
          </div>
        </div>





      </div>
    </div>
    </div>



   <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapseThree" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Контактная информация</a>
    </div>
    <div id="collapseThree" class="accordion-body collapse">
      <div class="accordion-inner">

       <div class="control-group">
          <label class="control-label">Регион * <span id="city_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="city" id="city" placeholder="пример: Москва" value="<?php echo $city_name ?>" />
          </div>
       </div>

       <div class="control-group">
          <label class="control-label">Е-майл * <span id="email_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="email" id="email" placeholder="пример: sergey78@mail.com"  class="asci_only" value="<?php echo $email ?>"/>
          </div>
       </div>


       <div class="control-group">
          <label class="control-label">Телефон * <span id="phone_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="phone" id="phone" class="phone" placeholder="пример: 095-8001122" value="<?php echo $has_mobile ?>"/>
          </div>
       </div>


      </div>
    </div>
   </div>



  <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapseFour" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Аккаунт</a>
    </div>
    <div id="collapseFour" class="accordion-body collapse">
      <div class="accordion-inner">


       <div class="control-group">
          <label class="control-label">Логин (ник) * <span id="login_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="login" id="login" placeholder="пример: sergey78" class="asci_only" value="<?php echo $nickname ?>"/>
            <span class="err" id="login_err"></span>
         </div>
       </div>


      <p>
        <a href="javascript:ShowPassw()" class="btn small" id="btn_update_passw">Сменить пароль</a>
      </p>

      <div id="block_passw" style="display:none;">

        <div class="control-group" id="elem_passw_old">
            <label class="control-label"><?php echo Share::lng('ANEM_PASSW-OLD');?> * <span id="password_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="password" name="passw_old" id="passw_old" placeholder="password"/>
            </div>
        </div>

        <div class="control-group" >
            <label class="control-label"><?php echo Share::lng('ANEM_PASSW');?> * <span id="elem_passw_old_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="password" name="passw" id="passw" placeholder="password" onchange="checkPassw()"/>
            </div>
        </div>

        <div class="control-group" >
            <label class="control-label"><?php echo Share::lng('ANEM_CONFIRM');?> * <span id="confirm_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="password" name="confirm" id="confirm" placeholder="password" onchange="checkPassw()"/>
            </div>
        </div>

        <p id="msg_passw"></p>
        <a href="javascript:UpdatePassw()" class="btn small" id="btn2_update_passw">Сменить пароль</a>

      </div> <!--block_passw-->


      </div>
    </div>
    </div>
  </div>



  </div>


</div>
</div>
  <div class="clear"></div>
  <br/>
  <div class="alert alert-error err" id="total_err">
    Не все поля заполнены! Проверьте фому.
  </div>

    <div class="modal-footer">
			<a href='/' class='btn btn-inverse' id='btn_cancel_form' >Отмена</a>
      <a href='javascript:Save();' class='btn btn-primary' id='btn_save_form' >Сохранить</a>
 	  </div>
 	  <div id="swait" style="width:100%; text-align:center; display:block; margin-top:40px; font-weight:bold;display:none;">
 	     Отправка данных ...&nbsp;&nbsp;<img src="/images/pleasewait.gif" alt="Отправка данных">
 	  </div>
  
<input type="hidden" id="cid" value="<?php echo $cid ?>" />
<input type="hidden" id="cname" value="<?php echo $cname ?>" />  

<?php echo CHtml::endForm(); ?>

<script type="text/javascript">

var objForm = (
  {"total_err":0,"param":""},
  [
	{
	"name":"lastname",
	"value":"",
  "tp":0,
	"validate":1,  
	"errmess": "введите фамилию"
  },
	{
	"name":"firstname",
	"value":"",
  "tp":0,
  "validate":1,
	"errmess": "введите имя"
  },  
	{
	"name":"surname",
	"value":"",
  "tp":0,
	"validate":1,
	"errmess": "введите отчество"
  },  
	{
	"name":"birthday",
	"value":"",
  "tp":0,
  "validate":1,
	"errmess": "введите дату"
  },  
	{
	"name":"ismale",
	"value":"",
	"tp":1,
  "validate":0,
	"errmess": ""
  },  
	{
	"name":"city",
	"value":"",
  "tp":0,  
	"validate":1,
	"errmess": "введите город"
  },  
	{
	"name":"education",
	"value":"",
  "tp":0,  
	"validate":1,
	"errmess": "введите университет/техникум"
  },
	{
	"name":"education_type",
	"value":"",
  "tp":1,  
	"validate":1,
	"errmess": "выберите тип"
  },
	{
	"name":"phone",
	"value":"",
  "tp":0,  
	"validate":1,
	"errmess": "введите телефон"
  },
	{
	"name":"login",
	"value":"",
  "tp":0,  
	"validate":1,
	"errmess": "введите логин"
  },
	{
	"name":"passw",
	"value":"",
  "tp":0,  
	"validate":1,
	"errmess": ["введите пароль","пароли не совпадают"]
  },
	{
	"name":"email",
	"value":"",
  "tp":0,  
	"validate":1,
	"errmess": "введите email"
  },
	{
	"name":"cid",
	"value":"<?php echo $cid ?>",
	"tp":0,
  "validate":0,
	"errmess": ""
  },
  {
	"name":"cname",
	"value":"<?php echo $cname ?>",
	"tp":0,
  "validate":0,
	"errmess": ""
  }  
      
]);

var photo='';

var promo = {
  login: "",
  passw: "",
  email: "",
  lastname: "",
  firstname: "",
  surname: "",
  birthday: "",
  city: "",
  education: "",
  education_type: 0,
  phone: "",
  avatar: ""  
};

<?php
if($photo!='') {
  echo 'photo = "!'.substr($photo,7).'";';
  echo 'promo.photo = "!'.substr($photo,7).'";';
}
echo "\r\n var cid='".$cid."';\r\n"; 
?>

function checkPassw() {
  var passw = $("#passw").val();
  var confirm = $("#confirm").val(); 
  
  if(passw!="" && confirm!="") {
  if(passw!=confirm)
  {
    $("#passw").next().text("Пароли не совпадают");
    promo.pssw = "";
  } else {
    $("#passw").next().text(" ");
    promo.passw = passw;          
  }
  }
}


function Save()
{
  if(token!=undefined) setValidateObjForm('passw', 0);
  formValidate();
  //alert(objForm.total_err);
  if(objForm.total_err==0)
  {
    $("#swait").show();
    var photo_form = $("#photo_file").val();
    if(photo_form.length>0) promo.photo = photo_form;
    var val = objForm.param+"photo:"+promo.photo;
     
			if(ip==undefined || ip==null || ip==""){
        getIp();
        setTimeout('Save()',1000);
        return false;    
      }
      if(token==undefined || token==null)
      {
        // Insert
        var xen = encript(val,ip);
        link = site_url+"?cmd=REGISTER_PROMO&mode=2&value="+xen+"&callback=?";
      } else {
        // Update
        var xen = encript(val,token);
        link = site_url+"?cmd=UPDATE_PROMO&mode=3&uid="+uid+"&value="+xen+"&callback=?";
      }
			jsonp(link, function (data) {
        if(data.value>0)
        {
          if(token==undefined) { Clear(); }
          else {location.href="/";}
        } else {
          alert("Запрос не принят!");
        } 
      });
  } else {
     showPopUp('Валидация формы','Заполнены не все поля!');
  }
}

function UpdatePassw()
{
  var old_psw = $("#passw_old").val();
  var psw1 = $("#passw").val();
  var psw2 = $("#confirm").val();
  var mess = '';
  
  if(old_psw.length>0 && psw1.length>0 && psw2.length>0)
  {
    if(psw1==psw2)
    {
        // Update
        var val = "old_passw:"+old_psw+",new_passw:"+psw1;
        var xen = encript(val,token);
        link = site_url+"?cmd=UPDATE_PASSW&mode=3&uid="+uid+"&value="+xen+"&callback=?";
      
        jsonp(link, function (data) {
        if(data.value>0)
        {
          mess = 'Пароль изменен';
        } else {
          mess = 'Пароль неправильный';
        } 
        $("#msg_passw").html(mess);
        });		
        
    } else {
      mess = err_confirm;
    }
  } else {
    mess = 'Заполните все поля';
  }
  $("#msg_passw").html(mess);
}

function Clear() { 
  var mess = '<br/><br/><br/><br/><br/><br/><div><h3>Поздравляем, вы получили аккаунт <b>'+
  getValueObjForm('login')+'</b> для входа в личный кабинет</h3>'+
  '<p>Для активации пароля - вам отправлено письмо на адрес <a href="mailto:'+
  getValueObjForm('email')+'">'+getValueObjForm('email')+'</a></p><p>Откройте письмо и перейдите по ссылке</p></div>';
  $("#wrapper").html(mess);
  $("#btn_save_form").hide();
  $("#swait").hide();
  
  sendMail(getValueObjForm('email'), getValueObjForm('login'));
}  


/* -------- Client script --------------*/

  function Upload()
  {
    $("input[type=\'file\'").trigger("click");
    return false;
  }

	function ajaxFileUpload()
	{
    if($("#fileToUpload").val()=="")
    {
       $("input[type=\'file\'").trigger("click");
       return;
    }

		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});

		$.ajaxFileUpload
		(
			{
				url:"<?php echo Yii::app()->homeUrl.'uploads/doajaxfileupload.php'; ?>",
				secureuri:false,
				fileElementId:"fileToUpload",
				dataType: "json",
				data:{name:"logan", id:"id"},
				success: function (data, status)
				{
					if(typeof(data.error) != "undefined")
					{
						if(data.error != "")
						{
							alert(data.error);
						}else
						{
              $("#photo").attr("src","/content/"+data.name);
              $("#photo_file").val(data.name);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		);

		return false;
	}

  
function getForm_RegisterPromo() {
  if(!!uid)
  {
			link = site_url+"?cmd=GET_REG_PROMO&value=&uid="+uid+"&callback=?";
			jsonp(link, function (data) {
        parseForm(data);
      });
   }		
}

function ShowPassw()
{
  $("#block_passw").show();
  $("#btn_update_passw").hide();
}


function parseForm(data) {
  $("#lastname").val(data.lastname);
  $("#firstname").val(data.firstname);
  $("#surname").val(data.surname);
  $("#birthday").val(data.birthday);
  $("#phone").val(data.phone);
  $("#email").val(data.email);
  $("#city").val(data.city);
  //$("#interests").val(data.aboutme);
  $("#education").val(data.university);
  $("#education_type_"+data.education_type).prop('checked', true);
  $("#ra").val(data.name_ra);
  $("#login").val(data.login); orig_login = data.login; promo.login = data.login;


  if(data.photo=="" || data.photo=='!/man.png') {
    $("#photo").attr({src:"/images/man.png"});
  } else {
    if(data.photo.substring(0,1)=='!') {
        $("#photo").attr({src:"http://"+data.photo.substring(1)});
      }
      else {
        $("#photo").attr({src:"/content/"+data.photo});      
      }

  }

  promo.photo = data.photo;
  $("#ismale_"+data.isman).attr("checked", true);
  setValidateObjForm('passw', 0);
}

  
  
$(function() {	
  $("#city").autocomplete({
    source: function(request,response) {
		var link = site_url+"?cmd=GET_CITY_LIST&mode=1&filter="+request.term+"&callback=?";
			jsonp(link, function (data) { 
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
      //getListMetro(ui.item.id);
    }
  });
});

$(function() {	
  $("#education").autocomplete({
    source: function(request,response) {
		var link = site_url+"?cmd=GET_EDUCATION_LIST&mode=1&filter="+request.term+"&callback=?";
			jsonp(link, function (data) { 
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
      //getListMetro(ui.item.id);
    }
  });
});

<?php
if(isset($cid)) {
  echo 'token==undefined;';
}
?>
$(document).ready( function(){
    getIp();
    GetMemberToken();

    if(token==undefined)
    {
      // new mode
      $("#block_passw").show();
      $("#elem_passw_old").hide();
      $("#btn_update_passw").hide();
      $("#btn2_update_passw").hide();
    }

    $(".err").hide();
/*    
    $('#birthday').datepicker(
            {
                changeMonth: true,
                changeYear: true,
                yearRange: "-50:+1"
            }
        );
*/
});
  
  </script>