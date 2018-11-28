'use strict'
var MainProject = {
	bAjaxTimer : false,
	idCo : $('#index').data('country'),
    showPopup(event, type) {
        let header = '',
            body = '';

        if(event==='error') {
            header = 'Ошибка';
            switch(type) {
                case 'xls':
                    body = 'Формат файла должен быть "xls" или "xlsx". Выберите подходящий файл!';
                    break;
                case 'load-file':
                    body = 'Этот файл не подходит. Необходимо проверить корректность данных';
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
                case 'invitation-del':
                    body = 'Невозможно удалить единственный контакт';
                    break;
                case 'addr-in-create':
                    body = 'Для проекта требуется добавить адресную программу на сайте, '
                        +'или загрузить программу с помощью XLS файла.';
                    break;
                case 'users-in-create':
                    body = 'Для проекта требуется добавить или пригласить персонал';
                    break;
                case 'onecity':
                    body = 'Невозможно удалить из проекта единственный город';
                    break;
                case 'onelocation':
                    body = 'Невозможно удалить единственную локацию в городе';
                    break;
                case 'oneperiod':
                    body = 'Невозможно удалить единственный период в ТТ';
                    break;
                case 'server':
                    body = 'Произошла ошибка. Необходимо обновить страницу';
                    break;
                default: break;
            }
        }
        if(event==='notif') {
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
                case 'empty-fields':
                    body = 'Необходимо заполнить поля';
                    break;
                case 'nochange':
                    body = 'Значения в полях не менялись';
                    break;
                case 'max-users':
                    body = 'В проект возможно добавить не более ' + maxUsersInProject + ' соискателей';
                    break;
                case 'added-max-users':
                    body = 'В проект возможно добавить не более ' + maxUsersInProject + ' соискателей. Сейчас выбрано допустимое количество';
                    break;
                default: break;
            }
        }
        if(event==='success') {
            header = 'Оповещение';
            switch(type) {
                case 'delcity':
                    body = 'Город успешно удален';
                    break;
                case 'delloc':
                    body = 'ТТ успешно удалена';
                    break;
                case 'delperiod':
                    body = 'Период успешно удален';
                    break;
                case 'new-task':
                    body = 'Задание успешно создано';
                    break;
                case 'change-task':
                    body = 'Задание успешно изменено';
                    break;
                case 'all-dates-task':
                    body = 'Задание успешно продублировано на все даты точки';
                    break;
                case 'all-users-task':
                    body = 'Задание успешно продублировано всему персоналу';
                    break;
                case 'delete-task':
                    body = 'Задание успешно удалено';
                    break;
                case 'output-gps':
                    body = 'Вы были успешно отмечены на карте';
                    break;
                default: break;
            }
        }

        if(event==='attention') {
            header = 'Внимание';
            switch(type) {
                case 'full-in-create':
                    body = 'Для добавления нового проекта, '
                        + 'обязательным условием является наличие адресной программы. '
                        + 'Вы можете её добавить как через сайт, так и через XLS файл. '
                        + 'Пример для добавления адресов скачайте тут же';
                    break;
                default: break;
            }
        }

        let html = "<form data-header='" + header + "'>" + body + "</form>";
        ModalWindow.open({ content: html, action: { active: 0 }, additionalStyle:'dark-ver' });
    }
};