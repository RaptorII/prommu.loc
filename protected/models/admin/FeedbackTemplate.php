<?php

class FeedbackTemplate
{
    /**
     * @return array|CDbDataReader
     * @throws CException
     */
    public function getTemplates() {

        $id = filter_var(
            Yii::app()->getRequest()->getParam('themeId'),FILTER_SANITIZE_NUMBER_INT
        );

        $arRes = Yii::app()->db->createCommand()
            ->select("*")
            ->from('feedback_admin_template')
            ->where('theme = :id', [':id' => $id])
            ->order('id desc')
            ->queryAll();

        return $arRes;
    }

    public function getTemplatesQuick() {

        $name = filter_var(
            Yii::app()->getRequest()->getParam('referal'), FILTER_SANITIZE_STRING
        );

        $sql = "
              SELECT
                  *
              FROM
                feedback_admin_template_theme 
              WHERE
                name
              LIKE
                '%$name%'
        ";
        return Yii::app()->db->createCommand($sql)->queryAll();
    }

    /**
     * @return array
     * @throws CException
     */
    public function addTemplate() {

        $theme = filter_var(
            Yii::app()->getRequest()->getParam('theme'),FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );

        $name = filter_var(
            Yii::app()->getRequest()->getParam('title'),FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );

        $text = filter_var(
            Yii::app()->getRequest()->getParam('text'),FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );

        Yii::app()->db->createCommand()->insert(
            'feedback_admin_template',
            array(
                'name' => $name,
                'text' => $text,
                'theme' => (int) $theme
            )
        );

        $id = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();

        return array('error'=>false, 'id'=>$id);
    }

    /**
     * @return array
     *
     */
    public function delTemplate() {

        $id = filter_var(
            Yii::app()->getRequest()->getParam('id'),FILTER_SANITIZE_NUMBER_INT
        );

        Yii::app()->db->createCommand()->delete(
            'feedback_admin_template',
            'id=:id',
            array(':id'=>$id)
        );

        return array( 'error'=>false);
    }

    /**
     * @return array|CDbDataReader
     * @throws CException
     */
    public function getThemeSel() {
        return Yii::app()->db->createCommand()
            ->select("*")
            ->from('feedback_admin_template_theme')
            ->order('id')
            ->queryAll();
    }

    /**
     * @return array
     * @throws CException
     */
    public function addThemeSel() {

        $themeName = filter_var(
            Yii::app()->getRequest()->getParam('themeNew'),FILTER_SANITIZE_FULL_SPECIAL_CHARS
        );

        Yii::app()->db->createCommand()->insert(
            'feedback_admin_template_theme',
            [
                'name' => $themeName,
            ]
        );

        $id = Yii::app()->db->createCommand('SELECT LAST_INSERT_ID()')->queryScalar();
        return array('error'=>false, 'id'=>$id);
    }

    /**
     * @return array
     */
    public function delThemeSel() {

        $id = filter_var(
            Yii::app()->getRequest()->getParam('id'),FILTER_SANITIZE_NUMBER_INT
        );

        Yii::app()->db->createCommand()->delete(
            'feedback_admin_template_theme',
            'id=:id',
            array(':id'=>$id)
        );

        return array( 'error'=>false);

    }
}