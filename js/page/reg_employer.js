var objForm = (
    {"total_err": 0, "param": ""},
        [
            { "name": "login", "value": "", "tp": 0, "validate": 1 },
            { "name": "passw", "value": "", "tp": 0, "validate": 0 },
            { "name": "company", "value": "", "tp": 0, "validate": 1 },
            { "name": "company_type", "value": "DEMPL", "tp": 0, "validate": 0 },
            { "name": "city", "value": "", "tp": 0, "validate": 1 },
            { "name": "www", "value": "", "tp": 0, "validate": 0 },
            { "name": "phone", "value": "", "tp": 0, "validate": 1 },
            //{ "name": "lastname", "value": "", "tp": 0, "validate": 1 },
            { "name": "firstname", "value": "", "tp": 0, "validate": 1 },
            //{ "name": "surname", "value": "", "tp": 0, "validate": 1 },
            { "name": "photo", "value": "", "tp": 0, "validate": 0 },
            { "name": "position", "value": "", "tp": 0, "validate": 0 },
            { "name": "news", "value": "1", "tp": 1, "validate": 0 }
        ]);

//   { "name":"education", "value":"", "tp":0, "validate":0 },
//   { "name":"education_type", "value":"0", "tp":1, "validate":0 },

var orig_login = "";

function getForm_RegisterEmployer() {
    if (!!uid) {
        link = site_url + "?cmd=GET_REG_EMPLOYER&value=&uid=" + uid + "&callback=?";
        jsonp(link, function (data) {

            parseForm(data);
        });
    }
}

function parseForm(data) {
    $("#lastname").val(data.LNAME);
    $("#firstname").val(data.FNAME);
    $("#surname").val(data.SNAME);
    $("#email").val(data.EMAIL);
    $("#login").val(data.EMAIL);
    $("#city").val(data.city_name);
    $("#position").val(data.POS);
    $("#www").val(data.WWW);
    $("#company").val(data.COMP);
    $("#company_type").val(data.UTYPE);
    orig_login = data.EMAIL;
    $("#login").attr("readonly", true);
    isnews = false;
    if(data.ISNEWS == 1) isnews=true;
    $("#news").attr("checked", isnews);
    //objForm.login = data.login;

    /*
    if (data.photo == "") {
        $("#photo").attr({src: "/images/man.png"});
    } else {
        $("#photo").attr({src: "/content/" + data.photo});
    }
    */

    $("#phone").val(data.PHONE);
    $("#btn_save_form").text("Сохранить");
    formValidate();
}


function checkLogin() {
/*
    alert('checkLogin');
    var lg = $("#login").val();
    var keys = '00000';
    var link;

    if (lg != "") {
        var val = "login:" + lg;
        var xen = encript(val, ip);

        if (token != undefined && lg == orig_login) return;

        link = site_url + "?cmd=CHECK_LOGIN&mode=2&value=" + xen + "&callback=?";
        jsonp(link, function (data) {
            if (data.value == 1) {
                $("#login").next().text(err_user);
                objForm.login = "";
            } else {
                $("#login").next().text(" ");
                objForm.login = lg;
            }

        });
    } else {
        $("#login").next().text(err_login);
    }
*/
}

function checkPassw() {
    var passw = $("#passw").val();
    var confirm = $("#confirm").val();

    if (passw != "" && confirm != "") {
        if (passw != confirm) {
            $("#passw").next().text(err_confirm);
            objForm.pssw = "";
        } else {
            $("#passw").next().text(" ");
            objForm.passw = passw;
        }
    } else {
        $("#passw").next().text(err_passw);
    }
}


function isValidEmail(email, strict) {
    if (!strict) email = email.replace(/^\s+|\s+$/g, '');
    return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}


function isValidPhone(phone, strict) {
    if (!strict) phone = phone.replace(/^\s+|\s+$/g, '');
    if (phone.length < 7) return false;
    return (/^([0-9_\-]+\-)*[0-9_\-]{2,10}$/i).test(phone);
}

