<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 26.09.2019
 * Time: 13:25
 */

class Outstaffing extends CActiveRecord
{
  public $company_search;
  public $subservice_search;
  public $limit;

  function __construct()
  {
    parent::__construct();
    $this->limit = 20;
  }

  public function tableName()
  {
    return 'outstaffing';
  }

  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function search()
  {
    $get = Yii::app()->getRequest()->getParam('Outstaffing');
    $criteria = new CDbCriteria;

    $criteria->select = 't.*, e.name as company_search';
    $criteria->join = "LEFT JOIN employer e ON e.id_user=t.id";
    $arCondition = [];
    $this->id_key = $get['id_key'];
    if(!empty($get['company_search']))
    {
      $arCondition[] = "e.name LIKE '%" . $get['company_search'] . "%'";
      $this->company_search = $get['company_search'];
    }
    if(isset($get['subservice_search']) && $get['subservice_search']!=='all')
    {
      $arCondition[] = "t." . $get['subservice_search'] . "<>''";
      $this->subservice_search = $get['subservice_search'];
    }

    if(count($arCondition))
    {
      $criteria->condition = implode(' and ', $arCondition);
    }

    $criteria->compare('t.id_key', $this->id_key, true);
    $criteria->compare('t.type', $this->type, true);
    $criteria->compare('e.name', $this->company_search, true);

    return new CActiveDataProvider($this, array(
      'criteria' => $criteria,
      'pagination' => ['pageSize' => $this->limit],
      'sort' => ['defaultOrder' => 't.is_new desc, t.id_key desc'],
    ));
  }

  public static function getSubService($service, $key=false)
  {
    $arRes = [
      'all' => 'Все',
      'consult' => 'Консультация менеджера по услуге',
    ];

    if($service=='outstaffing')
    {
      $arRes['rezident'] = 'Оформление сотрудников-резидентов по всей РФ';
      $arRes['nrezident'] = 'Оформление сотрудников-нерезидентов в Москве и МО';
    }
    if($service=='outsourcing')
    {
      $arRes['advertising'] = 'Организация проведения различных видов рекламы';
      $arRes['control'] = 'Организация контроля проведения проекта';
    }

    return ($key ? $arRes[$key] : $arRes);
  }
  /**
   * @param $id
   * @return array
   * Данные о отдельном заказе
   */
  public function getOrder($id)
  {
    $arRes = [];
    $arRes['item'] = $this::model()->findByPk($id);
    if($arRes['item'])
    {
      $id_user = $arRes['item']['id'];
      $arRes['employer'] = Share::getUsers([$id_user])[$id_user];
      $arVacId = Share::explode($arRes['item']['vacancy']);
      $model = new Vacancy();
      $arRes['vacancies'] = $model->getVacanciesById($arVacId);
    }

    return $arRes;
  }
  /**
   * @param $id
   * @return int
   */
  public function setAdminViewed($id)
  {
    return $this::model()->updateByPk($id,['is_new'=>0]);
  }
  /**
   * @param $obj - object(zii.widgets.grid.CGridView)
   * @return string
   */
  public static function getService($obj)
  {
    $s = Yii::app()->getRequest()->getParam('service');
    $arRes = [];
    !empty($obj->consult) && $arRes[] = $obj->consult;
    if($s=='outstaffing')
    {
      !empty($obj->rezident) && $arRes[] = $obj->rezident;
      !empty($obj->nrezident) && $arRes[] = $obj->nrezident;
    }
    if($s=='outsourcing')
    {
      !empty($obj->advertising) && $arRes[] = $obj->advertising;
      !empty($obj->control) && $arRes[] = $obj->control;
    }

    return implode(',<br>',$arRes);
  }
}