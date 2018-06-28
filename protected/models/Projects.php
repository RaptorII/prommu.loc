<?php
class Projects extends ARModel
{
	/*
	*		получить проекты работодателя
	*/
    public function getProjects($id, $type)
    {
    	if(!$id) 
    		return false;

        $arResult['projects'] = Yii::app()->db->createCommand()
                ->select('p.id, p.id_user, p.cdate, p.mdate')
                ->from('projects p')
                ->where('p.id_user=:id', array(':id' => $id))
                ->queryAll();

        return $arResult;
    }
}
?>