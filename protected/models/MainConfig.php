<?php
/**
 * Date: 15.02.2016
 * Time: 9:59
 */

//namespace protected\config;

class MainConfig
{
    public static $DOC_ROOT = ''; // $_SERVER['DOCUMENT_ROOT']

    public static $DEF_PAGE_LIMIT = 10;
    public static $DEF_PAGE_API_LIMIT = 0;
    public static $DEF_PAGE_API_LIMITS = 10000;
    public static $AUTH_EXPIRE_TIME = 604800;
    public static $AUTH_EXPIRE_TIME_LONG = 1814400;
    public static $PROFILE_FILL_MAX = 0;
    public static $EMPLOYER_MAX_PHOTOS = 10;
    public static $APPLICANT_MAX_PHOTOS = 10;

    public static $DEF_LOGO = 'logo.png';
    public static $DEF_LOGO_F = 'logo_f.png';
    public static $DEF_LOGO_EMPL = 'rk-mask.jpg';

    public static $IMG_LOADING2 = '/theme/pic/loading2.gif';

    public static $PATH_EMPL_LOGO = 'images/company/tmp';
    public static $PATH_APPLIC_LOGO = 'images/applic';
    public static $LOGO_APPLICANT_MALE = '/theme/pic/logo_applicant_male.jpg';
    public static $LOGO_APPLICANT_FEMALE = '/theme/pic/logo_applicant_female.jpg';
    public static $LOGO_EMPLOYER = '/theme/pic/logo_employer.jpg';
    public static $PATH_CSS = 'theme/css';
    public static $CSS = '/theme/css/';
    public static $JS = '/theme/js/';
    public static $PIC = '/theme/pic/';


    public static $PATH_CONTENT_PROTECTED = 'content/protected';
//    public static $PATH_COMMONTMPLS = 'protected/views/frontend/commontmpls';

    public static $DIR_VIEWS_SITE = 'site';
    public static $DIR_VIEWS_USER = 'user';

