<?php
/**
 * Archives (archives)
 * @var $this app\components\View
 * @var $this ommu\archive\controllers\AdminController
 * @var $model ommu\archive\models\Archives
 * @var $form app\components\ActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 6 March 2019, 09:19 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');

$referenceCode = $parent->referenceCode;
array_multisort($referenceCode);
?>

<div class="archives-create">

<?php echo $this->render('_form', [
	'model' => $model,
	'setting' => $setting,
	'fond' => $fond,
	'parent' => $parent,
	'referenceCode' => $referenceCode,
]); ?>

</div>
