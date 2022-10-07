<?php
/**
 * Archive Lurings (archive-lurings)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\luring\AdminController
 * @var $model ommu\archive\models\ArchiveLurings
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
use yii\widgets\DetailView;

if (!$small) {
    $context = $this->context;
    if ($context->breadcrumbApp) {
        $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
    }
    $archive = $model->archive;
    $this->params['breadcrumbs'][] = ['label' => $archive->isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Inventory'), 'url' => $archive->isFond ? ['fond/index'] : ['admin/index']];
    $archiveDetailUrl = $archive->isFond ? ['fond/view', 'id' => $archive->id] : ['admin/view', 'id' => $archive->id];
    $this->params['breadcrumbs'][] = ['label' => $archive->isFond ? $archive->code : Yii::t('app', '#{level-name} {code}', ['level-name' => strtoupper($archive->levelTitle->message), 'code' => $archive->code]), 'url' => $archiveDetailUrl];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Luring'), 'url' => ['manage', 'archive' => $archive->id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');
} ?>

<div class="archive-lurings-view">

<?php
$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id ? $model->id : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model->quickAction(Url::to(['publish', 'id' => $model->primaryKey]), $model->publish),
		'format' => 'raw',
		'visible' => !$small,
	],
	[
		'attribute' => 'archiveTitle',
		'value' => function ($model) {
            return $model::parseArchive($model);
		},
		'format' => 'raw',
	],
	[
		'attribute' => 'introduction',
		'value' => $model->introduction ? $model->introduction : '-',
		'format' => 'html',
		'visible' => !$small,
	],
	[
		'attribute' => 'senarai_file_draft',
		'value' => function ($model) {
            $uploadPath = join('/', [$model->archive::getUploadPath(false), 'document_draft']);
            return $model::parseSenaraiFileDraft($model->senarai_file_draft, $uploadPath);
		},
		'format' => 'raw',
	],
	[
		'attribute' => 'senarai_file',
		'value' => function ($model) {
            $uploadPath = $model::getUploadPath(false);
            $senarai_file = $model->senarai_file ? Yii::t('app', 'Download: {senarai_file}', ['senarai_file' => Html::a($model->senarai_file, Url::to(join('/', ['@webpublic', $uploadPath, $model->senarai_file])), ['title' => $model->senarai_file, 'data-pjax' => 0, 'target' => '_blank'])]) : '-';
            return $senarai_file;
		},
		'format' => 'raw',
	],
	[
		'attribute' => 'oDownload',
		'value' => function ($model) {
			$downloads = $model->grid->download;
			return Html::a($downloads, ['luring/download/manage', 'luring' => $model->primaryKey], ['title' => Yii::t('app', '{count} downloads', ['count' => $downloads])]);
		},
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
		'value' => Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Update'), 'class' => 'btn btn-primary btn-sm']),
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