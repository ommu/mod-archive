<?php
/**
 * Archive View Histories (archive-view-history)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\view\HistoryController
 * @var $model ommu\archive\models\ArchiveViewHistory
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
use yii\widgets\DetailView;

if(!$small) {
    $context = $this->context;
    if($context->breadcrumbApp) {
        $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
    }
    $this->params['breadcrumbs'][] = ['label' => $model->view->archive->isFond ? Yii::t('app', 'Fond') : Yii::t('app', 'Inventory'), 'url' => $model->view->archive->isFond ? ['fond/index'] : ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->view->archive->isFond ? $model->view::htmlHardDecode($model->view->archive->title) : $model->view->archive->fond_code, 'url' => [($archive->isFond ? 'fond' : 'admin').'/view', 'id'=>$model->view->archive_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'View'), 'url' => ['view/admin/manage', 'archive'=>$model->view->archive_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'History'), 'url' => ['view/history/manage', 'view'=>$model->view_id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="archive-view-history-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'archiveTitle',
		'value' => function ($model) {
			$archiveTitle = isset($model->view->archive) ? $model->view->archive->title : '-';
			if($archiveTitle != '-')
				return Html::a($archiveTitle, ['admin/view', 'id'=>$model->view->archive_id], ['title'=>$model->view::htmlHardDecode($archiveTitle), 'class'=>'modal-btn']);
			return $archiveTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'view.user_id',
		'value' => isset($model->view->user) ? $model->view->user->displayname : '-',
	],
	[
		'attribute' => 'view_date',
		'value' => Yii::$app->formatter->asDatetime($model->view_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'view_ip',
		'value' => $model->view_ip,
		'visible' => !$small,
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