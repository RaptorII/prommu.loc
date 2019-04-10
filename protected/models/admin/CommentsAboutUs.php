<?
class CommentsAboutUs extends CActiveRecord
{
	public $offset;
	public $limit;

	function __construct()
	{
		$this->limit = 100;
		$this->offset = 0;
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'comments_about_us';
	}
	/** 
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;
		return new CActiveDataProvider(
				get_class($this), 
				array(
					'criteria' => $criteria,
					'pagination' => ['pageSize' => $this->limit],
					'sort' => ['defaultOrder' => 'is_viewed asc,id desc']
				)
			);
	}
	/**
	 * @param $date - integer (unix time)
	 * @param $format - string
	 *		красивая дата
	 */
	public static function getDate($date, $format = 'd.m.Y G:i')
	{
		return !empty($date) ? date($format, $date) : ' - ';
	}
	/**
	 * @param $status - bool
	 */
	public static function getStatus($status)
	{
		return $status ? 'Отрицательный' : 'Положительный';
	}
	/**
	 * @param $id_user - integer
	 *		данные о юзере
	 */
	public static function getUser($id_user)
	{
		$arUser = Share::getUsers([$id_user])[$id_user];
		$link = '/admin/site/' . (Share::isApplicant($arUser['status']) ? 'PromoEdit/' : 'EmplEdit/') . $id_user;
		echo '<a href="' . $link . '" title="Просмотреть профиль">' . $arUser['name'] . '</a>';
	}
	/**
	*		Чтение данных	
	*/
	public function getData($id)
	{
		$item = $this::model()->findByPk($id);
		$item->is_viewed = 1;
		$item->save();
		return array('item' => $item);
	}
	/**
	 * 
	 */
	public function commentsCnt()
	{
    $query = self::model()->findAll(array(
                'condition' => 'is_viewed=0',
                'order' => 'id DESC',
            )
        );
    return count($query);
	}
}