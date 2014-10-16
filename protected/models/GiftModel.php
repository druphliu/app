<?php

/**
 * This is the model class for table "gift".
 *
 * The followings are the available columns in table 'gift':
 * @property integer $id
 * @property string $title
 * @property string $type
 * @property integer $wechatId
 * @property string $template
 * @property string $created_at
 * @property integer $status
 * @property string $unstartMsg
 * @property string $endMsg
 * @property string $codeOverMsg
 * @property string $pauseMsg
 * @property string $startTime
 * @property string $endTime
 */
class GiftModel extends CActiveRecord
{
    const TYPE_KEYWORDS = 'keywords';
    const TYPE_MENU = 'menu';
    const GIFT_TYPE = 'gift';

    public static $typeArray = array(self::TYPE_KEYWORDS=>'关键词',self::TYPE_MENU=>'菜单');

    public $action;
    public $keywords;
    public $isAccurate;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GiftModel the static model class
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
		return 'gift';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, wechatId', 'required'),
			array('wechatId, status', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>150),
			array('type', 'length', 'max'=>8),
			array('template, unstartMsg, endMsg, codeOverMsg, pauseMsg', 'length', 'max'=>255),
			array('startTime, endTime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, type, wechatId, template, created_at, status, unstartMsg, endMsg, codeOverMsg, pauseMsg, startTime, endTime', 'safe', 'on'=>'search'),
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
			'title' => '礼包名称',
			'type' => '领取方式',
			'wechatId' => 'Wechat',
			'template' => '回复模版',
			'created_at' => 'Created At',
			'status' => 'Status',
			'unstartMsg' => '未开始回复',
			'endMsg' => '结束回复',
			'codeOverMsg' => '领取完毕回复',
			'pauseMsg' => '活动暂停回复',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
            'keywords'=>'关键词',
            'isAccurate'=>'是否精准匹配',
            'action'=>'菜单值'
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('wechatId',$this->wechatId);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('unstartMsg',$this->unstartMsg,true);
		$criteria->compare('endMsg',$this->endMsg,true);
		$criteria->compare('codeOverMsg',$this->codeOverMsg,true);
		$criteria->compare('pauseMsg',$this->pauseMsg,true);
		$criteria->compare('startTime',$this->startTime,true);
		$criteria->compare('endTime',$this->endTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}