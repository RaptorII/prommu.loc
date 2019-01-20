<?
class CodeReview extends CActiveRecord
{
	public $reviewId;
	public $limit;
	public $view;
	public $pageTitle;

	function __construct($reviewId=0)
	{
		$this->limit = 100;
		$this->view = 'system/review';
		$this->pageTitle = $reviewId ? 'Редактирование ревью' : 'Создание ревью';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'system_development_review';
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
					'sort' => ['defaultOrder' => 'id desc']
				)
			);
	}
	/**
	*		Чтение данных	
	*/
	public function getData($id)
	{
		return array('item' => $this::model()->findByPk($id));
	}
	/**
	*		Запись данных
	*/
	public function setData($obj)
	{
		$arRes = array('error'=>false);
		// id
		$id = $obj->getParam('id');
		// name
		$this->name = filter_var(
											trim($obj->getParam('name')),
											FILTER_SANITIZE_FULL_SPECIAL_CHARS
										);
		// description
		$this->description = trim($obj->getParam('description'));
		// code
		$this->code = trim($obj->getParam('code'));
		
		empty($this->name) && $arRes['messages'][] = 'поле "Название" должно быть заполнено';
		empty($this->description) && $arRes['messages'][] = 'поле "Описание" должно быть заполнено';
		empty($this->code) && $arRes['messages'][] = 'поле "Код" должно быть заполнено';

		if(count($arRes['messages'])) // error
		{
			$arRes['error'] = true;
			$arRes['item'] = $this;

			return $arRes;
		}

		$time = time();
		$this->mdate = $time;

		if(!intval($id)) // insert
		{
			$this->cdate = $time;
			$this->setIsNewRecord(true);
		}
		else // update
		{
			$this->id = $id;
		}

		if($this->save())
		{
			Yii::app()->user->setFlash('success', 'Данные успешно сохранены');
			return array('redirect' => true);
		}
		else
		{
			Yii::app()->user->setFlash('danger', 'Ошибка сохранения');
			return array('redirect' => true);
		}

		return $arRes;
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
}