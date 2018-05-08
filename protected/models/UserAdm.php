<?php

/**
 * This is the model class for table "user_adm".
 *
 * The followings are the available columns in table 'user_adm':
 * @property integer $id
 * @property string $login
 * @property string $passw
 * @property string $email
 * @property string $access_time
 * @property string $ip
 * @property integer $status
 * @property integer $isblocked
 */
class UserAdm extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserAdm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_adm';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, passw, access_time', 'required'),
			array('status, isblocked', 'numerical', 'integerOnly'=>true),
			array('login, passw', 'length', 'max'=>64),
			array('email', 'length', 'max'=>128),
			array('ip', 'length', 'max'=>15),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, login, passw, email, access_time, ip, status, isblocked', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Login',
			'passw' => 'Passw',
			'email' => 'Email',
			'access_time' => 'Access Time',
			'ip' => 'Ip',
			'status' => 'Status',
			'isblocked' => 'Isblocked',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('passw',$this->passw,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('access_time',$this->access_time,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('isblocked',$this->isblocked);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getAdmin($id)
	{
		$result = Yii::app()->db->createCommand()
    			->select("u.id, u.login, u.email, u.access, u.isblocked, u.status")
    			->from('user_adm u')
		    	->where('u.id=:id', array(':id'=>$id))
		    	->queryRow();

    	return $result;
	}

	public function updateAdmin($data, $id) {
		if(empty($id) || $id<=0) return null;

	

		Yii::app()->db->createCommand()
			->update('user_adm', array(
				'access' => $data['access'],
				'login' => $data['login'],
				'email' => $data['email'],
				// 'passw' => $data['passw'],
			), 'id=:id', array(':id' => $id));
	

	}

	
	public function getAccess($user_id) {
   		$result = Yii::app()->db->createCommand()
    	->select('access')    	
    	->from('user_adm')
    	->where('id=:id', array(':id'=>$user_id))
    	->queryRow();

      return $result['access'];
	}
}