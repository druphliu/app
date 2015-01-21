<?php

class EggController extends WechatManagerController
{
	public function actionIndex()
	{
		$type = Yii::app()->request->getParam('type');
		$type = $type ? $type : Globals::TYPE_KEYWORDS;
		switch ($type) {
			case Globals::TYPE_KEYWORDS:
				$with = array('active_keywords');
				$whereType = "and t.type='" . Globals::TYPE_KEYWORDS . "' and t.activeType='".Globals::TYPE_EGG."'
                and active_keywords.type='" . Globals::TYPE_SCRATCH . "'";
				break;
			case Globals::TYPE_MENU:
				$with = array('active_menuaction');
				$whereType = "and t.type='" . Globals::TYPE_MENU . "' and t.activeType='".Globals::TYPE_EGG."'";
				break;
		}
		$this->layout = '//layouts/memberList';
		$dataProvider = new CActiveDataProvider('ActiveModel', array(
			'criteria' => array(
				'order' => 't.id DESC',
				'with' => $with,
				'condition' => "t.wechatId = {$this->wechatInfo->id} $whereType",
				'together' => true
			),
			//'pagination' => false,
			'pagination' => array(
				'pageSize' => Page::SIZE,
				'pageVar' => 'page'
			),
		));
		$this->render('index', array('data' => $dataProvider->getData(), 'pages' => $dataProvider->getPagination(),
			'type' => $type, 'wechatInfo' => $this->wechatInfo));
	}

	public function actionCreate()
	{
		$menuList = array();
		$type = Yii::app()->request->getParam('type');
		$type = $type ? $type : Globals::TYPE_KEYWORDS;
		if ($type == Globals::TYPE_MENU) {
			//取menu的下拉列表
			$menuList = MenuModel::model()->getMenuDropDownList($this->wechatInfo->id, Globals::TYPE_GIFT);
		}
		$model = new ActiveModel();
		if (isset($_POST['ActiveModel'])) {
			$model->type = $type;
			$model->attributes = $_POST['ActiveModel'];
			$model->wechatId = $this->wechatInfo->id;
			//奖项处理
			for ($i = 1; $i <= 3; $i++) {
				${'award' . $i} = $_POST['award' . $i];
				${'isentity' . $i} = $_POST['isentity' . $i] ? $_POST['isentity' . $i] : 0;
				$awards[$i] = array('name' => ${'award' . $i}, 'isentity' => ${'isentity' . $i});
			}
			$model->awards = serialize($awards);
			$model->activeType = Globals::TYPE_EGG;
			if ($model->validate()) {
				$model->save();
				switch ($type) {
					case Globals::TYPE_KEYWORDS:
						$keywords = $_POST['ActiveModel']['keywords'];
						$isAccurate = $_POST['ActiveModel']['isAccurate'];
						$keywordsArray = explode(',', $keywords);
						foreach ($keywordsArray as $k) {
							$keywordsModel = new KeywordsModel();
							$keywordsModel->responseId = $model->id;
							$keywordsModel->type = Globals::TYPE_SCRATCH;
							$keywordsModel->isAccurate = $isAccurate;
							$keywordsModel->name = $k;
							$keywordsModel->wechatId = $this->wechatInfo->id;
							$keywordsModel->save();
						}
						break;
					case Globals::TYPE_MENU:
						$menuId = $_POST['ActiveModel']['action'];
						$menuActionModel = MenuactionModel::model()->find('menuId=:menuId', array(':menuId' => $menuId));
						$menuActionModel->responseId = $model->id;
						$menuActionModel->save();
						break;
				}
				ShowMessage::success('添加成功', Yii::app()->createUrl('egg', array('type' => $type)));
			}
		}
		Yii::app()->clientScript->scriptMap['jquery.js'] = false;
		$this->render('create', array('model' => $model, 'type' => $type ? $type : GiftModel::TYPE_KEYWORDS,
			'wechatId' => $this->wechatInfo->id, 'responseId' => 0, 'menuList' => $menuList));
	}
}