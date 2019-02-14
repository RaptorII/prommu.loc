<h3><i>Редактирование SEO</i></h3>
<style>
.btn {
	background: #00c0ef;
}
#tablist{
    background-color: #222d32;
    margin-bottom:30px;
    text-align: justify;
}
#tablist li{
    display: inline-block;
}
#tablist a{
    font-weight: bold;
    color: #b8c7ce;
    border-left: 3px solid transparent;
}
#tablist .active a,
#tablist a:hover{
    color: #fff;
    background: #1e282c;
    border-left-color: #3c8dbc;
}

.tab-content .tab-pane{

}

.tab-content .tab-pane:nth-child(1){

}
</style>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <ul class="nav user__menu" role="tablist" id="tablist">
            <li class="active">
                <a href="#seo" aria-controls="seo" role="tab" data-toggle="tab">Prommu Москва</a>
            </li>
            <li>
                <a href="#seo_rostov" aria-controls="seo_rostov" role="tab" data-toggle="tab">Prommu Ростов</a>
            </li>
            <li>
                <a href="#seo_spb" aria-controls="seo_spb" role="tab" data-toggle="tab">Prommu Санкт-Питербург</a>
            </li>
            <li>
                <a href="#seo_novosibirsk" aria-controls="seo_novosibirsk" role="tab" data-toggle="tab">Prommu Новосибирск</a>
            </li>
            <li>
                <a href="#seo_volgograd" aria-controls="seo_volgograd" role="tab" data-toggle="tab">Prommu Волгоград</a>
            </li>
            <li>
                <a href="#seo_ufa" aria-controls="seo_ufa" role="tab" data-toggle="tab">Prommu Уфа</a>
            </li>
            <li>
                <a href="#seo_voronezh" aria-controls="seo_voronezh" role="tab" data-toggle="tab">Prommu Воронеж</a>
            </li>
            <li>
                <a href="#seo_krasnoyarsk" aria-controls="seo_krasnoyarsk" role="tab" data-toggle="tab">Prommu Красноярск</a>
            </li>
            <li>
                <a href="#seo_omsk" aria-controls="seo_omsk" role="tab" data-toggle="tab">Prommu Омск</a>
            </li>
            <li>
                <a href="#seo_perm" aria-controls="seo_perm" role="tab" data-toggle="tab">Prommu Пермь</a>
            </li>
            <li>
                <a href="#seo_samara" aria-controls="seo_samara" role="tab" data-toggle="tab">Prommu Самара</a>
            </li>
            <li>
                <a href="#seo_ekaterinburg" aria-controls="seo_ekaterinburg" role="tab" data-toggle="tab">Prommu Екатеринбург</a>
            </li>
            <li>
                <a href="#seo_kazan" aria-controls="seo_kazan" role="tab" data-toggle="tab">Prommu Казань</a>
            </li>
            <li>
                <a href="#seo_chelyabinsk" aria-controls="seo_chelyabinsk" role="tab" data-toggle="tab">Prommu Челябинск</a>
            </li>
            <li>
                <a href="#seo_nn" aria-controls="seo_nn" role="tab" data-toggle="tab">Prommu Нижний Новгород</a>
            </li>
        </ul>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="seo">
            seo mos
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_ufa">
            seo_ufa
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_volgograd">
            seo_volgograd
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_voronezh">
            seo_voronezh
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_nn">
            seo_nn
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_krasnoyarsk">
            seo_krasnoyarsk
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_novosibirsk">
            seo_novosibirsk
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_omsk">
            seo_omsk
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_perm">
            seo_perm
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_rostov">
            seo_rostov
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_samara">
            seo_samara
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_spb">
            seo_spb
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_ekaterinburg">
            seo_ekaterinburg
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_kazan">
            seo_kazan
        </div>
        <div role="tabpanel" class="tab-pane fade in" id="seo_chelyabinsk">
            seo_chelyabinsk
        </div>
    </div>
</div>

<a href="<?php echo Yii::app()->createUrl('site/seoAdd'); ?>" class="btn" id="btn_add">Добавить URL</a>
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

<div class="span11">
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
		 'itemsCssClass' => 'table table-bordered table-hover dataTable col-xs-12 col-sm-9 col-md-10',
    'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style'=>    'padding: 10px;'),
		'columns'=>array
		(
			array
			(
				'name' => 'url',
				'value' => '$data->url',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'width: 50px; text-align: left; white-space: nowrap'),
				'filter'=>'',
			),
			array
			(
				'name' => 'meta_title',
				'value' => '$data->meta_title',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'text-align: left;'),
				'filter'=>'',
			),
			array
			(
				'name' => 'seo_h1',
				'value' => '$data->seo_h1',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'text-align: left;'),
				'filter'=>'',
			),
			array
			(
				'name' => 'meta_description',
				'value' => '$data->meta_description',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'text-align: left;'),
				'filter'=>'',
			),
			array
			(
				'header' => 'описание страницы',
				'name' => 'meta_keywords',
				'value' => '$data->meta_keywords',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'text-align: left;'),
				'filter'=>'',
			),
			/*array
			(
				'name' => 'meta_keywords',
				'value' => '$data->meta_keywords',
				'type' => 'html',
				'htmlOptions'=>array('style'=>'text-align: left;'),
				'filter'=>'',
			),*/
	    	array(
	      		'class'=>'CButtonColumn',
	      		'deleteConfirmation'=>"js:'URL '+$(this).parent().parent().children(':first-child').text()+' будет удален! Продолжить?'",
		  		'template' => '{edit}&nbsp;{remove}',
	  			'buttons'=>array
      			(
        			'edit' => array
        			(
            			'url'=>'Yii::app()->createUrl("site/seoEdit",  array("id" => $data->id))',
            			'options'=>array('title'=>'Редактировать'),
            			'label' => '<span class="btn"><i class="icon-pencil"></i></span>',
            			'htmlOptions'=>array('style'=>'background: #00c0ef;')
            			
        			),
        			'remove' => array
        			(
            			'url'=>'Yii::app()->createUrl("site/seoDelete",  array("id" => $data->id))',
            			'options'=>array('title'=>'Удалить'),
            			'label' => '<span class="btn btn-danger"><i class="icon-trash"></i></span>'
        			),
      			),
  			),
		),
	));

	function ShowName($name, $id) {
		return '<a href="#Modal" id="btn_'.$id.'" data-toggle="modal" onclick="javascript:edt('.$id.')">'.$name.'</a>';
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