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
}