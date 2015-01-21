<?php

/**
 * This is the model class for table "basereplay".
 *
 * The followings are the available columns in table 'basereplay':
 * @property string $id
 * @property string $type
 * @property integer $wechatId
 * @property integer $responseId
 * @property string $replayType
 */
class BasereplayModel extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BasereplayModel the static model class
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
		return 'basereplay';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wechatId, responseId', 'required'),
			array('wechatId, responseId', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>10),
			array('replayType', 'length', 'max'=>9),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, wechatId, responseId, replayType', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'wechatId' => 'Wechat',
			'responseId' => 'Response',
			'replayType' => 'Replay Type',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('wechatId',$this->wechatId);
		$criteria->compare('responseId',$this->responseId);
		$criteria->compare('replayType',$this->replayType,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}