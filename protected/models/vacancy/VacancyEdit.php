<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 16.03.2020
 * Time: 10:30
 */

class VacancyEdit
{
  function __construct(&$object)
  {
    $rq = Yii::app()->getRequest();
    $module = $rq->getParam('module');

    if($module==2)
    {
      // Заголовок
      $value = VacancyCheckFields::checkTextField($rq->getParam('title'));
      $value ? $object->data->title=$value : $object->errors['title']=true;
      // Должность
      $value = VacancyCheckFields::checkPost($rq->getParam('post'));
      $value ? $object->data->post=$value : $object->errors['post']=true;
      // Опыт работы
      $value = VacancyCheckFields::checkList($rq->getParam('exp'),'experience');
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
      $value = VacancyCheckFields::checkList($rq->getParam('istemp'),'work_type');
      $value===false ? $object->errors['istemp']=true : $object->data->istemp=$value;
    }
    if($module==4)
    {
      // Должность
      $value = VacancyCheckFields::checkPost($rq->getParam('post'));
      $value ? $object->data->post=$value : $object->errors['post']=true;
      // Опыт работы
      $value = VacancyCheckFields::checkList($rq->getParam('exp'),'experience');
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
      $value = VacancyCheckFields::checkList($rq->getParam('istemp'),'work_type');
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
    if($module==5)
    {
      // Описание
      $value = VacancyCheckFields::checkTextarea($rq->getParam('requirements'), true);
      $value ? $object->data->requirements=$value : $object->errors['requirements']=true;
      // Обязанности
      $object->data->duties = VacancyCheckFields::checkTextarea($rq->getParam('duties'));
      // Условия
      $object->data->conditions = VacancyCheckFields::checkTextarea($rq->getParam('conditions'));
    }
    if($module==6)
    {
      // Налоговый статус
      $value = VacancyCheckFields::checkList($rq->getParam('self_employed'),'self_employed');
      $value===false ? $object->errors['self_employed']=true : $object->data->self_employed=$value;
    }
    if($module==7)
    {
      // Тип оплаты и Заработная плата
      $type = VacancyCheckFields::checkList($rq->getParam('salary_type'), 'salary_type');
      $salary = VacancyCheckFields::checkSalary($rq->getParam('salary'));
      if($type===false)
      {
        $object->errors['salary_type'] = true;
      }
      elseif ($salary===false)
      {
        $object->errors['salary'] = true;
      }
      else
      {
        $object->data->shour = $type==0 ? $salary : 0;
        $object->data->sweek = $type==1 ? $salary : 0;
        $object->data->smonth = $type==2 ? $salary : 0;
        $object->data->svisit = $type==3 ? $salary : 0;
      }
      // Сроки оплаты
      if(!empty($rq->getParam('salary_time_custom')))
      {
        $value = VacancyCheckFields::checkTextField($rq->getParam('salary_time_custom'));
        if($value===false)
        {
          $object->errors['salary_time_custom']=true;
        }
        else
        {
          $object->data->properties['cpaylims'] = [
            'id' => $object->attributes->items['cpaylims']['id'],
            'key' => 'cpaylims',
            'name' => $object->attributes->items['cpaylims']['name'],
            'value' => $value
          ];
        }
      }
      else
      {
        $value = VacancyCheckFields::checkList($rq->getParam('salary_time'),'salary_time');
        if($value===false)
        {
          $object->errors['salary_time']=true;
        }
        else
        {
          $object->data->properties['paylims'] = [
            'id' => $value,
            'key' => 'paylims',
            'name' => Vacancy::getAllAttributes()->items['paylims']['name'],
            'value' => Vacancy::getAllAttributes()->lists['paylims'][$value]
          ];
        }
      }
      // Комментарии по оплате
      $value = VacancyCheckFields::checkTextarea($rq->getParam('salary_comment'));
      if(!empty($value)) // Комментарии по оплате
      {
        $object->data->properties['salary-comment'] = [
          'id' => Vacancy::getAllAttributes()->items['salary-comment']['id'],
          'key' => 'salary-comment',
          'name' => Vacancy::getAllAttributes()->items['salary-comment']['name'],
          'value' => $value
        ];
      }
    }
    if($module==8)
    {
      $object->data->ismed = $rq->getParam('medbook')==1;
      $object->data->isavto = $rq->getParam('car')==1;
      $object->data->smart = $rq->getParam('smartphone')==1;
      $object->data->cardPrommu = $rq->getParam('card_prommu')==1;
      $object->data->card = $rq->getParam('card')==1;
      $object->data->additional = $object->data->ismed
        || $object->data->isavto
        || $object->data->smart
        || $object->data->card
        || $object->data->cardPrommu;
    }
  }
  /**
   * @param $id_vacancy
   * @param $id_user
   * @return bool|int - service_cloud.id
   */
  public static function checkPayment($id_vacancy, $id_user)
  {
    $result = false;
    $city = (new City())->changeVacancyPayCity($id_vacancy, $id_user);
    if($city)
    {
      $result = (new PrommuOrder())->orderInEditVac($id_vacancy, $city);
    }
    return $result;
  }
  /**
   * @param $id - integer
   * @param $id_user - integer
   * @param $module - integer
   * @param $data - object
   * Редактирование вакансии
   */
  public static function setVacancy($id, $id_user, $module, $data=false)
  {
    $arUpdate = [];
    $model = new Vacancy();
    if($module==1) // активация вакансии
    {
      $services = (new ServiceCloud())->getCreateVacancyPaidService($id);
      if(!count($services->items)) // активируем только если оплачено создание вакансии
      {
        $arUpdate = ['status'=>Vacancy::$STATUS_ACTIVE];
      }
    }
    if($module==2)
    {
      $arUpdate = [
        'title' => $data->title,
        'exp' => (integer)$data->exp,
        'agefrom' => (integer)$data->agefrom,
        'ageto' => $data->ageto>0?intval($data->ageto):null,
        'isman' => (integer)$data->isman,
        'iswoman' => (integer)$data->iswoman,
        'istemp' => (integer)$data->istemp
      ];
      $model->saveVacancyPosts($id, $data->post);
    }
    if($module==4)
    {
      $arUpdate = [
        'exp' => (integer)$data->exp,
        'agefrom' => (integer)$data->agefrom,
        'ageto' => $data->ageto>0?intval($data->ageto):null,
        'isman' => (integer)$data->isman,
        'iswoman' => (integer)$data->iswoman,
        'istemp' => (integer)$data->istemp
      ];
      $model->saveVacancyPosts($id, $data->post);
      $arAttr = [];
      foreach ($data->properties as $key => $v)
      {
        if(in_array($key,$model::getAllAttributes()->additional_lists))
        {
          if($key=='manh' || $key=='weig')
          {
            $arAttr[$key]=$v['value'];
          }
          else
          {
            $arAttr[$key]=$v['id'];
          }
        }
      }
      $model->saveVacancyAttributes($id, $arAttr);
    }
    if($module==5)
    {
      $arUpdate = [
        'requirements' => $data->requirements,
        'duties' => $data->duties,
        'conditions' => $data->conditions
      ];
    }
    if($module==6)
    {
      $arUpdate = ['self_employed' => (integer)$data->self_employed];
    }
    if($module==7)
    {
      $arUpdate = [
        'shour' => $data->shour,
        'sweek' => $data->sweek,
        'smonth' => $data->smonth,
        'svisit' => $data->svisit
      ];
      $arAttr = ['salary-comment' => $data->properties['salary-comment']['value']];
      if(isset($data->properties['cpaylims']))
      {
        $arAttr['cpaylims'] = $data->properties['cpaylims']['value'];
      }
      else
      {
        $arAttr['paylims'] = $data->properties['paylims']['id'];
      }
      $model->saveVacancyAttributes($id, $arAttr);
    }
    if($module==8)
    {
      $arUpdate = [
        'ismed' => (integer)$data->ismed,
        'isavto' => (integer)$data->isavto,
        'smart' => (integer)$data->smart,
        'cardPrommu' => (integer)$data->cardPrommu,
        'card' => (integer)$data->card
      ];
    }
    if($module==9)
    {
      $model = new City();
      $model->changeVacancyLocations($id, $id_user);
    }

    if(count($arUpdate))
    {
      $model->updateUserVacancy($id, $id_user, $arUpdate);
    }
  }
}