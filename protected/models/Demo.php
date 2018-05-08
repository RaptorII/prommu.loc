<?php
class Demo extends CActiveRecord
{
 	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function tableName()
	{
		return 'demo';
	}
	
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('key, value', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, key', 'safe', 'on'=>'search'),
		);
	}



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
			'id' => '#',
			'key' => 'Demo Key',
			'value' => 'Demo Value',
		);
	}
	
	
	
	public function afterSave() {
		parent::afterSave();
		//связываем нового пользователя с ролью
		//$auth=Yii::app()->authManager;
		//предварительно удаляем старую связь
		//$auth->revoke($this->prevRole, $this->u_id);
		//$auth->assign($this->u_role, $this->u_id);
		//$auth->save();
		return true;
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

		$criteria->compare('key',$this->key,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}


}
?>