    <link rel="stylesheet" href="/css/reset.css"> <!-- CSS reset -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/default.css" />
    <link rel="stylesheet" type="text/css" href="/css/component.css" /> 
    <!-- <link rel="stylesheet" type="text/css" href="/js/css/default.css" /> -->
    <link rel="stylesheet" type="text/css" href="/js/css/component.css" />
<!--     <link href='https://fonts.googleapis.com/css?family=Playfair+Display:700,900|Fira+Sans:400,400italic' rel='stylesheet' type='text/css'> -->
    <script src="/js/modernizrr.js"></script> 

<section class="cd-horizontal-timeline">
    <div class="timeline">
        <div class="events-wrapper">
            <div class="events">
                <ol>
                    <li><a href="#0" data-date="11/01/2014" class="selected">PROMO</a></li>
                    <li><a href="#0" data-date="28/02/2014">EMPL</a></li>
                    <li><a href="#0" data-date="09/09/2014">COMMON</a></li>
                    <li><a href="#0" data-date="09/12/2014">INFO</a></li>
                    <!-- <li><a href="#0" data-date="20/05/2014">COMMON</a></li>
                    <li><a href="#0" data-date="09/07/2014">PROMO</a></li>
                    <li><a href="#0" data-date="30/08/2014">EMPLOYER</a></li>
                    <li><a href="#0" data-date="15/09/2014">COMMON</a></li>
                    <li><a href="#0" data-date="01/11/2014">PROMO</a></li>
                    <li><a href="#0" data-date="10/12/2014">EMPLOYER</a></li> -->
                </ol>

                <span class="filling-line" aria-hidden="true"></span>
            </div> <!-- .events -->
        </div> <!-- .events-wrapper -->
            
        <ul class="cd-timeline-navigation">
            <li><a href="#0" class="prev inactive">Назад</a></li>
            <li><a href="#0" class="next">Вперед</a></li>
        </ul> <!-- .cd-timeline-navigation -->
    </div> <!-- .timeline -->

    <div class="events-content">
        <ol>
            <li class="selected" data-date="11/01/2014">
            
         
            <h1 style="  font-size: 37px;">Профиль</h1>
            <p> <button class="md-trigger" data-modal="modal-user">User</button><br/></p>
            <h2 class="api">http://prommu.com:4445/api/promo/ GET</h2>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-promo">Promo</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                    Получение личных данных соискателя
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/promo/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-promo">Promo[]</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                    Редактирование личных данных соискателя
                </p>
                <em>Дополнительно</em>
                <p> Свойство user – обязательное.
Метод не редактирует внутренние объекты (contact например), даже свойства в
которых эти объекты метод не трогает.
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/promo/contact/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-contact">Contact</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                    Редактирование контактов соискателя (User.contact)
                </p>
                 <em>Дополнительно</em>
                <p> 
                   Здесь можно и не передавать тело запроса — в таком случае в контакте текущего
Promo все свойства будут выставлены в null
                </p>
                <br/>
                  <h2 class="api">http://prommu.com:4445/api/promo/experiences/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-exp">Experience []</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                    Редактирование опыта соискателя (User.experiences)
                </p>
                 <em>Дополнительно</em>
                <p> 
                   Свойство post — обязательное.
Можно передать пустой массив. В таком случае в User.experiences будет
установлен null, а все другие объекты Experience которые принадлежат
текущему promo будут удалены.
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/promo/targetvacancies/  POST</h2>
                <em>Request</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-tv">TargetVacancy []</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Редактирование целевых вакансий соискателя (User.targetvacancies)
                </p>
                 <em>Дополнительно</em>
                <p> 
                   Свойства post и city — обязательные.
Можно передать пустой массив. В таком случае в User.targetvacancies будет
установлен null, а все другие объекты TargetVacancy которые принадлежат
текущему promo будут удалены.
                </p>
                <br/>
                <h1 style="  font-size: 37px;">PUSH</h1>
                   <h2 class="api">http://prommu.com:4445/api/promo/push/  POST</h2>
                <em>Request</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-push">Push Model</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Обновление, запись, удаление PUSH-topics & PUSH-config пользователя
                </p>
                
                <br/>
                   <h2 class="api">http://prommu.com:4445/api/promo/push/  GET</h2>
               <!--  <em>Request</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-tv">TargetVacancy []</button><br/>
                </p> -->
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-push">Push[] Model</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Получение списка PUSH-topics & PUSH-config пользователя
                </p>
                 
                <br/>
                <h1 style="  font-size: 37px;">Поиск</h1>
                 <h2 class="api">http://prommu.com:4445/api/promo/employer/search/  GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • cotypes - массив типов фирм работодателей <br/>
                                       • cities - массив городов<br/>
                                       • ln - задать часть имени работодателя<br/>
                </p>
                 <em>Response</em>
                <p> 
                    <b>Parameters</b>: <button class="md-trigger" data-modal="modal-employer">Page Employer</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Поиск работодателей. Если ни одного параметра не передать, то метод вернет всех
