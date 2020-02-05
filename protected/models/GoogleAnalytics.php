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
    $model = new UserRegisterPageCounter();
    $arRes = $model->getGoogleGoals();

    if(!count($arRes))
    {
      file_put_contents(
        __DIR__ . "/_GA_measurement_protocol_log.txt",
        date('Y.m.d H:i:s') . ' result_error' . PHP_EOL,
        FILE_APPEND
      );
      return false;
    }

    $arRequest = $arId = [];
    foreach ($arRes as $v)
    {
      if($v['page']!=UserRegister::$PAGE_USER_LEAD || count($arRequest)>=20) // не более 20и обращений в одном запросе
      {
        continue;
      }
      $arT = $arRequest;
      $arr = [
        'v' => '1',
        't' => 'event',
        'tid' => MainConfig::$GOOGLE_ANALYTICS_ID, // ID счетчика
        'cid' => $v['client'], // client ID
        'ec' => 'offline', // Категория
        'ea' => 'user' // Действие
      ];
      $arT[] = http_build_query($arr);
      $str = implode("\n",$arT);
      if((strlen($str) / 1000) < 16) // общий размер запроса не может превышать 16 Кб
      {
        $arRequest[] = http_build_query($arr);
        $arId[] = $v['id'];
      }
    }

    if(!count($arRequest))
    {
      file_put_contents(
        __DIR__ . "/_GA_measurement_protocol_log.txt",
        date('Y.m.d H:i:s') . ' filter_error'
        . PHP_EOL . print_r($arRequest, true) . PHP_EOL,
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

    $model->updateByPkArray(['is_send'=>1], $arId);
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
      date('Y.m.d H:i:s') . ' ' . $url . PHP_EOL . $query
      . PHP_EOL . print_r($result, true) . PHP_EOL,
      FILE_APPEND
    );
    return $result;
  }
}