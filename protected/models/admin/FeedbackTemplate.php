<?php

class FeedbackTemplate
{
	/**
	 * @return $arr data
	 * Получить все шаблоны
	 */
	public function getTemplates() {
		$arRes = Yii::app()->db->createCommand()
							->select("*")
							->from('feedback_admin_template')
							->order('id desc')
							->queryAll();

		return $arRes;
	}
	/**
	 * @return $arr data
	 * Получить все шаблоны
	 */
	public function addTemplate() {
		$name = filter_var(
								Yii::app()->getRequest()->getParam('title'),
								FILTER_SANITIZE_FULL_SPECIAL_CHARS
							);
		$text = filter_var(
								Yii::app()->getRequest()->getParam('text'),
								FILTER_SANITIZE_FULL_SPECIAL_CHARS
							);

		Yii::app()->db->createCommand()
			->insert(
				'feedback_admin_template', 
				array('name' => $name,'text' => $text)
			);

		$id = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();

		return array('error'=>false, 'id'=>$id);
	}
	/**
	 * @return $arr data
	 * Получить все шаблоны
	 */
	public function delTemplate() {
		$id = filter_var(
								Yii::app()->getRequest()->getParam('id'),
								FILTER_SANITIZE_NUMBER_INT
							);

		Yii::app()->db->createCommand()->delete(
				'feedback_admin_template', 
				'id=:id', 
				array(':id'=>$id)
			);

		return array( 'error'=>false);
	}
}