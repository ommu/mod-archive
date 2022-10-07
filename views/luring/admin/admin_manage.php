<?php
/**
 * Archive Lurings (archive-lurings)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\luring\AdminController
 * @var $model ommu\archive\models\ArchiveLurings
 * @var $searchModel ommu\archive\models\search\ArchiveLurings
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 October 2022, 23:20 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}

if ($archive) {
    $this->params['breadcrumbs'][] = ['label' => $archive->isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Inventory'), 'url' => $archive->isFond ? ['fond/index'] : ['admin/index']];
    $archiveDetailUrl = $archive->isFond ? ['fond/view', 'id' => $archive->id] : ['admin/view', 'id' => $archive->id];
    $this->params['breadcrumbs'][] = ['label' => $archive->isFond ? $archive->code : Yii::t('app', '#{level-name} {code}', ['level-name' => strtoupper($archive->levelTitle->message), 'code' => $archive->code]), 'url' => $archiveDetailUrl];
}
$this->params['breadcrumbs'][] = Yii::t('app', 'Lurings');

if ($archive) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Generate Senarai Luring'), 'url' => Url::to(['luring/admin/create', 'id' => $archive->id]), 'icon' => 'plus-square', 'htmlOptions' => ['class' => 'btn btn-success']],
    ];
}

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="archive-lurings-manage">
<?php Pjax::begin(); ?>

<?php if ($archive != null) {
	echo $this->render('/admin/admin_view', ['model' => $archive, 'small' => true]);
} ?>

<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

<?php echo $this->render('_option_form', ['model' => $searchModel, 'gridColumns' => $searchModel->activeDefaultColumns($columns), 'route' => $this->context->route]); ?>

<?php
$columnData = $columns;
array_push($columnData, [
	'class' => 'app\components\grid\ActionColumn',
	'header' => Yii::t('app', 'Option'),
	'urlCreator' => function($action, $model, $key, $index) {
        if ($action == 'view') {
            return Url::to(['view', 'id' => $key]);
        }
        if ($action == 'update') {
            return Url::to(['update', 'id' => $key]);
        }
        if ($action == 'delete') {
            return Url::to(['delete', 'id' => $key]);
        }
	},
	'buttons' => [
		'view' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail Luring'), 'class' => 'modal-btn']);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Luring')]);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Luring'),
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