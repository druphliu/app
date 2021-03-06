<?php

/**
 *
 * @property integer $id
 * @property integer $giftId
 * @property string $code
 * @property string $openId
 * @property string $created_at
 */
class GiftCodeModel extends CActiveRecord
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
	public static function model($table_name = false, $className = __CLASS__)
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
			array('giftId, code', 'required'),
			array('giftId', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>20),
			array('openId', 'length', 'max'=>28),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, giftId, code, openId, created_at', 'safe', 'on'=>'search'),
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
			'giftId' => 'Gift',
			'code' => 'Code',
			'openId' => 'Open',
			'created_at' => 'Created At',
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
		$criteria->compare('giftId',$this->giftId);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('openId',$this->openId,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}