    public static $VIEWS_COMM_CONTENT_TPL = 'commontmpls/comm-content-tpl';
    public static $VIEWS_COMM_PAGES_TPL = 'comm-pages-tpl';
    public static $VIEWS_COMM_PRIVATE_CABINET_TPL = 'comm-private-cabinet-tpl';
    public static $VIEWS_COMM_PRIVATE_CABINET_APPLIC_TPL = 'comm-private-cabinet-applic-tpl';
    public static $VIEWS_DB_PAGES = 'page-db-pages-tpl';
    public static $VIEWS_API = 'page-helps-tpl';
    public static $VIEWS_SITE_MESSAGE = 'page-site-message-tpl';
    public static $VIEWS_COMM_LOGO_TPL = 'comm-logo-tpl';
//    public static $VIEWS_LOGIN = 'login';
    public static $VIEWS_LOGIN = 'page-login-tpl';
    public static $VIEWS_PHONE = 'page-phone-tpl';
    public static $VIEWS_CODES = 'page-phone-code-tpl';
    public static $VIEWS_REGISTER_APPLICANT = 'page-register-applicant-tpl';
    public static $VIEWS_REGISTER_COMPANY = 'page-register-company-tpl';
    public static $VIEWS_REGISTER_VK = 'page-register-app-vk-tpl';
    public static $VIEWS_REGISTER_FB = 'page-register-app-fb-tpl';
    public static $VIEWS_REGISTER_COMPLETE = 'page-register-complete-tpl';
    public static $VIEWS_REGISTEREX_MESSAGE = 'page-registerex-message-tpl';
    // user profile data
    public static $VIEWS_EDIT_PROFILE_APPLICANT = 'page-edit-profile-applicant-tpl';
    public static $VIEWS_EDIT_PROFILE_EMPLOYER = 'page-edit-profile-employer-tpl';
    //
    public static $VIEWS_COMPANY_PROFILE_OWN = 'page-company-profile-own-tpl';
    public static $VIEWS_COMPANY_VACS_OWN = 'vacancies/employer-list';
    public static $VIEWS_VACANCY_VIEW = 'page-vacancy-view-tpl';
    public static $VIEWS_PUBL_VACANCY = 'page-publ-vacancy-tpl';
    public static $VIEWS_APPLICANT_PROFILE_OWN = 'page-applicant-profile-own-tpl';
    public static $VIEWS_SEARCH_VAC = 'page-search-vac-tpl';
    public static $VIEWS_COMPANY_LIST = 'page-search-company-tpl';
    public static $VIEWS_SEARCH_PROMO = 'page-search-promo-tpl';
    public static $VIEWS_EMPL_RESPONSES = 'page-responses-empl-tpl';
    public static $VIEWS_APPLICANT_RESPONSES = 'page-responses-applicant-tpl';
    public static $VIEWS_APPLICANT_SETRATE = 'page-applicant-setrate-tpl';
    public static $VIEWS_USER_CHATS = 'page-user-chats-tpl';
    public static $VIEWS_APPLICANT_MESS_VIEW = 'page-applicant-mess-view-tpl';
    public static $VIEWS_EMPL_MESS_VIEW = 'page-empl-mess-view-tpl';
    public static $VIEWS_NEWS = 'page-news-tpl';
    public static $VIEWS_ARTICLES = 'page-articles-tpl';
    public static $VIEWS_ARTICLES_SINGLE = 'page-articles-single-tpl';
    public static $VIEWS_NEWS_SINGLE = 'page-news-single-tpl';
    public static $VIEWS_ABOUT = 'page-about-tpl';
    public static $VIEWS_FEEDBACK = 'page-feedback-tpl';
    public static $VIEWS_FAQ = 'page-faq-tpl';
    public static $VIEWS_COMMENTS = 'page-comments-tpl';
    public static $VIEWS_RATE_APPLIC = 'page-rate-applic-tpl';
    public static $VIEWS_EMPL_INFO = 'page-empl-tpl';
    public static $VIEWS_PROMO_INFO = 'page-promo-tpl';
    public static $VIEWS_RATE_EMPL = 'page-rate-empl-tpl';
    public static $VIEWS_SITEMAP = 'page-sitemap-tpl';
    public static $VIEWS_API_HELP = 'page-help-tpl';
    public static $VIEWS_PASS_RESTORE_FORM = 'passrestore/page-pass-restore-form-tpl';
    public static $VIEWS_NEW_PASS_FORM = 'passrestore/page-new-pass-form-tpl';

    public static $VIEWS_PUBVAC_BLOCK3_CITY_VIEW_TPL = '_block3-city-view-tpl';
    public static $VIEWS_PUBVAC_BLOCK3_NEW_CITY_TPL = '_block3-new-city-tpl';
    public static $VIEWS_PUBVAC_BLOCK3_LOCATION_VIEW_TPL = '_block3-loc-view-tpl';
    public static $VIEWS_PUBVAC_BLOCK3_LOCATION_EDIT_TPL = '_block3-loc-edit-tpl';
    public static $VIEWS_PUBVAC_BLOCK3_LOCATION_WRAPPER_TPL = '_block3-loc-wrapper-tpl';

    public static $PAGE_INDEX = '';
    public static $PAGE_SITE_MESSAGE = '/message';
    public static $PAGE_AUTH = '/user/auth';
    public static $PAGE_PROFILE = '/user/profile';
    public static $PAGE_PROFILE_COMMON = '/ankety';
    public static $PAGE_LOGIN = '/user/login';
    public static $PAGE_LOGOUT = '/user/logout';
    public static $PAGE_REGISTER = '/user/register';
    public static $PAGE_EDIT_PROFILE = '/user/editprofile';
    public static $PAGE_ACTIVATE = '/user/activate';
    public static $PAGE_VACANCY = '/vacancy';
    public static $PAGE_VACANCY_EDIT = '/user/vacedit';
    public static $PAGE_VACPUB = '/user/vacpub';
    public static $PAGE_SEARCH_EMPL = '/searchempl';
    public static $PAGE_SEARCH_PROMO = '/ankety';
    public static $PAGE_SEARCH_VAC = '/vacancy';
    public static $PAGE_RESPONSES = '/user/responses';
    public static $PAGE_SETRATE = '/user/setrate';
    public static $PAGE_SET_SITE_RATE = '/user/setsiterate';
    public static $PAGE_IM = '/user/im';
    public static $PAGE_RATE = 'rate';

