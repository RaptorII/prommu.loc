'use strict'
var ProjectPage = (function () {
	function ProjectPage() {
        var self = this;
        self.init();
    }
    ProjectPage.prototype.init = function () {
    	var mainSave = $('.project__main-btn a'),
    		main = $('#main'),
    		nameInput = $(main).find('[name="name"]'),
    		errors = false;





    	mainSave.click(function(e){
    		e.preventDefault();
    		var name = nameInput.val();

    		if(name.length<1){
    			$(nameInput).addClass('error');
    		}
    		

    	});
    };
    ProjectPage.prototype.
    return ProjectPage;
}());
$(document).ready(function () {
	var Project = new ProjectPage();

	
});

