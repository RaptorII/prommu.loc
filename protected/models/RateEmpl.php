<?php

/**
 * Модель рейтинга работодателя
 */
class RateEmpl extends Rate
{
    function __construct($inProps)
    {
        parent::__construct($inProps);

        $this->viewTpl = MainConfig::$VIEWS_RATE_EMPL;
    }



	/**
	 * Данные для view
	 */
	public function getViewData()
	{
        if($this->UserProfile->id)
        {
            // получаем общий рейтинг
            $data['rating'] = $this->getPointRate($this->UserProfile->id );
            $data['rating'] = $this->prepareProfileCommonRate($data['rating']);


            // рейтинг выставленный пользователями, только для своего профиля
            if( $this->id == $this->UserProfile->id )
            {
                $data['rateByUser'] = $this->getRateByUser();
//                $this->setLastRateDate($data['rateByUser']);
            } // endif
        }
        else
        {
            $data = array();
        } // endif

        return $data;
	}


    
    
    /**
     * Получаем данные рейтинга пользователя
     */
    public function getPointRate($inID)
    {
        $id = $inID;

        // получаем рейтинг и уровень характеристик
        $sql = <<<EOT
SELECT sum(m.rate) as rate, sum(m.rate_neg) as rate_neg, m.id_point, m.descr 
FROM (
  SELECT
    CASE WHEN rd.point >= 0 THEN rd.point ELSE 0 END AS rate,
    CASE WHEN rd.point < 0 THEN rd.point ELSE 0 END AS rate_neg,
    rd.id_point,
    r.descr
  FROM rating_details rd,
       point_rating r
  WHERE id_user = {$id}
  AND r.id = rd.id_point
) m 
GROUP BY m.id_point 
EOT;
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['rate'] = $res;


        // получение название характеристик рейтинга
        $sql = "SELECT id, descr FROM point_rating WHERE grp = 2";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();
        $data['rateNames'] = array();
        foreach ($res as $key => $val) { $data['rateNames'][$val['id']] = $val['descr']; }

        return $data;
    }

    /**
     * Получаем данные рейтинга пользователя
     */
    public function getDynamicRate($inID)
    {
        $id = $inID;

        // получаем рейтинг и уровень характеристик
        $sql = <<<EOT
SELECT sum(m.rate) as rate, sum(m.rate_neg) as rate_neg,m.crdate, m.id_point, m.descr 
FROM (
  SELECT
    CASE WHEN rd.point >= 0 THEN rd.point ELSE 0 END AS rate,
    CASE WHEN rd.point < 0 THEN rd.point ELSE 0 END AS rate_neg,
    rd.id_point,
    r.descr,
    rd.crdate
  FROM rating_details rd,
       point_rating r
  WHERE rd.id_userf != {$id} AND rd.id_user = {$id} 
  AND r.id = rd.id_point
) m 
GROUP BY m.crdate 
EOT;
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $data['rate'] = $res;

        return $data;
    }
    
    /**
     * Готовим dynamic рейтинг к выводу
     */
    public function prepareProfileCommonDynamicRate($inData)
    {


        // sum all pos and neg rate
        $rate = array(0, 0);
        $maxPointRate = 0;
        foreach ($inData['rate'] as $key => $val)
        {
            // масимальный рейтинг
            if( $val['rate'] - abs($val['rate_neg']) > $maxPointRate) $maxPointRate = $val['rate'] - abs($val['rate_neg']);

            // сумарные рейтинги по всем атрибутам
            $rate[0] += $val['rate'];
            $rate[1] += abs($val['rate_neg']);
            
            $date = date('Y-m-d',  strtotime($val['crdate']));
            // рейтинги по атрибутам
            $pointRate[$date][0] += $val['rate'];
            $pointRate[$date][1] += abs($val['rate_neg']);
        } // end foreach

        return array('pointRate' => $pointRate,
                'rate' => $rate,
                'countRate' => $rate[0] - $rate[1],
                'maxPointRate' => $maxPointRate,
                'rateNames' => $inData['rateNames'],
            );
    }

    /**
     * Готовим рейтинг к выводу
     */
    public function prepareProfileCommonRate($inData)
    {
        foreach ($inData['rateNames'] as $key => $val)
        {
            $pointRate[$key] = array(0, 0);
        } // end foreach


        // sum all pos and neg rate
        $rate = array(0, 0);
        $maxPointRate = 0;
        foreach ($inData['rate'] as $key => $val)
        {
            // масимальный рейтинг
            if( $val['rate'] - abs($val['rate_neg']) > $maxPointRate) $maxPointRate = $val['rate'] - abs($val['rate_neg']);

            // сумарные рейтинги по всем атрибутам
            $rate[0] += $val['rate'];
            $rate[1] += abs($val['rate_neg']);

            // рейтинги по атрибутам
            $pointRate[$val['id_point']][0] += $val['rate'];
            $pointRate[$val['id_point']][1] += abs($val['rate_neg']);
        } // end foreach

        return array('pointRate' => $pointRate,
                'rate' => $rate,
                'countRate' => $rate[0] - $rate[1],
                'maxPointRate' => $maxPointRate,
                'rateNames' => $inData['rateNames'],
            );
    }



    /**
     * Готовим рейтинг к выводу
     */
    public function getRateByUser()
    {
        // получаем рейтинг и уровень характеристик
        $sql = "SELECT ra.id_vac, ra.id_userf idus, ra.id_point, DATE_FORMAT(ra.crdate, '%d.%m.%Y %H:%i:%s') crdate, ra.point
              , CONCAT(r.firstname, ' ', r.lastname) fio, r.photo
            FROM rating_details ra
            INNER JOIN (
              SELECT DISTINCT ra.id_userf
              FROM rating_details ra
              INNER JOIN resume r ON r.id_user = ra.id_userf
              WHERE ra.id_user = {$this->id}
              LIMIT 0, 20
            ) t1 ON t1.id_userf = ra.id_userf
            INNER JOIN resume r ON r.id_user = ra.id_userf
            WHERE ra.id_user = {$this->id} ";
        $res = Yii::app()->db->createCommand($sql)->queryAll();

        $datetime2 = $this->UserProfile->getUserStatus('lastRateDate');
        $timezone = new DateTimeZone("Europe/Kiev");
        if( $datetime2 ) ($datetime2 = (new DateTime)->createFromFormat('Y-m-d H:i:s', $datetime2, $timezone));
        else ($datetime2 = (new DateTime)->createFromFormat('Y-m-d H:i:s', '2000-01-01 01:01:01', $timezone));
        $flagHasNew = 0;
        foreach ($res as $key => $val)
        {
            // проверяем на просмотренные рейтинги по дате
            if( $datetime2 )
            {
                $datetime1 = (new DateTime())->createFromFormat('d.m.Y H:i:s', $val['crdate'], $timezone);
                $interval = $datetime1->diff($datetime2);
                $new = (int)$interval->format('%R%i');
            }
            else $new = 1;

            if( $new < 0 && !$flagHasNew ) ($flagHasNew = 1);

            $data[$val['id_vac']][$val['id_point']] = array_merge($val, array('new' => $new));
        } // endforeach


        // есть непросмотренные рейтинги
        if( $flagHasNew ) $this->setLastRateDate();

        return $data;
    }



    /**
     * Устанавливаем дату последнего просмотра рейтинга пользователем
     */
     private function setLastRateDate()
     {
         $this->UserProfile->setUserStatus(array('key' => 'lastRateDate', 'val' => date("Y-m-d H:i:s")));
     }
}
