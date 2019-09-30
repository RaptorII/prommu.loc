<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 20.09.2019
 * Time: 16:31
 */

class ServiceController extends Controller
{
  public $id;

  public function init()
  {
    $id = Yii::app()->user->getId();
    $m = new UserAdm();
    $access = $m->getAccess($id);

    if(Yii::app()->user->isGuest || strpos($access, 'Услуги')===false)
    {
      $this->redirect('login');
      Yii::app()->end();
    }

    $bUrl = Yii::app()->request->baseUrl;
    $gcs = Yii::app()->getClientScript();
    $gcs->registerCssFile($bUrl . '/css/template.css');
    $gcs->registerScriptFile($bUrl . '/js/service/list.js', CClientScript::POS_END);

    $this->id = Yii::app()->getRequest()->getParam('id');
    $this->id = intval($this->id);
  }
  /**
   *  список услуг
   */
  public function actionIndex()
  {
    $this->render('index');
  }
  /**
   *  Заказ услуг гостями
   */
  public function actionService_order()
  {
    $this->render('service_order-' . ($this->id ? 'item' : 'list'));
  }
  /**
   *  premium, email, push, sms, repost, api
   */
  public function actionService_cloud()
  {
    $service = Yii::app()->getRequest()->getParam('service');
    if(!in_array($service,['vacancy','email','push','sms','repost','api']))
    {
      $this->redirect('/admin/service');
    }
    if(Yii::app()->getRequest()->isPostRequest)
    {
      $model = new Service();
      $model->setData();
      $this->redirect('/admin/service/service_cloud/' . $service);
    }

    $this->render('service_cloud-' . ($this->id ? 'item' : 'list'));
  }
  /**
   *  outstaffing, outsourcing
   */
  public function actionOutstaffing()
  {
    $service = Yii::app()->getRequest()->getParam('service');
    if(!in_array($service,['outstaffing','outsourcing']))
    {
      $this->redirect('/admin/service');
    }

    $this->render('outstaffing-' . ($this->id ? 'item' : 'list'));
  }
  /**
   *  Заказ Карты прому
   */
  public function actionCard_request()
  {
    $this->render('card_request-' . ($this->id ? 'item' : 'list'));
  }
  /**
   *  Заказ Медкниги
   */
  public function actionMed_request()
  {
    $this->render('med_request-' . ($this->id ? 'item' : 'list'));
  }
  /**
   *  ajax
   */
  public function actionUpdate()
  {
    $arRes = ['message' => 'Ошибка изменения данных'];
    $data = Yii::app()->getRequest()->getParam('data');
    $data = json_decode($data, true, 5, JSON_BIGINT_AS_STRING);
    if($data['table']=='card_request')
    {
      $model = new UserCard();
      $arRes['message'] = $model->updateData($data);
    }
    echo CJSON::encode($arRes);
  }
}