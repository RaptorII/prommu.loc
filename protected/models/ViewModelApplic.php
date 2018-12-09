<?php
/**
 * Date: 01-03-16
 */

class ViewModelApplic extends ViewModel
{
    function __construct()
    {
        parent::__construct();

        $view = MainConfig::$VIEWS_APPLICANT;
        $this->pageProfile = MainConfig::$VIEWS_APPLICANT_PROFILE_OWN;
        $this->pageEditProfile = $view . MainConfig::$VIEWS_EDIT_PROFILE;
        $this->pageRegisterPopup = $view . MainConfig::$VIEWS_REGISTER_POPUP;
        $this->pageMyPhotos = $view . MainConfig::$VIEWS_MY_PHOTOS;
        $this->pageResponses = MainConfig::$VIEWS_APPLICANT_RESPONSES;
        $this->pageSetRate = MainConfig::$VIEWS_APPLICANT_SETRATE;
        $this->pageMessView = MainConfig::$VIEWS_APPLICANT_MESS_VIEW;
    }
}