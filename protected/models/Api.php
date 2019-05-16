<?php
/**
 * Работа с API
 * Date: 26.04.19
 * Time: 02:22
 * Grescode
 */
class Api
{
    private static $HEADER_POST = 1;
    private static $HEADER_GET = 2;

    /** @var UserProfile */
    private $Profile;
    private $idus;
    private $token = null;  
    private $apiKey = 'AAAAOoZQN40:APA91bEgi7ebdOYMEwl60gzbgqFCOxv3gvmiq9hdpl4lE1SLOeCHHHlRah0U5qEHroYznP3MHnm3Ilj-n7ilsf8Rd9J-oEDZYE_3vsFIvqq9XgZrLfL64MWFaaFUVPZ5aIrtfNo3Mt07';
    public $apiUrl = 'https://fcm.googleapis.com/fcm/send';
    public $timeout = 5;
    public $sslVerifyHost = false;
    public $sslVerifyPeer = false;   

    public function apiProcess()
    {
        $apiMethod = filter_var(Yii::app()->getRequest()->getParam('api'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        try
        {
            switch( strtolower($apiMethod) )
            {
                /// VERSION 26.04.2019
                
                ///FIRST BLOCK
                case 'register' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->registerUsers(); break;
                case 'user_restorepass' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->restorePass(); break;
                case 'faq' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getFaq(); break;
                case 'auth' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->authUsers(); break;
                case 'feedback' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->feedback(); break;
                
                ///PROFILE BLOCK
                case 'user_get' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->getUserData(); break;
                case 'edit_prof' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->updateProf(); break;
                case 'attrib_get' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getAttrib(); break;
                case 'post_get' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getPost(); break;
                case 'city_get' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getCity(); break;
                case 'vacancy_search' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getVacancy(); break;
                case 'empl_search' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getEmplSearch(); break;
                case 'promo_search' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getPromoSearch(); break;
                
                
                case 'push' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getPush(); break;
                case 'export' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->export(); break;
                case 'vacancy_own' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getVacancyOwn(); break;
                case 'vacancy_get' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->getVacancyDataView(); break;
                case 'response_set' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->setResponse(); break;
                case 'response_data' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->dataResponse(); break;
                case 'chat_theme_get' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->getChatThemes(); break;
                case 'cotypes_get' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getCotypes(); break;
                case 'vacancy_data' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->getVacancyData(); break;
                case 'send_mess' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->setMess(); break;
                case 'set_vk' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->setCommAndRate(); break;
                case 'rere' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->rere(); break;
                case 'vacancy_act' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->vacAct(); break;
                case 'vacancy_pub' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->vacationPub(); break;
                case 'invite_set' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->setInvite(); break;
                case 'photo' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->photoEdit(); break;
                case 'data_help' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->getHelp(); break;
                case 'send_push' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->setPush(); break;
                case 'send_topic' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->setTopics(); break;
                case 'get_topic' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->getTopics(); break;
                case 'kew' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->kew(); break;
                case 'rest' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->rest(); break;
                case 'vac' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->vac(); break;
                case 'delete' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->delete(); break;
                case 'firebase' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->firebase(); break;
                case 'tele' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->teleProm(); break;
                case 'teles' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->teleProms(); break;
                case 'teles_test' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->telePromsTest(); break;
                case 'nicola' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getNicolaDay(); break;
                case 'mailer' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->mailBox(); break;
                case 'vacmon' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->vacancyMonitoring(); break;
                case 'apivk' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->apiVK(); break;
                case 'log' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->testLog(); break;
                case 'tect' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->teSt(); break;
                case 'ideas' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->ideas(); break;
                case 'export_auto' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->exportAutomize(); break;
                case 'geo_project' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->geoProject(); break;
                case 'serchuse' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->searchUse(); break;
                case 'rateuse' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->rateUse(); break;
                case 'maleor' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->maleor(); break;
                case 'testpay' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->testPay(); break;
                case 'services' : $this->checkMethodHeader(self::$HEADER_GET); $data = $this->services(); break;
                case 'import' : $this->checkMethodHeader(self::$HEADER_POST); $data = $this->testInfo(); break;
                case 'rest_one_day': $this->checkMethodHeader(self::$HEADER_GET); $data = $this->getRestOneDay(); break;
                
                
                 
                

                default: throw new ExceptionApi('No such method', 1001);

            }

        }
        catch (Exception $e) {
            $code = abs($e->getCode());

            switch( $e->getCode() )
            {
                case -1001 : $message = 'No such API method'; break;
                case -1003 : $message = 'Wrong header'; break;
                default: $code= 1002; $message = $e->getMessage();
            }
            
            
            $data = ['error' => $code, 'message' => $message];
            
        } // endtry
        
       
        $status = $this->error_refuse($data);
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        return $data;
    }
    
    public function getAttrib(){
        $sql = "SELECT
                d.id
              , d.name
              , d.type
              , d.id_par idpar
              , d.key
            FROM user_attr_dict d";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

    
        for($i = 0; $i < count($res); $i ++){
            if($res[$i]['idpar'] !=0 ){
                $id_par = $res[$i]['idpar'];
                $sql = "SELECT
                    d.id
                  , d.name
                  , d.type
                  , d.id_par idpar
                  , d.key
                 FROM user_attr_dict d
                 WHERE id={$id_par}";
                $parent = Yii::app()->db->createCommand($sql)->queryRow();
                
                $sql = "SELECT
                    d.id
                  , d.name
                  , d.type
                  , d.id_par idpar
                  , d.key
                 FROM user_attr_dict d
                 WHERE id_par={$id_par}";
                $child = Yii::app()->db->createCommand($sql)->queryAll();
                
                $attr[$parent['key']] = [];
                // array_push($attr[$parent['key']], $res[$i]);
                $attr[$parent['key']] = $child;
            } else {
                $attr[$res[$i]['key']] = $res[$i];
            }
        }
        
        $days = array("1" => "Понедельник","2" => "Вторник","3" => "Среда","4" => "Четверг","5" => "Пятница","6" => "Суббота","7" => "Воскресенье");
        $attr['workDays']  = [];
        for($i = 1; $i < count($days); $i ++){
            $day['id'] = $i;
            $day['name'] = $days[$i];
            $attr['workDays'][] = $day;
            
        }
        // foreach ($res as $key => $val)
        // {
            
        //     $attr[$val['key']] = $val;
        // } // end foreach
        
        $data['userAttribs'] = $attr;
        
        
        return $data;
    }
    
    public function getFaq(){
        $type = Yii::app()->getRequest()->getParam('type');
        if($type == 2) $type = 1;
        if($type == 3) $type = 2;
       
        $faq = new Faq();
        $res = $faq->getFaqAll($type);
        
        for($i = 0; $i < count($res); $i++){
            if($res[$i]["type"] == 2) $res[$i]["type"] = 3;
            if($res[$i]["type"] == 1) $res[$i]["type"] = 2;
        }
        
        return $res;
    }
    
    
    public function testInfo(){
        
        $email = Yii::app()->getRequest()->getParam('email');
        $sql = "SELECT id_user
            FROM user
            WHERE login LIKE '%{$email}%' 
            ";
        $res = Yii::app()->db->createCommand($sql)->queryRow();
        
        return $res;
    }
    
    public function services(){
        $pricess = new PrommuOrder();
        $prices = $pricess->getPricesData();

         return $prices;
    }
    
    public function registerUsers(){
        
       $auth = new Auth();
       $inData['inputData'] = $_POST;
       $inData = $_POST;
       $inData['type'] = $_POST['type'];
    
       return $auth->registerUser($inData);
       
    }
    
    public function error_refuse($data){
        if(!$data['error']){
             $status = 200;
        } else {
             $status = 500;
        }
        
        return $status;
    }
    
    public function rateUse(){
          
   $id = Yii::app()->getRequest()->getParam('idus');
      
      $sql = "SELECT r.id, r.id_user idus,r.web, name , r.logo, r.rate, r.rate_neg
                , cast(r.rate AS SIGNED) - ABS(cast(r.rate_neg as signed)) avg_rate,
                 (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 0 AND mm.isneg = 0 AND mm.isactive = 1 AND mm.id_empl = r.id) commpos,
                   (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 0 AND mm.isneg = 1 AND mm.isactive = 1 AND mm.id_empl = r.id) commneg
                , (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 0 AND mm.id_promo = r.id) comment_count
                   ,(SELECT COUNT(*) cou FROM empl_vacations v WHERE v.id_user = r.id_user AND v.status = 1 AND v.ismoder = 100) vaccount
            FROM employer r
            WHERE r.id_user = {$id}
            ORDER BY avg_rate DESC
            LIMIT 6";
        $result = Yii::app()->db->createCommand($sql)
        ->queryAll();

        $rate = $result[0]['rate'] + $result[0]['rate_neg'];
        $rating = $result[0]['commpos'] + $result[0]['commneg'];
        $rates = ($rate/$rating) * 10;

        if($result[0]['vaccount']){
            $vacancy = 10;
        }

        if($result[0]['commpos'] - $result[0]['commneg'] > 0){
            $comment = 25;
        } 

        if($result[0]['logo']){
            $logo = 2;
        }
        if($result[0]['web']){
            $web = 2;
        }
        $result = $web + $logo + $comment + $rates + $vacancy + 2 + 2;
        
        $model = new Termostat();
        $arRes['dates'] = $model->getDates();
        $arRes['services'] = $model->getTermostatServices($id, $arRes['dates']);
        $arRes['schedule'] = $model->getTermostatEmplCount($id, $arRes['dates']);
        $arRes['viewsUser'] = $model->getTermostatEmplCount($id, $arRes['dates']);

        var_dump($arRes);
        echo "Прежний рейтинг работодателя: $result (система рейтинга Prommu Rate )<br/> ";
        // echo "Прежний рейтинг работодателя: $result + $proc ( services - $proc1, service - $proc2, proc3 - $proc3, proc4 - $proc4 ) (система рейтинга Prommu Rate + Termostat )<br/> ";
        
    }
    
    public function searchUse(){
        $date = '2018-08-01';
        $bdate = '2018-08-24';
        $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('user usr')
            ->where('usr.crdate  BETWEEN :date AND :bdate', array( ':date'=> $date, 'bdate'=> $bdate,))
            ->queryAll();
        for($i = 0; $i < count($data); $i ++){
            if($data[$i]['status'] == 2){
                 $user = Yii::app()->db->createCommand()
                ->select("*")
                ->from('resume a')
                ->where('a.id_user =:id_us', array( ':id_us'=> $data[$i]['id_user']))
                ->queryRow();  
            } else {
                 $user = Yii::app()->db->createCommand()
                ->select("*")
                ->from('employer a')
                ->where('a.id_user =:id_us', array( ':id_us'=> $data[$i]['id_user']))
                ->queryRow();
            }
            
            if($user['id_user'] == $data[$i]['id_user']){
                 $datas = Yii::app()->db->createCommand()
                ->select("*")
                ->from('analytic a')
                ->where('a.id_us =:id_us', array( ':id_us'=> $data[$i]['id_user']))
                ->queryRow();
                
                if($datas['id_us'] != $data[$i]['id_user']){
                echo $data[$i]['id_user'].'<br/>';
            }
            }
            
        }
        
      
        
    }
    
    public function excelgets(){
        Yii::import('ext.yexcel.Yexcel');
        $sheet_array = Yii::app()->yexcel->readActiveSheet('/var/www/dev.prommu/uploads/prommu_example.xls');
        //Заголовки
        $city = "Город";
        $location = "Локация";
        $street = "Улица";
        $home = "Дом";
        $build = "Здание";
        $str = "Строение";
        $date = "Дата работы";
        $time = "Время работы";

        $location = [];

        var_dump($sheet_array);

        for($i = 1; $i < count($sheet_array)+1; $i++){
           

                $location[] = [
                    'name' =>  $sheet_array[$i]['B'],
                    'adres' =>  $sheet_array[$i]['C'].' '.$sheet_array[$i]['D'].' '.$sheet_array[$i]['E'].' '.$sheet_array[$i]['F'],
                    'id_city' =>  $sheet_array[$i]['A'],
                    'bdate' =>  explode("-", $sheet_array[$i]['G'])[0],
                    'edate' =>  explode("-", $sheet_array[$i]['G'])[1],
                    'btime' => explode("-", $sheet_array[$i]['H'])[0],
                    'etime' =>  explode("-", $sheet_array[$i]['H'])[1],
                ];
        }


        return $location;

    }
    

    public function ideas(){
         $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('analytic')
            ->order("id_us desc")
            ->offset(0)
            ->limit(1000)
            ->queryAll();
            for($i = 0; $i < count($data); $i ++){
                 if(strpos($data[$i]['keywords'], "спб") !== false  || strpos($data[$i]['keywords'], "Петербург") !== false){
                     $res = Yii::app()->db->createCommand()
                                    ->update('analytic', array(
                                           'subdomen' => 1,
                                    ), 'id_us=:id_us', array(':id_us' => $data[$i]['id_us']));
                 }

                 if(strpos($data[$i]['point'], "spb") !== false ){
                     $res = Yii::app()->db->createCommand()
                                    ->update('analytic', array(
                                           'subdomen' => 1,
                                    ), 'id_us=:id_us', array(':id_us' => $data[$i]['id_us']));
                 }

            }


    }

 public function teSt(){
      
            // $arr['analyt'] = $rest;
            // $arr['resume'] = $rests;

            // return $arr;
        $lines = file_get_contents('https://dev.prommu.com/protected/models/11.txt');
        echo $lines;
        $liness = explode("зарегистрирован новый пользователь", $lines);
        $count = count($liness);
        $linesss = $liness;
        for($i = 0; $i < $count; $i ++) {
           $user = explode("Пользователь:",  $linesss[$i]);
           echo $users = explode(",",  $user[1])[0];
           $name = explode("Имя:",  $linesss[$i]);
           echo $names = explode(",",  $name[1])[0];
           $ustype = explode("Тип:",  $linesss[$i]);
           echo $ustypes = explode(",",  $ustype[1])[0];
           if(strpos($ustype, "Соискатель") !== false) { $ustypes = 2;
            } else $ustypes = 3;
           $type =  explode("Тип трафика:",  $linesss[$i]);
           $source = explode("Источник:",  $type[1]);
           echo $sources = $source[0];
           $canal = explode("Канал:",  $source[1]);
           echo $referers = $canal[0];
           $campaign = explode("Кампания:",  $canal[1]);
           echo $canals = $campaign[0];
           $content = explode("Контент:",  $campaign[1]);
           echo $campaigns = $content[0];
           $keywords = explode("Ключевые слова:",  $content[1]);
           echo  $contents = $keywords[0];
           $point = explode("Точка входа:",  $keywords[1]);
           echo $keywordss = $point[0];
           $last_referer = explode("Реферер:",  $point[1]);
           echo $points = $last_referer[0];
           $last_referers = explode("Реферер:",  $last_referer[1]);
           $last_refererss = explode("С наилучшими", $last_referers[0]);
           echo $last_referersss = $last_refererss[0];

           if($users != 0){
            $sql = "SELECT r.id_us
            FROM analytic r
            WHERE r.id_us = {$users}";
            $red = Yii::app()->db->createCommand($sql);
            $log = $red->queryRow();
            echo $log['id_us'];
            if($log['id_us'] == $users) {
                    echo "heee";
            } else {
            
                     $analytData = array('id_us' => $users,
                        'name' => $names,
                        'date' =>  date('Y-m-d H:i:s'),
                        'type' => $ustypes,
                        'referer' => $sources,
                        'canal' => $canals,
                        'campaign' => $campaigns,
                        'content' => $contents, 
                        'keywords' => $keywordss,
                        'transition' => $referers,
                        'point' => $points, 
                        'last_referer' => $last_referersss,
                        'active' => 1,
                    );

                $res = Yii::app()->db->createCommand()
                        ->insert('analytic', $analytData);


            }
          }

            

        }
        
   
    }




    public function apiVK(){
         $code = Yii::app()->getRequest()->getParam('code');
         $promo = Yii::app()->getRequest()->getParam('promo');
         $email = Yii::app()->getRequest()->getParam('email');
         $userid =  Yii::app()->getRequest()->getParam('userid');
    

        $usData = Yii::app()->db->createCommand()
                ->select("u.userid, u.pass, u.email")
                ->from('user_api u')
                ->where('u.userid = :user_id', array(':user_id' =>$userid))
                ->queryRow();

        if($usData['userid'] == $userid) {
            $rest['email'] = $usData['email'];
            $rest['password'] = $usData['pass'];
            return $rest;
        }else{

        $ch = curl_init("https://api.vk.com/method/users.get.json?user_ids=$userid&fields=nickname,sex,bdate,city,country,timezone,photo,photo_medium,photo_big,photo_rec,email&access_token=$code&v=V"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POST, 0); 
        $responses = curl_exec($ch); 
        $responses = json_decode($responses, true);

         $rest['password'] = rand(1111,9999)."prommu".rand(1111,9999);
        $salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22) . '$';
        $token = crypt($rest['password'], $salt);

         $pid = Yii::app()->db->createCommand("SELECT u.id_user  FROM  user u WHERE u.id_user = (SELECT MAX(u.id_user)  FROM user u)")->queryScalar();
         $pid+1;
         if($email == "") $email = $rest['password']."@prommu.com";

              
                

        if($promo == 1) {

             $res = Yii::app()->db->createCommand()
                ->insert('user_api', array(
                    'password' => $token,
                    'id' => $pid,
                    'firstName' => $responses['response'][0]['first_name'],
                    'date' => date('Y-m-d H:i:s'),
                    'lastName' => $responses['response'][0]['last_name'],
                    'email' =>  $email,
                    'userid' => $userid,
                    'pass' => $rest['password'],
                ));

             $res = Yii::app()->db->createCommand()
                ->insert('_user_role', array(
                    'user_id' => $pid,
                    'role_id' => 1,
                    ));

                 $pids = Yii::app()->db->createCommand("SELECT u.id  FROM  resume u WHERE u.id = (SELECT MAX(u.id)  FROM resume u)")->queryScalar();
                     $pids+1;

                $bdate = explode(".", $responses['response'][0]['bdate']);
                 $res = Yii::app()->db->createCommand()
                    ->insert('promo', array(
                        'id' => $pids,
                        'sex' => "MALE",
                        'birthday' => $bdate[2].".".$bdate[1].".".$bdate[0],
                        'car_exists' => false,
                        'med_cert_exists' => false, 
                        'about' => ' ',
                        'user_id' => $pid,
                        'pay' => ' '
                    ));


                    $res = Yii::app()->db->createCommand()
                    ->insert('promo_target_vacancy', array(
                        'promo_id' => $pids,
                        'rate' => 0,
                        'city_id' => 1307,
                        'post_id' => 111, ));
        } elseif($promo == 0) {

            $res = Yii::app()->db->createCommand()
                ->insert('user_api', array(
                    'password' => $token,
                    'id' => $pid,
                    'firstName' => $responses['response'][0]['first_name'],
                    'date' => date('Y-m-d H:i:s'),
                    'lastName' => $responses['response'][0]['last_name'],
                    'email' =>  $email,
                    'userid' => $userid,
                    'pass' => $rest['password'],
                ));

             $res = Yii::app()->db->createCommand()
                ->insert('_user_role', array(
                    'user_id' => $pid,
                    'role_id' => 2,
                    ));

                $pids = Yii::app()->db->createCommand("SELECT u.id  FROM  employer u WHERE u.id = (SELECT MAX(u.id)  FROM employer u)")->queryScalar();
                     $pids+1;

                 $res = Yii::app()->db->createCommand()
                    ->insert('employer_company', array(
                        'id' => $pids,
                        'name' => $responses['response'][0]['last_name'],
                        'webSite' => 'prommu.com',
                        'city_id' => 1307,
                        'company_type_id' => 102,
                    ));


                    $res = Yii::app()->db->createCommand()
                    ->insert('employer_api', array(
                        'id' => $pids,
                        'vacancy' => 0,
                        'company_id' => $pids,
                        'post_id' => 112,
                        'user_id' => $pid,
                    ));
        }
       

            $rest['email'] = $email;
            return $rest;
        }
    }

    public function testLog(){
        $section = file_get_contents('https://prommu.com/protected/runtime/application.log');
        $section = explode("---", $section);
        echo $section[160];
       $sql = "SELECT r.text
            FROM log r
            WHERE r.id = 1";
        $red = Yii::app()->db->createCommand($sql);
        $log = $red->queryScalar();
        echo count($section)-2;
         Yii::app()->db->createCommand()
                ->update('log', array(
                    'text' => count($section)-2,
                ), 'id = :id', array(':id' => 1));
                $j = 0;
        for($i = $log; $i < count($section)-1; $i ++) {
            //@prommubag
            if(strpos($section[$i], "404") === false || strpos($section[$i], "CDbCommandBuilder") === false){
            
                $items[$j] = $section[$i];
                $module = explode("/var", $items[$j]);
                $dat = explode("[", $items[$j]);
                $text = "Что-то сломалось, ".$dat[0]." ".$module[1]." детальное описание ошибки - https://prommu.com/admin/site/monitoring";
             // $sendto ="https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?chat_id=@prommubag&text=$text";
             //           file_get_contents($sendto);
                       $j++;
            } else unset($section[$i]);  
            
        }
     
       
    }


    public function vacancyMonitoring(){
        $date = new DateTime('-50 days');
        $dateStart = $date->format('Y-m-d');
        $dateEnd =  date('Y-m-d');
        $date = new DateTime('+1 day');
        $dateTomor = $date->format('Y-m-d');
       $sql = "SELECT e.id,  e.title,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate, et.bdate, et.edate, u.email, em.name
            FROM empl_vacations e 
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            LEFT JOIN empl_locations el ON el.id_vac = e.id
            LEFT JOIN empl_city et ON et.id_vac = e.id
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user 
            JOIN user u ON em.id_user = u.id_user AND u.ismoder = 1
            WHERE  DATE(et.bdate) BETWEEN '{$dateStart}' AND '{$dateTomor}'
            GROUP BY  e.id DESC";
            $rest = Yii::app()->db->createCommand($sql);
            $rest = $rest->queryAll();;

        for($i = 0; $i < count($rest); $i++){
            if(explode(" ", $rest[$i]['bdate'])[0] ==  $dateTomor ||explode(" ", $rest[$i]['bdate'])[0] == $dateStart ){
            $idvac = $rest[$i]['id'];
            $sql = "SELECT ru.email, r.firstname, r.lastname
            FROM vacation_stat s
            INNER JOIN empl_vacations e ON e.id = s.id_vac
            INNER JOIN resume r ON s.id_promo = r.id
            INNER JOIN user ru ON ru.id_user = r.id_user
            WHERE s.status IN(5,6) AND e.id = {$idvac}";
            $res = Yii::app()->db->createCommand($sql);
            $ress = $res->queryAll();
            for($j = 0; $j < count($ress); $j ++) {
                 $content = file_get_contents(Yii::app()->basePath . "/views/mails/app/start-tomorrow.html");
                 $content = str_replace('#APPNAME#', $ress[$j]['firstname'].' '.$ress[$j]['lastname'], $content);
                 $content = str_replace('#VACID#', $rest[$i]['id'], $content);
                 $content = str_replace('#VACNAME#', $rest[$i]['title'], $content);
                 $content = str_replace('#VACDATEBEG#',$rest[$i]['bdate'], $content);
                 $content = str_replace('#VACTIMEBEG#',"08:00", $content);
                 $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $rest[$i]['id'], $content);
               
               Share::sendmail($ress[$i]['email'], "Prommu.com Старт проекта завтра", $content);
            }

                $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/start-tomorrow.html");
                 $content = str_replace('#EMPNAME#', $rest[$i]['name'], $content);
                 $content = str_replace('#VACID#', $rest[$i]['id'], $content);
                 $content = str_replace('#VACNAME#', $rest[$i]['title'], $content);
                 $content = str_replace('#VACDATEBEG#',$rest[$i]['bdate'], $content);
                 $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $rest[$i]['id'], $content);
               
               Share::sendmail($rest[$i]['email'], "Prommu.com Старт проекта завтра", $content);
            
            }
             if(explode(" ", $rest[$i]['bdate'])[0] ==  $dateStart){
                 $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/start-today.html");
                 $content = str_replace('#EMPNAME#', $rest[$i]['name'], $content);
                 $content = str_replace('#VACID#', $rest[$i]['id'], $content);
                 $content = str_replace('#VACNAME#', $rest[$i]['title'], $content);
                 $content = str_replace('#VACDATEBEG#',$rest[$i]['bdate'], $content);
                 $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $rest[$i]['id'], $content);
               
               Share::sendmail($rest[$i]['email'], "Prommu.com Старт проекта сегодня", $content);
                
            }
             if(explode(" ", $rest[$i]['edate'])[0] ==  $dateStart){
                 $content = file_get_contents(Yii::app()->basePath . "/views/mails/emp/vac-completed.html");
                 $content = str_replace('#EMPNAME#', $rest[$i]['name'], $content);
                 $content = str_replace('#VACID#', $rest[$i]['id'], $content);
                 $content = str_replace('#VACNAME#', $rest[$i]['title'], $content);
                 $content = str_replace('##VACTIMEEND##',$rest[$i]['edate'], $content);
                 $content = str_replace('#VACLINK#',  Subdomain::site() . MainConfig::$PAGE_VACANCY . DS . $rest[$i]['id'], $content);
               
               Share::sendmail($rest[$i]['email'], "Prommu.com Завершение проекта сегодня", $content);
                
            }
       
        }
        

        return $rest;

    }

    public function mailBox()
    {
        Im::sendEmailNotifications();
        $termostat = new Termostat;
        $termostat->sendEmailNotifications();

        return Mailing::send();
    }
    
    private function requestStatus($code) {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[500];
    }
    
    public function authUsers()
    {
        $Auth = new Auth();
        $login = Yii::app()->getRequest()->getParam('login');
        $code = Yii::app()->getRequest()->getParam('code');
        $res = $Auth->doAPIAuth();
        
        if(empty($res['error']) && !empty($code) && $res['status'] == 2){
            $activate = Yii::app()->db->createCommand()
            ->select("r.code")
            ->from('activate r')
            ->where('r.phone = :login AND r.code = :code', array(':login' => $login, ':code' => $code))
            ->queryRow();
            
            
            if($activate['code'] == $code){
                Yii::app()->db->createCommand()
                ->update('user', array(
                    'isblocked' => 0,
                ), 'id_user=:id_user', array(':id_user' => $res['id']));

                return $res;
            } 
            else
            {
                $res = [];
                $res['error'] = '102';
                $res['message'] = 'Некорректный код подтверждения';
                return $res;
            }
        } 
        elseif(empty($res['error']) && empty($code) && $res['status'] == 2)
        {
            $code = rand(1111,9999);
            $rest = Yii::app()->db->createCommand()
                                ->insert('activate', array('id' => $code,
                                    'id' => $code,
                                    'code' => $code,
                                    'phone' => $login,
                                    'email' => $login,
                                    'date' => date("Y-m-d h-i-s"),
                                    ));
                                    
            if(strpos($login, '@') === false){
                        
                $res['code'] = $this->teleProms($login, $code);
                        
            } else {
                $message = '<p style="font-size:16px">Ваш код для потдверждения регистрации <br/><p style="text-align:center">'.$code.'</p></p>';
                Share::sendmail($login, "Prommu.com. Код подтверждения регистрации", $message);
            }
            
            $res = [];
            $res['code'] = 1;
            $res['message'] = 'Отправлен код подтверждения';
            return $res;
             
        } else {
            
            return $res;
        }
       
       
    }

    public function feedback(){
        $autotype = 0;
        $app = Yii::app()->getRequest()->getParam('app');
        $id = Yii::app()->getRequest()->getParam('id');
        $type = filter_var(Yii::app()->getRequest()->getParam('type'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $theme = "Вопрос в приложении: ";
        $name = filter_var(Yii::app()->getRequest()->getParam('name'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $them = filter_var(Yii::app()->getRequest()->getParam('theme'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $emails = filter_var(Yii::app()->getRequest()->getParam('email'), FILTER_SANITIZE_EMAIL);
        $text = filter_var(Yii::app()->getRequest()->getParam('text'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $theme.=$them;

        $Feedback = new Feedback();
        $res = $Feedback->SaveData();
        
        return $res;
    }

    public function rere()
    {
    
        $auth = new Auth();
        $auth->Authorize(['id' => Yii::app()->session['au_us_data']->id]);

    } 

    public function rest(){
       

        for($i = 15000; $i < 1000; $i --){
            $res = Yii::app()->db->createCommand()
            ->select("r.status, r.email, r.id_user")
            ->from('user r')
            ->where('r.id_user = :uid AND r.ismoder = 0 AND r.isblocked = 0', array(':uid' => $i))
            ->queryRow();

            $result = Yii::app()->db->createCommand()
            ->select("id")
            ->from('user_api')
            ->where('id = :id', array(':id' => $res['id_user']))
            ->queryRow();

        
            $status = $res['status'];

            echo $result['id'].'<br/>';


            if($result['id']){
                
            
           

                if($status == 2){
                $password = '12345';
                $salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22) . '$';
                

                 $resume = Yii::app()->db->createCommand()
            ->select("u.isman, u.ismed, u.ishasavto,u.id, u.birthday, u.aboutme, u.firstname, u.lastname ")
            ->from('resume u')
            ->leftJoin('user r', 'r.id_user = u.id_user')
            ->where('u.id_user = :uid AND r.ismoder = 0 ', array(':uid' => $i))
            ->queryRow();

            if($resume){

            if($resume['isman'] = 1) {
                $male = 'MALE';
            }
            else $male = 'FEMALE';


            $token = crypt('12345', $salt);

            $res = Yii::app()->db->createCommand()
                ->insert('user_api', array(
                    'password' => $token,
                    'id' => $i,
                    'firstName' => $resume['firstname'],
                    'messages' => 0,
                    'negative' => 0,
                    'positive' => 0,
                    'invites'  => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'lastName' => $resume['lastname'],
                    'email' => $res['email'],
                    'phone' => 2304,
                ));
           
            $res = Yii::app()->db->createCommand()
                ->insert('_user_role', array(
                    'user_id' => $i,
                    'role_id' => 1,
                    ));

             $res = Yii::app()->db->createCommand()
                    ->insert('promo', array(
                        'id' => $resume['id'],
                        'sex' => $male,
                        'birthday' => $resume['birthday'],
                        'car_exists' => $resume['ishasavto'],
                        'med_cert_exists' => $resume['ismed'], 
                        'about' => $resume['aboutme'],
                        'user_id' => $i,
                        'pay' => ' '
                    ));

                    $res = Yii::app()->db->createCommand()
                    ->insert('promo_target_vacancy', array(
                        'promo_id' => $resume['id'] ,
                        'rate' => 0,
                        'city_id' => 1307,
                        'post_id' => 111, ));
                }
            }
                elseif($status== 3){

                    $salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22) . '$';
                

                 $employer = Yii::app()->db->createCommand()
            ->select("u.name, u.firstname, u.lastname, u.id ")
            ->from('employer u')
            ->leftJoin('user r', 'r.id_user = u.id_user')
            ->where('u.id_user = :uid AND r.ismoder = 0', array(':uid' => $i))
            ->queryRow();

            if($employer){
                    $token = crypt('12345', $salt);

            $res = Yii::app()->db->createCommand()
                ->insert('user_api', array(
                    'password' => $token,
                    'id' => $i,
                    'firstName' => $employer['firstname'],
                    'messages' => 0,
                    'negative' => 0,
                    'positive' => 0,
                    'invites'  => 0,
                    'date' => date('Y-m-d H:i:s'),
                    'lastName' => $employer['lastname'],
                    'email' => $res['email'],
                    'phone' => 2304,
                ));
           
            $res = Yii::app()->db->createCommand()
                ->insert('_user_role', array(
                    'user_id' => $i,
                    'role_id' => 2,
                    ));

                    $res = Yii::app()->db->createCommand()
                    ->insert('employer_company', array(
                        'id' => $employer['id'],
                        'name' => $employer['name'],
                        'webSite' => 'prommu.com',
                        'city_id' => 1307,
                        'company_type_id' => 102,
                    ));

                    $res = Yii::app()->db->createCommand()
                    ->insert('employer_api', array(
                        'id' => $employer['id'],
                        'vacancy' => 0,
                        'company_id' => $employer['id'],
                        'post_id' => 112,
                        'user_id' => $i,
                    ));
                }
                }
            }
    
        }
        

    }

    public function kew(){

              $sql = "SELECT e.id_mech, c.name
                FROM user_mech e
                JOIN user usr ON usr.id_user=e.id_us
                JOIN user_attr_dict c ON c.key=e.id_mech
                WHERE  e.isshow=0 AND usr.ismoder = 1 AND usr.isblocked = 0 AND usr.status = 2
                GROUP BY e.id_mech
                ORDER BY e.id_mech";
        $dat = Yii::app()->db->createCommand($sql)->queryAll();

        $sql = "SELECT e.id_city, c.name
                FROM user_city e
                JOIN user usr ON usr.id_user=e.id_user
                JOIN city c ON c.id_city=e.id_city
                WHERE e.id_resume != 0 AND usr.ismoder = 1 AND usr.isblocked = 0 AND usr.status = 2
                GROUP BY e.id_city
                ORDER BY c.name";
        $datass = Yii::app()->db->createCommand($sql)->queryAll();

                $count = count($datass);
                for($i = 0; $i < $count; $i ++){
                    $city[$datass[$i]['id_city']] = $datass[$i]['name'];
                   
                }

                $counts = count($dat);
                for($i = 0; $i < $counts; $i ++){
                    $post[$dat[$i]['id_mech']] = $dat[$i]['name'];
                   
                }

        $data['city'] = $city;
        $data['post'] = $post;
        return $data;


    }

    public function teleProm($id, $key){

        $user = Yii::app()->db->createCommand()
            ->select("e.firstname, e.lastname, e.isman")
            ->from('resume e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id))
            ->queryAll();

        $list = Yii::app()->db->createCommand()
            ->select('*')
            ->from('user_attribs')
            ->where('id_us=:id_user AND id_attr=:id_attr', array(':id_user'=>$id, ':id_attr'=>1))
            ->queryAll();


        $service = Yii::app()->db->createCommand()
            ->select("e.text")
            ->from('service_cloud e')
            ->where('e.key=:key', array(':key' => $key))
            ->queryAll();

        $text = $service[0]['text'];
        $telephone = $list[0]['val'];
        $firstname = $user[0]['firstname'];
        $lastname = $user[0]['lastname'];
        $text = "-PROMMU.COM- $text";


        $api_key = 'iu7nou5f4jhdh2b1ftvd9z57hup30758'; // Уникальный код вашей АТС 
        $api_salt = 's2m6mibgrjkybmph5bk40g180h1rfxqx'; // Ключ для создания подписи 
        $url = 'https://app.mango-office.ru/vpbx/commands/sms'; 
        $data = array( 
        "command_id" => "ID" . rand(10000000,99999999), // идентификатор команды 
        "from_extension" => "3010", // внутренний номер сотрудника 
        "text" => $text, // текст смс 
        "to_number" => $telephone, // кому отправить смс 
        "sms_sender" => "PRO" // ОБЯЗАТЕЛЬНЫЙ ПАРАМЕТР. имя отправителя. Если не заполнено - будет использоваться имя отправителя, выбранное в ЛК. 
        ); 
        $json = json_encode($data); 
        $sign = hash('sha256', $api_key . $json . $api_salt); 
        $postdata = array( 
        'vpbx_api_key' => $api_key, 
        'sign' => $sign, 
        'json' => $json 
        ); 
        $post = http_build_query($postdata); 
        $ch = curl_init($url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        $response = curl_exec($ch); 
        curl_close($ch); 
    

    }

    public function teleProms($telephone = 0, $text = ''){

        $telephone = $telephone?:Yii::app()->getRequest()->getParam('phone');
        $text = $text?:Yii::app()->getRequest()->getParam('code');
        $text = "-PROMMU.COM- $text";


        $api_key = 'iu7nou5f4jhdh2b1ftvd9z57hup30758'; // Уникальный код вашей АТС 
        $api_salt = 's2m6mibgrjkybmph5bk40g180h1rfxqx'; // Ключ для создания подписи 
        $url = 'https://app.mango-office.ru/vpbx/commands/sms'; 
        $data = array( 
        "command_id" => "ID" . rand(10000000,99999999), // идентификатор команды 
        "from_extension" => "3010", // внутренний номер сотрудника 
        "text" => $text, // текст смс 
        "to_number" => $telephone, // кому отправить смс 
        "sms_sender" => "PRO" // ОБЯЗАТЕЛЬНЫЙ ПАРАМЕТР. имя отправителя. Если не заполнено - будет использоваться имя отправителя, выбранное в ЛК. 
        ); 
        $json = json_encode($data); 
        $sign = hash('sha256', $api_key . $json . $api_salt); 
        $postdata = array( 
        'vpbx_api_key' => $api_key, 
        'sign' => $sign, 
        'json' => $json 
        ); 
        $post = http_build_query($postdata); 
        $ch = curl_init($url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        $response = curl_exec($ch); 
        curl_close($ch); 


        return $response;
    

    }

    public function telePromsTest(){

        $telephone = Yii::app()->getRequest()->getParam('phone');
        $text = Yii::app()->getRequest()->getParam('code');
        $text = "-PROMMU.COM- $text";


        $api_key = 'iu7nou5f4jhdh2b1ftvd9z57hup30758'; // Уникальный код вашей АТС 
        $api_salt = 's2m6mibgrjkybmph5bk40g180h1rfxqx'; // Ключ для создания подписи 
        $url = 'https://app.mango-office.ru/vpbx/commands/sms'; 
        $data = array( 
        "command_id" => "ID" . rand(10000000,99999999), // идентификатор команды 
        "from_extension" => "3010", // внутренний номер сотрудника 
        "text" => $text, // текст смс 
        "to_number" => $telephone, // кому отправить смс 
        "sms_sender" => "PRO" // ОБЯЗАТЕЛЬНЫЙ ПАРАМЕТР. имя отправителя. Если не заполнено - будет использоваться имя отправителя, выбранное в ЛК. 
        ); 
        $json = json_encode($data); 
        $sign = hash('sha256', $api_key . $json . $api_salt); 
        $postdata = array( 
        'vpbx_api_key' => $api_key, 
        'sign' => $sign, 
        'json' => $json 
        ); 
        $post = http_build_query($postdata); 
        $ch = curl_init($url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
        $response = curl_exec($ch); 
        curl_close($ch); 


        return $response;
    

    }

    public function export(){

         $date = filter_var(Yii::app()->getRequest()->getParam('date'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $bdate = filter_var(Yii::app()->getRequest()->getParam('bdate'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $domen = Yii::app()->getRequest()->getParam('domen');
        //print_r($ids);
         if($date == "week"){
            $my_time = time() - 604800; 
            $yester = date("Y-m-d", $my_time); 
              $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('analytic')
            ->where('active=:active AND date >:date AND subdomen=:domen', array(':active' => 1, ':date'=> $yester, ':domen'=>$domen))
            ->order("id_us desc")
            ->queryAll();
            
         }
         else {
             $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('analytic')
            ->where('active=:active AND (date BETWEEN :date AND :bdate) AND subdomen=:domen AND name!=:name', array(':active' => 1, ':date'=> $date, 'bdate'=> $bdate, 'domen'=>$domen, 'name'=>'NO ACTIVE'))
            ->order("id_us desc")
            ->group("id_us")
            ->queryAll();
         }
        

            $ac = "Пользователь";
            $type = "Тип";
            $referer = "Источник";
            $Canal = "Канал";
            $Campaign = "Кампания";
            $Content = "Контент";
            $Keywords = "Ключевые слова";
            $Point = "Поинт";
            $Last_referer = "Последний реферер";
            $Name = "Имя/Фамилия";
            $Date = "Дата";
            $Email = "Email";

        $csv_file = '<table border="1">
            <tr><td style="color:red; background:#E0E0E0">'.'ID'.
            '</td><td style="color:red; background:#E0E0E0">'.$Name.
            '</td><td style="color:red; background:#E0E0E0">'.$type.
            '</td><td style="color:red; background:#E0E0E0">'.$referer.
            '</td><td style="color:red; background:#E0E0E0">'.$Canal.
            '</td><td style="color:red; background:#E0E0E0">'.$Campaign.
            '</td><td style="color:red; background:#E0E0E0">'.$Content.
            '</td><td style="color:red; background:#E0E0E0">'.$Keywords.
            '</td><td style="color:red; background:#E0E0E0">'.$Email.
            '</td><td style="color:red; background:#E0E0E0">'.$Date.
            '</td><td style="color:red; background:#E0E0E0">'.$Point.


'</td></tr>';

        foreach ($data as $row) {


            $csv_file .= '<tr>';
            $b = "";
            $b_end = "";
            // if ($row["k"]==0) {
            //     $b = '<b>';
            //     $b_end = '</b>';
            // }
            if($row['type'] == 2){
                $types = "Соискатель";
                $id_user = $row['id_us'];
                $user = Yii::app()->db->createCommand()
            ->select("e.firstname, e.lastname, usr.email")
            ->from('resume e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id_user))
            ->queryAll();
            if($user[0]){
                $firstname = $user[0]['firstname'];
                $lastname = $user[0]['lastname'];
                $email = $user[0]['email'];
                $fio = "$firstname ".$lastname;
                $ana = 1;
            } else $ana = 0;

            
            }
            elseif($row['type'] == 3){
            
            $name = $row['name'];
            
            $id_user = $row['id_us'];
                $user = Yii::app()->db->createCommand()
            ->select("e.name, e.firstname, e.lastname, usr.email")
            ->from('employer e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id_user))
            ->queryAll();
             if($user[0]){
                $email = $user[0]['email'];
                $fio = $user[0]['name']." ".$user[0]['firstname']." ".$user[0]['lastname'];
                $types = "Работодатель";
                $ana = 1;
            } else $ana = 0;

            } 

            if($ana){
            $csv_file .= '<td>'.$b.$row["id_us"].$b_end.
                '</td><td>'.$b.$fio.$b_end.
                '</td><td>'.$b.$types.$b_end.
                '</td><td>'.$b.$row["transition"].$b_end.
                '</td><td>'.$b.$row["canal"].$b_end.
                '</td><td>'.$b.$row["campaign"].$b_end.
                '</td><td>'.$b.$row["content"].$b_end.
                '</td><td>'.$b.$row["keywords"].$b_end.
                '</td><td>'.$b.$email.$b_end.
                '</td><td>'.$b.$row["date"].$b_end.
                '</td><td>'.$b.$row["point"].$b_end.
                // '</td><td>'.$b.$row["last_referer"].$b_end.
                // '</td><td>'.$b.$row["date"].$b_end.
                '</td></tr>';
            }
        }

        $csv_file .='</table>';
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/analyt_de.xls'; // название файла
        $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт


        fwrite($file,trim($csv_file)); // записываем в файл строки
        fclose($file); // закрываем файл

       // задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл.
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        //header('Content-Type: text/csv');
        //header('Content-Disposition: attachment; filename=export.csv;');
        header('Content-Disposition: attachment; filename=analyt_prommu.xls');
        header('Content-transfer-encoding: binary');
        //header("content-type:application/csv;charset=ANSI");
        header('Content-Type: text/html; charset=windows-1251');
        header('Content-Type: application/x-unknown');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        //print "\xEF\xBB\xBF"; // UTF-8 BOM
        readfile($file_name); // считываем файл

    }

     public function exportAutomize(){

         $date = filter_var(Yii::app()->getRequest()->getParam('date'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $bdate = filter_var(Yii::app()->getRequest()->getParam('bdate'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $domen = Yii::app()->getRequest()->getParam('domen');
        //print_r($ids);
         if($date == "week"){
            $my_time = time() - 604800; 
            $yester = date("Y-m-d", $my_time); 
              $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('analytic a')
            ->join('user usr', 'usr.id_user=a.id_us')
            ->where('a.active=:active AND usr.crdate >:date', array(':active' => 1, ':date'=> $yester))
            ->order("a.id_us desc")
            ->queryAll();
            
         }
         else {
               $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('analytic a')
            ->join('user usr', 'usr.id_user=a.id_us')
            ->where('a.active=:active AND (usr.crdate BETWEEN :date AND :bdate)  AND a.name!=:name', array(':active' => 1, ':date'=> $date, 'bdate'=> $bdate, 'name'=>'NO ACTIVE'))
            ->order("a.id_us desc")
            ->group("a.id_us")
            ->queryAll();
         }
            


        $csv_file = '<table border="1">
            <tr><td style="color:red; background:#E0E0E0">Время'.
            '</td><td style="color:red; background:#E0E0E0">День'.
            '</td><td style="color:red; background:#E0E0E0">Месяц'.
            '</td><td style="color:red; background:#E0E0E0">Год'.
            '</td><td style="color:red; background:#E0E0E0">Домен'.
            '</td><td style="color:red; background:#E0E0E0">Идентификатор'.
            '</td><td style="color:red; background:#E0E0E0">Пользователь'.
            '</td><td style="color:red; background:#E0E0E0">Тип'.
            '</td><td style="color:red; background:#E0E0E0">Тип заявки'.
            '</td><td style="color:red; background:#E0E0E0">Телефон'.
            '</td><td style="color:red; background:#E0E0E0">Email'.
            '</td><td style="color:red; background:#E0E0E0">Источник'.
            '</td><td style="color:red; background:#E0E0E0">Канал'.
            '</td><td style="color:red; background:#E0E0E0">Кампания'.
            '</td><td style="color:red; background:#E0E0E0">Контент'.
            '</td><td style="color:red; background:#E0E0E0">Ключевое слово'.
            '</td><td style="color:red; background:#E0E0E0">Площадка'.
            '</td><td style="color:red; background:#E0E0E0">IP адрес'.
            '</td><td style="color:red; background:#E0E0E0">Client ID'.
            '</td><td style="color:red; background:#E0E0E0">Статус'.

'</td></tr>';
        
        foreach ($data as $row) {
        
            
            $csv_file .= '<tr>';
            $b = "";
            $b_end = "";

            $type_feed = 'регистрация';

            switch ($row['subdomen']) {
            case '0':
                 $domen = 'https://prommu.com';
                 break;

            case '1':
                 $domen = 'https://spb.prommu.com';
                 break;
             
             default:
                 # code...
                 break;
            }

            if(strpos('yandex,yandex.ru,away.vk.com,google,facebook', $row['canal']) !== false) {
                    $row["canal"] = $row['referer'];
                    $row["referer"] = $row['transition'];
                    $row['transition'] = $row['canal']; 
                                   
                }

                if(strpos($row["canal"], "cpc") !== false) {
                    $row["canal"] = explode(" ", $row["canal"])[0];
                                   
                }
              

                $attribs = Yii::app()->db->createCommand()
                        ->select("ua.val")
                        ->from('user_attribs ua')
                        ->where('ua.key=:key AND ua.id_us = :id_user', array(':key' => 'mob', ':id_user' => $row['id_us']))
                        ->queryRow();
                $phone = $attribs['val'];

            if($row['type'] == 2){
                $types = "Соискатель";
                $id_user = $row['id_us'];

                $user = Yii::app()->db->createCommand()
                ->select("e.firstname, e.lastname, usr.email, e.date_public ")
                ->from('resume e')
                ->join('user usr', 'usr.id_user=e.id_user')
                ->where('e.id_user=:id_user', array(':id_user' => $id_user))
                ->queryRow();
            
                
                    $firstname = $user['firstname'];
                    $lastname = $user['lastname'];
                    $email = $user['email'];
                    $fio = "$firstname ".$lastname;
                    $ana = 1;
                    $status = 'активен';

                    if(empty($email)){
                        $user = Yii::app()->db->createCommand()
                        ->select("ua.data, ua.dt_create, ua.status")
                        ->from('user_activate ua')
                        ->where('ua.id_user=:id_user', array(':id_user' => $id_user))
                        ->queryRow();
                        $status = 'не активен';

                        $data = json_decode($user['data'], true);
                        $firstname = $this->encoderSys($data['firstname']);
                        $lastname = $this->encoderSys($data['lastname']);
                        if($user['status'] == 0){
                            $lastname = $this->encoderSys($data['name']);
                        }
                        $fio = "$firstname ".$lastname; 
                        $email = $this->encoderSys($data['email']);
                        $user['date_public'] = $user['dt_create'];
                    }

                    $date1 = explode(" ",$user['date_public'])[0];
                    $time1 = explode(" ",$user['date_public'])[1];
                    $day = explode("-", $date1)[2];
                    $month = explode("-", $date1)[1];
                    $year = explode("-", $date1)[0];
                    $csv_file .= '<td>'.$b.$time1.$b_end.
                    '</td><td>'.$b.$day.$b_end.
                    '</td><td>'.$b.$month.$b_end.
                    '</td><td>'.$b.$year.$b_end.
                    '</td><td>'.$b.$domen.$b_end.
                    '</td><td>'.$b.$id_user.$b_end.
                    '</td><td>'.$b.$fio.$b_end.
                    '</td><td>'.$b.$types.$b_end.
                    '</td><td>'.$b.$type_feed.$b_end.
                    '</td><td>'.$b.$phone.$b_end.
                    '</td><td>'.$b.$email.$b_end.
                    '</td><td>'.$b.$row["transition"].$b_end.
                    '</td><td>'.$b.$row["canal"].$b_end.
                    '</td><td>'.$b.$row["campaign"].$b_end.
                    '</td><td>'.$b.$row["content"].$b_end.
                    '</td><td>'.$b.$row["keywords"].$b_end.
                    '</td><td>'.$b.$row["source"].$b_end.
                    '</td><td>'.$b.$row["ip"].$b_end.
                    '</td><td>'.$b.$row["client"].$b_end.
                    '</td><td>'.$b.$status.$b_end.
                    '</td></tr>';


            } elseif($row['type'] == 3) {
            
                $name = $row['name'];
                $id_user = $row['id_us'];

                $user = Yii::app()->db->createCommand()
                ->select("e.name, e.firstname, e.lastname, usr.email, e.crdate")
                ->from('employer e')
                ->join('user usr', 'usr.id_user=e.id_user')
                ->where('e.id_user=:id_user', array(':id_user' => $id_user))
                ->queryRow();


                    $email = $user['email'];
                    $fio = $user['name']." ".$user['firstname']." ".$user['lastname'];
                    $types = "Работодатель";
                    $ana = 1;
                    $status = 'активен';

                    if(empty($email)){
                        $user = Yii::app()->db->createCommand()
                        ->select("ua.data, ua.dt_create")
                        ->from('user_activate ua')
                        ->where('ua.id_user=:id_user', array(':id_user' => $id_user))
                        ->queryRow();


                        $status = 'не активен';
                        $data = json_decode($user['data'], true);
                        $firstname = $this->encoderSys($data['firstname']);
                        $lastname = $this->encoderSys($data['lastname']);
                        $name = $this->encoderSys($data['name']);
                        $fio = $name." ".$firstname." ".$lastname;
                        $email = $data['email'];
                        $user['crdate'] = $user['dt_create'];
                     
                    }
                    $date1 = explode(" ",$user["crdate"])[0];
                    $time1 = explode(" ",$user["crdate"])[1];
                    $day = explode("-", $date1)[2];
                    $month = explode("-", $date1)[1];
                    $year = explode("-", $date1)[0];
                    $csv_file .= '<td>'.$b.$time1.$b_end.
                    '</td><td>'.$b.$day.$b_end.
                    '</td><td>'.$b.$month.$b_end.
                    '</td><td>'.$b.$year.$b_end.
                    '</td><td>'.$b.$domen.$b_end.
                    '</td><td>'.$b.$id_user.$b_end.
                    '</td><td>'.$b.$fio.$b_end.
                    '</td><td>'.$b.$types.$b_end.
                    '</td><td>'.$b.$type_feed.$b_end.
                    '</td><td>'.$b.$phone.$b_end.
                    '</td><td>'.$b.$email.$b_end.
                    '</td><td>'.$b.$row["transition"].$b_end.
                    '</td><td>'.$b.$row["canal"].$b_end.
                    '</td><td>'.$b.$row["campaign"].$b_end.
                    '</td><td>'.$b.$row["content"].$b_end.
                    '</td><td>'.$b.$row["keywords"].$b_end.
                    '</td><td>'.$b.$row["source"].$b_end.
                    '</td><td>'.$b.$row["ip"].$b_end.
                    '</td><td>'.$b.$row["client"].$b_end.
                    '</td><td>'.$b.$status.$b_end.
                    '</td></tr>';
                
                // else {
                //     // $user = Yii::app()->db->createCommand()
                //     // ->select("ua.data")
                //     // ->from('user_activate ua')
                //     // ->where('ua.id_user=:id_user', array(':id_user' => $id_user))
                //     // ->queryRow();
                //     // $status = 'не активен';
                //     // $data = json_decode($user['data'], true);
                //     // $firstname = $this->encoderSys($data['firstname']);
                //     // $lastname = $this->encoderSys($data['lastname']);
                //     // $name = $this->encoderSys($data['name']);
                //     // $fio = $name." ".$firstname." ".$lastname;
                //     // $email = $data['email'];
                //     // $row["transition"] = $data['transition'];
                //     // $row["canal"] = $data['canal'];
                //     // $row["content"] = $data['content'];
                //     // $row["campaign"] = $data['campaign'];
                //     // $row["ip"] = $data['ip'];
                //     // $row["client"] = $data['client'];
                //     // $row["source"] = $data['source'];
                //     // $row["keywords"] = $data['keywords'];

                //     // $date1 = explode(" ",$row["date"])[0];
                //     // $time1 = explode(" ",$row["date"])[1];
                //     // $day = explode("-", $date1)[2];
                //     // $month = explode("-", $date1)[1];
                //     // $year = explode("-", $date1)[0];
                //     // $csv_file .= '<td>'.$b.$time1.$b_end.
                //     // '</td><td>'.$b.$day.$b_end.
                //     // '</td><td>'.$b.$month.$b_end.
                //     // '</td><td>'.$b.$year.$b_end.
                //     // '</td><td>'.$b.$domen.$b_end.
                //     // '</td><td>'.$b.$id_user.$b_end.
                //     // '</td><td>'.$b.$fio.$b_end.
                //     // '</td><td>'.$b.$types.$b_end.
                //     // '</td><td>'.$b.$type_feed.$b_end.
                //     // '</td><td>'.$b.$email.$b_end.
                //     // '</td><td>'.$b.$email.$b_end.
                //     // '</td><td>'.$b.$row["transition"].$b_end.
                //     // '</td><td>'.$b.$row["canal"].$b_end.
                //     // '</td><td>'.$b.$row["campaign"].$b_end.
                //     // '</td><td>'.$b.$row["content"].$b_end.
                //     // '</td><td>'.$b.$row["keywords"].$b_end.
                //     // '</td><td>'.$b.$row["source"].$b_end.
                //     // '</td><td>'.$b.$row["ip"].$b_end.
                //     // '</td><td>'.$b.$row["client"].$b_end.
                //     // '</td><td>'.$b.$status.$b_end.
                //     // '</td></tr>';
                // }


               

            } 

        }


        $csv_file .='</table>';
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/analyt_de.xls'; // название файла
        $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт


        fwrite($file,trim($csv_file)); // записываем в файл строки
        fclose($file); // закрываем файл

       // задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл.
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        //header('Content-Type: text/csv');
        //header('Content-Disposition: attachment; filename=export.csv;');
        header('Content-Disposition: attachment; filename=analyt_prommu.xls');
        header('Content-transfer-encoding: binary');
        //header("content-type:application/csv;charset=ANSI");
        header('Content-Type: text/html; charset=windows-1251');
        header('Content-Type: application/x-unknown');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        //print "\xEF\xBB\xBF"; // UTF-8 BOM
        readfile($file_name); // считываем файл

        $this->exportAutomizeFeedback();

    }

    public function exportAutomizeFeedback(){

         $date = filter_var(Yii::app()->getRequest()->getParam('date'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $bdate = filter_var(Yii::app()->getRequest()->getParam('bdate'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $domen = Yii::app()->getRequest()->getParam('domen');
        //print_r($ids);
         if($date == "week"){
            $my_time = time() - 604800; 
            $yester = date("Y-m-d", $my_time); 

            $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('feedback')
            ->where('crdate >:date', array(':date'=> $yester))
            ->order("crdate desc")
            ->queryAll();
            
         }
         else {

             $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('feedback')
            ->where('(crdate BETWEEN :date AND :bdate)', array(':date'=> $date, 'bdate'=> $bdate))
            ->order("crdate desc")
            ->queryAll();
         }
        


        $csv_file = '<table border="1">
            <tr><td style="color:red; background:#E0E0E0">Время'.
            '</td><td style="color:red; background:#E0E0E0">День'.
            '</td><td style="color:red; background:#E0E0E0">Месяц'.
            '</td><td style="color:red; background:#E0E0E0">Год'.
            '</td><td style="color:red; background:#E0E0E0">Домен'.
            '</td><td style="color:red; background:#E0E0E0">Идентификатор'.
            '</td><td style="color:red; background:#E0E0E0">Пользователь'.
            '</td><td style="color:red; background:#E0E0E0">Тип'.
            '</td><td style="color:red; background:#E0E0E0">Тип заявки'.
            '</td><td style="color:red; background:#E0E0E0">Телефон'.
            '</td><td style="color:red; background:#E0E0E0">Email'.
            '</td><td style="color:red; background:#E0E0E0">Источник'.
            '</td><td style="color:red; background:#E0E0E0">Канал'.
            '</td><td style="color:red; background:#E0E0E0">Кампания'.
            '</td><td style="color:red; background:#E0E0E0">Контент'.
            '</td><td style="color:red; background:#E0E0E0">Ключевое слово'.
            '</td><td style="color:red; background:#E0E0E0">Площадка'.
            '</td><td style="color:red; background:#E0E0E0">IP адрес'.
            '</td><td style="color:red; background:#E0E0E0">Client ID'.

'</td></tr>';

        
        foreach ($data as $row) {
            $csv_file .= '<tr>';
            $b = "";
            $b_end = "";

            $domen = 'https://prommu.com';
            $type_feed = 'обратная связь';


            if($row['type'] == 2){

                $name = $row['name'];
                $email = $row['email'];
                $fio = $row['name'];
                $types = "Соискатель";
                $ana = 1;

            
            } elseif($row['type'] == 3) {
            
                $name = $row['name'];
                $email = $row['email'];
                $fio = $row['name'];
                $types = "Работодатель";
                $ana = 1;
    

            } elseif($row['type'] == 0) {
            
                $name = $row['name'];
                $email = $row['email'];
                $fio = $row['name'];
                $types = "Гость";
                $ana = 1;
           

            } 



            if($ana){
            $keywords = $this->encoderSys($row['keywords']);
            $date1 = explode(" ",$row["crdate"])[0];
            $time1 = explode(" ",$row["crdate"])[1];
            $canal = explode(",", $row["canal"])[0];
            $transition = explode(",", $row["transition"])[0];
            $day = explode("-", $date1)[2];
            $month = explode("-", $date1)[1];
            $year = explode("-", $date1)[0];
            $csv_file .= '<td>'.$b.$time1.$b_end.
                '</td><td>'.$b.$day.$b_end.
                '</td><td>'.$b.$month.$b_end.
                '</td><td>'.$b.$year.$b_end.
                '</td><td>'.$b.$domen.$b_end.
                '</td><td>'.$b.'(none)'.$b_end.
                '</td><td>'.$b.$fio.$b_end.
                '</td><td>'.$b.$types.$b_end.
                '</td><td>'.$b.$type_feed.$b_end.
                '</td><td>'.$b.'(none)'.$b_end.
                '</td><td>'.$b.$email.$b_end.
                '</td><td>'.$b.$transition.$b_end.
                '</td><td>'.$b.$canal.$b_end.
                '</td><td>'.$b.$row["campaign"].$b_end.
                '</td><td>'.$b.$row["content"].$b_end.
                '</td><td>'.$b.$keywords.$b_end.
                '</td><td>'.$b.$row["ip"].$b_end.
                '</td><td>'.$b.$row["client"].$b_end.
                // '</td><td>'.$b.$row["last_referer"].$b_end.
                // '</td><td>'.$b.$row["date"].$b_end.
                '</td></tr>';
            }
        }

        $csv_file .='</table>';
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/analyt_de.xls'; // название файла
        $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт


        fwrite($file,trim($csv_file)); // записываем в файл строки
        fclose($file); // закрываем файл

       // задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл.
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        //header('Content-Type: text/csv');
        //header('Content-Disposition: attachment; filename=export.csv;');
        header('Content-Disposition: attachment; filename=analyt_prommu.xls');
        header('Content-transfer-encoding: binary');
        //header("content-type:application/csv;charset=ANSI");
        header('Content-Type: text/html; charset=windows-1251');
        header('Content-Type: application/x-unknown');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        //print "\xEF\xBB\xBF"; // UTF-8 BOM
        readfile($file_name); // считываем файл

        $this->exportAutomizeServices();

    }

    public function exportAutomizeServices(){

         $date = filter_var(Yii::app()->getRequest()->getParam('date'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $bdate = filter_var(Yii::app()->getRequest()->getParam('bdate'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         $domen = Yii::app()->getRequest()->getParam('domen');
        //print_r($ids);
         if($date == "week"){
            $my_time = time() - 604800; 
            $yester = date("Y-m-d", $my_time); 

            $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('service_cloud')
            ->where('date >:date', array(':date'=> $yester))
            ->order("date desc")
            ->queryAll();
            
         }
         else {

             $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('service_cloud')
            ->where('(date BETWEEN :date AND :bdate)', array(':date'=> $date, 'bdate'=> $bdate))
            ->order("date desc")
            ->queryAll();
         }
        


        $csv_file = '<table border="1">
            <tr><td style="color:red; background:#E0E0E0">Время'.
            '</td><td style="color:red; background:#E0E0E0">День'.
            '</td><td style="color:red; background:#E0E0E0">Месяц'.
            '</td><td style="color:red; background:#E0E0E0">Год'.
            '</td><td style="color:red; background:#E0E0E0">Домен'.
            '</td><td style="color:red; background:#E0E0E0">Идентификатор'.
            '</td><td style="color:red; background:#E0E0E0">Пользователь'.
            '</td><td style="color:red; background:#E0E0E0">Тип'.
            '</td><td style="color:red; background:#E0E0E0">Тип заявки'.
            '</td><td style="color:red; background:#E0E0E0">Телефон'.
            '</td><td style="color:red; background:#E0E0E0">Email'.
            '</td><td style="color:red; background:#E0E0E0">Источник'.
            '</td><td style="color:red; background:#E0E0E0">Канал'.
            '</td><td style="color:red; background:#E0E0E0">Кампания'.
            '</td><td style="color:red; background:#E0E0E0">Контент'.
            '</td><td style="color:red; background:#E0E0E0">Ключевое слово'.
            '</td><td style="color:red; background:#E0E0E0">Площадка'.
            '</td><td style="color:red; background:#E0E0E0">IP адрес'.
            '</td><td style="color:red; background:#E0E0E0">Client ID'.

'</td></tr>';

        
        foreach ($data as $row) {
            $csv_file .= '<tr>';
            $b = "";
            $b_end = "";
            $domen = 'https://prommu.com';
            $type_feed = 'заказ услуг '.$row['type'];           
           
            $id_user = $row['id_user'];
            $user = Yii::app()->db->createCommand()
            ->select("e.name, e.firstname, e.lastname, usr.email")
            ->from('employer e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id_user))
            ->queryAll();

             $analyt = Yii::app()->db->createCommand()
            ->select("a.content, a.keywords, a.campaign, a.canal,
                a.transition, a.ip, a.client")
            ->from('analytic a')
            ->join('user usr', 'usr.id_user=a.id_us')
            ->where('a.id_us=:id_user', array(':id_user' => $id_user))
            ->queryAll();

             if($user[0] && $analyt[0]){
                $email = $user[0]['email'];
                $fio = $user[0]['name']." ".$user[0]['firstname']." ".$user[0]['lastname'];
                $types = "Работодатель";
                $ana = 1;
                $keywords = $analyt[0]['keywords'];
                $content = $analyt[0]['content'];
                $campaign = $analyt[0]['campaign'];
                $canal = $analyt[0]['canal'];
                $transition = $analyt[0]['transition'];
                $client = $analyt[0]['client'];
                $ip = $analyt[0]['ip'];

                $keywords = $this->encoderSys($keywords);
                $transition = explode(",", $transition)[0];
            } elseif ($user[0]) {
                $email = $user[0]['email'];
                $fio = $user[0]['name']." ".$user[0]['firstname']." ".$user[0]['lastname'];
                $types = "Работодатель";
                $ana = 1;
                $keywords = '(none)';
                $content = '(none)';
                $campaign = '(none)';
                $canal = '(none)';
                $transition = '(none)';
                $client = '(none)';
                $ip = '(none)';
            } else $ana = 0;



            if($ana){
            $date1 = explode(" ",$row["date"])[0];
            $time1 = explode(" ",$row["date"])[1];
            $canal = explode(",", $canal)[0];
            $day = explode("-", $date1)[2];
            $month = explode("-", $date1)[1];
            $year = explode("-", $date1)[0];
            $csv_file .= '<td>'.$b.$time1.$b_end.
                '</td><td>'.$b.$day.$b_end.
                '</td><td>'.$b.$month.$b_end.
                '</td><td>'.$b.$year.$b_end.
                '</td><td>'.$b.$domen.$b_end.
                '</td><td>'.$b.$id_user.$b_end.
                '</td><td>'.$b.$fio.$b_end.
                '</td><td>'.$b.$types.$b_end.
                '</td><td>'.$b.$type_feed.$b_end.
                '</td><td>'.$b.'(none)'.$b_end.
                '</td><td>'.$b.$email.$b_end.
                '</td><td>'.$b.$transition.$b_end.
                '</td><td>'.$b.$canal.$b_end.
                '</td><td>'.$b.$campaign.$b_end.
                '</td><td>'.$b.$content.$b_end.
                '</td><td>'.$b.$keywords.$b_end.
                '</td><td>'.$b.$ip.$b_end.
                '</td><td>'.$b.$client.$b_end.
                // '</td><td>'.$b.$row["last_referer"].$b_end.
                // '</td><td>'.$b.$row["date"].$b_end.
                '</td></tr>';
            }
        }

        $csv_file .='</table>';
        $file_name = $_SERVER['DOCUMENT_ROOT'].'/content/analyt_de.xls'; // название файла
        $file = fopen($file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт


        fwrite($file,trim($csv_file)); // записываем в файл строки
        fclose($file); // закрываем файл

       // задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл.
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        //header('Content-Type: text/csv');
        //header('Content-Disposition: attachment; filename=export.csv;');
        header('Content-Disposition: attachment; filename=analyt_prommu.xls');
        header('Content-transfer-encoding: binary');
        //header("content-type:application/csv;charset=ANSI");
        header('Content-Type: text/html; charset=windows-1251');
        header('Content-Type: application/x-unknown');
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
        //print "\xEF\xBB\xBF"; // UTF-8 BOM
        readfile($file_name); // считываем файл

    }

    public function encoderSys($eco){
        
        $eco = urldecode($eco);
        $eco = $this->json_fix_cyr($eco);
        return $eco;

    }

    function json_fix_cyr($json_str) {
         $cyr_chars = array (
        '\u0430' => 'а', '\u0410' => 'А',
        '\u0431' => 'б', '\u0411' => 'Б',
        '\u0432' => 'в', '\u0412' => 'В',
        '\u0433' => 'г', '\u0413' => 'Г',
        '\u0434' => 'д', '\u0414' => 'Д',
        '\u0435' => 'е', '\u0415' => 'Е',
        '\u0451' => 'ё', '\u0401' => 'Ё',
        '\u0436' => 'ж', '\u0416' => 'Ж',
        '\u0437' => 'з', '\u0417' => 'З',
        '\u0438' => 'и', '\u0418' => 'И',
        '\u0439' => 'й', '\u0419' => 'Й',
        '\u043a' => 'к', '\u041a' => 'К',
        '\u043b' => 'л', '\u041b' => 'Л',
        '\u043c' => 'м', '\u041c' => 'М',
        '\u043d' => 'н', '\u041d' => 'Н',
        '\u043e' => 'о', '\u041e' => 'О',
        '\u043f' => 'п', '\u041f' => 'П',
        '\u0440' => 'р', '\u0420' => 'Р',
        '\u0441' => 'с', '\u0421' => 'С',
        '\u0442' => 'т', '\u0422' => 'Т',
        '\u0443' => 'у', '\u0423' => 'У',
        '\u0444' => 'ф', '\u0424' => 'Ф',
        '\u0445' => 'х', '\u0425' => 'Х',
        '\u0446' => 'ц', '\u0426' => 'Ц',
        '\u0447' => 'ч', '\u0427' => 'Ч',
        '\u0448' => 'ш', '\u0428' => 'Ш',
        '\u0449' => 'щ', '\u0429' => 'Щ',
        '\u044a' => 'ъ', '\u042a' => 'Ъ',
        '\u044b' => 'ы', '\u042b' => 'Ы',
        '\u044c' => 'ь', '\u042c' => 'Ь',
        '\u044d' => 'э', '\u042d' => 'Э',
        '\u044e' => 'ю', '\u042e' => 'Ю',
        '\u044f' => 'я', '\u042f' => 'Я',
 
        '\r' => '',
        '\n' => '<br />',
        '\t' => ''
    );
 
        foreach ($cyr_chars as $cyr_char_key => $cyr_char) {
            $json_str = str_replace($cyr_char_key, $cyr_char, $json_str);
        }
        return $json_str;
    }

public function vac(){
        $eco = Yii::app()->getRequest()->getParam('name');
      

      $result = Yii::app()->db->createCommand()
                ->select('id_user')
                ->from('user')
                ->where('email=:email', array(':email'=>$eco))
                ->queryAll();
            $id = $result['id_user'];

           return $result[0]['id_user']; 
        

}

    public function delete(){
        $email = filter_var(Yii::app()->getRequest()->getParam('email'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
        $sql = "SELECT id_user
            FROM user
            WHERE email LIKE '%{$email}%' 
            ";
        $res = Yii::app()->db->createCommand($sql)->queryRow();

        
    

        Yii::app()->db->createCommand()
            ->delete('user', 'id_user=:id_user', array(':id_user' => $res['id_user']));

                            return "I'll be back";
           
                }
            
            
                public function neWorkDay(){
        
        $sql = "SELECT DATEDIFF(s.bdate , NOW()) date, v.id_promo promo
            FROM empl_vacations e 
            INNER JOIN vacation_stat v ON e.id = v.id_vac
            INNER JOIN empl_city s ON e.id = s.id_vac
            INNER JOIN employer em ON em.id_user = e.id_user
            INNER JOIN user eu ON em.id_user = eu.id_user
            WHERE e.status = 1 
            AND DATEDIFF(e.remdate, NOW()) > 0";
        $res = Yii::app()->db->createCommand($sql)->queryAll();


        for($i = 0; $i < count($res); $i++){
        $idus = $res[$i]['promo'];
        $sql = "SELECT r.id_user
            FROM resume r
            WHERE r.id = {$idus}";
        $red = Yii::app()->db->createCommand($sql);
        $id = $red->queryScalar();

        $day = $res[$i]['date'];
            if($day == "1"){
                $ids = $id;
                $sql = "SELECT r.new_workday
                FROM push_config r
                WHERE r.id = {$ids}";
                $result = Yii::app()->db->createCommand($sql)->queryScalar();

                if($result == 2) {
                $message = array(
                    'title' => 'Prommu',
                    'body' => 'Завтра на работу',
                    'click_action' => 'NEW_PUSH_ACTION',
                );
                $datas = array(
                    'action' => 'new_workday',
                ); 
                $figaro = compact('ids', 'datas', 'message');
                $service = $this->getPush($figaro);
                }
            }
        }

        $sql = "SELECT DATEDIFF(e.remdate , NOW()) date, e.id_user empl
            FROM empl_vacations e 
            INNER JOIN vacation_stat v ON e.id = v.id_vac
            INNER JOIN empl_city s ON e.id = s.id_vac
            INNER JOIN employer em ON em.id_user = e.id_user
            INNER JOIN user eu ON em.id_user = eu.id_user
            WHERE e.status = 1 
            AND DATEDIFF(e.remdate, NOW()) > 0";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        for($i = 0; $i < count($res); $i++){
        $id = $res[$i]['empl'];
        $day = $res[$i]['date'];
            if($day == "1"){
                $ids = $id;
                $sql = "SELECT r.new_workday
                FROM push_config r
                WHERE r.id = {$ids}";
                $result = Yii::app()->db->createCommand($sql)->queryScalar();

                if($result == 2) {
                $message = array(
                    'title' => 'Prommu',
                    'body' => 'Завтра последний день отображения вакансии',
                    'click_action' => 'NEW_PUSH_ACTION',
                );
                $datas = array(
                    'action' => 'new_workday',
                ); 
                $figaro = compact('ids', 'datas', 'message');
                $service = $this->getPush($figaro);
                }
            }
        }
       
        
        return $res;

        
    }

    public function getTopics()
    {
    $error = '-101';
    try
     {
       $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
       list($idus, $profile, $datas) = $this->checkAccessToken($accessToken);

       $sql = "SELECT r.new_mess
            FROM push_config r
            WHERE r.id = {$idus}";
        $res = Yii::app()->db->createCommand($sql)->queryAll(); 
        if(!isset($res[0])){
        Yii::app()->db->createCommand()
                    ->insert('push_config', array(
                        'id' => $idus,
                        'new_mess' => 2,
                        'new_rate' => 2,
                        'new_invite' => 2,
                        'new_respond' => 2,
                        'new_workday' => 2,
                   ));
            
    }


       $sql = "SELECT r.status
            FROM user r
            WHERE r.id_user = {$idus}";
        $res= Yii::app()->db->createCommand($sql)->queryScalar();
        if($res == 2){
       
    

       $sql = "SELECT r.vac, r.city
            FROM user_topics r
            WHERE r.id = {$idus}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['topic'] = $res;

        $sql = "SELECT r.new_mess, r.new_rate, r.new_invite, r.new_respond, r.new_workday
            FROM push_config r
            WHERE r.id = {$idus}";
        $res = Yii::app()->db->createCommand($sql)->queryAll(); 
        $data['config'] = $res;
    }
    elseif($res == 3) {
        $sql = "SELECT r.new_mess, r.new_rate, r.new_invite, r.new_respond, r.new_workday
            FROM push_config r
            WHERE r.id = {$idus}";
        $res = Yii::app()->db->createCommand($sql)->queryAll(); 
        $data['config'] = $res;
    }
        
       } catch (Exception $e)
            {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                case -104 : $message = 'Error while getting chat data'; break;
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        }
       

        return $data;
    }

   

    public function setTopics()
    {
    $error = '-101';
     try
     {

        $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cloud = Yii::app()->getRequest()->getParam('cloud');
        $cloud = $cloud ? get_object_vars(json_decode($cloud)) : null;
        $topic = $cloud['topic'];
        $config = $cloud['config'];
        list($idus, $profile, $data) = $this->checkAccessToken($accessToken);
        
        foreach ($config as $keys => $values) {
            $arrCon[] = $values;
        }
        Yii::app()->db->createCommand()
            ->delete('push_config', 'id=:id', array(':id' => $idus));

        if(!empty($topic)){
        $sql = "SELECT r.vac
            FROM user_topics r
            WHERE r.id = {$idus}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();


        foreach ($res as $key => $value) {
             foreach ($value as $key1 => $value1) {
                  Yii::app()->db->createCommand()
                ->delete('user_topics', array('and', 'id=:id', 'vac=:vac'), array(':id' => $idus, ':vac' => $value1));
               }
             }
        
       foreach ($topic as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $arr[] = $value1;
            }
       }

       $count = count($arr);

       for($i = 0; $i< $count; $i++){
            if($i % 2){
                $arrCity[] = $arr[$i];
            }
            else{
                $arrVac[] = $arr[$i];
                }
        }

        for($i = 0; $i < count($arrCity); $i++ )
        {
             Yii::app()->db->createCommand()
                ->insert('user_topics', array(
                    'id' => $idus,
                    'vac' => $arrVac[$i],
                    'city' => $arrCity[$i],
                ));
            }
            Yii::app()->db->createCommand()
                ->reset();
            Yii::app()->db->createCommand()
                    ->insert('push_config', array(
                        'id' => $idus,
                        'new_mess' => $arrCon[0],
                        'new_rate' => $arrCon[1],
                        'new_invite' => $arrCon[2],
                        'new_respond' => $arrCon[3],
                        'new_workday' => $arrCon[4],
                   ));
            

        }
        elseif(empty($topic)) {
            Yii::app()->db->createCommand()
                    ->insert('push_config', array(
                        'id' => $idus,
                        'new_mess' => $arrCon[0],
                        'new_rate' => $arrCon[1],
                        'new_invite' => $arrCon[2],
                        'new_respond' => $arrCon[3],
                        'new_workday' => $arrCon[4],
                   ));
        }
    
            
        
        } catch (Exception $e)
            {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                case -104 : $message = 'Error while getting chat data'; break;
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        }
      
        return $data = ['error' => '0', 'message' => 'success'];
    }

    public function getNicolaDay(){
        //  $sql = "SELECT u.email
        //     FROM resume r
        //     INNER JOIN user_mech m ON r.id_user = m.id_us 
        //     INNER JOIN user_city ci ON r.id_user = ci.id_user AND ci.id_city IN(10320)
        //     INNER JOIN user u ON r.id_user = u.id_user AND u.ismoder = 1 AND u.isblocked = 0
        //     WHERE m.isshow = 0 AND m.id_mech IN (116)";
        // $result = Yii::app()->db->createCommand($sql)->queryAll();

         $sql = "SELECT e.id, e.ispremium, e.status, e.title, e.requirements, e.duties, e.conditions, e.istemp,
                   DATE_FORMAT(e.remdate, '%d.%m.%Y') remdate,e.agefrom, e.ageto,
                   e.shour,
                   e.sweek,
                   e.smonth,
                   e.isman,
                   e.smart,
                   e.iswoman,
                   e.repost,
                   DATE_FORMAT(e.crdate, '%d.%m.%Y') crdate
              , c1.id_city, c2.name AS ciname, c1.citycu
              , ea.id_attr
              , d.name AS pname
              , ifnull(em.logo, '') logo
            FROM empl_vacations e 
            LEFT JOIN empl_city c1 ON c1.id_vac = e.id 
            LEFT JOIN city c2 ON c2.id_city = c1.id_city 
            JOIN empl_attribs ea ON ea.id_vac = e.id
            JOIN user_attr_dict d ON (d.id = ea.id_attr) AND (d.id_par = 110)
            JOIN employer em ON em.id_user = e.id_user
            JOIN user u ON em.id_user = u.id_user AND u.ismoder = 1
            WHERE e.id= 1899
            ORDER BY e.id DESC";
            $rest = Yii::app()->db->createCommand($sql);
            $rest = $rest->queryAll();

        return $rest[0]['id_city'];
    }

     public function getPush($token, $type)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $YOUR_API_KEY = 'AAAAxOfjHmg:APA91bGljNykiRurhAUchhCEnZ6puyL5IrBw0pbIsV9aR0rXI1_gPmkSmpgMgtBtWBezsQtecDSGjBozqQIYskPc5ZmsRKwYwMz91uDkPrQcR_FgXz0mj5L_j5Z2IW7Dm35_JSOFVSoo'; // Server key
        $YOUR_TOKEN_ID = $token; // Client token id


   
        $request_headers = [
            'Content-Type: application/json',
            'Authorization: key=' . $YOUR_API_KEY,
        ];
        switch ($type) {
            case 'mess':

                $message = "Вы получили новое сообщение на сервисе";
                $title = "Новое сообщение";
                $link = Subdomain::site() . MainConfig::$PAGE_CHATS_LIST;
                break;

             case 'vacancy':

                $message = "Работодатель приглашает на вакансию";
                $title = "Новое приглашение";
                $link = "https://prommu.com/vacancy";
                break;

            case 'invite':
                $message = "Вы получили новый отклик на вакансию";
                $title = "Новый отклик";
                $link = "https://prommu.com/user/responses";
                break;

            case 'respond':
                $message = "Вы получили новое приглашение на вакансию";
                $title = "Новое приглашение";
                $link = "https://prommu.com/user/responses";
                break;

            case 'rate':
                $message = "Вы получили новый отзыв на сервисе";
                $title = "Новый отзыв";
                $link = "https://prommu.com/rate";
                break;

            case 'workonday':
                $message = "До начала работы на вакансии остался один день";
                $title = "Остался день";
                $link = "https://prommu.com";
                break;

            case 'vacmoder':
                $message = "Ваша вакансия прошла модерацию на сервисе";
                $title = "Вакансия опубликована";
                $link = "https://prommu.com";
                break;

            case 'workoffday':
                $message = "До окончания действия вакансии остался один день";
                $title = "Остался день";
                $link = "https://prommu.com";
                break;
            case 'NicolaDay':
                $message = "Prommu поздравляет пользователей с Днем святого Николая!";
                $title = "С праздником!";
                $link = "https://prommu.com";
                break;

            default:
                # code...
                break;
        }
    
        $request_body = [
            'to' => $YOUR_TOKEN_ID,
            'notification' => [
                'title' => $title,
                'body' => $message,
                'icon' => 'https://prommu.com/images/logo.png',
                'click_action' => $link,
            ],
        ];
    
        $fields = json_encode($request_body);
    
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    
    }

     public function getPushApi($token, $type, $text, $link)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $YOUR_API_KEY = 'AAAAxOfjHmg:APA91bGljNykiRurhAUchhCEnZ6puyL5IrBw0pbIsV9aR0rXI1_gPmkSmpgMgtBtWBezsQtecDSGjBozqQIYskPc5ZmsRKwYwMz91uDkPrQcR_FgXz0mj5L_j5Z2IW7Dm35_JSOFVSoo'; // Server key
        $YOUR_TOKEN_ID = $token; // Client token id


   
        $request_headers = [
            'Content-Type: application/json',
            'Authorization: key=' . $YOUR_API_KEY,
        ];
        switch ($type) {
            case 'mess':

                $message = "Вы получили новое сообщение на сервисе";
                $title = "Новое сообщение";
                $link = Subdomain::site() . MainConfig::$PAGE_CHATS_LIST;
                break;

             case 'vacancy':

                $message = "Работодатель приглашает на вакансию";
                $title = "Новое приглашение";
                $link = $link;
                break;

            case 'invite':
                $message = "Вы получили новый отклик на вакансию";
                $title = "Новый отклик";
                $link = "https://prommu.com/user/responses";
                break;

            case 'respond':
                $message = "Вы получили новое приглашение на вакансию";
                $title = "Новое приглашение";
                $link = "https://prommu.com/user/responses";
                break;

            case 'rate':
                $message = "Вы получили новый отзыв на сервисе";
                $title = "Новый отзыв";
                $link = "https://prommu.com/rate";
                break;

            case 'workonday':
                $message = "До начала работы на вакансии остался один день";
                $title = "Остался день";
                $link = "https://prommu.com";
                break;

            case 'vacmoder':
                $message = "Ваша вакансия прошла модерацию на сервисе";
                $title = "Вакансия опубликована";
                $link = "https://prommu.com";
                break;

            case 'workoffday':
                $message = "До окончания действия вакансии остался один день";
                $title = "Остался день";
                $link = "https://prommu.com";
                break;
            case 'NicolaDay':
                $message = "Prommu поздравляет пользователей с Днем святого Николая!";
                $title = "С праздником!";
                $link = "https://prommu.com";
                break;

            default:
                # code...
                break;
        }
    
        $request_body = [
            'to' => $YOUR_TOKEN_ID,
            'notification' => [
                'title' => $title,
                'body' => $message,
                'icon' => 'https://prommu.com/images/logo.png',
                'click_action' => $link,
            ],
        ];
    
        $fields = json_encode($request_body);
    
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    
    }




    public function firebase()
    {
        $subscriber_id = $_GET['endpoint'];
        $id = Share::$UserProfile->id;
        
        $sql = "SELECT r.push
            FROM user_push r
            WHERE r.id = {$id}";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

          foreach ($res as $key => $value) {
             foreach ($value as $key1 => $value1) {
                  Yii::app()->db->createCommand()
                ->delete('user_push', array('and', 'id=:id', 'push=:push'), array(':id' => $id, ':push' => $value1));
               }
             }

        $res = Yii::app()->db->createCommand()
                    ->insert('user_push', array(
                        'id' => $id,
                        'push' => $subscriber_id,
                    ));

    }

    public function setPush()
    {
    $error = '-101';
     try
     {

        $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $push_token = filter_var(Yii::app()->getRequest()->getParam('push_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $os = Yii::app()->getRequest()->getParam('os');
        list($idus, $profile, $datas) = $this->checkAccessToken($accessToken);

            $sql = "SELECT r.push
            FROM push_mess r
            WHERE r.id = {$idus}";
            $res = Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($res as $key => $value) {
             foreach ($value as $key1 => $value1) {
                if($push_token == $value1){
                  Yii::app()->db->createCommand()
                ->delete('push_mess', array('and', 'id=:id', 'push=:push'), array(':id' => $idus, ':push' => $value1));
               }
            
             }
          }
                     Yii::app()->db->createCommand()
                ->insert('push_mess', array(
                    'id' => $idus,
                    'token' => $accessToken,
                    'push' => $push_token,
                    'os' => $os,
                ));
        $data=['error' => '0', 'message' => 'Good Luck'];

        } catch (Exception $e)
            {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                case -104 : $message = 'Error while getting chat data'; break;
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } 

        return $data;
    }

    public function photoEdit()
    {
    $error = '-101';
     try
     {

        $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        list($idus, $profile, $data) = $this->checkAccessToken($accessToken);
        $id = $idus;
        $sql = "SELECT r.status
            FROM user r
            WHERE r.id_user = {$idus}";
        $res= Yii::app()->db->createCommand($sql)->queryScalar();
        if($res == 3){
            $Logo = new UploadLogo($profile);
            $data = $Logo->processUploadedLogoEmpl($_FILES['photo']);
            Yii::app()->db->createCommand()
                ->update('employer', array(
                    'logo' => $data,
                ), 'id_user = :id', array(':id' => $idus));
            }
        elseif($res == 2){
            $Logo = new UploadLogo($profile);
            $data = $Logo->processUploadedLogoPromo($_FILES['photo']);
            $User = new UserProfileApplic($profile);
            $dat = $User->sendLogo(compact('id', 'data'));
        }
        } catch (Exception $e)
            {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                case -104 : $message = 'Error while getting chat data'; break;
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } 

        return $message = "Good Sent";
    }
    

    public function composTer()
    {
     $error = '-101';
     try
     {
       $id = Yii::app()->getRequest()->getParam('id'); // читаем вакансии
       $sql = "SELECT
                s.id_vac sid, s.status, eu.id_user idusempl,
                ru.id_user iduspromo
            FROM vacation_stat s
            INNER JOIN empl_vacations e ON e.id = s.id_vac
            INNER JOIN employer em ON em.id_user = e.id_user
            INNER JOIN user eu ON em.id_user = eu.id_user
            INNER JOIN resume r ON s.id_promo = r.id
            INNER JOIN user ru ON ru.id_user = r.id_user
            WHERE s.isresponse = 1
              AND r.id_user = {$id}
              AND s.status = 6";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        } catch (Exception $e)
            {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                case -104 : $message = 'Error while getting chat data'; break;
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } 

        return $res;// endif
        } // end foreach


       public function setCommAndRate()
       {
           
        $vc = new Vacancy();
        $vc = $vc->VkRepost(1435, 3);

        return $vc;
    }
            


    /**
     * Получаем должности
     * @return array
     */
    public function getPost()
    {

        //    $sql = "SELECT r.status
        //     FROM user r";
        // $res= Yii::app()->db->createCommand($sql)->queryRow();
        // return $res;
        // $data['isblocked'] = 0;

        // $res = Yii::app()->db->createCommand()
        //     ->update('user', $data, $value);
        // return $data[0]['name'];

        $sql = "SELECT DISTINCT r.id, r.id_user idus, r.photo, r.firstname, r.lastname, r.isman
                , cast(r.rate AS SIGNED) - ABS(cast(r.rate_neg as signed)) avg_rate, r.rate, r.rate_neg, photo
            FROM resume r
            INNER JOIN user u ON r.id_user = u.id_user AND u.ismoder = 1
            AND ( u.isblocked = 3)
        
            ORDER BY avg_rate DESC, id DESC
            LIMIT 1000";
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($result as $key => $value) {
                $props = array(
                'id_us' => $value['idus'],
                'id_mech' => rand(111,119),
                'isshow' => 0, 
                'crdate' => date("Y-m-d H:i:s"),

                );

                $res = Yii::app()->db->createCommand()
                ->insert('user_mech', $props);

                Yii::app()->db->createCommand()
                ->update('resume', array(
                    'isblocked' => 0,
                ), 'id = :id', array(':id' => $value['id']));

                Yii::app()->db->createCommand()
                ->update('user', array(
                    'isblocked' => 0,
                ), 'id_user = :id', array(':id' => $value['idus']));

            

        }
        return $result;
    }

    public function updateProf() 
    {
        $error = '-101';
        try
        {
        $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cloud = Yii::app()->getRequest()->getParam('cloud');
        $cloud = $cloud ? get_object_vars(json_decode(base64_decode($cloud))) : null;
        list($idus, $profile, $data) = $this->checkAccessToken($accessToken);
        $sql = "SELECT r.status
            FROM user r
            WHERE r.id_user = {$idus}";
        $res= Yii::app()->db->createCommand($sql)->queryScalar();
        if($res==3){
        $User = new User();
        $data = $User->updateEmployerApi($cloud,$idus);
    }
    elseif($res==2) {
        $User = new User();
        $data = $User->updatePromo($cloud,$idus);
    }
    else return $data = ['error' => $error, 'message' => $message];
        } catch (Exception $e)
            {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                case -104 : $message = 'Error while getting chat data'; break;
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        }

        return $data;
    }


    public function getPromoSearch()
    {
        $filter = Yii::app()->getRequest()->getParam('filter');
        $filter = $filter ? get_object_vars(json_decode(base64_decode($filter))) : null;
        $limit = filter_var(Yii::app()->getRequest()->getParam('limit', 0), FILTER_SANITIZE_NUMBER_INT);

        $limit = $limit == 0 || $limit > MainConfig::$DEF_PAGE_API_LIMIT ? MainConfig::$DEF_PAGE_API_LIMIT : $limit;

        // читаем фильтр
        if( $filter )
        {
            $api = $filter['api'] ?: null;
            // фильтр по типу
            $sm = $filter['sm'] ?: null;
            $sf = $filter['sf'] ?: null;
            $mb = $filter['mb'] ?: null;
            $avto = $filter['avto'] ?: null;
            $posts = $filter['posts'] ? array_combine($filter['posts'], $filter['posts']) : null;
            // фильтр городов
            $cities = $filter['city'] ? array_combine($filter['city'], $filter['city']) : null;
            $qs = $filter['qs'] ?: null;
            $af = $filter['af'] ?: null;
            $at = $filter['at'] ?: null;
            $filter = ['filter' => compact('posts', 'cities', 'qs', 'sm', 'sf', 'mb', 'avto', 'af','at', 'api')];
        }
        else
        {
            $filter = [];
        } // endif
    

        // получаем данные страницы
        $SearchPromo = new SearchPromo();
        $arAllId = $SearchPromo->searchPromosCount($filter);
        $cnt = sizeof($arAllId);
        $pages = new CPagination($cnt);
        $pages->pageSize = $limit;
        $pages->applyLimit($SearchPromo);


        // отсеивать из ответа работодателей
        $data = $SearchPromo->getPromos($arAllId, 0, $filter);

        return array_merge($data);
    }


    /**
     * Получаем фильтрованных работодателей
     * @return array
     */
    public function getEmplSearch()
    {
        $filter = Yii::app()->getRequest()->getParam('filter');
        $filter = $filter ? get_object_vars(json_decode(base64_decode($filter))) : null;
        $limit = filter_var(Yii::app()->getRequest()->getParam('limit', 0), FILTER_SANITIZE_NUMBER_INT);

        $limit = $limit == 0 || $limit > MainConfig::$DEF_PAGE_API_LIMIT ? MainConfig::$DEF_PAGE_API_LIMIT : $limit;

        // читаем фильтр
        if( $filter )
        {
            // фильтр по типу
            $cotype = $filter['cotype'] ? array_combine($filter['cotype'], $filter['cotype']) : null;
            // фильтр городов
            $cities = $filter['city'] ? array_combine($filter['city'], $filter['city']) : null;
            $qs = $filter['qs'] ?: null;
            $filter = ['filter' => compact('cotype', 'cities', 'qs')];
        }
        else
        {
            $filter = [];
        } // endif
    

        // получаем данные страницы
        $SearchEmpl = new SearchEmpl();
        $pages = new CPagination($SearchEmpl->searchEmployersCount($filter));
        $pages->pageSize = $limit;
        $pages->applyLimit($SearchEmpl);


        // отсеивать из ответа работодателей
        $data = $SearchEmpl->getEmployers(0, $filter);

        return array_merge($data);
    }


    public function setRate()
    {
        $error = '-101';
        try
        {
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cloud = Yii::app()->getRequest()->getParam('cloud');
            $cloud = $cloud ? get_object_vars(json_decode($cloud)) : null;
            list($idus, $profile, $dat) = $this->checkAccessToken($accessToken);
            $profile->setUserData();
            $idvac = $cloud['idvac'];
            $message = $cloud['message'];
            $type = $cloud['type'];
            $rate = $cloud['rate'];
            $idusp = $cloud['idusp'];
            $sql = "SELECT r.status
            FROM user r
            WHERE r.id_user = {$idus}";
            $retRa= Yii::app()->db->createCommand($sql)->queryScalar();

            if( $retRa == 2) 
            {
                $Response = new ResponsesApplic($profile);
                $figaros = compact('idvac', 'message', 'type', 'rate', 'idus');
                $data = $Response->saveRateDatas($figaros);

            }
            elseif($retRa == 3) 
            {
                $Response = new ResponsesEmpl($profile);
                $figaros = compact('idvac', 'message', 'type', 'rate', 'idus', 'idusp');
                $data = $Response->saveRateDatas($figaros);
            }

        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -104 : // зарезервированоa
                case -103 : $message = $e->getMessage(); break; // token expired
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry

        return $data;
    }



    public function dataResponse()
    {
        $error = '-101';
        try
        {
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cloud = Yii::app()->getRequest()->getParam('cloud');
            $cloud = $cloud ? get_object_vars(json_decode($cloud)) : null;
            $limit = filter_var(Yii::app()->getRequest()->getParam('limit', MainConfig::$DEF_PAGE_API_LIMIT), FILTER_SANITIZE_NUMBER_INT);
            $limit = $limit > MainConfig::$DEF_PAGE_API_LIMIT ? MainConfig::$DEF_PAGE_API_LIMIT : $limit;

            // проверка токена, получаем профиль
            list($idus, $profile, $dat) = $this->checkAccessToken($accessToken);
            $profile->setUserData();
            $sql = "SELECT r.status
            FROM user r
            WHERE r.id_user = {$idus}";
            $retRa= Yii::app()->db->createCommand($sql)->queryScalar();
            if($retRa == 2){
                $sql = "SELECT r.id
                FROM resume r
                WHERE r.id_user = {$idus}";
                $res = Yii::app()->db->createCommand($sql);
                $id = $res->queryScalar();
                $type = $cloud['type'];
                $figaro = compact('id', 'type');

                $Response = new ResponsesApplic($profile);
                $data['resps'] = $Response->getResponsess($figaro);
            }
            elseif($retRa == 3){
                $id = $idus;
                $figaro = compact('id');
                $Response = new ResponsesEmpl($profile);
                $data['resps'] = $Response->getResponsess($figaro);
            }

            if( (int)$data['error'] > 0 ) throw new ExceptionApi($data['message'], -104);
          


        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -104 : // зарезервированоa
                case -103 : $message = $e->getMessage(); break; // token expired
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry

        return $data;
    }



    public function setInvite()
    {
        $error = '-101';
        try
        {   
            $idvac = filter_var(Yii::app()->getRequest()->getParam('idvac', 0), FILTER_SANITIZE_NUMBER_INT);
            $id = filter_var(Yii::app()->getRequest()->getParam('id', 0), FILTER_SANITIZE_NUMBER_INT);
            $idres = filter_var(Yii::app()->getRequest()->getParam('idres', 0), FILTER_SANITIZE_NUMBER_INT);
            $status = filter_var(Yii::app()->getRequest()->getParam('status', 0), FILTER_SANITIZE_NUMBER_INT);
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // проверка токена, получаем профиль
            list($idus, $profile, $dat) = $this->checkAccessToken($accessToken);
            $profile->setUserData();

            $sql = "SELECT r.status
            FROM user r
            WHERE r.id_user = {$idus}";
            $retRa= Yii::app()->db->createCommand($sql)->queryScalar();

            if( $retRa == 3) 
            {
                $sql = "SELECT r.id
                FROM resume r
                WHERE r.id_user = {$id}";
                $res = Yii::app()->db->createCommand($sql);
                $id = $res->queryScalar();

            $res = (new Vacancy($profile))->getVacancyInfo($idus);
            $rest = $res['vac_data'];
            foreach($rest as $key => $val){
                if($key == $idvac) {
                    $Response = new ResponsesApplic($profile);
                    $data = $Response->invite(compact('idvac', 'id'));
                }
            }
            if($data == null) {
                
                $data = ['error' => '1', 'message' => 'Not vacantions!'];

            }
        }
        elseif($retRa==2) {
            $sql = "SELECT s.status
            FROM vacation_stat s 
            WHERE s.id= {$idres}
              AND s.isresponse = 2";
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryScalar();

            if($status == 5  || $status == 3 )
            {     

            $Response = new ResponsesApplic($profile);
            $data = $Response->setResponseStatus(compact('idres', 'status', 'idus'));
        }
        else {
             $data = ['error' => '1', 'message' => 'No permission!'];
        }

        }
        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -104 : // зарезервированоa
                case -103 : $message = $e->getMessage(); break; // token expired
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry

        return $data;
    }

    /**
     * Оставляем отклик на вакансию
     * @return array
     */
    public function setResponse()
    {
        $error = '-101';
        try
        {
            $idvac = filter_var(Yii::app()->getRequest()->getParam('id', 0), FILTER_SANITIZE_NUMBER_INT);
            $status = filter_var(Yii::app()->getRequest()->getParam('status', 0), FILTER_SANITIZE_NUMBER_INT);
            $idres = filter_var(Yii::app()->getRequest()->getParam('idres', 0), FILTER_SANITIZE_NUMBER_INT);
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);


            // проверка токена, получаем профиль
            list($idus, $profile, $data) = $this->checkAccessToken($accessToken);
            $profile->setUserData();

            $sql = "SELECT r.status
            FROM user r
            WHERE r.id_user = {$idus}";
            $res= Yii::app()->db->createCommand($sql)->queryScalar();
            if($res==3){
            $Response = new ResponsesEmpl($profile);
            $data = $Response->setResponseStatus(compact('idres', 'idus', 'status'));
            }
            elseif($res==2) {
            $res = (new Vacancy($profile))->getVacancyView($idvac)['response'];
            if( (int)$res['response'] != 1 ) throw new ExceptionApi($res['message'], -104);

            $Response = new ResponsesApplic($profile);
            $data = $Response->setVacationResponse(compact('idvac'));
            if( (int)$data['error'] > 0 ) throw new ExceptionApi($data['message'], -104);

            }

        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -104 : // зарезервированоa
                case -103 : $message = $e->getMessage(); break; // token expired
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry

        return $data;
    }


    /**
     * ПОлучаем темы чатов
     * @return array
     */


    public function getChatMessage()
    {   
        $error = '-101';
        try
        {
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $idChat = Yii::app()->getRequest()->getParam('id_chat');
            $limit = filter_var(Yii::app()->getRequest()->getParam('limit', 0), FILTER_SANITIZE_NUMBER_INT);
            $limit = $limit == 0 || $limit > MainConfig::$DEF_PAGE_API_LIMIT ? MainConfig::$DEF_PAGE_API_LIMIT : $limit;

           list($idus, $profile, $data) = $this->checkAccessToken($accessToken);
            
            $sql = "SELECT ca.id, ca.message,ca.is_resp isresp, ca.is_read isread
                  , e.name namefrom
                  , CONCAT(r.firstname, ' ', r.lastname) nameto
                  , DATE_FORMAT(ca.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(ca.crdate, '%H:%i:%s') crtime
                FROM chat ca 
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE (ca.id_usp  = {$idus} OR ca.id_use  = {$idus})
                AND ca.id_theme = {$idChat}
                ORDER BY id DESC
                LIMIT {$limit}";

        $res = Yii::app()->db->createCommand($sql);
        $data= $res->queryAll();

           $res = Yii::app()->db->createCommand()
            ->update('chat', array(
                'is_read' => 1,
            ), 'id_theme = :id_theme AND is_resp = 1 AND is_read = 0', array(':id_theme' => $idChat));

   } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                case -104 : $message = 'Error while getting chat data'; break;
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        }
            

        return $data;
    }



    public function getChatThemes()
    {
        $error = '-101';
        try
        {
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = filter_var(Yii::app()->getRequest()->getParam('limit', MainConfig::$DEF_PAGE_API_LIMIT), FILTER_SANITIZE_NUMBER_INT);

            $limit = $limit > MainConfig::$DEF_PAGE_API_LIMIT ? MainConfig::$DEF_PAGE_API_LIMIT : $limit;

            // проверка токена, получаем профиль
            list($idus, $profile, $data) = $this->checkAccessToken($accessToken);

            $Im = $profile->makeChat();


            $pages=new CPagination($Im->getChatsCount($profile->id));
            $pages->pageSize = $limit;
            $pages->applyLimit($Im);

            try { $data = $Im->getChats();
            } catch (Exception $e) { throw new ExceptionApi($e->getMessage(), -104); } // endtry


        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                case -104 : $message = 'Error while getting chat data'; break;
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry

        return $data;
    }



    /**
     * ПОлучаем типы компаний
     * @return array
     */
    public function getCotypes()
    {
        $error = '-101';
        try
        {
            // читаем типы копании
            $sql = "SELECT d.id, d.id_par, d.type, d.name
                FROM user_attr_dict d
                WHERE d.id_par = 101
                ORDER BY d.name
                ";
            $data = Yii::app()->db->createCommand($sql)->queryAll();


        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : $message = 'Error while getting chat data'; break;
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry

        return $data;
    }

    public function vacAct()
    {
        $error = '-101';
        try
        {   
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cloud = Yii::app()->getRequest()->getParam('cloud');
            $cloud = $cloud ? get_object_vars(json_decode($cloud)) : null;
            $idvac = $cloud['idvac'];
            $deactive = $cloud['deactive'];

            list($idus, $profile, $data) = $this->checkAccessToken($accessToken);
            $Vacancy = new Vacancy($profile);
            $commo = compact('idvac', 'deactive', 'idus');   
            $dat = $Vacancy->vacActivate($commo);
            
           } catch (Exception $e)
        {
            $error = abs($e->getCode()
                );
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry
       
        return $dat;
    }


    public function vacationPub()
    {
        $error = '-101';
        try
        {   
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cloud = Yii::app()->getRequest()->getParam('cloud');
            $cloud = $cloud ? get_object_vars(json_decode($cloud)) : null;
            $title = $cloud['title'];
            $agefrom = $cloud['agefrom'];
            $ageto = $cloud['ageto'];
            $agefrom = $cloud['agefrom'];
            $isman = $cloud['isman'];
            $iswoman = $cloud['iswoman'];
            $istemp = $cloud['istemp'];
            $shour = $cloud['shour'];
            $ismed = $cloud['ismed'];
            $isavto = $cloud['isavto'];
            $remdate = $cloud['remdate'];
            $istemp = $cloud['istemp'];
            $posts = $cloud['posts'];
            $city = $cloud['city'];
            $bdate = $cloud['bdate'];
            $edate = $cloud['edate'];
            $idvac = $cloud['idvac'];

            list($idus, $profile, $data) = $this->checkAccessToken($accessToken);
            $figaro = compact('title','agefrom','ageto','isman', 'iswoman',  'istemp', 'shour', 'ismed', 'isavto', 'remdate','posts', 'city','bdate','edate','pub', 'idvac', 'idus');
            $Vacancy = new Vacancy($profile);
            $data = $Vacancy->saveVacData($figaro);   
            
           } catch (Exception $e)
        {
            $error = abs($e->getCode()
                );
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry
       
        return $data;
    }


    /**
     * Получаем данные по вакансии
     * @return array
     */
    public function getVacancyDataView()
    {
        $error = '-101';
        try
        {
            $idvac = filter_var(Yii::app()->getRequest()->getParam('id', 0), FILTER_SANITIZE_NUMBER_INT);
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // проверка токена, получаем профиль
            if( $accessToken )
            {
                list($idus, $profile, $data) = $this->checkAccessToken($accessToken);
                $profile->setUserData();
                $sql = "SELECT
                 s.status, em.id_user empl
                FROM vacation_stat s
                INNER JOIN empl_vacations e ON e.id = s.id_vac
                INNER JOIN employer em ON em.id_user = e.id_user
                INNER JOIN resume r ON s.id_promo = r.id
                INNER JOIN user ru ON ru.id_user = r.id_user
                INNER JOIN user eu ON eu.id_user = em.id_user
                WHERE s.isresponse IN (1,2)
                AND e.id = {$idvac}
                AND (ru.id_user = {$idus} OR em.id_user = {$idus})";
                $res = Yii::app()->db->createCommand($sql);
                $res = $res->queryRow();

                


            }
            else
            {
                $profile = (object)[];
            } // endif
            $Vacancy = new Vacancy($profile);
            $data = $Vacancy->getVacancyView($idvac);

            if($idus == $data['vac']['idus']){
                $data['vac']['isMyVacancy'] = "true";
            }
            else $data['vac']['isMyVacancy'] = "false";

            if($data['response']['response'] == 1 && $res['status'] == 0 ||
                $data['response']['response'] == 1 && $res['status'] == 0){
                 $data['vac']['canSendRequest'] = "true";
            }
            else $data['vac']['canSendRequest'] = "false";
            
            if($res['status'] == 5 ){
                 $data['vac']['canSendMessage'] = "true";
            }
            else  $data['vac']['canSendMessage'] = "false";

            if($res['status'] == 6 ){
                 $data['vac']['canSendRating'] = "true";
            }
            else  $data['vac']['canSendRating'] = "false";
           
           if(!empty($data['vacResponses']['0']['idusr'])){
                $data['vac']['idus'] = $data['vacResponses']['0']['idusr'];
           }

            $empl = $data['vac']['idus'];

            $Profile = (new ProfileFactory())->makeProfile(['id' => $idus]);
                $this->Profile = $Profile;
                $res = $Profile->getPointRate($idus);
                $res = $Profile->prepareProfileCommonRate($res);
                $data['vac']['rate'] = ['rateval' => $res['pointRate'],
                                'ratenames' => $res['rateNames'],
                                   ];


            if( (int)$data['error'] > 0 ) $data['error'] = 104;
        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                case -104 : // зарезервировано
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry
        return  $data;
    }


    public function getVacancyData()
    {
        $error = '-101';
        try
        {   
            $id = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            list($idus, $profile, $data) = $this->checkAccessToken($accessToken);
            $Vacancy = new Vacancy($profile);
            $data = $Vacancy->getVacancyInfo($id);
            
           } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                default: $error = 101; $message = 'Error get api data';
            }
            $data = ['error' => $error, 'message' => $message];
        } // endtry
        if($data==null){
            return $data = [];
        }
        else
        return $data;
    }
    /**
     * Получаем список моих вакансий
     * @return array
     */
    public function getVacancyOwn()
    {
        $error = '-101';
        try
        {
            $filter = Yii::app()->getRequest()->getParam('filter');
            $filter = $filter ? get_object_vars(json_decode(base64_decode($filter))) : null;
            $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $limit = filter_var(Yii::app()->getRequest()->getParam('limit', MainConfig::$DEF_PAGE_API_LIMIT), FILTER_SANITIZE_NUMBER_INT);

            $limit = $limit > MainConfig::$DEF_PAGE_API_LIMIT ? MainConfig::$DEF_PAGE_API_LIMIT : $limit;
            // проверка токена, получаем профиль
            $this->checkAccessToken($accessToken);
            // получаем данные страницы
            $Vacancy = new Vacancy($this->Profile);
            // results per page
            $pages=new CPagination($Vacancy->getVacanciesCount());
            $pages->pageSize = $limit;
            $pages->applyLimit($Vacancy);


            // отсеивать из ответа вакансии
            $data = $Vacancy->getVacancies()['vacs'];

        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry

        return $data;
    }



    /**
     * Получаем список вакансий c фильтрацией
     * @return array
     */

       public function getVacancy()
    {
        $filter = Yii::app()->getRequest()->getParam('filter');
        $filter = $filter ? get_object_vars(json_decode(base64_decode($filter))) : null;
        $id = filter_var(Yii::app()->getRequest()->getParam('id', 0), FILTER_SANITIZE_NUMBER_INT);
        $limit = filter_var(Yii::app()->getRequest()->getParam('limit', 0), FILTER_SANITIZE_NUMBER_INT);
        // $limit = $limit == 0 || $limit > MainConfig::$DEF_PAGE_API_LIMIT ? MainConfig::$DEF_PAGE_API_LIMIT : $limit;


        // читаем фильтр
        if( $filter )
        {
            // фильтр должностей
            $post = $filter['post'] ? array_combine($filter['post'], $filter['post']) : null;
            // фильтр городов
            $city = $filter['city'] ? array_combine($filter['city'], $filter['city']) : null;
            $sr = $filter['sr'] ?: null;
            $sphf = $filter['sphf'] ?: null;
            $spht = $filter['spht'] ?: null;
            $spwf = $filter['spwf'] ?: null;
            $spwt = $filter['spwt'] ?: null;
            $spmf = $filter['spmf'] ?: null;
            $spmt = $filter['spmt'] ?: null;
            $af = $filter['af'] ?: null;
            $at = $filter['at'] ?: null;
            $sex = $filter['sex'] ?: null;
            $filter = ['filter' => compact('post', 'city', 'sr', 'sphf', 'spht', 'spwf', 'spwt', 'spmf', 'spmt', 'af', 'at', 'sex')];
        }
        else
        {
            $filter = [];
        } // endif


        // получаем данные страницы
        $SearchVac = new SearchVac();
        $pages = new CPagination($SearchVac->searchVacationsCount($filter));
        $pages->pageSize = $limit;
        $pages->applyLimit($SearchVac);

        $data = array_values($SearchVac->getVacations($filter)['vacs']);
        // отсеивать из ответа вакансии
            if(!empty($id))
            {
                $Vacancy = new Vacancy();
                foreach($data as $key => $val){
                $idvac = $data[$key]['id'];
                $data[$key]['response']= $Vacancy->getVacancyViews($idvac, $id)['response']['response'];

                // if($data[$key]['response'] == 0) {
                //     unset($data[$key]);
                // }
             }
        }
    
           $data = array_merge(['vacations' => $data, 'pageCount' => $pages->pageCount]);

        return array_merge($data);
    }


    /**
     * Получаем город
     * @return array
     */
    public function getCity()
    {
        $filter = filter_var(Yii::app()->getRequest()->getParam('filter'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
//        $email = filter_var(Yii::app()->getRequest()->getParam('country'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $limit = filter_var(Yii::app()->getRequest()->getParam('limit', 0), FILTER_SANITIZE_NUMBER_INT);

        $limit = $limit == 0 || $limit > MainConfig::$DEF_PAGE_API_LIMITS ? MainConfig::$DEF_PAGE_API_LIMITS : $limit;

        return (new City())->getCityList(0, $filter, $limit);
 
    }


    /**
     * Инициализация восстановления пароля
     * @return array
     */
    public function restorePass()
    {
        $email = filter_var(Yii::app()->getRequest()->getParam('param'), FILTER_SANITIZE_EMAIL);

        $RestorePass = new RestorePass();
        $res = $RestorePass->passRestoreRequest(compact('email'));
        if( $res['error'] == 1 ) return ['success' => 1] ;
        else return $res;
    }


    /**
     * Получение токена для пользователя
     * @return array
     */
   public function authUser()
    {
        $id = Yii::app()->getRequest()->getParam('id');
        $str = Yii::app()->getRequest()->getParam('url');
        $code = Yii::app()->getRequest()->getParam('code');
        $str = explode(',', $str);
        $strk = '';
        if(count($str) != 1) {
            for($i = 0; $i <count($str); $i ++ ) {
                if($i < (count($str) - 1)) {
                    $strk.= $str[$i].'&'; 
                }
                else {
                    $strk.= $str[$i]; 
                }      
            }
        } 
        else {
            for($i = 0; $i <count($str); $i ++ ) {
                $strk.= $str[$i];       
            }
        }

        if($code == "prommucomWd126wdn"){ // !!!!!!!!!!!!! Здесь стояло присвоение
            $Auth = new Auth();
            $res = $Auth->Authorize(['id' => $id]);

            header("Location: " . Subdomain::getCacheData()->url . "/$strk");
        }
    }



    /**
     * ПОлучаем данные по пользователю
     * $dataTypes - содержит список данных, которые надо отдать
     * @return array
     */
    public function getUserData()
    {
        $error = '-101';
        try
        {
            $this->token = $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $this->idus = $idus = filter_var(Yii::app()->getRequest()->getParam('id'), FILTER_SANITIZE_NUMBER_INT);
            $dataTypes = filter_var(Yii::app()->getRequest()->getParam('dataType'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $dataTypes = explode(',', $dataTypes);


            // ПРоверка домена на актуальность, получение всех данных авторизации
//            $data = (new UserTokens())->getUserTokens(['token' => $accessToken]);
//            unset($data['tokens']['uid']);
//
//            $this->idus = $idus = $data['tokens']['id_user'];
//
//
            if( $accessToken )
            {
                list($idus, $Profile, $data) = $this->checkAccessToken($accessToken, compact('idus'));
            }
            else
            {
                // получаем объект профиля
                $Profile = (new ProfileFactory())->makeProfile(['id' => $idus]);
                $this->Profile = $Profile;
            } // endif
            

            if(!$Profile->error){
            foreach ($dataTypes as $key => $val)
            {
                switch( $val )
                {
                    // получаем данные пользователя
                    case 'profile' : $data['profile'] = $Profile->getProfileDataAPI(['id' => $idus]);
                        // if( !array_values($data['profile']['userAttribs'])[0]['id_attr'] ) $data['profile']['userAttribs'] = [] ;
                        break;
                        
                       

                    // получаем рейтинг пользователя
                    case 'rating' : $res = $Profile->getPointRate($idus);
                            $res = $Profile->prepareProfileCommonRate($res);
                            $data['rate'] = ['rateval' => $res['pointRate'],
                                'ratenames' => $res['rateNames'],
                            ];
                        break;

                    // получаем кол-во комментариев положит. и отрицат.
                    case 'comments' : $res = $Profile->getCommentsCount();
                            $data['comments'] = $res;
//                            $data['rate'] = ['rateval' => $res['pointRate'],
//                                'ratenames' => $res['rateNames'],
//                            ];
                        break;

                    // получаем кол-во новых откликов на вакансии
                    case 'respons-new-count' : $this->hasToken();
                        $data['respons-new-count'] = (new ProfileFactory())->makeProfile(['id' => $data['tokens']['id_user']])->makeResponse()->getNewResponses();
                        break;

                    // получаем кол-во новых сообщений
                    case 'message-new-count' : $this->hasToken();
                        $data['message-new-count'] = (new PushChecker((new ProfileFactory())->makeProfile(['id' => $data['tokens']['id_user']])))->getNewUerMessages();
                        break;
                }
            } // end foreach
        }
        else $data = ['error' => '-101', 'message' => 'Невозможно получить данные пользователя'];

        } catch (Exception $e)
        {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage() ?: 'Token invalid'; break; // token expired
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        } // endtry
    
        
        return $data;
    }



    /**
     * Проверка на правильный POST/GET заголовок
     * @throws ExceptionApi
     */
    private function checkMethodHeader($headerType)
    {

        switch( $headerType )
        {
            case self::$HEADER_POST : $res = Yii::app()->getRequest()->isPostRequest; break;
            case self::$HEADER_GET : $res = !Yii::app()->getRequest()->isPostRequest;
        }

        if( !$res ) throw new ExceptionApi('', -1003);
    }



    /**
     * Получаем токен и профиль пользователя
     * @param $accessToken
     * @param $inProps array : idus - id пользователя для получения профиля
     * @throws ExceptionApi
     */
    private function checkAccessToken($accessToken, $inProps = [])
    {
        // ПРоверка домена на актуальность, получение всех данных авторизации
        $data = (new UserTokens())->getUserTokens(['token' => $accessToken]);
        unset($data['tokens']['uid']);
       
        $idus = $inProps['idus'] ?: $data['tokens']['id_user'];

        // получаем объект профиля
        $this->Profile = $profile = (new ProfileFactory())->makeProfile(['id' => $idus]);
//            return array_merge(compact('idus', 'profile'), ['tokenData' => $data]);
        return array($idus, $profile, $data);
    }

    public function setMess(){

        $error = '-101';
        try
        {
        $accessToken = filter_var(Yii::app()->getRequest()->getParam('access_token'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $message = filter_var(Yii::app()->getRequest()->getParam('m', 0), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idTm =  filter_var(Yii::app()->getRequest()->getParam('tm', 0), FILTER_SANITIZE_NUMBER_INT);
        $lastMessId = filter_var(Yii::app()->getRequest()->getParam('l', 0), FILTER_SANITIZE_NUMBER_INT);
        $new =  filter_var(Yii::app()->getRequest()->getParam('new', 0), FILTER_SANITIZE_NUMBER_INT);
        $vid =  filter_var(Yii::app()->getRequest()->getParam('vid', 0), FILTER_SANITIZE_NUMBER_INT);
        $theme = filter_var(Yii::app()->getRequest()->getParam('t'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        list($idus, $profile, $data) = $this->checkAccessToken($accessToken);
        $message = trim(preg_replace('/&lt;([\/]?(?:div|b|i|br|u))&gt;/i', "<$1>", $message));
        $sql = "SELECT r.status
            FROM user r
            WHERE r.id_user = {$idus}";
        $retRa= Yii::app()->db->createCommand($sql)->queryScalar();

        if( $retRa == 3) 
        {
            if( $new > 0 ) 
            {
                $iduse = $new;
                $props = array(
                'id_usp' => $iduse,
                'id_use' => $idus,
                );

                if( $theme ) $props['title'] = $theme;
                elseif( $vid ) $props['id_vac'] = $vid;

                $res = Yii::app()->db->createCommand()
                ->insert('chat_theme', $props);
                $idTm = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
                $lastMessId = 0;

                if( $vid )
                {
                $sql = "SELECT e.title FROM empl_vacations e WHERE e.id = {$vid}";
                $res = Yii::app()->db->createCommand($sql);
                $theme = $res->queryScalar();
                }
            }
            else
            {
            } // endif
        
            $sql = "SELECT ca.id_usp idusp, ca.id_use iduse
                FROM chat ca 
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$idTm}
                LIMIT 1";
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryRow();
            $idus == $res['iduse'] ? $ids = $res : $ids = false;

            if( $new || $ids)
            {
                $res = Yii::app()->db->createCommand()
                    ->insert('chat', array(
                        'id_theme' => $idTm,
                        'id_usp' => $ids['idusp']?: $iduse,
                        'id_use' => $idus,
                        'message' => $message,
                        'is_resp' => 1,
                        'is_read' => 0,
                        'crdate' => date("Y-m-d H:i:s"),
                    ));
            }
            $sql = "SELECT ca.id, ca.message, ca.is_resp isresp, ca.is_read isread, ca.id_usp idusp, ca.id_use iduse, ca.files
                  , e.name namefrom
                  , CONCAT(r.firstname, ' ', r.lastname) nameto
                  , DATE_FORMAT(ca.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(ca.crdate, '%H:%i:%s') crtime
                FROM chat ca
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$idTm}
                  AND ca.id > {$lastMessId}";
            $res = Yii::app()->db->createCommand($sql);
            $data['messages'] = $res->queryAll();
            $data = array_merge($data['messages'], array('theme' => $theme, 'idtm' => $idTm));

            $res = Yii::app()->db->createCommand()
            ->update('chat', array(
                'is_read' => 1,
            ), 'id_theme = :id_theme AND is_resp = 0 AND is_read = 0', array(':id_theme' => $idTm));
            $chat = $idTm;
            $ids = $ids['idusp']?: $iduse;
            $sql = "SELECT r.new_mess
            FROM push_config r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryScalar(); 
            if($res == 2) {
            $message = array(
            'title' => 'Prommu',
            'body' => 'Новое сообщение',
            'click_action' => 'NEW_PUSH_ACTION',
            );
            $datas = array(
            'action' => 'new_message',
            'chat_id' => $chat,
            ); 
            $figaro = compact('ids', 'chat', 'datas', 'message');
            $service = $this->getPush($figaro);
        }
        }
        elseif( $retRa == 2)
        {
            if( $new > 0 )
                {
                    $iduse = $new;
                    $props = array(
                    'id_usp' => $idus,
                    'id_use' => $iduse,
                    );
                if( $theme ) $props['title'] = $theme;
                elseif( $vid ) $props['id_vac'] = $vid;

                $res = Yii::app()->db->createCommand()
                ->insert('chat_theme', $props);
                $idTm = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
                $lastMessId = 0;

                if( $vid )
                {
                $sql = "SELECT e.title FROM empl_vacations e WHERE e.id = {$vid}";
                $res = Yii::app()->db->createCommand($sql);
                $theme = $res->queryScalar();
                }
            }
            else
            {
            } 
        
                $sql = "SELECT ca.id_usp idusp, ca.id_use iduse
                FROM chat ca 
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$idTm}
                LIMIT 1";
                $res = Yii::app()->db->createCommand($sql);
                $res = $res->queryRow();
                $idus == $res['idusp'] ? $ids = $res : $ids = false;

       
                if( $new || $ids)
                {
                    $res = Yii::app()->db->createCommand()
                    ->insert('chat', array(
                        'id_theme' => $idTm,
                        'id_usp' => $idus,
                        'id_use' => $ids['iduse']?: $iduse,
                        'message' => $message,
                        'is_resp' => 0,
                        'is_read' => 0,
                        'crdate' => date("Y-m-d H:i:s"),
                    ));
                }
                $sql = "SELECT ca.id, ca.message, ca.is_resp isresp, ca.is_read isread, ca.id_usp idusp, ca.id_use iduse, ca.files
                  , e.name namefrom
                  , CONCAT(r.firstname, ' ', r.lastname) nameto
                  , DATE_FORMAT(ca.crdate, '%d.%m.%Y') crdate, DATE_FORMAT(ca.crdate, '%H:%i:%s') crtime
                FROM chat ca
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$idTm}
                  AND ca.id > {$lastMessId}";
            $res = Yii::app()->db->createCommand($sql);
            $data['messages'] = $res->queryAll();
            $data = array_merge($data['messages'], array('theme' => $theme, 'idtm' => $idTm));

            $res = Yii::app()->db->createCommand()
            ->update('chat', array(
                'is_read' => 1,
            ), 'id_theme = :id_theme AND is_resp = 1 AND is_read = 0', array(':id_theme' => $idTm));

            $chat = $idTm;
            $ids = $ids['iduse']?: $iduse;
            $sql = "SELECT r.new_mess
            FROM push_config r
            WHERE r.id = {$ids}";
            $res = Yii::app()->db->createCommand($sql)->queryScalar(); 
            if($res == 2) {
            $message = array(
            'title' => 'Prommu',
            'body' => 'Новое сообщение',
            'click_action' => 'NEW_PUSH_ACTION',
            );
            $datas = array(
            'action' => 'new_message',
            'chat_id' => $chat,
            ); 
            $figaro = compact('ids', 'chat', 'datas', 'message');
            $service = $this->getPush($figaro);
                }
            }
        
     } catch (Exception $e)
            {
            $error = abs($e->getCode());
            switch( $e->getCode() )
            {
                case -102 : // token invalid
                case -103 : $message = $e->getMessage(); break; // token expired
                case -104 : $message = 'Error while getting chat data'; break;
                default: $error = 101; $message = 'Error get api data';
            }

            $data = ['error' => $error, 'message' => $message];
        }
        return $data = ['error' => '0', 'message' => 'Telegram  sent'];   
                  
    }

    /**
     * Проверяем токен
     * @param $accessToken
     * @throws ExceptionApi
     */
    private function hasToken()
    {
        if( $this->token ) return true;
        else throw new ExceptionApi('', -102);
    }
}