без исключений.
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/promo/vacancy/search/  GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • post - массив должностей <br/>
                                       • city - массив городов<br/>
                                       • payType - тип оплаты<br/>
                                       • pf, pt - оплата в час с и по, соответственно <br/>
                                       • af, at - фильтр по возрасту, с и по, соответственно<br/>
                                       • sex - пол<br/>
                </p>
                 <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-vacancy">Page Vacancy</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Поиск активных вакансий по фильтру. Если ни одного параметра не передать, то
метод вернет всех без исключений.
                </p>
                <br/>
                <h1 style="  font-size: 37px;">Рейтинг</h1>
                      <h2 class="api">http://prommu.com:4445/api/promo/employer/feedback/  POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • vacancy – id вакансии. Вакансия должна быть не активной! <br/>
                                       • promo – id соискателя<br/>
                                       • positive – true или false для общей оценки работодателя<br/>
                                       • text – текст отзыва<br/>
                    <b>Body</b>:<button class="md-trigger" data-modal="modal-er">Employer Rate</button><br/>
                </p>
                 <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                  Отправить отзыв на работодателя
                </p>
                <br/>
                       <h2 class="api">http://prommu.com:4445/api/promo/employer/feedback/  GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • employer – id работодателя <br/>
                    <b>Body</b>:<button class="md-trigger" data-modal="modal-ef">EmployerFeedback[]</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                 Получение всех отзывов на работодателя
                </p>
                <br/>
                <h1 style="  font-size: 37px;">Заявки/приглашения</h1>
                   <h2 class="api">http://prommu.com:4445/api/promo/vacancy/request/  POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • vacancy - id объекта <button class="md-trigger" data-modal="modal-vacancy"> Vacancy</button><br/>. Вакансия должна быть активна,
иначе вернем ошибку. <br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Отправить заявку на вакансию vacancy для текущего соискателя
                </p>
                <em>Дополнительно</em>
                <p> 
                   Если текущий соискатель уже отправлял заявку на vacancy, или получал
приглашение на эту вакансию, значит вернем ошибку, и не важно какой
RequestStatus у «старого» приглашения.
Метод так же проверяет подходит ли текущий соискатель на эту вакансию, в
частности проверяем по свойствам вакансии ageFrom, ageTo, sex, car, medCert. Если
где-то соискатель не подходит, то вернем ошибку. Важно: если у текущего Promo
что-то не выставлено (например sex = null), то метод тоже не пропустит заявку по
несоответствию требуемого пола
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/promo/vacancy/request/  GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>:• status - RequestStatus для приглашений. Если параметр
передается, значит вернутся приглашения только с этим статусом. <br/>
                </p>
                 <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-vr">VacancyRequest[]</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Получение приглашений и заявок на вакансии для себя.
                </p>
                 <em>Дополнительно</em>
                <p> 
                   Если ни одного параметра не передано, значит вернем все приглашения и заявки (с любым
статусом).
Если этот метод среди всех найденных приглашений обнаружит обьекты с
статусом SENDING, то такие приглашения автоматически меняют статус на
WAITING и сохраняются.
                </p>
                <br/>
                  <h2 class="api">http://prommu.com:4445/api/promo/vacancy/request/status/  POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>:• request - id объекта VacancyRequest. Во-первых это должно быть
приглашение, во-вторых должно быть отправлено текущему
соискателю и в-третьих иметь статус WAITING – иначе вернем
ошибку. 
<br/>                          • status - RequestStatus который нужно присвоить приглашению.
Здесь должно быть либо CONFIRM, либо REFUSE, иначе вернем
ошибку
<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Отреагировать на ожидающее приглашение – то есть поменять его статус
(promoStatus) на CONFIRM или REFUSE.
                </p>
                <br/>
                <h1 style="  font-size: 37px;">Чат</h1>
                   <h2 class="api">http://prommu.com:4445/api/promo/chat/  POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>:•  text - сообщение <br/> 
                                      •  user – id юзера получателя<br/>
                                      •  title – тема чата<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Отправить сообщение в чат
                </p>
                <em>Дополнительно</em>
                <p> 
                   Метод сам находит нужный чат и добавляет в него сообщение. Если
соответствующего чата нет, то он создается.
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/promo/chat/  GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>:• id— чат, сообщения которого хотим получить
                                <br/> • all - (api/promo/chat/all) - возвращает все чаты пользователя
                                <br/> 
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>:<button class="md-trigger" data-modal="modal-chat">Chat[]</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Получение своих чатов
                </p>
                <em>Дополнительно</em>
                <p> 
                   Если read не передается, значит вернем все чаты (с прочитанными сообщениями и
не прочитанными).
Этот метод автоматически всем полученным сообщениям в чатах выставляет
свойство read в true, то есть отмечает все сообщения как прочитанные.
                </p>
                <br/>
                  <h2 class="api">http://prommu.com:4445/api/promo/chat/vacancy/  POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>:• text – текст сообщения
                                <br/> 
                                • vacancy – id объекта Vacancy
                                <br/> 
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Отправить сообщение работодателю в чат вакансии
                </p>
                <em>Дополнительно</em>
                <p> 
                  Метод сам находит нужный чат и добавляет в него сообщение. Если
