<?php
/**
 * Archive Settings (archive-setting)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\AdminController
 * @var $model ommu\archive\models\ArchiveSetting
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 March 2019, 23:53 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\archive\models\ArchiveSetting;

$this->params['breadcrumbs'][] = Yii::t('app', 'Archive Settings');
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
				'value' => ArchiveSetting::getPermission($model->permission),
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
				'attribute' => 'fond_sidkkas',
				'value' => ArchiveSetting::getFondSidkkas($model->fond_sidkkas),
			],
			[
				'attribute' => 'maintenance_mode',
				'value' => ArchiveSetting::getFondSidkkas($model->maintenance_mode),
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