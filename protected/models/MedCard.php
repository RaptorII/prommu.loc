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
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('fff',$this->fff,true);
		$criteria->compare('iii',$this->iii,true);
		$criteria->compare('ooo',$this->ooo,true);
		$criteria->compare('pay',$this->pay);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('regaddr',$this->regaddr,true);
		$criteria->compare('crdate',$this->crdate,true);
		$criteria->compare('comment',$this->comment,true);
        $criteria->compare('processed',$this->processed,true);


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => ['defaultOrder'=>'id desc'],
		));
	}

    public function getNewCnt() {
        return  $this->countByAttributes(['status'=> 0]);

    }
}
		// should not be searched.

		