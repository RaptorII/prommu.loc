<?php

/**
 * Используется для поиска работодателей
 */
class SearchEmpl extends Model
{
//    public function getPageSearchVac()
//    {
//        $sql = "SELECT d.id, d.type, d.name FROM user_attr_dict d WHERE d.id_par = 110 ORDER BY name";
//        $res = Yii::app()->db->createCommand($sql)->queryAll();
//        $data['posts'] = $res;
//
//        $data['empls'] = $this->getVacations()['vacs'];
//
//        return $data;
//    }



    public function getEmployers($isEmplOnly = 0, $props = [])
    {
        $filter = $this->renderSQLFilter(['filter' => $props['filter']]);

        $data = $this->searchEmployers($filter);
        if( !$isEmplOnly ) $data = array_merge($data, $this->getFilterData());

        return $data;
    }
    
    public function getEmployersAPI($isEmplOnly = 0, $props = [])
    {
        $filter = $this->renderSQLFilter(['filter' => $props['filter']]);

        $data = $this->searchEmployersAPI($filter);
        if( !$isEmplOnly ) $data = array_merge($data, $this->getFilterData());

        return $data;
    }

    private function searchEmployersAPI($filter)
    {
        $limit = $this->limit;
        $offset = $this->offset;

        try
        {
            $res = (new Employer())->getEmployersQueries(array('page' => 'searchempl', 'filter' => $filter, 'offset' => $offset, 'limit' => $limit));
        }
        catch (Exception $e) {
            return array('error' => $e->getMessage());
        } // endtry



        $data['empls'] = array();
        foreach ($res as $key => $val)
        {
            
            $data['empls'][$val['id']]['user_id'] = (int)$val['id_user'];
            $data['empls'][$val['id']]['employer_id'] = (int)$val['id'];
            $data['empls'][$val['id']]['name'] = $val['name'];
            $data['empls'][$val['id']]['type'] = $val['tname'];
            $data['empls'][$val['id']]['logo'] = "https://files.prommu.com/users/".$val['id_user']."/".$val['logo'].".jpg";
            
            if( !isset($data['empls'][$val['id']])) $data['empls'][$val['id']] = array('city' => array()) ;
            $data['empls'][$val['id']]['city']['id'] = (int)$val['id_city'];
            $data['empls'][$val['id']]['city']['name'] = $val['ciname'];
            
            $data['empls'][$val['id']]['rate']['positive_rate'] = (int)$val['rate'];
            $data['empls'][$val['id']]['rate']['negative_rate'] = (int)$val['rate_neg'];
            
            $data['empls'][$val['id']]['response']['positive_response'] = 0;
            $data['empls'][$val['id']]['response']['negative_response'] = 0;
            
            $data['empls'][$val['id']]['rate_sum'] = (int)$val['rate'] - (int)$val['rate_neg'];
            
            
            
            $data['empls'][$val['id']]['created_at'] = $val['crdate'];
            $data['empls'][$val['id']]['updated_at'] = $val['mdate'];
            $data['empls'][$val['id']]['is_online'] = (boolean)$val['is_online'];
            $data['empls'][$val['id']]['vacancy_count'] = (int)$val['vaccount'];
            
            


        } // end foreach

        $i = 1;
        $ret['empls'] = array();
        
        foreach ($data['empls'] as $key => $val) { $ret['empls'][$i] = $val; $i++; }


        return $ret;
    }
    

    public function searchEmployersCount($props = [])
    {
        $filter = $this->renderSQLFilter(['filter' => $props['filter']]);

        $sql = "SELECT COUNT(e.id_user)
              FROM employer e
              INNER JOIN user_city uc ON e.id_user = uc.id_user
              INNER JOIN user u ON u.id_user = e.id_user

              {$filter} AND u.ismoder = 1
              ORDER BY e.id DESC";

        $res = Yii::app()->db->createCommand($sql);

        return $res->queryScalar();
    }



