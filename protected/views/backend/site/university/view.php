<h3><i>Редактирование ВУЗов</i></h3>

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

$dataProvider = new CActiveDataProvider('University', array('criteria' => $criteria, 'pagination' => $pagination, ));

//print_r($dataProvider); die;
$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'my-grid',
  'dataProvider'=>$dataProvider,
  'htmlOptions'=>array('class'=>'table table-hover'),
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
            'value'=> 'ShowName($data->name, $data->id)',
            'type' => 'raw',
            'id'=>'$data->id',
            'htmlOptions' => array('class'=>'m_element'),
        ),

)));

function ShowName($name, $id) {
  return '<a href="#Modal" id="btn_'.$id.'" data-toggle="modal" onclick="javascript:edt('.$id.')">'.$name.'</a>';
}
//CHtml::button($data->name, array("onclick"=>"open_pop_up(\"#pop-up\");edt(".$data->id.")", "id"=>"btn_".$data->id, "style"=>"width:100%"))

?>
<a href='#' class='btn' id='btn_add' style='width:50px' onclick="open_pop_up('#pop-up');edt(0);">Создать</a>
</div>

<script type="text/javascript">


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
   		var address="/admin/ajax/EditUniversity";
      if(id==0) address="/admin/ajax/AddUniversity";
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
    var address="/admin/ajax/SetActiveUniversity";
    
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