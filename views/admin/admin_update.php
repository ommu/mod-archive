<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

\ommu\archive\assets\AciTreeAsset::register($this);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inventory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model::htmlHardDecode($model->code), 'url' => ['view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back to Inventaris'), 'url' => Url::to(['index']), 'icon' => 'tasks', 'htmlOptions' => ['class'=>'btn btn-success']],
];
if(!$fond) {
	$this->params['menu']['content'] = ArrayHelper::merge(
		$this->params['menu']['content'], 
		[
			['label' => Yii::t('app', 'Show Reference Code'), 'url' => 'javascript:void(0);', 'icon' => 'code', 'htmlOptions' => ['class'=>'btn btn-warning', 'id'=>'reference-code']],
		]
	);

	$treeDataUrl = Url::to(['data', 'id'=>$model->parent_id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->parent_id';
JS;
	$this->registerJs($js, \yii\web\View::POS_HEAD);
}
?>

<div class="archives-update">

<?php
echo !Yii::$app->request->isAjax && !$fond ? '<div id="tree" class="aciTree hide mb-4"></div>' : '';

echo $this->render('_form', [
	'model' => $model,
	'setting' => $setting,
	'fond' => $fond,
	'referenceCode' => $model->referenceCode,
]); ?>

</div>