соответствующего чата нет, то он создается.
Важно: текущий соискатель должен иметь подтвержденную заявку или
приглашение на переданную вакансию, иначе вернем ошибку
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/promo/chat/vacancy/  GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>:• read — если здесь false, то вернутся только чаты с
непрочитанными сообщениями. Но! Если true, то вернутся все чаты,
а не только с прочитанными сообщениями (просто нет смысла в
поиске только с прочитанными).<br/>
                </p>
                 <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-vc">VacancyChat[]</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Получение своих чатов вакансий.
                </p>
                 <em>Дополнительно</em>
                <p> 
                   Если read не передается, значит вернем все чаты (с прочитанными сообщениями и
не прочитанными).
Этот метод автоматически всем полученным сообщениям в чатах выставляет
свойство read в true, то есть отмечает все сообщения как прочитанные.
статусом)
                </p>
                <br/>   
            </li>

            <li data-date="28/02/2014">
               <h1 style="  font-size: 37px;">Профиль</h1>
            <h2 class="api">http://prommu.com:4445/api/employer/ GET</h2>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-employer">Employer</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                    Получение личных данных работодателя
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/employer/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-employer">Employer[]</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                    Редактирование личных данных работодателя включительно с внутренним
обьектом company
                </p>
                <em>Дополнительно</em>
                <p> Свойства company,post, company.name, company.city, company.companyType –
обязательные. То есть если вы ни чего не передадите в этих свойствах (фактически
передадите null), то получите ошибку.
                </p>
                <br/>
                 <h1 style="  font-size: 37px;">PUSH</h1>
                   <h2 class="api">http://prommu.com:4445/api/promo/push/  POST</h2>
                <em>Request</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-push">Push Model</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Обновление, запись, удаление PUSH-topics & PUSH-config пользователя
                </p>
                
                <br/>
                   <h2 class="api">http://prommu.com:4445/api/promo/push/  GET</h2>
               <!--  <em>Request</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-tv">TargetVacancy []</button><br/>
                </p> -->
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-push">Push[] Model</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Получение списка PUSH-topics & PUSH-config пользователя
                </p>
                 
                <br/>
                <h1 style="  font-size: 37px;">Поиск</h1>
                <h2 class="api">http://prommu.com:4445/api/employer/promo/search/  GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • posts - массив должностей <br/>
                                       • cities - массив городов<br/>
                                       • bf, bt - фильтр по возрасту, с и по, соответственно<br/>
                                       • ln - задать часть фамилии соискателя<br/>
                                       • sex - пол<br/>
                                       • mc - наличие медкнижки ( 1, 0 )<br/>
                                       • car - наличие автомобиля ( 1, 0 )<br/>


                </p>
                 <em>Response</em>
                <p> 
                    <b>Parameters</b>: <button class="md-trigger" data-modal="modal-promo">Page Promo</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Поиск соискателей. Если ни одного параметра не передать, то метод вернет всех
без исключений.
                </p>
                <br/>
                <h1 style="  font-size: 37px;">Рейтинг</h1>
                 <h2 class="api">http://prommu.com:4445/api/employer/promo/feedback/  POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • vacancy – id вакансии. Вакансия должна быть не активной! <br/>
                                       • promo – id соискателя<br/>
                                       • positive – true или false для общей оценки соискателя<br/>
                                       • text – текст отзыва<br/>
                    <b>Body</b>:<button class="md-trigger" data-modal="modal-pr">Promo Rate</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Отправить отзыв на соискателя
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/employer/promo/feedback/ GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • promo – id соискателя<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>:<button class="md-trigger" data-modal="modal-pf">Promo Feedback</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Получение всех отзывов на работодателя
                </p>
                <br/>
                <h1 style="  font-size: 37px;">Вакансии</h1>
                 <h2 class="api">http://prommu.com:4445/api/employer/vacancy/   GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: vacancy - id объекта </b>:<button class="md-trigger" data-modal="modal-vacancy">Vacancy</button><br/>. Важно: id должно быть своей
вакансии, иначе вернем ошибку<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>:</b>:<button class="md-trigger" data-modal="modal-vacancy">Vacancy</button><br/><br/>
                </p>
                <em>Описание</em>
                <p> 
                  Получение своих вакансий
                </p>
                <em>Дополнительно</em>
                <p> 
                  Если параметр id не передавать, то получим массив всех своих вакансий
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/employer/vacancy/ POST</h2>
                <em>Request</em>
                <p> 
                     <b>Body</b>:<button class="md-trigger" data-modal="modal-vacancy">Vacancy</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Редактирование (или создание новой) вакансии работодателя
                </p>
                <em>Дополнительно</em>
                <p> 
                   Свойства employer, city, post – обязательные. То есть если вы ни чего не
