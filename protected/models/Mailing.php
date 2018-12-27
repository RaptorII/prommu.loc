<?
class Mailing extends CActiveRecord
{
	public $offset;
	public $limit;

	public static $EVENT_TYPE_EMAIL = 1;
	public static $EVENT_TYPE_PUSH = 2;
	public static $TYPES = array(
			1 => 'email',
			2 => 'push'
		);

	function __construct($scenario = 'insert')
	{
		parent::__construct($scenario = 'insert');

		$this->limit = 20;
		$this->offset = 0;
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin_mailing_event';
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array();
	}
	/** 
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		/*
		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('mdate',$this->mdate,true);
		*/
		return new CActiveDataProvider(
				'Mailing', 
				array(
					'criteria' => $criteria,
					'pagination' => ['pageSize' => 20],
					'sort' => ['defaultOrder'=>'id desc']
				)
			);
	}
	/**
	*		красивая дата
	*/
	public static function getDate($date, $format = 'd.m.Y G:i')
	{
		return date($format, $date);
	}
}