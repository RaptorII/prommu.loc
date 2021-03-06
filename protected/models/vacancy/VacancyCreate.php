<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 21.02.2020
 * Time: 11:40
 */

class VacancyCreate
{
  public $id_user;
  public $vacancy;
  public $step;
  public $is_new;
  public $data;
  public $dataOther;
  public $errors;
  public $id_vacancy;
  const VIEW_TEMPLATE = '/user/vacancy/create/step_';

  function __construct($vacancy=false)
  {
    $this->id_user = Share::$UserProfile->id;
    $this->errors = [];
    $this->dataOther = (object)[];

    $duplicate = Yii::app()->getRequest()->getParam('duplicate');

    if(!Share::$UserProfile->isModer()) // Непромодерированный не может добавлять вакансии
    {
      $this->errors['access'] = 'Вы сможете добавить вакансию, после прохождения модерации, спасибо за понимание';
    }
    elseif($duplicate=='Y') // дублирование вакансии
    {
      $id_vacancy = intval(Yii::app()->getRequest()->getParam('id'));

      $model = new Vacancy();
      $query = $model->getVacanciesById([$id_vacancy]);
      if(count($query))
      {
        $query = reset($query);
        $this->data = (object)[
          'title' => $query['title'],
          'istemp' => $query['istemp'],
          'exp' => $query['exp'],
          'self_employed' => $query['self_employed'],
          'age_from' => $query['agefrom'],
          'age_to' => $query['ageto'],
          'gender' => [],
          'post' => [],
          'city' => [],
          'salary_time' => '',
          'salary_time_custom' => '',
          'salary_comment' => '',
          'salary_type' => 0,
          'salary' => 0,
          'requirements' => $query['requirements'],
          'duties' => $query['duties'],
          'conditions' => $query['conditions'],
          'medbook' => $query['ismed'],
          'car' => $query['isavto'],
          'smartphone' => $query['smart'],
          'card_prommu' => $query['cardPrommu'],
          'card' => $query['card'],
          'repost' => []
        ];
        $query['isman'] && $this->data->gender[] = 'man';
        $query['iswoman'] && $this->data->gender[] = 'woman';
        if($query['shour']>0)
        {
          $this->data->salary_type = 0; // руб / час
          $this->data->salary = $query['shour'];
        }
        if($query['sweek']>0)
        {
          $this->data->salary_type = 1; // руб / неделя
          $this->data->salary = $query['sweek'];
        }
        if($query['smonth']>0)
        {
          $this->data->salary_type = 2; // руб / месяц
          $this->data->salary = $query['smonth'];
        }
        if($query['svisit']>0)
        {
          $this->data->salary_type = 3; // руб / посещение
          $this->data->salary = $query['svisit'];
        }
        substr($query['repost'], 0,1)=='1' && $this->data->repost[] = 'vk';
        substr($query['repost'], 1,1)=='1' && $this->data->repost[] = 'facebook';
        substr($query['repost'], 2,1)=='1' && $this->data->repost[] = 'telegram';

        $query = Yii::app()->db->createCommand()
          ->from('empl_attribs')
          ->where('id_vac=:id',[':id'=>$id_vacancy])
          ->queryAll();
        $arAttr = $model::getAllAttributes();

        foreach ($query as $v)
        {
          $arProp = $arAttr->items[$v['key']];
          if($arProp['id_par']==Vacancy::ID_POSTS_ATTRIBUTE) // должность
          {
            $this->data->post[] = $v['key'];
          }
          else // прочие атрибуты
          {
            if($v['key']=='paylims')
            {
              $this->data->salary_time = $v['id_attr'];
            }
            if($v['key']=='cpaylims')
            {
              $this->data->salary_time_custom = $v['val'];
            }
            if($v['key']=='salary-comment')
            {
              $this->data->salary_comment = $v['val'];
            }
          }
        }

        $this->data->city = Yii::app()->db->createCommand()
          ->select('id_city')
          ->from('empl_city')
          ->where('id_vac=:id',[':id'=>$id_vacancy])
          ->queryColumn();

        $this->vacancy = md5(time() . $this->id_user);
        $this->vacancy = substr($this->vacancy,0,7);
        $this->step = 'duplicate';
        $this->is_new = true;
      }
      else
      {
        $this->errors['access'] = 'Вакансии не существует, либо у вас нет доступа';
      }
    }
    elseif($vacancy) // открытие существующей вакансии
    {
      $this->vacancy = filter_var($vacancy, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $this->is_new = false;
      $object = $this->getData();
      if(!$object)
      {
        $this->errors['access'] = 'Вакансии не существует, либо у вас нет доступа';
      }
      $this->data = $object->data;
      $changeStep = Yii::app()->getRequest()->getParam('change_step');
      if(in_array($changeStep,[1,2,3,4,5]))
      {
        $this->step = $changeStep; // Делаем возврат на предыдущую ступень
        $this->setData();
      }
      else
      {
        $this->step = $object->step; // Переходим на следующую
      }
    }
    else // создание новой вакансии
    {
      $this->vacancy = md5(time() . $this->id_user);
      $this->vacancy = substr($this->vacancy,0,7);
      $this->step = 1;
      $this->is_new = true;
      $this->data = (object)[];
    }

    $this->id_vacancy = false;
  }
  /**
   * Получение данных по вакансии
   * @return CDbDataReader|mixed
   */
  private function getData()
  {
    $query = Yii::app()->db->createCommand()
      ->from('vacancy_create')
      ->where(
        'vacancy=:vacancy and id_user=:id_user',
        [':vacancy'=>$this->vacancy, ':id_user'=>$this->id_user]
        )
      ->limit(1)
      ->queryRow();

    if(!$query)
    {
      return false;
    }
    $query['data'] = json_decode($query['data']);
    return (object)$query;
  }
  /**
   * Запись данных по вакансии
   * @return mixed
   */
  private function setData()
  {
    $arData = [
      'id_user' => $this->id_user,
      'vacancy' => $this->vacancy,
      'step' => ($this->step=='duplicate' ? 6 : $this->step),
      'data' => json_encode($this->data),
      'mdate' => time()
    ];
    if($this->is_new)
    {
      $arData['cdate'] = time();
      $result = Yii::app()->db->createCommand()
        ->insert('vacancy_create',$arData);
    }
    else
    {
      $result = Yii::app()->db->createCommand()
        ->update(
          'vacancy_create',
          $arData,
          'vacancy=:vacancy and id_user=:id_user',
          [':vacancy'=>$this->vacancy, ':id_user'=>$this->id_user]
        );
    }
    return $result;
  }
  /**
   * Удаление данных по вакансии
   * @return CDbDataReader|mixed
   */
  public function deleteData()
  {
    return Yii::app()->db->createCommand()
      ->delete(
        'vacancy_create',
        "vacancy=:vacancy",
        [':vacancy'=>$this->vacancy]
      );
  }
  /**
   * Проверка полей
   */
  public function setDataByStep()
  {
    $rq = Yii::app()->getRequest();
    $step = $rq->getParam('step');

    if($step==1)
    {
      // Заголовок
      $value = VacancyCheckFields::checkTextField($rq->getParam('title'));
      $value ? $this->data->title=$value : $this->errors['title']=true;
      // Должность
      $value = VacancyCheckFields::checkPost($rq->getParam('post'));
      $value ? $this->data->post=$value : $this->errors['post']=true;
      // Город
      $v = $rq->getParam('city');
      if(!(is_array($v) && count($v)))
      {
        $this->errors['city'] = true;
      }
      else
      {
        $this->data->city = $v;
      }
      $this->checkPeriod();
    }
    elseif($step==2)
    {
      // Тип работы
      $value = VacancyCheckFields::checkList($rq->getParam('istemp'),'work_type');
      $value===false ? $this->errors['istemp']=true : $this->data->istemp=$value;
      // Опыт работы
      $value = VacancyCheckFields::checkList($rq->getParam('exp'),'experience');
      $value ? $this->data->exp=$value : $this->errors['exp']=true;
      // Налоговый статус
      $value = VacancyCheckFields::checkList($rq->getParam('self_employed'),'self_employed');
      $value===false ? $this->errors['self_employed']=true : $this->data->self_employed=$value;
      // Возраст
      $value = VacancyCheckFields::checkAge($rq->getParam('age_from'), $rq->getParam('age_to'));
      if($value)
      {
        $this->data->age_from = $value[0];
        $this->data->age_to = $value[1];
      }
      else
      {
        $this->errors['age'] = true;
      }
      // Пол
      $value = VacancyCheckFields::checkGender($rq->getParam('gender'));
      $value ? $this->data->gender=$value : $this->errors['gender']=true;
    }
    elseif($step==3)
    {
      // Заработная плата
      $value = VacancyCheckFields::checkSalary($rq->getParam('salary'));
      $value ? $this->data->salary=$value : $this->errors['salary']=true;
      // Тип оплаты
      $value = VacancyCheckFields::checkList($rq->getParam('salary_type'), 'salary_type');
      $value===false ? $this->errors['salary_type']=true : $this->data->salary_type=$value;
      // Сроки оплаты
      if(!empty($rq->getParam('salary_time_custom')))
      {
        $value = VacancyCheckFields::checkTextField($rq->getParam('salary_time_custom'));
        $value===false ? $this->errors['salary_time_custom']=true : $this->data->salary_time_custom=$value;
      }
      else
      {
        $value = VacancyCheckFields::checkList($rq->getParam('salary_time'),'salary_time');
        $value===false ? $this->errors['salary_time']=true : $this->data->salary_time=$value;
      }
      // Комментарии по оплате
      $this->data->salary_comment = VacancyCheckFields::checkTextarea($rq->getParam('salary_comment'));
    }
    elseif($step==4)
    {
      // Описание
      $value = VacancyCheckFields::checkTextarea($rq->getParam('requirements'), true);
      $value ? $this->data->requirements=$value : $this->errors['requirements']=true;
      // Обязанности
      $this->data->duties = VacancyCheckFields::checkTextarea($rq->getParam('duties'));
      // Условия
      $this->data->conditions = VacancyCheckFields::checkTextarea($rq->getParam('conditions'));
    }
    elseif ($step==5)
    {
      // медкнига
      if($rq->getParam('medbook')==1)
      {
        $this->data->medbook = true;
      }
      // автомобиль
      if($rq->getParam('car')==1)
      {
        $this->data->car = true;
      }
      // смартфон
      if($rq->getParam('smartphone')==1)
      {
        $this->data->smartphone = true;
      }
      // карта прому
      if($rq->getParam('card_prommu')==1)
      {
        $this->data->card_prommu = true;
      }
      // карта
      if($rq->getParam('card')==1)
      {
        $this->data->card = true;
      }
      // соцсети
      $v = $rq->getParam('repost');
      if(in_array('vk',$v) || in_array('facebook',$v) || in_array('telegram',$v))
      {
        $this->data->repost = $v;
      }
    }
    elseif ($step==6)
    {

    }
    elseif ($step=='duplicate')
    {
      $this->checkPeriod();
    }

    if(!count($this->errors)) // ошибок нет
    {
      if($this->step!='duplicate' && $this->step<6)
      {
        $this->step=$step+1;
      }

      $this->setData();

      if($step==6 || $step=='duplicate')
      {
        $this->createVacancy();
      }
    }
  }
  /*
   *
   */
  public function getDataByStep()
  {
    if($this->step==1)
    {
      $this->dataOther->posts = Vacancy::getPostsSortList();
      $this->dataOther->arSelectPost = [];
      foreach ($this->dataOther->posts as $v)
      {
        if(in_array($v['id'],$this->data->post))
        {
          $this->dataOther->arSelectPost[$v['id']] = $v['name'];
        }
      }
    }
    if(in_array($this->step,[1,6,'duplicate']))
    {
      $this->dataOther->arSelectCity = [];
      if(count($this->data->city))
      {
        $model = new City();
        $this->dataOther->cities = $model->getCities($this->data->city);
        foreach ($this->dataOther->cities as $v)
        {
          $this->dataOther->arSelectCity[$v['id_city']] = $v['name'];
        }
      }
      if(!count($this->dataOther->arSelectCity))
      {
        $this->dataOther->arSelectCity[Share::$UserProfile->cache->city->id_city] = Share::$UserProfile->cache->city->name;
      }
    }
    if(in_array($this->step,[6,'duplicate']))
    {
      $period = strtotime($this->data->edate) - strtotime($this->data->bdate);
      $this->dataOther->period = $period/86400;
      $model = new PrommuOrder();
      $this->dataOther->prices = $model->getPriceByCity(
        $this->dataOther->cities,
        'premium-vacancy'
      );
    }
  }
  /**
   * @param $step = integer(1,2,3,4,5)
   * @return = string
   */
  public function getView($step = false)
  {
    return self::VIEW_TEMPLATE . ($step ?: $this->step);
  }
  /**
   * @return array
   * Метод проверяет необходимость оплаты и устанавливает необходимые значения в объекте. И возвращает массив после PrommuOrder
   */
  public function checkPayment()
  {
    $arRes = [];
    if(!$this->id_vacancy)
    {
      return $arRes;
    }

    $rq = Yii::app()->getRequest();
    if(
      $rq->getParam('premium')==1
      ||
      (!Share::$UserProfile->accessToFreeVacancy && $this->step!='duplicate')
    )
    {
      $arCity = [];
      $arPrice = [];
      if($rq->getParam('premium')==1)
      {
        foreach ($this->dataOther->prices as $v)
        {
          if(in_array($v['id_city'],$rq->getParam('premium_region')))
          {
            $arCity[] = $v['id_city'];
            $arPrice[] = $v['price'];
          }
        }
      }
      $model = new PrommuOrder();
      $arRes = $model->orderInCreationVac(
        $this->id_vacancy,
        $arCity,
        $arPrice,
        $rq->getParam('premium_period'),
        $this->data->city
      );
    }
    return $arRes;
  }
  /**
   * Проверка даты начала и даты завершения
   */
  private function checkPeriod()
  {
    $v1 = Yii::app()->getRequest()->getParam('bdate'); // Дата начала
    $v2 = Yii::app()->getRequest()->getParam('edate'); // Дата завершения
    if(!Share::checkFormatDate($v1))
    {
      $this->errors['bdate'] = true;
    }
    elseif(!Share::checkFormatDate($v2))
    {
      $this->errors['edate'] = true;
    }
    elseif(
      (strtotime($v1)>strtotime($v2)) // если даты отличаются
      ||
      ((strtotime($v2)-strtotime($v1)) > (30*86400)) // период сильно большой(больше 30 дней)
    )
    {
      $this->errors['bdate'] = true;
      $this->errors['edate'] = true;
    }
    else
    {
      $this->data->bdate = $v1;
      $this->data->edate = $v2;
    }
  }
  /**
   * Создание вакансии
   */
  private function createVacancy()
  {
    $repost = 0;
    in_array('vk',$this->data->repost) && $repost|=1;
    in_array('facebook',$this->data->repost) && $repost|=2;
    in_array('telegram',$this->data->repost) && $repost|=4;
    $repost = decbin($repost);
    $repost = str_pad($repost, 3, "0", STR_PAD_LEFT);
    // создание вакансии
    $arVacancy = [
      'id_user' => Share::$UserProfile->id,
      'id_empl' => Share::$UserProfile->exInfo->eid,
      'title' => $this->data->title,
      'requirements' => $this->data->requirements,
      'duties' => $this->data->duties,
      'conditions' => $this->data->conditions,
      'remdate' => Share::dateFormatToMySql($this->data->edate),
      'istemp' => $this->data->istemp,
      'shour' => ($this->data->salary_type==0 ? $this->data->salary : 0),
      'sweek' => ($this->data->salary_type==1 ? $this->data->salary : 0),
      'smonth' => ($this->data->salary_type==2 ? $this->data->salary : 0),
      'svisit' => ($this->data->salary_type==3 ? $this->data->salary : 0),
      'exp' => $this->data->exp,
      'isman' => (integer)in_array('man',$this->data->gender),
      'iswoman' => (integer)in_array('woman',$this->data->gender),
      'ismed' => (integer)isset($this->data->medbook),
      'isavto' => (integer)isset($this->data->car),
      'agefrom' => (integer)$this->data->age_from,
      'ageto' => (integer)$this->data->age_to,
      'status' => Vacancy::$STATUS_NO_ACTIVE,
      'ismoder' => Vacancy::$ISMODER_NEW,
      'crdate' => date('Y-m-d H:i:s'),
      'mdate' => date('Y-m-d H:i:s'),
      'smart' => (integer)isset($this->data->smartphone),
      'repost' => $repost,
      'card' => (integer)isset($this->data->card),
      'cardPrommu' => (integer)isset($this->data->card_prommu),
      'self_employed' => $this->data->self_employed
    ];

    if(!empty($this->data->salary_time_custom)) // Сроки оплаты
    {
      $arAttributes = ['cpaylims' => $this->data->salary_time_custom];
    }
    else
    {
      $arAttributes = ['paylims' => $this->data->salary_time];
    }
    if(!empty($this->data->salary_comment)) // Комментарии по оплате
    {
      $arAttributes['salary-comment'] = $this->data->salary_comment;
    }

    $model = new Vacancy();
    $this->id_vacancy = $model->createVacancy($arVacancy, $this->data->post, $arAttributes);
    // добавление городов вакансии
    $arInsert = [];
    foreach ($this->data->city as $v)
    {
      $arInsert[] = [
        'id_vac' => $this->id_vacancy,
        'id_city' => $v,
        'bdate' => Share::dateFormatToMySql($this->data->bdate),
        'edate' => Share::dateFormatToMySql($this->data->edate)
      ];
    }
    Share::multipleInsert(['empl_city'=>$arInsert]);
        // email ведомление юзеру
    Mailing::set(3,
      [
        'email_user' => Share::$UserProfile->exInfo->email,
        'company_user' => empty(Share::$UserProfile->exInfo->name) ? 'пользователь' : Share::$UserProfile->exInfo->name,
        'id_vacancy' => $this->id_vacancy
      ]
    );
    // Изменяем в базе дату добавления платной вакансии
    Yii::app()->db->createCommand()
      ->update(
        'employer',
        ['vacancy_payment'=>strtotime('today')],
        'id_user=:id_user',
        [':id_user'=>Share::$UserProfile->id]
      );
  }
}