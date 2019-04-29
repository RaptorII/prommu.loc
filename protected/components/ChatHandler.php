<?php
class ChatHandler extends YiiChatDbHandlerBase {
    //
    // IMPORTANT:
    // in any time here you can use this available methods:
    //  getData(), getIdentity(), getChatId()
    //
    protected function getDb()
    {
      return Yii::app()->db;
    }

    protected function createPostUniqueId()
    {
      return hash('sha1',$this->getChatId().time().rand(1000,9999));      
    }

    protected function getIdentityName()
    {
      $model = new UserAdm();
      return $model->getUser($this->getIdentity())['fullname'];
    }

    protected function getDateFormatted($value)
    {
      return Share::getPrettyDate(date('Y-m-d H:i:s',$value));
    }

    protected function acceptMessage($message){
      return $message;
      // return true for accept this message. false reject it.
      //return true;
    }
}
?>