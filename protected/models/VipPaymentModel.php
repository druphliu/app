<?php

/**
 * This is the model class for table "vippayment".
 *
 * The followings are the available columns in table 'vippayment':
 * @property integer $id
 * @property integer $uid
 * @property string $username
 * @property string $orderId
 * @property string $amount
 * @property integer $count
 * @property integer $stauts
 * @property string $payCode
 * @property integer $created_at
 */
class VipPaymentModel extends CActiveRecord
{
    const STATUS_READY = 0;
    const STATUS_VERIFIED = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILED = -1;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return VipPaymentModel the static model class
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
		return 'vippayment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, amount', 'required'),
			array('uid, count, stauts, created_at', 'numerical', 'integerOnly'=>true),
			array('payCode', 'length', 'max'=>15),
            array('orderId', 'length', 'max'=>16),
			array('amount', 'length', 'max'=>5),
            array('username', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, uid, username, orderId, amount, count, stauts, payCode, created_at', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
            'username' => 'Username',
			'orderId' => 'Order',
			'amount' => '充值金额',
			'count' => '充值选项',
			'stauts' => 'Stauts',
			'payCode' => 'Pay Code',
			'created_at' => 'Created At',
            'paymentOption'=>'充值选项'
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
		$criteria->compare('uid',$this->uid);
        $criteria->compare('username',$this->username,true);
		$criteria->compare('orderId',$this->orderId,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('stauts',$this->stauts);
		$criteria->compare('payCode',$this->payCode,true);
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}