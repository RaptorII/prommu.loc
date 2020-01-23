<?php

/**
 * Используется для поиска соискателей
 */
class SearchPromo extends Model
{
    public function getPromos($arAllId, $isEmplOnly = 0,$props = [])
    {
        $filter = $this->renderSQLFilter(['filter' => $props['filter']]);

        $data = $this->searchPromos($arAllId, $filter);
        if( !$isEmplOnly ) $data = array_merge($data, $this->getFilterData());

        return $data;
    }
    
    public function getPromosAPI($arAllId, $isEmplOnly = 0,$props = [])
    {
        $filter = $this->renderSQLFilterAPI(['filter' => $props['filter']]);

        $data = $this->searchPromosAPI($arAllId,$filter);
        if( !$isEmplOnly ) $data = array_merge($data, $this->getFilterData());

        return $data;
    }


    
    private function searchPromosAPI($arAllId, $filter)
    {
        $limit = $this->limit;
        $offset = $this->offset;
        $arId = array();
        // находим нужные ID по пагинации
        
        for( $i=$offset, $n=sizeof($arAllId); $i<$n; $i++ )
            ( $i < ($offset + $limit) ) && $arId[] = $arAllId[$i];


        try {
            $res = (new Promo())->getApplicantsQueries(array('page' => 'searchpromo', 'table' => $filter['table'], 'filter' => $filter['filter'], 'offset' => $offset, 'limit' => $limit,'arId' => $arId));

        }
        catch (Exception $e) {
            return array('error' => $e->getMessage());
        } // endtry
        
        // var_dump($res);
        $data['promo'] = array();
        foreach ($res as $key => $val)
        {
            // if( !isset($data['promo'][$val['id']])) $data['promo'][$val['id']] = array('city' => array(), 'posts' => array()) ;
            
            
                
            
            ///attribs
            $data['promo'][$val['id']]['id'] = (int)$val['id'];
            $data['promo'][$val['id']]['id_user'] = (int)$val['id_user'];
            
            $data['promo'][$val['id']]['first_name'] = $val['firstname'];
            $data['promo'][$val['id']]['last_name'] = $val['lastname'];
            $data['promo'][$val['id']]['photo'] = $val['photo'] ? "https://files.prommu.com/users/".$val['id_user']."/".$val['photo'].".jpg" : NULL;
            
             ///owner
            $data['promo'][$val['id']]['birthday'] = $val['birthday'];
            $data['promo'][$val['id']]['age'] = (int)$val['age'];
            $data['promo'][$val['id']]['projects'] = (int)$val['projects'];
            
            ///city
            $data['promo'][$val['id']]['city']['id'] = (int)$res[$val['id_user']]['city']['id'];
            $data['promo'][$val['id']]['city']['name'] = $res[$val['id_user']]['city']['name'];
            
            $data['promo'][$val['id']]['metro'] = $res[$val['id_user']]['metroes'];
            ///
            
            $data['promo'][$val['id']]['post'] = $res[$val['id_user']]['post'];
            
            
            $data['promo'][$val['id']]['is_man'] = (boolean)$val['isman'];
            $data['promo'][$val['id']]['is_med'] = (boolean)$val['ismed'];
            $data['promo'][$val['id']]['is_hasavto'] = (boolean)$val['ishasavto'];
            $data['promo'][$val['id']]['smart'] = (boolean)$val['smart'];
            $data['promo'][$val['id']]['card'] = (boolean)$val['card'];
            $data['promo'][$val['id']]['card_prommu'] = (boolean)$val['card_prommu'];
            
            $data['promo'][$val['id']]['rate']['positive_rate'] = (int)$val['rate'];
            $data['promo'][$val['id']]['rate']['negative_rate'] = (int)$val['rate_neg'];
            ///
            $data['promo'][$val['id']]['response']['positive_response'] = 0;
            $data['promo'][$val['id']]['response']['negative_response'] = 0;
            
            $data['empls'][$val['id']]['rate_sum'] = (int)$val['rate'] - (int)$val['rate_neg'];
            // ///posts
            // $data['vacs'][$val['id']]['posts']['id'] = (int)$val['id_attr'];
            // $data['vacs'][$val['id']]['posts']['name'] = $val['pname'];
            // ///
            
            $data['promo'][$val['id']]['is_online'] = (boolean)$val['is_online'];
            
            $data['promo'][$val['id']]['created_at'] = $val['date_public'];
            $data['promo'][$val['id']]['moder_at'] = $val['mdates'];
            
            
        
            
            //
          
            // if( $val['mid'] ) $data['vacs'][$val['id']]['metroes'][$val['mid']] = $val['mname'];
            // $data['vacs'][$val['id']] = array_merge($data['vacs'][$val['id']], $val);
        
        } // end foreach

        $i = 1;
        $ret['promo'] = array();
        foreach ($data['promo'] as $key => $val) { $ret['promo'][$i] = $val; $i++; }

        return $ret;


        return $arRes;
    }


