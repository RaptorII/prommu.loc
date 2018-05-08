var site_url = "http://92.63.111.184:8080/";
//var site_url = "http://127.0.0.1:8080/";
var ip;
var u_type = -1;
var token;
var uid;
var login;
var rating;
var cnt_response=0;
var myLatlng; // = new google.maps.LatLng(-34.397, 150.644);
var warning_access = 'Не найдет пользователь с таким логином/паролем!';

/***************************************
 * Encode-Decode script
 * ************************************/

function Access() {
	var lg = $('#r_login').val();
	var ps = $('#r_passw').val();
	var keys = '00000';
	var link;
	var ip;
  var jsn;
	if(validate_access(lg,ps))
	{
    link = site_url+"?cmd=GET_IP&callback=?";
		jsonp(link, function (data) {
			ip = data.key;
			var val = "login:"+lg+",passw:"+ps;
      var xen = encript(val,ip);
			link = site_url+"?cmd=AUTH_USER&mode=2&value="+xen+"&callback=?";
			jsonp(link, function (data) {
        if(data.access_token=='undefined')
        {
          //showPopUp('Предупреждение', 'Не найдет пользователь с таким логином/паролем');
          showMessAuth(warning_access);
        } else {
            u_type = data.type;
            saveToken(data.access_token, data.uid, data.type, ip, data.login, data.rating);
            saveCountResp(data.count_resp);
            ShowAuth();
            getTopMenu();
        }
        });
		});
	}
}

function AccessCid(cid, cname) {
	var lg = '';
	var ps = '';
	var keys = '00000';
	var link = '';
	var ip = '';
  var jsn = '';

		link = site_url+"?cmd=GET_IP&callback=?";
		jsonp(link, function (data) {
      ip = data.key;
			var val = "cid:"+cid+",cname:"+cname;
			var xen = encript(val,ip);

			link = site_url+"?cmd=AUTH_USER_CID&mode=2&value="+xen+"&callback=?";
			jsonp(link, function (data)
      {
        if(data.access_token=='undefined')
        {
          showPopUp('Предупреждение', 'Не найдет пользователь с таким логином/паролем');
        } else {
            u_type = data.type;
            saveToken(data.access_token, data.uid, data.type, ip, data.login, data.rating);
            getTopMenu();
            ShowAuth();
        }

      });
		});
}

function showMessAuth(mess) {
  $("#mess_auth").text(mess);
  $("#mess_auth").show("slow");
}

function AccessEmail(email) {
	var lg = '';
	var ps = '';
	var keys = '00000';
	var link = '';
	var ip = '';
  var jsn = '';

		link = site_url+"?cmd=GET_IP&callback=?";
		jsonp(link, function (data) {
      ip = data.key;
			var val = "email:"+email;
			var xen = encript(val,ip);

			link = site_url+"?cmd=AUTH_USER_EMAIL&mode=2&value="+xen+"&callback=?";
			jsonp(link, function (data)
      {
        if(data.access_token=='undefined')
        {
          showPopUp('Предупреждение', 'Не найдет такой пользователь');
        } else {
            u_type = data.type;
            saveToken(data.access_token, data.uid, data.type, ip, data.login, data.rating);
            getTopMenu();
            ShowAuth();
        }

      });
		});
}



function validate_access(login, passw) {
	var result = true;
	if(isEmpty(login)) {
		$('#r_login').attr({style:"background:#ffff00;"});
		result = false;
	} else {
	 	$('#r_login').attr({style:"background:#fff;"});
	}

	if(isEmpty(passw)) {
		$('#r_passw').attr({style:"background:#ffff00;"});
		result = false;
	} else {
	 	$('#r_passw').attr({style:"background:#fff;"});
	}
	return result;
}


function isEmpty(obj) {
	if (typeof obj == 'undefined' || obj === null || obj === '') return true;
	if (typeof obj == 'number' && isNaN(obj)) return true;
	if (obj instanceof Date && isNaN(Number(obj))) return true;
	return false;
}

function jsonp (url, callback) {
    var name = "jsonp" +  +new Date,
        script = document.createElement("script"),
        head = document.getElementsByTagName("head")[0];
    window[name] = callback;
console.info( 'url.replace(/=\?/, "=" + name)', url.replace(/=\?/, "=" + name) );
    script.src = url.replace(/=\?/, "=" + name);
    try
    {
      head.insertBefore(script, head.firstChild);
    }
    catch(e) {
      alert("Error network");
    }
}


function encript(input, key) {
  //input = 'n=вася&cmd=SEARCH_ALL_RA&mode=2';
  var mult = '';
	var kmult = '';
	var code=0;
	//var str = ''
	var enc_chr = '';
	var enc_str = '';
	var len = input.length;
	var j=0;
	for (var i=0; i<len; i++) {
   	mult = input.charAt(i);
		kmult = key.charAt(j);
		code = mult.charCodeAt(0) ^ kmult.charCodeAt(0);
		enc_str += code.toString(16)+'-';
		j++;
		if(j>=key.length) j=0;
	}
	return enc_str.substring(-1);
}