передадите в этих свойствах (фактически передадите null), то получите ошибку.
Если в свойстве deactivate дата – в будущем, то эта вакансия автоматически стает
активной (опубликованной).
Если в свойстве deactivate передана дата из прошлого или сегодняшняя, то
вакансия станет неактивной и все заявки и приглашения (<button class="md-trigger" data-modal="modal-vr">VacancyRequest</button>) по
этой вакансии поменяют статус на REFUSE.
Каждую полночь система запускает задачу проверки и обновления активных
вакансий, как только дата в deactivate станет сегодняшним днем, система
автоматически сделает эту вакансию не активной (снимет с публикации) и все
заявки и приглашения (<button class="md-trigger" data-modal="modal-vr">VacancyRequest</button>) по этой вакансии поменяют статус на
REFUSE.
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/employer/vacancy/activate/ POST</h2>
                <em>Request</em>
                <p> 
                     <b>Parameters</b>:• vacancy – id обьекта<button class="md-trigger" data-modal="modal-vacancy">Vacancy</button><br/>
                     • activate – если true, то вакансия станет активной на 10 дней;<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Активация или деактивация определенной вакансии
                </p>
                <em>Дополнительно</em>
                <p> 
                   Если не передавать свойство activate, то это будет иметь такой же эффект, как
если бы передали false — деактивация вакансии, а также все заявки и
приглашения (<button class="md-trigger" data-modal="modal-vr">VacancyRequest</button>) по этой вакансии поменяют статус на REFUSE.
При деактивации неактивной вакансии эффекта ни какого не будет.
Метод создан для удобства - активировать (или наоборот) вакансию можно просто
через метод /api/employer/vacancy/ POST передав дату деактивации в свойстве
deactivate.
                </p>
                <br/>
                <h1 style="  font-size: 37px;">Заявки/Приглашения</h1>
                  <h2 class="api">http://prommu.com:4445/api/employer/vacancy/request/ POST</h2>
                <em>Request</em>
                <p> 
                     <b>Parameters</b>:vacancy - id объекта <button class="md-trigger" data-modal="modal-vacancy">Vacancy</button>. Вакансия во-первых должна быть
активна, во-вторых должна принадлежать текущему employer'у.<br/>
                     • promo — id объекта <button class="md-trigger" data-modal="modal-promo">Promo</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Отправить promo приглашение на вакансию vacancy
                </p>
                <em>Дополнительно</em>
                <p> 
                  Если обьект promo уже получал приглашение на vacancy, или сам отправлял
заявку на эту вакансию, значит вернем ошибку, и не важно какой <button class="md-trigger" data-modal="modal-rs">Request Status</button> у
«старого» приглашения. 
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/employer/vacancy/request/ GET</h2>
                <em>Request</em>
                <p> 
                     <b>Parameters</b>:vacancy - id объекта <button class="md-trigger" data-modal="modal-vacancy">Vacancy</button>. Если параметр передается, значит
вернутся заявки только для этой вакансии. Вакансия во-первых
должна быть активна, во-вторых должна принадлежать текущему
employer'у.<br/>
                </p>
                <em>Response</em>
                <p> 
                     <b>Body</b>: <button class="md-trigger" data-modal="modal-vr">VacancyRequest</button><br/>
            
                </p>
                <em>Описание</em>
                <p> 
                  Получение заявок от соискателей и приглашений на свои вакансии.
                </p>
                <em>Дополнительно</em>
                <p> 
                  Если ни одного параметра не передано, значит вернем все заявки (с любым
статусом) для всех вакансий.
Если этот метод среди всех найденных заявок обнаружит объекты с статусом
SENDING, то такие заявки автоматически меняют статус на WAITING и
сохраняются.
                </p>
                <br/>
                  <h2 class="api">http://prommu.com:4445/api/employer/vacancy/request/status/ POST</h2>
                <em>Request</em>
                <p> 
                     <b>Parameters</b>: request - id объекта <button class="md-trigger" data-modal="modal-vr">Vacancy Request</button>. Во-первых это должна быть
заявка, во-вторых должна принадлежать текущему employer'у и втретьих иметь статус WAITING – иначе вернем ошибку<br/>
                 status - RequestStatus который нужно присвоить заявке. Здесь
должно быть либо CONFIRM, либо REFUSE, иначе вернем ошибку.
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                 Отреагировать на ожидающую заявку – то есть поменять ее статус
(employerStatus) на CONFIRM или REFUSE
                </p>
                <br/>
                <h1 style="  font-size: 37px;">Чат</h1>
                  <h2 class="api">http://prommu.com:4445/api/employer/chat/ POST</h2>
                <em>Request</em>
                <p> 
                     <b>Parameters</b>: text - сообщение<br/>
                     user – id юзера получателя<br/>
                      title – тема чата<br/>
                     
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                  Отправить сообщение в чат
                </p>
                <em>Дополнительно</em>
                <p> 
                  Метод сам находит нужный чат и добавляет в него сообщение. Если
