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


        $message = sprintf("На сайте <a href='http://%s'>http://%1$01s</a> Заказ  услуг
                <br/>
                 <br/>
                Услуга: <b>%s</b>  
                <br/>
                <br/>
                Пользователь: <b>%s</b>  
                <br/>
                Тема: <b>%s</b>  
                <br/>
                Email: <b>%s</b>  
                <br/>
                <br/>
                 ----------------------------------------------------------
                <br/>
                Тип трафика: <b>%s</b>  
                <br/>
                Источник: <b>%s</b>  
                <br/>
                Канал: <b>%s</b>  
                <br/>
                Кампания: <b>%s</b>  
                <br/>
                Контент: <b>%s</b>  
                <br/>
                Ключевые слова: <b>%s</b>  
                <br/>
                Точка входа: <b>%s</b>  
                <br/>
                Реферер: <b>%s</b>
                <br/>
                Roistat: <b>%s</b>  ",
            Subdomain::getSiteName(),$id, $fio, $tel, $email, $referer, $transition, $canal, $campaign, $content, $keywords, $point, $last_referer, $roistat);

        $emails[0] = "denisgresk@gmail.com";
        $emails[1] = "man.market2@gmail.com";
        $emails[2] = "prommu.servis@gmail.com";
        $emails[3] = "e.market.easss@gmail.com"; 
        $emails[4] = "projekt.sergey@gmail.com";
        $emails[5] = "manag_reports@euro-asian.ru";
        $emails[6] = "e.marketing@euro-asian.ru";
        $emails[7] = "site.adm@euro-asian.ru";
        for($i = 0; $i <count($emails); $i++){
            Share::sendmail($emails[$i], "Prommu: заказ услуг", trim($message));
       
        }

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
        Yii::app()->user->setFlash('Message', array('type' => '-green', 'message' => $message));
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
    public function prepareFilterData(){
        $vacId = Yii::app()->getRequest()->getParam('vacancy');
        // достаем данные вакансии
        $arVacInfo = Yii::app()->db->createCommand()
            ->select('ev.id, ev.id_user, ev.isman, ev.iswoman, ev.agefrom, ev.ageto, ev.ismed, ev.isavto, ev.smart, ev.card, ev.cardPrommu')
            ->from('empl_vacations ev')
            ->where('ev.id=:id', array(':id' => $vacId))
            ->queryRow();
        // достаем города вакансии
        $arVacInfo['cities'] = Yii::app()->db->createCommand()
            ->select('ec.id_city, c.id_co, c.name')
            ->from('empl_city ec')
            ->leftJoin('city c', 'c.id_city=ec.id_city')
            ->where('ec.id_vac=:id', array(':id' => $vacId))
            ->queryAll();
        // достаем должности вакансии
        $arVacInfo['posts'] = Yii::app()->db->createCommand()
            ->select('uad.id, uad.name')
            ->from('empl_attribs ea')
            ->leftJoin('user_attr_dict uad', 'uad.key=ea.key')
            ->where('ea.id_vac=:id AND uad.id_par=110', array(':id' => $vacId))
            ->queryAll();

        foreach ($arVacInfo['cities'] as $c)
            $_POST['cities'][] = $c['id_city'];

        foreach ($arVacInfo['posts'] as $p)
            $_POST['posts'][] = $p['id'];

		$_POST['sm'] = $arVacInfo['isman'];
		$_POST['sf'] = $arVacInfo['iswoman'];
		$_POST['mb'] = $arVacInfo['ismed'];
		$_POST['avto'] = $arVacInfo['isavto'];
		$_POST['smart'] = $arVacInfo['smart'];
		$_POST['card'] = $arVacInfo['card'];
		$_POST['cardPrommu'] = $arVacInfo['cardPrommu'];
		$_POST['af'] = $arVacInfo['agefrom'];
		$_POST['at'] = $arVacInfo['ageto'];

		return $this->getFilteredPromos();
    }
    /*
    *
    */
    public function getFilteredPromos(){
		$arRes = array();
        $SearchPromo = new SearchPromo();
        // $ph = $filter['ph'];
        // $filter = ['filter' => compact('ph')];
        $arAllId = $SearchPromo->searchPromosCount();
        $arRes['app_count'] = sizeof($arAllId);
        $arRes['pages'] = new CPagination($arRes['app_count']);
        $arRes['pages']->pageSize = 51;
        $arRes['pages']->applyLimit($SearchPromo);
        $arRes['workers'] = $SearchPromo->getPromos($arAllId);
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