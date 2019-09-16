<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 * @var $searchModel ommu\archive\models\search\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.co>
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

if(!$parent)
	$this->params['breadcrumbs'][] = $this->title;
else {
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inventory'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = ['label' => $parent::htmlHardDecode($parent->code), 'url' => ['view', 'id'=>$parent->id]];
	$this->params['breadcrumbs'][] = Yii::t('app', 'Childs');
}

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

<?php if($level != null)
	echo $this->render('/setting/level/admin_view', ['model'=>$level, 'small'=>true]); ?>

<?php if($media != null)
	echo $this->render('/setting/media/admin_view', ['model'=>$media, 'small'=>true]); ?>

<?php if($creator != null)
	echo $this->render('/setting/creator/admin_view', ['model'=>$creator, 'small'=>true]); ?>

<?php if($repository != null)
	echo $this->render('/setting/repository/admin_view', ['model'=>$repository, 'small'=>true]); ?>

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
	'buttons' => [
		'view' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title'=>Yii::t('app', 'Detail'), 'data-pjax'=>0]);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title'=>Yii::t('app', 'Update'), 'data-pjax'=>0]);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view} {update} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>