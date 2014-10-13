<?php

/**
 * This is the model class for table "subscribereplay".
 *
 * The followings are the available columns in table 'subscribereplay':
 * @property string $id
 * @property string $type
 * @property integer $wechatId
 * @property integer $responseId
 */
class SubscribereplayModel extends CActiveRecord
{
    const SUBSCRIBE_TYPE = 'subscribe';
    const KEYWORDS_TYPE ='keywords';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SubscribereplayModel the static model class
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
		return 'subscribereplay';
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
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, wechatId, responseId', 'safe', 'on'=>'search'),
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
			'responseId' => 'Replay',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}