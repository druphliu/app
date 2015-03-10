<?php

/**
 * This is the model class for table "active_awards".
 *
 * The followings are the available columns in table 'active_awards':
 * @property integer $id
 * @property integer $activeId
 * @property integer $grade
 * @property string $code
 * @property integer $isentity
 * @property string $openId
 * @property integer $type
 * @property integer $datetime
 * @property integer $status
 * @property string $awardsInfo
 */
class ActiveAwardsModel extends CActiveRecord
{
	private static $tableName ;

	public function __construct($table_name = '') {

		if($table_name === null) {
			parent::__construct(null);
		} else {
			self::$tableName = $table_name ;
			parent::__construct();
		}

	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GiftCodeModel the static model class
	 */
	public static function model($tableName = false, $className = __CLASS__)
	{
		self::$tableName = $tableName ;

		return parent::model(__CLASS__);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return self::$tableName;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('activeId, code', 'required'),
			array('activeId, grade, isentity, type, datetime, status', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>150),
			array('openId', 'length', 'max'=>28),
			array('awardsInfo', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, activeId, grade, code, isentity, openId, type, datetime, status, awardsInfo', 'safe', 'on'=>'search'),
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
			'activeId' => 'Active',
			'grade' => '中奖等级',
			'code' => '中奖码或者中奖详情(实物中奖即为奖品详情，礼包码类即为码值)',
			'isentity' => '是否实物',
			'openId' => 'Open',
			'type' => '类型(用于礼包码区分正版和越狱),1:正版,2:越狱',
			'datetime' => '中奖时间',
			'status' => '状态：0未中奖，1中奖未确认，2成功领取',
			'awardsInfo' => '中奖信息',
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
		$criteria->compare('activeId',$this->activeId);
		$criteria->compare('grade',$this->grade);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('isentity',$this->isentity);
		$criteria->compare('openId',$this->openId,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('datetime',$this->datetime);
		$criteria->compare('status',$this->status);
		$criteria->compare('awardsInfo',$this->awardsInfo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}