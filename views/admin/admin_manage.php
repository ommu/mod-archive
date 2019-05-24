<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 * @var $searchModel ommu\archive\models\search\Archives
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
use app\components\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use ommu\archive\models\ArchiveLevel;

$this->params['breadcrumbs'][] = $this->title;

if(($id = Yii::$app->request->get('id')) != null) {
	$this->params['menu']['content'] = [
		['label' => Yii::t('app', 'Add New Child Levels'), 'url' => Url::to(['create', 'id'=>$id]), 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn btn-success']],
	];
} else {
	$this->params['menu']['content'] = [
		['label' => Yii::t('app', 'Add Fond'), 'url' => Url::to(['create']), 'icon' => 'plus-square', 'htmlOptions' => ['class'=>'btn btn-success']],
	];
}
$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="archives-manage">
<?php Pjax::begin(); ?>

<?php if($level != null) {
$model = $level;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'level_name_i',
			'value' => function ($model) {
				if($model->level_name_i != '')
					return Html::a($model->level_name_i, ['setting/level/view', 'id'=>$model->id], ['title'=>$model->level_name_i, 'class'=>'modal-btn']);
				return $model->level_name_i;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'level_desc_i',
			'value' => $model->level_desc_i,
		],
		[
			'attribute' => 'child',
			'value' => ArchiveLevel::getChild($model->child),
			'format' => 'html',
		],
	],
]);
}?>

<?php if($media != null) {
$model = $media;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'media_name_i',
			'value' => function ($model) {
				if($model->media_name_i != '')
					return Html::a($model->media_name_i, ['setting/media/view', 'id'=>$model->id], ['title'=>$model->media_name_i, 'class'=>'modal-btn']);
				return $model->media_name_i;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'media_desc_i',
			'value' => $model->media_desc_i,
		],
	],
]);
}?>

<?php if($creator != null) {
$model = $creator;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'creator_name',
			'value' => function ($model) {
				if($model->creator_name != '')
					return Html::a($model->creator_name, ['setting/creator/view', 'id'=>$model->id], ['title'=>$model->creator_name, 'class'=>'modal-btn']);
				return $model->creator_name;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'creator_desc',
			'value' => $model->creator_desc ? $model->creator_desc : '-',
		],
	],
]);
}?>

<?php if($repository != null) {
$model = $repository;
echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		[
			'attribute' => 'repository_name',
			'value' => function ($model) {
				if($model->repository_name != '')
					return Html::a($model->repository_name, ['setting/repository/view', 'id'=>$model->id], ['title'=>$model->repository_name, 'class'=>'modal-btn']);
				return $model->repository_name;
			},
			'format' => 'html',
		],
		[
			'attribute' => 'repository_desc',
			'value' => $model->repository_desc ? $model->repository_desc : '-',
		],
	],
]);
}?>

<?php //echo $this->render('_search', ['model'=>$searchModel]); ?>

<?php echo $this->render('_option_form', ['model'=>$searchModel, 'gridColumns'=>$searchModel->activeDefaultColumns($columns), 'route'=>$this->context->route]); ?>

<?php 
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
		if($action == 'view')
			return Url::to(['view', 'id'=>$key]);
		if($action == 'update')
			return Url::to(['update', 'id'=>$key]);
		if($action == 'delete')
			return Url::to(['delete', 'id'=>$key]);
	},
	'template' => '{view} {update} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>