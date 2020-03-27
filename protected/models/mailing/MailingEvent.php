<?
class MailingEvent extends Mailing
{
    public static $EVENT_TYPE_EMAIL = 1;
    public static $EVENT_TYPE_PUSH = 2;
    public static $TYPES = array(
        1 => 'email',
        2 => 'push'
    );

    function __construct()
    {
        $this->view = 'notifications/event';
        $this->pageTitle = 'события';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'admin_mailing_event';
    }

    /**
     *        Чтение данных
     */
    public function getData($id)
    {
        $template = new MailingTemplate;
        $arRes['template'] = $template->getActiveTemplate();
        $arRes['item'] = $this::model()->findByPk($id);

        return $arRes;
    }

    /**
     *        Запись данных
     */
    public function setData($obj)
    {
        $arRes = array('error' => false);
        // id
        $id = $obj->getParam('id');
        // emails
        $this->receiver = filter_var(
            $obj->getParam('receiver'),
            FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );
        $arReceiver = $this->getEmailArray($this->receiver);
        // title
        $this->title = filter_var(
            trim($obj->getParam('title')),
            FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );
        // text
        $this->text = $obj->getParam('text');
        // is_active
        $this->is_active = $obj->getParam('is_active');
        // is_urgent
        $this->is_urgent = $obj->getParam('is_urgent');

        if (!count($arReceiver))
            $arRes['messages'][] = 'необходимо ввести корректный Email';
        if (empty($this->title) || empty($this->text))
            $arRes['messages'][] = 'поля "Заголовок" и "Текст письма" должны быть заполнены';

        if (count($arRes['messages'])) // error
        {
            $arRes['error'] = true;
            $event = $this->getData($id)['item'];
            $this->comment = $event->comment;
            $this->type = $event->type;
            $this->params = $event->params;
            $arRes['item'] = $this;
            $template = new MailingTemplate;
            $arRes['template'] = $template->getActiveTemplate();

            return $arRes;
        }

        $time = time();
        $this->mdate = $time;

        if (!intval($id)) // insert
        {
            $this->cdate = $time;
            $this->setIsNewRecord(true);
        } else // update
        {
            $this->id = $id;
        }

        if ($this->save()) {
            $this->setCacheData(); // Заносим все в кеш для ускорения работы
            Yii::app()->user->setFlash('success', 'Данные успешно сохранены');
            return array('redirect' => true);
        } else {
            Yii::app()->user->setFlash('danger', 'Ошибка сохранения');
            return array('redirect' => true);
        }

        return $arRes;
    }

}


//		Пример массива параметров для события
array(
    'id_user' => array(
        'name' => "#ID_USER#",
        'pattern' => "/#ID_USER#/",
        'description' => "ID пользователя сайта"
    ),
    'link_profile' => array(
        'name' => "#LINK_PROFILE#",
        'pattern' => "/#LINK_PROFILE#/",
        'value' => '#SITE##PAGE_PROFILE_COMMON#/#ID_USER#',
        'breplace' => true, // флаг, обозначающий наличие заменяемых частей в value
        'description' => "Ссылка на профиль пользователя"
    ),
    'link_profile_admin' => array(
        'name' => "#LINK_PROFILE_ADMIN#",
        'pattern' => "/#LINK_PROFILE_ADMIN#/",
        'value' => array( // ключ массива в value соответствует типу $usertype
            2 => '#SITE#/admin/site/PromoEdit/#ID_USER#',
            3 => '#SITE#/admin/site/EmplEdit/#ID_USER#'
        ),
        'breplace' => true,
        'description' => "Ссылка на профиль пользователя в админке"
    )
);

