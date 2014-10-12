<?php

/**
 * This is the model class for table "gift".
 *
 * The followings are the available columns in table 'gift':
 * @property integer $id
 * @property string $title
 * @property string $type
 * @property string $created_at
 */
class GiftModel extends CActiveRecord
{
    const TYPE_KEYWORDS = 'keywords';
    const TYPE_MENU = 'menu';
    const GIFT_TYPE = 'gift';

    public static $typeArray = array(self::TYPE_KEYWORDS=>'关键词',self::TYPE_MENU=>'菜单');

    public $action;
    public $keyword;
    public $isAccurate;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return GiftModel the static model class
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
		return 'gift';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('title', 'length', 'max'=>150),
			array('type', 'length', 'max'=>8),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, type, created_at', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'type' => 'Type',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}