<?php
/*
 * ENicEdit widget class file.
 * @author Igogo, ZiombieIT.net <igogozombieit@gmail.com>
 * @link http://www.zombieit.net/
 * Copyright (c) 2013 Igogo, ZiombieIT.net
 * MADE IN Ukraine

 * ENicEdit extends CWidget and implements a base class for a nicEdit widget.
 * more about nicEdit can be found at http://nicedit.com/.
 * @version: 1.1
 */
class ENicEdit extends CWidget{
	public $name = 'area';
	public $options = array();
	public $htmlOptions = array();
	public $model = null;
	public $field = null;
	public $value = '';

	public function init(){
		if($this->model){
			if($this->field == null || empty($this->field)){
				throw new CException(Yii::t('core', 'Вы не указали название поля модели!'));
			}

			$this->name = get_class($this->model).'_'.$this->field;
		}

		if(empty($this->name)){
			throw new CException(Yii::t('core', 'Вы должны заполнить поле "name" или поля "model" и "field"!'));
		}

		$js = dirname(__FILE__).'/assets';
        $jsUrl = Yii::app()->assetManager->publish($js);
		if(!isset($this->options['iconsPath'])){
			$this->options['iconsPath'] = Yii::app()->createAbsoluteUrl('/'.$jsUrl.'/nicEditorIcons.gif');
		}

        Yii::app()->clientScript->registerScriptFile($jsUrl.'/nicEdit.js?v=1', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScript('nic_edit_'.$this->name, "
			new nicEditor(".json_encode($this->options).").panelInstance('".$this->name."');
        ");
	}

	public function run(){
		$this->render('editor');
	}
}