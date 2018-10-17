'use strict'
var ProjectConvertVacancy = (function () {

	function ProjectConvertVacancy() {
		this.init();
	}

	ProjectConvertVacancy.prototype.init = function () {
		let self = this;

		$('.evl__to-project-btn').click(function(){
			var data = {
							id: this.dataset.id,
							type: 'convert',
							to: 'project',
						},
					popup = {
							content: '<div></div>', 
							action: { active: 0 }, 
							additionalStyle:'dark-ver'
						};

			ModalWindow.open(popup);
			ModalWindow.loadingOn();

			$.ajax({
				type: 'POST',
				url: '/ajax/Project',
				data: { data: JSON.stringify(data) },
				dataType: 'json',
				success: function (r) {
					if(r.error==true) {
						var n,content;

						if(typeof r['empty-fields']!=='undefined') {
							n = r['empty-fields'].length;
							for (var i=0, n=r['empty-fields'].length; i<n; i++) {
								content = r['empty-fields'][i] + '<br>';
							}
							content = '<div class="convert">Для преобразования вакансии в проект необходимо:<br>'
								+ content + '</div>';
						}
						if(typeof r['vacancy-missing']!=='undefined') {
							content = '<div style="text-align:center">Вакансия не найдена</div>'
						}
						if(typeof r['already-created']!=='undefined') {
							content = '<div style="text-align:center">Проект по этой вакансии уже существует</div>'
						}
						popup.action = { active: 0 };
						popup.content = content;
						ModalWindow.redraw(popup);
						$('#MWwrapper .header-block').text('Ошибка');
					}
					else {
						popup.action = { active: 0 };
						popup.content = '<div>Проект успешно создан</div>';
						ModalWindow.redraw(popup);
						$('#MWwrapper .header-block').text('Выполнено');
					}
				}
			});
		});
		$('.projects__to-vac-btn').click(function(){
			console.log(111);
		});
	}

	return ProjectConvertVacancy;
}());
/*
*
*/
$(document).ready(function () {
	new ProjectConvertVacancy();
});