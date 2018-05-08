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
}