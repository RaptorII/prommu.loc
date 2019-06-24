<?php

class ServicePrice extends ARModel {
    
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'service_prices';
	}

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, price, comment, service, region, valute','required'),
            array('id, price, region', 'numerical', 'integerOnly'=>true),
            array('valute', 'length', 'max'=>64),
        
            array('id, price, comment, service, region, valute', 'safe', 'on'=>'search'),
        );

    }

    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id, true);
        $criteria->compare('price',$this->price, true);
        $criteria->compare('comment',$this->comment, true); 
        $criteria->compare('service',$this->service, true);
        $criteria->compare('region',$this->region, true);
        $criteria->compare('valute',$this->valute, true);
        
        return new CActiveDataProvider('ServicePrice', array(
            'criteria'=>$criteria,
            'pagination' => array('pageSize' => 100,),
            'sort' => ['defaultOrder'=>'id asc'],
        ));
    }


    

}