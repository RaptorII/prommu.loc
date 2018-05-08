// var topmenu = the JSON object
// <div class="hmenu">The menu</div>

var tm_menu_html;
var tm_pid_list = [];


function generateTopMenu() {
	tm_menu_html = '<ul class="dropdown dropdown-horizontal">';
	if(topmenu.length>0) {    	
        $.each(topmenu,function(i,el)
	{
		$.each(topmenu,function(j,el2)
		{
   			if(el.id == el2.parent_id) tm_pid_list[el.id] = true;
   		});
	});
	
	print_topmenu(0,topmenu[0].parent_id);
	}
	$("#mainmenu").html(tm_menu_html+"</ul>");
}




function print_topmenu(cnt, pid) {
	while(cnt<topmenu.length)
	{
		if(tm_pid_list[topmenu[cnt].id] === undefined)
		{
			if(topmenu[cnt].parent_id == pid){
			        if(topmenu[cnt].module+'/'+topmenu[cnt].link==select_url)
					tm_menu_html+='<li class="active"><a href="?r=site/'+topmenu[cnt].module+'/'+topmenu[cnt].link+'">'+topmenu[cnt].name+'</a></li>';
				else
					tm_menu_html+='<li><a href="?r=site/'+topmenu[cnt].module+'/'+topmenu[cnt].link+'">'+topmenu[cnt].name+'</a></li>';
			}
		}
		else {
			//alert(select_url);
			if(topmenu[cnt].module+'/'+topmenu[cnt].link==select_url) {
				tm_menu_html+='<li class="dir active"><a href="?r=site/'+topmenu[cnt].module+'/'+topmenu[cnt].link+'">'+topmenu[cnt].name+'</a>';
			}
			else {							
				tm_menu_html+='<li class="dir"><a href="?r=site/'+topmenu[cnt].module+'/'+topmenu[cnt].link+'">'+topmenu[cnt].name+'</a>';
			}
				//console.log(topmenu[cnt].name+"\r\n");
   			tm_menu_html+='<ul>';
   			print_topmenu(cnt+1,topmenu[cnt].id);
   			tm_menu_html+='</ul></li>';
		}
		cnt++;
	}
}

