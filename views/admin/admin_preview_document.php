<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2020 OMMU (www.ommu.id)
 * @created date 3 March 2020, 09:52 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php
$setting = $model->getSetting(['image_type', 'document_type', 'maintenance_mode', 'maintenance_document_path', 'maintenance_image_path']);
$extension = pathinfo($model->old_archive_file, PATHINFO_EXTENSION);
$imageFileType = $model->formatFileType($setting->image_type);
$documentFileType = $model->formatFileType($setting->document_type);
$isDocument = in_array($extension, $documentFileType) ? true : false;

if ($model->isNewFile) {
    $uploadPath = join('/', [$model::getUploadPath(), $model->id]);
} else {
    $uploadPath = join('/', [$model::getUploadPath(), ($isDocument == true ? $setting->maintenance_document_path : $setting->maintenance_image_path)]);
}
$fileExists = $model->archive_file != '' && file_exists(join('/', [$uploadPath, $model->archive_file])) ? true : false;

if ($model->archive_file && $fileExists) {
    if ($model->isNewFile) {
        $uploadPath = join('/', [$model::getUploadPath(false), $model->id]);
    } else {
        $uploadPath = join('/', [$model::getUploadPath(false), ($isDocument == true ? $setting->maintenance_document_path : $setting->maintenance_image_path)]);
    }
    $filePath = Url::to(join('/', ['@webpublic', $uploadPath, $model->archive_file]));

    if ($isDocument == true) {
        echo \app\components\widgets\PreviewPDF::widget([
            'url' => $filePath,
            'navigationOptions' => ['class' => 'summary mb-4'],
            'previewOptions' => ['class' => 'preview-pdf border border-width-3'],
        ]);

    } else {
        echo Html::img($filePath, ['alt' => $model->archive_file, 'class' => 'mb-4']).'<br/>'.$model->archive_file;
    }

} else { ?>
	<div class="bs-example" data-example-id="simple-jumbotron">
		<div class="jumbotron">
			<h1><?php echo $model->archive_file ? Yii::t('app', 'Archive document not found') : Yii::t('app', 'Archive document not available');?></h1>
			<?php echo $model->archive_file ? Html::tag('p', $model->archive_file) : '';?>
		</div>
	</div>
<?php }?>