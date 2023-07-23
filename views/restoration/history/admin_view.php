<?php
/**
 * Archive Restoration Histories (archive-restoration-history)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\restoration\HistoryController
 * @var $model ommu\archive\models\ArchiveRestorationHistory
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:23 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if (!$small) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restoration Histories'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $model->restoration->archive->title;

    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id' => $model->id]), 'icon' => 'pencil', 'htmlOptions' => ['class' => 'btn btn-primary']],
        ['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id' => $model->id]), 'htmlOptions' => ['data-confirm' => Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method' => 'post', 'class' => 'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="archive-restoration-history-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'restorationArchiveId',
		'value' => function ($model) {
            $restorationArchiveId = isset($model->restoration) ? $model->restoration->archive->title : '-';
            if ($restorationArchiveId != '-') {
                return Html::a($restorationArchiveId, ['restoration/view', 'id' => $model->restoration_id], ['title' => $restorationArchiveId, 'class' => 'modal-btn']);
            }
            return $restorationArchiveId;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'condition',
		'value' => $model::getCondition($model->condition),
		'visible' => !$small,
	],
	[
		'attribute' => 'condition_date',
		'value' => Yii::$app->formatter->asDatetime($model->condition_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm modal-btn']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
	],
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>