<?php

/**
 * Используется для поиска соискателей
 */
class SearchPromo extends Model
{
    public function getPromos($isEmplOnly = 0,$props = [])
    {
        $filter = $this->renderSQLFilter(['filter' => $props['filter']]);

        $data = $this->searchPromos($filter);
        if( !$isEmplOnly ) $data = array_merge($data, $this->getFilterData());

        return $data;
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



    public function searchPromosCount()
    {
        $strCities = Subdomain::getCitiesIdies(true);
        $filter = $this->renderSQLFilter();

        $sql = "SELECT COUNT(DISTINCT r.id)
                FROM resume r
                INNER JOIN user u ON u.id_user = r.id_user AND u.ismoder = 1 AND u.isblocked = 0 

                INNER JOIN user_city uc ON r.id_user = uc.id_user  AND !(uc.id_city IN({$strCities}))
                {$filter['table']}
                INNER JOIN user_mech a ON a.id_us = r.id_user
                {$filter['filter']}
                ORDER BY r.id DESC ";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);

        return $res->queryScalar();
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
        $sql = "SELECT d.id,
                   d.id_par,
                   d.type,
                   d.name
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
        // города
        if( Yii::app()->getRequest()->getParam('cities') || $inProps['filter']['cities'] ) $data['cities'] = $inProps['filter']['cities'] ?: Yii::app()->getRequest()->getParam('cities');
        // posts
        if(Yii::app()->getRequest()->getParam('posts') || $inProps['filter']['posts'] ) $data['posts'] = $inProps['filter']['posts'] ?: Yii::app()->getRequest()->getParam('posts');
        // sex
        if( Yii::app()->getRequest()->getParam('sm') || $inProps['filter']['sm']  ) $data['sm'] = $inProps['filter']['sm'] ?: Yii::app()->getRequest()->getParam('sm');
        if( Yii::app()->getRequest()->getParam('ph') || $inProps['filter']['ph']  ) $data['ph'] = $inProps['filter']['ph'] ?: Yii::app()->getRequest()->getParam('ph');

        if( Yii::app()->getRequest()->getParam('sf') || $inProps['filter']['sf']  ) $data['sf'] = $inProps['filter']['sf'] ?: Yii::app()->getRequest()->getParam('sf');
        // medbook n avto
        if( Yii::app()->getRequest()->getParam('mb') || $inProps['filter']['mb']  ) $data['mb'] = $inProps['filter']['mb'] ?: Yii::app()->getRequest()->getParam('mb');
        if( Yii::app()->getRequest()->getParam('avto') || $inProps['filter']['avto']  ) $data['avto'] = $inProps['filter']['avto'] ?: Yii::app()->getRequest()->getParam('avto');
         if( Yii::app()->getRequest()->getParam('smart') || $inProps['filter']['smart']  ) $data['smart'] = $inProps['filter']['smart'] ?: Yii::app()->getRequest()->getParam('smart');
        if( Yii::app()->getRequest()->getParam('card') || $inProps['filter']['card']  ) $data['card'] = $inProps['filter']['card'] ?: Yii::app()->getRequest()->getParam('card');
        if( Yii::app()->getRequest()->getParam('cardPrommu') || $inProps['filter']['cardPrommu']  ) $data['cardPrommu'] = $inProps['filter']['cardPrommu'] ?: Yii::app()->getRequest()->getParam('cardPrommu');
        // Quick search название города или имя,фамилия
        if( $sq = Yii::app()->getRequest()->getParam('qs')){  $data['qs'] = trim(filter_var($sq, FILTER_SANITIZE_FULL_SPECIAL_CHARS));}
        elseif($inProps['filter']['qs']) {
            $data['qs'] = $inProps['filter']['qs'];

        }
        // age
        if(Yii::app()->getRequest()->getParam('af') || $inProps['filter']['af'])
            $data['ageFrom'] = $inProps['filter']['af'] ?: Yii::app()->getRequest()->getParam('af');
        if(Yii::app()->getRequest()->getParam('at') || $inProps['filter']['at'])
            $data['ageTo'] = $inProps['filter']['at'] ?: Yii::app()->getRequest()->getParam('at');
        //if( $inProps['filter']['af'] ) $data['ageFrom'] = $inProps['filter']['af'];
        // age to
        //if( $inProps['filter']['at'] ) $data['ageTo'] = $inProps['filter']['at'];

        $filter = [];
        $tables = [];
        
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
            //$filter[] = "r.age <= {$data['ageTo']}";



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

        $filter = count($filter) ? 'WHERE ' . join(' and ', $filter) : '';

        return array('filter' => $filter, 'table' => join(' ', $tables));
    }



    private function searchPromos($filter)
    {
        $limit = $this->limit;
        $offset = $this->offset;

        $strCities = Subdomain::getCitiesIdies(true);

        try
        {
            $res = (new Promo())->getApplicantsQueries(array('page' => 'searchpromo', 'table' => $filter['table'], 'filter' => $filter['filter'], 'offset' => $offset, 'limit' => $limit));
        }
        catch (Exception $e) {
            return array('error' => $e->getMessage());
        } // endtry

        $sql = "SELECT r.id_user
            FROM resume r
            INNER JOIN (
                SELECT DISTINCT r.id
                FROM resume r
                INNER JOIN user u ON u.id_user = r.id_user AND u.ismoder = 1 AND u.isblocked = 0
                INNER JOIN user_city uc ON r.id_user = uc.id_user AND !(uc.id_city IN({$strCities}))
                {$filter['table']}
                INNER JOIN user_mech a ON a.id_us = r.id_user
                {$filter['filter']}
                ORDER BY r.id DESC 
            ) t1 ON t1.id = r.id
            
            LIMIT 10000";

            $result = Yii::app()->db->createCommand($sql)->queryAll();

        // подготовка данных по соискателям
        $data['promo'] = array();
        if( $res )
        {
            $UserProfileApplic = (new ProfileFactory())->makeProfile(['id' => 1, 'type' => 2]);
            foreach ($res as $key => $val)
            {
                if( !isset($data['promo'][$val['id']])) $data['promo'][$val['id']] = array('city' => array(), 'post' => array(), 'metroes' => array()) ;
                // город
                $data['promo'][$val['id']]['city'][$val['id_city']] = $val['ciname'];
                // должности
                $data['promo'][$val['id']]['post'][$val['idpost']] = array($val['val'], $val['pay'], $val['pt']);
                // metro
                if( $val['mid'] ) $data['promo'][$val['id']]['metroes'][$val['mid']] = $val['mname'];
                // возраст
                $d1 = new DateTime();
                $d2 = new DateTime($val['birthday']);
                $diff = $d2->diff($d1);
                $data['promo'][$val['id']]['age'] = $diff->y;
                $id = $val['id'];
                $id_user = $val['id_user'];
                 $sql = "SELECT COUNT(vs.id) cou
                FROM empl_vacations v
                INNER JOIN vacation_stat vs ON vs.id_vac = v.id 
                WHERE vs.id_promo = {$id} 
                  AND " . Vacancy::getScopesCustom(Vacancy::$SCOPE_APPLIC_WORKING, 'vs');
                $res = Yii::app()->db->createCommand($sql)->queryScalar();
                $data['promo'][$val['id']]['projects'] = $res;
                $sql = "SELECT r.isman 
                    FROM resume r
                    LEFT JOIN user u ON u.id_user = r.id_user
                    LEFT JOIN user_attribs a ON r.id_user = a.id_us
                    LEFT JOIN user_attr_dict d ON a.id_attr = d.id
                    WHERE r.id_user = {$id_user}";
                $res = Yii::app()->db->createCommand($sql)->queryScalar();
                $data['promo'][$val['id']]['sex'] = $res;


                $data['promo'][$val['id']] = array_merge($data['promo'][$val['id']], $val);
                // comment
                if( !isset($data['promo'][$val['id']]['comm']) ) list($data['promo'][$val['id']]['comm'], $data['promo'][$val['id']]['commneg']) = $UserProfileApplic->getCommentsCount($val['id']);
            } // end foreach
        } // endif
        
        $i = 1;
        $ret['promo'] = array();
        $ret['promos'] = $result;
        foreach ($data['promo'] as $key => $val) { $ret['promo'][$i] = $val; $i++; }
        // $i = 1;
        // foreach ($result as $key => $val) { $ret['promos'][$i] = $val['id_user']; $i++; }


        return $ret;
    }

    public function buildPrettyUrl($data)
    {
        $url = array();
        // $this->updateSeoValues();
        $cnt = 0;

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

        if(!$cnt) $str = '/ankety';
        elseif($cnt==1) $str = '/ankety/';
        elseif($cnt>1) $str = '/ankety/?';

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
            $url .= '/' . $post['comment'];
        }
        // города
        if(isset($data['cities']) && sizeof($data['cities'])==1 && $data['cities'][0]!=$id_city){
            $sql = "SELECT ci.seo_url FROM city ci WHERE ci.id_city = '".$data['cities'][0]."'";
            $city = Yii::app()->db->createCommand($sql)->queryRow();
            $url .= '/' . $city['seo_url'];     
        }
        $url = (sizeof($data['cities'])>1 || sizeof($data['posts'])>1 ? '' : $base . $url);

        $table = Subdomain::getSeoTable();
        $seo = Yii::app()->db->createCommand('SELECT * FROM ' . $table . ' WHERE url = "'.$url.'"')->queryRow();

        return $seo;
    }
}