<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 08.07.2019
 * Time: 13:07
 */

class AdminMessage extends CActiveRecord
{
  public $limit;
  public $view;
  public $pageTitle;
  public $cacheID; // уникальный ID кеша
  public $cacheTime; // уникальный ID кеша

  function __construct()
  {
    $this->limit = 20;
    $this->view = 'notifications/message';
    $this->pageTitle = 'сообщения';
    $this->cacheID = 'Admin_messages_data';
    $this->cacheTime = 31536000; // хранение кеша 1 год, или пока не появятся новвые месседжи или прочитанности
  }

  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'admin_message';
  }

  public function search()
  {
    $criteria = new CDbCriteria;
    return new CActiveDataProvider(
      get_class($this),
      array(
        'criteria' => $criteria,
        'pagination' => ['pageSize' => $this->limit],
        'sort' => ['defaultOrder' => 'date desc']
      )
    );
  }
  /**
   *		Чтение данных
   */
  public function getData($id)
  {
    $arRes['item'] = $this::model()->findByPk($id);
    $model = new AdminMessageReceiver();
    $arRes['receivers'] = $model->getDataForMessage($id);
    $id_user = Yii::app()->getRequest()->getParam('id_user');
    if(!empty($id_user))
    {
      $arRes['receiver_new'] = $id_user;
      $arRes['users'] = Share::getUsers([$id_user]);
    }
    elseif(count($arRes['receivers']))
    {
      $arIdUsers = [];
      foreach ($arRes['receivers'] as $v)
      {
        $arIdUsers[] = $v['id_user'];
      }
      $arRes['users'] = Share::getUsers($arIdUsers);
    }
    return $arRes;
  }
  /**
   *		Запись данных
   */
  public function setData($obj)
  {
    $arParams = array();
    $arRes = array('error'=>false);
    // title
    $this->title = filter_var(
      trim($obj->getParam('title')),
      FILTER_SANITIZE_FULL_SPECIAL_CHARS
    );
    // text
    $this->text = $obj->getParam('text');

    if(empty($this->title) || empty($this->text))
      $arRes['messages'][] = 'поля "Заголовок" и "Текст письма" должны быть заполнены';

    if(count($arRes['messages'])) // error
    {
      $arRes['error'] = true;
      $arRes['item'] = $this;
      return $arRes;
    }
    // Сохраняем в любом случае
    $time = time();
    $this->date = $time;
    $id = $obj->getParam('id');

    if(!intval($id)) // insert
    {
      $this->date = $time;
      $this->setIsNewRecord(true);
    }
    else // update
    {
      $this->id = $id;
    }

    if($this->save())
    {
      $arRes['redirect'] = true;
      Yii::app()->user->setFlash('success', 'Данные успешно сохранены');
    }
    else
    {
      $arRes['redirect'] = true;
      Yii::app()->user->setFlash('danger', 'Ошибка сохранения');
    }

    $arReceivers = $obj->getParam('receivers');
    $arReceiversOld = $obj->getParam('receivers_old');
    if(count($arReceivers))
    {
      $arInsert = [];
      !intval($id) && $id=$this->id;

      foreach ($arReceivers as $id_user)
      {
        if(count($arReceiversOld))
        {
          if(!in_array($id_user,$arReceiversOld))
          {
            $arInsert[] = ['id_user'=>$id_user,'id_message'=>$id];
          }
        }
        else
        {
          $arInsert[] = ['id_user'=>$id_user,'id_message'=>$id];
        }
      }

      Share::multipleInsert(['admin_message_receiver'=>$arInsert]);
    }

    return $arRes;
  }
  /*
   * @param $id_user - integer
   * Кешируемый запрос данных
   */
  public function getNewMessages($id_user)
  {
    if(empty($id_user))
      return false;

    return Yii::app()->db->createCommand()
      ->select('amr.id, amr.id_message, am.title, am.text')
      ->from('admin_message_receiver amr')
      ->leftjoin('admin_message am','am.id=amr.id_message')
      ->where('amr.readed is null and amr.id_user=:id',[':id'=>$id_user])
      ->limit($this->limit)
      ->queryAll();
  }
  /**
   *  @param $cacheId - string
   *  Делаем выборку из кеша
   */
  /*public function getCacheData($cacheId)
  {
    $arRes = Cache::getData($cacheId);
    if($arRes['data']===false)
      $arRes = $this->setCacheData($cacheId);

    return $arRes['data'];
  }*/
  /**
   *  @param $cacheId - string
   * 	Делаем запись в кеш
   */
  /*public function setCacheData($cacheId)
  {
    $arRes = Cache::getData($cacheId);
    $arRes['data'] = array();

    $event = new MailingEvent;
    $arEvents = $event::model()->findAll(); // Нужны все события
    foreach ($arEvents as $v)
      $arRes['data']['events'][$v->id] = $v;
    $template = new MailingTemplate;
    $arRes['data']['template'] = $template->getActiveTemplate(); // и активный шаблон
    Cache::setData($arRes, $this->cacheTime);
    return $arRes;
  }*/

    /**
     *	Send data by Cron to Users
     */
    public function sendDataByCron($obj) {

        //$arParams = []; //not need
        $arRes = array('error'=>false);
        $this->title = $obj['title'];
        $this->text = $obj['text'];

        // Save
        $time = time();
        $this->date = $time;
        $id = $obj['id'];

        if(!intval($id)) { // insert
            $this->date = $time;
            $this->setIsNewRecord(true);
        } else {
            $this->id = $id; // update
        }

        if($this->save()) {
            $arRes['redirect'] = true;
            Yii::app()->user->setFlash('success', 'Данные успешно сохранены');
        } else {
            $arRes['redirect'] = true;
            Yii::app()->user->setFlash('danger', 'Ошибка сохранения');
        }

        $arReceivers = $obj['users'];

        if(count($arReceivers)) {
            $arInsert = [];
            !intval($id) && $id=$this->id;

            foreach ($arReceivers as $id_user) {
                    $arInsert[] = ['id_user'=>$id_user,'id_message'=>$id];
            }

            Share::multipleInsert(['admin_message_receiver'=>$arInsert]);
        }

        return $arRes;
    }

}