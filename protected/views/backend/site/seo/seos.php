<h3 class="box-title">SEO утилита - Страницы </h3>

<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="http://prommu.com/admin/site/vacancy?seo=1#">Вакансии</a>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="http://prommu.com/admin/seo?seo=1" >Страницы сайта</a>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="http://prommu.com/admin/site/users?seo=1" >Анкеты</a>
<div class="modal hide fade" id="Modal" tabindex="-1" role="dialog">
	<div class="modal-header">
		<h3 id="edit_title">Редактирование</h3>
	</div>
	<div class="modal-body">
		<p>
			<label for="ename">Название</label>
			<div class="controls input-append">
				<input type="text" name="ename" id="ename" class="admform span6"><span class="add-on"><i class="icon-tag"></i></span>
			</div>
		</p>
	</div>
	<div class="modal-footer">
		<button class="btn btn-warning" data-dismiss="modal">Закрыть</button>
		<button class="btn btn-success" data-dismiss="modal" onclick='EditForm()'>Сохранить</button>
	</div>
	<input type="hidden" id="pkid" />
</div>

<div class="span11" id="seo-content">
	<?php
	$criteria = new CDbCriteria();
	$pagination = array('pageSize'=>20,);

	$dataProvider = new CActiveDataProvider('City', array('criteria' => $criteria, 'pagination' => $pagination, ));

//print_r($dataProvider); die;
	$this->widget('zii.widgets.grid.CGridView', array
	(
		'id'=>'my-grid',
		'dataProvider'=>$model->search(),
		'filter'=>$model,
		'itemsCssClass' => 'table table-bordered table-hover dataTable',
    	'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style'=>'padding: 10px;'),
		'columns'=>array
		(
			array
			(
				'name' => 'url',
				'value' => 'ShowUrl($data->url)',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'width: 50px; text-align: left; white-space: nowrap'),
				'filter'=>'',
			),
			array
			(
				'name' => 'Раздел',
				'value' => 'ShowType($data->url)',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'text-align: left;'),
				'filter'=>'',
			),
			array
			(
				'name' => 'Дата создания',
				'value' => '$data->crdate',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'text-align: left;'),
				'filter'=>'',
			),
			array
			(
				'name' => 'Дата изменения',
				'value' => '$data->mdate',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'text-align: left;'),
				'filter'=>'',
			),
			array
			(
				'name' => 'Отображение',
				'value' => 'getIndex($data->index,$data->id)',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'text-align: center;'),
				'filter'=>'',
			),
	
			
			 array(
            'name' => 'Редактор',
            'value' => 'ShowEdit($data->id)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
        	),
			 array(
            'name' => 'Удалить',
            'value' => 'ShowDelete($data->id)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
        	),
	    	
		),
	));

function ShowType($id){
	
	if(strpos($id, 'ankety') !== false) {
		return "ankety";
	}
	elseif(strpos($id, 'vacancy') !== false) {
		return "vacancy";
	}
	elseif(strpos($id, 'services') !== false) {
		return "services";
	}
	elseif(strpos($id, 'news') !== false) {
		return "news";
	}
	else{$id = explode("/", $id);  return $id[0];}
    

}

function ShowEdit($id) {
    return  '<button type="button" class="btn btn-default"><a href="/admin/site/seoEdit/' . $id . '">Редактировать</a></button> ';
}

function ShowDelete($id) {
    return  '<button type="button" class="btn btn-default"><a href="/admin/site/seoDelete/' . $id . '">Удалить</a></button> ';

}

function ShowUrl($id){

    return "<a href='https://prommu.com$id'>https://prommu.com$id</a>";

}

	function ShowName($name, $id) {
		return '<a href="#Modal" id="btn_'.$id.'" data-toggle="modal" onclick="javascript:edt('.$id.')">'.$name.'</a>';
	}

	function getIndex($i, $id){
		$icon = !$i ? 'remove text-danger' : 'ok text-success';
		$title = !$i ? 'Не индексируется' : 'Индексируется';
		return '<span class="glyphicon glyphicon-' 
			. $icon . ' index-page" title="' 
			. $title . '"><i class="hide">' 
			. $id . '</i><b class="hide">' 
			. $i . '</b></span>';
	}
	?>

	<a href="<?php echo Yii::app()->createUrl('site/seoAdd'); ?>" class="btn" id="btn_add">Добавить URL</a>

