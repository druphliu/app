<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'member':
 * @property integer $uid
 * @property string $username
 * @property string $nickname
 * @property string $pswd
 * @property integer $group_id
 */
class UserModel extends CActiveRecord
{
    public $repswd;
    public $newPswd;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username,group_id', 'required'),
            array('pswd','required','on'=>'create'),
            array('username','unique'),
			array('group_id', 'numerical', 'integerOnly'=>true),
			array('username, nickname', 'length', 'max'=>50),
            array('pswd, repswd', 'length', 'max' => 32),
            array('pswd', 'compare', 'compareAttribute'=>'repswd' ,'on'=>'create',"message"=>"两次密码不一致"),
            array('newPswd', 'compare', 'compareAttribute'=>'repswd' ,'on'=>'update',"message"=>"两次密码不一致"),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uid, username, nickname, pswd, group_id', 'safe', 'on'=>'search'),
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
			'pswd' => '密码',
            'newPswd'=>'密码',
            'repswd' => '重复密码',
			'group_id' => '用户组',
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
		$criteria->compare('group_id',$this->group_id);

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

    public function behaviors()
    {
        return array(
            // 行为类名 => 类文件别名路径
            'ActiveRecordLogableBehavior'=>
                'backend.behaviors.ActiveRecordLogableBehavior',
        );
    }
}
