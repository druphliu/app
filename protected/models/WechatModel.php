<?php

/**
 * This is the model class for table "wechat".
 *
 * The followings are the available columns in table 'wechat':
 * @property integer $id
 * @property integer $uid
 * @property string $type
 * @property string $name
 * @property string $originalId
 * @property string $wechatAccount
 * @property integer $area
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $token
 * @property string $apiUrl
 */
class WechatModel extends CActiveRecord
{
    const TYPE_BUS = 'BUS';
    const TYPE_PERS = 'PERS';
    public static $typeSelect = array(self::TYPE_BUS => '企业', self::TYPE_PERS => '个人');

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return WechatModel the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'wechat';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('uid, originalId, name, wechatAccount', 'required'),
            array('uid, area, created_at, updated_at', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 4),
            array('name', 'length', 'max' => 40),
            array('originalId', 'length', 'max' => 15),
            array('wechatAccount', 'length', 'max' => 20),
            array('token', 'length', 'max' => 32),
            array('apiUrl', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, uid, type, name, originalId, wechatAccount, area, created_at, updated_at, token, apiUrl', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'uid' => 'Uid',
            'type' => '类型',
            'name' => '公众号名称',
            'originalId' => '公众号原始ID',
            'wechatAccount' => '微信号',
            'area' => '地区',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'token' => 'Token',
            'apiUrl' => 'Api Url',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('uid', $this->uid);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('originalId', $this->originalId, true);
        $criteria->compare('wechatAccount', $this->wechatAccount, true);
        $criteria->compare('area', $this->area);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('updated_at', $this->updated_at);
        $criteria->compare('token', $this->token, true);
        $criteria->compare('apiUrl', $this->apiUrl, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}