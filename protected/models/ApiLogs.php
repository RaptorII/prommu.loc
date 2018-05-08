<?php

/**
 * This is the model class for table "api_logs".
 *
 * The followings are the available columns in table 'api_logs':
 * @property integer $id
 * @property string $cmd
 * @property integer $id_user
 * @property string $dtcreate
 */
class ApiLogs extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ApiLogs the static model class
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
		return 'api_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cmd, id_user, dtcreate', 'required'),
			array('id_user', 'numerical', 'integerOnly'=>true),
			array('cmd', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, cmd, id_user, dtcreate', 'safe', 'on'=>'search'),
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
			'cmd' => 'Cmd',
			'id_user' => 'Id User',
			'dtcreate' => 'Dtcreate',
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

		//$criteria->compare('id',$this->id);
		$criteria->compare('cmd',$this->cmd,true);
		$criteria->compare('id_user',$this->id_user);
		$criteria->compare('dtcreate',$this->dtcreate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


    public function Read($day, $limit)
    {
        $cmd=Yii::app()->db->createCommand();
        $cmd->distinct = true;
        $cmd->select('t.id_user, tn.login, tn.email, tn.status')
            ->from('api_logs t')
            ->join('user tn','tn.id_user = t.id_user');
        $cmd->where('t.dtcreate >= SUBDATE(CURDATE(), INTERVAL '.$day.' DAY)');
        $cmd->order('dtcreate');
        $cmd->limit($limit);
        $res = $cmd->queryAll();
        return $res;

    }

    public function Export($day, $id_user)
    {
        $cmd=Yii::app()->db->createCommand();
        $cmd->select('t.id, t.id_user, t.cmd, t.dtcreate, tn.login, tn.email, tn.status')
            ->from('api_logs t')
            ->join('user tn','tn.id_user = t.id_user');
        $cmd->where('t.dtcreate >= SUBDATE(CURDATE(), INTERVAL '.$day.' DAY) and t.id_user='.$id_user);
        $cmd->order('t.id');
        $res = $cmd->queryAll();
        return $res;
    }

}