/*

//подборочка готовых параметров

$ppp = array(
    'email_user' => array(
        'name' => "#EMAIL_USER#",
        'pattern' => "/#EMAIL_USER#/",
        'description' => "Email пользователя сайта"
    ),
    'name_user' => array(
        'name' => "#NAME_USER#",
        'pattern' => "/#NAME_USER#/",
        'description' => "Имя пользователя сайта"
    ),
    'analytic_period' => array(
        'name' => "#ANALYTIC_PERIOD#",
        'pattern' => "/#ANALYTIC_PERIOD#/",
        'description' => "Период расчета аналитики"
    ),
    'cnt_vacancy_public' => array(
        'name' => "#ANALYTIC_VAC_PUBLIC#",
        'pattern' => "/#ANALYTIC_VAC_PUBLIC#/",
        'description' => "Опубликованых вакансий"
    ),
    'cnt_vacancy_views' => array(
        'name' => "#ANALYTIC_VAC_VIEWS#",
        'pattern' => "/#ANALYTIC_VAC_VIEWS#/",
        'description' => "Кол-во просмотров вакансий"
    ),
    'cnt_vacancy_responce' => array(
        'name' => "#ANALYTIC_VAC_RESPONCE#",
        'pattern' => "/#ANALYTIC_VAC_RESPONCE#/",
        'description' => "Кол-во откликов на вакансии"
    ),
    'cnt_vacancy_invite' => array(
        'name' => "#ANALYTIC_VAC_INVIT#",
        'pattern' => "/#ANALYTIC_VAC_INVIT#/",
        'description' => "Кол-во приглашений на вакансии"
    ),
    'vacancy_list' => array(
        'name' => "#VACANCY_LIST#",
        'pattern' => "/#VACANCY_LIST#/",
        'description' => "Список вакансий"
    ),
    'cnt_services' => array(
        'name' => "#ANALYTIC_SERVICE_CNT#",
        'pattern' => "/#ANALYTIC_SERVICE_CNT#/",
        'description' => "Кол-во использованных услуг"
    ),
    'service_list' => array(
        'name' => "#SERVICE_LIST#",
        'pattern' => "/#SERVICE_LIST#/",
        'description' => "Список использованных услуг"
    ),
    'cnt_views' => array(
        'name' => "#ANALYTIC_CNT_VIEWS#",
        'pattern' => "/#ANALYTIC_CNT_VIEWS#/",
        'description' => "Кол-во просмотров"
    ),
    'analytic_schedule_src' => array(
        'name' => "#SCHEDULE_SRC#",
        'pattern' => "/#SCHEDULE_SRC#/",
        'description' => "Картинка с графиком"
    ),
    'email_user' => array(
        'name' => "#EMAIL_USER#",
        'pattern' => "/#EMAIL_USER#/",
        'description' => "Email пользователя сайта"
    ),
    'name_user' => array(
        'name' => "#NAME_USER#",
        'pattern' => "/#NAME_USER#/",
        'description' => "Имя работодателя"
    ),
    'name_applicant' => array(
        'name' => "#NAME_APPLICANT#",
        'pattern' => "/#NAME_APPLICANT#/",
        'description' => "Имя соискателя"
    ),
    'id_applicant' => array(
        'name' => "#ID_APPLICANT#",
        'pattern' => "/#ID_APPLICANT#/",
        'description' => "ID соискателя"
    ),
    'link_profile' => array(
        'name' => "#LINK_PROFILE#",
        'pattern' => "/#LINK_PROFILE#/",
        'value' => '#SITE##PAGE_PROFILE_COMMON#/#ID_APPLICANT#',
        'breplace' => true,
        'description' => "Ссылка на профиль соискателя"
    ),
    'id_vacancy' => array(
        'name' => "#ID_VACANCY#",
        'pattern' => "/#ID_VACANCY#/",
        'description' => "ID вакансии"
    ),
    'title_vacancy' => array(
        'name' => "#TITLE_VACANCY#",
        'pattern' => "/#TITLE_VACANCY#/",
        'description' => "Заголовок вакансии"
    ),
    'link_vacancy' => array(
        'name' => "#LINK_VACANCY#",
        'pattern' => "/#LINK_VACANCY#/",
        'value' => '#PAGE_USER_VACANCY#/#ID_VACANCY#',
        'breplace' => true,
        'description' => "Ссылка на вакансию"
    )
);

echo serialize($ppp);

$ppp = array(
    'id_user' => array(
        'name' => "#ID_USER#",
        'pattern' => "/#ID_USER#/",
        'description' => "ID пользователя сайта"
    ),
    'name_user' => array(
        'name' => "#NAME_USER#",
        'pattern' => "/#NAME_USER#/",
        'description' => "Имя пользователя сайта"
    ),
    'id_vacancy' => array(
        'name' => "#ID_VACANCY#",
        'pattern' => "/#ID_VACANCY#/",
        'description' => "ID вакансии"
    ),
    'company_user' => array(
        'name' => "#COMPANY_USER#",
        'pattern' => "/#COMPANY_USER#/",
        'description' => "Название компании работодателя"
    ),
    'img_company_logo' => array(
        'name' => "#IMG_COMPANY_LOGO#",
        'pattern' => "/#IMG_COMPANY_LOGO#/",
        'description' => "Лого компании"
    ),
    'title_vacancy' => array(
        'name' => "#TITLE_VACANCY#",
        'pattern' => "/#TITLE_VACANCY#/",
        'description' => "Заголовок вакансии"
    ),
    'link_vacancy' => array(
        'name' => "#LINK_VACANCY#",
        'pattern' => "/#LINK_VACANCY#/",
        'value' => '#PAGE_USER_VACANCY#/#ID_VACANCY#',
        'breplace' => true,
        'description' => "Ссылка на вакансию"
    ),
    'position_vacancy' => array(
        'name' => "#TITLE_VACANCY#",
        'pattern' => "/#TITLE_VACANCY#/",
        'description' => "Заголовок вакансии"
    ),
    'pay_for_vacancy' => array(
        'name' => "#TITLE_VACANCY#",
        'pattern' => "/#TITLE_VACANCY#/",
        'description' => "Заголовок вакансии"
    ),
    'email_user' => array(
        'name' => "#EMAIL_USER#",
        'pattern' => "/#EMAIL_USER#/",
        'description' => "Email пользователя сайта"
    ),
    'name_applicant' => array(
        'name' => "#NAME_APPLICANT#",
        'pattern' => "/#NAME_APPLICANT#/",
        'description' => "Имя соискателя"
    ),
    'id_applicant' => array(
        'name' => "#ID_APPLICANT#",
        'pattern' => "/#ID_APPLICANT#/",
        'description' => "ID соискателя"
    ),
    'link_profile' => array(
        'name' => "#LINK_PROFILE#",
        'pattern' => "/#LINK_PROFILE#/",
        'value' => '#SITE##PAGE_PROFILE_COMMON#/#ID_APPLICANT#',
        'breplace' => true,
        'description' => "Ссылка на профиль соискателя"
    ),

);


*/
