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
      return $result . $to . ' ' . Share::endingYears($to);
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
}