<?php
/**
 * Date: 24.02.2016
 * Time: 12:55
 */



class ViewModel
{
    static public $LOGO_TYPE_APPLIC = 2;
    static public $LOGO_TYPE_EMPL = 3;
    static public $LOGO_SIZE_400 = 2;
    static public $LOGO_SIZE_MAX = 3;

    //Yii::app()->Controller->ViewModel->formatTelNumber($attr[1]['val'])
    public $pageProfile;
    public $pageEditProfile;
    public $pageRegisterPopup;
    public $pageMyPhotos;
    public $pageSearchVac;
    public $pageSearchEmpl;
    public $pageSearchPromo;
    public $pageVacancy;
    public $pageResponses;
    public $pageSetRate;
    public $pageMessages;
    public $pageMessView;
    public $pageFeedback;
    public $pageCardprommu;
    public $addContentClass;

    private $viewData = [];



    public function __construct()
    {
        $this->pageSearchVac = MainConfig::$VIEWS_SEARCH_VAC;
        $this->pageSearchEmpl = MainConfig::$VIEWS_COMPANY_LIST;
        $this->pageSearchPromo = MainConfig::$VIEWS_SEARCH_PROMO;
        $this->pageVacancy = MainConfig::$VIEWS_VACANCY_VIEW;
        $this->pageMessages = MainConfig::$VIEWS_USER_CHATS;
        $this->pageFeedback = MainConfig::$VIEWS_FEEDBACK;
        $this->pageCardprommu = MainConfig::$VIEWS_CARD_PROMMU;
    }


    public function init()
    {
        if( Share::$isAjaxRequest ) { } else
        {
            if( Share::$UserProfile->type > 0 )
            {
                // получаем меню для профилей
                if( Share::$UserProfile->type == 2 ) $menuId = 5;
                elseif( Share::$UserProfile->type == 3 ) $menuId = 6;

                $menu = new Menu;
                Share::$viewData['menu'] = $menu->getTreeDB(0, Share::getLangSelected(), $menuId, 1); // меню кабинета работодателя


                // получаем счётчики для работодателя
                if( Share::$UserProfile->type == 3 )
                {
                    $this->setViewData('userData', array('newResponses' => (new ResponsesEmpl())->getNewResponses()));
                }
                elseif( Share::$UserProfile->type == 2 )
                {
                    $this->setViewData('userData', array('newResponses' => (new ResponsesApplic())->getNewResponses()));
                } // endif
            } // endif
        } // endif


        // добавляем классы в узел контента, чтобы не дублировать CSS для каждой страницы
        if( Yii::app()->controller->action->getId() == 'userprofile' )
        {
            if( $this->getViewData('profType') == 3 ) $this->setViewData('addContentClass', 'page-company-profile-own');

        } elseif( Yii::app()->controller->action->getId() == 'vacedit' )
        {
            $this->setViewData('addContentClass', 'page-vacpub');
        } // endif
    }



    public function getViewData($inKey = '') { return $inKey ? $this->viewData->$inKey : $this->viewData; }


    public function setViewData($key, $val)
    {
        if( !$this->viewData ) $this->viewData = (object)[];
        $this->viewData->$key = $val;
    }



    /**
     * форматирует номер телефона
     */
    public function formatTelNumber($inNum)
    {
        return strlen($inNum) > 4 ? sprintf("%s%s %s %s",
                    strlen($inNum) > 10 ? substr($inNum, 0, strlen($inNum)-10) . ' ' : '',
                    strlen($inNum) > 7 ? substr($inNum, strlen($inNum)-10, 3) : '',
                    substr($inNum, strlen($inNum)-7, 3),
                    substr($inNum, strlen($inNum)-4, 4))
                : $inNum;
    }



    /**
     * найти элемент в матричном массиве
     */
    public function isInArray($inArr, $inKey, $inVal)
    {
        if( is_array($inArr) )
            foreach ($inArr as $key => $val)
            {
                if( $val[$inKey] == $inVal ) return $key;
            } // end foreach
        return 0;
    }



    /**
     * заменить параметр в URL строке
     */
    public function replaceInUrl($inUrl, $inKey, $inVal)
    {
        if( !$inUrl ) $inUrl = Yii::app()->request->url;

        $parts = parse_url($inUrl);
        parse_str($parts['query'], $query);
        if( $inVal == null ) unset($query[$inKey]);
        else $query[$inKey] = $inVal;

        $query = http_build_query($query);

        return $parts['path'] . ($query ? '?' . $query : '');
    }