соответствующего чата нет, то он создается.
                </p>
                <br/>
                  <h2 class="api">http://prommu.com:4445/api/employer/chat/ GET</h2>
                <em>Request</em>
                <p> 
                     <b>Parameters</b>:• id — чата, сообщения которого хотим получить<br/>
                     • all - (api/employer/chat/all) - возвращает все чаты пользователя<br/>
                </p>
                <em>Response</em>
                <p> 
                     <b>Body</b>: <button class="md-trigger" data-modal="modal-chat">Chat[]</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                  Получение своих чатов.
                </p>
                <em>Дополнительно</em>
                <p> 
                  Если read не передается, значит вернем все чаты (с прочитанными сообщениями и
не прочитанными).
Этот метод автоматически всем полученным сообщениям в чатах выставляет
свойство read в true, то есть отмечает все сообщения как прочитанные.
                </p>
                <br/>
                  <h2 class="api">http://prommu.com:4445/api/employer/chat/vacancy/ POST</h2>
                <em>Request</em>
                <p> 
                     <b>Parameters</b>: • promo — id обьекта <button class="md-trigger" data-modal="modal-promo">Promo</button><br/><br/>
                     • text – текст сообщения<br/>
                     • vacancy – id объекта <button class="md-trigger" data-modal="modal-vacancy">Vacancy</button><br/>. Вакансия должна принадлежать
текущему работодателю (принципал) иначе вернем ошибку.<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                  Отправить сообщение соискателю в чат вакансии
                </p>
                <em>Дополнительно</em>
                <p> 
                 Метод сам находит нужный чат и добавляет в него сообщение. Если
соответствующего чата нет, то он создается.
Важно: переданный соискатель должен иметь подтвержденную заявку или
приглашение на переданную вакансию, иначе вернем ошибку.
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/employer/chat/vacancy/ GET</h2>
                <em>Request</em>
                <p> 
                     <b>Parameters</b>: • read — если здесь false, то вернутся только чаты с
непрочитанными сообщениями. Но! Если true, то вернутся все чаты,
а не только с прочитанными сообщениями (просто нет смысла в
поиске только с прочитанными).<br/>
                     • vacancy – id объекта <button class="md-trigger" data-modal="modal-vacancy">Vacancy</button><br/>. Если параметр передается,
значит вернутся чаты только для этой вакансии.<br/>
                </p>
                <em>Response</em>
                <p> 
                     <b>Body</b>: <button class="md-trigger" data-modal="modal-vc">VacancyChat[]</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                  Получение своих чатов вакансий.
                </p>
                <em>Дополнительно</em>
                <p> 
                 Если read не передается, значит вернем все чаты (с прочитанными сообщениями и
не прочитанными).
Этот метод автоматически всем полученным сообщениям в чатах выставляет
свойство read в true, то есть отмечает все сообщения как прочитанные
                </p>
                <br/>
            <li data-date="09/12/2014">
                <h2>Запросы</h2>
                <p> 
                   Если параметр запроса подчеркнут (пример: promo) значит это обязательный
параметр, иначе его можно не передавать.<br/>
• Если параметр или свойство объекта — boolean тип, то можно передавать
как true \ false, так и 1 \ 0.<br/>
• Если параметр — массив объектов, то передавать надо массив id этих
объектов. Например параметр cities — массив объектов city, то передавать
надо cities=1,3,5 — то есть id объектов.<br/>
• Если параметр или свойство объекта следующего типа:<br/>
◦ Date - формат: дд-мм-гггг (например 23-06-2007)<br/>
◦ DateTime - формат: дд-мм-гггг_чч-мм (например 22-01-2017_01-21)<br/>
• Любой метод для редактирования объектов (/api/promo/ POST или
/api/employer/ POST) не редактирует внутренние объекты, если в методе не
указанно иное. Например: метод /api/employer/vacancy/ POST редактирует
объект Vacancy, но внутренний объект Employer (свойство employer) он не
редактирует – менять его бесполезно, это ни чего не даст.<br/>
• Если метод возвращает объект Page, то в дополнение к параметрам метода
можно так же передать общие параметры постраничной выборки:<br/>
◦ page — номер страницы<br/>
◦ size — кол-во объектов в странице<br/>
◦ sort — сортировка по свойству. Примеры: sort=lastname,asc;
sort=id,desc<br/>
                </p>
                <h2>Безопасность и ответы</h2>
                <p>
                     API использует basic авторизацию, то есть в заголовке каждого запроса
