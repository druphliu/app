<?php

/**
 * This is the model class for table "scratch".
 *
 * The followings are the available columns in table 'scratch':
 * @property integer $id
 * @property string $type
 * @property string $title
 * @property string $backgroundPic
 * @property integer $wechatId
 * @property string $button
 * @property string $awards
 * @property string $startTime
 * @property string $endTime
 * @property string $unstartMsg
 * @property string $endMsg
 * @property string $codeOverMsg
 * @property string $pauseMsg
 * @property string $created_at
 * @property integer $ispaward
 * @property integer $status
 * @property string $desc
 */
class ScratchModel extends CActiveRecord
{
    public $action;
    public $keywords;
    public $isAccurate;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ScratchModel the static model class
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
		return 'scratch';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, title,startTime', 'required'),
			array('type', 'length', 'max'=>8),
			array('title, backgroundPic, button, awards', 'length', 'max'=>255),
			array('endTime, created_at, desc', 'safe'),
            array('backgroundPic',
                'file',
                'allowEmpty'=>true,
                'maxSize'=>1024 * 1024 * 200,
                'tooLarge'=>'最大不超过200MB，请重新上传!',
            ),
            array('button',
                'file',
                'allowEmpty'=>true,
                'maxSize'=>1024 * 1024 * 200,
                'tooLarge'=>'最大不超过200MB，请重新上传!',
            ),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, title, backgroundPic, button, awards, startTime, endTime, created_at,times,desc', 'safe', 'on'=>'search'),
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
            'scratch_keywords' => array(self::HAS_MANY, 'KeywordsModel', 'responseId'),
            'scratch_menuaction' => array(self::HAS_MANY, 'MenuactionModel','responseId','with'=>'action_menu'),
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
			'title' => 'Title',
			'backgroundPic' => '背景图片',
			'button' => '刮奖区图片',
			'awards' => '奖项设置',
			'created_at' => 'Created At',
            'ispaward'=>'是否参与奖',
            'unstartMsg' => '未开始回复',
            'endMsg' => '结束回复',
            'pauseMsg' => '活动暂停回复',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'keywords' => '关键词',
            'isAccurate' => '是否精准匹配',
            'times'=>'刮奖次数',
            'desc' => '活动描述',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('banner',$this->banner,true);
		$criteria->compare('button',$this->button,true);
		$criteria->compare('awards',$this->awards,true);
		$criteria->compare('startTime',$this->startTime,true);
		$criteria->compare('endTime',$this->endTime,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}