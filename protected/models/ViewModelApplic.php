<?php
/**
 * Date: 01-03-16
 */

class ViewModelApplic extends ViewModel
{
    function __construct()
    {
        parent::__construct();

        $this->pageProfile = MainConfig::$VIEWS_APPLICANT_PROFILE_OWN;
        $this->pageEditProfile = MainConfig::$VIEWS_EDIT_PROFILE_APPLICANT;
        $this->pageResponses = MainConfig::$VIEWS_APPLICANT_RESPONSES;
        $this->pageSetRate = MainConfig::$VIEWS_APPLICANT_SETRATE;
        $this->pageMessView = MainConfig::$VIEWS_APPLICANT_MESS_VIEW;
        $this->pageVacancies = MainConfig::$VIEW_APPLICANT_VACS_LIST;
        $this->pageVacancyItem = MainConfig::$VIEW_APPLICANT_VACS_ITEM;
        $this->lastRegisterForm = MainConfig::$VIEWS_APPLICANT_REGISTER_FORM;
    }
}