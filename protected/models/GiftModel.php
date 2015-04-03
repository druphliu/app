<?php

/**
 * This is the model class for table "gift".
 *
 * The followings are the available columns in table 'gift':
 * @property integer $id
 * @property string $title
 * @property integer $wechatId
 * @property string $template
 * @property string $RTemplate
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
    const CREATE_CODE_TABLE_NAME = '{{giftcode_%d}}';
    const CREATE_CODE_TABLE_SQL = "CREATE TABLE `%s` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `giftId` int(10) NOT NULL,
  `code` varchar(20) NOT NULL,
  `openId` varchar(28) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=106018 DEFAULT CHARSET=utf8";
    const TABLE_CREATE_OK = 1;
    const TABLE_CREATE_FAILED = -1;
    const TABLE_HAS_EXIST =3;

    public static $typeArray = array(self::TYPE_KEYWORDS => '关键词', self::TYPE_MENU => '菜单');

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
			array('template, RTemplate, unstartMsg, endMsg, codeOverMsg, pauseMsg', 'length', 'max'=>255),
			array('startTime, endTime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, wechatId, template, RTemplate, created_at, status, unstartMsg, endMsg, codeOverMsg, pauseMsg, startTime, endTime', 'safe', 'on'=>'search'),
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
            'gift_keywords' => array(self::HAS_MANY, 'KeywordsModel', 'responseId')
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => '标题',
			'wechatId' => '微信ID',
			'template' => '回复模板',
			'RTemplate' => '再次领取回复模板',
			'created_at' => '创建时间',
			'status' => '状态',
			'unstartMsg' => '未开始回复模板',
			'endMsg' => '结束回复模板',
			'codeOverMsg' => '礼包码领取完回复模板',
			'pauseMsg' => '停止回复模板',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
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
		$criteria->compare('wechatId',$this->wechatId);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('RTemplate',$this->RTemplate,true);
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

    /**
     * 保存前过滤掉a,br标签之外标签
     * @return bool
     */
    protected function beforeSave()
    {
        if (parent::beforeSave()) {
            $this->template = strip_tags($this->template, '<a><br>');
            $this->unstartMsg = strip_tags($this->unstartMsg, '<a><br>');
            $this->codeOverMsg = strip_tags($this->codeOverMsg, '<a><br>');
            $this->endMsg = strip_tags($this->endMsg, '<a><br>');;
            $this->pauseMsg = strip_tags($this->pauseMsg, '<a><br>');
            $this->RTemplate = strip_tags($this->RTemplate, '<a><br>');
            return true;
        } else {
            return false;
        }
    }

    public function createCodeTable($wechatId)
    {
        $tableName = sprintf(self::CREATE_CODE_TABLE_NAME, $wechatId);
        $sql = sprintf(self::CREATE_CODE_TABLE_SQL, $tableName);
        if (yii::app()->db->createCommand(("SHOW TABLES LIKE '$tableName'"))->queryRow()) {
            $return = self::TABLE_HAS_EXIST;
        } else {
            $result = yii::app()->db->createCommand($sql);
            if ($result->query()) {
                $return = self::TABLE_CREATE_OK;
            } else {
                $return = self::TABLE_CREATE_FAILED;
            }
        }
        return $return;
    }

    public function getCodeTableName($wechatId){
        return sprintf(self::CREATE_CODE_TABLE_NAME, $wechatId);;
    }
}