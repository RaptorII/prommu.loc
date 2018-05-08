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
class Menu extends CActiveRecord
{
	//public $input;
	/**
	 * Returns the static model of the specified AR class.
	 * @return MenuTree the static model class
	 */
	private static $_output = "";
	private static $isadmin = true;

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
			array('menu_id, parent_id, page_id, link_type, pos', 'required'),
			array('menu_id, parent_id, page_id, link_type, hidden, del, pos', 'numerical', 'integerOnly'=>true),
			array('module', 'length', 'max'=>30),
			array('link', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, module, menu_id, parent_id, page_id, link, link_type, hidden, del, pos', 'safe', 'on'=>'search'),
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
			'menu_id' => 'Menu',
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
		$criteria->compare('menu_id',$this->menu_id);
		$criteria->compare('show_auth',$this->show_auth);
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

	public function updateMenu($lang, $id)
	{
		// --- Обновить данные в 2 таблицах ---
		$model = new MenuTree;
		$model2 = new MenuTreeNames;
		$result = MenuTreeNames::model()->find("menu_tree_id=$id and lang='".$lang."'");
		$model->attributes=$_POST;
		$model2->attributes=$_POST;
		MenuTreeNames::model()->updateByPk($result->id, array('name' => $model2->attributes['name']));
		MenuTree::model()->updateByPk($id, array('link' => $model->attributes['link'],
			'module' => $model->attributes['module'], 'show_auth' => $model->attributes['show_auth'],
			'hidden' => $model->attributes['hidden']));
	}

	public function getMaxPosMenu($menu_type)
	{
		$result = Yii::app()->db->createCommand()
    	->select('MAX(pos) as maxpos')
    	->from('menu_tree')
    	->where('parent_id=:pid and link_type=:link_type', array(':pid'=>0, ':link_type'=>$menu_type))
    	->queryRow();
    	return $result['maxpos'];
	}

	public function newMenu($lang, $id, $parent, $menu_type)
	{
		$model = new MenuTree;
		if($parent==null || $parent=='') $parent=0;
		$model->attributes=$_POST;
		$model->parent_id = $parent;
		$model->page_id = 1;
		$model->link_type = $menu_type;
		$model->menu_id = 1;
		if(!isset($_POST['show_auth'])) $model->show_auth = 0;
		if(!isset($_POST['hidden'])) $model->hidden = 0;
		$rows_pos = self::getMaxPosMenu($menu_type);
		$model->pos = $rows_pos + 1;
		//print_r($model->attributes);die;
		$model->save();
		$id = Yii::app()->db->lastInsertID;

//		echo "id=$id";die;
    $rows = Yii::app()->db->createCommand()
    	->select('id, name')
    	->from('lang')
    	->queryAll();

		foreach($rows as $row)
		{
      $command = Yii::app()->db->createCommand();
      $command->insert('menu_tree_names', array(
    		'menu_tree_id'=>$id,
    		'name'=>$_POST["name"],
    		'lang'=>$row['name'],
      ));
		}
	}

	// данные для формы редактирования
	public function getMenuForm($lang, $id)
	{
		if($id==0)
		{
			$result = new MenuTree;
			$result->id = 0;
			$result->hidden = 0;
			$result->module = 'page';
			$result->show_auth = 0;
			$result->parent_id = 0;
			$result->page_id = 1;
			$result->link = '';
			$result->del = 0;
			$result->pos = 0;
			$result->MenuTreeNames = new MenuTreeNames;
			$result->MenuTreeNames->name = '';

			return $result;
		}
		return MenuTree::model()->with('MenuTreeNames')->find("t.id=$id and lang='$lang'");
	}


	public function delMenu($id, $lang, $menu_type)
	{
		//echo Yii::app()->createAbsoluteUrl('/site/upload'); die;
		$command = Yii::app()->db->createCommand();
		$command->update('menu_tree', array('del'=>'1',),
			'id=:id', array(':id'=>$id));

		return self::getAllTree(1, 0, $lang, $menu_type);
	}

	public function getMenuListHtml($lang,$menu_type)
	{
		return self::getAllTree(1, 0, $lang,$menu_type);
	}

	public function getMenuListOfPos($id, $switch, $lang, $menu_type)
	{
		if(self::changePos($id,$switch,$menu_type))
		{
			return self::getMenuListHtml($lang,$menu_type);
		}
		return "Error menulist";
	}

	public function changePos($id, $switch, $menu_type)
	{
		// Определяем позицию обновляемого объекта
		//$current_pos = $db->variable("SELECT `pos` FROM `menu_tree` WHERE `id`='$id' LIMIT 1");
		$result = MenuTree::model()->find("id=$id");

		$current_pos = $result->pos;

		if(!$current_pos) return false;
		// Определяем максимальную(последнюю) позицию в таблице
    	$max_pos = self::getMaxPosMenu($menu_type);

		if(!$max_pos) $max_pos = 0;

		// Режим
		switch($switch)
		{
			case 'up':
				$new_pos = $current_pos-1;
				if( $new_pos == 0 ) return false;

			break;
			case 'down':
				$new_pos = $current_pos+1;
				if( $new_pos > $max_pos ) return false;

			break;
			case 'top':

				$new_pos = 1;

			break;
			case 'bottom':

				$new_pos = $max_pos;

			break;
			case 'delete':

				$new_pos = $max_pos;

			break;
			default:

				return false;

			break;
		}
		// Собираем массив позиций всех записей
		//$id_array = $db->assoc("SELECT `id` FROM `$table` $add_query ORDER BY `pos`");

		$id_array = Yii::app()->db->createCommand()
    	->select('id')
    	->from('menu_tree')
    	->where('parent_id=:pid and link_type=:link_type', array(':pid'=>0, ':link_type'=>$menu_type))
    	->order('pos')
    	->queryAll();

		$i=1;
		foreach($id_array as $id)
		{
			$positions[$i] = $id;
			$i++;
		}
		// Смена `pos` редактируемой записи (id)

		if ( $new_pos > $current_pos ) // Если новое значение `pos` больше текущего
		{
			// Перемещаем позицию ВНИЗ относительно значений `pos`
			while($current_pos != $new_pos)
			{
				$temp = $positions[$current_pos]; // id текущей позиции
				$positions[$current_pos] = $positions[$current_pos+1];
				$positions[$current_pos+1] = $temp;
				$current_pos++;
			}
		}
		else
		{
			// Перемещаем позицию ВВЕРХ относительно значений `pos`
			while($current_pos != $new_pos)
			{
				$temp = $positions[$current_pos];
				$positions[$current_pos] = $positions[$current_pos-1];
				$positions[$current_pos-1] = $temp;
				$current_pos--;
			}
		}
		// Обновляем позиции (pos) всего дерева записей
		foreach($positions as $pos_number=>$id_pos)
		{
			 //$db->query("UPDATE `$table` SET `pos`='$pos_number' WHERE `id`='{$id_pos['id']}'");
			MenuTree::model()->updateByPk($id_pos['id'], array('pos' => $pos_number));
		}
		return true;
	}


	public function getModelList()
	{
		return Modules::model()->findAll("in_list=1");
	}


/*
	// Вывод меню
	public function showMenu($name,$menu_id,$maxlevel=1,$parent_id=0)
	{
		$this->maxlevel = $maxlevel;
		// собираем дерево

		$tree = $this->getTree($menu_id,1,$parent_id); // 1 - iterration counter
		if($tree === false) return '[no tree]';

		$output = '<div id="'.$name.'">'.$this->rn;

		if(method_exists($this,$name))
			$this->output .= $this->$name($tree);

		$output .= '</div>'.$this->rn;

		return $output;
	}
*/

	public function getTreeDB($parent_id, $lang, $menu_type, $isauth)
	{
		$result = Yii::app()->db->createCommand()
    	->select('menu_tree.id, parent_id, name, module, link, menu_tree.show_auth as visible, hidden')
    	->rightJoin('menu_tree_names n', 'n.menu_tree_id=menu_tree.id AND lang=:lang', array(':lang'=>'ru'))
    	->from('menu_tree')
    	->where('lang=:lang and del=:del and link_type=:menu_type and show_auth=:auth', array(':lang'=>$lang, ':del'=>0, ':menu_type'=>$menu_type, ':auth'=>$isauth))
    	->order('parent_id, pos')
    	->queryAll();

    	return $result;
	}


	public function getTree($parent_id, $lang, $menu_type, $isauth)
	{
		$result = Yii::app()->db->createCommand()
    	->select('menu_tree.id, parent_id, name, module, link, menu_tree.show_auth as visible ')
    	->rightJoin('menu_tree_names n', 'n.menu_tree_id=menu_tree.id AND lang=:lang', array(':lang'=>'ru'))
    	->from('menu_tree')
    	->where('lang=:lang and del=:del and link_type=:menu_type and show_auth=:auth', array(':lang'=>$lang, ':del'=>0, ':menu_type'=>$menu_type, ':auth'=>$isauth))
    	->order('parent_id, pos')
    	->queryAll();

    	return json_encode($result);
	}

/**
 * getTwoTree
 *
 * Генерирует 2 меню (главное + личный кабинет)
 * произошло изменение в логике - теперь генерирует только меню ЛК
 */
	public function getTwoTree($parent_id, $lang, $menu_type, $menu_type2, $isauth)
	{
		$result = Yii::app()->db->createCommand()
    	->select('menu_tree.id, parent_id, name, module, link, menu_tree.show_auth as visible')
    	->rightJoin('menu_tree_names n', 'n.menu_tree_id=menu_tree.id AND lang=:lang', array(':lang'=>$lang))
    	->from('menu_tree')
    	->where('(link_type=:menu_type2) and lang=:lang and del=:del', array(':lang'=>$lang, ':del'=>0,
        ':menu_type'=>$menu_type, ':menu_type2'=>$menu_type2))
    	->order('link_type, parent_id, pos')
    	->queryAll();
      //link_type=:menu_type or
    	//echo $menu_type2; die;
    	return json_encode($result);
	}

	public function getAllTree($menu_id, $parent_id, $lang, $menu_type)
	{
		//$query = mysql_query('SELECT * FROM menu ORDER BY pid, id');
		//$result= mysql_fetch_array($query);
		$result = Yii::app()->db->createCommand()
    	->select('menu_tree.id, parent_id, link, name, module')
    	->rightJoin('menu_tree_names n', 'n.menu_tree_id=menu_tree.id AND lang=:lang', array(':lang'=>'ru'))
    	->from('menu_tree')
    	->where('lang=:lang and del=:del and link_type=:menu_type', array(':lang'=>$lang, ':del'=>0, ':menu_type'=>$menu_type))
    	->order('parent_id, pos')
    	->queryAll();

		$tree = array();
		foreach ($result as $row) {
			$tree[(int) $row['parent_id']][] = $row;
		}
		self::$_output = "";
		//print_r($tree); die;
		self::treePrint($tree);
		return self::$_output;
	}

	function treePrint($tree, $pid=0) {
	    if (empty($tree[$pid]))
	        return;
	    self::$_output .= '<ul>';
	    foreach ($tree[$pid] as $k => $row) {
	        self::$_output .= '<li>';
	        if(self::$isadmin)
	        	self::wrapMenuAdmin($row['id'],$row['name']);
	        else
	        	self::$_output .= $row['name'];

	        if (isset($tree[$row['id']]))
	            self::treePrint($tree, $row['id']);
	        self::$_output .= '</li>';
	    }
	    self::$_output .= '</ul>';
	}

	function wrapMenuAdmin($id, $name){
		self::$_output .='<div class="m_element">';
		self::$_output .='<span class="m_element_link">';
		self::$_output .='<a href="#" onclick="go('.$id.')">'.$name.'</a></span>';
		self::$_output .='<div class="m_element_options">';
		self::$_output .='<a href="#" onclick="addMenu('.$id.')"><img src="/css/backend/menu/81.png" alt="добавить сюда"/></a>';
		self::$_output .='<a href="/adm/menu/do.hide/?id=5"><img src="/css/backend/menu/55.png" alt="скрыть"/></a>';
		self::$_output .='<a href="#" onclick="changePos('.$id.',\'up\')"><img src="/css/backend/menu/3.png" alt="вверх"/></a>';
		self::$_output .='<a href="#" onclick="changePos('.$id.',\'down\')"><img src="/css/backend/menu/4.png" alt="вниз"/></a>';
		self::$_output .='<a href="#" onClick="deleteMenu('.$id.')"><img src="/css/backend/menu/12.png" alt="удалить"/></a>';
		self::$_output .='</div></div>';
   }


}