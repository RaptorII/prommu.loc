// var topmenu = the JSON object
// <div class="hmenu">The menu</div>

var tm_menu_html;
var tm_pid_list = [];


function generateTopMenu() {
	//tm_menu_html = '<ul class="dropdown dropdown-horizontal">';
  tm_menu_html = '<div id="vmenu">';
	if(topmenu==null || topmenu==undefined) return;
	if(topmenu.length>0) {    	
    $.each(topmenu,function(i,el)
	  {
		   $.each(topmenu,function(j,el2)
		   {
   			   if(el.id == el2.parent_id) tm_pid_list[el.id] = true;
   	   });
	  });
    //console.log(topmenu[i].visible);
	
	  //if(topmenu[i].visible=='1')
    print_topmenu(0,topmenu[0].parent_id);
	}
	$("#mainmenu").html(tm_menu_html+"</div><br/><br/>");
}




function print_topmenu(cnt, pid) {
	if(topmenu==null || topmenu==undefined) return;
	while(cnt<topmenu.length)
	{
    //console.log(topmenu[cnt].visible==0)
    if(topmenu[cnt].visible==0){cnt++;continue;}
		if(tm_pid_list[topmenu[cnt].id] === undefined)
		{
			if(topmenu[cnt].parent_id == pid){
			        if(topmenu[cnt].module+'/'+topmenu[cnt].link==select_url)
           tm_menu_html+='<div class="rt active"><a href="/site/'+topmenu[cnt].module+'/'+topmenu[cnt].link+'">'+topmenu[cnt].name+'</a></div>';
				else
          tm_menu_html+='<div class="rt"><a href="/site/'+topmenu[cnt].module+'/'+topmenu[cnt].link+'">'+topmenu[cnt].name+'</a></div>';
			}
		}
		else {
			//alert(select_url);
			if(topmenu[cnt].module+'/'+topmenu[cnt].link==select_url) {
				tm_menu_html+='<li class="dir active"><a href="/site/'+topmenu[cnt].module+'/'+topmenu[cnt].link+'">'+topmenu[cnt].name+'</a>';
			}
			else {							
				tm_menu_html+='<li class="dir"><a href="/site/'+topmenu[cnt].module+'/'+topmenu[cnt].link+'">'+topmenu[cnt].name+'</a>';
			}
				//console.log(topmenu[cnt].name+"\r\n");
   			tm_menu_html+='<ul>';
   			print_topmenu(cnt+1,topmenu[cnt].id);
   			tm_menu_html+='</ul></li>';
		}
		cnt++;
	}
}

