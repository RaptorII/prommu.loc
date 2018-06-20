<?php

class SearchRule extends CBaseUrlRule {
    public function createUrl($manager,$route,$params,$ampersand)
    {
        return false;
    }

    public function parseUrl($manager,$request,$pathInfo,$rawPathInfo)
    {
        $path = explode('/', $request->getPathInfo());

        if(($path[0] != 'vacancy' && $path[0] != 'ankety') )
            return false;
        else
        {
            if(sizeof($path)>1 || sizeof($_GET)){
                if($path[0]=='vacancy')
                    return $this->parseVacation($path);

                if($path[0]=='ankety')
                    return $this->parsePromo($path);
            }
        }

        return false;
    }

    public function parseVacation($path)
    {
        array_shift($path);

        if(!sizeof($path) && strpos($_SERVER['REQUEST_URI'], '?')!==false){ // add params from GET
            $pos = strpos($_SERVER['REQUEST_URI'], '?');
            $get = substr($_SERVER['REQUEST_URI'], $pos+1);
            strpos($get, '/')===false ? $path[]=$get : $path=explode('/', $get);
        }

        $outData = array();

        $modelSearchVac = new SearchVac;
        $modelSearchVac->updateSeoValues();

        $templateUrlParams = array(
            'cities' => array(),
            'occupations' => array(),
            'others' => 0
        );

        foreach($path as $k => $v)
        {
            // salary per hour
            if(strpos($v, 'salary-hour') === 0)
            {
                $templateUrlParams['others']++;
                $salary = explode(',', str_replace('salary-hour=', '', $v));

                if(sizeof($salary) != 2)
                    continue;

                $outData['salary-hour'] = array(
                    'raw' => $v,
                    'param' => 'sphf='.(int)$salary[0].'&spht='.(int)$salary[1]
                );

                $outData['sr'] = array(
                    'raw' => 1,
                    'param' => 'sr=1'
                );
                continue;
            }

            // salary per week
            if(strpos($v, 'salary-week') === 0)
            {
                $templateUrlParams['others']++;

                $salary = explode(',', str_replace('salary-week=', '', $v));

                if(sizeof($salary) != 2)
                    continue;

                $outData['salary-week'] = array(
                    'raw' => $v,
                    'param' => 'spwf='.(int)$salary[0].'&spwt='.(int)$salary[1]
                );

                $outData['sr'] = array(
                    'raw' => 1,
                    'param' => 'sr=1'
                );
                continue;
            }

            // salary per month
            if(strpos($v, 'salary-month') === 0)
            {
                $templateUrlParams['others']++;

                $salary = explode(',', str_replace('salary-month=', '', $v));

                if(sizeof($salary) != 2)
                    continue;

                $outData['salary-month'] = array(
                    'raw' => $v,
                    'param' => 'spmf='.(int)$salary[0].'&spmt='.(int)$salary[1]
                );

                $outData['sr'] = array(
                    'raw' => 1,
                    'param' => 'sr=1'
                );
                continue;
            }
            // salary per visit
            if(strpos($v, 'salary-visit') === 0)
            {
                $templateUrlParams['others']++;

                $salary = explode(',', str_replace('salary-visit=', '', $v));

                if(sizeof($salary) != 2)
                    continue;

                $outData['salary-visit'] = array(
                    'raw' => $v,
                    'param' => 'spvf='.(int)$salary[0].'&spvt='.(int)$salary[1]
                );

                $outData['sr'] = array(
                    'raw' => 1,
                    'param' => 'sr=1'
                );
                continue;
            }

            // age
            if(strpos($v, 'age') === 0)
            {
                $templateUrlParams['others']++;
                $age = explode('-', str_replace('age=', '', $v));
                if(sizeof($age) != 2)
                    continue;

                $outData['age'] = array(
                    'raw' => $v,
                    'param' => 'af='.(int)$age[0].'&at='.(int)$age[1]
                );
                continue;
            }


            // bt
            if($v == 'partial' || $v == 'fulltime')
            {
                $templateUrlParams['others']++;
                $outData['bt'] = array(
                    'raw' => $v,
                    'param' => 'bt='.(($v == 'partial') ? 1 : 2)
                );
                continue;
            }

            // sex ?
            if($v == 'male' || $v == 'female')
            {
                $templateUrlParams['others']++;
                $outData['sex'] = array(
                    'raw' => $v,
                    'param' => 'sex='.($v=='male' ? 1 : 2)
                );
                continue;
            }

            if($v == 'smart')
            {
                $templateUrlParams['others']++;
                $outData['smart'] = array(
                    'raw' => $v,
                    'param' => 'smart=on'
                );
                continue;
            }

            if($v == 'card')
            {
                $templateUrlParams['others']++;
                $outData['card'] = array(
                    'raw' => $v,
                    'param' => 'bcard=1'
                );
                continue;
            }

            if($v == 'cardprommu')
            {
                $templateUrlParams['others']++;
                $outData['cardPrommu'] = array(
                    'raw' => $v,
                    'param' => 'pcard=1'
                );
                continue;
            }

            // occupation ?
            $tmpV = explode(',', $v);
            $mayBeOne = false;

            foreach($tmpV as $k => $tmpV2)
            {
                if($occupation = $modelSearchVac->getOccupationByField('comment', $tmpV2))
                {
                    $outData['occupation'.$k] = array(
                        'raw' => $v,
                        'param' => 'post['.$occupation['id'].']=on'
                    );

                    $mayBeOne = true;
                    $templateUrlParams['occupations'][] = $occupation['name'];
                    continue;
                }
                else
                {
                    if($mayBeOne)
                    {
                        $outData['occupationpoself'] = array(
                            'raw' => $v,
                            'param' => 'poself='.$tmpV2
                        );
                    }
                }
            }

            // city ?
            $tmpV = explode(',', $v);

            foreach($tmpV as $k => $tmpV2)
            {
                if($city = $modelSearchVac->getCityByField('seo_url', $tmpV2))
                {
                    $outData['city'.$k] = array(
                        'raw' => $v,
                        'param' => 'cities[]='.$city['id']
                    );
                    $templateUrlParams['cities'][] = $city['name'];
                    continue;
                }
            }
        }

        //if(sizeof($outData))
        if(sizeof($outData) || (sizeof($path)==1 && (int)$path[0]>0)) // new IF
        {
            $getParams = array();
            foreach($outData as $dt)
                $getParams[] = $dt['param'];

            $url = '?'.implode('&', $getParams);
            $query_str = parse_url($url, PHP_URL_QUERY);
            parse_str($query_str, $query_params);

            $_GET['seo_builded2'] = 1;
            $_GET['template_url_params'] = $templateUrlParams;

            foreach($query_params as $k => $v)
                $_GET[$k] = $v;

            return 'site/vacancy';
        }

        return false;
    }

