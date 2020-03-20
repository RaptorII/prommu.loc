<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 16.03.2020
 * Time: 10:19
 */

class VacancyCheckFields
{
  const TITLE_LENGTH = 70; // допустимая длинна заголовка
  const MIN_AGE_FROM = 14; // минимально допустимый возраст
  /**
   * @param $value - string
   * @return bool|string - допустимое значение, или false в случае ошибки
   */
  public static function checkTitle($value) // Заголовок
  {
    $value = trim($value);
    $value = preg_replace("/[^\w\d\s\-\,\.\+\#\!\?\%\:\;\(\)]/u", '', $value);
    $value = substr($value,0,self::TITLE_LENGTH);
    return (!strlen($value) ? false : $value);
  }
  /**
   * @param $value - array
   * @return bool|array - допустимое значение, или false в случае ошибки
   */
  public static function checkPost($value) // Должность
  {
    $arPost = Vacancy::getPostsList();
    if(!is_array($value) || !count($value))
    {
      return false;
    }
    else
    {
      $bError = false;
      foreach ($value as $v)
      {
        if(!array_key_exists($v, $arPost))
        {
          $bError = true;
          break;
        }
      }
      return $bError ? false : $value;
    }
  }
  /**
   * @param $value1 - string
   * @param $value2 - string
   * @return bool|array(ageFrom, ageTo) - допустимое значение, или false в случае ошибки
   */
  public static function checkAge($value1, $value2) // Возраст
  {
    $value1 = intval($value1);
    $value2 = intval($value2);
    if(!$value1 || $value1<self::MIN_AGE_FROM)
    {
      return false;
    }
    elseif($value2 && ($value1>$value2))
    {
      return false;
    }
    else
    {
      !$value2 && $value2='';
      return [$value1, $value2];
    }
  }
  /**
   * @param $value - array
   * @return bool|array - допустимое значение, или false в случае ошибки
   */
  public static function checkGender($value) // Пол
  {
    if(!in_array('man',$value) && !in_array('woman',$value))
    {
      return false;
    }
    else
    {
      return $value;
    }
  }
  /**
   * @param $value - string
   * @param $isRequired - bool
   * @return bool|string - допустимое значение, или false в случае ошибки
   */
  public static function checkTextarea($value, $isRequired=false) // Описание | Обязанности | Условия
  {
    $value = trim($value);
    $value=="<br>" && $value="";

    if(!strlen($value) && $isRequired)
    {
      return false;
    }
    $value = htmlspecialchars($value,ENT_QUOTES);
    $value = stripslashes($value);
    return $value;
  }
  /**
   * @param $value - string
   * @param $maxLimit - bool | integer
   * @return string - допустимое значение
   */
  public static function checkNum($value, $maxLimit=false)
  {
    $value = intval($value);
    if($maxLimit)
    {
      $value = substr($value,0, $maxLimit);
    }
    return $value==0 ? false : $value;
  }
  /**
   * @param $value - integer
   * @return bool|integer - допустимое значение, или false в случае ошибки
   */
  public static function checkSalary($value)
  {
    $value = intval($value);
    return (!$value || $value>=1000000) ? false : $value;
  }
  /**
   * @param $value - integer
   * @param $type - string
   * @return bool|integer - допустимое значение, или false в случае ошибки
   */
  public static function checkList($value, $type)
  {
    $value = intval($value);
    $arCheck = [];
    switch ($type)
    {
      case 'experience': $arCheck = Vacancy::EXPERIENCE; break;
      case 'work_type': $arCheck = Vacancy::WORK_TYPE; break;
      case 'self_employed': $arCheck = Vacancy::SELF_EMPLOYED; break;
      case 'salary_type': $arCheck = Vacancy::SALARY_TYPE; break;
      case 'salary_time': $arCheck = Vacancy::getAllAttributes()->lists['paylims']; break;
    }
    return (!array_key_exists($value, $arCheck) ? false : $value);
  }
}