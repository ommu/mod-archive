<?php
/**
 * Archive Lurings (archive-lurings)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\luring\AdminController
 * @var $model ommu\archive\models\ArchiveLurings
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
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
    $senaraiLabel = $archive->isFond ? Yii::t('app', 'Senarai') : Yii::t('app', 'Inventory');
    $senaraiUrl = $archive->isFond ? ['fond/index'] : ['admin/index'];
    if ($isPengolahan) {
        $senaraiLabel = Yii::t('app', 'Senarai');
        $senaraiUrl = ['luring/admin/index'];
    }
    $this->params['breadcrumbs'][] = ['label' => $senaraiLabel, 'url' => $senaraiUrl];
    $senaraiDetailLabel = $archive->isFond ? $archive->code : Yii::t('app', '#{level-name} {code}', ['level-name' => strtoupper($archive->levelTitle->message), 'code' => $archive->code]);
    $senaraiDetailUrl = $archive->isFond ? ['fond/view', 'id' => $archive->id] : ['admin/view', 'id' => $archive->id];
    if ($isPengolahan) {
        $senaraiDetailUrl = ['luring/admin/view', 'id' => $archive->id];
    }
    $this->params['breadcrumbs'][] = ['label' => $senaraiDetailLabel, 'url' => $senaraiDetailUrl];
    $this->params['breadcrumbs'][] = ['label' => $isPengolahan ? Yii::t('app', 'Document') : Yii::t('app', 'Luring'), 'url' => ['manage', 'archive' => $archive->id]];
} else {
    $this->params['breadcrumbs'][] = ['label' => $isPengolahan ? Yii::t('app', 'Documents') : Yii::t('app', 'Lurings'), 'url' => ['index']];
}
$this->params['breadcrumbs'][] = Yii::t('app', 'Generate');
?>

<div class="archive-lurings-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
