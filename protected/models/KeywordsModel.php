<?php

/**
 * This is the model class for table "keywords".
 *
 * The followings are the available columns in table 'keywords':
 * @property integer $id
 * @property string $name
 * @property integer $responseId
 * @property string $type
 * @property integer $isAccurate
 * @property integer $wechatId
 */
class KeywordsModel extends CActiveRecord
{

    public static $sysKeywords =array('正版','混版','中奖查询');

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return KeywordsModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'keywords';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('responseId, isAccurate, wechatId', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>150),
			array('type', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, responseId, type, isAccurate, wechatId', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'responseId' => 'Response',
			'type' => 'Type',
			'isAccurate' => 'Is Accurate',
			'wechatId' => 'Wechat',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('responseId',$this->responseId);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('isAccurate',$this->isAccurate);
		$criteria->compare('wechatId',$this->wechatId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}