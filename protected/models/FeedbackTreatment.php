<?php

/**
 * This is the model class for table "feedback".
 *
 * The followings are the available columns in table 'feedback':
 * @property string $id
 * @property integer $type
 * @property string $name
 * @property string $theme
 * @property string $email
 * @property string $text
 * @property string $crdate
 * @property integer $pid
 * @property integer $is_smotr
 * @property string $date_smotr
 */
class FeedbackTreatment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'feedback';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, theme, text, pid, date_smotr, chat', 'required'),
			array(' pid, is_smotr', 'numerical', 'integerOnly'=>true),
			array('name, theme, type', 'length', 'max'=>100),
			array('email', 'length', 'max'=>50),
			array('crdate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, name, theme, email, text, crdate, pid, is_smotr, date_smotr, chat', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'name' => 'Name',
			'theme' => 'Theme',
			'email' => 'Email',
			'text' => 'Text',
			'crdate' => 'Crdate',
			'pid' => 'Pid',
			'is_smotr' => 'Is Smotr',
			'date_smotr' => 'Date Smotr',
			'chat' => 'Chat',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('type',$this->type, true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('theme',$this->theme,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('crdate',$this->crdate,true);
		$criteria->compare('pid',$this->pid);
		$criteria->compare('is_smotr',$this->is_smotr);
		$criteria->compare('date_smotr',$this->date_smotr,true);
		$criteria->compare('chat',$this->chat,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 20,),
			'sort' => ['defaultOrder'=>'id desc'],
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FeedbackTreatment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
