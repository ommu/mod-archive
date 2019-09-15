<?php
/**
 * Archive Settings (archive-setting)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\AdminController
 * @var $model ommu\archive\models\ArchiveSetting
 * @var $form yii\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 March 2019, 23:53 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inventory'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="archive-setting-update">

<?php if(!$model->isNewRecord) {
	echo DetailView::widget([
		'model' => $model,
		'options' => [
			'class'=>'table table-striped detail-view',
		],
		'attributes' => [
			'id',
			'license',
			[
				'attribute' => 'permission',
				'value' => $model::getPermission($model->permission),
			],
			[
				'attribute' => 'meta_description',
				'value' => $model->meta_description ? $model->meta_description : '-',
			],
			[
				'attribute' => 'meta_keyword',
				'value' => $model->meta_keyword ? $model->meta_keyword : '-',
			],
			'reference_code_sikn',
			[
				'attribute' => 'reference_code_separator',
				'value' => '"'.$model->reference_code_separator.'"',
			],
			[
				'attribute' => 'image_type',
				'value' => $model->image_type,
			],
			[
				'attribute' => 'document_type',
				'value' => $model->document_type,
			],
			[
				'attribute' => 'fond_sidkkas',
				'value' => $model::getFondSidkkas($model->fond_sidkkas),
			],
			[
				'attribute' => 'production_date',
				'value' => Yii::$app->formatter->asDate($model->production_date, 'medium'),
			],
			[
				'attribute' => 'maintenance_mode',
				'value' => $model::getFondSidkkas($model->maintenance_mode),
			],
			[
				'attribute' => 'maintenance_document_path',
				'value' => $model->maintenance_document_path ? $model->maintenance_document_path : '-',
			],
			[
				'attribute' => 'maintenance_image_path',
				'value' => $model->maintenance_image_path ? $model->maintenance_image_path : '-',
			],
			[
				'attribute' => 'modified_date',
				'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
			],
			[
				'attribute' => 'modifiedDisplayname',
				'value' => isset($model->modified) ? $model->modified->displayname : '-',
			],
			[
				'attribute' => '',
				'value' => Html::a(Yii::t('app', 'Update'), Url::to(['update']), [
					'class' => 'btn btn-primary',
				]),
				'format' => 'html',
			],
		],
	]);
} else {
	echo $this->render('_form', [
		'model' => $model,
	]);
} ?>

</div>