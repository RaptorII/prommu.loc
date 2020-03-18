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
  public $finish_link;
  const VIEW_TEMPLATE = '/user/vacancy/create/step_';

  function __construct($vacancy=false)
  {
    $this->id_user = Share::$UserProfile->id;
    $this->errors = [];
    $this->dataOther = (object)[];

    if($vacancy)
    {
      $this->vacancy = filter_var($vacancy, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $this->is_new = false;
      $object = $this->getData();
      if(!$object)
      {
        $this->errors['access'] = 'Вакансии не существует, либо у вас нет доступа';
      }
      $this->data = $object->data;
      $this->step = $object->step; // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    }
    else
    {
      $this->vacancy = md5(time() . $this->id_user);
      $this->vacancy = substr($this->vacancy,0,7);
      $this->step = 1;
      $this->is_new = true;
      $this->data = (object)[];
    }

    $this->id_vacancy = false;
    $this->finish_link = false;
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
      'step' => $this->step,
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
   * Проверка полей
   */
  public function setDataByStep()
  {
    $rq = Yii::app()->getRequest();
    $step = $rq->getParam('step');

    if($step==1)
    {
      // Заголовок
      $value = VacancyCheckFields::checkTitle($rq->getParam('title'));
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
      // Дата начала
      $v = $rq->getParam('bdate');
      if(!Share::checkFormatDate($v))
      {
        $this->errors['bdate'] = true;
      }
      else
      {
        $this->data->bdate = $v;
      }
      // Дата завершения
      $v = $rq->getParam('edate');
      if(!Share::checkFormatDate($v))
      {
        $this->errors['edate'] = true;
      }
      else
      {
        $this->data->edate = $v;
      }
    }
    elseif($step==2)
    {
      // Тип работы
      $value = VacancyCheckFields::checkWorkType($rq->getParam('istemp'));
      $value===false ? $this->errors['istemp']=true : $this->data->istemp=$value;
      // Опыт работы
      $value = VacancyCheckFields::checkExperience($rq->getParam('exp'));
      $value ? $this->data->exp=$value : $this->errors['exp']=true;
      // Налоговый статус
      $v = $rq->getParam('self_employed');
      if(!in_array($v,array_keys(Vacancy::SELF_EMPLOYED)))
      {
        $this->errors['self_employed'] = true;
      }
      else
      {
        $this->data->self_employed = $v;
      }
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
      $v = intval($rq->getParam('salary'));
      if(!$v || $v>1000000)
      {
        $this->errors['salary'] = true;
      }
      else
      {
        $this->data->salary = $v;
      }
      $v = intval($rq->getParam('salary_type'));
      if(!array_key_exists($v, Vacancy::SALARY_TYPE))
      {
        $this->errors['salary_type'] = true;
      }
      else
      {
        $this->data->salary_type = $v;
      }
      // Сроки оплаты
      $v = intval($rq->getParam('salary_time'));
      if(!array_key_exists($v, Vacancy::SALARY_TIME))
      {
        $this->errors['salary_time'] = true;
      }
      else
      {
        $this->data->salary_time = $v;
      }
      // Комментарии по оплате
      $v = trim($rq->getParam('salary_comment'));
      $v = strip_tags($v);
      $v = htmlspecialchars($v,ENT_QUOTES);
      $this->data->salary_comment = stripslashes($v);
    }
    elseif($step==4)
    {
      // Описание
      $v = trim($rq->getParam('requirements'));
      $v=="<br>" && $v="";

      if(!strlen($v))
      {
        $this->errors['requirements'] = true;
      }
      else
      {
        $v = htmlspecialchars($v,ENT_QUOTES);
        $this->data->requirements = $v;
      }
      $this->data->requirements = stripslashes($v);
      // Обязанности
      $v = trim($rq->getParam('duties'));
      $v=="<br>" && $v="";
      $v = htmlspecialchars($v,ENT_QUOTES);
      $this->data->duties = stripslashes($v);
      // Условия
      $v = trim($rq->getParam('conditions'));
      $v=="<br>" && $v="";
      $v = htmlspecialchars($v,ENT_QUOTES);
      $this->data->conditions = stripslashes($v);
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

    if(!count($this->errors)) // ошибок нет
    {
      $this->step<5 && $this->step++;
      $this->setData();
      if($step==5)
      {
        $model = new Vacancy();
        $this->id_vacancy = $model->createVacancy(
          $this->data,
          (object)[
            'id_user' => Share::$UserProfile->id,
            'id' => Share::$UserProfile->exInfo->eid,
            'email' => Share::$UserProfile->exInfo->email,
            'name' => empty(Share::$UserProfile->exInfo->name) ? 'пользователь' : Share::$UserProfile->exInfo->name
          ]
        );
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
    if(in_array($this->step,[1,5]))
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
    }
    if($this->step==5)
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
    if($rq->getParam('premium')==1)
    {
      $arCity = [];
      $arPrice = [];
      foreach ($this->dataOther->prices as $v)
      {
        if(in_array($v['id_city'],$rq->getParam('premium_region')))
        {
          $arCity[] = $v['id_city'];
          $arPrice[] = $v['price'];
        }
      }
      $model = new PrommuOrder();
      $arRes = $model->orderPremiumInCreationVac(
        $this->id_vacancy,
        $arCity,
        $arPrice,
        $rq->getParam('premium_period')
      );
    }
    return $arRes;
  }
}