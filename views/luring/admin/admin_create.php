<?php
/**
 * Archive Lurings (archive-lurings)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\luring\AdminController
 * @var $model ommu\archive\models\ArchiveLurings
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 October 2022, 23:20 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Url;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
if ($archive) {
    $this->params['breadcrumbs'][] = ['label' => $archive->isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Inventory'), 'url' => $archive->isFond ? ['fond/index'] : ['admin/index']];
    $archiveDetailUrl = $archive->isFond ? ['fond/view', 'id' => $archive->id] : ['admin/view', 'id' => $archive->id];
    $this->params['breadcrumbs'][] = ['label' => $archive->isFond ? $archive->code : Yii::t('app', '#{level-name} {code}', ['level-name' => strtoupper($archive->levelTitle->message), 'code' => $archive->code]), 'url' => $archiveDetailUrl];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Luring'), 'url' => ['manage', 'archive' => $archive->id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Luring'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = Yii::t('app', 'Generate');
?>

<div class="archive-lurings-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
