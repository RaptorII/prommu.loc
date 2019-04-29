<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	 protected $_id;


	public function authenticate()
	{

		// Производим стандартную аутентификацию, описанную в руководстве.
        $user = UserAdm::model()->find('LOWER(login)=?', array(strtolower($this->username)));
        if(($user===null) or (md5($this->password)!==$user->passw) or $user->isblocked) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            // В качестве идентификатора будем использовать id, а не username,
            // как это определено по умолчанию. Обязательно нужно переопределить
            // метод getId(см. ниже).
            $this->_id = $user->id;

            // Далее логин нам не понадобится, зато имя может пригодится
            // в самом приложении. Используется как Yii::app()->user->name.
            // realName есть в нашей модели. У вас это может быть name, firstName
            // или что-либо ещё.
            $this->username = $user->login;

            $this->errorCode = self::ERROR_NONE;
        }
    	return !$this->errorCode;
	}


	public function getId(){
        return $this->_id;
    }
}