    // поиск соискателей на главной
    public function searchApplicants()
    {
        $searchWord = filter_var(Yii::app()->getRequest()->getParam('search'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if( strlen($searchWord) > 2 )
        {
            $sql = "SELECT r.id, r.id_user,
                     r.firstname,
                     r.lastname
                FROM (
                  SELECT r.id, r.id_user, r.firstname, r.lastname FROM resume r, user u 
                  WHERE r.id_user = u.id_user 
                    AND " . User::getScopesCustom(User::$SCOPE_ACTIVE, 'u') . " 
                    AND r.lastname LIKE '{$searchWord}%' 
                  ORDER BY r.id DESC LIMIT 10
                ) r
                ORDER BY lastname, firstname
            ";
            /** @var $res CDbCommand */
            $res = Yii::app()->db->createCommand($sql);
            $res = $res->queryAll();

            $obj = (object)[];
            foreach ($res as $key => &$val)
            {
                $obj->name = $val['firstname'] . ' ' . $val['lastname'];
                $obj->code = $val['id_user'];
                $val = clone $obj;
            } // end foreach
        }
        else
        {
            $res = array();
        } // endif

        return $res;
    }



    public function searchPromosCount($props = [])
    {
        $filter = $this->renderSQLFilter(['filter' => $props['filter']]);
        $sql = "SELECT DISTINCT r.id_user
                FROM resume r
                INNER JOIN user u ON u.id_user = r.id_user 
                INNER JOIN user_city uc ON r.id_user = uc.id_user  
                    {$filter['table']}
                INNER JOIN user_mech a ON a.id_us = r.id_user
                INNER JOIN user_attribs ua ON ua.id_us = r.id_user
                    {$filter['filter']}
                ORDER BY r.mdate DESC ";
        /** @var $res CDbCommand */
        $query = Yii::app()->db->createCommand($sql)->queryColumn();

        return $query;
    }
    
    public function searchPromosCountAPI($props = [])
    {
        $filter = $this->renderSQLFilter(['filter' => $props['filter']]);
        $sql = "SELECT COUNT(DISTINCT r.id_user)
                FROM resume r
                INNER JOIN user u ON u.id_user = r.id_user 
                INNER JOIN user_city uc ON r.id_user = uc.id_user  
                    {$filter['table']}
                INNER JOIN user_mech a ON a.id_us = r.id_user
                    {$filter['filter']}
                ORDER BY r.mdate DESC ";
        /** @var $res CDbCommand */
        $query = Yii::app()->db->createCommand($sql)->queryScalar();

        return $query;
    }


    // получение данных для фильтра
    private function getFilterData($inData = array())
    {
        // если были ввдедены значения
        $data['qs'] = filter_var(Yii::app()->getRequest()->getParam('qs'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cities = Yii::app()->getRequest()->getParam('cities');
        $metro = Yii::app()->getRequest()->getParam('metro');
        $posts = Yii::app()->getRequest()->getParam('posts');
        $sm = Yii::app()->getRequest()->getParam('sm');
        $sf = Yii::app()->getRequest()->getParam('sf');
        $ph = Yii::app()->getRequest()->getParam('ph');
        $mb = Yii::app()->getRequest()->getParam('mb');
          // salary
        $data['sr'] = filter_var(Yii::app()->getRequest()->getParam('sr'), FILTER_SANITIZE_NUMBER_INT);
        $data['sphf'] = filter_var(Yii::app()->getRequest()->getParam('sphf'), FILTER_SANITIZE_NUMBER_FLOAT);
        $data['spht'] = filter_var(Yii::app()->getRequest()->getParam('spht'), FILTER_SANITIZE_NUMBER_FLOAT);
        $data['spwf'] = filter_var(Yii::app()->getRequest()->getParam('spwf'), FILTER_SANITIZE_NUMBER_FLOAT);
        $data['spwt'] = filter_var(Yii::app()->getRequest()->getParam('spwt'), FILTER_SANITIZE_NUMBER_FLOAT);
        $data['spmf'] = filter_var(Yii::app()->getRequest()->getParam('spmf'), FILTER_SANITIZE_NUMBER_FLOAT);
        $data['spmt'] = filter_var(Yii::app()->getRequest()->getParam('spmt'), FILTER_SANITIZE_NUMBER_FLOAT);
        $data['spvf'] = filter_var(Yii::app()->getRequest()->getParam('spvf'), FILTER_SANITIZE_NUMBER_FLOAT);
        $data['spvt'] = filter_var(Yii::app()->getRequest()->getParam('spvt'), FILTER_SANITIZE_NUMBER_FLOAT);


       
        $sr = Yii::app()->getRequest()->getParam('sr');
        $type = Yii::app()->getRequest()->getParam('type');
        $avto = Yii::app()->getRequest()->getParam('avto');
        $smart = Yii::app()->getRequest()->getParam('smart');
        $card = Yii::app()->getRequest()->getParam('card');
        $cardPrommu = Yii::app()->getRequest()->getParam('cardPrommu');

        // читаем города
        $data['city'] = array();
        if( $cities )
        {
            $sql = "SELECT ci.id_city id, ci.name, ci.ismetro FROM city ci 
                WHERE ci.id_city IN(".join(',',$cities).") 
                ORDER BY name";
            $data['city'] = Yii::app()->db->createCommand($sql)->queryAll();          
        } // endif



        // читаем должности
        $sql = "SELECT d.id, d.id_par, d.type, d.name, d.postself
            FROM user_attr_dict d
            WHERE d.id_par = 110
            ORDER BY d.name
            ";
        $data['posts'] = Yii::app()->db->createCommand($sql)->queryAll();
        if( $posts )
            foreach ($data['posts'] as $key => &$val)
            {
                if( in_array($val['id'], $posts) ) $val['selected'] = 1;
            } // end foreach

        return $data;
    }



    private function searchCity($inQS)
    {
        $sql = "SELECT id_city id FROM city WHERE name LIKE '{$inQS}%' ORDER BY name";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);

        return $res->queryAll();
    }



    private function renderSQLFilter($inProps = [])
    {
      $data = array();
      $rq = Yii::app()->getRequest();
      $arFilter = $inProps['filter'];
      $filter = ["u.ismoder = 1 AND u.isblocked = 0"];
      $tables = [];

        // города
        if( $rq->getParam('cities') || $arFilter['cities'] ) $data['cities'] = $arFilter['cities'] ?: $rq->getParam('cities');
        // posts
        if($rq->getParam('posts') || $arFilter['posts'] ) $data['posts'] = $arFilter['posts'] ?: $rq->getParam('posts');
        // sex
        if( $rq->getParam('sm') || $arFilter['sm']  ) $data['sm'] = $arFilter['sm'] ?: $rq->getParam('sm');
        if( $rq->getParam('ph') || $arFilter['ph']  ) $data['ph'] = $arFilter['ph'] ?: $rq->getParam('ph');

        if( $rq->getParam('sf') || $arFilter['sf']  ) $data['sf'] = $arFilter['sf'] ?: $rq->getParam('sf');
        // medbook n avto
        if( $rq->getParam('mb') || $arFilter['mb']  ) $data['mb'] = $arFilter['mb'] ?: $rq->getParam('mb');
        if( $rq->getParam('avto') || $arFilter['avto']  ) $data['avto'] = $arFilter['avto'] ?: $rq->getParam('avto');
         if( $rq->getParam('smart') || $arFilter['smart']  ) $data['smart'] = $arFilter['smart'] ?: $rq->getParam('smart');
        if( $rq->getParam('card') || $arFilter['card']  ) $data['card'] = $arFilter['card'] ?: $rq->getParam('card');
        if( $rq->getParam('cardPrommu') || $arFilter['cardPrommu']  ) $data['cardPrommu'] = $arFilter['cardPrommu'] ?: $rq->getParam('cardPrommu');
        // self_employed
        $rq->getParam('self_employed') && $data['self_employed'] = $rq->getParam('self_employed');
        $arFilter['self_employed'] && $data['self_employed'] = $arFilter['self_employed'];
        // Quick search название города или имя,фамилия
        if( $sq = $rq->getParam('qs')){  $data['qs'] = trim(filter_var($sq, FILTER_SANITIZE_FULL_SPECIAL_CHARS));}
        elseif($arFilter['qs']) {
            $data['qs'] = $arFilter['qs'];
        }
        $salradio = filter_var($rq->getParam('sr', $arFilter['sr'] ?: 0), FILTER_SANITIZE_NUMBER_INT);
        if( $salradio == 1 && (($int = filter_var($rq->getParam('sphf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['sphf'])) ) $data['salHourF'] = $int;
        if( $salradio == 1 && (($int = filter_var($rq->getParam('spht'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spht'])) ) $data['salHourT'] = $int;
        if( $salradio == 2 && (($int = filter_var($rq->getParam('spwf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spwf'])) ) $data['salWeekF'] = $int;
        if( $salradio == 2 && (($int = filter_var($rq->getParam('spwt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spwt'])) ) $data['salWeekT'] = $int;
        if( $salradio == 3 && (($int = filter_var($rq->getParam('spmf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spmf'])) ) $data['salMonF'] = $int;
        if( $salradio == 3 && (($int = filter_var($rq->getParam('spmt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spmt'])) ) $data['salMonT'] = $int;
        if( $salradio == 4 && (($int = filter_var($rq->getParam('spvf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spvf'])) ) $data['salVisitF'] = $int;
        if( $salradio == 4 && (($int = filter_var($rq->getParam('spvt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spvt'])) ) $data['salVisitT'] = $int;

        // age
        if($rq->getParam('af') || $arFilter['af'])
            $data['ageFrom'] = $arFilter['af'] ?: $rq->getParam('af');
        if($rq->getParam('at') || $arFilter['at'])
            $data['ageTo'] = $arFilter['at'] ?: $rq->getParam('at');

        // quick search
        if( !empty($data['qs']) ) {
            // фильтр по фио
            $filter[] = "r.lastname like '".$data['qs']."%'";
        }

        // city filter
        if( !empty($data['cities']) )
        {
            $filter[] = 'uc.id_city IN ('.join(',',$data['cities']).')';
        }
        else
        {
            $filter[] = 'uc.id_city IN ('.Subdomain::getCacheData()->strCitiesIdes.')';
        }

        if( !empty($data['ph']) )
        {
            $filter[] = "ua.key = 'mob' AND ua.val <> 0";
        }

        // posts
        if( !empty($data['posts']) )
        {
            $filter[] = 'a.id_mech IN ('.join(',',$data['posts']).')';
        }
        
        if( isset($data['salHourF']) ) $filter[] = "a.pay >= {$data['salHourF']}";
        if( isset($data['salHourT']) ) $filter[] = "a.pay <= {$data['salHourT']}";
        if( isset($data['salWeekF']) ) $filter[] = "a.pay >= {$data['salWeekF']}";
        if( isset($data['salWeekT']) ) $filter[] = "a.pay <= {$data['salWeekT']}";
        if( isset($data['salMonF']) ) $filter[] = "a.pay >= {$data['salMonF']}";
        if( isset($data['salMonT']) ) $filter[] = "a.pay <= {$data['salMonT']}";
        if( isset($data['salVisitF']) ) $filter[] = "a.pay >= {$data['salVisitF']}";
        if( isset($data['salVisitT']) ) $filter[] = "a.pay >= {$data['salVisitT']}";
        if( $data['salHourF'] > 0 ||  $data['salHourT'] > 0 || $data['salWeekF'] > 0|| $data['salWeekT']> 0 || 
       $data['salMonF']> 0 ||$data['salMonT']> 0 || $data['salVisitF']> 0 || $data['salHourF']> 0 ){
            $type = (int)$salradio-1;
            $filter[] = "a.id_attr = 0 AND a.isshow = 0 AND a.pay_type = {$type}";
        }
         
        // sex
        if( !empty($data['sf']) && empty($data['sm']) || empty($data['sf']) && !empty($data['sm']) )
        {
            $filter[] = 'r.isman = ' . ($data['sm'] ? '1' : '0');
        }

        if( isset($data['ageFrom']) && $data['ageFrom']>0 )
            $filter[] = "( 
                ( YEAR(CURRENT_DATE) - YEAR(r.birthday) ) - ( DATE_FORMAT(CURRENT_DATE, '%m%d') < DATE_FORMAT(r.birthday, '%m%d') )
            ) >= {$data['ageFrom']}";
        if( isset($data['ageTo']) && $data['ageTo']>0 )
            $filter[] = "( 
                ( YEAR(CURRENT_DATE) - YEAR(r.birthday) ) - ( DATE_FORMAT(CURRENT_DATE, '%m%d') < DATE_FORMAT(r.birthday, '%m%d') )
            ) <= {$data['ageTo']}";

        // medbook
        if( !empty($data['mb']) )
        {
            $filter[] = ' r.ismed = 1';
        }

        // avto
        if( !empty($data['avto']) )
        {
            $filter[] = ' r.ishasavto = 1';
        }

        if( !empty($data['smart']) )
        {
            $filter[] = ' r.smart = 1';
        }

        if( !empty($data['card']) )
        {
            $filter[] = ' r.card = 1';
        }

        if( !empty($data['cardPrommu']) )
        {
            $filter[] = ' r.cardPrommu = 1';
        }
      // self_employed
      !empty($data['self_employed']) && $filter[]=" (ua.key='self_employed' AND ua.val IS NOT NULL)";

      $filter = count($filter) ? 'WHERE ' . join(' and ', $filter) : '';

      return array('filter' => $filter, 'table' => join(' ', $tables));
    }

    private function renderSQLFilterAPI($inProps = [])
    {
      $data = array();
      $rq = Yii::app()->getRequest();
      $arFilter = $inProps['filter'];
      $filter = ["u.ismoder = 1 AND u.isblocked = 0"];
      $tables = [];

        // города
        if( $rq->getParam('cities') || $arFilter['cities'] ) $data['cities'] = $arFilter['cities'] ?: $rq->getParam('cities');
        // posts
        if($rq->getParam('posts') || $arFilter['posts'] ) $data['posts'] = $arFilter['posts'] ?: $rq->getParam('posts');
        // sex
        if( $rq->getParam('sm') || $arFilter['sm']  ) $data['sm'] = $arFilter['sm'] ?: $rq->getParam('sm');
        if( $rq->getParam('ph') || $arFilter['ph']  ) $data['ph'] = $arFilter['ph'] ?: $rq->getParam('ph');

        if( $rq->getParam('sf') || $arFilter['sf']  ) $data['sf'] = $arFilter['sf'] ?: $rq->getParam('sf');
        // medbook n avto
        if( $rq->getParam('mb') || $arFilter['mb']  ) $data['mb'] = $arFilter['mb'] ?: $rq->getParam('mb');
        if( $rq->getParam('avto') || $arFilter['avto']  ) $data['avto'] = $arFilter['avto'] ?: $rq->getParam('avto');
         if( $rq->getParam('smart') || $arFilter['smart']  ) $data['smart'] = $arFilter['smart'] ?: $rq->getParam('smart');
        if( $rq->getParam('card') || $arFilter['card']  ) $data['card'] = $arFilter['card'] ?: $rq->getParam('card');
        if( $rq->getParam('cardPrommu') || $arFilter['cardPrommu']  ) $data['cardPrommu'] = $arFilter['cardPrommu'] ?: $rq->getParam('cardPrommu');
        // self_employed
        $rq->getParam('self_employed') && $data['self_employed'] = $rq->getParam('self_employed');
        $arFilter['self_employed'] && $data['self_employed'] = $arFilter['self_employed'];
        // Quick search название города или имя,фамилия
        if( $sq = $rq->getParam('qs')){  $data['qs'] = trim(filter_var($sq, FILTER_SANITIZE_FULL_SPECIAL_CHARS));}
        elseif($arFilter['qs']) {
            $data['qs'] = $arFilter['qs'];
        }
        $salradio = filter_var($rq->getParam('sr', $arFilter['sr'] ?: 0), FILTER_SANITIZE_NUMBER_INT);
        if( $salradio == 1 && (($int = filter_var($rq->getParam('sphf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['sphf'])) ) $data['salHourF'] = $int;
        if( $salradio == 1 && (($int = filter_var($rq->getParam('spht'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spht'])) ) $data['salHourT'] = $int;
        if( $salradio == 2 && (($int = filter_var($rq->getParam('spwf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spwf'])) ) $data['salWeekF'] = $int;
        if( $salradio == 2 && (($int = filter_var($rq->getParam('spwt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spwt'])) ) $data['salWeekT'] = $int;
        if( $salradio == 3 && (($int = filter_var($rq->getParam('spmf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spmf'])) ) $data['salMonF'] = $int;
        if( $salradio == 3 && (($int = filter_var($rq->getParam('spmt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spmt'])) ) $data['salMonT'] = $int;
        if( $salradio == 4 && (($int = filter_var($rq->getParam('spvf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spvf'])) ) $data['salVisitF'] = $int;
        if( $salradio == 4 && (($int = filter_var($rq->getParam('spvt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $arFilter['spvt'])) ) $data['salVisitT'] = $int;

        // age
        if($rq->getParam('af') || $arFilter['af'])
            $data['ageFrom'] = $arFilter['af'] ?: $rq->getParam('af');
        if($rq->getParam('at') || $arFilter['at'])
            $data['ageTo'] = $arFilter['at'] ?: $rq->getParam('at');

        // quick search
        if( !empty($data['qs']) ) {
            // фильтр по фио
            $filter[] = "r.lastname like '".$data['qs']."%'";
        }

        // city filter
        if( !empty($data['cities']) )
        {
            $filter[] = 'uc.id_city IN ('.join(',',$data['cities']).')';
        }
        

        if( !empty($data['ph']) )
        {
            $filter[] = "ua.key = 'mob' AND ua.val <> 0";
        }

        // posts
        if( !empty($data['posts']) )
        {
            $filter[] = 'a.id_mech IN ('.join(',',$data['posts']).')';
        }
        
        if( isset($data['salHourF']) ) $filter[] = "a.pay >= {$data['salHourF']}";
        if( isset($data['salHourT']) ) $filter[] = "a.pay <= {$data['salHourT']}";
        if( isset($data['salWeekF']) ) $filter[] = "a.pay >= {$data['salWeekF']}";
        if( isset($data['salWeekT']) ) $filter[] = "a.pay <= {$data['salWeekT']}";
        if( isset($data['salMonF']) ) $filter[] = "a.pay >= {$data['salMonF']}";
        if( isset($data['salMonT']) ) $filter[] = "a.pay <= {$data['salMonT']}";
        if( isset($data['salVisitF']) ) $filter[] = "a.pay >= {$data['salVisitF']}";
        if( isset($data['salVisitT']) ) $filter[] = "a.pay >= {$data['salVisitT']}";
        if( $data['salHourF'] > 0 ||  $data['salHourT'] > 0 || $data['salWeekF'] > 0|| $data['salWeekT']> 0 || 
       $data['salMonF']> 0 ||$data['salMonT']> 0 || $data['salVisitF']> 0 || $data['salHourF']> 0 ){
            $type = (int)$salradio-1;
            $filter[] = "a.id_attr = 0 AND a.isshow = 0 AND a.pay_type = {$type}";
        }
         
        // sex
        if( !empty($data['sf']) && empty($data['sm']) || empty($data['sf']) && !empty($data['sm']) )
        {
            $filter[] = 'r.isman = ' . ($data['sm'] ? '1' : '0');
        }

        if( isset($data['ageFrom']) && $data['ageFrom']>0 )
            $filter[] = "( 
                ( YEAR(CURRENT_DATE) - YEAR(r.birthday) ) - ( DATE_FORMAT(CURRENT_DATE, '%m%d') < DATE_FORMAT(r.birthday, '%m%d') )
            ) >= {$data['ageFrom']}";
        if( isset($data['ageTo']) && $data['ageTo']>0 )
            $filter[] = "( 
                ( YEAR(CURRENT_DATE) - YEAR(r.birthday) ) - ( DATE_FORMAT(CURRENT_DATE, '%m%d') < DATE_FORMAT(r.birthday, '%m%d') )
            ) <= {$data['ageTo']}";

        // medbook
        if( !empty($data['mb']) )
        {
            $filter[] = ' r.ismed = 1';
        }

        // avto
        if( !empty($data['avto']) )
        {
            $filter[] = ' r.ishasavto = 1';
        }

        if( !empty($data['smart']) )
        {
            $filter[] = ' r.smart = 1';
        }

        if( !empty($data['card']) )
        {
            $filter[] = ' r.card = 1';
        }

        if( !empty($data['cardPrommu']) )
        {
            $filter[] = ' r.cardPrommu = 1';
        }
      // self_employed
      !empty($data['self_employed']) && $filter[]=" (ua.key='self_employed' AND ua.val IS NOT NULL)";

      $filter = count($filter) ? 'WHERE ' . join(' and ', $filter) : '';
    
        $res = array('filter' => $filter, 'table' => join(' ', $tables));
        var_dump($res);
      return $res;
    }

    private function searchPromos($arAllId, $filter)
    {
        $limit = $this->limit;
        $offset = $this->offset;
        $arRes['promos'] = $arAllId;
        $arId = array();
        // находим нужные ID по пагинации
        for( $i=$offset, $n=sizeof($arAllId); $i<$n; $i++ )
            ( $i < ($offset + $limit) ) && $arId[] = $arAllId[$i];

        try {
            $arPromo = (new Promo())->getApplicantsQueries(
                array(
                    'page' => 'searchpromo', 
                    'table' => $filter['table'], 
                    'filter' => $filter['filter'], 
                    'offset' => $offset, 
                    'limit' => $limit,
                    'arId' => $arId
                )
            );

            $i = 1;
            foreach ($arPromo as $p) {
                $arRes['promo'][$i] = $p;
                $i++; 
            }
        }
        catch (Exception $e) {
            return array('error' => $e->getMessage());
        } // endtry

        return $arRes;
    }

    public function buildPrettyUrl($data)
    {
        $url = array();
        // $this->updateSeoValues();
        $cnt = 0;
        $hasPost = false;

        // должности
        if(isset($data['posts']) && is_array($data['posts']) && sizeof($data['posts']))
        {
            $posts = array();
            foreach($data['posts'] as $post_id)
            {
                $post = $this->getOccupationByField('id', $post_id);
                if($post && $post['comment']){
                    $posts[] = $post['comment'];
                    $cnt++;
                    $hasPost = true;
                }
            }
            if(sizeof($posts))
                $url[] = implode(',', $posts);
        }

        // города
        if(isset($data['cities']) && is_array($data['cities']) && sizeof($data['cities']))
        {
            $cities = array();
            foreach($data['cities'] as $city_id)
            {
                $city = $this->getCityByField('id_city', $city_id);
                if($city && $city['seo_url']){
                    $cities[] = $city['seo_url'];
                    $cnt++;
                }
            }
            if(sizeof($cities))
                $url[] = implode(',', $cities);
        }

        // метро
        if(isset($data['metro']) && is_array($data['metro']) && sizeof($data['metro']))
        {
            $cities = array();
            foreach($data['metro'] as $city_id)
            {
                var_dump($city_id);
                $city = $this->getMetroByField('id', $city_id);
                if($city && $city['seo_url']){
                    $cities[] = $city['seo_url'];
                    $cnt++;
                }
            }

            if(sizeof($cities))
                $url[] = implode(',', $cities);
        }

        // фамилия
        if(isset($data['qs']) && trim($data['qs']) != '')
        {
            $url[] = 'name='.$data['qs'];
            $cnt++;
        }

        // sex ?
        if(isset($data['sm']))
        {
            $url[] = 'male';
            $cnt++;
        }

        if(isset($data['sf']))
        {
            $url[] = 'female';
            $cnt++;
        }

        if(isset($data['ph']))
        {
            $url[] = 'ph';
            $cnt++;
        }

        // медкнижка
        if(isset($data['mb']))
        {
            $url[] = 'medbook';
            $cnt++;
        }
        
          // salary per hour
        if(isset($data['sphf']) && isset($data['spht']) && (int)$data['sphf'] > 0 && (int)$data['spht'] > 0 && (int)$data['sr'] == 1)
        {
            $url[] = 'salary-hour='.(int)$data['sphf'].','.(int)$data['spht'];
            $cnt++;
        }

        // salary per week
        if(isset($data['spwf']) && isset($data['spwt']) && (int)$data['spwf'] > 0 && (int)$data['spwt'] > 0 && (int)$data['sr'] == 2)
        {
            $url[] = 'salary-week='.(int)$data['spwf'].','.(int)$data['spwt'];
            $cnt++;
        }

        // salary per month
        if(isset($data['spmf']) && isset($data['spmt']) && (int)$data['spmf'] > 0 && (int)$data['spmt'] > 0 && (int)$data['sr'] == 3)
        {
            $url[] = 'salary-month='.(int)$data['spmf'].','.(int)$data['spmt'];
            $cnt++;
        }

        // salary per visit
        if(isset($data['spvf']) && isset($data['spvt']) && (int)$data['spvf'] > 0 && (int)$data['spvt'] > 0 && (int)$data['sr'] == 4)
        {
            $url[] = 'salary-visit='.(int)$data['spvf'].','.(int)$data['spvt'];
            $cnt++;
        }

        // age
        if((isset($data['af']) && (int)$data['af'] > 0) || (isset($data['at']) && (int)$data['at'] > 0))
        {
            $url[] = 'age='.(int)$data['af'].'-'.(int)$data['at'];
            $cnt++;
        }

        // наличие автомобиля
        if(isset($data['avto']))
        {
            $url[] = 'car';
            $cnt++;
        }

        if(isset($data['smart']))
        {
            $url[] = 'smart';
            $cnt++;
        }

        if(isset($data['card']))
        {
            $url[] = 'card';
            $cnt++;
        }

        if(isset($data['cardPrommu']))
        {
            $url[] = 'cardprommu';
            $cnt++;
        }

        if(isset($data['self_employed']) && $data['self_employed']==1)
        {
            $url[] = 'self_employed';
            $cnt++;
        }
        // pages
        if(isset($data['page']))
        {
            $url[] = 'page='.$data['page'];
            $cnt++;
        }

        if(!$cnt) $str = '/ankety';
        elseif($cnt==1 && $hasPost) $str = '/ankety/';
        else $str = '/ankety/?';

        return $str . implode('/', $url);
    }

    /**
     * Получение списка должностей
     */
    public function getOccupations()
    {
        $sql = "SELECT
                    d.id,
                    d.id_par,
                    d.type,
                    d.name
                FROM
                    user_attr_dict d
                WHERE
                    d.id_par = 110
                    AND d.key <> 'custpo'
                ORDER BY
                    d.npp, d.name
        ";

        $occupations = Yii::app()->db->createCommand($sql)->queryAll();

        return $occupations;
    }

    /**
     * Получение списка городов
     */
    public function getCities()
    {
        $sql = "SELECT
                    ci.id_city id, ci.name, ci.seo_url
                FROM
                    city ci 
                ORDER BY
                    name
        ";

        $cities = Yii::app()->db->createCommand($sql)->queryAll();

        return $cities;
    }

    /**
     * Получение списка станций метро
     */
    public function getMetros()
    {
        $sql = "SELECT
                    *
                FROM
                    metro ci 
                ORDER BY
                    name
        ";

        $cities = Yii::app()->db->createCommand($sql)->queryAll();

        return $cities;
    }

    /**
     * Поиск конкретного города по seo_url
     */
    public function getCityByField($field, $value)
    {
        $sql = "SELECT
                    ci.id_city id, ci.name, ci.seo_url
                FROM
                    city ci
                WHERE
                    `".$field."` = '".$value."'
        ";

        $city = Yii::app()->db->createCommand($sql)->queryRow();
        return $city;
    }

    /**
     * Поиск конкретной станции метро seo_url
     */
    public function getMetroByField($field, $value)
    {
        $sql = "SELECT
                    *
                FROM
                    metro ci
                WHERE
                    `".$field."` = '".$value."'
        ";

        $city = Yii::app()->db->createCommand($sql)->queryRow();
        return $city;
    }

    /**
     * Поиск конкретной должности по seo_url
     */
    public function getOccupationByField($field, $value)
    {
        $sql = "SELECT * FROM
                    user_attr_dict d
                WHERE
                    d.id_par = 110
                    AND `".$field."` = '".$value."'
                    AND d.key <> 'custpo'
                ORDER BY
                    d.npp, d.name
        ";

        $occupation = Yii::app()->db->createCommand($sql)->queryRow();
        return $occupation;
    }    

    /**
     * Обновляем seo url для всех (current: должности, города)
     */
    public function updateSeoValues()
    {
        $cities = $this->getCities();
        foreach($cities as $city)
        {
            if(!$city['seo_url'] || trim($city['seo_url']) == '')
                Yii::app()->db->createCommand('UPDATE `city` SET seo_url = "'.str_seo_url(trim($city['name'])).'" WHERE id_city = "'.$city['id'].'"')->execute();
        }

        $metros = $this->getMetros();
        foreach($metros as $city)
        {
            if(!$city['seo_url'] || trim($city['seo_url']) == '')
                Yii::app()->db->createCommand('UPDATE `metro` SET seo_url = "'.str_seo_url(trim($city['name'])).'" WHERE id = "'.$city['id'].'"')->execute();
        }

        $occupations = $this->getOccupations();
        foreach($occupations as $occupation)
        {
            if(!$occupation['comment'] || trim($occupation['comment']) == '')
                Yii::app()->db->createCommand('UPDATE `user_attr_dict` SET comment = "'.str_seo_url(trim($occupation['name'])).'" WHERE id = "'.$occupation['id'].'"')->execute();
        }
    }
    /*
    *       поиск сео данных к странице с параметрами
    *       1) GET, 2) root URL, 3) root city ID  
    */
    public function getPromoSeo($data, $base, $id_city)
    {
        $url = '';
        // позиции
        if(isset($data['posts']) && sizeof($data['posts'])==1){
            $sql = "SELECT * FROM user_attr_dict d
                WHERE d.id_par = 110 AND d.id = '" . $data['posts'][0] . "' AND d.key <> 'custpo'
                ORDER BY d.npp, d.name";
            $post = Yii::app()->db->createCommand($sql)->queryRow();
            // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            if($post['id']==111 && strpos($_SERVER['REQUEST_URI'], 'razdacha-listovok')!=false) {
                $url .= '/razdacha-listovok';
            }
            else {
                $url .= '/' . $post['comment'];
            }
        }
        // города
        if(isset($data['cities']) && sizeof($data['cities'])==1 && $data['cities'][0]!=$id_city){
            $sql = "SELECT ci.seo_url FROM city ci WHERE ci.id_city = '".$data['cities'][0]."'";
            $city = Yii::app()->db->createCommand($sql)->queryRow();
            $url .= '/' . $city['seo_url'];     
        }
        $url = (sizeof($data['cities'])>1 || sizeof($data['posts'])>1 ? '' : $base . $url);

        $table = Subdomain::getCacheData()->seo;
        $seo = Yii::app()->db->createCommand('SELECT * FROM ' . $table . ' WHERE url = "'.$url.'"')->queryRow();

        return $seo;
    }
}