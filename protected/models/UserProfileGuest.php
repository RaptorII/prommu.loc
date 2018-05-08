<?php

/**
 * Date: 18.02.2016
 * Time: 10:12
 */

class UserProfileGuest extends UserProfile
{
    public function getProfileData() { } // abstract

    function __construct($inProps)
    {
        parent::__construct($inProps);

        $this->id = 0;
        $this->exInfo = (object)array();
        $this->type = 0;
    }


    public function makeRate($inProps) { }

    // фабрика модели рейтинга
    public function makeResponse() { }

    // фабрика чатов
    public function makeChat() { return null; }

    // получаем данные профиля для API
    public function getProfileDataAPI($props) { }

    public function setUserData() { }
    public function getProfileDataEdit() { }
    public function getRateCount($inID = 0) { }
    public function getCommentsCount($inID = 0) { }
    public function getProfileDataView($inID = 0) { }
    public function proccessLogo() { }

    protected function getUserData($inId) { }
}
