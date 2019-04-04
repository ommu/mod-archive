<?php
/**
 * Archive Creators (archive-creator)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\CreatorController
 * @var $model ommu\archive\models\ArchiveCreator
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 4 April 2019, 15:05 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Creators'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<div class="archive-creator-create">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>
