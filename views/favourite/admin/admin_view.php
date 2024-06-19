<?php
/**
 * Archive Favourites (archive-favourites)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\favourite\AdminController
 * @var $model ommu\archive\models\ArchiveFavourites
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 October 2022, 09:35 WIB
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
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bookmark'), 'url' => ['manage', 'archive' => $archive->id]];
    $this->params['breadcrumbs'][] = Yii::t('app', 'Detail');
} ?>

<div class="archive-favourites-view">

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
            return $model::parseArchive($model, true);
		},
		'format' => 'html',
	],
	[
		'attribute' => 'userDisplayname',
		'value' => isset($model->user) ? $model->user->displayname : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_ip',
		'value' => $model->creation_ip ? $model->creation_ip : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'creation_date',
		'value' => Yii::$app->formatter->asDatetime($model->creation_date, 'medium'),
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
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]); ?>

</div>