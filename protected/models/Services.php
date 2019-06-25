<?php
/**
 * Date: 29.03.2016
 *
 * Модель услуг и заказа
 */

class Services extends Model
{
    /**
     * получаем данные услуги
     */
    public function getServiceData($inLink, $inId = 0)
    {
        $lang = Yii::app()->session['lang'];

        if( $inLink ) $where = "p.link = '{$inLink}'";
        else $where = "p.id = '{$inId}'";

        $sql = "SELECT p.id, p.link, pc.name, 
                pc.html, pc.img, pc.imganons, 
                pc.meta_title, pc.meta_description
            FROM pages p
            INNER JOIN pages_content pc ON p.id = pc.page_id AND pc.lang = '{$lang}'
            WHERE {$where}";
        $data['service'] = Yii::app()->db->createCommand($sql)->queryRow();
        // получаем должности
        $sql = "SELECT m.id , m.`key` , m.name val FROM user_attr_dict m WHERE m.id_par = 110  ORDER BY val";
        $data['posts'] = Yii::app()->db->createCommand($sql)->queryAll();

        return $data;
    }



    /**
     * получаем все услуги
     */
    public function getServices()
    {
        $data = array();
        $lang = Yii::app()->session['lang'];

        $sql = "SELECT p.id, p.link, pc.name, pc.anons, 
                pc.html, pc.img, pc.imganons
            FROM pages p
            INNER JOIN pages_content pc ON p.id = pc.page_id AND pc.lang = '{$lang}'
            WHERE p.group_id = 3 
            ORDER BY npp ";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        // получаем меню услуг
        $menu = $this->getMenu();

        foreach ($menu as $m) {
            $m['icon'] = str_replace('/services/', '', $m['link']);
            foreach ($res as $s) {
                $data['services'][$s['id']] = $s;       
                if($m['icon']==$s['link'] || $m['icon']=='invitations') {
                    $m['anons'] = $s['anons'];
                    $data['menu'][$m['parent_id']][$m['id']] =  $m; 
                }
            }
        }

        return $data;
    }



