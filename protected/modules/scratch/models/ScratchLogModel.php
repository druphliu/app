<?php

/**
 * This is the model class for table "scratch_log".
 *
 * The followings are the available columns in table 'scratch_log':
 * @property integer $id
 * @property string $openId
 * @property integer $scratchId
 * @property integer $datetime
 */
class ScratchLogModel extends CActiveRecord
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
    public static function model($table_name)
    {
        self::$tableName = $table_name ;

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
			array('openId, scratchId, datetime', 'required'),
			array('scratchId, datetime', 'numerical', 'integerOnly'=>true),
			array('openId', 'length', 'max'=>28),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, openId, scratchId, datetime', 'safe', 'on'=>'search'),
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
			'scratchId' => 'Scratch',
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
		$criteria->compare('scratchId',$this->scratchId);
		$criteria->compare('datetime',$this->datetime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}