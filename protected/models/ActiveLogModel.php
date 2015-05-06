<?php

/**
 * This is the model class for table "active_log".
 *
 * The followings are the available columns in table 'active_log':
 * @property integer $id
 * @property string $openId
 * @property integer $activeId
 * @property integer $datetime
 */
class ActiveLogModel extends CActiveRecord
{
    const CREATE_TABLE_NAME = '{{active_log_%d}}';
    const CREATE_TABLE_SQL = "CREATE TABLE `%s` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `openId` char(28) NOT NULL,
  `activeId` int(10) NOT NULL COMMENT '活动Id',
  `datetime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=362 DEFAULT CHARSET=utf8;";
    const TABLE_CREATE_OK = 1;
    const TABLE_CREATE_FAILED = -1;
    const TABLE_HAS_EXIST =3;

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
			array('openId, activeId, datetime', 'required'),
			array('activeId, datetime', 'numerical', 'integerOnly'=>true),
			array('openId', 'length', 'max'=>28),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, openId, activeId, datetime', 'safe', 'on'=>'search'),
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
			'openId' => 'Open',
			'activeId' => 'Active',
			'datetime' => 'Datetime',
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
		$criteria->compare('openId',$this->openId,true);
		$criteria->compare('activeId',$this->activeId);
		$criteria->compare('datetime',$this->datetime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function createTable($wechatId)
    {
        $tableName = sprintf(self::CREATE_TABLE_NAME, $wechatId);
        $sql = sprintf(self::CREATE_TABLE_SQL, $tableName);
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

    public function getTableName($wechatId){
        return sprintf(self::CREATE_TABLE_NAME, $wechatId);;
    }
}