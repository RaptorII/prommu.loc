/*
 ===== Example ==========
 // tp - тип переменной:
 // 0-текст;
 // 1-radiogroup;
 // 2-checkbox;
 // для типов 1 и 2 должно быть задано name html элемента.
 // 3-сравнение 2-ух полей
 // 4-selected (выпадающий список)
 // 5-selected multiselect
 // 6-textbox с поиском (для моб.)


 var objForm = (
 {"total_err":0,"param":""},
 [
 {
 "name":"email",
 "value":"",
 "tp":0,
 "validate":1,
 "errmess": "введите email"
 },
 {
 "name":"name",
 "value":"",
 "tp":0,
 "validate":1,
 "errmess": ["введите имя","имя не должно быть короче 3"]
 }
 ]);
 */

function formValidate() {
    $(".err").hide();
    objForm.total_err = 0;
    var param = "";
    var val = "";
    var check_val = true;
    var idx_mess = 0;
    for (var i = 0; i < objForm.length; i++) {
        check_val = true;
        // check as type
        switch (objForm[i].tp) {
            case 0:
                val = $("#" + objForm[i].name).val();
                if (val == '') {
                    check_val = false;
                } else {
                    if (objForm[i].name == 'login') {
                        isDuplicateLogin(val, i);
                    }
                }
                break;
            case 1:
                val = $("input[name=" + objForm[i].name + "]:checked").val();
                check_val = false;
                if (val > "0") check_val = true;
                break;
            case 2:
                var sel = '';
                val = -1;
                $("input[name=" + objForm[i].name + "]:checked").each(function (i, selected) {
                    sel += '-' + $(selected).val();
                });

                if (sel != '') {
                    //if(objForm[i].name.substring(0,4) == 'mech')
                    //	val = EncodeBiteToIntN(sel.substring(1));
                    //else
                    val = sel.substring(1);
                }

                check_val = false;
                if (val != -1) check_val = true;
                break;
            case 3:
                val = $("#" + objForm[i].name).val();
                var val2 = $("#" + objForm[i].name + "_confirm").val();
                if (val == '') {
                    check_val = false;
                    idx_mess = 0;
                } else {
                    if (val != val2) {
                        check_val = false;
                        idx_mess = 1;
                    }
                }
                break;
            case 4:
                val = $("#" + objForm[i].name + " option:selected").val();
                break;
            case 5:
                var sel = '';
                $("#" + objForm[i].name + " :selected").each(function (i, selected) {
                    sel += '-' + $(selected).val();
                });

                val = sel.substring(1);
                break;
            case 6:
                val = $("#" + objForm[i].name).prev().find('input').val();
                //val = $("#"+objForm[i].name).val(); 
                if (val == '') check_val = false;
                break;

        }
        objForm[i].value = val;

        if (objForm[i].validate == 1) {
            if (!check_val) {
                objForm[i].value = "";
                /*
                 if(objForm[i].tp==3)
                 {
                 $("#"+objForm[i].name+"_err").text(objForm[i].errmess[idx_mess]);
                 }
                 else {
                 $("#"+objForm[i].name+"_err").text(objForm[i].errmess);
                 }
                 */
                $("#" + objForm[i].name + "_err").show();
                objForm.total_err = 1;
                console.log('Error: ' + objForm[i].name);
            } else {
                objForm[i].value = val;
                //$("#"+objForm[i].name+"_err").text(' ');
            }
        }
        if (objForm[i].tp == 2)
            param += objForm[i].name + ":" + objForm[i].value + ",";
        else
            param += objForm[i].name + ":" + ProtSymb(objForm[i].value) + ",";
    }
    objForm.param = param;
    if (objForm.total_err > 0) {
        $("#total_err").show();
    }
    console.log('Total_err: ' + objForm.total_err);
    console.log('Param: ' + objForm.param);
}


function isAsci(cCode) {
    return /[a-zA-Z0-9@\._\, -]/.test(String.fromCharCode(cCode))
}

function isPhone(cCode) {
    return /[0-9\-]/.test(String.fromCharCode(cCode))
}

