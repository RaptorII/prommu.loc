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
  const MODULE_3 = '../user/vacancy/edit/module_3';
  const MODULE_4 = '../user/vacancy/edit/module_4';

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
    if($module==3)
    {
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
      // Дополнительные параметры
      $arAttr = $rq->getParam('attributes');
      // Рост
      $value = VacancyCheckFields::checkNum($arAttr['manh'], 3);
      if($value)
      {
        $object->data->properties['manh'] = [
          'id' => $object->attributes->items['manh']['id'],
          'key' => 'manh',
          'name' => $object->attributes->items['manh']['name'],
          'value' => $value
        ];
        $object->data->add_props = true;
      }
      // Вес
      $value = VacancyCheckFields::checkNum($arAttr['weig'], 3);
      if($value)
      {
        $object->data->properties['weig'] = [
          'id' => $object->attributes->items['weig']['id'],
          'key' => 'weig',
          'name' => $object->attributes->items['weig']['name'],
          'value' => $value
        ];
        $object->data->add_props = true;
      }
      // Цвет волос
      if(array_key_exists($arAttr['hcolor'],$object->attributes->lists['hcolor']))
      {
        $object->data->properties['hcolor'] = [
          'id' => $arAttr['hcolor'],
          'key' => 'hcolor',
          'name' => $object->attributes->items['hcolor']['name'],
          'value' => $object->attributes->lists['hcolor'][$arAttr['hcolor']]
        ];
        $object->data->add_props = true;
      }
      // Длина волос
      if(array_key_exists($arAttr['hlen'],$object->attributes->lists['hlen']))
      {
        $object->data->properties['hlen'] = [
          'id' => $arAttr['hlen'],
          'key' => 'hlen',
          'name' => $object->attributes->items['hlen']['name'],
          'value' => $object->attributes->lists['hlen'][$arAttr['hlen']]
        ];
        $object->data->add_props = true;
      }
      // Цвет глаз
      if(array_key_exists($arAttr['ycolor'],$object->attributes->lists['ycolor']))
      {
        $object->data->properties['ycolor'] = [
          'id' => $arAttr['ycolor'],
          'key' => 'ycolor',
          'name' => $object->attributes->items['ycolor']['name'],
          'value' => $object->attributes->lists['ycolor'][$arAttr['ycolor']]
        ];
        $object->data->add_props = true;
      }
      // Объем груди
      if(array_key_exists($arAttr['chest'],$object->attributes->lists['chest']))
      {
        $object->data->properties['chest'] = [
          'id' => $arAttr['chest'],
          'key' => 'chest',
          'name' => $object->attributes->items['chest']['name'],
          'value' => $object->attributes->lists['chest'][$arAttr['chest']]
        ];
        $object->data->add_props = true;
      }
      // Объем талии
      if(array_key_exists($arAttr['waist'],$object->attributes->lists['waist']))
      {
        $object->data->properties['waist'] = [
          'id' => $arAttr['waist'],
          'key' => 'waist',
          'name' => $object->attributes->items['waist']['name'],
          'value' => $object->attributes->lists['waist'][$arAttr['waist']]
        ];
        $object->data->add_props = true;
      }
      // Объем бедер
      if(array_key_exists($arAttr['thigh'],$object->attributes->lists['thigh']))
      {
        $object->data->properties['thigh'] = [
          'id' => $arAttr['thigh'],
          'key' => 'thigh',
          'name' => $object->attributes->items['thigh']['name'],
          'value' => $object->attributes->lists['thigh'][$arAttr['thigh']]
        ];
        $object->data->add_props = true;
      }
    }
    if($module==4)
    {
      // Описание
      $value = VacancyCheckFields::checkTextarea($rq->getParam('requirements'), true);
      $value ? $object->data->requirements=$value : $object->errors['requirements']=true;
      // Обязанности
      $object->data->duties = VacancyCheckFields::checkTextarea($rq->getParam('duties'));
      // Условия
      $object->data->conditions = VacancyCheckFields::checkTextarea($rq->getParam('conditions'));
    }
  }
}