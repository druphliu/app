<?php

/**
 * This is the model class for table "menuaction".
 *
 * The followings are the available columns in table 'menuaction':
 * @property integer $id
 * @property integer $wechatId
 * @property string $action
 * @property string $type
 * @property integer $responseId
 */
class MenuactionModel extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuactionModel the static model class
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
		return 'menuaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wechatId, action, responseId', 'required'),
			array('wechatId, responseId', 'numerical', 'integerOnly'=>true),
			array('action', 'length', 'max'=>30),
			array('type', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, wechatId, action, type, responseId', 'safe', 'on'=>'search'),
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
			'wechatId' => 'Wechat',
			'action' => 'Action',
			'type' => 'Type',
			'responseId' => 'Response',
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
		$criteria->compare('wechatId',$this->wechatId);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('responseId',$this->responseId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}