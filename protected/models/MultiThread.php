<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 19.07.2019
 * Time: 15:46
 */

class MultiThread extends Thread
{
  public $curlParams;
  public $arProxy;
  public $inn;
  public $result;

  public function __construct($inn, $params, $arProxy)
  {
    $this->inn = $inn;
    $this->curlParams = $params;
    $this->arProxy = $arProxy;
  }

  public function run()
  {
    $this->result = ['inn' => $this->inn];

    do
    {
      $this->curlParams[CURLOPT_PROXY] = $this->arProxy[array_rand($this->arProxy)];
      $ch = curl_init(MainConfig::$RESOURCE_SELF_EMPLOYED);
      curl_setopt_array($ch, $this->curlParams);
      $this->result['response'] = curl_exec($ch);
      $this->result = json_decode($this->result['response']);
      $error = curl_error($ch);
      $this->result['error'] = ($error ?: false);
      $this->result['headers'] = curl_getinfo($ch);
      curl_close($ch);
    }while($this->result['error']!=false);
  }
}