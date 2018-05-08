<?php

/**
 * This is the model class for table "banners".
 *
 * The followings are the available columns in table 'banners':
 * @property integer $id
 * @property string $file
 * @property string $name
 * @property string $url
 * @property integer $ishide
 */
class Banners extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Banners the static model class
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
		return 'banners';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file', 'required'),
			array('ishide', 'numerical', 'integerOnly'=>true),
			array('file', 'length', 'max'=>32),
			array('name', 'length', 'max'=>128),
			array('url', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, file, name, url, ishide', 'safe', 'on'=>'search'),
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
			'file' => 'File',
			'name' => 'Name',
			'url' => 'Url',
			'ishide' => 'Ishide',
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
		$criteria->compare('file',$this->file,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('ishide',$this->ishide);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
  
  //=================== My Services ====================

  public function GetList($limit, $filter)
	{
    $res = Yii::app()->db->createCommand()
    ->select('t.id, t.name')
    ->from('banners t')
    ->where('t.name like :filter', array(':filter'=>$filter.'%'))
    ->limit($limit)
    ->queryAll(); 
    return $res;
	}
  
  
  public function AddName($name, $link, $file_banner)
	{
		$command = Yii::app()->db->createCommand();
		$command->insert('banners', array(
    		'ishide'=>'0',
        'name'=>$name,
        'url'=>$link,
        'file'=>$file_banner,
		));
	}

	public function EditName($name, $link, $file_banner, $id)
	{
		$command = Yii::app()->db->createCommand();
		$command->update('banners', array(
    		'name'=>$name,
        'url'=>$link,
        'file'=>$file_banner        
		), 'id=:id', array(':id'=>$id));
	}

	public function SetActive($id)
	{    
    $res = Yii::app()->db->createCommand()
    ->select('ishide')
    ->from('banners')
    ->where('id=:id', array(':id'=>$id))
    ->limit(1)
    ->queryRow();    
    
    $newStatus = $res['ishide'] ^ 1;
		
		$command = Yii::app()->db->createCommand();
		$command->update('banners', array(
    	 'ishide'=>$newStatus
		), 'id=:id', array(':id'=>$id));
		return $newStatus;
	}
}