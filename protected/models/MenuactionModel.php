<?php

/**
 * This is the model class for table "menuaction".
 *
 * The followings are the available columns in table 'menuaction':
 * @property integer $id
 * @property integer $menuId
 * @property string $action
 * @property integer $responseId
 */
class MenuactionModel extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuactionModel the static model class
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
		return 'menuaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('menuId, responseId', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, menuId, action, responseId', 'safe', 'on'=>'search'),
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
            'action_menu'=>array(self::BELONGS_TO,'MenuModel','menuId')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'menuId' => 'Menu',
			'action' => 'Action',
			'responseId' => 'Response',
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
		$criteria->compare('menuId',$this->menuId);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('responseId',$this->responseId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    public function getTree($wechatId){
        $sql = "select m.name,m.id,m.type,m.parentId,a.action,a.responseId,a.id as actionId from " . MenuModel::model()->tableName() . " m left join ".
            MenuactionModel::model()->tableName()." a on m.id=a.menuId  where m.wechatId=" . $wechatId;
        $command = Yii::app()->db->createCommand($sql);
        $data = $command->queryAll();
        $tree = new Tree($data);
        return $tree->get_tree_list();
    }
}