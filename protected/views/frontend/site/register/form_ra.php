<?php  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/ajaxfileupload.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/jsform.js', CClientScript::POS_HEAD);
echo CHtml::form('', 'post', array('enctype' => 'multipart/form-data'));
?>
<h3>Регистрация рекламного агентства</h3>
<div class="row" id="form_ra">
    <div class="span3">

        <div id="photo_msg"></div>

        <div class="photo"><img id="photo" src="/images/man.png"/></div>
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
                        <a href="#collapseOne" class="accordion-toggle" data-toggle="collapse"
                           data-parent="#accordion2">Основная информация</a>
                    </div>
                    <div id="collapseOne" class="accordion-body collapse in">
                        <div class="accordion-inner">

                            <?php
                            echo FormHelper::TextField('name_ra', '', 'Название рекламного агентства *', 'пример: Искра', 'span4', '', '', 1);
                            echo FormHelper::TextField('web', '', 'Web сайт *', 'www.iskra.net', 'span4', '', '', 1);
                            echo FormHelper::TextField('position', '', 'Позиция (должность) *', '', 'span4', '', '', 1);
                            echo FormHelper::TextField('email', '', 'Email *', 'info@iskra.com', 'span4', '', '', 1);
                            ?>
                        </div>
                    </div>
                </div>


                <div class="accordion-group">
                    <div class="accordion-heading silver">
                        <a href="#collapseFour" class="accordion-toggle" data-toggle="collapse"
                           data-parent="#accordion2">Аккаунт</a>
                    </div>
                    <div id="collapseFour" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <div class="control-group">
                                <label class="control-label">Логин (ник) * <span id="login_err"
                                                                                 class="err badge badge-important">!</span></label>

                                <div class="controls input-append">
                                    <input type="text" name="login" id="login" placeholder="пример: sergey78"
                                           class="asci_only" />
                                    <span class="err" id="login_err"></span>
                                </div>
                            </div>

                            <p>
                                <a href="javascript:ShowPassw()" class="btn small" id="btn_update_passw">Сменить
                                    пароль</a>
                            </p>

                            <div id="block_passw" style="display:none;">

                                <div class="control-group" id="elem_passw_old">
                                    <label class="control-label"><?php echo Share::lng('ANEM_PASSW-OLD');?> * <span
                                        id="passw_old_err" class="err badge badge-important">!</span></label>

                                    <div class="controls input-append">
                                        <input type="password" name="passw_old" id="passw_old" placeholder="password"/>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><?php echo Share::lng('ANEM_PASSW');?> * <span
                                        id="passw_err" class="err badge badge-important">!</span></label>

                                    <div class="controls input-append">
                                        <input type="password" name="passw" id="passw" placeholder="password" onchange="checkPassw()"/>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><?php echo Share::lng('ANEM_CONFIRM');?> * <span
                                        id="confirm_err" class="err badge badge-important">!</span></label>

                                    <div class="controls input-append">
                                        <input type="password" name="confirm" id="confirm" placeholder="password"
                                               onchange="checkPassw()"/>
                                    </div>
                                </div>

                                <p id="msg_passw"></p>
                                <a href="javascript:UpdatePassw()" class="btn small" id="btn2_update_passw">Сменить
                                    пароль</a>

                            </div>
                            <!--block_passw-->


                        </div>
                    </div>
                </div>

            </div>
            <!-- End Accordeon -->

        </div>
        <!-- end pan1 -->


        <div class="clear"></div>


        <br/><br/>
        <div class="alert alert-error err" id="total_err">
            Не все поля заполнены! Проверьте фому.
        </div>

        <div class="modal-footer">
            <a href='/site/page/about' class='btn btn-inverse' id='btn_cancel_form'>Отмена</a>
            <a href='#' class='btn btn-primary' id='btn_save_form' onclick="Save();">Сохранить</a>
        </div>

    </div>
</div><!-- end row -->

<div id="msg" class="alert" style="display:none"></div>

<?php echo CHtml::endForm(); ?>

<script type="text/javascript">
var isPassw = false;
var isLogin = false;
var objForm = (
    {"total_err":0, "param":""},
        [
            { "name":"login", "value":"", "tp":0, "validate":1 },
            { "name":"passw", "value":"", "tp":0, "validate":0 },
            { "name":"email", "value":"", "tp":0, "validate":1 },
            { "name":"name_ra", "value":"", "tp":0, "validate":1 },
            { "name":"web", "value":"", "tp":0, "validate":1 },
            { "name":"position", "value":"", "tp":0, "validate":1 },
            { "name":"photo", "value":"", "tp":0, "validate":0 }
        ]);

