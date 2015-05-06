<?php

/**
 * This is the model class for table "active_awards_info".
 *
 * The followings are the available columns in table 'active_awards_info':
 * @property integer $id
 * @property integer $awardId
 * @property string $username
 * @property string $tel
 * @property string $service
 * @property string $roleName
 * @property integer $type
 */
class ActiveAwardsInfoModel extends CActiveRecord
{
    const CREATE_TABLE_NAME = '{{active_awards_info_%d}}';
    const CREATE_TABLE_SQL = "CREATE TABLE `%s` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `awardId` int(10) NOT NULL,
  `username` varchar(30) DEFAULT NULL COMMENT '真实名字',
  `tel` varchar(11) DEFAULT NULL COMMENT '联系电话',
  `service` varchar(20) DEFAULT NULL COMMENT '所在区',
  `roleName` varchar(30) DEFAULT NULL COMMENT '角色名字',
  `type` tinyint(2) NOT NULL COMMENT '平台:wp android ios',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='实物中奖登记信息表';";
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
			array('awardId, type', 'required'),
			array('awardId, type', 'numerical', 'integerOnly'=>true),
			array('username, roleName', 'length', 'max'=>30),
			array('tel', 'length', 'max'=>11),
			array('service', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, awardId, username, tel, service, roleName, type', 'safe', 'on'=>'search'),
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
			'awardId' => 'Award',
			'username' => '真实名字',
			'tel' => '联系电话',
			'service' => '所在区',
			'roleName' => '角色名字',
			'type' => '平台:wp android ios',
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
		$criteria->compare('awardId',$this->awardId);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('service',$this->service,true);
		$criteria->compare('roleName',$this->roleName,true);
		$criteria->compare('type',$this->type);

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
