<?php
/**
 * Date: 16.02.2016
 * Time: 9:01
 */


class UserIdentityCustom extends CUserIdentity
{
    private $_id;
    public function authenticate()
    {
        $record=User::model()->findByAttributes(array('login'=>'test1'));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
//        else if(!CPasswordHelper::verifyPassword('11',$record->passw))
        else if(md5('11') != $record->passw)
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id=$record->id_user;
            $this->setState('email', $record->email);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
}