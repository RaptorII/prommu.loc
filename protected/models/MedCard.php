<?php

/**
 * This is the model class for table "card_request".
 *
 * The followings are the available columns in table 'card_request':
 * @property string $id
 * @property integer $status
 * @property string $name
 * @property string $post
 * @property string $tabn
 * @property string $fff
 * @property string $iii
 * @property string $ooo
 * @property integer $doctype
 * @property string $docser
 * @property string $docnum
 * @property string $docdate
 * @property integer $docorgcode
 * @property string $docorgname
 * @property string $borndate
 * @property string $bornplace
 * @property string $tel
 * @property string $regaddr
 * @property integer $regcountry
 * @property string $liveaddr
 * @property integer $livecountry
 * @property string $files
 * @property string $crdate
 * @property string $comment
 * @property integer $processed
 */
class MedCard extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserCard the static model class
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
		return 'med_request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, tabn, fff, pay, iii, ooo, tel, regaddr', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('fff, iii, ooo', 'length', 'max'=>50),
			array('tel', 'length', 'max'=>20),
			array('regaddr', 'length', 'max'=>255),
			array('crdate, comment, processed', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, status, name, fff, iii, ooo, tel, regaddr, pay, crdate, comment, email', 'safe', 'on'=>'search'),
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
			'status' => 'Status',
			'name' => 'Name',
			'fff' => 'Fff',
			'iii' => 'Iii',
			'ooo' => 'Ooo',
			'tel' => 'Tel',
			'regaddr' => 'Regaddr',
			'crdate' => 'Crdate',
			'processed' => 'Processed',
            'pay' => 'Pay',
            'email' => 'Email'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		$param = Yii::app()->getRequest()->getParam('MedCard');

		if(intval($param['id']))
    {
      $this->id = $param['id'];
    }
    $this->fff = $param['fff'];
    $this->tel = $param['tel'];
    $this->email = $param['email'];
    if($param['regaddr']!=='0')
    {
      $this->regaddr = $param['regaddr'];
    }
    if($param['pay']!=='0')
    {
      $this->pay = $param['pay'];
    }
    if($param['status']!=='all')
    {
      $this->status = $param['status'];
    }

    $criteria->compare('id',$this->id, true);
    $criteria->compare('fff',$this->fff,true);
    $criteria->compare('tel',$this->tel,true);
    $criteria->compare('email',$this->email,true);
    $criteria->compare('regaddr',$this->regaddr,true);
    $criteria->compare('pay',$this->pay,true);
    $criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'sort' => ['defaultOrder'=>'status asc, id desc'],
		));
	}

    /**
     * @return bool|CDbDataReader|mixed|string
     */
    public static function getAdminCnt()
    {
        return  self::model()->countByAttributes(['status'=> 0]);
    }
    /**
    *
    */
    public static function getIndex($all=false)
    {
    	$arRes = [
    		'На Новослободской' => 'На Новослободской',
    		'На Таганке' => 'На Таганке',
    		'На Киевской' => 'На Киевской',
    		'На Курской' => 'На Курской'
    	];
    	if($all)
    	{
    		array_unshift($arRes, 'Все');
    	}

    	return (!empty($key) ? $arRes[$key] : $arRes);
    }
    /**
    *
    */
    public static function getPayType($all=false)
    {
    	$arRes = [
    		'Сервису Промму' => 'Сервису Промму',
    		'Наличными в Медицинском центре' => 'Наличными в Медицинском центре'
    	];
    	if($all)
    	{
    		array_unshift($arRes, 'Все');
    	}

    	return (!empty($key) ? $arRes[$key] : $arRes);
    }
  /**
   * @param $id
   * @return int
   */
  public function setAdminViewed($id)
  {
    $data = $this::model()->findByPk($id);
    $result = false;
    if(!$data['status'])
    {
      $result = $this::model()->updateByPk($id,['status'=>1]);
    }
    return $result;
  }
  /**
   * @param $id
   * @return array
   */
  public function getOrder($id)
  {
    $arRes = [];
    $arRes['item'] = $this::model()->findByPk($id);

    return $arRes;
  }
  /**
   * @param $data - array
   * @return bool|CDbDataReader|mixed|string
   */
  public function updateData($data)
  {
    $result = self::model()->updateByPk(
      $data['id'],
      [$data['field'] => $data['value']]
    );

    return ($result ? 'Данные успешно обновлены' : 'Ошибка изменения данных');
  }
}