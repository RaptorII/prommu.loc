<?php

/**
 * This is the model class for table "ra".
 *
 * The followings are the available columns in table 'ra':
 * @property integer $id_ra
 * @property string $name
 * @property string $web
 * @property string $position
 * @property string $logo
 * @property integer $id_user
 */
class Ra extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Ra the static model class
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
		return 'ra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, position, logo', 'required'),
			array('name, web', 'length', 'max'=>128),
			array('position, logo', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_ra, name, web, position, logo, id_user', 'safe', 'on'=>'search'),
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
			'id_ra' => '#',
			'name' => Share::lword('NAME_RA'),
			'web' => Share::lword('WEB'),
			'position' => Share::lword('POSITION'),
			'logo' => Share::lword('LOGO'),
			'id_user' => 'Id User',
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

		$criteria->compare('id_ra',$this->id_ra);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('web',$this->web,true);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('id_user',$this->id_user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	public function CustomSave($attr2, $attr1)
	{
      $command = Yii::app()->db->createCommand();
      $command->insert('user', array(
    		'login'=>$attr1['login'],
    		'passw'=>md5($attr1['passw']),
    		'email'=>$attr1['email'],
    		'status'=>$attr1['status'],
      ));
      
      $id = Yii::app()->db->lastInsertID;
      
      $command->insert('ra', array(
    		'id_user'=>$id,
    		'name'=>$attr2['name'],
    		'web'=>$attr2['web'],
    		'position'=>$attr2['position'],
    		'logo'=>$attr2['logo'],
      ));      
	}
  
  
  public function GetList($limit, $filter)
	{
    $res = Yii::app()->db->createCommand()
    ->select('t.id_ra as id, t.name')
    ->from('ra t')
    ->where('t.name like :filter', array(':filter'=>$filter.'%'))
    ->limit($limit)
    ->queryAll(); 
    return $res;
	}
  
}