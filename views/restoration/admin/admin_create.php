<?php
/**
 * Archive Restorations (archive-restoration)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\restoration\AdminController
 * @var $model ommu\archive\models\ArchiveRestoration
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 24 July 2023, 01:22 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Url;

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restoration'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-restoration-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