должно быть поле Authorization : Basic amF2YS1ib3R <br/>
• По уровням доступа (кто и какие методы может вызывать):<br/>
◦ /api/** - любой (в т.ч. не авторизированый) пользователь<br/>
◦ /api/common/** - Promo и Employer<br/>
◦ /api/user/** - Promo и Employer<br/>
◦ /api/promo/** - только Promo<br/>
◦ /api/employer/** - только Employer<br/>
• Если в описании метода нет раздела Response – значит метод ничего не
возвращает.<br/>
• Если метод вернул статус 200 или 201, значит метод завершил работу без
ошибок и в теле ничего не возвращает (разумеется если методом не
предусмотрено иное).<br/>
• Если статус ответа метода не 200 или 201, значит обычно метод возвращает
ошибку (Error), кроме очевидных случаев вроде 404 или 401.<br/>
• Если POST метод требует существующий в базе объект - например City, но
получает такой, которого нет в базе — вернем ошибку.<br/>
                </p>
                <h2>Прочее</h2>
                <p>OtherIII.Модели 5.Other</p>
<p>
1. Error<br/>
{<br/>
"status": 500,<br/>
"message": "Error message content"<br/>
}<br/>
</p><p>
2. Page<br/>
{<br/>
"content": [],<br/>
"totalElements": 0,<br/>
"totalPages": 0,<br/>
"numberOfElements": 0,<br/>
"size": 50,<br/>
"number": 0,<br/>
"sort": {<br/>
"direction": "DESC",<br/>
"property": "id"<br/>
}<br/>
}<br/>
</p><p>
3. <b>Sex<b> (enum)<br/>
MALE<br/>
FEMALE<br/>
</p><p>
4. RequestStatus (enum)<br/>
SENDING<br/>
WAITING<br/>
CONFIRM<br/>
REFUSE<br/>
</p><p>
5. PayType (enum)<br/>
HOURLY<br/>
WEEKLY<br/>
MONTHLY<br/>
</p><p>
6. FeedbackType (enum)<br/>
NEUTRAL<br/>
NEGATIVE<br/>
POSITIVE<br/>
</p>
</ul>
<p>
            </li>

            <li data-date="09/09/2014">
                <h2 class="api">http://prommu.com:4445/api/signup/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • promo - true — если это Promo , или false для Employer'a<br/>
                    • codes - 1 (запрос на получение кода активации) , codes = пароль полученный с сообщения<br/>
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-user">User</button><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                    Регистрация нового пользователя c помощью email
                </p>
                <em>Дополнительно</em>
                <p> 
                    Свойства email, password – обязательные. То есть если вы ни чего не передадите
в этих свойствах (фактически передадите null), то получите ошибку.
                </p>
                <br/>
                <h2 class="api">http://prommu.com:4445/api/signupnew/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • promo - true — если это Promo , или false для Employer'a<br/>
                    • codes - 1 (запрос на получение кода активации) , codes = пароль полученный с сообщения<br/>
                    • password - пароль юзера<br/>
                    • phone - номер телефона<br/>
                   
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                    Регистрация нового пользователя с помощью телефона
                </p>
                
                <br/>
                <h2 class="api">http://prommu.com:4445/api/network/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • promo - true — если это Promo , или false для Employer'a<br/>
                    • code - access_token профиля в социальной сети<br/>
                    • provider - обозначение социальной сети (fb, ok, vk, gl)<br/>
                    • email - email пользователя в социальной сети<br/>
                    • userid - индентификатор (user_id) пользователя в социальной сети<br/>
                   При авторизации и регистрации возвращается - пароль и логин. 

                    
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                    Регистрация нового пользователя c помощью социальных сетей
                </p>
                <br/>

                 <h2 class="api">http://prommu.com:4445/api/recovery/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: • email - email, под которым пользователь зарегистрирован<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Восстановление пароля пользователя
                </p>
                <em>Дополнительно</em>
                <p> 
                    Метод генерирует и сохраняет новый пароль, затем отправляет его на почту
пользователю.
                </p>
                <br/>
                 <h2 class="api">http://prommu.com:4445/api/help/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>:
                    <br/> 
                    • name – имя <br/>
                    • email – почта для ответа<br/>
                    • theme – тема сообщения<br/>
                    • text – текст сообщения<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Обратная связь
                </p>
                     <br/>
                 <h2>http://prommu.com:4445/api/user/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-user">User</button><br/><br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Редактирование личных данных пользователя
                </p>
                <em>Дополнительно</em>
                <p> 
                    Свойство email не изменяемое, то есть неважно что вы в нем передадите,
редактироваться будет принципал (авторизированный обьект пользователя)
                </p>
                 <br/>
                 <h2 class="api">http://prommu.com:4445/api/user/photo/ POST</h2>
                <em>Request</em>
                <p> 
                    <b>Body</b>: Byte [] - маcсив байтов изображения.<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-success">Success</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                   Изменение фото.
                </p>
                <em>Дополнительно</em>
                <p> 
                   Если пользователь – соискатель, то это имеется ввиду его фото. Если пользователь
– работодатель, то это лого компании.
                </p>
                 <br/>
                 <h2 class="api">http://prommu.com:4445/api/user/photo/ GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: user – id пользователя, чье изображение надо получить<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: Byte [] - маасив байтов изображения.<br/>
                </p>
                <em>Описание</em>
                <p> 
                   Получение фото
                </p>
                <em>Дополнительно</em>
                <p> 
                  Если параметр user не передавать, то получим свое фото. Если нет фото (своего
или переданного юзера), то вернем пустой массив.
                </p>
                 <br/>
                 <h2 class="api">http://prommu.com:4445/api/common/post/ GET</h2>
                
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-2">Post[]</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                  Получение всех типов компаний в системе
                </p>
                 <br/>
                 <h2 class="api">http://prommu.com:4445/api/common/city/ GET</h2>
                <em>Request</em>
                <p> 
                    <b>Parameters</b>: country – false – значит метод вернет обьекты <button class="md-trigger" data-modal="modal-5">CityLight</button>; true – для
обьектов <button class="md-trigger" data-modal="modal-4">City</button>, их же метод и вернет если не передавать параметр<br/>
                </p>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-4">City[]</button> / <button class="md-trigger" data-modal="modal-5">CityLight[]</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                 Получение всех городов в системе
                </p>
                 <br/>
                 <h2 class="api">http://prommu.com:4445/api/common/companytype/ GET</h2>
                <em>Response</em>
                <p> 
                    <b>Body</b>: <button class="md-trigger" data-modal="modal-6">CompanyType[]</button><br/>
                </p>
                <em>Описание</em>
                <p> 
                 Получение всех должностей в системе
                </p>
            </li>
        </ol>
    </div>

    <div class="md-modal md-effect-1" id="modal-user">
            <div class="md-content">
                <h3>User Model</h3>
                <div>
                    <p> { <br/>
"id" : 1,<br/>  
"email" : "my@mail.com",<br/>
"password" : "12345",<br/>
"firstName" : "Vasya",<br/>
"lastName" : "Nomatter",<br/>
"phone" : "34567896"<br/>
"messages": "12",<br/>
"rate"{
    "positive":"3",<br/>
    "negative":"1"<br/>
}
"invite":"1"<br/>
}</p>
                    
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div> <!-- .events-content -->
           <div class="md-modal md-effect-1" id="modal-2">
            <div class="md-content">
                <h3>Post Model</h3>
                <div>
                    <p> {<br/>
"id" : 1,<br/>
"name" : "Промоутер"<br/>
}</p>
                    
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div> <!-- .events-content -->

<div class="md-modal md-effect-1" id="modal-3">
            <div class="md-content">
                <h3>Country Model</h3>
                <div>
                    <p> {<br/>
"id" : 1,<br/>
"name" : "Рф"<br/>
}</p>
                    
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>

        <div class="md-modal md-effect-1" id="modal-4">
            <div class="md-content">
                <h3>City Model</h3>
                <div>
                    <p> {<br/>
"id" : 1,<br/>
"name" : "Москва",<br/>
"country" : Country<br/>
}</p>
                    
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>


                <div class="md-modal md-effect-1" id="modal-5">
            <div class="md-content">
                <h3>CityLight Model</h3>
                <div>
                    <p> { <br/>
"id" : 1,<br/>
"country" : 1,<br/>
"name" : "Москва"<br/>
}<br/>
country – id объекта Country</p>
                    
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>


         <div class="md-modal md-effect-1" id="modal-6">
            <div class="md-content">
                <h3>CompanyType Model</h3>
                <div>
                    <p> {<br/>
"id" : 1,<br/>
"name" : "ивент агенство"<br/>
}<br/></p>
                    
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div> 

         <div class="md-modal md-effect-1" id="modal-contact">
            <div class="md-content">
                <h3>Contact Model</h3>
                <div>
                    <p> {<br/>
"id" : 0,<br/>
"skype" : "gfdfgd",<br/>
"fbUrl" : "gfdfgd",<br/>
"vkUrl" : "gfdfgd",<br/>
"viber" : "gfdfgd",<br/>
"extraPhone" : "343234234334",<br/>
"other" : "gfdfgd"<br/>
}<br/></p>     
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
        <div class="md-modal md-effect-1" id="modal-exp">
            <div class="md-content">
                <h3>Experience Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"post" : Post<br/>
}<br/></p>     
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
        <div class="md-modal md-effect-1" id="modal-tv">
            <div class="md-content">
                <h3>TargetVacancy Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"rate" : 0,<br/>
"post" : Post,<br/>
"city" : City<br/>
}<br/></p>     
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
        <div class="md-modal md-effect-1" id="modal-promo">
            <div class="md-content">
                <h3>Promo Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"birthday" : "22-01-2017",<br/>
"sex" : Sex,<br/>
"medCertExists" : false,<br/>
"carExists" : false,<br/>
"user" : User,<br/>
"contact" : Contact,<br/>
"targetVacancies" : [ TargetVacancy ],<br/>
"experiences" : [ Experience ]<br/>
}<br/></p>     
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
         <div class="md-modal md-effect-1" id="modal-pf">
            <div class="md-content">
                <h3>PromoFeedback Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"text" : "Текст",<br/>
"positive" : false,<br/>
"rate" : PromoRate,<br/>
"vacancy" : Vacancy,<br/>
"promo" : Promo,<br/>
"date" : "25-01-2017_16-29"<br/>
}<br/></p> <br/> <p>Свойства vacancy, promo — это составной уникальный ключ, то есть нельзя создать
два объекта PromoFeedback с одинаковыми данными в этих двух свойствах.</p>    
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
        <div class="md-modal md-effect-1" id="modal-pr">
            <div class="md-content">
                <h3>PromoRate Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"quality" : FeedbackType,<br/>
"punctuality" : FeedbackType,<br/>
"sociability" : FeedbackType<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>      
            <div class="md-modal md-effect-1" id="modal-vr">
            <div class="md-content">
                <h3>VacancyRequest Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"vacancy" : Vacancy,<br/>
"promo" : Promo,<br/>
"promoStatus" : RequestStatus,<br/>
"employerStatus" : RequestStatus,<br/>
"date" : "22-01-2017_01-21"<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
              <div class="md-modal md-effect-1" id="modal-ef">
            <div class="md-content">
                <h3>EmployerFeedback Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"text" : "Текст",<br/>
"positive" : false,<br/>
"rate" : EmployerRate,<br/>
"vacancy" : Vacancy,<br/>
"promo" : Promo,<br/>
"date" : "25-01-2017_16-29"<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
         <div class="md-modal md-effect-1" id="modal-er">
            <div class="md-content">
                <h3>EmployerRate Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"term" : FeedbackType,<br/>
"payment" : FeedbackType,<br/>
"clarity" : FeedbackType,<br/>
"sociability" : FeedbackType<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
        <div class="md-modal md-effect-1" id="modal-mc">
            <div class="md-content">
                <h3>MessageChat Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"text" : "текст",<br/>
"author" : Promo,<br/>
"read" : false,<br/>
"date" : "25-01-2017_16-29"<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
        <div class="md-modal md-effect-1" id="modal-chat">
            <div class="md-content">
                <h3>Chat Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"sender" : User,<br/>
"reciver" : User,<br/>
"title" : "Тема чата",<br/>
"messages" : [<br/>
MessageChat,<br/>
MessageChat<br/>
]<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
        <div class="md-modal md-effect-1" id="modal-vc">
            <div class="md-content">
                <h3>VacancyChat Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"promo" : User,<br/>
"vacancy" : Vacancy,<br/>
"messages" : [<br/>
MessageChat,<br/>
MessageChat<br/>
]<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
        <div class="md-modal md-effect-1" id="modal-vc">
            <div class="md-content">
                <h3>VacancyChat Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"promo" : User,<br/>
"vacancy" : Vacancy,<br/>
"messages" : [<br/>
MessageChat,<br/>
MessageChat<br/>
]<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
                <div class="md-modal md-effect-1" id="modal-vacancy">
            <div class="md-content">
                <h3>Vacancy Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"title" : null,<br/>
"premium" : false,<br/>
"requirements" : null,<br/>
"duties" : null,<br/>
"conditions" : null,<br/>
"temporary" : false,<br/>
"pay" : 0,<br/>
"payType" : PayType,<br/>
"ageFrom" : 0,<br/>
"ageTo" : 0,<br/>
"experience" : null,<br/>
"medCert" : false,<br/>
"car" : false,<br/>
"deactivate" : "22-01-2017",<br/>
"datestart" : "17-01-2017",<br/>
"dateend" : "21-01-2017",<br/>
"sex" : Sex,<br/>
"post" : Post,<br/>
"city" : City,<br/>
"employer" : Employer<br/>
<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>
        <div class="md-modal md-effect-1" id="modal-employer">
            <div class="md-content">
                <h3>Employer Model</h3>
                <div>
                    <p>{<br/>
"id" : 0,<br/>
"user" : User,<br/>
"company" : Company,<br/>
"post" : Post<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>   
 <div class="md-modal md-effect-1" id="modal-success">
            <div class="md-content">
                <h3>Request Success Model</h3>
                <div>
                    <p>{<br/>
  "status": 200,<br/>
  "message": "success"<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div> 
        <div class="md-modal md-effect-1" id="modal-push">
            <div class="md-content">
                <h3>Push Model</h3>
                <div>
                    <p>{<br/>
  "status": 200,<br/>
  "message": "success"<br/>
}<br/></p>
                    <button class="md-close">Закрыть!</button>
                </div>
            </div>
        </div>   
</section>
<script src="/js/mains.js"></script> 
<!-- <script src="/js/cssParser.js"></script>
<script src="/js/css-filters-polyfill.js"></script> -->
<script src="/js/classie.js"></script>
<script src="/js/modalEffects.js"></script><!-- Resource jQuery -->