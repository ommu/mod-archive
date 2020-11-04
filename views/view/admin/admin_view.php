<?php
/**
 * Archive Views (archive-views)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\view\AdminController
 * @var $model ommu\archive\models\ArchiveViews
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 25 February 2020, 16:43 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $context = $this->context;
    if ($context->breadcrumbApp) {
        $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
    }
    $this->params['breadcrumbs'][] = ['label' => $model->archive->isFond ? Yii::t('app', 'Fond') : Yii::t('app', 'Inventory'), 'url' => $model->archive->isFond ? ['fond/index'] : ['admin/index']];
    $this->params['breadcrumbs'][] = ['label' => $model->archive->isFond ? $model->archive->code : Yii::t('app', '{level-name} {code}', ['level-name'=>$model->archive->level->level_name_i, 'code'=>$model->archive->code]), 'url' => [($model->archive->isFond ? 'fond' : 'admin').'/view', 'id'=>$model->archive_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'View'), 'url' => ['view/admin/manage', 'archive'=>$model->archive_id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="archive-views-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'archiveTitle',
		'value' => function ($model) {
			$archiveTitle = isset($model->archive) ? $model->archive->title : '-';
            if ($archiveTitle != '-') {
                return Html::a($archiveTitle, ['admin/view', 'id'=>$model->archive_id], ['title'=>$model::htmlHardDecode($archiveTitle), 'class'=>'modal-btn']);
            }
			return $archiveTitle;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
	],
	[
		'attribute' => 'views',
		'value' => function ($model) {
			$views = $model->views;
			return Html::a($views, ['view/history/manage', 'view'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} histories', ['count'=>$views])]);
		},
		'format' => 'html',
		'visible' => !$small,
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
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
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