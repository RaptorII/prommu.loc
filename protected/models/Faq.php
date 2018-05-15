<?php
/**
 *degres
 * Модель статей
 */

class Faq extends CActiveRecord
{
	/*
	*
	*/
    public function tableName()
    {
        return 'faq_api';
    }
	/*
	*		admin faq list
	*/
    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('question',$this->question, true);
        $criteria->compare('theme',$this->theme,true);
        $criteria->compare('type',$this->type, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination' => array('pageSize' => 50,),
            'sort' => ['defaultOrder'=>'id asc'],
        ));
    }
	/*
	*
	*/
	public function getFaq()
    {
        $sql = "SELECT f.id, f.answer, f.question, f.theme, f.type
            FROM faq_api f";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryAll();

        return $res;
    }
    /*
    *	delete faq item from list for admin
    */
	public function deleteFaq($id)
	{
		$command = Yii::app()->db->createCommand()
    		->delete('faq_api','id=:id', array(':id'=>$id));
	}
    /*
    *	get faq item info for admin
    */
	public function getFaqItem($id)
	{
		return  Yii::app()->db->createCommand()
					->select('*')
					->from('faq_api')
					->where('id=:id',array(':id'=>$id))
					->queryRow();
	}
	/*
	*	update faq item info for admin
	*/
	public function changeFaqItem($id){
		Yii::app()->db->createCommand()
				->update(
					'faq_api', 
					array(
						'question'=>$_POST['question'],
						'answer'=>$_POST['answer'],
						'theme'=>$_POST['theme'],
						'type'=>$_POST['type']
					),
					'id=:id', 
					array(':id'=>$id)
			);
	}
}

?>