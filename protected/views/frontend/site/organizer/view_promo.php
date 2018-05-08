<div id="container_demo" >
  <div id="wrapper">
  <h3>Статус занятости</h3>

 <SELECT id="mnt" name="mnt" onchange="genCal()">
  <OPTION value="1">январь</OPTION>
  <OPTION value="2">февраль</OPTION>
  <OPTION value="3">март</OPTION>
  <OPTION value="4">апрель</OPTION>
  <OPTION value="5">май</OPTION>
  <OPTION value="6">июнь</OPTION>
  <OPTION value="7">июль</OPTION>
  <OPTION value="8">август</OPTION>
  <OPTION value="9">сентябрь</OPTION>
  <OPTION value="10">октябрь</OPTION>
  <OPTION value="11">ноябрь</OPTION>
  <OPTION value="12">декабрь</OPTION>
  </SELECT>
  <div id="pan_calendar"></div>
  <div class="clear"></div>
  <br/>
  <a href="javascript:SaveOrg()" class="btn btn-success">Сохранить</a>
  
</div>
</div>  
  
<script>
var myDays;
var cnt_days;

function readOrg() {
    //getMonth
    var m = new Date().getMonth()+1;
    for(var i=1; i<m; i++) $('#mnt [value="'+i+'"]').attr('disabled',true);
    $('#mnt [value="'+m+'"]').attr('selected',true);
    
    if(token!=undefined)
    {
      // Read data
      var val = "month:"+$('#mnt').val();
      var xen = encript(val,token);
      link = site_url+"?cmd=GET_ORGANIZER_PROMO&mode=3&uid="+uid+"&value="+xen+"&callback=?";
			jsonp(link, function (data) {
          myDays = data;
          genCal();        
      });
    }
}

function SaveOrg()
{
  if(token!=undefined)
  {
      var val = JSON.stringify(myDays);
      val = '{"tms":'+val+'}';
      var xen = encript(val,token);
      link = site_url+"?cmd=SAVE_ORGANIZER_PROMO&mode=3&uid="+uid+"&value="+xen+"&callback=?";
			jsonp(link, function (data) {
        if(data.value>0)
        {
          showPopUp("Сообщение","Данные успешно сохранены");
               
        } else {
          showPopUp("Ошибка","Ошибка передачи данных");
        } 
      });
  }  
}
 
function genCal()
{
  var html = [];
  var day = 0;
  var val = $("#mnt option:selected").val(); 
  var mon=parseInt(val);
  var year=2013;
  var dd, st;
  var days = getCountDays(year, mon-1)+1; // кол-во дней в месяце
  cnt_days = countDays(year,mon-1); // кол-во дней с начала года
  //alert(cnt_days);
  for(var i=0; i<days; i++)
  {
    html.push('<div class="br',i%2,'">');
    for(var j=0; j<24; j++)
    {
      st='';
            dd=getDay(day+i,mon,year);
            if(dd=='вс') st=' free';

      if(i==0) {
        if(j==0) html.push('<div class="bch"></div>');        
          html.push('<div class="bh',j%2,'">',int2(j),'-',int2(j+1),'</div>');
      } else {
          if(j==0) {
            html.push('<div class="bch',st,'"><b>',day+i,'</b><br/>',dd,'</div>');
            html.push('<div class="bc',j%2,st,checkMyDay(cnt_days,i,j),'" onclick="chTm(',i,',',j,')"></div>');
          }
          else
            html.push('<div class="bc',j%2,st,checkMyDay(cnt_days,i,j),'" onclick="chTm(',i,',',j,')"></div>');
      }
    }
    html.push('</div>');
  }
  $("#pan_calendar").html(html.join(''));
}

//.......the number of days in the month
function getCountDays(year, month){
  return 33 - new Date(year, month, 33).getDate();
}


function showDeb()
{
  //...........DEBUG........
  for(var i=0; i<myDays.length; i++)
  {
    console.log('i='+i+', day='+myDays[i].d+', time='+myDays[i].t);
  }
  console.log('------------');
}

function chTm(day,tm)
{
  console.log('day:'+day);
  var bt = new Array(0,1,2,4,8,16,32,64,128,256,512,1024,2048,4096,8192,16384,32768,65536,131072,262144,524288,1048576,2097152,4194304,8388608,16777216);
  //...search day
  var ch_idx=-1;
  for(var i=0; i<myDays.length; i++)
  {
    if(parseInt(myDays[i].d) == day+cnt_days) {ch_idx=i; break;}
  }
  
  if(ch_idx == -1) {
    myDays.push({"d":day+cnt_days,"t":bt[tm+1]});
  }
  else
    myDays[ch_idx].t = myDays[ch_idx].t ^ bt[tm+1];
   
  genCal(); 
}

function getDay(day,mon,year){
 var days = new Array("вс","пн","вт","ср","чт","пт","сб");
 day=parseInt(day, 10); //если день двухсимвольный и <10 
 mon=parseInt(mon, 10); //если месяц двухсимвольный и <10 
 var a=parseInt((14-mon)/12);
 var y=year-a;
 var m=mon+12*a-2;
 var d=(7000+parseInt(day+y+parseInt(y/4)-parseInt(y/100)+parseInt(y/400)+(31*m)/12))%7;
 return days[d];
}

function int2(v)
{
  if(v<10) return '0'+v;
  else
  {
    if(v==24) v='00';
    return v;
  }
}

//.....the number of days in the year
function countDays(year,month){
        var now =  new Date(year, month, 1);
        setdate = new Date(year, 0, 1, 0, 0);  
        day = (now - setdate) / 1000 / 60 / 60 / 24;
        day = Math.round(day);
        return day;
}

function checkMyDay(days, dd, tm)
{  
  tm++;
  var bt = new Array(0,1,2,4,8,16,32,64,128,256,512,1024,2048,4096,8192,16384,32768,65536,131072,262144,524288,1048576,2097152,4194304,8388608,16777216);
  //if( tm==24) return '!';
  for(var i=0; i<myDays.length; i++)
  {
    if(parseInt(myDays[i].d) == days+dd) {
      // день совпал
      var tmv = parseInt(myDays[i].t);
      var res = tmv & bt[tm] ;
      //console.log('time:'+tm+', byte time='+bt[tm]+', mask='+tmv, 'result='+res) 
      if(res == 0) return '';
        else
        return ' active';
    }
  }
  return '';
}
</script>