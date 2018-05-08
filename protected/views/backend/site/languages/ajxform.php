<form method="POST" id="form">
<?php
  //$clr = array ('#FFFF00','#99CCFF','99FF00');
  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/access.js', CClientScript::POS_HEAD);
  $res = Yii::app()->db->createCommand()
        ->select("count(*) as cnt")               
        ->from('lang_turbo')
        ->where("lang='ru'")
        ->queryRow();
  $cnt = $res['cnt'];

  $res2 = Yii::app()->db->createCommand()
        ->select("name, title")               
        ->from('lang')
        ->order('name')
        ->queryAll();

  $res = Yii::app()->db->createCommand()
        ->select("id, grp, lang, key, value")               
        ->from('lang_turbo')
        ->order('grp, key, lang')
//        ->where('uid = :uid', array(':uid'=>$cookie))
        ->queryAll();

  $column = $cnt/4;
  if($column<10) $column=10;
  ?>
  <a href="#" class="btn small" onclick="showAdd()">Добавить</a>
  <a href="#" class="btn small" onclick="createLangCache()">Обновить кеш</a>
  <div id="add_panel" style="display:none; position:absolute; background:#E0E0E0; border:2px solid #A0A0A0; padding:3px; width:300px;">  
  <span style="padding:0 10px 0 5px">Группа</span><input type="text" id="grp" style="width:50px"/><span style="padding:0 10px 0 20px">Ключ</span><input type="text" id="key" style="width:100px"/><br/>
  <?php
  foreach($res2 as $rw)
  {
    echo '<span style="padding:0 10px 0 5px">'.$rw['name'].'</span><input type="text" id="val_'.$rw['name'].'" style="width:250px"/><br/>';
  }
  echo '<a href="#" class="btn small" onclick="addLang()">Сохранить</a><a href="#" class="btn small" onclick="Cancel()">Отмена</a>';
  echo '</div>';
  
  $clr = array ('#ffff00','#ccffff','#ffcc99', '#99cccc', '#ffccff', '#aaccff', '#ccff00', '#0060ff'); 
  echo '<div id="lang_panel">';
  echo '<div style="width:200px;float:left;vertical-align: tex-top;border:2px solid #fff">';
  //echo '</div>';  
  
  $cnt=0;
  $old = '';
  $cnt_clr = 0;
  $old_grp = $res[0]['grp']; 
  foreach($res as $row)
  {
    // ---BLOCK---
    //echo $row['key'].'.'.$old.'::';    
    if($old!=$row['key']) {
      $old = $row['key'];
      if($cnt>0) echo '</div>';
      
      if($cnt>$column)
      {
        // Next column
        echo '</div>';
        echo '<div style="width:200px;float:left;vertical-align: tex-top;border:2px solid #fff">'."\r\n";
        $cnt=0;            
      }
      $cnt++;
      if($old_grp!=$row['grp']) {
          $old_grp=$row['grp'];
          $cnt_clr++;
      }      
      echo '<div style="background-color:'.$clr[$cnt_clr].';" class="bb" id="b'.$row['grp'].'_'.$row['key'].'">'."\r\n";
      echo '<h4>'.$row['grp'].'_'.$row['key'].'<a href="javascript:Del(\''.$row['grp'].'_'.$row['key'].'\')" style="float:right" title="Delete">[X]</a></h4>'."\r\n";
    }
    //if($old_grp!=$row['grp']) $old=' ';      
    echo '<p><a href="#" id="'.$row['lang'].'_'.$row['grp'].'_'.$row['key'].'">';
    echo $row['lang'].'</a><span>'.$row['value'].'</span><input type="text" id="e'.$row['lang'].'_'.$row['grp'].'_'.$row['key'].'" onchange="do_edt(this)"/></p>'."\r\n";        
  }
  
  echo '</div>';
?>
<div style="clear:both"></div>
</div>

<script type="text/javascript">
var lng=[<?php
  foreach($res2 as $rw)
  {
    echo '"'.$rw['name'].'",';
  }
 ?>"-"];