</div>
<script type="text/javascript">
	function Cancel()
	{
		$("#editform").hide();
		$('#btn_add').show();
	}

	function ShowForm(id) {
		$('#btn_add').hide();
		$('#editform').show();
	}

	function SaveForm(id) {
  // validate
  var valid = true;
  if(isEmpty($('#name_ru').val()))
  {
  	valid=false;
  	$('#name_ru').attr({style:"background:#ff90c0;"});
  }
  else
  	$('#name_ru').attr({style:"background:#fff;"});


  if(isEmpty($('#name_en').val()))
  {
  	valid=false;
  	$('#name_en').attr({style:"background:#ff90c0;"});
  }
  else
  	$('#name_en').attr({style:"background:#fff;"});

  if(valid)
  {
  	$("#btn_add").attr("disabled", "true");
  	var address="/admin/ajax/AddCity";
  	$.ajax({
  		type:'GET',
  		url:address+'?name_ru='+$("#name_ru").val()+'&name_en='+$("#name_en").val(),
  		cache: false,
  		dataType: 'text',
  		success:function (data) {
  			$("#menu_edit").html(data);
  			document.location.href = '';
  		},
  		error: function(data){
  			alert("Download error!");
  			$("#btn_add").attr("disabled", "false");
  		}
  	});

  }

}


function isEmpty(obj) {
	if (typeof obj == 'undefined' || obj === null || obj === '') return true;
	if (typeof obj == 'number' && isNaN(obj)) return true;
	if (obj instanceof Date && isNaN(Number(obj))) return true;
	return false;
}


function edt(id) {
	if(id==0)
	{
    // Insert mode
    $("#ename").val("");
    $("#pkid").val(0);
    $("#edit_title").text("Добавить");
}
else
{
    // Edit mode
    var name = $("#btn_"+id).text();
    $("#ename").val(name);
    $("#pkid").val(id);
    $("#edit_title").text("Редактирование");
}
}

function EditForm() {
	var id = $("#pkid").val();
	var address="/admin/ajax/EditCity";
	if(id==0) address="/admin/ajax/AddCity";
	$.ajax({
		type:'GET',
		url:address+'?name='+$("#ename").val()+'&id='+id,
		cache: false,
		dataType: 'text',
		success:function (data) {
			if(id>0) {
				$("#btn_"+id).text($("#ename").val());
				$("#ename").val('');
			}
			else {
				location.href="";
			}
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
	// изменение коммента
	$('#seo-content').on('click','.index-page',function(){
		var self = this,
			id = $(self).find('i').text(),
			index = +($(self).find('b').text()) ? 0 : 1,
			arParams = [];

			arParams.push({name:'param',value:'index'});
			arParams.push({name:'id',value:id});
			arParams.push({name:'value',value:index});

		$.ajax({
			type: 'GET',
			url: '/admin/ajax/changePageSeoParam',
			data: arParams,
			success: function(res){
				if(res=='1'){
					alert('Изменено');
					if($(self).hasClass('glyphicon-ok')) {
						$(self).removeClass('glyphicon-ok text-success')
							.addClass('glyphicon-remove text-danger')
							.attr('title','Не индексируется');
					}
					else{
						$(self).removeClass('glyphicon-remove text-danger')
							.addClass('glyphicon-ok text-success')
							.attr('title','Индексируется');
					}
				}
				else
					alert('Произошла ошибка');
			}
		});
	});
});

function setShow(id, element)
{
	var address="/admin.php/ajax/SetActiveCity";

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

</script>
<style type="text/css">
	.index-page{ cursor: pointer }
</style>