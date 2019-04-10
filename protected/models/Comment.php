<?php
/**
 * Date: 08.09.2016
 *
 * Модель отзывов соискателя
 */

class Comment extends ARModel
{
    function __construct()
    {
        parent::__construct();
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'comments';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id', 'required'),
            // array('ishide', 'numerical', 'integerOnly' => true),
            array('id_promo, id_empl, message', 'length', 'max' => 500),
            
            array('id, iseorp, id_promo, id_empl, message, isneg, isactive, processed, crdate', 'safe', 'on' => 'search'),
        );
    }


        
    
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'id_promo' => 'Id_promo',
            'id_empl' => 'Id_empl',
            'message' => 'Message',
            'iseorp' => 'Iseorp',
            'isneg' => 'Isneg',
            'isactive' => 'Isactive',
            'processed' => 'Processed',
            'crdate' => 'Crdate',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('id_promo', $this->id_promo,true);
        $criteria->compare('id_empl', $this->id_empl,true);
        $criteria->compare('message', $this->message,true);
        $criteria->compare('iseorp', $this->iseorp,true);
        $criteria->compare('isneg', $this->isneg,true);
        $criteria->compare('isactive', $this->isactive,true);
        $criteria->compare('processed', $this->processed,true);
        $criteria->compare('crdate', $this->crdate,true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 10,),
            'sort' => ['defaultOrder'=>'id desc'],
        ));
    }

    public function ChangeModer($id, $st)
    {
         $sql = "UPDATE comments 
                INNER JOIN (
                  SELECT mm.id
                  FROM comments mm
                  WHERE mm.id = {$id} 
                ) t1 ON comments.id = t1.id
                SET isactive = {$st} ";

        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);
        $res->execute();
        
    }
    /**
     *  счетчик для админки
     */
    public function commentsCnt()
    {
        $arRes = array(
                'emp_reviews' => 0,
                'app_reviews' => 0,
                'all' => 0
            );

        $query = self::model()->findAll(array(
                    'condition' => 'is_viewed=0',
                    'order' => 'id DESC',
                )
            );

        if(count($query))
        {
            foreach ($query as $obj)
            {
                $obj->iseorp==1 
                    ? $arRes['emp_reviews']++
                    : $arRes['app_reviews']++;

            }
            $arRes['all'] = count($query);
        }

        return $arRes;
    }
    /**
     * @param $iseorp - bool
     */
    public function setViewed($iseorp)
    {
        Yii::app()->db->createCommand()->update(
                'comments', 
                ['is_viewed'=>1],
                'iseorp=:iseorp',
                [':iseorp' => $iseorp]
            );
    }
}