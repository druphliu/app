<?php

/**
 * This is the model class for table "openplatform".
 *
 * The followings are the available columns in table 'openplatform':
 * @property integer $id
 * @property integer $wechatId
 * @property string $name
 * @property string $token
 * @property string $apiUrl
 * @property integer $status
 * @property integer $created_at
 */
class OpenPlatformModel extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OpenPlatformModel the static model class
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
		return 'openplatform';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wechatId, name, token, apiUrl, created_at', 'required'),
			array('wechatId, status, created_at', 'numerical', 'integerOnly'=>true),
			array('name, token', 'length', 'max'=>150),
			array('apiUrl', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, wechatId, name, token, apiUrl, status, created_at', 'safe', 'on'=>'search'),
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
			'wechatId' => 'Wechat',
			'name' => '平台名称',
			'token' => 'Token',
			'apiUrl' => 'Api Url',
			'status' => 'Status',
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
		$criteria->compare('wechatId',$this->wechatId);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('apiUrl',$this->apiUrl,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}