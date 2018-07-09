<?php
/**
 * Date: 06.07.2018
 *
 * Модель загрузки картинок
 */

class Loadimg extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
	
    public function tableName()
	{
		return 'images';
	}

	
}