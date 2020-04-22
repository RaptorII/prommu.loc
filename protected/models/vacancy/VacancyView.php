<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 18.03.2020
 * Time: 12:58
 */

class VacancyView
{
  public static function getAge($from, $to)
  {
    $result = 'от ' . $from . ' ';
    if($to)
    {
      return $result . ' до ' . $to . ' ' . Share::endingYears($to);
    }
    else
    {
      return $result . Share::endingYears($from);
    }
  }

  public static function getGender($isman, $iswoman)
  {
    if($isman && $iswoman)
    {
      return 'Мужчины и Женщины';
    }
    elseif($isman)
    {
      return 'Мужчины';
    }
    else
    {
      return 'Женщины';
    }
  }

  public static function getSalary($shour, $sweek, $smonth, $svisit)
  {
    $arRes = ['salary'=>'', 'salary_type'=>'', 'full'=>''];
    if($shour>0)
    {
      $arRes['salary'] = $shour;
      $arRes['salary_type'] = 0;
    }
    elseif($sweek>0)
    {
      $arRes['salary'] = $sweek;
      $arRes['salary_type'] = 1;
    }
    elseif ($smonth>0)
    {
      $arRes['salary'] = $smonth;
      $arRes['salary_type'] = 2;
    }
    elseif ($svisit>0)
    {
      $arRes['salary'] = $svisit;
      $arRes['salary_type'] = 3;
    }
    $arRes['full'] = $arRes['salary'] . ' ' . Vacancy::SALARY_TYPE[$arRes['salary_type']];
    return $arRes;
  }
  /**
   * @param $name
   * @param bool $class
   * @return string
   */
  public static function createVacancyLink($name, $class=false)
  {
    $arParams = [];
    if(Share::$UserProfile->accessToFreeVacancy===true)
    {
      $arParams['class'] = ($class ? $class : '');
    }
    else
    {
      $arParams['class'] = ($class ? $class.' popup__paid-vacancy' : 'popup__paid-vacancy');
      $arParams['data-message'] = "Уважаемый, «" . Share::$UserProfile->exInfo->name
        . "»<br>Добавление новой вакансии является платной услугой.<br>Желаете продолжить?<br><a href='"
        . MainConfig::$PAGE_VACPUB . "' class='btn__orange'>Продолжить</a>";
    }

    return CHtml::link($name, MainConfig::$PAGE_VACPUB, $arParams);
  }
  /**
   * @param $arCities - array of city.id_city
   * @return bool|string
   */
  public static function getSalaryByHints($arCities)
  {
    if(!count($arCities))
    {
      return false;
    }
    $result = [];
    if(in_array(1307, $arCities)) // МСК
    {
      $result[] = 'Средняя оплата по Москве<br> <b>280</b> руб/час<br> <b>4480</b> руб/неделя<br> <b>17920</b> руб/месяц<br> <b>1000</b> руб/посещение';
    }
    if(in_array(1838, $arCities)) // СПБ
    {
      $result[] = 'Средняя оплата по Санкт-Петербургу<br> <b>200</b> руб/час<br> <b>3200</b> руб/неделя<br> <b>12800</b> руб/месяц<br> <b>700</b> руб/посещение';
    }

    $cnt = 0;
    foreach ($arCities as $v)
    {
      in_array($v,[1307,1838]) && $cnt++;
    }

    if(count($arCities)>$cnt) // Регионы
    {
      $result[] = 'Средняя оплата по регионам<br> <b>150</b> руб/час<br> <b>2400</b> руб/неделя<br> <b>9600</b> руб/месяц<br> <b>500</b> руб/посещение';
    }

    return implode('<br>',$result);
  }
}