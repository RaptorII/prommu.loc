<?php
/* @var $this SiteController */

  $this->pageTitle='Настройка меню';
  $output = '';
//$share = new Share;
//$lang = $share->getLang();
  $lang = Share::getLangSelected();
  Share::getLanguages('languages',$lang);

//$au = actionLogin();
//echo "<h1>Auth: $au </h1>";

?>
<h3><i>Настройка меню</i></h3>
<div class="span4" sidebar>

<?php

echo CHtml::dropDownList('menu_type', $menu_type,
              CHtml::listData(MenuType::model()->findAll(),'id','name'),
              array('onchange'=>"onchangeType(this)"));

?>
</div>
<div class="span8">
<div id="menu_edit"></div>

<?php //echo CHtml::submitButton('Calc');
?>
<?php
echo CHtml::form();
echo CHtml::hiddenField('field_id', '', array('type'=>"hidden"));
echo CHtml::hiddenField('field_op', '', array('type'=>"hidden"));
echo CHtml::hiddenField('field_parent', '', array('type'=>"hidden"));
echo CHtml::hiddenField('field_lang', $lang, array('type'=>"hidden"));
echo CHtml::hiddenField('field_menu_type', $menu_type, array('type'=>"hidden"));
echo CHtml::submitButton('Обработать',array("id"=>"btn_submit",'class'=>'btn success', 'style'=>"visibility:hidden"));

echo CHtml::ajaxSubmitButton('Обработать', '', array(
    'type' => 'POST',
    // Результат запроса записываем в элемент, найденный
    // по CSS-селектору #output.
    'update' => '#output',
),
array(
    // Меняем тип элемента на submit, чтобы у пользователей
    // с отключенным JavaScript всё было хорошо.
    'type' => 'submit',
    'style'=>"visibility:hidden"
));

?>
<br/><br/>
<a href="#" class="btn btn-primary" onclick="newMenu()">Добавить главный пункт меню</a>
</div>

<script type="text/javascript">
var lang = '<?php echo $lang;?>';

$(document).ready(function(){
loadPage("/admin/ajax/Listmenu");
});

function menu_functional()
{
	if($('#menu_edit'))
	{
		// При нажатии на пункт меню закрывается контейнер всплывающего меню
		 $(".m_element span").click(function(){
			  var options=$(this).children(".m_element_options:eq(0)");
			  options.css("display","none");
		 });

		 // Вывод всплывающего меню при наведении на пункт меню

		 $(".m_element").hover(
		  function()
		  {
			   $(this).children(".m_element_options:eq(0)").css("display","inline");
			   //$(this).css('background','#f5f5f5');
		  },
		  function()
		  {
			   $(this).children(".m_element_options:eq(0)").css("display","none");
			   //$(this).css('background','');
		  }
		 );
	}
}

function loadPage(address)
{
   $.ajax({
        type:'GET',
        url:address+'?lang='+lang+'&menu_type='+$("#field_menu_type").val(),
        cache: false,
        dataType: 'text',
        success:function (data) {
        	$("#menu_edit").html(data);
        	menu_functional();
        },
        error: function(data){
    	   alert("Download error!");
        }
    });
 }

function onchangeLang(sel)
{
	var value = sel.options[sel.selectedIndex].value;
	$("#field_lang").val(value);
	loadPage("/admin/ajax/Listmenu");
}

function onchangeType(sel)
{
	var value = sel.options[sel.selectedIndex].value;
	$("#field_menu_type").val(value);
	loadPage("/admin/ajax/Listmenu");
}

function changePos(id,cmd)
{
  loadPage("/admin/ajax/ChangePosMenu?switch="+cmd+"&id="+id+'&menu_type='+$("#field_menu_type").val()+"&lang="+lang);
}

function deleteMenu(id,cmd)
{
	if(confirm('Подтвердите удаление'))
	{
		loadPage("/admin/ajax/DeleteMenu?id="+id+"&lang="+lang);
	}
}

function addMenu(id)
{
	$("#field_id").val("0");
	$("#field_parent").val(id);
	$("#field_op").val("FORM");
	$("#btn_submit").click();
}

function go(id)
{
	$("#field_id").val(id);
	$("#field_op").val("FORM");
	$("#btn_submit").click();
}

function newMenu()
{
	$("#field_parent").val("0");
	$("#field_id").val("0");
	$("#field_op").val("FORM");
	$("#btn_submit").click();
}
</script>


<?php echo CHtml::endForm(); ?>
