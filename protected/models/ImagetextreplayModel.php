<?php

/**
 * This is the model class for table "imagetextreplay".
 *
 * The followings are the available columns in table 'imagetextreplay':
 * @property integer $id
 * @property string $type
 * @property string $keywords
 * @property integer $isAccurate
 * @property string $title
 * @property string $imgUrl
 * @property string $description
 * @property integer $wechatId
 * @property string $url
 */
class ImagetextreplayModel extends CActiveRecord
{
    const IMAGE_TEXT_REPLAY_TYPE = 'image-text';

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ImagetextreplayModel the static model class
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
		return 'imagetextreplay';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('isAccurate, wechatId', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>9),
			array('keywords, title', 'length', 'max'=>100),
			array('imgUrl, description, url', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type, keywords, isAccurate, title, imgUrl, description, wechatId, url', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'keywords' => 'Keywords',
			'isAccurate' => 'Is Accurate',
			'title' => 'Title',
			'imgUrl' => 'Img Url',
			'description' => 'Description',
			'wechatId' => 'Wechat',
			'url' => 'Url',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('isAccurate',$this->isAccurate);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('imgUrl',$this->imgUrl,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('wechatId',$this->wechatId);
		$criteria->compare('url',$this->url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}