function isNumber(cCode) {
    return /[0-9\.]/.test(String.fromCharCode(cCode))
}

function ProtSymb(s) {
    if (s == undefined) return s;
    s = s.replace(/,/g, "\\u002c");
    s = s.replace(/"/g, "\\u0022");
    s = s.replace(/&/g, "\\u0026");
    s = s.replace(/:/g, "\\u003a");
    return s;
}

function setValueObjForm(key, val) {
    for (var i = 0; i < objForm.length; i++) {
        if (objForm[i].name == key) {
            objForm[i].value = val;
            return;
        }
    }
}

function setValidateObjForm(key, val) {
    for (var i = 0; i < objForm.length; i++) {
        if (objForm[i].name == key) {
            objForm[i].validate = val;
            return;
        }
    }
}


function getValueObjForm(key) {
    for (var i = 0; i < objForm.length; i++) {
        if (objForm[i].name == key) {
            return objForm[i].value;
        }
    }
    return undefined;
}

function setValidateObjForm(key, val) {
    for (var i = 0; i < objForm.length; i++) {
        if (objForm[i].name == key) {
            objForm[i].validate = val;
            break;
        }
    }

}

function isDuplicateLogin(lg, i) {
    if (lg != "") {
        var val = "login:" + lg+"&uid=" + uid;
        var xen = encript(val, ip);

        link = site_url + "?cmd=CHECK_LOGIN&mode=2&value=" + xen + "&callback=?";
        jsonp(link, function (data) {
            if (data.value == 1) {
                showPopUp("", "Такой пользователь уже существует");
                 objForm[i].validate = 0;
                $("#login_err").show();
            } else {
                //$("#login_err").hide();

            }

        });
    } else {
        //$("#login_err").show();

    }
}

function pause(n) {
    today = new Date()
    today2 = today
    while ((today2 - today) <= n) {
        today2 = new Date()
    }
}

// return array 1..7
function DecodeToBiteN(intVal) {
    var bt = new Array(0, 1, 2, 4, 8, 16, 32, 64, 128);
    var out = [];
    if (intVal == 0) {
        out.push(0);
        return out;
    }
    for (var i = 1; i < 8; i++) {
        if (bt[i] & intVal) {
            out.push(i);
        }
    }
    return out;
}

function EncodeBiteToIntN(strVal) {
    var s = strVal.split('-');
    var bt = new Array(0, 1, 2, 4, 8, 16, 32, 64, 128);
    var res = 0;
    for (var i = 0; i < s.length; i++) {
        res = res + bt[parseInt(s[i])];
    }
    return res;
}

function SetCheckBoxList(data, name) {
    if (data.length > 1) {
        $("input[name=" + name + "]:checked").removeAttr('checked');
        var arr = data.split('-');
        for (var key in arr) {
            $("#" + name + "_" + arr[key]).attr('checked', true);
        }
    }
}

$(document).ready(function () {
    if (objForm != undefined) {
        for (var i = 0; i < objForm.length; i++) {
            if (objForm[i].validate == 1) {
                if (objForm[i].tp == 0) $("#" + objForm[i].name).attr("autov", "1");
                if (objForm[i].tp == 1 || objForm[i].tp == 2) $("input[name=" + objForm[i].name + "]").attr("autov", "1");
                if (objForm[i].tp == 3) {
                    $("#" + objForm[i].name).attr("autov", "1");
                    $("#" + objForm[i].name + "_confirm").attr("autov", "1");
                }
            }
        }
        $("[autov]").change(formValidate);
    }

    $('.asci_only').keypress(function (e) {
        if ($.browser.msie)
            return isAsci(e.keyCode)
        else
            return isAsci(e.charCode)
    });

    $('.phone').keypress(function (e) {
        if ($.browser.msie)
            return isPhone(e.keyCode)
        else
            return isPhone(e.charCode)
    });

    $('.number').keypress(function (e) {
        if ($.browser.msie)
            return isNumber(e.keyCode)
        else
            return isNumber(e.charCode)
    });


});