    public static $PAGE_PHONE = '/phone';
    public static $PAGE_CODES = '/codes';

    public static $PAGE_NEWS = '/about/news';
    public static $PAGE_ARTICLES = '/articles';
    public static $PAGE_FEEDBACK = '/feedback';
    public static $PAGE_FAQ = '/about/faqv';
    public static $PAGE_EMPL_INFO = '/about/empl';
    public static $PAGE_PROMO_INFO = '/about/prom';
    public static $PAGE_SERVICES = '/services';
    public static $PAGE_COMMENTS = 'comments';
    public static $PAGE_PAGES = '';
    public static $PAGE_ABOUT = '/about';
    /* API */
    public static $PAGE_API = '/api';
    public static $PAGE_SEND_SMS_CODE = '/api.teles/';
    /* */
    public static $PAGE_VACANCIES = 'user/vacancies';
    public static $PAGE_VACARHIVE = 'user/vacarhive';
    public static $PAGE_PROMMUCARD = 'user/prommucard';
    public static $PAGE_VACACTIVATE = 'user/vacactivate';
    public static $PAGE_SITEMAP = 'map';
    public static $PAGE_PASS_RESTORE = 'pass-restore';
    public static $PAGE_NEW_PASS = 'new-pass';
    public static $PAGE_CONDITIONS = '/services/conditions';

    /* SERVICES */
    public static $PAGE_SERVICES_PREMIUM = '/services/premium-vacancy';
    public static $PAGE_SERVICES_SHARES = '/services/#';
    public static $PAGE_SERVICES_PUSH = '/services/push-notification';
    public static $PAGE_SERVICES_SOCIAL = '/services/publication-vacancy-social-net';
    public static $PAGE_SERVICES_SMS = '/services/sms-informing-staff';
    public static $PAGE_SERVICES_GEO = '/services/geolocation-staff';
    public static $PAGE_SERVICES_OUTSOURCING = '/services/personal-manager-outsourcing';
    public static $PAGE_SERVICES_OUTSTAFFING = '/services/outstaffing';
    public static $PAGE_SERVICES_CARD_PROMMU = '/services/prommu_card';
    public static $PAGE_SERVICES_API = '/services/api-key-prommu';
    public static $PAGE_SERVICES_MEDICAL = '/services/medical-record';
    public static $PAGE_SERVICES_EMAIL = '/services/email-invitation';
    /* SERVICES  VIEWS */
    public static $VIEWS_SERVICES = 'services/list';
    public static $VIEWS_SERVICE_VIEW = 'services/page-service-view';
    public static $VIEWS_SERVICE_PREMIUM_VIEW = 'services/page-premium-view';
    public static $VIEWS_SERVICE_PUSH_VIEW = 'services/page-push-view';
    public static $VIEWS_SERVICE_SMS_VIEW = 'services/page-sms-view';
    public static $VIEWS_SERVICE_OUTSTAFFING_VIEW = 'services/page-outstaffing-view';
    public static $VIEWS_SERVICE_OUTSOURCING_VIEW = 'services/page-outsourcing-view';
    public static $VIEWS_SERVICE_API_VIEW = 'services/page-api-view';
    public static $VIEWS_SERVICE_MEDICAL = 'services/page-medical-view';
    public static $VIEWS_SERVICE_DUPLICATION = 'services/page-social-view';
    public static $VIEWS_SERVICE_EMAIL = 'services/page-email-view';
    public static $VIEWS_CARD_PROMMU = 'services/page-card-view';
    /* SMS SERVICE PROMO AJAX */
    public static $VIEWS_SERVICE_ANKETY_AJAX = '../site/services/ankety-ajax';

