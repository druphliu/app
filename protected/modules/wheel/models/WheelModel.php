<?php

/**
 * This is the model class for table "wheel".
 *
 * The followings are the available columns in table 'wheel':
 * @property integer $id
 * @property integer $wechatId
 * @property string $type
 * @property string $title
 * @property string $backgroundPic
 * @property string $button
 * @property string $awards
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
class WheelModel extends CActiveRecord
{
	public $keywords;
	public $isAccurate;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WheelModel the static model class
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
		return 'wheel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wechatId, type, title, backgroundPic, button, awards, created_at', 'required'),
			array('wechatId, isSensitive, status, times', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>8),
			array('title, backgroundPic, button, unstartMsg, endMsg, pauseMsg', 'length', 'max'=>255),
			array('awards', 'length', 'max'=>1000),
			array('startTime, endTime, desc', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, wechatId, type, title, backgroundPic, button, awards, startTime, endTime, created_at, unstartMsg, endMsg, pauseMsg, isSensitive, status, times, desc', 'safe', 'on'=>'search'),
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
            'wheel_keywords' => array(self::HAS_MANY, 'KeywordsModel', 'responseId'),
            'wheel_menuaction' => array(self::HAS_MANY, 'MenuactionModel','responseId','with'=>'action_menu'),
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
			'type' => 'Type',
			'title' => 'Title',
			'backgroundPic' => 'Background Pic',
			'button' => 'Button',
			'awards' => 'Awards',
			'startTime' => 'Start Time',
			'endTime' => 'End Time',
			'created_at' => 'Created At',
			'unstartMsg' => 'Unstart Msg',
			'endMsg' => 'End Msg',
			'pauseMsg' => 'Pause Msg',
			'isSensitive' => 'isSensitive',
			'status' => 'Status',
			'times' => 'Times',
			'desc' => 'Desc',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('backgroundPic',$this->backgroundPic,true);
		$criteria->compare('button',$this->button,true);
		$criteria->compare('awards',$this->awards,true);
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
}