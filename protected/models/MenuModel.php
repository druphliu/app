<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property integer $id
 * @property integer $wechatId
 * @property string $type
 * @property string $name
 * @property integer $parentId
 * @property integer $keywordsId
 * @property string $url
 */
class MenuModel extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MenuModel the static model class
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
		return 'menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wechatId', 'required'),
			array('wechatId, parentId, keywordsId', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>8),
			array('name', 'length', 'max'=>15),
			array('url', 'length', 'max'=>125),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, wechatId, type, name, parentId, keywordsId, url', 'safe', 'on'=>'search'),
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
			'type' => 'Type',
			'name' => 'Name',
			'parentId' => 'Parent',
			'keywordsId' => 'Keywords',
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
		$criteria->compare('wechatId',$this->wechatId);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('parentId',$this->parentId);
		$criteria->compare('keywordsId',$this->keywordsId);
		$criteria->compare('url',$this->url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function getMenuDropDownList($wechatId, $type)
	{
		$result = array();
		$menu = $this->getTree($wechatId);
		foreach ($menu as $m) {
			if ($m['child']) {
				foreach ($m['child'] as $ch) {
					if ($ch['type'] == $type) {
						$result[$ch['id']] = $ch['name'];
					}
				}
			} elseif ($m['type'] == $type) {
				$result[$m['id']] = $m['name'];
			}
		}
		return $result;
	}

	public function getTree($wechatId){
		$sql = "select m.name,m.id,m.type,m.parentId,m.url,m.keywordsId,k.name as keywordsName from " .MenuModel::model()->tableName(). " m
			  left join ".KeywordsModel::model()->tableName()." k on k.id=m.keywordsId where m.wechatId=" . $wechatId;
		$command = Yii::app()->db->createCommand($sql);
		$data = $command->queryAll();
		$tree = new Tree($data);
		return $tree->get_tree_list();
	}
}