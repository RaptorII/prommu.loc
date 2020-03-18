<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 16.03.2020
 * Time: 10:30
 */

class VacancyEdit
{
  const MODULE_1 = '../user/vacancy/edit/module_1';
  const MODULE_2 = '../user/vacancy/edit/module_2';

  function __construct(&$object)
  {
    $rq = Yii::app()->getRequest();
    $module = $rq->getParam('module');

    if($module==1)
    {
      // Заголовок
      $value = VacancyCheckFields::checkTitle($rq->getParam('title'));
      $value ? $object->data->title=$value : $object->errors['title']=true;
      // Должность
      $value = VacancyCheckFields::checkPost($rq->getParam('post'));
      $value ? $object->data->post=$value : $object->errors['post']=true;
      // Опыт работы
      $value = VacancyCheckFields::checkExperience($rq->getParam('exp'));
      $value ? $object->data->exp=$value : $object->errors['exp']=true;
      // Возраст
      $value = VacancyCheckFields::checkAge($rq->getParam('age_from'), $rq->getParam('age_to'));
      if($value)
      {
        $object->data->agefrom = $value[0];
        $object->data->ageto = $value[1];
        $object->data->age = VacancyView::getAge($value[0], $value[1]);
      }
      else
      {
        $object->errors['age'] = true;
      }
      // Пол
      $value = VacancyCheckFields::checkGender($rq->getParam('gender'));
      if($value)
      {
        $object->data->isman = $value[0]=='man';
        $object->data->iswoman = $value[1]=='woman';
      }
      else
      {
        $object->errors['gender'] = true;
      }
      // Тип работы
      $value = VacancyCheckFields::checkWorkType($rq->getParam('istemp'));
      $value===false ? $object->errors['istemp']=true : $object->data->istemp=$value;
    }
  }
}