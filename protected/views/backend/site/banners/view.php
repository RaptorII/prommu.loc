<?php  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));
?>
<style>
.imgcontent img {height:100px;}
</style>
<h3><i>Управление баннерами</i></h3>
    <div id="pop-up">
    	<h3 id="edit_title">Редактирование</h3>
    	<p>
    	<label for="ename">Название</label>
    	<input type="text" name="ename" id="ename" class="admform small">
    	</p>
      <p>
    	<label for="elink">Ссылка</label>
    	<input type="text" name="elink" id="elink" class="admform small">
    	</p>
      
      <!-- Uploader -->
  <img id="loading" src="/images/loading.gif" style="display:none;">

		<table cellpadding="0" cellspacing="0" class="tableForm">

		<thead>
			<tr>
				<td>Баннер</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
				<input id="fileToUpload" type="file" name="fileToUpload" />
			</td></tr>

		</tbody>
			<tfoot>
				<tr>
					<td><button class="btn" id="buttonUpload" onclick="return ajaxFileUpload();">Загрузить</button></td>
				</tr>
			</tfoot>

	</table>
	<div id="msg"></div>
      
    	<div id="btn_panel">
			<a href='#' class='btn mini' id='btn_save_form' onclick='EditForm()'>Сохранить</a>
			<a href='#' class='btn mini' id='btn_save_form' onclick="close_pop_up('#pop-up');">Отмена</a>
    	</div>
    	<input type="hidden" id="pkid" />
	</div>
	<div id="overlay"></div>
<?php
$criteria = new CDbCriteria();
                $pagination = array('pageSize'=>20,);

$dataProvider = new CActiveDataProvider('Banners', array('criteria' => $criteria, 'pagination' => $pagination, ));

//print_r($dataProvider); die;
$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'my-grid',
  'dataProvider'=>$dataProvider,
  'columns'=>array(
        array(
            'name' => '#',
            'value' => '$data->id',
            'type' => 'html',
            'htmlOptions'=>array('style'=>'width: 50px; text-align: center;'),
        ),
        array(
            'name' => 'Активно',
            'type' => 'raw',
            'value' => '"<div class=\"isblocked_".$data->ishide."\" onclick=\"setShow(".$data->id.", this)\"></div>"',
            'htmlOptions'=>array('style'=>'width: 70px;'),
        ),

        array(
            'name' => 'Название',
            'value'=> '"<img src=\"/content/".$data->file."\" height=100 /><br/>".$data->name',
            'type' => 'raw',
            'id'=>'$data->id',
            'htmlOptions' => array('class'=>'m_element'),
        ),
        array(
            'name' => 'Ссылка',
            'value'=> 'CHtml::button($data->url, array("onclick"=>"open_pop_up(\"#pop-up\");edt(".$data->id.")", "id"=>"btn_".$data->id, "style"=>"width:100%"))',
            'type' => 'raw',
            'id'=>'$data->id',
            'htmlOptions' => array('class'=>'m_element'),
        ),

)));

echo CHtml::endForm(); 
?>
<a href='#' class='btn' id='btn_add' style='width:50px' onclick="open_pop_up('#pop-up');edt(0);">Создать</a>

 

<script type="text/javascript">
var file_banner;

function Cancel()
{
	$("#editform").hide();
	$('#btn_add').show();
}

function ShowForm(id) {
	$('#btn_add').hide();
    $('#editform').show();
}


function isEmpty(obj) {
	if (typeof obj == 'undefined' || obj === null || obj === '') return true;
	if (typeof obj == 'number' && isNaN(obj)) return true;
	if (obj instanceof Date && isNaN(Number(obj))) return true;
	return false;
}


function open_pop_up(box) {
	$("#overlay").show();
	$(box).center_pop_up();
	$(box).show(500);
}

function close_pop_up(box) {
	$(box).hide(500);
	$("#overlay").delay(550).hide(1);
}

function edt(id) {
  if(id==0)
  {
    // Insert mode
    file_banner="";
    $("#ename").val("");
    $("#pkid").val(0);
    $("#edit_title").text("Добавить");    
  }
  else
  {
    // Edit mode
    var name = $("#btn_"+id).val();
    $("#ename").val(name);
    $("#pkid").val(id);
    $("#edit_title").text("Редактирование");
  }
}

function EditForm() {
	var id = $("#pkid").val();
   		var address="/admin/ajax/EditBanners";
      if(id==0) address="/admin/ajax/AddBanners";
   		$.ajax({
        type:'GET',
        url:address+'?name='+$("#ename").val()+'&linkbanner='+$("#elink").val()+'&filebanner='+file_banner+'&id='+id,
        cache: false,
        dataType: 'text',
        success:function (data) {
          if(id>0) {
        	 $("#btn_"+id).val($("#ename").val());
        	 $("#ename").val('');
          }
          else {
            location.href="";
          }
        	close_pop_up('#pop-up');
        },
        error: function(data){
    	   alert("Download error!");
        }
    	});

}

$(document).ready(function(){

	jQuery.fn.center_pop_up = function(){
		this.css('position','absolute');
		this.css('top', ($(window).height() - this.height()) / 2+$(window).scrollTop() + 'px');
		this.css('left', ($(window).width() - this.width()) / 2+$(window).scrollLeft() + 'px');
	}

});

function setShow(id, element)
{
    var address="/admin/ajax/SetActiveBanners";
    
    $.ajax({
        type:'GET',
        url:address+'?id='+id,
        cache: false,
        dataType: 'text',
        success:function (data) {
        	element.setAttribute('class', 'isblocked_'+data);
        },
        error: function(data){
    	   alert("Download error!");
        }
    	});
    
}


	function ajaxFileUpload()
	{
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
							$('#msg').html(data.msg);
              file_banner = data.name;
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
  

</script>