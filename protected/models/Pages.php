<?php

/**
 * This is the model class for table "pages".
 *
 * The followings are the available columns in table 'pages':
 * @property integer $id
 * @property string $link
 * @property string $group_id
 */
class Pages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Pages the static model class
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
		return 'pages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('link', 'length', 'max'=>100),
			array('group_id', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, link, group_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		//return array(
		//);

		//return array('PagesContent'=>array(self::HAS_MANY, 'PagesContent', array('page_id'=>'id')));

				return array(
			'pages_contents' => array(self::HAS_MANY, 'PagesContent', 'page_id'),
		);

	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'link' => 'Link',
			'group_id' => 'Group',
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

		$criteria->compare('link',$this->link,true);

		$criteria->compare('group_id',$this->group_id,true);

		return new CActiveDataProvider('Pages', array(
			'criteria'=>$criteria,
		));
	}

	public function getPagesList()
	{
		$lang = 'ru';
		$result = Yii::app()->db->createCommand()
    	->select('pages.id, pages.link, name')
    	->leftJoin('pages_content n', 'n.page_id=pages.id AND lang=:lang', array(':lang'=>$lang))
    	->from('pages')
    	->order('id')
    	->queryAll();

    	return $result;
	}
	
}