    /* FOR STUDENTS */
    public static $VIEWS_WORK_FOR_STUDENTS = 'page-work-for-students-tpl';
    public static $PAGE_WORK_FOR_STUDENTS = '/work-for-students';

    /* PAYMENT VIEW */
    public static $PAGE_PAYMENT = '/user/payment';
    public static $PAGE_PAYMENT_VIEW = 'page-payment-tpl';

    /* REVIEWS/RATING */
    public static $PAGE_REVIEWS = '/user/reviews';
    public static $PAGE_REVIEWS_VIEW = 'page-reviews-tpl';

    /* ANALYTIC VIEW*/
    public static $VIEWS_ANALYTIC = 'page-analytic-tpl';

    /* VACANCY AJAX BLOCK */
    public static $VIEWS_SEARCH_VAC_AJAX = 'vacancies/vacancy-search-block';
    /* ANKETY AJAX BLOCK */
    public static $VIEWS_SEARCH_PROMO_AJAX = 'promo-search/promo-search-block';
    public static $VIEWS_API_PROMO_AJAX = 'promo-search/api-promo-search';
    /* SEARCHEMPL AJAX BLOCK */
    public static $VIEWS_SEARCH_EMPL_AJAX_BLOCK = 'empl-search/empl-search-block';
    public static $VIEWS_SEARCH_EMPL_AJAX_FILTER = 'empl-search/empl-search-filter';
    public static $LINK_TO_PLAYMARKET = 'https://play.google.com/store/apps/details?id=com.prommu.mobile';
    public static $LINK_TO_APP_STORE = '/';
    /* VACANTION VIEW FOR TABS */
    public static $VACANCY_APPROVED = 'approved'; // Утвержденные
    public static $VACANCY_INVITED = 'invited'; // Приглашенные
    public static $VACANCY_RESPONDED = 'responded'; // Откликнувшиеся
    public static $VACANCY_DEFERRED = 'deferred'; // Отложенные
    public static $VACANCY_REJECTED = 'rejected'; // Отклоненные
    public static $VACANCY_REFUSED = 'refused'; // Отказавшиеся
    /* ANALYTICS */
    public static $VIEWS_ANALYTICS = 'page-analytics-view-tpl';
    public static $PAGE_ANALYTICS = '/user/analytics';
    public static $AJAX_ANALYTICS = 'page-analytics-view-ajax';

    /* SETTINGS */
    public static $VIEWS_SETTINGS = 'page-settings-view-tpl';
    public static $PAGE_SETTINGS = '/user/settings';

    public static $PAGE_VACDELETE = 'user/vacdelete';
    public static $DEBUG_TIMER = true;

    public static $PAGE_VACTOSOCIAL = '/user/vacposttosocial';
    public static $VIEW_ORDER_SERVICE = 'services/page-from-vacancy-view';
    public static $PAGE_ORDER_SERVICE = '/orderservice';
    /* SOCIAL GROUPS */
    public static $PROMMU_VKONTAKTE = 'https://vk.com/vremennaya_rabota';
    public static $PROMMU_FACEBOOK = 'https://www.facebook.com/prommucom';
    public static $PROMMU_TELEGRAM = 'https://t.me/prommucom';
    /* IDEAS */
    public static $VIEW_IDEAS_LIST = 'ideas/page-list-view';
    public static $VIEW_IDEA = 'ideas/page-idea-view';
    public static $VIEW_IDEA_NEW = 'ideas/page-idea-new-view';
    public static $PAGE_IDEAS_LIST = '/ideas';
    public static $PAGE_IDEA_NEW = '/ideas/new';
    public static $VIEW_IDEAS_AJAX_FILTER = 'ideas/page-list-ajax';
    public static $VIEW_IDEAS_COMMENTS_AJAX_ORDER = 'ideas/page-comments-ajax';
    /* PROJECTS */
    public static $VIEW_EMP_PROJECT_LIST = 'projects/emp-list';
    public static $VIEW_APP_PROJECT_LIST = 'projects/app-list';
    public static $VIEW_PROJECT_ITEM = 'projects/project-base';
    public static $VIEW_PROJECT_NEW = 'projects/new';
    public static $VIEW_PROJECT_ALL = 'projects/all';
    public static $VIEW_PROJECT_USER_CARD = 'projects/user-card';

