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
use yii\helpers\ArrayHelper;

\ommu\archive\assets\AciTreeAsset::register($this);

if(!$small) {
    $context = $this->context;
    if($context->breadcrumbApp) {
        $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
    }
    $this->params['breadcrumbs'][] = ['label' => $isFond ? Yii::t('app', 'Fond') : Yii::t('app', 'Inventory'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $isFond ? $model->code : Yii::t('app', '{level-name} {code}', ['level-name'=>$model->level->level_name_i, 'code'=>$model->code]);

    if(!in_array('location', $model->level->field)) {
        unset($this->params['menu']['content']['location']);
    }
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
		'value' => $model::getPublish($model->publish),
	],
	[
		'attribute' => 'sidkkas',
		'value' => $model->filterYesNo($model->sidkkas),
		'visible' => !$small,
	],
	[
		'attribute' => 'parent_id',
		'value' => $model::parseParent($model),
		'format' => 'raw',
		'visible' => !$small && !$isFond,
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
		'attribute' => 'medium',
		'value' => function ($model) {
			if(strtolower($model->level->level_name_i) == 'item')
				return $model->medium ? $model->medium : '-';
			return $model::parseChilds($model->getChilds(['sublevel'=>false, 'back3nd'=>true]), $model->id);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'creator',
		'value' => $model::parseRelated($model->getRelatedCreator(true, 'title'), 'creator'),
		'format' => 'html',
		'visible' => !$small && in_array('creator', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'repository',
		'value' => $model::parseRelated($model->getRelatedRepository(true, 'title'), 'repository', ', '),
		'format' => 'html',
		'visible' => !$small && in_array('repository', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'media',
		'value' => $model::parseRelated($model->getRelatedMedia(true, 'title')),
		'format' => 'html',
		'visible' => !$small && in_array('media', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'archive_type',
		'value' => $model::getArchiveType($model->archive_type ? $model->archive_type : '-'),
		'visible' => !$small && in_array('archive_type', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'archive_date',
		'value' => function ($model) {
			if(empty($model->level->child))
				return Yii::$app->formatter->asDate($model->archive_date, 'long');
			if(strtolower($model->level->level_name_i) == 'fond')
				return Yii::$app->formatter->asDate($model->archive_date, 'php:Y');
			return Yii::$app->formatter->asDate($model->archive_date, 'long');
		},
		'visible' => !$small,
	],
	[
		'attribute' => 'archive_file',
		'value' => function ($model) {
			if(!$model->archive_file)
				return '-';

			$extension = pathinfo($model->archive_file, PATHINFO_EXTENSION);
			$setting = $model->getSetting(['image_type', 'document_type', 'maintenance_mode', 'maintenance_document_path', 'maintenance_image_path']);
			$imageFileType = $model->formatFileType($setting->image_type);
			$documentFileType = $model->formatFileType($setting->document_type);

			if($model->isNewFile)
				$uploadPath = join('/', [$model::getUploadPath(false), $model->id]);
			else {
				if(in_array($extension, $imageFileType))
					$uploadPath = join('/', [$model::getUploadPath(false), $setting->maintenance_image_path]);
				if(in_array($extension, $documentFileType))
					$uploadPath = join('/', [$model::getUploadPath(false), $setting->maintenance_document_path]);
			}
			$filePath = Url::to(join('/', ['@webpublic', $uploadPath, $model->archive_file]));
			// $filePath = Url::to(join('/', ['@webpublic', 'siks', 'example-preview-pdf.pdf']));

			if(in_array($extension, $imageFileType))
				return Html::img($filePath, ['alt'=>$model->archive_file, 'class'=>'mb-3']).'<br/>'.$model->archive_file;
			if(in_array($extension, $documentFileType)) {
				return \app\components\widgets\PreviewPDF::widget([
					'url' => $filePath,
					'navigationOptions' => ['class'=>'summary mb-4'],
					'previewOptions' => ['class'=>'preview-pdf border border-width-3'],
				]);
			}
		},
		'format' => 'raw',
		'visible' => !$small && in_array('archive_file', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'location',
		'value' => function ($model) {
			if(($location = $model->getRelatedLocation(false)) != null)
				return $model::parseLocation($location);
			return Html::a(Yii::t('app', 'Add archive location'), ['location', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Add archive location'), 'class'=>'modal-btn']);
		},
		'format' => 'html',
		'visible' => !$small && in_array('location', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'subject',
		'value' => $model::parseSubject($model->getRelatedSubject(true, 'title'), 'subjectId'),
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'function',
		'value' => $model::parseSubject($model->getRelatedFunction(true, 'title'), 'functionId'),
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'creationDisplayname',
		'value' => isset($model->creation) ? $model->creation->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'modified_date',
		'value' => Yii::$app->formatter->asDatetime($model->modified_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => 'modifiedDisplayname',
		'value' => isset($model->modified) ? $model->modified->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'updated_date',
		'value' => Yii::$app->formatter->asDatetime($model->updated_date, 'medium'),
		'visible' => !$small,
	],
	[
		'attribute' => '',
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Update'), 'class'=>'btn btn-success btn-sm']),
		'format' => 'html',
		'visible' => !$small && Yii::$app->request->isAjax ? true : false,
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