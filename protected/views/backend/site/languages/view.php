<?php
  $lang = Share::getLangSelected();
  Share::getLanguages('languages',$lang);

  echo "<h3><i>".Share::lword('TITLE')."</i></h3>";
  echo CHtml::form('','POST',array("id"=>"form"));
?>

    <div id="pop-up2">
    	<h3><?php echo Share::lword('EDIT');?></h3>
    	<p>
    	<div class="label"><?php echo Share::lword('LANG'); ?></div>
    	<?php 
        echo CHtml::dropDownList('lst_lang', '',
              CHtml::listData(Lang::model()->findAll(),'name','name'),'');
    	?>
    	</p>
    	<p>
    	<div class="label"><?php echo Share::lword('PAGE'); ?></div>
    	<?php 
        echo CHtml::dropDownList('lst_page', '',
              CHtml::listData($model->getLangPagesList(), 'page', 'page'), '');
    	?>
    	</p>
    	<p>
    	<div class="label"><?php echo Share::lword('KEY'); ?></div>
    	<input type="text" name="keyword" id="keyword">
    	</p>
    	<p>
    	<div class="label"><?php echo Share::lword('VALUE'); ?></div>
    	<input type="text" name="value" id="value" class="admform">
    	</p>


    	<div id="btn_panel">
			<a href='#' class='btn' style='width:70px;display:inline;' onclick='EditFullForm()'><?php echo Share::lword('SAVE');?></a>
			<a href='#' class='btn' style='width:70px;margin-right:5px; display:inline;' onclick="close_pop_up('#pop-up2');"><?php echo Share::lword('CANCEL');?></a>
    	</div>
    	<input type="hidden" id="pkid" />
	</div>

	<div id="overlay"></div>
<?php	
$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'my-grid',
  'dataProvider'=>$model->search(),
  'filter'=>$model,
  'columns'=>array(
        array(
            'name' => 'id',
            'value' => '$data->id',
            'type' => 'raw',
            'htmlOptions'=>array('style'=>'width: 70px; text-align: center;'),
            'value' => '$data->id."<div class=\"edt\" onclick=\"open_pop_up2(\'#pop-up2\'); getRow(".$data->id.")\"></div><div class=\"del\" onclick=\"Del(".$data->id.")\"></div>"',
        ),
        array(
            'name' => 'lang',
            'type' => 'raw',
            'value' => '$data->lang',
            'filter' => CHtml::listData(Lang::model()->findAll(),'name','name'),
        ),

        array(
            'name' => 'page',
            'type' => 'raw',
            'value' => '$data->page',
            'filter' => CHtml::listData($model->getLangPagesList(),'page','page'),
        ),

        array(
            'name' => 'keyword',
            'type' => 'raw',
            'value' => '$data->keyword',
        ),
        array(
            'name' => 'value',
            'type' => 'raw',
            'value' => '$data->value',
        ),        

)));


echo "<a href=\"#\" class=\"btn\" id=\"btn_add\" style=\"width:50px\" onclick=\"open_pop_up2('#pop-up2');clearForm();\">".Share::lword('CREATE')."</a>";

 
echo CHtml::submitButton('submit',array("id"=>"btn_submit",'style'=>"visibility:hidden"));
echo CHtml::endForm(); ?>
<script type="text/javascript">

var json_data;

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
  	
  var country_id = $('#country').val();
  var sport_id = $('#sport').val();

  	if(valid)
  	{
   		$("#btn_add").attr("disabled", "true");
   		var address="/admin/ajax/AddTournament";
   		$.ajax({
        type:'GET',
        url:address+'?name_ru='+$("#name_ru").val()+'&name_en='+$("#name_en").val()+'&country='+country_id+'&sport='+sport_id,
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


function open_pop_up(box) {
	$("#overlay").show();
	$(box).center_pop_up();
	$(box).show(500);
}

function close_pop_up(box) {
	$(box).hide(500);
	$("#overlay").delay(550).hide(1);
}

function open_pop_up2(box) {
	$("#overlay").show();
	$(box).center_pop_up();
	$(box).show(500);
}

function edt2(id) {
}

function edt(id) {
  var name = $("#btn_"+id).val();
  name = name.substring(5);
  $("#ename").val(name);
  $("#pkid").val(id);
}

function EditFullForm() {
  var id        = $("#pkid").val();
  var lang      = $("#lst_lang").val();
  var page      = $("#lst_page").val();
  var keyword   = $("#keyword").val();
  var value     = $("#value").val();
  
  var address="/admin/ajax/EditLanguage";

   		$.ajax({
        type:'GET',
        url:address+'?id='+id+'&lang='+lang+'&page='+page+'&keyword='+keyword+'&value='+value,
        cache: false,
        dataType: 'text',
        success:function (data) {
           close_pop_up('#pop-up2');
           $("#btn_submit").click();
        },
        error: function(data){
    	   alert("Download error!");
        }
    	});
  
}

function clearForm() {
  $("#pkid").val('0');
  $("#lst_lang").val('');
  $("#lst_page").val('');
  $("#keyword").val('');
  $("#value").val('');
}

function EditForm() {
	var id = $("#pkid").val();
   		var address="/admin/ajax/EditTournament";
   		$.ajax({
        type:'GET',
        url:address+'?name='+$("#ename").val()+'&id='+id,
        cache: false,
        dataType: 'text',
        success:function (data) {
        	//$("#menu_edit").html(data);
        	var id = $("#pkid").val();
        	var lang = $("#btn_"+id).val();
  			lang = lang.substring(0,5);
        	$("#btn_"+id).val(lang + $("#ename").val());
        	$("#ename").val('');

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


function getRow(id) {
   		var address="/admin/ajax/GetLanguage";
   		$("#pkid").val(id);
   		$.ajax({
        type:'GET',
        url:address+'?id='+id,
        cache: false,
        dataType: 'text',
        success:function (data) {
          //alert(data);
          json_data = JSON.parse(data);
          viewForm();
        },
        error: function(data){
    	   alert("Download error!");
        }
    	});

}

function viewForm() {
  $("#lst_lang").val(json_data.lang);
  $("#lst_page").val(json_data.page);
  $("#keyword").val(json_data.keyword);
  $("#value").val(json_data.value);
}

function Del(id)
{
  var address="/admin/ajax/DelLanguage";
  if (confirm("Уверены, что хотите удалить запись #"+id)) {
  
     		$.ajax({
        type:'GET',
        url:address+'?id='+id,
        cache: false,
        dataType: 'text',
        success:function (data) {
          $("#btn_submit").click();
        },
        error: function(data){
    	   alert("Download error!");
        }
    	});
  }
}

</script>