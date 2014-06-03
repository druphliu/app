<?php

/**
 * This is the model class for table "{{admin_user}}".
 *
 * The followings are the available columns in table '{{admin_user}}':
 * @property integer $id
 * @property string $name
 * @property string $pswd
 * @property integer $group_id
 * @property integer $created_at
 * @property integer $logined_at
 *
 * The followings are the available model relations:
 * @property AdminGroup $group
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{admin_user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, pswd, group_id, created_at, logined_at', 'required'),
			array('group_id, created_at, logined_at', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('pswd', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, pswd, group_id, created_at, logined_at', 'safe', 'on'=>'search'),
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
			'group' => array(self::BELONGS_TO, 'AdminGroup', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'pswd' => 'Pswd',
			'group_id' => 'Group',
			'created_at' => 'Created At',
			'logined_at' => 'Logined At',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pswd',$this->pswd,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('logined_at',$this->logined_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
