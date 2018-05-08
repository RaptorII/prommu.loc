<?php
/**
 * Опции системы
 *
 * Created by Vlasakh
 * Date: 21.09.16
 * Time: 9:41
 */


class Options extends CActiveRecord
{

    public function tableName()
    {
        return 'options';
    }


    public function getOption($inName)
    {
        return $this->find('name=:name', [':name' => $inName]);
    }


    public function getByGroup($inName)
    {
        $res = $this->findAll('`group`=:name', [':name' => $inName]);
        foreach ($res as $key => $val) $data[$val->name] = $val;

        return $data;
    }

    function test()
    {
        $options = (new Options)->findAll();
        $criteria = new CDbCriteria;
//        $criteria->select  = 'em.id as f1, em.id_user as f2, em.name as f3, e.title as f4';
//        $criteria->select  = 'em.id as sid, em.id_user, em.name, e.title title';
        $criteria->select  = 'em.id, em.id_user, em.name, e.title';
        $criteria->alias = 'em';
        $criteria->addInCondition('em.id', [1,9]); // = 'em.id = ' . Share::$UserProfile->exInfo->eid;
        $criteria->join = 'LEFT JOIN `empl_vacations` e ON em.`id_user` = e.`id_user`';
//        $criteria->with = array('empl_vacations');
        $empl = (new TestEmployer);
        $res = $empl->findAll($criteria);
//        $res = TestEmployer::model()->findAll($criteria);
        0||$notpr||print  "<pre> \$options :".$s1
              .$s1 . print_r($options[0]->name, 1)."\n"
              .$s1 . print_r($options[0]->val, 1)."\n"
              .$s1 . print_r($res[0]->title  , 1)."\n"
              .$s1 . print_r($res  , 1)."\n"
//              .$s1 . print_r($options[0]->val, 1)."\n"
              ."</pre>";$notpr;
    }
}