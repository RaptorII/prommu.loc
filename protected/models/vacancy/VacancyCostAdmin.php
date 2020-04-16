<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 15.04.2020
 * Time: 13:33
 */

class VacancyCostAdmin extends ARModel
{
  public $company_search;
  public $cost;

  public function tableName()
  {
    return 'empl_vacations';
  }

  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function search()
  {
    $condition = [];
    $params = Yii::app()->getRequest()->getParam('VacancyCostAdmin');

    $criteria = new CDbCriteria;
    $criteria->select = 't.*, e.name as company_search, sc.status as cost';
    $criteria->join = "LEFT JOIN employer e ON e.id_user=t.id_user"
      . " LEFT JOIN service_cloud sc ON sc.name=t.id";
    $criteria->group = 't.id';

    if(intval($params['id']))
    {
      $this->id = $params['id'];
      $condition[] = 't.id='.$params['id'];
    }
    if(!empty($params['title']))
    {
      $this->title = $params['title'];
      $condition[] = "t.title LIKE '%".$params['title'] . "%'";
    }
    if(strlen($params['status']))
    {
      $this->status = $params['status'];
      $condition[] = 't.status='.$params['status'];
    }
    if(strlen($params['ismoder']))
    {
      $this->ismoder = $params['ismoder'];
      $condition[] = 't.ismoder='.$params['ismoder'];
    }
    if(!empty($params['company_search']))
    {
      $this->company_search = $params['company_search'];
      $condition[] = "e.name LIKE '%".$params['company_search'] . "%'";
    }
    if(intval($params['id_user']))
    {
      $this->id_user = $params['id_user'];
      $condition[] = 't.id_user='.$params['id_user'];
    }
    if(strlen($params['cost']))
    {
      if($params['cost']=='0') // бесплатные
      {
        $this->cost = $params['cost'];
        $condition[] = 'sc.status IS NULL';
      }
      if($params['cost']=='1') // Ожидают оплаты
      {
        $this->cost = $params['cost'];
        $condition[] = 'sc.status=0';
      }
      if($params['cost']=='2') // Оплачены
      {
        $this->cost = $params['cost'];
        $condition[] = 'sc.status=1';
      }
    }

    if(count($condition))
    {
      $criteria->condition = implode(' and ', $condition);
    }

    return new CActiveDataProvider(__CLASS__, [
      'criteria'=>$criteria,
      'pagination' => ['pageSize' => 20],
      'sort' => [
        'defaultOrder'=>'t.mdate desc',
        'attributes'=>[
          'company_search'=>['asc'=>'e.name', 'desc'=>'e.name DESC'],
          '*',
        ]
      ]
    ]);
  }
}