function myXOR(a,b) {
  return ( a ? 1 : 0 ) ^ ( b ? 1 : 0 );
}



function getIp()
{
 	link = site_url+"?cmd=GET_IP&callback=?";
	jsonp(link, function (data) {
	  ip = data.key;
	});
}


function saveToken(token, uid, utype, ip, login, rating)
{
    var current_date = new Date;
    var cookie_year = current_date.getFullYear ( );
    var cookie_month = current_date.getMonth ( );
    var cookie_day = current_date.getDate ( ) + 1;

/*
    set_cookie("token", token, cookie_year, cookie_month, cookie_day);
    set_cookie("uid", uid, cookie_year, cookie_month, cookie_day);
    set_cookie("utype", utype, cookie_year, cookie_month, cookie_day);
    set_cookie("rating", rating, cookie_year, cookie_month, cookie_day);
    set_cookie("ip", ip, cookie_year, cookie_month, cookie_day);
    set_cookie("login", login, cookie_year, cookie_month, cookie_day);
*/
    localStorage.setItem("token", token);
    localStorage.setItem("uid", uid);
    localStorage.setItem("utype", utype);
    localStorage.setItem("rating", rating);
    localStorage.setItem("ip", ip);
    localStorage.setItem("login", login);
}

function saveCountResp(countResp) {
    var current_date = new Date;
    var cookie_year = current_date.getFullYear ( );
    var cookie_month = current_date.getMonth ( );
    var cookie_day = current_date.getDate ( ) + 1;
    //set_cookie("count_resp", countResp, cookie_year, cookie_month, cookie_day);
    localStorage.setItem("count_resp", countResp);
    cnt_response = countResp;
}

function GetMemberToken()
{
   token = get_cookie("token");
   uid = get_cookie("uid");
   login = get_cookie("login");
   u_type = get_cookie("utype");
   rating = get_cookie("rating");
   cnt_response = get_cookie("count_resp");

   if(ip==undefined) ip = get_cookie("ip");
   //showPopUp('TOKEN', token);
}

function Exit()
{
  localStorage.removeItem('token');
  localStorage.removeItem('uid');
  localStorage.removeItem('login');
  localStorage.removeItem('rating');
  localStorage.removeItem('count_resp');
  localStorage.removeItem('ip');

  location.href="/";
}

function ClearCookie() {
  //$.cookie('promo', null);
  localStorage.removeItem('token');
  localStorage.removeItem('uid');
  localStorage.removeItem('login');
  localStorage.removeItem('rating');
  localStorage.removeItem('count_resp');
  localStorage.removeItem('ip');
}

function greetUser()//при загрузке страницы,реагирует на наличие имени
      {
        if (userName)
        {
            userName = get_cookie("bug_username");//ЭТА ЧАСТЬ //НЕ ВЫПОЛНЯЕТСЯ
            msg = "сообщение" + userName;
            toolTip(msg);
        }
        else
        {
            msg = "сообщение";
        }
      }

function get_cookie ( cookie_name )
{
  return localStorage.getItem(cookie_name);
}

/*
function get_cookie ( cookie_name )//получение значения куки
{
  var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );

  if ( results )
    return ( unescape ( results[2] ) );
  else
    return null;
}
*/

/*
function set_cookie ( name, value, exp_y, exp_m, exp_d, path, domain, secure ) //установка куки
{
  var cookie_string = name + "=" + escape ( value );

  if ( exp_y )
  {
    var expires = new Date ( exp_y, exp_m, exp_d );
    cookie_string += "; expires=" + expires.toGMTString();
  }

  if ( path )
        cookie_string += "; path=" + escape ( path );

  if ( domain )
        cookie_string += "; domain=" + escape ( domain );

  if ( secure )
        cookie_string += "; secure";

  document.cookie = cookie_string;
}
*/

