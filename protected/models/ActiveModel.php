<?php

/**
 * This is the model class for table "active".
 *
 * The followings are the available columns in table 'active':
 * @property integer $id
 * @property integer $wechatId
 * @property string $type
 * @property string $title
 * @property string $awards
 * @property integer $ispaward
 * @property integer $predictCount
 * @property integer $isCode
 * @property string $startTime
 * @property string $endTime
 * @property string $created_at
 * @property string $unstartMsg
 * @property string $endMsg
 * @property string $pauseMsg
 * @property integer $isSensitive
 * @property integer $status
 * @property integer $times
 * @property string $desc
 */
class ActiveModel extends CActiveRecord
{
	public $keywords;
	public $isAccurate;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'active';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wechatId, title, awards, predictCount', 'required'),
			array('wechatId, ispaward, predictCount, isCode, isSensitive, status, times', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>12),
			array('title, unstartMsg, endMsg, pauseMsg', 'length', 'max'=>255),
			array('awards', 'length', 'max'=>1000),
			array('startTime, endTime, desc', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, wechatId, type, title, awards, ispaward, predictCount, isCode, startTime, endTime, created_at, unstartMsg, endMsg, pauseMsg, isSensitive, status, times, desc', 'safe', 'on'=>'search'),
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
			'active_keywords'=>array(self::HAS_MANY, 'KeywordsModel', 'responseId')
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
			'type' => '活动类型',
			'title' => 'Title',
			'awards' => '奖项',
			'ispaward' => '是否参与奖',
			'predictCount' => '活动预测参加人数',
			'isCode' => '奖项中是否有虚拟奖品',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
			'created_at' => '创建时间',
			'unstartMsg' => '未开始提示',
			'endMsg' => '结束提示',
			'pauseMsg' => '暂停提示',
			'isSensitive' => '是否区分正混版',
			'status' => '是否开启',
			'times' => '每天可参加次数',
			'desc' => '活动简介',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('wechatId',$this->wechatId);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('awards',$this->awards,true);
		$criteria->compare('ispaward',$this->ispaward);
		$criteria->compare('predictCount',$this->predictCount);
		$criteria->compare('isCode',$this->isCode);
		$criteria->compare('startTime',$this->startTime,true);
		$criteria->compare('endTime',$this->endTime,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('unstartMsg',$this->unstartMsg,true);
		$criteria->compare('endMsg',$this->endMsg,true);
		$criteria->compare('pauseMsg',$this->pauseMsg,true);
		$criteria->compare('isSensitive',$this->isSensitive);
		$criteria->compare('status',$this->status);
		$criteria->compare('times',$this->times);
		$criteria->compare('desc',$this->desc,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActiveModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
