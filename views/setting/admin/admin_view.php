<?php
/**
 * Archive Settings (archive-setting)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\AdminController
 * @var $model ommu\archive\models\ArchiveSetting
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 5 March 2019, 23:53 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

if($breadcrumb) {
    $context = $this->context;
    if($context->breadcrumbApp) {
        $this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
    }
    $this->params['breadcrumbs'][] = $this->title;
}

if(!$small) {
    $this->params['menu']['content'] = [
        ['label' => Yii::t('app', 'Reset'), 'url' => Url::to(['delete']), 'htmlOptions' => ['data-confirm'=>Yii::t('app', 'Are you sure you want to reset this setting?'), 'data-method'=>'post', 'class'=>'btn btn-danger'], 'icon' => 'trash'],
    ];
} ?>

<div class="archive-setting-view">

<?php
$attributes = [
    'id',
    'license',
    [
        'attribute' => 'permission',
        'value' => $model::getPermission($model->permission),
    ],
    [
        'attribute' => 'meta_description',
        'value' => $model->meta_description ? $model->meta_description : '-',
    ],
    [
        'attribute' => 'meta_keyword',
        'value' => $model->meta_keyword ? $model->meta_keyword : '-',
    ],
    [
        'attribute' => 'fond_sidkkas',
        'value' => $model::getFondSidkkas($model->fond_sidkkas),
    ],
    'reference_code_sikn',
    [
        'attribute' => 'reference_code_separator',
        'value' => '"'.$model->reference_code_separator.'"',
    ],
    [
        'attribute' => 'short_code',
        'value' => $model::getFondSidkkas($model->short_code),
    ],
    [
        'attribute' => 'medium_sublevel',
        'value' => $model::getFondSidkkas($model->medium_sublevel),
    ],
    [
        'attribute' => 'production_date',
        'value' => Yii::$app->formatter->asDate($model->production_date, 'medium'),
    ],
    [
        'attribute' => 'image_type',
        'value' => $model->image_type,
    ],
    [
        'attribute' => 'document_type',
        'value' => $model->document_type,
    ],
    [
        'attribute' => 'maintenance_mode',
        'value' => $model::getFondSidkkas($model->maintenance_mode),
    ],
    [
        'attribute' => 'maintenance_image_path',
        'value' => $model->maintenance_image_path ? $model->maintenance_image_path : '-',
    ],
    [
        'attribute' => 'maintenance_document_path',
        'value' => $model->maintenance_document_path ? $model->maintenance_document_path : '-',
    ],
    [
        'attribute' => 'breadcrumb_param',
        'value' => $model::parseBreadcrumbApps($model->breadcrumb_param),
        'format' => 'html',
        'visible' => !$small,
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
        'attribute' => '',
        'value' => Html::a(Yii::t('app', 'Update'), Url::to(['update']), [
            'class' => 'btn btn-primary',
        ]),
        'format' => 'html',
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