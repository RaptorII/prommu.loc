<?php

/**
 * This is the model class for table "languages".
 *
 * The followings are the available columns in table 'languages':
 * @property integer $id
 * @property string $keyword
 * @property string $value
 * @property string $page
 * @property string $lang
 */
class Languages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Languages the static model class
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
		return 'languages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('keyword, value, page, lang', 'required'),
			array('keyword, page', 'length', 'max'=>32),
			array('value', 'length', 'max'=>128),
			array('lang', 'length', 'max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, keyword, value, page, lang', 'safe', 'on'=>'search'),
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
			'id' => '#',
			'keyword' => Share::lword('KEY'),
			'value' => Share::lword('VALUE'),
			'page' => Share::lword('PAGE'),
			'lang' => Share::lword('LANG'),
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
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('page',$this->page,true);
		$criteria->compare('lang',$this->lang,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	
	
	//---------- my Commands -----------------
	public function Add($lang, $page, $keyword, $value)
	{
      $keyword = mb_strtoupper($keyword, "utf-8");
      $res = Yii::app()->db->createCommand()
      ->insert('languages', array(
        'keyword'=>$keyword,
        'value'=>$value,
        'page'=>$page,
        'lang'=>$lang,
        ));
       
        $id = Yii::app()->db->lastInsertID;
        
      return $id ;

	}


  public function Edit($id, $lang, $page, $keyword, $value)
	{
      $keyword = mb_strtoupper($keyword, "utf-8");
      $res = Yii::app()->db->createCommand()
      ->update('languages', array(
        'keyword'=>$keyword,
        'value'=>$value,
        'page'=>$page,
        'lang'=>$lang),
        'id=:id', array(':id'=>$id,));
       
        $id = Yii::app()->db->lastInsertID;
        
      return true;
	}
	
	
	public function GetRow($id)
	{    
    $res = Yii::app()->db->createCommand()
    ->select('id, keyword, value, page, lang')
    ->from('languages')
    ->where('id=:id', array(':id'=>$id))
    ->limit(1)
    ->queryRow();    
    return $res;
	}
	
	public function DeleteRow($id)
	{
	    $res = Yii::app()->db->createCommand()
      ->delete('languages', 'id=:id', array(':id'=>$id));
      return $res;	
	}
	
	
	public function getLangPagesList()
	{
     $criteria = new CDbCriteria;
     $criteria->select = 'page';
     $criteria->distinct = True;
     return Languages::model()->findAll($criteria);
	}



}