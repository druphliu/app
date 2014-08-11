<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-29
 * Time: 上午10:03
 */
class ActiveRecordLogableBehavior extends CActiveRecordBehavior
{
    private $_oldattributes = array();

    /**
     * @param $event
     */
    public function afterSave($event)
    {
        //非新记录，即非插入
        if (!$this->Owner->isNewRecord) {

            $newattributes = $this->Owner->getAttributes(); //获得AR类中已修改的各字段值
            $oldattributes = $this->getOldAttributes(); //之前的旧数据

            //比较新旧数据
            foreach ($newattributes as $name => $value) {
                if (!empty($oldattributes)) {
                    $old = $oldattributes[$name];
                } else {
                    $old = '';
                }

                //如果该字段旧数据与新数据不一样，则进行记录
                if ($value != $old) {
                    //$changes = $name . ' ('.$old.') => ('.$value.'), ';

                    $log = new ActiveRecordLog; //实例log对象
                    $log->description = Yii::t('admin/activeLog', "User {username} changed {name} for {className} [{id}]",
                        array('username' => Yii::app()->user->Name, 'name' => $name,
                            'className' => get_class($this->Owner), 'id' => $this->Owner->getPrimaryKey()));
                    $log->action = 'CHANGE'; //设置操作类型为“修改”
                    $log->model = get_class($this->Owner);
                    $log->idModel = $this->Owner->getPrimaryKey(); //获得修改的记录的主键
                    $log->field = $name; //修改的字段名
                    $log->created_at = new CDbExpression('NOW()'); //日志生成时间
                    $log->username = Yii::app()->user->id; //记录用户id
                    $log->save(); //保存日至到数据库
                }
            }
        } else { //新纪录直接保存操作日志入库
            $log = new ActiveRecordLog;
            $log->description = Yii::t('admin/activeLog', 'User {username} created {className}[{id}]',
                array('username' => Yii::app()->user->Name, 'className' => get_class($this->Owner), 'id' => $this->Owner->getPrimaryKey()));
            $log->action = 'CREATE';
            $log->model = get_class($this->Owner);
            $log->idModel = $this->Owner->getPrimaryKey();
            $log->field = '';
            $log->created_at = new CDbExpression('NOW()');
            $log->username = Yii::app()->user->id;
            $log->save();
        }
    }

    /**
     * @param $event
     */
    public function afterDelete($event)
    {
        $log = new ActiveRecordLog;
        $log->description =  Yii::t('admin/activeLog', 'User {username} deleted {className}[{id}]',
        array('username'=>Yii::app()->user->Name,'className'=>get_class($this->Owner),'id'=> $this->Owner->getPrimaryKey()));
        $log->action = 'DELETE';
        $log->model = get_class($this->Owner);
        $log->idModel = $this->Owner->getPrimaryKey();
        $log->field = '';
        $log->created_at = new CDbExpression('NOW()');
        $log->username = Yii::app()->user->id;
        $log->save();
    }

    public function afterFind($event)
    {
        //保存查询出来的数据
        $this->setOldAttributes($this->Owner->getAttributes());
    }

    public function getOldAttributes()
    {
        return $this->_oldattributes;
    }

    public function setOldAttributes($value)
    {
        $this->_oldattributes = $value;
    }
}