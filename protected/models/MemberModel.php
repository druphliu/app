<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $uid
 * @property string $username
 * @property string $nickname
 * @property string $pswd
 * @property integer $lv
 * @property integer $created_at
 * @property integer $updated_at
 */
class MemberModel extends CActiveRecord
{
    public $repswd;
    public $newpswd;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('lv', 'required'),
			array('lv, created_at, updated_at', 'numerical', 'integerOnly'=>true),
            array('username','unique'),
			array('username', 'length', 'max'=>40),
			array('nickname', 'length', 'max'=>100),
			array('pswd,newpswd,repswd', 'length', 'max'=>32),
            array('newpswd', 'compare', 'compareAttribute'=>'repswd' ,'on'=>'pswd,update,create',"message"=>"两次密码不一致"),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uid, username, nickname, pswd, lv, created_at, updated_at', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
			'username' => '用户名',
			'nickname' => '昵称',
			'newpswd' => '新密码',
            'repswd' => '重复新密码',
			'lv' => 'Lv',
			'created_at' => 'Created At',
			'updated_at' => 'Updated At',
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

		$criteria->compare('uid',$this->uid);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('pswd',$this->pswd,true);
		$criteria->compare('lv',$this->lv);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('updated_at',$this->updated_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