    public function parsePromo($path)
    {
        array_shift($path);

        if(!sizeof($path) && strpos($_SERVER['REQUEST_URI'], '?')!==false){ // add params from GET
            $pos = strpos($_SERVER['REQUEST_URI'], '?');
            $get = substr($_SERVER['REQUEST_URI'], $pos+1);
            strpos($get, '/')===false ? $path[]=$get : $path=explode('/', $get);
        }

        $outData = array();

        $modelSearchPromo = new SearchPromo;
        // $modelSearchPromo->updateSeoValues();

        $templateUrlParams = array(
            'cities' => array(),
            'occupations' => array(),
            'others' => 0,
            'promo' => true
        );

        foreach($path as $k => $v)
        {
            // car
            if($v == 'car')
            {
                $templateUrlParams['others']++;
                $outData['car'] = array(
                    'raw' => $v,
                    'param' => 'avto=1'
                );
                continue;
            }

            if($v == 'smart')
            {
                $templateUrlParams['others']++;
                $outData['smart'] = array(
                    'raw' => $v,
                    'param' => 'smart=1'
                );
                continue;
            }

            if($v == 'card')
            {
                $templateUrlParams['others']++;
                $outData['card'] = array(
                    'raw' => $v,
                    'param' => 'card=1'
                );
                continue;
            }
            
            
            if($v == 'cardprommu')
            {
                $templateUrlParams['others']++;
                $outData['cardPrommu'] = array(
                    'raw' => $v,
                    'param' => 'cardPrommu=1'
                );
                continue;
            }

            // medbook
            if($v == 'medbook')
            {
                $templateUrlParams['others']++;
                $outData['medbook'] = array(
                    'raw' => $v,
                    'param' => 'mb=1'
                );
                continue;
            }

            // sex ?
            if($v == 'male')
            {
                $templateUrlParams['others']++;
                $outData['sex_male'] = array(
                    'raw' => $v,
                    'param' => 'sm=1'
                );
                continue;
            }

            if($v == 'female')
            {
                $templateUrlParams['others']++;
                $outData['sex_female'] = array(
                    'raw' => $v,
                    'param' => 'sf=1'
                );
                continue;
            }

            // фамилия
            if(strpos($v, 'name') === 0)
            {
                $templateUrlParams['others']++;
                $name = str_replace('name=', '', $v);

                // $outData['name'] = array(
                //     'raw' => $v,
                //     'param' => 'name='.$name
                // );
                $outData['name'] = array(
                    'raw' => $v,
                    'param' => 'qs='.$name
                );
                continue;
            }

            // metro ?
            $tmpV = explode(',', $v);

            foreach($tmpV as $k => $tmpV2)
            {
                if($metro = $modelSearchPromo->getMetroByField('seo_url', $tmpV2))
                {
                    $outData['metro'.$k] = array(
                        'raw' => $v,
                        'param' => 'metro[]='.$metro['id']
                    );
                    $templateUrlParams['others']++;
                    continue;
                }
            }

            // occupation ?
            $tmpV = explode(',', $v);
            foreach($tmpV as $k => $tmpV2)
            {
                if($occupation = $modelSearchPromo->getOccupationByField('comment', $tmpV2))
                {
                    $outData['occupation'.$k] = array(
                        'raw' => $v,
                        'param' => 'posts[]='.$occupation['id']
                    );
                    $templateUrlParams['occupations'][] = $occupation['name'];
                    continue;
                }
            }

            // city ?
            $tmpV = explode(',', $v);
            foreach($tmpV as $k => $tmpV2)
            {
                if($city = $modelSearchPromo->getCityByField('seo_url', $tmpV2))
                {
                    $outData['city'.$k] = array(
                        'raw' => $v,
                        'param' => 'cities[]='.$city['id']
                    );
                    $templateUrlParams['cities'][] = $city['name'];
                    continue;
                }
            }
        }

        if(sizeof($outData))
        {
            $getParams = array();
            foreach($outData as $dt)
                $getParams[] = $dt['param'];

            $url = '?'.implode('&', $getParams);
            $query_str = parse_url($url, PHP_URL_QUERY);
            parse_str($query_str, $query_params);

            $_GET['seo_builded2'] = 1;
            $_GET['template_url_params'] = $templateUrlParams;

            foreach($query_params as $k => $v)
                $_GET[$k] = $v;

            return 'site/ankety';
        }

        return false;
    }
}