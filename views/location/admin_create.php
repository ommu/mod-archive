<?php
/**
 * Archive Locations (archive-location)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\location\AdminController
 * @var $model ommu\archive\models\ArchiveLocation
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 08:42 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inventory'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Location'), 'url' => ['location/admin/index']];
if(isset($model->parent)) {
	$controller = $model->parent->type;
	if($controller == 'building')
		$controller = 'admin';
	$this->params['breadcrumbs'][] = ['label' => $model->getAttributeLabel('parent_id').': '.$model->parent->location_name, 'url' => ['location/'.$controller.'/view', 'id'=>$model->parent_id]];
}
$this->params['breadcrumbs'][] = ['label' => $model->getAttributeLabel('location_name'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-location-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
