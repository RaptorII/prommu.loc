<?php
/**
 * YiiUploadAction class file.
 *
 *	@example:
 *		public function actions() { return array('yiiupload'=>array('class'=>'YiiUploadAction')); }
 *
 * @uses CWidget
 * @version 1.0 
 * @author Dmitry Derevyanko <derevyanko977@gmail.com>
 * 
 */
class YiiUploadAction extends CAction {
	public function run(){
		$inst = new YiiUploadWidget();
		$inst->runAction();
	}
 }