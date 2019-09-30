<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 13.09.2019
 * Time: 11:30
 */

class ServiceGuestOrder extends CActiveRecord
{
  public $limit;

  function __construct()
  {
    parent::__construct();
    $this->limit = 20;
  }

  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'service_order';
  }

  public function search()
  {
    $params = Yii::app()->getRequest()->getParam('ServiceGuestOrder');

    $criteria = new CDbCriteria;
    $this->id = $params['id'];
    $this->fio = $params['fio'];
    $this->email = $params['email'];
    $this->is_viewed = $params['is_viewed'];
    if(!empty($params['id_se']))
    {
      $this->id_se = $params['id_se'];
      $criteria->condition = "id_se LIKE '" . $params['id_se'] . "'";
    }
    $criteria->compare('id', $this->id, true);
    $criteria->compare('fio', $this->fio, true);
    $criteria->compare('email', $this->email, true);
    $criteria->compare('is_viewed', $this->is_viewed, true);
    return new CActiveDataProvider(
      get_class($this),
      array(
        'criteria' => $criteria,
        'pagination' => ['pageSize' => $this->limit],
        'sort' => ['defaultOrder' => 'is_viewed asc, id desc']
      )
    );
  }
  /**
   * Сделать заказ на услугу
   */
  public function setOrder()
  {
    $rq = Yii::app()->getRequest();
    $code = filter_var($rq->getParam('code'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $fio = filter_var($rq->getParam('fio'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tel = filter_var($rq->getParam('tel'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($rq->getParam('email'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $referer = filter_var($rq->getParam('referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $transition = filter_var($rq->getParam('transition'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $canal = filter_var($rq->getParam('canal'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $campaign = filter_var($rq->getParam('campaign'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_var($rq->getParam('content'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $keywords = filter_var($rq->getParam('keywords'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $point = filter_var($rq->getParam('point'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $last_referer = filter_var($rq->getParam('last_referer'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $roistat = (isset($_COOKIE['roistat_visit'])) ? $_COOKIE['roistat_visit'] : "(none)";

    $res = Yii::app()->db->createCommand()
      ->insert(
        'service_order',
        [
          'id_se' => $code,
          'fio' => $fio,
          'tel' => $tel,
          'email' => $email,
          'crdate' => date("Y-m-d H:i:s"),
          'referer' => $referer,
          'canal' => $canal,
          'campaign' => $campaign,
          'content' => $content,
          'last_referer' => $last_referer,
          'point' => $point,
          'transition' => $transition,
          'keywords' => $keywords,
          'roistat' => $roistat
        ]
      );
    // Письмо админу о заказе услуги
    Mailing::set(19,
      [
        'service_id' => self::getServiceName($code),
        'name_user' => $fio,
        'service_theme' => $tel,
        'service_email' => $email,
        'service_traffic' => $referer,
        'service_transition' => $transition,
        'service_canal' => $canal,
        'service_campaign' => $campaign,
        'service_content' => $content,
        'service_keywords' => $keywords,
        'service_point' => $point,
        'service_referer' => $last_referer,
        'service_roistat' => $roistat
      ]
    );
    return ['res' => $res];
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
    return $arRes;
  }
  /**
   * @param $id
   * @return int
   */
  public function setAdminViewed($id)
  {
    return $this::model()->updateByPk($id,['is_viewed'=>1]);
  }
  /**
   * @return bool|CDbDataReader|mixed|string
   * счетчик просмотров для админа
   */
  public static function getCount()
  {
    return self::model()->countByAttributes(['is_viewed'=>0]);
  }
  /**
   * @param string $code
   * @return array|mixed
   * ассоциативный массив для админки
   */
  public static function getServiceName($code='')
  {
    $arRes = [
      '' => 'Все услуги',
      'creation-vacancy' => 'Создание вакансии',
      'premium-vacancy' => 'Премиум вакансия',
      'email-invitation' => 'Электронная почта',
      'push-notification' => 'PUSH уведомления',
      'sms-informing-staff' => 'SMS информирование',
      'publication-vacancy-social-net' => 'Публикация в соцсетях',
      'geolocation-staff' => 'Геолокация',
      'personal-manager-outsourcing' => 'Личный менеджер и аутсорсинг персонала',
      'outstaffing' => 'Аутстаффинг персонала',
      'prommu_card' => 'Получение корпоративной карты Prommu',
      'medical-record' => 'Получение медицинской книги',
      'api-key-prommu' => 'Получение API ключа'
    ];

    return (empty($code) ? $arRes : $arRes[$code]);
  }
}