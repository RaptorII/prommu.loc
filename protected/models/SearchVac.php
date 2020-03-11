<?php

/**
 * Используется для поиска вакансий соискателем
 */
class SearchVac extends Model
{
    public function getPageSearchVac()
    {
        $sql = "SELECT d.id, d.type, d.name FROM user_attr_dict d WHERE d.id_par = 110 ORDER BY npp, name";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['posts'] = $res;

        $data['vacs'] = $this->getVacations()['vacs'];

        return $data;
    }



    // для поиска на главной
    public function getSearchVacs()
    {
        $searchWord = filter_var(Yii::app()->getRequest()->getParam('search'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if( strlen($searchWord) > 2 )
        {
            $sql = "SELECT DISTINCT e.id, e.title 
                FROM empl_vacations e 
                INNER JOIN empl_attribs ea ON ea.id_vac = e.id
                INNER JOIN user_attr_dict d ON d.id = ea.id_attr AND d.id_par = 110
                WHERE d.name LIKE '%{$searchWord}%'
                  AND e.status = 1
                  AND e.ismoder = 100 
                  AND e.in_archive=0
                ORDER BY e.id DESC 
                LIMIT 10";
            $res = Yii::app()->db->createCommand($sql)->queryAll();

            foreach ($res as $key => &$val)
            {
                $val = (object)array('name' => $val['title'], 'code' => $val['id']);
            } // end foreach
        }
        else
        {
            $res = array();
        } // endif

        return $res;
    }



    public function getVacations($props = [])
    {
        $filter = $props['filter'] ?: [];
        $filter = $this->renderSQLFilter(['filter' => $filter]);

        $data = $this->searchVacations($filter, $props['profile']);
        $data = array_merge($data, $this->getFilterData());

//        display($data);
//        die();

        return $data;
    }
    
    public function getVacationsAPI($props = [])
    {
        $filter = $props['filter'] ?: [];
        
        $filter = $this->renderSQLFilterAPI(['filter' => $filter]);

        $data = $this->searchVacationsAPI($filter, $props['profile']);
        $data = array_merge($data, $this->getFilterData());

        return $data;
    }

    private function searchVacationsAPI($filter = '', $props=[])
    {
        $limit = $this->limit;
        $offset = $this->offset;
        $profile = $props['profile'];
        try
        {
            $res = (new Vacancy())->getVacanciesQueries(array('page' => 'searchapi', 'filter' => $filter, 'offset' => $offset, 'limit' => $limit, 'profile'=>$profile));

        }
        catch (Exception $e) {
            return array('error' => $e->getMessage());
        } // endtry
    
        

        $data['vacs'] = array();
        
        foreach ($res as $key => $val)
        {
            var_dump($val);
            
            if( !isset($data['vacs'][$val['id']])) $data['vacs'][$val['id']] = array('city' => array(), 'posts' => array()) ;
            
            
            ///attribs
            $data['vacs'][$val['id']]['id'] = (int)$val['id'];
            $data['vacs'][$val['id']]['title'] = $val['title'];
            
             ///owner
            $data['vacs'][$val['id']]['owner']['id'] = (int)$val['uid'];
            $data['vacs'][$val['id']]['owner']['name'] = $val['coname'];
            $data['vacs'][$val['id']]['owner']['logo'] = $val['logo'] ? "https://filesapp.dev.prommu.com/users/".$val['uid']."/".$val['logo']."400.jpg" : NULL;
            ///
            
            ///city
            $data['vacs'][$val['id']]['city'][0]['id'] = (int)$val['id_city'];
            $data['vacs'][$val['id']]['city'][0]['name'] = $val['id_city'] > 0 ? $val['ciname'] : $val['citycu'];
            ///
            
            ///posts
            $data['vacs'][$val['id']]['posts'][0]['id'] = (int)$val['id_attr'];
            $data['vacs'][$val['id']]['posts'][0]['name'] = $val['pname'];
            ///
            
            $data['vacs'][$val['id']]['created_at'] = $val['crdate'];
            $data['vacs'][$val['id']]['removed_at'] = $val['remdate'];
                
           
            $data['vacs'][$val['id']]['is_man'] = (boolean)$val['isman'];
            $data['vacs'][$val['id']]['is_woman'] = (boolean)$val['iswoman'];
            
           
            
            
            $data['vacs'][$val['id']]['is_premium'] = (boolean)$val['ispremium'];
            $data['vacs'][$val['id']]['is_active'] = 1;
            $data['vacs'][$val['id']]['is_med'] = (boolean)$val['ismed'];
            $data['vacs'][$val['id']]['is_hasavto'] = (boolean)$val['isavto'];
            $data['vacs'][$val['id']]['is_temp'] =  (boolean)$val['istemp'];
            $data['vacs'][$val['id']]['smart'] = (boolean)$val['smart'];
            $data['vacs'][$val['id']]['card'] = (boolean)$val['card'];
            $data['vacs'][$val['id']]['card_prommu'] = (boolean)$val['cardPrommu'];
            
            
            $data['vacs'][$val['id']]['requirements'] = $val['requirements'];
            $data['vacs'][$val['id']]['conditions'] = $val['conditions'];
            $data['vacs'][$val['id']]['duties'] = $val['duties'];
            
            
            $data['vacs'][$val['id']]['salary_hour'] = (float)$val['shour'];
            $data['vacs'][$val['id']]['salary_week'] = (float)$val['sweek'];
            $data['vacs'][$val['id']]['salary_month'] = (float)$val['smonth'];
            $data['vacs'][$val['id']]['salary_visit'] = (float)$val['svisit'];
            
            //
          
            // if( $val['mid'] ) $data['vacs'][$val['id']]['metroes'][$val['mid']] = $val['mname'];
            // $data['vacs'][$val['id']] = array_merge($data['vacs'][$val['id']], $val);
        
        } // end foreach

        $i = 1;
        $ret['vacs'] = array();
        foreach ($data['vacs'] as $key => $val) { $ret['vacs'][$i] = $val; $i++; }

        return $ret;
    }
    
    // получение кол-ва вакансий
    public function searchVacationsCountApi($props = [])
    {
        $filter = $props['filter'] ?: [];
        $filter = $this->renderSQLFilterApi(['filter' => $filter]);

        $sql = "SELECT COUNT(DISTINCT e.id)
                FROM empl_vacations e
                INNER JOIN empl_city c ON c.id_vac = e.id 
                {$filter['table']}
                INNER JOIN empl_attribs ea ON ea.id_vac = e.id  
                {$filter['filter']}
                AND e.status = 1 AND e.ismoder = 100 AND e.in_archive=0 AND e.remdate >= CURDATE()
                ORDER BY e.ispremium DESC, e.id DESC ";

        $res = Yii::app()->db->createCommand($sql);

        return $res->queryScalar();
    }
    
    // получение кол-ва вакансий
    public function searchVacationsCount($props = [])
    {
        $filter = $props['filter'] ?: [];
        $filter = $this->renderSQLFilter(['filter' => $filter]);

        $sql = "SELECT COUNT(DISTINCT e.id)
                FROM empl_vacations e
                INNER JOIN empl_city c ON c.id_vac = e.id 
                {$filter['table']}
                INNER JOIN empl_attribs ea ON ea.id_vac = e.id  
                {$filter['filter']}
                AND e.status = 1 AND e.ismoder = 100 AND e.in_archive=0 AND e.remdate >= CURDATE()
                ORDER BY e.ispremium DESC, e.id DESC ";

        $res = Yii::app()->db->createCommand($sql);

        return $res->queryScalar();
    }



    // получение данных для фильтра
    private function getFilterData($inData = array())
    {
        // если были ввдедены значения
        $cities = Yii::app()->getRequest()->getParam('cities');
        $posts = Yii::app()->getRequest()->getParam('post');
        $data['poself'] = filter_var(Yii::app()->getRequest()->getParam('poself'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['postsAll'] = $postsAll = Yii::app()->getRequest()->getParam('poall') == 'on';// || !count($posts) && !$data['poself'];
        $data['qs'] = filter_var(Yii::app()->getRequest()->getParam('qs'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data['bt'] = Yii::app()->getRequest()->getParam('bt');
        $data['sex'] = filter_var(Yii::app()->getRequest()->getParam('sex'), FILTER_SANITIZE_NUMBER_INT);
        $data['af'] = filter_var(Yii::app()->getRequest()->getParam('af'), FILTER_SANITIZE_NUMBER_INT);
        $data['at'] = filter_var(Yii::app()->getRequest()->getParam('at'), FILTER_SANITIZE_NUMBER_INT);
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
//        foreach ($data as $key => $val)
//        {
//            if( !empty($val) ) $res[$key] = $val;
//        } // end foreach
//        $data = $res;

        // читаем города
        $data['city'] = array();
        if( $cities )
        {
            $sql = "SELECT ci.id_city id, ci.name FROM city ci 
                WHERE ci.id_city IN(".join(',',$cities).") 
                ORDER BY name";
            $data['city'] = Yii::app()->db->createCommand($sql)->queryAll();
        } // endif
        $data['selected']['city'] = count($data['city']);



        // читаем должности
        $data['posts'] = $this->getOccupations();

        $flag = 0;
        if( $posts )
            foreach($data['posts'] as $key => &$val)
            {
                if( array_key_exists($val['id'], $posts) ) //!$data['poself'] && ($postsAll ||
                {
                    $val['selected'] = 1;
                    $flag || ($flag = 1);
                }
            } // end foreach
        $data['selected']['posts'] = $flag;

        return $data;
    }


    /**
     * Получение списка должностей
     */
    public function getOccupations()
    {
        $sql = "SELECT d.id, d.id_par, d.type, d.name, d.postself
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

        $occupations = $this->getOccupations();
        foreach($occupations as $occupation)
        {
            if(!$occupation['comment'] || trim($occupation['comment']) == '')
                Yii::app()->db->createCommand('UPDATE `user_attr_dict` SET comment = "'.str_seo_url(trim($occupation['name'])).'" WHERE id = "'.$occupation['id'].'"')->execute();
        }
    }

    public function buildPrettyUrl($data)
    {
        $url = array();
        //$this->updateSeoValues();
        $cnt = 0;
        $hasPost = false;

        // должности
        if(isset($data['post']) && is_array($data['post']) && sizeof($data['post']))
        {
            $posts = array();
            foreach($data['post'] as $post_id => $on)
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

        // вид занятости
        if(isset($data['bt']) && in_array($data['bt'], array(1,2)))
        {
            if($data['bt'] == 1){
                $url[] = 'partial';
                $cnt++;
            }

            if($data['bt'] == 2){
                $url[] = 'fulltime';
                $cnt++;
            }
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

        // sex ?
        if(isset($data['sex']))
        {
            if($data['sex'] == 1){
                $url[] = 'male';
                $cnt++;
            }
            if($data['sex'] == 2){
                $url[] = 'female';
                $cnt++;
            }
        }

        if(isset($data['smart']))
        {
            if($data['smart'] == 1){
                $url[] = 'smart';
                $cnt++;
            }
        }

        if(isset($data['self_employed']))
        {
          if($data['self_employed'] == 1){
            $url[] = 'self_employed';
            $cnt++;
          }
        }

        // age
        if((isset($data['af']) && (int)$data['af'] > 0) || (isset($data['at']) && (int)$data['at'] > 0))
        {
            $url[] = 'age='.(int)$data['af'].'-'.(int)$data['at'];
            $cnt++;
        }

        // cards
        if(isset($data['pcard']))
        {
            $url[] = 'cardprommu';
            $cnt++;
        }
        if(isset($data['bcard']))
        {
            $url[] = 'card';
            $cnt++;
        }
        // pages
        if(isset($data['page']))
        {
            $url[] = 'page='.$data['page'];
            $cnt++;
        }
        
        if(!$cnt) $str = '/vacancy';
        elseif($cnt==1 && $hasPost) $str = '/vacancy/';
        else $str = '/vacancy/?';

        return $str . implode('/', $url);
    }

    private function renderSQLFilter($inProps = [])
    {
        // analyse parameters
        $data = array();
        // quicksearch
        if( ($s1 = filter_var(Yii::app()->getRequest()->getParam('qs'), FILTER_SANITIZE_FULL_SPECIAL_CHARS)) || $inProps['filter']['qs'] ) $data['qs'] = $s1 ? $s1 : $inProps['filter']['qs'];
        // города
        if( Yii::app()->getRequest()->getParam('cities') || $inProps['filter']['city'] ) $data['cities'] = $inProps['filter']['city'] ?: Yii::app()->getRequest()->getParam('cities');
        // все должности
        if( !Yii::app()->getRequest()->getParam('poall') || $inProps['filter']['post'] ) $data['posts'] = $inProps['filter']['post'] ?: Yii::app()->getRequest()->getParam('post');
         if( Yii::app()->getRequest()->getParam('smart') || $inProps['filter']['smart']  ) $data['smart'] = $inProps['filter']['smart'] ?: Yii::app()->getRequest()->getParam('smart');
        if( Yii::app()->getRequest()->getParam('pcard') || $inProps['filter']['cardPrommu']  ) $data['cardPrommu'] = $inProps['filter']['cardPrommu'] ?: Yii::app()->getRequest()->getParam('pcard');
        if( Yii::app()->getRequest()->getParam('bcard') || $inProps['filter']['card']  ) $data['card'] = $inProps['filter']['card'] ?: Yii::app()->getRequest()->getParam('bcard');
        if( Yii::app()->getRequest()->getParam('self_employed') || $inProps['filter']['self_employed']  )
          $data['self_employed'] = $inProps['filter']['self_employed'] ?: Yii::app()->getRequest()->getParam('self_employed');
        // должность в ручную
        if( !Yii::app()->getRequest()->getParam('poall') && ($s1 = filter_var(Yii::app()->getRequest()->getParam('poself'), FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ) $data['selfPost'] = $s1;
        // занятость
        if( (($int = filter_var(Yii::app()->getRequest()->getParam('bt'), FILTER_SANITIZE_NUMBER_INT)) != 3 && $int) || $inProps['filter']['istemp'] ) $data['busyType'] = $int ? $int - 1 : $inProps['filter']['istemp'] ;

        // пол
        $int = filter_var(Yii::app()->getRequest()->getParam('sex'), FILTER_SANITIZE_NUMBER_INT);
        $int || ($int = $inProps['filter']['sex']);
        if( $int != 3 && $int ) $data['sex'] = $int;
        // age from
        if( ($int = filter_var(Yii::app()->getRequest()->getParam('af'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['af']) ) $data['ageFrom'] = $int;
        // age to
        if( ($int = filter_var(Yii::app()->getRequest()->getParam('at'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['at']) ) $data['ageTo'] = $int;

        // salary
        $salradio = filter_var(Yii::app()->getRequest()->getParam('sr', $inProps['filter']['sr'] ?: 0), FILTER_SANITIZE_NUMBER_INT);
        if( $salradio == 1 && (($int = filter_var(Yii::app()->getRequest()->getParam('sphf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['sphf'])) ) $data['salHourF'] = $int;
        if( $salradio == 1 && (($int = filter_var(Yii::app()->getRequest()->getParam('spht'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spht'])) ) $data['salHourT'] = $int;
        if( $salradio == 2 && (($int = filter_var(Yii::app()->getRequest()->getParam('spwf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spwf'])) ) $data['salWeekF'] = $int;
        if( $salradio == 2 && (($int = filter_var(Yii::app()->getRequest()->getParam('spwt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spwt'])) ) $data['salWeekT'] = $int;
        if( $salradio == 3 && (($int = filter_var(Yii::app()->getRequest()->getParam('spmf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spmf'])) ) $data['salMonF'] = $int;
        if( $salradio == 3 && (($int = filter_var(Yii::app()->getRequest()->getParam('spmt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spmt'])) ) $data['salMonT'] = $int;
        if( $salradio == 4 && (($int = filter_var(Yii::app()->getRequest()->getParam('spvf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spvf'])) ) $data['salVisitF'] = $int;
        if( $salradio == 4 && (($int = filter_var(Yii::app()->getRequest()->getParam('spvt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spvt'])) ) $data['salVisitT'] = $int;
        // addmetro
        if( filter_var(Yii::app()->getRequest()->getParam('addmetro'), FILTER_SANITIZE_NUMBER_INT) ) $data['metro'] = 1;

        // create filter string
        $filter = [];
        $table = [];
        // QS
          // QS
        if( !empty($data['qs']) ) {
            $filter[] = "e.title LIKE '%{$data['qs']}%'";
        }
        
        // city filter
        if( !empty($data['cities']) )
        {
            $filter[] = "c.id_city IN (".join(',',$data['cities']).')';
        }
        else
        {
            $filter[] = 'c.id_city IN ('.Subdomain::getCacheData()->strCitiesIdes.')';
        }


        if( !empty($data['smart']) )
        {
            $filter[] = ' e.smart = 1';
        }
        

        if( !empty($data['self_employed']) )
        {
          $filter[] = ' e.self_employed = 1';
        }

        if( !empty($data['cardPrommu']) )
            $filter[] = ' e.cardPrommu = 1';

        if( !empty($data['card']) )
            $filter[] = ' e.card = 1';

        // posts filter
        if( !empty($data['posts']) )
        {
          $arPostId = array_keys($data['posts']);
          $filterPost = ' ea.id_attr IN ('.join(',', $arPostId).')';
        }
        
        
        $s1 = '';
        if( $filterPost ) $s1 = $filterPost;
        if( $filterPost && $filterPostSelf) $s1 .= ' OR ';
        $s1 .= $filterPostSelf;
        if( $filterPost || $filterPostSelf) $filter[] = "({$s1})";

        // тип занятости
        if( isset($data['busyType']) ) $filter[] = "e.istemp = {$data['busyType']}";
        // пол
        if( isset($data['sex']) )
        {
            $field = $data['sex'] == 1 ? 'isman' : 'iswoman' ;
            $filter[] = "e.{$field} = 1";
        }
        // age
        if( isset($data['ageFrom']) ) 
            $filter[] = "e.agefrom >= {$data['ageFrom']}";
        if( isset($data['ageTo']) ) {
            $filter[] = "e.agefrom <= {$data['ageTo']}";
            $filter[] = "e.ageto <= {$data['ageTo']}";
        }

        // salary
        if( isset($data['salHourF']) ) $filter[] = "e.shour >= {$data['salHourF']}";
        if( isset($data['salHourT']) ) $filter[] = "e.shour <= {$data['salHourT']}";
        if( isset($data['salWeekF']) ) $filter[] = "e.sweek >= {$data['salWeekF']}";
        if( isset($data['salWeekT']) ) $filter[] = "e.sweek <= {$data['salWeekT']}";
        if( isset($data['salMonF']) ) $filter[] = "e.smonth >= {$data['salMonF']}";
        if( isset($data['salMonT']) ) $filter[] = "e.smonth <= {$data['salMonT']}";
        if( isset($data['salVisitF']) ) $filter[] = "e.svisit >= {$data['salVisitF']}";
        if( isset($data['salVisitT']) ) $filter[] = "e.svisit <= {$data['salVisitT']}";

        $filter = count($filter) ? 'WHERE ' . join(' and ', $filter) : '';

        $filterData['table'] = join(' \n', $table);
        $filterData['filter'] = $filter;
        //var_dump($filterData);
        return $filterData;
    }

    
    private function renderSQLFilterAPI($inProps = [])
    {
        // analyse parameters
        $data = array();
        // quicksearch
        if( ($s1 = filter_var(Yii::app()->getRequest()->getParam('qs'), FILTER_SANITIZE_FULL_SPECIAL_CHARS)) || $inProps['filter']['qs'] ) $data['qs'] = $s1 ? $s1 : $inProps['filter']['qs'];
        // города
        $data['cities'] = $inProps['filter']['cities'] ? $inProps['filter']['cities']: Yii::app()->getRequest()->getParam('cities');
        // все должности
        $data['posts'] = $inProps['filter']['posts'] ? $inProps['filter']['posts']: Yii::app()->getRequest()->getParam('post');
        if( Yii::app()->getRequest()->getParam('smart') || $inProps['filter']['smart']  ) $data['smart'] = $inProps['filter']['smart'];
        if( Yii::app()->getRequest()->getParam('ismed') || $inProps['filter']['ismed']  ) $data['ismed'] = $inProps['filter']['ismed'];
        if( Yii::app()->getRequest()->getParam('isavto') || $inProps['filter']['isavto']  ) $data['isavto'] = $inProps['filter']['isavto'];
        if( Yii::app()->getRequest()->getParam('pcard') || $inProps['filter']['cardPrommu']  ) $data['cardPrommu'] = $inProps['filter']['cardPrommu'] ?: Yii::app()->getRequest()->getParam('pcard');
        if( Yii::app()->getRequest()->getParam('bcard') || $inProps['filter']['card']  ) $data['card'] = $inProps['filter']['card'] ?: Yii::app()->getRequest()->getParam('bcard');
        if( Yii::app()->getRequest()->getParam('self_employed') || $inProps['filter']['self_employed']  )
          $data['self_employed'] = $inProps['filter']['self_employed'] ?: Yii::app()->getRequest()->getParam('self_employed');
          
        
        // должность в ручную
        if( !Yii::app()->getRequest()->getParam('poall') && ($s1 = filter_var(Yii::app()->getRequest()->getParam('poself'), FILTER_SANITIZE_FULL_SPECIAL_CHARS)) ) $data['selfPost'] = $s1;
        // занятость
        if( $int = filter_var(Yii::app()->getRequest()->getParam('bt'), FILTER_SANITIZE_NUMBER_INT)|| $inProps['filter']['istemp'] ) $data['busyType'] = $int ? $int : $inProps['filter']['istemp'] ;

        // пол
        $int = filter_var(Yii::app()->getRequest()->getParam('sex'), FILTER_SANITIZE_NUMBER_INT);
        $int || ($int = $inProps['filter']['sex']);
        if( $int != 3 && $int ) $data['sex'] = $int;
        // age from
        if( ($int = filter_var(Yii::app()->getRequest()->getParam('af'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['af']) ) $data['ageFrom'] = $int;
        // age to
        if( ($int = filter_var(Yii::app()->getRequest()->getParam('at'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['at']) ) $data['ageTo'] = $int;

        // salary
        $salradio = filter_var(Yii::app()->getRequest()->getParam('sr', $inProps['filter']['sr'] ?: 0), FILTER_SANITIZE_NUMBER_INT);
        if( (($int = filter_var(Yii::app()->getRequest()->getParam('sphf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['sphf'])) ) $data['salHourF'] = $int;
        if( (($int = filter_var(Yii::app()->getRequest()->getParam('spht'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spht'])) ) $data['salHourT'] = $int;
        if( (($int = filter_var(Yii::app()->getRequest()->getParam('spwf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spwf'])) ) $data['salWeekF'] = $int;
        if((($int = filter_var(Yii::app()->getRequest()->getParam('spwt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spwt'])) ) $data['salWeekT'] = $int;
        if((($int = filter_var(Yii::app()->getRequest()->getParam('spmf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spmf'])) ) $data['salMonF'] = $int;
        if((($int = filter_var(Yii::app()->getRequest()->getParam('spmt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spmt'])) ) $data['salMonT'] = $int;
        if( (($int = filter_var(Yii::app()->getRequest()->getParam('spvf'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spvf'])) ) $data['salVisitF'] = $int;
        if(  (($int = filter_var(Yii::app()->getRequest()->getParam('spvt'), FILTER_SANITIZE_NUMBER_INT)) || ($int = $inProps['filter']['spvt'])) ) $data['salVisitT'] = $int;
        // addmetro
        if( filter_var(Yii::app()->getRequest()->getParam('addmetro'), FILTER_SANITIZE_NUMBER_INT) ) $data['metro'] = 1;
    
        
        // create filter string
        $filter = [];
        $table = [];
        // QS
          // QS
        if( !empty($data['qs']) ) {
            $filter[] = "e.title LIKE '%{$data['qs']}%'";
        }
        
        // city filter
        if( !empty($data['cities']) )
        {
            $filter[] = "c.id_city IN (".join(',',$data['cities']).')';
        }
        else
        {
            $filter[] = 'c.id_city IN ('.Subdomain::getCacheData()->strCitiesIdes.')';
        }
        
        if( !empty($data['ismed']) )
        {
            $filter[] = ' e.ismed = 1';
        }
        
        if( !empty($data['isavto']) )
        {
            $filter[] = ' e.isavto = 1';
        }

        if( !empty($data['smart']) )
        {
            $filter[] = ' e.smart = 1';
        }

        if( !empty($data['self_employed']) )
        {
          $filter[] = ' e.self_employed = 1';
        }

        if( !empty($data['cardPrommu']) )
            $filter[] = ' e.cardPrommu = 1';

        if( !empty($data['card']) )
            $filter[] = ' e.card = 1';

        // posts filter
        if( !empty($data['posts']) )
        {
            // foreach ($data['posts'] as $key => &$val) { $val = $key; } // end foreach;
            
            $filterPost = 'ea.id_attr IN ('.join(',', $data['posts']).')';
        }
        
       
        
       
        $s1 = '';
        if( $filterPost ) $s1 = $filterPost;
        if( $filterPost && $filterPostSelf) $s1 .= ' OR ';
        $s1 .= $filterPostSelf;
        if( $filterPost || $filterPostSelf) $filter[] = "({$s1})";
        
        // тип занятости
        if( isset($data['busyType']) ) $filter[] = "e.istemp = {$data['busyType']}";
        // пол
        if( isset($data['sex']) )
        {
            $field = $data['sex'] == 1 ? 'isman' : 'iswoman' ;
            $filter[] = "e.{$field} = 1";
        }
        // age
        if( isset($data['ageFrom']) ) 
            $filter[] = "e.agefrom >= {$data['ageFrom']}";
        if( isset($data['ageTo']) ) {
            $filter[] = "e.agefrom <= {$data['ageTo']}";
            $filter[] = "e.ageto <= {$data['ageTo']}";
        }

        // salary
        if( isset($data['salHourF']) ) $filter[] = "e.shour >= {$data['salHourF']}";
        if( isset($data['salHourT']) ) $filter[] = "e.shour <= {$data['salHourT']}";
        if( isset($data['salWeekF']) ) $filter[] = "e.sweek >= {$data['salWeekF']}";
        if( isset($data['salWeekT']) ) $filter[] = "e.sweek <= {$data['salWeekT']}";
        if( isset($data['salMonF']) ) $filter[] = "e.smonth >= {$data['salMonF']}";
        if( isset($data['salMonT']) ) $filter[] = "e.smonth <= {$data['salMonT']}";
        if( isset($data['salVisitF']) ) $filter[] = "e.svisit >= {$data['salVisitF']}";
        if( isset($data['salVisitT']) ) $filter[] = "e.svisit <= {$data['salVisitT']}";

        $filter = count($filter) ? 'WHERE ' . join(' and ', $filter) : '';

        $filterData['table'] = join(' \n', $table);
        $filterData['filter'] = $filter;
        //var_dump($filterData);
        return $filterData;
    }
    

    private function searchVacations($filter = '', $props=[])
    {
        $limit = $this->limit;
        $offset = $this->offset;
        $profile = $props['profile'];
        try
        {
            $res = (new Vacancy())->getVacanciesQueries(array('page' => 'searchvac', 'filter' => $filter, 'offset' => $offset, 'limit' => $limit, 'profile'=>$profile));

        }
        catch (Exception $e) {
            return array('error' => $e->getMessage());
        } // endtry


        $data['vacs'] = array();
        foreach ($res as $key => $val)
        {
            if( !isset($data['vacs'][$val['id']])) $data['vacs'][$val['id']] = array('city' => array(), 'posts' => array(), 'metroes' => array()) ;
            $data['vacs'][$val['id']]['city'][$val['id_city']] = $val['id_city'] > 0 ? $val['ciname'] : $val['citycu'];
            $data['vacs'][$val['id']]['posts'][$val['id_attr']] = $val['pname'];
            if( $val['mid'] ) $data['vacs'][$val['id']]['metroes'][$val['mid']] = $val['mname'];
            $data['vacs'][$val['id']] = array_merge($data['vacs'][$val['id']], $val);
            $data['vacs'][$val['id']]['posts'][$val['id_attr']] = $val['pname'];
        } // end foreach

        $i = 1;
        $ret['vacs'] = array();
        foreach ($data['vacs'] as $key => $val) { $ret['vacs'][$i] = $val; $i++; }

//$s1 = '$data[vacs]='.var_export($data['vacs'], 1)."\n";
//0||$notpr||file_put_contents('D:\DENVER2\home\localhost\prommu\!file', "\n--------------------\n".date("H:i:s")."\n".$s1, 0);//FILE_APPEND


        return $ret;
    }
    /*
    *       поиск сео данных к странице с параметрами
    *       1) GET, 2) root URL, 3) root city ID  
    */
    public function getVacancySeo($data, $base, $id_city)
    {
        $url = '';
        // позиции
        if(isset($data['post']) && sizeof($data['post'])==1){
            $id = array_search('on', $data['post']);
            $sql = "SELECT * FROM user_attr_dict d
                WHERE d.id_par = 110 AND d.id = '" . $id . "' AND d.key <> 'custpo'
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
        $url = (sizeof($data['cities'])>1 || sizeof($data['post'])>1 ? '' : $base . $url);

        $table = Subdomain::getCacheData()->seo;
        $seo = Yii::app()->db->createCommand('SELECT * FROM ' . $table . ' WHERE url = "'.$url.'"')->queryRow();

        return $seo;
    }
}