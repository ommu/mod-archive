<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Url;

\ommu\archive\assets\ArchiveTree::register($this);

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => $isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Inventory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $isFond ? $model->code : Yii::t('app', '#{level-name} {code}', ['level-name' => strtoupper($model->levelTitle->message), 'code' => $model->code]), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

if (!$isFond) {
	$treeDataUrl = Url::to(['data', 'id' => $model->parent_id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->parent_id';
JS;
	$this->registerJs($js, \yii\web\View::POS_HEAD);
}
if (!in_array('location', $model->level->field))
	unset($this->params['menu']['content']['location']);
?>

<div class="archives-update">

<?php
$aciTree = !Yii::$app->request->isAjax && !$isFond ? '<div id="tree" class="aciTree mb-4"></div>' : '';

echo $this->render('_form', [
	'model' => $model,
	'setting' => $setting,
	'parent' => $parent,
	'referenceCode' => $model->referenceCode,
	'isFond' => $isFond,
    'aciTree' => $aciTree,
]); ?>

</div>