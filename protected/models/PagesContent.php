<?php

/**
 * This is the model class for table "pages_content".
 *
 * The followings are the available columns in table 'pages_content':
 * @property integer $id
 * @property integer $page_id
 * @property integer $hidden
 * @property string $name
 * @property string $html
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $lang
 */
class PagesContent extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PagesContent the static model class
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
		return 'pages_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('page_id', 'required'),
			array('page_id, hidden', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
            array('img', 'length', 'max'=>50),
            array('anons', 'safe'),
			array('meta_title, meta_keywords', 'length', 'max'=>190),
			array('meta_description', 'length', 'max'=>255),
			array('lang', 'length', 'max'=>2),
			array('html', 'safe'),
            array('pubdate', 'date', 'format' => 'yyyy-M-d H:m:s'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, page_id, hidden, name, html, meta_title, meta_description, meta_keywords, lang', 'safe', 'on'=>'search'),
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
//				return array(
//        'Pages'=>array(self::BELONGS_TO, 'Pages ', array('id'=>'page_id')));


		return array(
			'page' => array(self::BELONGS_TO, 'Pages', 'page_id'),
		);


	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'page_id' => 'Page',
			'hidden' => 'Hidden',
			'name' => 'Name',
            'anons' => 'Anons',
            'img' => 'Img',
			'html' => 'Html',
			'meta_title' => 'Meta Title',
			'meta_description' => 'Meta Description',
			'meta_keywords' => 'Meta Keywords',
			'lang' => 'Lang',
            'pubdate' => 'Pubdate',
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

		$criteria->compare('page_id',$this->page_id);

		$criteria->compare('hidden',$this->hidden);

		$criteria->compare('name',$this->name,true);

        $criteria->compare('img',$this->img,true);

		$criteria->compare('html',$this->html,true);

		$criteria->compare('meta_title',$this->meta_title,true);

		$criteria->compare('meta_description',$this->meta_description,true);

		$criteria->compare('meta_keywords',$this->meta_keywords,true);

		$criteria->compare('lang',$this->lang,true);

		return new CActiveDataProvider('PagesContent', array(
			'criteria'=>$criteria,
		));
	}


	/* ------------ FUNCTIONAL --------------*/
	public function getContent($lang, $id) {
		if($id==0)
		{
			$result = new PagesContent;
			$result->id = 0;
			$result->hidden = 0;
			$result->lang = $lang;
			return $result;
		}
		else {
        /*
			$result = Yii::app()->db->createCommand()
    	->select('pages.id, pages.link, hidden, name, html, meta_title, meta_description, meta_keywords, lang')
    	->leftJoin('pages_content n', 'n.page_id=pages.id AND lang=:lang', array(':lang'=>$lang))
    	->from('pages')
    	->where('lang=:lang and pages.id=:id', array(':lang'=>$lang, ':id'=>$id))
    	->order('id')
    	->queryRow();
    	*/
       // print_r($result);die;
        //return $result;
        $result = Yii::app()->db->createCommand()
    	->select('id')
    	//->rightJoin('pages', 'pages.id=pages_content.page_id AND lang=:lang', array(':lang'=>$lang))
    	->from('pages_content')
    	->where('page_id=:pid and lang=:lang', array(':pid'=>$id, ':lang'=>$lang))
    	->queryRow();
        //return $result;
        $page_id = $result['id'];
    	return PagesContent::model()->findByPk($page_id);
		}
	}

	public function SaveContent($id, $params, $pagetype = '')
	{
		if(empty(trim($params['link'])))
		{
			$params['link'] = trim($params['name']);
			if(preg_match('/[^A-Za-z0-9_\-]/', $params['link']))
			{
				$params['link'] = str_seo_url($params['link']);
				$params['link'] = preg_replace('/[^A-Za-z0-9_\-]/', '', $params['link']);
			}
			else
			{
				$params['link'] = rand(1111111111,9999999999);
			}
		}

		if($id == 0) // создание контента
		{
			switch ($pagetype)
			{
				case 'news': $group_id=2; break;
				case 'articles': $group_id=99; break;
				default: $group_id=1; break;
			}
			// сохраняем в pages
			Yii::app()->db->createCommand()->insert(
				'pages', 
				['link'=>$params['link'], 'group_id'=>$group_id]
			);
			// Сохраняем в pages_content
			$this->page_id = Yii::app()->db->lastInsertID;
			$this->hidden = intval($params['hidden']);
			$this->name = filter_var($params['name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$this->anons = filter_var($params['anons'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$this->html = self::ClearHeaders($params['html']);
			$this->img = filter_var($params['img'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$this->meta_title = filter_var($params['meta_title'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$this->meta_description = filter_var($params['meta_description'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$this->meta_keywords = filter_var($params['meta_keywords'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$this->pubdate = filter_var($params['pubdate'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$this->mdate = date('Y-m-d H:i:s');
			$this->imganons = NULL;
			$this->lang = 'ru';
			$this->crdate = date('Y-m-d H:i:s');
			$this->digest = 0;
			$this->setIsNewRecord(true);
			$this->save();
		}
		else // обновление контента
		{
			$content_id = Yii::app()->db->createCommand()
				->select('id')
				->from('pages_content')
				->where('page_id=:pid', [':pid'=>$id])
				->queryScalar();

			if($content_id>0)
			{
				// сохраняем в pages
				Yii::app()->db->createCommand()
					->update('pages', ['link'=>$params['link']], 'id=:id', [':id'=>$id]);
				// Сохраняем в pages_content
				self::model()->updateByPk(
					$content_id,
					[
						'hidden' => intval($params['hidden']),
						'name' => filter_var($params['name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),
						'anons' => filter_var($params['anons'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),
						'html' => self::ClearHeaders($params['html']),
						'img' => filter_var($params['img'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),
						'meta_title' => filter_var($params['meta_title'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),
						'meta_description' => filter_var($params['meta_description'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),
						'meta_keywords' => filter_var($params['meta_keywords'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),
						'pubdate' => filter_var($params['pubdate'],FILTER_SANITIZE_FULL_SPECIAL_CHARS),
						'mdate' => date('Y-m-d H:i:s')
					]
				);
			}
		}
	}
	/*
	*	Очистка заголовков от тегов
	*/
    public function ClearHeaders($content)
	{
		$pattern = "'<h[2-6][^>]*?>.*?</h[2-6]>'si";
		preg_match_all($pattern, $content, $arHeaders);// собираем заголовки
		$arContent = preg_split($pattern, $content);
		if(sizeof($arHeaders[0]) > 0){ // если заголовки найдены
			$newContent = reset($arContent);
			foreach ($arHeaders[0] as $key => $header){
				preg_match("'</h(.+?)>'si", $header, $numH); // определяем заголовок
				preg_match_all("' style=\"(.+?)\"'is", $header, $arStyles); // собираем все стили
				$allStyles = '';
				if(sizeof($arStyles[1]) > 0)
					$allStyles = implode(";", $arStyles[1]);
				$headerText = strip_tags($header);
				$newContent .= '<h' . $numH[1] . ' style="' . $allStyles . '">' . $headerText . '</h' . $numH[1] . '>';
				$newContent .= next($arContent);
			}
			return $newContent;
		}
		else{
			return $content;
		}

    }
	public function getAllPages()
	{
		$result = Yii::app()->db->createCommand()
    			->select('id, link, group_id')
    			->from('pages')
    			->queryAll();
    	return $result;
	}

	public function getPageContent($link,$lang)
	{
		$result = Yii::app()->db->createCommand()
    			->select('t.id, t.link, p.name, p.html, p.meta_title, p.meta_description, p.meta_keywords, p.lang')
    			->from('pages t')
    			->rightJoin('pages_content p', 't.id=p.page_id')
    			->where('t.link=:link and p.lang=:lang and hidden=:hidden', array(':link'=>$link, ':lang'=>$lang, ':hidden'=>0))
    			->queryRow();
    	return $result;
	}


	public function DeleteContent($id)
	{
			$command = Yii::app()->db->createCommand()
    			->delete('pages_content','page_id=:id', array(':id'=>$id));

			$command = Yii::app()->db->createCommand()
    			->delete('pages','id=:id', array(':id'=>$id));
	}


	public function getVacanies($inLang)
	{
        try
        {
            $res = (new Vacancy())->getVacanciesQueries(array('page' => 'index'));
        }
        catch (Exception $e) {
            return array('error' => $e->getMessage());
        } // endtry



        $data['vacs'] = array();
        foreach ($res as $key => $val)
        {
            if( !isset($data['vacs'][$val['id']])) $data['vacs'][$val['id']] = array('city' => array(), 'posts' => array(), 'metroes' => array()) ;
            $data['vacs'][$val['id']]['city'][$val['id_city']] = $val['id_city'] > 0 ? $val['ciname'] : $val['citycu'];
            $data['vacs'][$val['id']]['posts'][$val['id_attr']] = $val['pname'];
            if( $val['mid'] ) $data['vacs'][$val['id']]['metroes'][$val['mid']] = $val['mname'];
            $data['vacs'][$val['id']] = array_merge($data['vacs'][$val['id']], $val);
        } // end foreach

        $i = 1;
        $ret['vacs'] = array();
        foreach ($data['vacs'] as $key => $val) { $ret['vacs'][$i] = $val; $i++; }

    	return $ret['vacs'];
	}


	public function getVacaniesAppointments($inLang)
	{
		$result = Yii::app()->db->createCommand()
    			->select("id, name, comment")
					->from('user_attr_dict d')
    			->where('d.id_par = 110')
    			->order('name')
    			->queryAll();

    	return $result;
	}


	public function getApplicants($inLang)
	{
    	return (new Promo())->getApplicantsQueries(array('page' => 'index'));
	}


	public function getCompanies($inLang)
	{
        return (new Employer())->getEmployersQueries(array('page' => 'index'));
	}
}