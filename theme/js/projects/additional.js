'use strict'
var MainProject = {
	bAjaxTimer : false,
	idCo : $('#index').data('country'),
    showPopup(event, type) {
        let header = '',
            body = '';

        if(event=='error') {
            header = 'Ошибка';
            switch(type) {
                case 'xls':
                    body = 'Формат файла должен быть "xls" или "xlsx". Выберите подходящий файл!';
                    break;
				case 'time':
                    body = 'Неправильно задано время работы';
                    break;
                case 'save-notif':
                    body = 'Необходимо заполнить все поля в приглашении';
                    break;
                case 'bad-email':
                    body = 'Email не соответствует общепринятому формату';
                    break;
                case 'bad-phone':
                    body = 'Неправильное количество символов в телефоне';
                    break;
                case 'city-del':
                    body = 'Невозможно удалить единственный город';
                    break;
                case 'loc-del':
                    body = 'Невозможно удалить единственную ТТ города';
                    break;
                case 'period-del':
                    body = 'Невозможно удалить единственный период ТТ';
                    break;
                default: break;
            }
        }
        if(event=='notif') {
            header = 'Предупреждение';
            switch(type) {
                case 'name':
                    body = 'Для продолжения необходимо ввести название проекта';
                    break;
                case 'add-city':
                    body = 'Перед добавлением нового города необходимо '
                        + 'заполнить все поля у существующих городов';
                    break;
                case 'add-period':
                    body = 'Перед добавлением нового периода необходимо '
                        + 'заполнить все пустые поля';
                    break;
                case 'add-tt':
                    body = 'Перед добавлением ТТ необходимо '
                        + 'заполнить все пустые поля';
                    break;
                case 'add-notif':
                    body = 'Перед добавлением нового приглашения необходимо '
                        + 'корректно заполнить все поля в существующих';
                    break;
                case 'addition':
                    body = 'Не было выбрано ни одного соискателя';
                    break;
                case 'save-program':
                    body = 'Перед сохранение необходимо заполнить все поля';
                    break;
                default: break;
            }
        }

        let html = "<form data-header='" + header + "'>" + body + "</form>";
        ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
    }
};