function Save() {
    // ..... Validate
    formValidate();
    if (objForm.total_err == 0) {
        var val = objForm.param;
        if (token == undefined) {
            // Insert
            var xen = encript(val, ip);
            link = site_url + "?cmd=REGISTER_EMPLOYER&mode=2&value=" + xen + "&callback=?";
        } else {
            // Update
            var xen = encript(val, token);
            link = site_url + "?cmd=UPDATE_EMPLOYER&mode=3&uid=" + uid + "&value=" + xen + "&callback=?";
        }


        jsonp(link, function (data) {
            if (data.value > 0) {
                Clear();
            } else {
                alert(err_network);
            }
        });
    } else {
        showPopUp('Валидация формы', 'Заполнены не все поля!');
    }
}


function Clear() {
    if (token == undefined) {
        var mess = '<br/><br/><br/><br/><br/><br/><div><h3>' + mess1 + ' <i>' +
            objForm.login + '</i> ' + mess2 + '</h3>' +
            '<p>' + mess3 + ' <a href="mailto:' +
            objForm.email + '">' + objForm.email + '</a></p><p>' + mess4 + '</p></div>';
        $("#wrapper").html(mess);
        $("#btn_save_form").hide();
        sendMail(objForm.email, objForm.login);
    } else {
        var mess = 'Данные успешно обновлены';
        $("#msg").html(mess);
        $("#msg").show();
        //$("#wrapper").html(mess);
    }
}

function ShowPassw() {
    $("#block_passw").show();
    $("#btn_update_passw").hide();
}

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
            url: '/uploads/doajaxfileupload.php',
            secureuri: false,
            fileElementId: 'fileToUpload',
            dataType: 'json',
            data: {name: 'logan', id: 'id'},
            success: function (data, status) {
                if (typeof(data.error) != 'undefined') {
                    if (data.error != '') {
                        alert(data.error);
                    } else {
                        //alert(data.msg);
                        $('#msg').html(data.msg);
                        objForm.photo = data.name;
                        $('#photo').attr("src", "/content/" + data.name);
                    }
                }
            },
            error: function (data, status, e) {
                alert(e);
            }
        }
    )

    return false;
}

function Upload()
{
    $("input[type=\'file\'").trigger("click");
    return false;
}


$(function () {
    $("#city").autocomplete({
        source: function (request, response) {

            link = site_url + "?cmd=GET_CITY_LIST&mode=1&filter=" + request.term + "&callback=?";
            jsonp(link, function (data) {
                //alert(data.message+", ip="+ip);
                response($.map(data, function (item) {
                    return {
                        label: item.name,
                        value: item.name,
                        id: item.id
                    }
                }));
            });
        },
        minLength: 1,
        select: function (event, ui) {
            //$("#lcountry").text('#'+ui.item.id);
        }
    });
});

$(function () {
    $("#ra").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "/admin.php/ajax/getRa",
                dataType: "json",
                data: {
                    featureClass: "P",
                    name_startsWith: request.term
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.name,
                            value: item.name,
                            id: item.id
                        }
                    }));
                }
            });
        },
        minLength: 1,
        select: function (event, ui) {
            //$("#lcountry").text('#'+ui.item.id);
            objForm.ra_id = ui.item.id;
        }
    });
});

function comp(key) {
    var cmp = $("#"+key).text();
    $("#btn_company").html(cmp+' <span class="caret"></span>');
    $("#company_type").val(key);
}

$(document).ready(function () {
    getIp();
    GetMemberToken();
    formValidate();
    var cmp = $("#DEMPL").text();
    $("#company_type").val('DEMPL');
    $("#btn_company").html(cmp+' <span class="caret"></span>');
    if (token == undefined) {
        // new mode
        $("#block_passw").show();
        $("#elem_passw_old").hide();
        $("#btn_update_passw").hide();
        $("#btn2_update_passw").hide();
    }

    $("#collapseOne").collapse('toggle');
    $("#collapseThree").collapse('toggle');
    $("#collapseFour").collapse('toggle');

});
