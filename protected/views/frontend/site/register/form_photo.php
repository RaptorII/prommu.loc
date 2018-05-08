<?php
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/fotorama.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerCssFile('/css/fotorama.css');
/*
$cookie_uid = '';
if(isset(Yii::app()->request->cookies['uid'])){
  $cookie_uid = Yii::app()->request->cookies['uid']->value;
}
*/
$uid = Share::getUserID();
if($uid==0) return;
?>
<form method="post" enctype="multipart/form-data">
<br/>

<div class="mymenu">
  <ul id="breadcrumbs-one">
    <li class="active"><a href="/site/resume">Резюме</a></li>
    <li><a href="/site/Photo" class="current">Мои фото</a></li>
  </ul>
</div>

<h3>Резюме</h3>


<table>
<tr>
<td>
<?php
        $res = Yii::app()->db->createCommand()
        ->select("id, photo")
        ->from('photo')
        ->where("id_user = :uid", array(':uid'=>$uid))
        ->queryAll();
    echo '<ul class="photolist">';
    $i=0;
    foreach($res as $row)
    {
       //echo '<a href="/content/'.$row['photo'].'"><img src="/content/thumbs/'.$row['photo'].'"></a>';
       echo '<li><a href="javascript:SPhoto('.$i.')"><img src="/content/thumbs/'.$row['photo'].'"></br>';
       echo '<a href="javascript:FirstPhoto('.$row['id'].')" title="Сделать главной"><img src="/images/ico/first.png"/>';
       echo '<a href="javascript:DelPhoto('.$row['id'].')" title="Удалить"><img src="/images/ico/del.png"/></li>';
       $i++;
    }
echo '</ul>';
echo '<input id="fileToUpload" type="file" name="fileToUpload" title="Выберите файл" style="display:none" onchange="ajaxFileUpload()"/>';
echo '<img id="loading" src="/images/loading.gif" style="display:none;">';
echo '<button class="btn" id="buttonUpload" onclick="return Upload();" title="Загрузить">Загрузить</button>';
echo "\r\n</td><td>\r\n";
echo '<div class="fotorama" id="my-fotorama" data-width="800" data-height="500">';
    foreach($res as $row)
    {
       //echo '<a href="/content/'.$row['photo'].'"><img src="/content/thumbs/'.$row['photo'].'"></a>';
       echo '<a href="/content/'.$row['photo'].'"><img src="/content/thumbs/'.$row['photo'].'"></a>';
    }

    echo "\r\n</div>\r\n";
?>
</td>
</tr>
</table>
<input type="hidden" name="op" id="op">
<input type="hidden" name="id" id="id">
<input type="hidden" name="photo_name" id="photo_name">
<input type="submit" id="btn_submit" value="submit" style="display:none">
</form>
<script>
var fotorama;

function SPhoto(i)
{
  fotorama.trigger('showimg', i);
}

function DelPhoto(id)
{
  $("#id").val(id);
  $("#op").val('DEL');
  $("#btn_submit").click();
}


  function Upload()
  {
    $("input[type='file'").trigger('click');
    return false;
  }

	function ajaxFileUpload()
	{
    //alert($("#fileToUpload").val());
    if($("#fileToUpload").val()=='')
    {
       $("input[type='file'").trigger('click');
       //$("#fileToUpload").click();
       return;
    }

    //alert('Start');
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
				url:'/uploads/doajaxfileupload.php',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							//alert(data.msg);
							//$('#msg').html(data.msg);
              $('#photo_name').val(data.name);
              $('#op').val('ADD');
              $("#btn_submit").click();
              //$('#photo').attr("src","/content/"+data.name);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)

		return false;
	}


$(document).ready(function() {
  fotorama = $('#my-fotorama').fotorama();

});
</script>