    public function declOfNum($number, $titles)
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
    }



    /**
     * Получаем дерево в виде списка
     */
    public function getTree($inData, $inIDParent, $lvl = 0)
    {
        if(is_array($inData) and isset($inData[$inIDParent]))
        {
            $tree = "<ul class='lvl-{$lvl}'>";
                foreach ($inData[$inIDParent] as $row)
                {
                    $active = $row['active'] ? 'active' : '' ;
                    if( $row['nolink'] ) $tree .= "<li><span class='item itm-{$row['id']}'>{$row['name']}</span>";
                    else $tree .= "<li><a href='{$row['link']}' class='item itm-{$row['id']} {$active}'>{$row['name']}</a>";

                    $tree .= $this->getTree($inData, $row['id'], $lvl + 1);
                    $tree .= '</li>';
                }
            $tree .= '</ul>';
        } else return null;

        return $tree;
    }



    /**
     * Форматируем вывод даты
     * @param $inDatebegin - дата для форматирования
     * @param $inDateend - если дата коца больше года - добавляем год в вывод
     */
    public function getFormatPeriodDate($inDatebegin, $inDateend = 0)
    {
        $monthes = array('янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек',);
        $wdays = array('Вск', 'пнд', 'Втр', 'Срд', 'Чтв', 'Птн', 'Сбт',);

        $time = strtotime($inDatebegin);

        $datetime1 = date_create($inDatebegin);
        $datetime2 = date_create($inDateend);
        $interval = date_diff($datetime1, $datetime2);
        $interval = $interval->format('%y');


        $data = getdate($time);
        $dt1 = sprintf("%s %s", $data['mday'], $monthes[$data['mon']-1]);
        if( abs($interval) > 0 ) $dt1 .= ' ' . $data['year'];
        $dt1 .= " (" . ucfirst($wdays[$data['wday']]) . ")";
        return $dt1;
    }



    /**
     * Добавляем классы к контенту
     */
    public function addContentClass($inClass)
    {
        $this->setViewData('addContentClass', $this->getViewData('addContentClass') . ' ' . $inClass);
    }



    /**
     * Добавляем классы к контенту
     */
    public function addBodyClass($inClass)
    {
        $this->setViewData('addBodyClass', $this->getViewData('addBodyClass') . ' ' . $inClass);
    }

        public function getHtmlLogoPromo($inLogo, $inType, $inFormat = 0, $sex)
    {
        // тип пользователя работодатель
        
        if( $inType == ViewModel::$LOGO_TYPE_APPLIC )
        {
            if($sex == 1){
                $path = MainConfig::$PATH_APPLIC_LOGO;
                $defLogo = MainConfig::$DEF_LOGO;
            }
            else {
                $path = MainConfig::$PATH_APPLIC_LOGO;
                $defLogo = MainConfig::$DEF_LOGO_F;
            }
        } // endif


        // размер картинки
        switch ($inFormat) {
           case 2 : $format = '400'; break;
           case 3 : $format = '000'; break;
           default: $format = 100;
        }
        return DS . $path . DS . (!$inLogo ?  $defLogo : $inLogo . $format . '.jpg');
    }

    /**
     * Получаем HTML код для вставки лого
     * @param $inLogo - id лого из базы
     * @param $inType int - тип пользователя
     * @param $inFormat int - размер лого 1 - 100, 2 - 400, 3 - 000
     */
    // $this->ViewModel->getHtmlLogo(array_values($val)[0]['photo'], 0, 1)
    public function getHtmlLogo($inLogo, $inType, $inFormat = 0, $sex)
    {
        // тип пользователя работодатель
        if( $inType == ViewModel::$LOGO_TYPE_EMPL )
        {
            $path = MainConfig::$PATH_EMPL_LOGO;
            $defLogo = MainConfig::$DEF_LOGO_EMPL;
        }
        elseif( $inType == ViewModel::$LOGO_TYPE_APPLIC )
        {
            $path = MainConfig::$PATH_APPLIC_LOGO;
            $defLogo = MainConfig::$DEF_LOGO;
        } // endif


        // размер картинки
        switch ($inFormat) {
           case 2 : $format = '400'; break;
           case 3 : $format = '000'; break;
           default: $format = 100;
        }
        return DS . $path . DS . (!$inLogo ?  $defLogo : $inLogo . $format . '.jpg');
    }

}