<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

!$small ? \ommu\archive\assets\AciTreeAsset::register($this) : '';

if (!$small) {
    $context = $this->context;
    if ($context->breadcrumbApp) {
        $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
    }
    $this->params['breadcrumbs'][] = ['label' => $isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Inventory'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $isFond ? $model->code : Yii::t('app', '#{level-name} {code}', ['level-name' => strtoupper($model->levelTitle->message), 'code' => $model->code]);

    if (!in_array('location', $model->level->field)) {
        unset($this->params['menu']['content']['location']);
    }
} ?>

<div class="archives-view">

<?php 
$treeDataUrl = Url::to(['data', 'id' => $model->id]);
$js = <<<JS
	var treeDataUrl = '$treeDataUrl';
	var selectedId = '$model->id';
JS;
!$small ? $this->registerJs($js, \yii\web\View::POS_HEAD) : '';

$attributes = [
	[
		'attribute' => 'id',
		'value' => $model->id,
		'visible' => !$small,
	],
	[
		'attribute' => 'publish',
		'value' => $model::getPublish($model->publish),
		'visible' => !$small,
	],
	[
		'attribute' => 'sidkkas',
		'value' => $model->filterYesNo($model->sidkkas),
		'visible' => !$small,
	],
	[
		'attribute' => 'parent_id',
		'value' => function ($model) {
            $parent = $model->parent;
            return $model::parseParent($parent);
		},
		'format' => 'raw',
		'visible' => !$small && !$isFond,
	],
	[
		'attribute' => 'levelName',
		'value' => function ($model) {
			$levelName = isset($model->levelTitle) ? $model->levelTitle->message : '-';
            if ($levelName != '-') {
				return Html::a($levelName, ['setting/level/view', 'id' => $model->level_id], ['title' => $levelName, 'class' => 'modal-btn']);
            }
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
            if (!$setting->maintenance_mode) {
				$referenceCode = $model->referenceCode;
				array_multisort($referenceCode);
				return $setting->reference_code_sikn.' '.preg_replace("/($model->code)$/", '<span class="text-primary">'.$model->code.'</span>', join($setting->reference_code_separator, ArrayHelper::map($referenceCode, 'level', 'code')));
			} else {
				$referenceCode = $model->referenceCode;
				array_multisort($referenceCode);
				$oldReferenceCodeTemplate = $setting->reference_code_sikn.' '.preg_replace("/($model->shortCode)$/", '<span class="text-danger">'.$model->shortCode.'</span>', $model->code);
				$newReferenceCodeTemplate = $setting->reference_code_sikn.' '.preg_replace("/($model->confirmCode)$/", '<span class="text-primary">'.$model->confirmCode.'</span>', join($setting->reference_code_separator, ArrayHelper::map($referenceCode, 'level', 'confirmCode')));
                if ($model->code == $model->confirmCode) {
                    return '//OLD//NEW// '.$newReferenceCodeTemplate;
                }
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
		'attribute' => 'creator',
		'value' => $model::parseRelated($model->getCreators(true, 'title'), 'creator'),
		'format' => 'html',
		'visible' => (!$small && in_array('creator', $model->level->field)) || ($small && $model->isFond) ? true : false,
	],
	[
		'attribute' => 'repository',
		'value' => $model::parseRelated($model->getRepositories(true, 'title'), 'repository', ', '),
		'format' => 'html',
		'visible' => !$small && in_array('repository', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'archive_date',
		'value' => $model->archive_date ? $model->archive_date : '-',
		'visible' => !$small,
	],
	[
		'attribute' => 'media',
		'value' => $model::parseRelated($model->getMedias(true, 'title')),
		'format' => 'html',
		'visible' => !$small && in_array('media', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'archive_type',
		'value' => $model::getArchiveType($model->archive_type ? $model->archive_type : '-'),
		'visible' => !$small && in_array('archive_type', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'subject',
		'value' => $model::parseSubject($model->getSubjects(true, 'title'), 'subjectId'),
		'format' => 'html',
		'visible' => !$small && in_array('subject', $model->level->field),
	],
	[
		'attribute' => 'function',
		'value' => $model::parseSubject($model->getFunctions(true, 'title'), 'functionId'),
		'format' => 'html',
		'visible' => !$small && in_array('function', $model->level->field),
	],
	[
		'attribute' => 'location',
		'value' => function ($model) {
            if (($location = $model->getLocations(false)) != null) {
                return $model::parseLocation($location);
            }
			return Html::a(Yii::t('app', 'Add archive location'), ['location', 'id' => $model->primaryKey], ['title' => Yii::t('app', 'Add archive location'), 'class' => 'modal-btn']);
		},
		'format' => 'html',
		'visible' => !$small && in_array('location', $model->level->field) ? true : false,
	],
	[
		'attribute' => 'medium',
		'value' => function ($model) {
            if (strtolower($model->levelTitle->message) == 'item') {
                return $model->medium ? $model->medium : '-';
            }
			return $model::parseChilds($model->getChilds(['sublevel' => false, 'back3nd' => true]), $model->id);
		},
		'format' => 'html',
        'visible' => !$small,
	],
    [
        'attribute' => 'oView',
        'value' => function ($model) {
            $views = $model->grid->view;
            return Html::a($views, ['view/admin/manage', 'archive' => $model->primaryKey, 'publish' => 1], ['title' => Yii::t('app', '{count} views', ['count' => $views]), 'data-pjax' => 0]);
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

$archiveInfo = DetailView::widget([
	'model' => $model,
	'options' => [
		'class' => 'table table-striped detail-view',
	],
	'attributes' => $attributes,
]);

echo $this->renderWidget($archiveInfo, [
	'overwrite' => true,
    'title' => Yii::t('app', 'Detail: {code}', ['level-name' => $model->levelTitle->message, 'code' => $model->code]),
	'cards' => Yii::$app->request->isAjax || $small ? false : true,
]); ?>

<?php echo !$small && !Yii::$app->request->isAjax && in_array('archive_file', $model->level->field) ? 
	$this->renderWidget('admin_preview_document', [
        'title' => Yii::t('app', 'Preview: {code}', ['level-name' => $model->levelTitle->message, 'code' => $model->code]),
		'model' => $model,
	]) : ''; ?>

</div>