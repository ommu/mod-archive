<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 * @var $form app\components\widgets\ActiveForm
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
use app\components\widgets\ActiveForm;
use yii\redactor\widgets\Redactor;
use ommu\archive\models\ArchiveMedia;
use ommu\selectize\Selectize;
use yii\helpers\ArrayHelper;
use ommu\archive\models\ArchiveRepository;

$redactorOptions = [
	'buttons' => ['html', 'format', 'bold', 'italic', 'deleted'],
	'plugins' => ['fontcolor', 'imagemanager']
];

if(!$isFond)
	$level = $model->isNewRecord ? $parent->getChildLevels(true) : $model->getChildLevels();
// if($setting->maintenance_mode) {
	echo '<div id="reference-code-box" class="hide"><pre>';
	print_r($referenceCode);
	echo '</pre></div>';
// }
?>

<div class="archives-form">

<?php if($isFond || !empty($level)) {
    $creatorField = (!$isFond) ? "
    creator.disable();
    $('input#archive_date').attr('disabled', true);
    $('#archive_type input[name=archive_type]').attr('disabled', true);
    $('input#archive_file').attr('disabled', true);
    media.disable();
    subject.disable();
    $('textarea#medium').attr('disabled', true);
    " : '';
    $levelChangeField = (!$isFond) ? "
	$('#level_id').on('change', function (e) {
		var levelId = $(this).val();
		if(levelId == 8) {
			creator.enable();
			$('.field-item').removeClass('hide');
			$('input#archive_date').attr('disabled', false);
			$('#archive_type input[name=archive_type]').attr('disabled', false);
			$('input#archive_file').attr('disabled', false);
			media.enable();
            subject.enable();
			$('textarea#medium').attr('disabled', false);
		} else {
			creator.disable();
			$('.field-item').addClass('hide');
			$('input#archive_date').attr('disabled', true);
			$('#archive_type input[name=archive_type]').attr('disabled', true);
			$('input#archive_file').attr('disabled', true);
			media.disable();
            subject.disable();
			$('textarea#medium').attr('disabled', true);
		}
	});
    " : '';
$js = <<<JS
	$creatorField
	$('#shortcode').on('keyup', function (e) {
		var shortCode = $(this).val();
		var parentCode = $(this).parent().find('.item').text();
		if(shortCode == '')
			var shortCode = 'XXX';
		$('.reference-code').html(parentCode+shortCode);
	});
	$('#reference-code').on('click', function (e) {
		$('#tree').toggleClass('show hide');
	});
	$levelChangeField
JS;
$this->registerJs($js, \app\components\View::POS_READY);

$hintCondition = $model->isNewRecord && !$isFond ? 'hint-tooltip' : '';
$form = ActiveForm::begin([
	'options' => ['class'=>'form-horizontal form-label-left '.$hintCondition],
	'enableClientValidation' => false,
	'enableAjaxValidation' => false,
	//'enableClientScript' => true,
	'fieldConfig' => [
		'errorOptions' => [
			'encode' => false,
		],
	],
]);
?>

<?php //echo $form->errorSummary($model);?>

<?php if(!$isFond) {
	echo $form->field($model, 'level_id')
		->dropDownList($level, ['prompt'=>''])
		->label($model->getAttributeLabel('level_id'))
		->hint(Yii::t('app', 'Record the level of this unit of description.'));
}

$shortCode = $model->shortCode ? $model->shortCode : 'XXX';
if($isFond) {
	echo $form->field($model, 'level_id', ['template' => '{label}{beginWrapper}{input}<h5 class="text-muted">'.$setting->reference_code_sikn.' <span class="text-primary reference-code">'.$shortCode.'</span></h5>{endWrapper}'])
		->hiddenInput()
		->label($model->getAttributeLabel('code'));

	$shortCodeFieldTemplate = [];
	$shortCodeInputOptions = ['maxlength'=>true, 'placeholder'=>'XXX'];
} else {
	if(!$model->getErrors() && $parent)
		$model->parent_id = $parent->id;
	$parentCode = $model->parent->code;
	if($setting->maintenance_mode)
		$parentCode = $model->parent->confirmCode;
	if(!$setting->maintenance_mode) {
		if(!$model->isNewRecord) {
			$template = '<h5 class="text-muted">'.$setting->reference_code_sikn.' '.preg_replace("/($model->code)$/", '<span class="text-primary reference-code">'.$model->code.'</span>', join($setting->reference_code_separator, ArrayHelper::map($referenceCode, 'level', 'code'))).'</h5>';
		} else {
			$template = '<h5 class="text-muted">'.$setting->reference_code_sikn.' '.join($setting->reference_code_separator, ArrayHelper::map($referenceCode, 'level', 'code')).$setting->reference_code_separator.'<span class="text-primary reference-code">'.$model->parent->code.'.'.$shortCode.'</span></h5>';
		}
	} else {
		if(!$model->isNewRecord) {
			$oldReferenceCodeTemplate = preg_replace("/($shortCode)$/", '<span class="text-danger">'.$shortCode.'</span>', $model->code);
			$newReferenceCodeTemplate = preg_replace("/($model->confirmCode)$/", '<span class="text-primary reference-code">'.$model->confirmCode.'</span>', join($setting->reference_code_separator, ArrayHelper::map($referenceCode, 'level', 'confirmCode')));
			if($model->code == $model->confirmCode)
				$template = '<h5 class="text-muted">//OLD//NEW// '.$setting->reference_code_sikn.' '.$newReferenceCodeTemplate.'</h5>';
			else {
				$template = '<h5 class="text-muted">//OLD// '.$setting->reference_code_sikn.' '.$oldReferenceCodeTemplate.'</h5>';
				$template .= '<h5 class="text-muted">//NEW// '.$setting->reference_code_sikn.' '.$newReferenceCodeTemplate.'</h5>';
			}
		} else
			$template = '<h5 class="text-muted">'.$setting->reference_code_sikn.' '.join($setting->reference_code_separator, ArrayHelper::map($referenceCode, 'level', 'confirmCode')).$setting->reference_code_separator.'<span class="text-primary reference-code">'.$model->parent->confirmCode.'.'.$shortCode.'</span></h5>';
	}
	echo $form->field($model, 'parent_id', ['template' => '{label}{beginWrapper}{input}'.$template.'{endWrapper}'])
		->hiddenInput()
		->label($model->getAttributeLabel('code'));

	$shortCodeFieldTemplate = ['template' => '{label}{beginWrapper}<div class="selectize-control shadow"><div class="selectize-input"><div class="item">'.$parentCode.'.</div>{input}</div></div>{error}{hint}{endWrapper}'];
	$shortCodeInputOptions = ['maxlength'=>true, 'class'=>'', 'placeholder'=>'XXX'];
} ?>

<?php echo $form->field($model, 'shortCode', $shortCodeFieldTemplate)
	->textInput($shortCodeInputOptions)
	->label($model->getAttributeLabel('shortCode'))
	->hint(Yii::t('app', 'Provide a specific local reference code, control number, or other unique identifier. The country and repository code will be automatically added from the linked repository record to form a full reference code.')); ?>

<?php echo $form->field($model, 'title')
	->textarea(['rows'=>4, 'cols'=>50])
	->widget(Redactor::className(), ['clientOptions' => $redactorOptions])
	->label($model->getAttributeLabel('title'))
	->hint(Yii::t('app', 'Provide either a formal title or a concise supplied title in accordance with the rules of multilevel description and national conventions.')); ?>

<?php
$fondMedium = $isFond && in_array('medium', $model->level->field) ? true : false;
echo ($fondMedium || !$isFond) ? $form->field($model, 'medium', ['options' => ['class'=>'form-group row field-item'.(!$isFond && (!isset($model->level) || !empty($model->level->child) || !in_array('medium', $model->level->field)) ? ' hide' : '').'']])
    ->textarea(['rows'=>2, 'cols'=>50])
    ->label($model->getAttributeLabel('medium'))
    ->hint(Yii::t('app', 'Record the extent of the unit of description by giving the number of physical or logical units in arabic numerals and the unit of measurement. Give the specific medium (media) of the unit of description. Separate multiple extents with a linebreak.')) : ''; ?>

<?php
$fondArchiveDate = $isFond && in_array('archive_date', $model->level->field) ? true : false;
echo ($fondArchiveDate || !$isFond) ? $form->field($model, 'archive_date', ['options' => ['class'=>'form-group row field-item'.(!$isFond && (!isset($model->level) || !empty($model->level->child) || !in_array('archive_date', $model->level->field)) ? ' hide' : '').'']])
	->textInput(['maxlength'=>true])
	->label($model->getAttributeLabel('archive_date')) : ''; ?>

<hr/>

<?php
$fondRepository = $isFond && in_array('repository', $model->level->field) ? true : false;
$repositorySuggestUrl = Url::to(['setting/repository/suggest']);
echo ($fondRepository || !$isFond) ? $form->field($model, 'repository', ['options' => ['class'=>'form-group row field-item'.(!$isFond && (!isset($model->level) || !empty($model->level->child) || !in_array('repository', $model->level->field)) ? ' hide' : '')]])
    ->widget(Selectize::className(), [
        'options' => [
            'placeholder' => Yii::t('app', 'Select a repository..'),
        ],
        'items' => ArrayHelper::merge([''=>Yii::t('app', 'Select a repository..')], ArchiveRepository::getRepository(1)),
        'url' => $repositorySuggestUrl,
        'queryParam' => 'term',
        'pluginOptions' => [
            'valueField' => 'id',
            'labelField' => 'label',
            'searchField' => ['label'],
            'persist' => false,
            'createOnBlur' => false,
            'create' => true,
        ],
    ])
    ->label($model->getAttributeLabel('repository'))
    ->hint(Yii::t('app', 'Record the name of the organization which has custody of the archival material. Search for an existing name in the archival institution records by typing the first few characters of the name. Alternatively, type a new name to create and link to a new archival institution record.')) : ''; ?>

<?php
$fondCreator = $isFond && in_array('creator', $model->level->field) ? true : false;
$creatorSuggestUrl = Url::to(['setting/creator/suggest']);
echo ($fondCreator || !$isFond) ? $form->field($model, 'creator', ['options' => ['class'=>'form-group row field-item'.(!$isFond && (!isset($model->level) || !empty($model->level->child) || !in_array('creator', $model->level->field)) ? ' hide' : '')]])
    ->widget(Selectize::className(), [
        'cascade' => true,
        'url' => $creatorSuggestUrl,
        'queryParam' => 'term',
        'pluginOptions' => [
            'plugins' => ['remove_button'],
            'valueField' => 'label',
            'labelField' => 'label',
            'searchField' => ['label'],
            'persist' => false,
            'createOnBlur' => false,
            'create' => true,
        ],
    ])
    ->label($model->getAttributeLabel('creator'))
    ->hint(Yii::t('app', 'Record the name of the organization(s) or the individual(s) responsible for the creation, accumulation and maintenance of the records in the unit of description. Search for an existing name in the authority records by typing the first few characters of the name. Alternatively, type a new name to create and link to a new authority record.')) : ''; ?>

<div class="ln_solid field-item <?php echo (!$fondRepository && !$fondCreator && (!$isFond && (!isset($model->level) || !empty($model->level->child)))) ? 'hide' : '';?>"></div>

<?php
$fondSubject = $isFond && in_array('subject', $model->level->field) ? true : false;
$subjectSuggestUrl = Url::to(['/admin/tag/suggest']);
echo ($fondSubject || !$isFond) ? $form->field($model, 'subject', ['options' => ['class'=>'form-group row field-item'.(!$isFond && (!isset($model->level) || !empty($model->level->child) || !in_array('subject', $model->level->field)) ? ' hide' : '')]])
	->widget(Selectize::className(), [
        'cascade' => true,
		'url' => $subjectSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('subject')) : '';?>

<?php
$fondFunction = $isFond && in_array('function', $model->level->field) ? true : false;
echo ($fondFunction || !$isFond) ? $form->field($model, 'function', ['options' => ['class'=>'form-group row field-item'.(!$isFond && (!isset($model->level) || !empty($model->level->child) || !in_array('function', $model->level->field)) ? ' hide' : '')]])
	->widget(Selectize::className(), [
		'url' => $subjectSuggestUrl,
		'queryParam' => 'term',
		'pluginOptions' => [
			'valueField' => 'label',
			'labelField' => 'label',
			'searchField' => ['label'],
			'persist' => false,
			'createOnBlur' => false,
			'create' => true,
		],
	])
	->label($model->getAttributeLabel('function')) : '';?>

<div class="ln_solid field-item <?php echo (!$fondSubject && !$fondFunction && ($isFond || !$isFond && (!isset($model->level) || !empty($model->level->child)))) ? 'hide' : '';?>"></div>

<?php
$fondMedia = $isFond && in_array('media', $model->level->field) ? true : false;
echo ($fondMedia || !$isFond) ? $form->field($model, 'media', ['options' => ['class'=>'form-group row field-item'.(!$isFond && (!isset($model->level) || !empty($model->level->child) || !in_array('media', $model->level->field)) ? ' hide' : '')]])
	->widget(Selectize::className(), [
		'cascade' => true,
		'items' => ArchiveMedia::getMedia(1),
		'options' => [
			'multiple' => true,
		],
		'pluginOptions' => [
			'plugins' => ['remove_button'],
		],
	])
	->label($model->getAttributeLabel('media')) : '';?>

<?php
$fondArchiveType = $isFond && in_array('archive_type', $model->level->field) ? true : false;
$imageType = $model::getArchiveType();
echo ($fondArchiveType || !$isFond) ? $form->field($model, 'archive_type', ['options' => ['class'=>'form-group row field-item'.(!$isFond && (!isset($model->level) || !empty($model->level->child) || !in_array('archive_type', $model->level->field)) ? ' hide' : '')]])
    ->radioList($imageType, ['prompt'=>''])
    ->label($model->getAttributeLabel('archive_type')) : '';?>

<?php
$extension = pathinfo($model->old_archive_file, PATHINFO_EXTENSION);
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

$archiveFile = '';
if(!$model->isNewRecord && $model->old_archive_file != '') {
	if(in_array($extension, $imageFileType))
		$archiveFile = Html::img(Url::to(join('/', ['@webpublic', $uploadPath, $model->old_archive_file])), ['alt'=>$model->old_archive_file, 'class'=>'d-block border border-width-3 mb-3']).$model->old_archive_file.'<hr/>';
	if(in_array($extension, $documentFileType))
		$archiveFile = Html::a($model->old_archive_file, Url::to(join('/', ['@webpublic', $uploadPath, $model->old_archive_file])), ['title'=>$model->old_archive_file, 'class'=>'d-block mb-3', 'target'=>'_blank']);
}

$fondArchiveFile = $isFond && in_array('archive_file', $model->level->field) ? true : false;
echo ($fondArchiveFile || !$isFond) ? $form->field($model, 'archive_file', ['template'=> '{label}{beginWrapper}<div>'.$archiveFile.'</div>{input}{error}{hint}{endWrapper}', 'options' => ['class'=>'form-group row field-item'.(!$isFond && (!isset($model->level) || !empty($model->level->child) || !in_array('archive_file', $model->level->field)) ? ' hide' : '')]])
	->fileInput()
	->label($model->getAttributeLabel('archive_file')) : ''; ?>

<div class="ln_solid field-item <?php echo ($isFond || !(!$isFond && !isset($model->level) || !empty($model->level->child))) ? '' : 'hide';?>"></div>

<?php $publish = $model::getPublish();
echo $form->field($model, 'publish')
	->dropDownList($publish, ['prompt'=>''])
	->label($model->getAttributeLabel('publish')); ?>

<?php
$fondSidkkas = $isFond && in_array('sidkkas', $model->level->field) ? true : false;
echo ($setting->fond_sidkkas && ($fondSidkkas || !$isFond)) ? $form->field($model, 'sidkkas')
    ->checkbox()
    ->label($model->getAttributeLabel('sidkkas')) : '';?>

<hr/>

<?php $model->backToManage = 1;
echo $form->field($model, 'backToManage')
	->checkbox()
	->label($model->getAttributeLabel('backToManage')); ?>

<hr/>

<?php echo $form->field($model, 'submitButton')
	->submitButton(); ?>

<?php ActiveForm::end();

} else {?>
	<div class="bs-example" data-example-id="simple-jumbotron">
		<div class="jumbotron">
			<h1><?php echo $model->getAttributeLabel('level_id').': '.$parent->level->level_name_i;?></h1>
			<p><?php echo Yii::t('app', 'This level cannot add more child levels');?></p>
		</div>
	</div>
<?php }?>

</div>