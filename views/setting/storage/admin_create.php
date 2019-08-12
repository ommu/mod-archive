<?php
/**
 * Archive Storages (archive-storage)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\StorageController
 * @var $model ommu\archive\models\ArchiveStorage
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 8 April 2019, 17:04 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Storages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-storage-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