function getPath()
{
  var p = document.location.href;
  if(p.substring(p.length-1)=="/") p = p.substring(0,p.length-1);
  var arr = p.split('/');
  if(arr[arr.length-1]=='#')
    return arr[arr.length-2];
  else
    return arr[arr.length-1];
}


 /**
	 * jQuery BASE64 functions
	 *
	 * 	<code>
	 * 		Encodes the given data with base64.
	 * 		String $.base64Encode ( String str )
	 *		<br />
	 * 		Decodes a base64 encoded data.
	 * 		String $.base64Decode ( String str )
	 * 	</code>
	 *
	 */

	(function($){

		var keyString = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

		var uTF8Encode = function(string) {
			string = string.replace(/\x0d\x0a/g, "\x0a");
			var output = "";
			for (var n = 0; n < string.length; n++) {
				var c = string.charCodeAt(n);
				if (c < 128) {
					output += String.fromCharCode(c);
				} else if ((c > 127) && (c < 2048)) {
					output += String.fromCharCode((c >> 6) | 192);
					output += String.fromCharCode((c & 63) | 128);
				} else {
					output += String.fromCharCode((c >> 12) | 224);
					output += String.fromCharCode(((c >> 6) & 63) | 128);
					output += String.fromCharCode((c & 63) | 128);
				}
			}
			return output;
		};

		var uTF8Decode = function(input) {
			var string = "";
			var i = 0;
			var c = c1 = c2 = 0;
			while ( i < input.length ) {
				c = input.charCodeAt(i);
				if (c < 128) {
					string += String.fromCharCode(c);
					i++;
				} else if ((c > 191) && (c < 224)) {
					c2 = input.charCodeAt(i+1);
					string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
					i += 2;
				} else {
					c2 = input.charCodeAt(i+1);
					c3 = input.charCodeAt(i+2);
					string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
					i += 3;
				}
			}
			return string;
		}

		$.extend({
			base64Encode: function(input) {
				var output = "";
				var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
				var i = 0;
				input = uTF8Encode(input);
				while (i < input.length) {
					chr1 = input.charCodeAt(i++);
					chr2 = input.charCodeAt(i++);
					chr3 = input.charCodeAt(i++);
					enc1 = chr1 >> 2;
					enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
					enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
					enc4 = chr3 & 63;
					if (isNaN(chr2)) {
						enc3 = enc4 = 64;
					} else if (isNaN(chr3)) {
						enc4 = 64;
					}
					output = output + keyString.charAt(enc1) + keyString.charAt(enc2) + keyString.charAt(enc3) + keyString.charAt(enc4);
				}
				return output;
			},
			base64Decode: function(input) {
				var output = "";
				var chr1, chr2, chr3;
				var enc1, enc2, enc3, enc4;
				var i = 0;
				input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
				while (i < input.length) {
					enc1 = keyString.indexOf(input.charAt(i++));
					enc2 = keyString.indexOf(input.charAt(i++));
					enc3 = keyString.indexOf(input.charAt(i++));
					enc4 = keyString.indexOf(input.charAt(i++));
					chr1 = (enc1 << 2) | (enc2 >> 4);
					chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
					chr3 = ((enc3 & 3) << 6) | enc4;
					output = output + String.fromCharCode(chr1);
					if (enc3 != 64) {
						output = output + String.fromCharCode(chr2);
					}
					if (enc4 != 64) {
						output = output + String.fromCharCode(chr3);
					}
				}
				output = uTF8Decode(output);
				return output;
			}
		});
	})(jQuery);


  // return array 1..7
  function DecodeToBite(intVal) {
    var bt = new Array(0,1,2,4,8,16,32,64,128);
    var out = [];
    for(var i=1;i<8;i++) {
      if(bt[i] & intVal) out.push(i);
    }
    return out;
  }

  function EncodeBiteToInt(strVal) {
     var s = strVal.split(',');
     var bt = new Array(0,1,2,4,8,16,32,64,128);
     var res = 0;
     for(var i=0; i<s.length; i++)
     {
        res = res+bt[parseInt(s[i])];
     }
     return res;
  }

  String.prototype.ProtectJs = function(){
    var s = this.replace(/,/g,"\\u002c");
    s = s.replace(/"/g,"\\u0022");
    s = s.replace(/&/g,"\\u0026");
    s = s.replace(/:/g,"\\u003a");
    return s;
  }

  function checkBR(str) {
    return str.replace(/\n/g, "<br />");
  }

  function urldecode(str) {
    return decodeURIComponent((str+'').replace(/\+/g, '%20'));
  }


function sendMail(m,l)
{
     var url = '/ajax/Mail/?mail='+m+'&l='+l;
       $.ajax({
        type:'GET',
        async : true,
        url:url,
        cache: false,
        contentType: "application/json; charset=utf-8",
        dataType: 'json',
        success:function (data) {
          showPopUp('',data.message);
        },
        error: function(data){
          alert("Download error!");
        }
    	});
}


// ---- map ----
function initMap(text) {
  //var text = $("#vacation_name").val()+"\r\n"+$("#phone").val()+"\r\n"+$("#email").val();
  var myOptions = {
        zoom: 16,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

  var marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    title: text
});

}

function getPoint()
{
    if(isMap)
    {
      $("#map_canvas").hide();
      isMap = false;
      return;
    }
    isMap = true;
    var city = $("#city").val();
    var address = $("#address").val();
    var text = $("#vacation_name").val()+"\r\n"+$("#phone").val()+"\r\n"+$("#email").val();
	console.log('city: '+city+'; address: '+address);
    ReadMap(city+', '+address, text);
}

function ReadMap(address, text)
{
    var url = "http://maps.googleapis.com/maps/api/geocode/json?address="+address+"&sensor=false&language=ru";
 	   $.ajax({
        type:'GET',
        url:url,
        cache: false,
        dataType: 'json',
        success:function (data) {
          myLatlng = new google.maps.LatLng(data.results[0].geometry.location.lat, data.results[0].geometry.location.lng);
//new google.maps.LatLng(-34.397, 150.644);

          $("#map_canvas").show();
          initMap(text);
        },
        error: function(data){
    	   alert("Download error!");
        }
    	});
}

function ShowMech(val)
{
    var txt = []
    var arrM = val.split('-');
    for(i=0; i<arrM.length; i++)
    {
        txt.push(mechInfo[arrM[i]], '; ');
    }
    return(txt.join(''));
}
