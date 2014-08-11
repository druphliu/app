<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-7-28
 * Time: 下午1:15
 */
class Page
{
    const SIZE = 15;
    static function go($pages)
    {
        return array(
            'header' => '',
            'firstPageLabel' => '<<',
            'lastPageLabel' => '>>',
            'firstPageCssClass' => '',
            'lastPageCssClass' => '',
            'maxButtonCount' => 8,
            'nextPageCssClass' => '',
            'previousPageCssClass' => '',
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'selectedPageCssClass' => 'active',
            'pages' => $pages,
            'internalPageCssClass' => '',
            'hiddenPageCssClass' => 'disabled',
            'cssFile' => false,
            'htmlOptions' => array(
                'class' => 'pagination'
            ),
        );
    }
}