<?php
/**
 * Archive Luring Downloads (archive-luring-download)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\luring\DownloadController
 * @var $model ommu\archive\models\ArchiveLuringDownload
 * @var $searchModel ommu\archive\models\search\ArchiveLuringDownload
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 5 October 2022, 08:16 WIB
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
if ($archive != null) {
    $senaraiLabel = $archive->isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Inventory');
    $senaraiUrl = $archive->isFond ? ['fond/index'] : ['admin/index'];
    if ($isPengolahan) {
        $senaraiLabel = Yii::t('app', 'Senarai');
        $senaraiUrl = ['luring/admin/index'];
    }
    $this->params['breadcrumbs'][] = ['label' => $senaraiLabel, 'url' => $senaraiUrl];
    $senaraiDetailLabel = $archive->isFond ? $archive->code : Yii::t('app', '#{level-name} {code}', ['level-name' => strtoupper($archive->levelTitle->message), 'code' => $archive->code]);
    $senaraiDetailUrl = $archive->isFond ? ['fond/view', 'id' => $archive->id] : ['admin/view', 'id' => $archive->id];
    if ($isPengolahan) {
        $senaraiDetailUrl = ['luring/admin/view', 'id' => $archive->id];
    }
    $this->params['breadcrumbs'][] = ['label' => $senaraiDetailLabel, 'url' => $senaraiDetailUrl];
    $documentLabel = Yii::t('app', 'Luring');
    $documentUrl = ['luring/admin/manage', 'archive' => $archive->id];
    if ($isPengolahan) {
        $documentLabel = Yii::t('app', 'Document');
        $documentUrl = ['luring/document/manage', 'archive' => $archive->id];
    }
    $this->params['breadcrumbs'][] = ['label' => $documentLabel, 'url' => $documentUrl];
} else {
    $documentLabel = Yii::t('app', 'Luring');
    $documentUrl = ['luring/admin/index'];
    if ($isPengolahan) {
        $documentLabel = Yii::t('app', 'Document');
        $documentUrl = ['luring/document/index'];
    }
    $this->params['breadcrumbs'][] = ['label' => $documentLabel, 'url' => $documentUrl];
}
$this->params['breadcrumbs'][] = Yii::t('app', 'Downloads');

$this->params['menu']['option'] = [
	//['label' => Yii::t('app', 'Search'), 'url' => 'javascript:void(0);'],
	['label' => Yii::t('app', 'Grid Option'), 'url' => 'javascript:void(0);'],
];
?>

<div class="archive-luring-download-manage">
<?php Pjax::begin(); ?>

<?php if ($luring != null) {
	echo $this->render('@ommu/archive/views/luring/admin/admin_view', ['model' => $luring, 'small' => true]);
} ?>

<?php if ($archive != null) {
	echo $this->render('@ommu/archive/views/admin/admin_view', ['model' => $archive, 'small' => true]);
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
			return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['title' => Yii::t('app', 'Detail Luring Download')]);
		},
		'update' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['title' => Yii::t('app', 'Update Luring Download')]);
		},
		'delete' => function ($url, $model, $key) {
			return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				'title' => Yii::t('app', 'Delete Luring Download'),
				'data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'data-method'  => 'post',
			]);
		},
	],
	'template' => '{delete}',
]);

echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columnData,
]); ?>

<?php Pjax::end(); ?>
</div>