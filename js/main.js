$(document).ready(function(){

	//ACT - edit_element
	if($('#edit_element'))
	{
		$('.parent').each(function(){
			$(this).children(".tree_children").css('min-width',$(this).css('width'));
		});		
		
		$('.parent').hover(
		   function(){
		   		$(this).children(".tree_children").css('display','block');
		   },
		   function(){
				$(this).children(".tree_children").css('display','none');		   
		});
		
		$("#show_tree .parent_link:last").addClass('parent_link_selected');
		
	}
	
	// ACT - edit_menu_config
	if($('#edit_menu_config'))
	{
		// Ajax-������ �� ����� ����� ������������� � ����������� �� ���������� �������
		$("#service").change(function(){
			$("#groups_cont").load('/file/adm/menu/groups?srv='+$(this).val()+'&menu_id='+$("#id").val());
		});
	}
	
	// menu_edit.act
	if($('#menu_edit'))
	{
		// ��� ������� �� ����� ���� ����������� ��������� ������������ ����
		 $(".m_element span").click(function(){
			  var options=$(this).children(".m_element_options:eq(0)");  
			  options.css("display","none");
		 });
	
		 // ����� ������������ ���� ��� ��������� �� ����� ����
	
		 $(".m_element span").hover(
		  function()
		  { 
			   $(this).children(".m_element_options:eq(0)").css("display","inline"); 
			   $(this).css('background','#f5f5f5');
		  },
		  function()
		  {
			   $(this).children(".m_element_options:eq(0)").css("display","none"); 
			   $(this).css('background','');
		  }
		 );
	}
	
	// ������� ���, �������� (��� �����)
	if($("#groups_cont"))
	{
		$("#groups_cont").ajaxStop(function(){
			$(".select_all_btn").click(function(){
				$("#groups input[type='checkbox']").attr('checked','checked');									
			});
					
			$(".deselect_all_btn").click(function(){
					$("#groups input[type='checkbox']").attr('checked','');										
			});									
		});
		
		// ����� � ������ ������ ��������� ����� �������������
		$(".select_all_btn").click(function(){
				$("#groups input[type='checkbox']").attr('checked','checked');									
		});
					
		$(".deselect_all_btn").click(function(){
				$("#groups input[type='checkbox']").attr('checked','');										
		});	
	}
});