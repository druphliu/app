<?php

/**
 * This is the model class for table "openreplay".
 *
 * The followings are the available columns in table 'openreplay':
 * @property integer $id
 * @property integer $openId
 * @property string $type
 * @property integer $wechatId
 * @property string $name
 * @property integer $status
 */
class OpenReplayModel extends CActiveRecord
{
    const OPEN_TYPE = 'open';

    public $action;
    public $keywords;
    public $isAccurate;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OpenReplayModel the static model class
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
		return 'openreplay';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,openId', 'required'),
			array('openId, wechatId', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, openId, type, wechatId', 'safe', 'on'=>'search'),
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
            'open_keywords' => array(self::HAS_MANY, 'KeywordsModel', 'responseId'),
            'open_menuaction' => array(self::HAS_MANY, 'MenuactionModel', 'responseId','with'=>'action_menu'),
            'open_openPlatForm'=>array(self::BELONGS_TO,'OpenPlatformModel','openId')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'openId' => '转接平台',
			'type' => 'Type',
			'wechatId' => 'Wechat',
            'name'=>'名称',
            'keywords'=>'关键字',
            'isAccurate'=>'是否精准匹配',
            'action'=>'菜单名'
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
		$criteria->compare('openId',$this->openId);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('wechatId',$this->wechatId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}