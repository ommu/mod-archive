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
use yii\helpers\ArrayHelper;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$this->params['menu']['content'] = [
	['label' => Yii::t('app', 'Back to Inventaris'), 'url' => Url::to(['index']), 'icon' => 'tasks', 'htmlOptions' => ['class'=>'btn btn-success btn-sm']],
];
if($setting->maintenance_mode) {
	$this->params['menu']['content'] = ArrayHelper::merge(
		$this->params['menu']['content'], 
		[
			['label' => Yii::t('app', 'Show Reference Code Parameters'), 'url' => 'javascript:void(0);', 'icon' => 'code', 'htmlOptions' => ['class'=>'btn btn-warning btn-sm', 'id'=>'reference-code']],
		]);
}

$referenceCode = $model->referenceCode;
array_multisort($referenceCode);
?>

<div class="archives-update">

<?php echo $this->render('_form', [
	'model' => $model,
	'setting' => $setting,
	'fond' => $fond,
	'referenceCode' => $referenceCode,
]); ?>

</div>