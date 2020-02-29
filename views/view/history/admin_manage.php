<?php
/**
 * Archive View Histories (archive-view-history)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\view\HistoryController
 * @var $model ommu\archive\models\ArchiveViewHistory
 * @var $searchModel ommu\archive\models\search\ArchiveViewHistory
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.co)
 * @created date 25 February 2020, 16:43 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;

$context = $this->context;
if($context->breadcrumbApp) {
    $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
if($view != null) {
    $this->params['breadcrumbs'][] = ['label' => $view->archive->isFond ? Yii::t('app', 'Fond') : Yii::t('app', 'Inventory'), 'url' => $view->archive->isFond ? ['fond/index'] : ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $view->archive->isFond ? $view->archive->code : Yii::t('app', '{level-name} {code}', ['level-name'=>$view->archive->level->level_name_i, 'code'=>$view->archive->code]), 'url' => [($archive->isFond ? 'fond' : 'admin').'/view', 'id'=>$view->archive_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'View'), 'url' => ['view/admin/manage', 'archive'=>$view->archive_id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archive'), 'url' => ['admin/index']];
}
$this->params['breadcrumbs'][] = $this->title;

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="archive-view-history-manage">
<?php Pjax::begin(); ?>

<?php if($view != null)
	echo $this->render('/view/admin/admin_view', ['model'=>$view, 'small'=>true]); ?>

<?php if($archive != null)
	echo $this->render('/admin/admin_view', ['model'=>$archive, 'small'=>true]); ?>

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
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title'=>Yii::t('app', 'Detail'), 'class'=>'modal-btn']);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title'=>Yii::t('app', 'Update'), 'class'=>'modal-btn']);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{view} {delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>