function UpdatePassw() {
    var old_psw = $("#passw_old").val();
    var psw1 = $("#passw").val();
    var psw2 = $("#confirm").val();
    var mess = '';

    if (old_psw.length > 0 && psw1.length > 0 && psw2.length > 0) {
        if (psw1 == psw2) {
            // Update
            var val = "old_passw:" + old_psw + ",new_passw:" + psw1;
            var xen = encript(val, token);
            link = site_url + "?cmd=UPDATE_PASSW&mode=3&uid=" + uid + "&value=" + xen + "&callback=?";

            jsonp(link, function (data) {
                if (data.value > 0) {
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



function checkLogin() {
    var lg = $("#login").val();
    var link;

    //if(ip=="undefined") getIp();

    if (lg != "") {
        var val = "login:" + lg;
        var xen = encript(val, ip);

        link = site_url + "?cmd=CHECK_LOGIN&mode=2&value=" + xen + "&callback=?";
        alert(link);
        jsonp(link, function (data) {
            if (data.message == 1) {
                isLogin = false;
                $("#login_err").show();
                showPopUp("Login", "Такой пользователь уже существует");
            } else {
                $("#login_err").hide();
                isLogin = true;
            }

        });
    } else {
        $("#login_err").show();
    }
}


function checkPassw() {
    formValidate();
    isPassw = false;
    isLogin = false;

      var passw = $("#passw").val();
      var confirm = $("#confirm").val();

      if(passw!="" && confirm!="") {
        if(passw!=confirm)
        {
            $("#passw_err").show();
          return false;
        } else {
            $("#passw_err").hide();
            isPassw = true;
          return true;
        }
      }
      else{
          $("#passw_err").show();
        return false;
      }    
}


/*
function checkEmail() {
  var email = $("#email").val();
  if(!isValidEmail (email, true))
  {
    $("#email").next().text("Некорректный email");
    ra.email = "";
  } else {
    $("#email").next().text(" ");
    ra.email = email;          
  }
}


function checkNameRa() {
  var name_ra = $("#name_ra").val();
  if(name_ra=="") {
    $("#name_ra").next().text("Введите название РА"); 
    ra.name_ra = ""; 
  } else {
    $("#name_ra").next().text(" "); 
    ra.name_ra = name_ra;   
  }
}


function checkWeb() {
  var web = $("#web").val();
  if(web=="") {
    $("#web").next().text("Введите url web сайта"); 
    ra.web = ""; 
  } else {
    $("#web").next().text(" "); 
    ra.web = web;   
  }
}

function checkPosition() {
  var position = $("#position").val();
  if(position=="") {
    $("#position").next().text("Введите должность"); 
    ra.position = ""; 
  } else {
    $("#position").next().text(" "); 
    ra.position = position;   
  }
}
*/
function getForm_RegisterRa() {
    if (!!uid) {
        link = site_url + "?cmd=GET_REG_RA&value=&uid=" + uid + "&callback=?";
        jsonp(link, function (data) {
            parseForm(data);
        });
    }
}

function parseForm(data) {
    $("#name_ra").val(data.name);
    $("#web").val(data.web);
    $("#position").val(data.position);
    $("#login").val(data.login);
    $("#email").val(data.email);
    $("#photo").attr({src:"/content/" + data.photo});
    $("#photo_file").val(data.photo);
    //ra.photo = data.photo;
    formValidate();
}

function ShowPassw() {
    $("#block_passw").show();
    $("#btn_update_passw").hide();
}


function isValidEmail(email, strict) {
    if (!strict) email = email.replace(/^\s+|\s+$/g, '');
    return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}


function Save() {
    // ..... Validate
    console.log("token="+token);
    formValidate();
    if (objForm.total_err == 0 && isPassw == true) {
        var val = objForm.param;
        val += "avg_rating:0,logo:" + $("#photo_file").val();

        if (token == undefined) {
            setValidateObjForm('passw',1);
            formValidate();
            // Insert
            if(objForm.total_err == 0)
            {
              var xen = encript(val, ip);
              link = site_url + "?cmd=REGISTER_RA&mode=2&value=" + xen + "&callback=?";
            }
        } else {
            // Update
            var xen = encript(val, token);
            link = site_url + "?cmd=UPDATE_RA&mode=3&uid=" + uid + "&value=" + xen + "&callback=?";
        }
        jsonp(link, function (data) {
            if (data.value > 0) {
                if (token == undefined) {
                    Clear();
                }
                else {
                    location.href = "/";
                }
            } else {
                showPopUp("", "Некоторые поля введены некорректно!");
            }
        });
    } else {
        showPopUp('Валидация формы','Заполнены не все поля!');
    }
}


function Clear() {
    var mess = '<br/><br/><br/><br/><br/><br/><div><h3>Поздравляем, вы получили аккаунт <i>' +
        getValueObjForm('login') + '</i> для входа в личный кабинет</h3>' +
        '<p>Для активации пароля - вам отправлено письмо на адрес <a href="mailto:' +
        getValueObjForm('email') + '">' + getValueObjForm('email') + '</a></p><p>Откройте письмо и перейдите по ссылке</p></div>';
    $("#msg").html(mess);
    $("#form_ra").hide();
    $("#msg").show();

    sendMail(getValueObjForm('email'), getValueObjForm('login'));
}

function Upload()
{
    $("input[type=\'file\'").trigger("click");
    return false;
}

function ajaxFileUpload() {
    $("#loading")
        .ajaxStart(function () {
            $(this).show();
        })
        .ajaxComplete(function () {
            $(this).hide();
        });

    $.ajaxFileUpload
        (
            {
                url:'/uploads/doajaxfileupload.php',
                secureuri:false,
                fileElementId:'fileToUpload',
                dataType:'json',
                data:{name:'logan', id:'id'},
                success:function (data, status) {
                    if (typeof(data.error) != 'undefined') {
                        if (data.error != '') {
                            alert(data.error);
                        } else {
                            $("#photo").attr("src","/content/"+data.name);
                            $('#photo_msg').html($('#msg').html() + data.name);
                            $("#photo_file").val(data.name);
                        }
                    }
                },
                error:function (data, status, e) {
                    alert(e);
                }
            }
        )

    return false;
}
$(document).ready(function () {
    getIp();
    GetMemberToken();
    if (token == undefined) {
        // new mode
        $("#block_passw").show();
        $("#elem_passw_old").hide();
        $("#btn_update_passw").hide();
        $("#btn2_update_passw").hide();
        setValidateObjForm('passw',1);
    }
            
});

</script>
