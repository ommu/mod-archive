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

\ommu\archive\assets\ArchiveTree::register($this);

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => !$parent || $parent->isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Inventory'), 'url' => !$parent || $parent->isFond ? ['fond/index']: ['admin/index']];
if ($parent) {
    $parentTitle = Yii::t('app', '{level-name} {code}', ['level-name' => $parent->levelTitle->message, 'code' => $parent->code]);
    if ($parent->isFond == true) {
        $parentTitle = $parent->code;
    }
	$this->params['breadcrumbs'][] = ['label' => $parentTitle, 'url' => [($parent->isFond ? 'fond' : 'admin').'/view', 'id' => $parent->id]];
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Childs'), 'url' => [($parent->isFond ? 'fond' : 'admin').'/manage', 'parent' => $parent->id]];
}
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

if ($parent) {
	$treeDataUrl = Url::to(['data', 'id' => $parent->id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$parent->id';
JS;
	$this->registerJs($js, \yii\web\View::POS_HEAD);
} ?>

<div class="archives-create">

<?php
$aciTree = !Yii::$app->request->isAjax && $parent ? '<div id="tree" class="aciTree mb-4"></div>' : '';

echo $this->render('_form', [
	'model' => $model,
	'setting' => $setting,
	'parent' => $parent,
	'referenceCode' => $parent->referenceCode,
	'isFond' => $isFond,
    'aciTree' => $aciTree,
]); ?>

</div>
