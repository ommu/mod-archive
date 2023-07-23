<?php
/**
 * Archive Restorations (archive-restoration)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\restoration\AdminController
 * @var $model ommu\archive\models\ArchiveRestoration
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:22 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Url;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restoration'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->archive->code, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back to Detail'), 'url' => Url::to(['view', 'id' => $model->id]), 'icon' => 'eye', 'htmlOptions' => ['class' => 'btn btn-info']],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
];
?>

<div class="archive-restoration-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>