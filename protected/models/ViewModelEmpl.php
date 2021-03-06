<?php
/**
 * Date: 01-03-16
 */

class ViewModelEmpl extends ViewModel
{
    public $pageVacancies;


    function __construct()
    {
        parent::__construct();

        $this->pageProfile = MainConfig::$VIEWS_COMPANY_PROFILE_OWN;
        $this->pageEditProfile = MainConfig::$VIEWS_EDIT_PROFILE_EMPLOYER;
        $this->pageVacancies = MainConfig::$VIEWS_COMPANY_VACS_OWN;
        $this->pageResponses = MainConfig::$VIEWS_EMPL_RESPONSES;
        $this->pageSetRate = MainConfig::$VIEWS_APPLICANT_SETRATE;
        $this->pageMessView = MainConfig::$VIEWS_EMPL_MESS_VIEW;
        $this->lastRegisterForm = MainConfig::$VIEWS_EMPLOYER_REGISTER_FORM;
    }
}