<?
class CodeReview extends CActiveRecord
{
	public $reviewId;
	public $limit;
	public $view;
	public $pageTitle;
	public $author;
	public $search_field;

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

	public function rules()
	{
		return array(
				array('name, author','safe','on'=>'search')
			);
	}

	function relations()
	{
		return array(
				'user'=>array(self::BELONGS_TO,'userAdm','author_id')
			);
	}
	/** 
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$condition = ['t.in_archive=0'];
		$criteria = new CDbCriteria;
		$criteria->with = array('user');
		// search
		$criteria->compare('user.surname',$this->author, true);
		$search = filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		if(!empty($search))
		{
			$criteria->condition = '(t.tags like :q) or (t.name like :q) 
				or (t.description like :q) or (t.code like :q)';
			$criteria->params = [':q'=>"%{$search}%"];
		}
		else
		{
			$surname = filter_var($_GET['CodeReview']['author'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			if(!empty($surname))
			{
				$condition[] = 'user.surname like :surname';
				$criteria->params[':surname'] = "%{$surname}%";
				$this->author = $surname;
			}
			// name
			$criteria->compare('t.name',$this->name, true);
			$name = filter_var($_GET['CodeReview']['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			if(!empty($name))
			{
				$condition[] = 't.name like :name';
				$criteria->params[':name'] = "%{$name}%";
				$this->name = $name;
			}	
		}

		if(count($condition))
		{
			$criteria->condition = implode(' and ', $condition);
		}

		return new CActiveDataProvider(
				get_class($this), 
				array(
					'criteria' => $criteria,
					'pagination' => ['pageSize' => $this->limit],
					'sort' => [
						'defaultOrder' => 't.id desc',
						'attributes'=>[
							'author'=>[
								'asc'=>'user.surname',
								'desc'=>'user.surname DESC',
							],
							'*',
						]
					]
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
		// chat_id
		$this->chat_id = trim($obj->getParam('chat_id'));
		// in_archive
		$this->in_archive = intval($obj->getParam('in_archive'));

		$arTags = $obj->getParam('tags');
		$arT = [];
		for ($i=0,$n=count($arTags); $i<$n; $i++)
		{ 
			$tag = filter_var($arTags[$i],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			strlen($tag) && $arT[] = $tag;
		}
		$this->tags = count($arT) ? implode(' ',$arT) : '';
		
		empty($this->name) && $arRes['messages'][] = 'поле "Название" должно быть заполнено';
		empty($this->description) && $arRes['messages'][] = 'поле "Описание" должно быть заполнено';
		empty($this->code) && $arRes['messages'][] = 'поле "Код" должно быть заполнено';
		empty($this->tags) && $arRes['messages'][] = 'для улучшения поиска необходимо ввести теги';

		if(count($arRes['messages'])) // error
		{
			$arRes['error'] = true;
			$objItem = $this->getData($id)['item'];
			if(intval($id) && is_object($objItem))
			{
				$arRes['item'] = $objItem;
			}
			else
			{
				$arRes['item'] = $this;
			}

			return $arRes;
		}

		$time = time();
		$this->mdate = $time;

		if(!intval($id)) // insert
		{
			$this->cdate = $time;
			$this->author_id = Yii::app()->user->id;
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