<?php
/**
 * Статусы системы
 *
 * Created by Vlasakh
 * Date: 10.10.16
 */


class Status extends CActiveRecord
{

    public function tableName()
    {
        return 'status';
    }


    public function getStatus($inName)
    {
        return $this->find('name=:name', [':name' => $inName]);
    }



    public function setStatus($inName, $inVal)
    {
        $status = $this->find('name=:name', [':name' => $inName]);
        $status->val = $inVal;
        $status->mdate = date("Y-m-d H:i:s");
        $status->save();

        return $status;
    }
}