<?php
/**
 * Date: 15.04.2016
 *
 * Модель откликов
 */

abstract class Responses extends Model
{
    /** @var UserProfile */
    protected $Profile;


    /**
     * @param $Profile UserProfile
     */
    function __construct($Profile = null)
    {
        $this->Profile = $Profile instanceof UserProfile ? $Profile : Share::$UserProfile;
    }



    /**
     * устанавливаем статус отклика
     */
    abstract public function setResponseStatus();
    /**
     * получаем кол-во откликов пользователя
     */
    abstract public function getResponsesCount($props = []);
    /**
     * получаем отклики пользователя
     */
    abstract public function getResponses();
    /**
     * Получаем кол-во новых заявок на вакансии
     */
    abstract public function getNewResponses();
    /**
     * Сохраняем данные выставленного рейтинга
     */
    abstract public function saveRateData();
    /**
     * @param $isResponse int isresponse from vacancy_stat
     * @param $status int status from vacancy_stat
     * получаем человекопонятный статус
     */
    abstract public function getStatus($isResponse, $status);

    public static $STATUS_NEW = 0; // новая заявка
    public static $STATUS_VIEW = 1; // просмотренная(отложеная)
    //public static $STATUS_??? = 2; // ???
    public static $STATUS_REJECT = 3; // отклонена
    public static $STATUS_EMPLOYER_ACCEPT = 4; // принята работодателем
    public static $STATUS_APPLICANT_ACCEPT = 5; // принята обеими сторонами
    public static $STATUS_BEFORE_RATING = 6; // есть уведомление об окончании(завершена вакансия)
    public static $STATUS_EMPLOYER_RATED = 7; // работодатель выставил рейтинг
    public static $STATUS_APPLICANT_RATED = 8; // соискатель выставил рейтинг
    public static $STATUS_FULL_RATING = 9; // все выставили рейтинг
}