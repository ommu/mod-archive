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
use yii\helpers\ArrayHelper;

\ommu\archive\assets\AciTreeAsset::register($this);

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => $isFond ? Yii::t('app', 'Fond') : Yii::t('app', 'Inventory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $isFond ? $model->code : Yii::t('app', '{level-name} {code}', ['level-name' => $model->level->level_name_i, 'code' => $model->code]), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

if (!$isFond) {
	$this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Show Reference Code'), 'url' => 'javascript:void(0);', 'icon' => 'code', 'htmlOptions' => ['class' => 'btn btn-warning', 'id' => 'reference-code']],
    ];

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
echo !Yii::$app->request->isAjax && !$isFond ? '<div id="tree" class="aciTree hide mb-4"></div>' : '';

echo $this->render('_form', [
	'model' => $model,
	'setting' => $setting,
	'referenceCode' => $model->referenceCode,
	'isFond' => $isFond,
]); ?>

</div>