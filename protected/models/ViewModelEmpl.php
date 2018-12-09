<?php
/**
 * Date: 01-03-16
 */

class ViewModelEmpl extends ViewModel
{
    public $pageVacpub;
    public $pageVacancies;


    function __construct()
    {
        parent::__construct();

        $view = MainConfig::$VIEWS_EMPLOYER;
        $this->pageProfile = MainConfig::$VIEWS_COMPANY_PROFILE_OWN;
        $this->pageEditProfile = $view . MainConfig::$VIEWS_EDIT_PROFILE;
        $this->pageRegisterPopup = $view . MainConfig::$VIEWS_REGISTER_POPUP;
        $this->pageMyPhotos = $view . MainConfig::$VIEWS_MY_PHOTOS;
        $this->pageVacpub = MainConfig::$VIEWS_PUBL_VACANCY;
        $this->pageVacancies = MainConfig::$VIEWS_COMPANY_VACS_OWN;
        $this->pageVacancArh = MainConfig::$VIEWS_COMPANY_VACS_ARHIVE;
        $this->pageResponses = MainConfig::$VIEWS_EMPL_RESPONSES;
        $this->pageSetRate = MainConfig::$VIEWS_APPLICANT_SETRATE;
        $this->pageMessView = MainConfig::$VIEWS_EMPL_MESS_VIEW;
    }
}