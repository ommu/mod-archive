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

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'SIKS'), 'url' => ['/archive/fond/index']];
$this->params['breadcrumbs'][] = ['label' => $isFond ? Yii::t('app', 'Fond') : Yii::t('app', 'Inventory'), 'url' => ['index']];
if($parent) {
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{level-name} {code}', ['level-name'=>$parent->level->level_name_i, 'code'=>$parent->code]), 'url' => ['view', 'id'=>$parent->id]];
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Childs'), 'url' => ['manage', 'parent'=>$parent->id]];
}
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

if($parent) {
	$this->params['menu']['content'] = [
		['label' => Yii::t('app', 'Show Reference Code'), 'url' => 'javascript:void(0);', 'icon' => 'code', 'htmlOptions' => ['class'=>'btn btn-warning', 'id'=>'reference-code']],
	];

	$treeDataUrl = Url::to(['data', 'id'=>$parent->id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$parent->id';
JS;
	$this->registerJs($js, \yii\web\View::POS_HEAD);
}
?>

<div class="archives-create">

<?php
echo !Yii::$app->request->isAjax && $parent ? '<div id="tree" class="aciTree hide mb-4"></div>' : '';

echo $this->render('_form', [
	'model' => $model,
	'setting' => $setting,
	'parent' => $parent,
	'referenceCode' => $parent->referenceCode,
	'isFond' => $isFond,
]); ?>

</div>