    public static $VIEW_PROJECT_ITEM_STAFF = 'projects/project-staff';
    public static $VIEW_PROJECT_ITEM_INDEX = 'projects/project-index';
    public static $VIEW_PROJECT_ITEM_GEO = 'projects/project-geo';
    public static $VIEW_PROJECT_ITEM_ROUTE = 'projects/project-route';
    public static $VIEW_PROJECT_ITEM_TASKS = 'projects/project-tasks';
    public static $VIEW_PROJECT_ITEM_REPORT = 'projects/project-report';
    public static $VIEW_PROJECT_ITEM_ADR_CHANGE = 'projects/project-address-edit';
    public static $VIEW_PROJECT_ITEM_PROMO_CHANGE = 'projects/project-users-select';


    public static $PAGE_PROJECT_LIST = '/user/projects';
    public static $PAGE_PROJECT_ARCHIVE = '/user/projects/archive';
    public static $PAGE_PROJECT_NEW = '/user/projects/new';
    public static $PAGE_PROJECT_ALL = '/user/projects/all';

    public static $VIEW_OTHERCITIES = 'page-cities-view';
    public static $PAGE_OTHERCITIES = '/othercities';

    /*   chats   */
    public static $PAGE_CHATS_LIST = '/user/chats';
    public static $PAGE_CHATS_LIST_VACANCIES = '/user/chats/vacancies';
    public static $PAGE_CHATS_LIST_FEEDBACK = '/user/chats/feedback';
    public static $VIEW_CHATS_LIST = 'chats/list-main';
    public static $VIEW_CHATS_LIST_VACANCIES = 'chats/list-vacancies';
    public static $VIEW_CHATS_LIST_FEEDBACK = 'chats/list-feedback';
    public static $VIEW_CHATS_ITEM_FEEDBACK = 'chats/item-feedback';
    public static $VIEW_CHATS_ITEM_VACANCY_PERSONAL = 'chats/item-vacancy-personal';
    public static $VIEW_CHATS_ITEM_VACANCY_PUBLIC = 'chats/item-vacancy-public';
    public static $VIEW_CHATS_ITEM_VACANCY_PUBLIC_AJAX = 'chats/item-vacancy-public-ajax';

    /*   applicant`s vacancies   */
    public static $VIEW_APPLICANT_VACS_LIST = 'vacancies/applicant-list';
    public static $PAGE_APPLICANT_VACS_LIST = '/user/vacancies';
    public static $PAGE_APPLICANT_VACS_LIST_ARCHIVE = '/user/vacancies/archive';
    public static $VIEW_APPLICANT_VACS_ITEM = 'vacancies/applicant-item';
    /* */
    public static $VIEW_SELF_EMPLOYED = '/user/self_employed';
    public static $VIEW_CHECK_SELF_EMPLOYED = '/user/check_self_employed';
    public static $RESOURCE_SELF_EMPLOYED = 'https://statusnpd.nalog.ru/api/v1/tracker/taxpayer_status';
    /* */
    public static $PAGE_LEGAL_ENTITY_RECEIPT = '/user/legal_entity_receipt/';
    public static $VIEW_LEGAL_ENTITY_RECEIPT = 'legal-entity-receipt';


}