    // обработка get данных из фильтра для вывода
    public function getFilterParams()
    {


        return 0;
    }



    // получение данных для фильтра
    private function getFilterData($inData = array())
    {
        // если были ввдедены значения
        $data['qs'] = filter_var(Yii::app()->getRequest()->getParam('qs'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cotype = Yii::app()->getRequest()->getParam('cotype');
        $cities = Yii::app()->getRequest()->getParam('cities');

        // читаем города
        $Q1 = Yii::app()->db->createCommand()
            ->select('t.id_city id, t.name, t.seo_url')
            ->from('city t')
            ->limit(10000);
        $data['cities'] = $Q1->queryAll();
       /* if( $cities )
        {
            $sql = "SELECT ci.id_city id, ci.name, ci.ismetro FROM city ci 
                WHERE ci.id_city IN(".join(',',$cities).") 
                ORDER BY name";
            $data['city'] = Yii::app()->db->createCommand($sql)->queryAll();
        }*/ // endif



        // читаем тип копании
        $sql = "SELECT d.id,
                   d.id_par,
                   d.type,
                   d.name
            FROM user_attr_dict d
            WHERE d.id_par = 101
            ORDER BY d.name
            ";
        $data['cotype'] = Yii::app()->db->createCommand($sql)->queryAll();
        if( $cotype )
            foreach ($data['cotype'] as $key => &$val)
            {
                if( in_array($val['id'], $cotype) ) $val['selected'] = 1;
            } // end foreach

        return $data;
    }



    private function renderSQLFilter($inProps = [])
    {
        $data = array();
        // города
        $param = $inProps['filter']['cities'] ?: $param = Yii::app()->getRequest()->getParam('cities');
        if( $param ) $data['cities'] = $param;
        // тип компании
        $param = $inProps['filter']['cotype'] ?: $param = Yii::app()->getRequest()->getParam('cotype');
        if( $param ) $data['cotype'] = $param;
        // Quick search id компании или название
        $param = $inProps['filter']['qs'] ?: $param = Yii::app()->getRequest()->getParam('qs');
        if( $param ) { $data['qs'] = filter_var(trim($param), FILTER_SANITIZE_FULL_SPECIAL_CHARS); }


        // default
        $filter = [];

        // city filter
        if( !empty($data['cities']) ) 
            $filter[] = 'uc.id_city IN ('.join(',',$data['cities']).')';
        else
            $filter[] = 'uc.id_city IN ('.Subdomain::getCacheData()->strCitiesIdes.')';
        // company type
        if( !empty($data['cotype']) ) {
            $filter[] = 'e.type IN ('.join(',',$data['cotype']).')';
        }
        // company name
        if( !empty($data['qs']) ) {
            $filter[] = "e.name like '%".$data['qs']."%'";
        }

        $filter = count($filter) ? 'WHERE ' . join(' AND ', $filter) : '';

        return $filter;
    }



    private function searchEmployers($filter)
    {
        $limit = $this->limit;
        $offset = $this->offset;

        try
        {
            $res = (new Employer())->getEmployersQueries(array('page' => 'searchempl', 'filter' => $filter, 'offset' => $offset, 'limit' => $limit));
        }
        catch (Exception $e) {
            return array('error' => $e->getMessage());
        } // endtry



        $data['empls'] = array();
        foreach ($res as $key => $val)
        {
            if( !isset($data['empls'][$val['id']])) $data['empls'][$val['id']] = array('city' => array(), 'post' => array(), 'metroes' => array()) ;
            $data['empls'][$val['id']]['city'][$val['id_city']] = $val['ciname'];
//            $data['empls'][$val['id']]['post'][$val['id_attr']] = $val['pname'];
            if( $val['mid'] ) $data['empls'][$val['id']]['metroes'][$val['mid']] = $val['mname'];
            $data['empls'][$val['id']] = array_merge($data['empls'][$val['id']], $val);

            // дописать вывод дней, месяцев, лет
            if( !$data['empls'][$val['id']]['exp'] )
            {
                $datetime2 = new DateTime();
                $datetime1 = (new DateTime())->createFromFormat('d.m.Y', $val['crdate']);
                $interval = $datetime1->diff($datetime2);
                $diff = floor($interval->format('%a') / 365); $type = 3;
                if( !$diff ) { $diff = floor($interval->format('%a') / 30); $type = 2; }
                if( !$diff ) { $diff = floor($interval->format('%a')); $type = 1; }
                $data['empls'][$val['id']]['exp'] = array($diff, $type);
            } // endif
        } // end foreach

        $i = 1;
        $ret['empls'] = array();
        foreach ($data['empls'] as $key => $val) { $ret['empls'][$i] = $val; $i++; }


        return $ret;
    }

    
    public function searchEmplForFilter(){
        // города
        $Q1 = Yii::app()->db->createCommand()
            ->select('t.id_city id, t.name, t.seo_url')
            ->from('city t')
            ->limit(10000);
        $data['cities'] = $Q1->queryAll();

        $arTemp = array();
        foreach ($data['cities'] as $val){
            $arTemp[$val['id']] = array(
                'name' => $val['name'],
                'seo' => $val['seo_url']
            );
        }
        $data['cities'] = $arTemp;

        // тип копании
        $sql = "SELECT d.id, d.name
            FROM user_attr_dict d
            WHERE d.id_par = 101
            ORDER BY d.name
            ";
        $data['cotype'] = Yii::app()->db->createCommand($sql)->queryAll();
        $arTemp = array();
        foreach($data['cotype'] as $val)
            $arTemp[$val['id']] = $val['name'];
        $data['cotype'] = $arTemp;
        ksort($data['cotype']);
        unset($arTemp);

        //  компании
       /* $sql = "SELECT e.id_user idus, e.type, uc.id_city city
            FROM employer e
            INNER JOIN (
              SELECT DISTINCT e.id
              FROM employer e
              INNER JOIN user_city uc ON e.id_user = uc.id_user
              INNER JOIN user u ON u.id_user = e.id_user AND u.ismoder = 1             
              ORDER BY e.id DESC 
            ) t1 ON t1.id = e.id
            
          INNER JOIN user_city uc ON e.id_user = uc.id_user
          INNER JOIN city ci ON ci.id_city = uc.id_city

            ORDER BY e.id DESC";
        $res = Yii::app()->db->createCommand($sql);

        try{
            $data['items'] = $res->queryAll();
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }*/

        return $data;
    }


    public function searchFilterData(){
        $arrayA = json_decode(Yii::app()->getRequest()->getParam('all'), true);
        $arrayB = json_decode(Yii::app()->getRequest()->getParam('new'), true);
        $get = json_decode(Yii::app()->getRequest()->getParam('get'), true);

        $arNewTypes = array();
        $arNewCities = array();
        $newGet = array();
        foreach($get as $param){
            $name = str_replace('[]', '', $param['name']);
            $newGet[$name][] = $param['value'];
        }

        if(sizeof($arrayB)){
            foreach($arrayA['items'] as $itemA){
                foreach($arrayB as $itemB){
                    if($itemA['idus']==$itemB['idus']){
                        if(
                            !in_array($itemA['type'], $arNewTypes) && 
                            isset($arrayA['cotype'][$itemA['type']]) && 
                            $itemA['type']>0
                        )
                            $arNewTypes[$itemA['type']] = $arrayA['cotype'][$itemA['type']];

                        if(!in_array($itemA['city'], $arNewCities))
                            $arNewCities[$itemA['city']] = $arrayA['cities'][$itemA['city']];
                    } 
                }
            }
        }  
        else{
            $arNewTypes = $arrayA['cotype'];
            $arNewCities = $arrayA['cities'];
        }


        $data = array(
            'cotype' => $arNewTypes,
            'cities' => $arNewCities,
            'get' => $newGet
        );
        return $data; 
    }
}