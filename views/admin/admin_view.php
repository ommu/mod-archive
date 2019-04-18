<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\archive\models\Archives;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back to Inventaris'), 'url' => Url::to(['index']), 'icon' => 'tasks', 'htmlOptions' => ['class'=>'btn btn-success btn-sm']],
];
?>

<div class="archives-view">

<?php 
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id,
	],
	[
		'attribute' => 'publish',
		'value' => Archives::getPublish($model->publish),
	],
	[
		'attribute' => 'sidkkas',
		'value' => $model->filterYesNo($model->sidkkas),
	],
	[
		'attribute' => 'parent_id',
		'value' => Archives::parseParent($model),
		'format' => 'html',
		'visible' => $model->level_id != 1 ? true : false,
	],
	[
		'attribute' => 'code',
		'value' => $model->code,
	],
	[
		'attribute' => 'title',
		'value' => $model->title ? $model->title : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'levelName',
		'value' => function ($model) {
			$levelName = isset($model->level) ? $model->level->title->message : '-';
			if($levelName != '-')
				return Html::a($levelName, ['setting/level/view', 'id'=>$model->level_id], ['title'=>$levelName, 'class'=>'modal-btn']);
			return $levelName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'medium',
		'value' => $model->medium ? $model->medium : '-',
	],
	[
		'attribute' => 'creator',
		'value' => function ($model) {
			return Archives::parseRelated($model->getRelatedCreator(true, 'title'), 'creator');
		},
		'format' => 'html',
	],
	[
		'attribute' => 'repository',
		'value' => function ($model) {
			return Archives::parseRelated($model->getRelatedRepository(true, 'title'), 'repository');
		},
		'format' => 'html',
	],
	[
		'attribute' => 'media',
		'value' => function ($model) {
			return Archives::parseRelated($model->getRelatedMedia(true, 'title'));
		},
		'format' => 'html',
	],
	[
		'attribute' => 'image_type',
		'value' => Archives::getImageType($model->image_type ? $model->image_type : '-'),
		'visible' => in_array('image_type', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
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
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->id], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary btn-sm']),
		'format' => 'html',
		'visible' => Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>