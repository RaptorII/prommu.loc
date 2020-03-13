<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 04.02.2020
 * Time: 9:30
 */

class GoogleAnalytics
{
  public static function MeasurementProtocol()
  {
    $hour = date('G');
    $min = date('i');
    if(intval($hour)==0 && intval($min)<5) // из-за неточностей крона проверяем реги в последние минуты предыдущего дня
    {
      $date = strtotime('yesterday');
    }
    else
    {
      $date = strtotime('today');
    }

    $bDate = date('Y-m-d H:i:s',$date);
    $eDate = date('Y-m-d H:i:s',($date + 86400));

    $arUser = Yii::app()->db->createCommand()
      ->select('ur.user')
      ->from('user u')
      ->join('user_register ur','ur.id_user=u.id_user')
      ->where(
        'u.crdate between :bdate and :edate',
        [':bdate'=>$bDate, ':edate'=>$eDate]
      )
      ->queryColumn();

    $arRes = Yii::app()->db->createCommand()
      ->select('client')
      ->from('user_client')
      ->where([
        'and',
        'is_send_to_ga=0',
        ['in','user',$arUser]
      ])
      ->queryColumn();

    if(!count($arRes))
    {
      file_put_contents(
        __DIR__ . "/_GA_measurement_protocol_log.txt",
        date('Y.m.d H:i:s') . ' search_error' . PHP_EOL,
        FILE_APPEND
      );
      return false;
    }

    $arRequest = $arUpdate = [];
    foreach ($arRes as $client)
    {
      if(count($arRequest)>=20) // не более 20и обращений в одном запросе
      {
        continue;
      }
      $arr = [
        'v' => '1',
        't' => 'event',
        'tid' => MainConfig::$GOOGLE_ANALYTICS_ID, // ID счетчика
        'cid' => $client, // client ID
        'ec' => 'offline', // Категория
        'ea' => 'user' // Действие
      ];

      array_push($arRequest, $arr);
      $str = implode("\n",$arRequest);
      if(strlen($str) < 16384) // общий размер запроса не может превышать 16 Кб
      {
        $arRequest[] = http_build_query($arr);
        $arUpdate[] = $client;
      }
      else
      {
        array_pop($arRequest);
      }
    }

    if(!count($arRequest))
    {
      file_put_contents(
        __DIR__ . "/_GA_measurement_protocol_log.txt",
        date('Y.m.d H:i:s') . ' filter_error' . PHP_EOL,
        FILE_APPEND
      );
      return false;
    }

    $url = MainConfig::$GOOGLE_ANALYTICS_MULTIPLE;
    $query = implode("\n",$arRequest);
    $curl = curl_init();
    curl_setopt_array(
      $curl,
      [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
        CURLOPT_HTTPHEADER => ['Content-type: application/x-www-form-urlencoded'],
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_POST, true,
        CURLOPT_POSTFIELDS => $query
      ]
    );
    $result = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($result,3);

    Yii::app()->db->createCommand()
      ->update(
        'user_client',
        ['is_send_to_ga'=>1],
        ['in','client',$arUpdate]
      );
    /*if(count($result['hitParsingResult']))
    {
      $arT = [];
      foreach ($result['hitParsingResult'] as $key => $v)
      {
        if(!$v['valid'])
        {
          continue;
        }

        foreach ($arRes as $j)
        {
          if(strripos($v['hit'],'cid='.$j['client'])!==false)
          {
            $arT[] = $arId[$key];
          }
        }
      }
      $arId = $arT;

      if(count($arId))
      {
        $model->updateByPkArray(['is_send'=>1], $arId);
      }
    }*/

    file_put_contents(
      __DIR__ . "/_GA_measurement_protocol_log.txt",
      date('Y.m.d H:i:s') . ' ' . $url . ' ' . count($arUpdate) . ' ' . implode(', ',$arUpdate)
      . PHP_EOL,
      FILE_APPEND
    );
    return $result;
  }
}