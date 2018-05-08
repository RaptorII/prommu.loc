<?php

/**
 * This is the model class for table "menu_tree".
 *
 * The followings are the available columns in table 'menu_tree':
 * @property integer $id
 * @property string $module
 * @property integer $menu_id
 * @property integer $parent_id
 * @property integer $page_id
 * @property string $link
 * @property integer $link_type
 * @property integer $hidden
 * @property integer $delete
 * @property integer $pos
 */
class MenuTree extends CActiveRecord
{
	//public $input;
	/**
	 * Returns the static model of the specified AR class.
	 * @return MenuTree the static model class
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
		return 'menu_tree';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('show_auth, menu_id, parent_id, page_id, link_type, pos', 'required'),
			array('show_auth, menu_id, parent_id, page_id, link_type, hidden, del, pos', 'numerical', 'integerOnly'=>true),
			array('module', 'length', 'max'=>30),
			array('link', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, module, show_auth, parent_id, page_id, link, link_type, hidden, del, pos', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
	    return array(
	        'MenuTreeNames'=>array(self::HAS_ONE, 'MenuTreeNames', array('menu_tree_id'=>'id'))
	    );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'module' => 'Module',
			'show_auth' => 'Menu',
			'parent_id' => 'Parent',
			'page_id' => 'Page',
			'link' => 'Link',
			'link_type' => 'Link Type',
			'hidden' => 'Hidden',
			'del' => 'Delete',
			'pos' => 'Pos',
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
		$criteria->compare('module',$this->module,true);
		$criteria->compare('show_auth',$this->show_auth);
		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('page_id',$this->page_id);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('link_type',$this->link_type);
		$criteria->compare('hidden',$this->hidden);
		$criteria->compare('del',$this->del);
		$criteria->compare('pos',$this->pos);

		return new CActiveDataProvider('MenuTree', array(
			'criteria'=>$criteria,
		));
	}
}