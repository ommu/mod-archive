<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

$treeDataUrl = Url::to(['archive-tree', 'id' => $model->id]);
$js = <<<JS
    var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->id';
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);

!Yii::$app->request->isAjax ? \ommu\archive\assets\ArchiveTreeFond::register($this) : ''; ?>

<div class="archives-view">
    <div id="tree" class="aciTree"></div>
</div>