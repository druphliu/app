<?php

/**
 * bootstrap 公共ui元素
 * User: Administrator
 * Date: 14-7-29
 * Time: 下午2:03
 */
class BootStrapUI
{
    const alertError = '<button data-dismiss="alert" class="close" type="button"><i class="icon-remove"></i></button>
							<strong><i class="icon-remove"></i>请更正以下错误:</strong>';
    const alertErrorClass = 'alert alert-danger';



    //------------------------------------------------form-------------------------------------------------------------------//
    const formLabelClass='col-sm-3 control-label no-padding-right';
    static function saveButton()
    {
        return CHtml::htmlButton("<i class='icon-ok bigger-110'></i>保存", array('class' => 'btn btn-info', 'type' => 'submit'));
    }

    static function resetButton()
    {
        return CHtml::htmlButton("<i class='icon-undo bigger-110'></i>重置", array('class' => 'btn', 'type' => 'reset'));
    }
}