function createLangCache()
{
  $.ajax({
    type:'GET',
    url:'/admin/ajax/GenerateLangCache',
    cache: false,
    dataType: 'json',
    success:function (data) {
      //alert(data.message);
      if(data.message=='OK')
      {
        showPopUp('Сообщение','Файлы обновлены');
      }
    },
    error: function(data){
    alert("Download error!");
    $("#btn_add").attr("disabled", "false");
    }
    }); 
}
 
function edt(elem)
{
  //alert(1);
  var id = elem.id;
  //alert(id);
  var v = $("#"+id).next().text();
  $("#"+id).next().hide();
  $("#"+id).next().next().val(v);
  $("#"+id).next().next().show();
  //alert(v);
  //$("#"+id).html('<input type="text" value="'+v+'" id="e'+id+'" />');
  //$("#"+id).next().on("change",do_edt(this));
}

function addLang()
{
  var key = $("#key").val();
  var grp = $("#grp").val();  
  if(key=='' || grp=='')
  {
    alert('заполните поля!');
    return;
  }
  
  var s='';
  for(var i=0; i<lng.length-1; i++)
  {
    s+=lng[i]+'::'+$("#val_"+lng[i]).val()+'|';    
  }
  
    $.ajax({
    type:'GET',
    url:'/admin/ajax/AddLang?key='+key+"&grp="+grp+"&words="+s,
    cache: false,
    dataType: 'text',
    success:function (data) {
    location.href='';
    },
    error: function(data){
    alert("Download error!");
    $("#btn_add").attr("disabled", "false");
    }
    });

  //alert(s);
}

function Del(key)
{
  if(confirm('Вы действительно хотите удалить запись '+key))
  {
    $.ajax({
    type:'GET',
    url:'/admin/ajax/DeleteLang?key='+key,
    cache: false,
    dataType: 'text',
    success:function (data) {
      //showPopUp("", data.message);
      $("#b"+key).hide();
    },
    error: function(data){
    alert("Download error!");
    $("#btn_add").attr("disabled", "false");
    }
    });
  }
}

function do_edt(elem)
{
  var id = elem.id;
  var s = $("#"+id).val();
  $("#"+id.substr(1)).next().text(s);
  $("#"+id).hide();
  $("#"+id.substr(1)).next().show();

  var v = id.split('_')
  //alert("key="+v[2]+"; grp="+v[1]+"; lang="+v[0].substring(1));
    $.ajax({
    type:'GET',
    url:'/admin/ajax/EditLang?key='+v[2]+"&grp="+v[1]+"&lang="+v[0].substring(1)+"&word="+s,
    cache: false,
    dataType: 'text',
    success:function (data) {
    //showPopUp("", data.message);
    },
    error: function(data){
    alert("Download error!");
    $("#btn_add").attr("disabled", "false");
    }
    });
}

function showAdd()
{
  //$("#grp").show();
  //$("#key").show();
  $("#add_panel").show(200);
}

function Cancel()
{
  $("#add_panel").hide(200);
}

$(document).ready(function(){

  $(".bb a").attr("onclick","edt(this)");
  //$('input[type="text"]').attr("display","none");
  $('#lang_panel input[type="text"]').hide();
  //$('input[type="text"]').on("change",do_edt(this));
  
  window.event.cancelBubble = true;

});


  function open_pop_up(box) {
   $("#overlay").css("height",$(document).height());
	 $("#overlay").show();
	 $(box).center_pop_up();
	 $(box).show(500);
  }

  function close_pop_up(box) {
	 $(box).hide(500);
	 $("#overlay").delay(550).hide(1);
  }

  function showPopUp(title, message) {
    $("#pop-up-mess h3").html(title);
    $("#pop-up-mess p").html(message);
    open_pop_up("#pop-up-mess");  
  }

 	jQuery.fn.center_pop_up = function(){
		this.css('position','absolute');
		//this.css('top', ($(window).height() - this.height()) / 2+$(window).scrollTop() + 'px');
    this.css('top', '400px');
		this.css('left', ($(window).width() - this.width()) / 2+$(window).scrollLeft() + 'px');
	}


</script>

</form>