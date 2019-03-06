<?php
/**
 * Archive Levels (archive-level)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\LevelController
 * @var $model ommu\archive\models\ArchiveLevel
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 02:27 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ommu\archive\models\ArchiveLevel;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title->message;

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back To Manage'), 'url' => Url::to(['manage']), 'icon' => 'table'],
	['label' => Yii::t('app', 'Detail'), 'url' => Url::to(['view', 'id'=>$model->id]), 'icon' => 'eye'],
	['label' => Yii::t('app', 'Update'), 'url' => Url::to(['update', 'id'=>$model->id]), 'icon' => 'pencil'],
	['label' => Yii::t('app', 'Delete'), 'url' => Url::to(['delete', 'id'=>$model->id]), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'), 'data-method'=>'post'], 'icon' => 'trash'],
];
?>

<div class="archive-level-view">

<?php echo DetailView::widget([
	'model' => $model,
	'options' => [
		'class'=>'table table-striped detail-view',
	],
	'attributes' => [
		'id',
		[
			'attribute' => 'publish',
			'value' => $this->quickAction(Url::to(['publish', 'id'=>$model->primaryKey]), $model->publish),
			'format' => 'raw',
		],
		[
			'attribute' => 'level_name_i',
			'value' => $model->level_name_i,
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
			'attribute' => 'archives',
			'value' => function ($model) {
				$archives = $model->getArchives(true);
				return Html::a($archives, ['admin/manage', 'id'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} archives', ['count'=>$archives])]);
			},
			'format' => 'html',
		],
	],
]); ?>

</div>