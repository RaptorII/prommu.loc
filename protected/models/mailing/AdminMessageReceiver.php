<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 10.07.2019
 * Time: 9:23
 */

class AdminMessageReceiver extends CActiveRecord
{
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }

  public function tableName()
  {
    return 'admin_message_receiver';
  }
  /**
   * @param $id_message - integer from admin_message
   * @return mixed
   */
  public function getDataForMessage($id_message)
  {
    return $this::model()->findAll(['condition'=>"id_message={$id_message}"]);
  }

  public function setReaded($id_message, $id_user)
  {
    Yii::app()->db->createCommand()->update(
      $this->tableName(),
      ['readed'=>time()],
      'id_user=:id_user and id_message=:id_messege',
      [':id_user'=>$id_user,':id_messege'=>$id_message]
    );
  }

}