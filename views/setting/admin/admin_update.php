<?php
/**
 * Archive Settings (archive-setting)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\setting\AdminController
 * @var $model ommu\archive\models\ArchiveSetting
 * @var $form app\components\widgets\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 5 March 2019, 23:53 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = Yii::t('app', 'Archive Settings');
?>

<div class="archive-setting-update">

<?php echo $this->render('_form', [
	'model' => $model,
]); ?>

</div>