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
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="archives-view">

<?php 
$attributes[] = [
	'attribute' => 'id',
	'value' => $model->id,
];
$attributes[] = [
	'attribute' => 'publish',
	'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
	'format' => 'raw',
];
$attributes[] = [
	'attribute' => 'sidkkas',
	'value' => $this->filterYesNo($model->sidkkas),
];
$attributes[] = [
	'attribute' => 'parent_id',
	'value' => $model->parent_id,
];
$attributes[] = [
	'attribute' => 'levelName',
	'value' => function ($model) {
		$levelName = isset($model->level) ? $model->level->title->message : '-';
		if($levelName != '-')
			return Html::a($levelName, ['setting/level/view', 'id'=>$model->level_id], ['title'=>$levelName]);
		return $levelName;
	},
	'format' => 'html',
];
$attributes[] = [
	'attribute' => 'code',
	'value' => $model->code,
];
$attributes[] = [
	'attribute' => 'title',
	'value' => $model->title ? $model->title : '-',
];
$attributes[] = [
	'attribute' => 'image_type',
	'value' => Archives::getImageType($model->image_type ? $model->image_type : '-'),
];
$attributes[] = [
	'attribute' => 'media',
	'value' => function ($model) {
		// return $model->getMedia();
		return '-';
	},
];
$attributes[] = [
	'attribute' => 'creation_date',
	'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
];
$attributes[] = [
	'attribute' => 'creationDisplayname',
	'value' => isset($model->creation) ? $model->creation->displayname : '-',
];
$attributes[] = [
	'attribute' => 'modified_date',
	'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
];
$attributes[] = [
	'attribute' => 'modifiedDisplayname',
	'value' => isset($model->modified) ? $model->modified->displayname : '-',
];
$attributes[] = [
	'attribute' => 'updated_date',
	'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
];

echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>