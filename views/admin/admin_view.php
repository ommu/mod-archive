<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
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
use yii\widgets\DetailView;
use ommu\archive\models\Archives;
use yii\helpers\ArrayHelper;

\ommu\archive\assets\AciTreeAsset::register($this);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;

if(!$small) {
$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back to Inventaris'), 'url' => Url::to(['index']), 'icon' => 'tasks', 'htmlOptions' => ['class'=>'btn btn-success']],
];
} ?>

<div class="archives-view">

<?php 
$treeDataUrl = Url::to(['data', 'id'=>$model->id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->id';
JS;
$this->registerJs($js, \yii\web\View::POS_HEAD);

$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id,
	],
	[
		'attribute' => 'publish',
		'value' => Archives::getPublish($model->publish),
	],
	[
		'attribute' => 'sidkkas',
		'value' => $model->filterYesNo($model->sidkkas),
	],
	[
		'attribute' => 'parent_id',
		'value' => Archives::parseParent($model),
		'format' => 'raw',
	],
	[
		'attribute' => 'code',
		'value' => function ($model) {
			$setting = \ommu\archive\models\ArchiveSetting::find()
				->select(['maintenance_mode', 'reference_code_sikn', 'reference_code_separator'])
				->where(['id' => 1])
				->one();
			if(!$setting->maintenance_mode) {
				$referenceCode = $model->referenceCode;
				array_multisort($referenceCode);
				return $setting->reference_code_sikn.' '.preg_replace("/($model->code)$/", '<span class="text-primary">'.$model->code.'</span>', join($setting->reference_code_separator, ArrayHelper::map($referenceCode, 'level', 'code')));
			} else {
				$referenceCode = $model->referenceCode;
				array_multisort($referenceCode);
				$oldReferenceCodeTemplate = $setting->reference_code_sikn.' '.preg_replace("/($model->shortCode)$/", '<span class="text-danger">'.$model->shortCode.'</span>', $model->code);
				$newReferenceCodeTemplate = $setting->reference_code_sikn.' '.preg_replace("/($model->confirmCode)$/", '<span class="text-primary">'.$model->confirmCode.'</span>', join($setting->reference_code_separator, ArrayHelper::map($referenceCode, 'level', 'confirmCode')));
				if($model->code == $model->confirmCode)
					return '//OLD//NEW// '.$newReferenceCodeTemplate;
				return '//OLD// '.$oldReferenceCodeTemplate.'<br/>//NEW// '.$newReferenceCodeTemplate;
			}
		},
		'format' => 'html',
	],
	[
		'attribute' => 'title',
		'value' => $model->title ? $model->title : '-',
		'format' => 'html',
	],
	[
		'attribute' => 'levelName',
		'value' => function ($model) {
			$levelName = isset($model->level) ? $model->level->title->message : '-';
			if($levelName != '-')
				return Html::a($levelName, ['setting/level/view', 'id'=>$model->level_id], ['title'=>$levelName, 'class'=>'modal-btn']);
			return $levelName;
		},
		'format' => 'html',
	],
	[
		'attribute' => 'creator',
		'value' => Archives::parseRelated($model->getRelatedCreator(true, 'title'), 'creator'),
		'format' => 'html',
	],
	[
		'attribute' => 'repository',
		'value' => Archives::parseRelated($model->getRelatedRepository(true, 'title'), 'repository', ', '),
		'format' => 'html',
	],
	[
		'attribute' => 'subject',
		'value' => Archives::parseSubject($model->getRelatedSubject(true, 'title'), 'subjectId'),
		'format' => 'html',
	],
	[
		'attribute' => 'function',
		'value' => Archives::parseSubject($model->getRelatedFunction(true, 'title'), 'functionId'),
		'format' => 'html',
	],
	[
		'attribute' => 'medium',
		'value' => Archives::parseChilds($model->childs, $model->id),
		'format' => 'html',
	],
	[
		'attribute' => 'media',
		'value' => Archives::parseRelated($model->getRelatedMedia(true, 'title')),
		'format' => 'html',
	],
	[
		'attribute' => 'image_type',
		'value' => Archives::getImageType($model->image_type ? $model->image_type : '-'),
		'visible' => in_array('image_type', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'location',
		'value' => Archives::parseLocation($model->location),
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
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->id], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-primary']),
		'format' => 'html',
		'visible' => Yii::$app->request->isAjax ? true : false,
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