    /**
     * Сделать заказ на услугу
     */
    public function createServiceOrder()
    {
        $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
        $fio = filter_var(Yii::app()->getRequest()->getParam('fio'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $tel = filter_var(Yii::app()->getRequest()->getParam('tel'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var(Yii::app()->getRequest()->getParam('email'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $referer = filter_var(Yii::app()->getRequest()->getParam('referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $transition = filter_var(Yii::app()->getRequest()->getParam('transition'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $canal = filter_var(Yii::app()->getRequest()->getParam('canal'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $campaign = filter_var(Yii::app()->getRequest()->getParam('campaign'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $content = filter_var(Yii::app()->getRequest()->getParam('content'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $keywords = filter_var(Yii::app()->getRequest()->getParam('keywords'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $point = filter_var(Yii::app()->getRequest()->getParam('point'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_referer = filter_var(Yii::app()->getRequest()->getParam('last_referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $roistat = (isset($_COOKIE['roistat_visit'])) ? $_COOKIE['roistat_visit'] : "(none)";

        $res = Yii::app()->db->createCommand()
            ->insert('service_order', array(
                'id_se' => $id,
                'fio' => $fio,
                'tel' => $tel,
                'email' => $email,
                'crdate' => date("Y-m-d H:i:s"),
            ));

        Mailing::set(
          19,
          [
            'service_id' => $id,
            'name_user' => $fio,
            'service_theme' => $tel,
            'service_email' => $email,
            'service_traffic' => $referer,
            'service_transition' => $transition,
            'service_canal' => $canal,
            'service_campaign' => $campaign,
            'service_content' => $content,
            'service_keywords' => $keywords,
            'service_point' => $point,
            'service_referer' => $last_referer,
            'service_roistat' => $roistat
          ]
        );

        return array('res' => $res);
    }



    public function orderPrommu()
    {
//        $data['name'] = filter_var(Yii::app()->getRequest()->getParam('company'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['post'] = filter_var(Yii::app()->getRequest()->getParam('post'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//        $data['tabn'] = filter_var(Yii::app()->getRequest()->getParam('num'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['fff'] = filter_var(Yii::app()->getRequest()->getParam('fff'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['iii'] = filter_var(Yii::app()->getRequest()->getParam('nnn'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['ooo'] = filter_var(Yii::app()->getRequest()->getParam('ooo'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $ser = explode("-",Yii::app()->getRequest()->getParam('doc-ser'));

        $data['docser'] = $ser[0];

        $data['docnum'] = $ser[1];
        $data['docdate'] = Yii::app()->getRequest()->getParam('birthday');
        $data['docorgname'] = filter_var(Yii::app()->getRequest()->getParam('doc-org'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['borndate'] =  Yii::app()->getRequest()->getParam('birthday');
    
        $data['bornplace'] = filter_var(Yii::app()->getRequest()->getParam('bornplace'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['regaddr'] = filter_var(Yii::app()->getRequest()->getParam('regaddr'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//        $data['regcountry'] = filter_var(Yii::app()->getRequest()->getParam('regcountry'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['liveaddr'] = filter_var(Yii::app()->getRequest()->getParam('addr'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//        $data['livecountry'] = filter_var(Yii::app()->getRequest()->getParam('country'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['tel'] = filter_var(Yii::app()->getRequest()->getParam('tel'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phoneCode = filter_var(Yii::app()->getRequest()->getParam('__phone_prefix'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['tel'] = $phoneCode . $data['tel'];
        $data['docorgcode'] = filter_var(Yii::app()->getRequest()->getParam('docorgcode'), FILTER_SANITIZE_NUMBER_INT);
        $data['comment'] = filter_var(Yii::app()->getRequest()->getParam('comment'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['crdate'] = date("Y-m-d H:i:s");

        // add img files
        $files = array();
        $path = "/images/services";
        $fidest = array();
        $storedFiles = $_SESSION['uploaduni'] ?: array();
        foreach ($storedFiles as $key => $val)
        {
            foreach ($val as $key2 => $val2)
            {
                $info = pathinfo($val2);

                $files[$key][$key2] = "{$path}/" . $info['basename'];

                $fidest[$key]['source'][] = $val2;
                $fidest[$key]['dest'][] = "{$path}/" . $info['basename'];
            } // end foreach
//            $files .= $key . ":" . join(":", $fidest[$key]['dest']) . "\n";

        }
//        $data['files'] = $files;
        $data['files'] = json_encode($files);

        $res = Yii::app()->db->createCommand()
            ->insert('card_request', $data);

        // копировать сканы
        $this->imgPath = "services";
        foreach ($fidest as $key => $val)
        {
            foreach ($val['dest'] as $key2 => $val2)
            {
                if( copy(MainConfig::$DOC_ROOT . $fidest[$key]['source'][$key2], MainConfig::$DOC_ROOT . $val2) )
                    unlink(MainConfig::$DOC_ROOT . $fidest[$key]['source'][$key2]);
            } // end foreach
        } // end foreach


        unset($_SESSION['uploaduni']);

        $message = 'Ваша заявка успешно принята в обработку. Ожидайте, наши менеджеры свяжутся с вами';
        Yii::app()->user->setFlash('prommu_flash', $message);
        return array('error' => 0);
    }



    private function getMenu()
    {
        $lang = Share::getLangSelected();
        $menu = new Menu;
        $res = $menu->getTreeDB(0, $lang, 2, 0);

        return $res;
    }
    /*
    *   добываем анкеты для услуг
    */
    public function prepareFilterData()
    {
        $vacId = Yii::app()->getRequest()->getParam('vacancy');
        $model = new Vacancy();
        $arData = $model->getFilterForVacancy($vacId);
        $_GET = $arData['filter']; // это надо чтоб установились в фильтр параметры вакансии и чтоб правильно работала навигация
		return $this->getFilteredPromos($arData['filter']);
    }
    /*
    *
    */
    public function getFilteredPromos($filter=array()){
		$arRes = array();
        $model = new SearchPromo();
        $arProps = ['filter' => $filter];
        $arAllId = $model->searchPromosCount($arProps);
        $arRes['app_count'] = sizeof($arAllId);
        $arRes['pages'] = new CPagination($arRes['app_count']);
        $arRes['pages']->pageSize = 21;
        $arRes['pages']->applyLimit($model);
        $arRes['workers'] = $model->getPromos($arAllId, false, $arProps);
        return $arRes;    	
    }
    /**
    *   тянем данные для карты
    */
    public function getUserDataForCard() {
        $arRes['months'] = array(0=>'Январь',1=>'Февраль',2=>'Март',3=>'Апрель',4=>'Май',5=>'Июнь',6=>'Июль',7=>'Август',8=>'Сентябрь',9=>'Октябрь',10=>'Ноябрь',11=>'Декабрь');
        $id = Share::$UserProfile->id;

        if($id>0) {
            $arRes['user'] = Yii::app()->db->createCommand()
                                ->select('a.val phone, u.email, u.status')
                                ->from('user u')
                                ->leftJoin(
                                    'user_attribs a', 
                                    'a.id_us=u.id_user AND a.id_attr=1'
                                )
                                ->where(
                                    'u.id_user=:idus', 
                                    array(':idus' => $id)
                                )
                                ->queryRow();

            if(isset($arRes['user']['phone'])){
                $arRes['user']['phone'] = str_replace('+','',$arRes['user']['phone']);
                $pos = strpos($arRes['user']['phone'], '(');
                $arRes['user']['phone-code'] = substr($arRes['user']['phone'],0,$pos);
                $arRes['user']['phone'] = substr($arRes['user']['phone'], $pos);
            }

            if($arRes['user']['status']==2){
              $res = Yii::app()->db->createCommand()
                ->select('r.firstname, r.lastname, r.birthday')
                ->from('resume r')
                ->where('r.id_user=:idus', array(':idus' => $id))
                ->queryRow();
            }
            if($arRes['user']['status']==3){
              $res = Yii::app()->db->createCommand()
                ->select('e.firstname, e.lastname')
                ->from('employer e')
                ->where('e.id_user=:idus', array(':idus' => $id))
                ->queryRow();
            }
            $arRes['user']['firstname'] = $res['firstname'];
            $arRes['user']['lastname'] = $res['lastname'];

            if(!empty($res['birthday']))
                $arRes['user']['birthday'] = DateTime::createFromFormat(
                                                    'Y-m-d', 
                                                    $res['birthday']
                                                )->format('d.m.Y');
        }
        return $arRes;
    }
}