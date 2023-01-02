<?php
/**
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\sync\AdminController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 30 December 2022, 15:26 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="alert alert-danger hide" role="alert"></div>

<?php echo Html::beginForm(['fond'], 'POST', ['class' => 'form-horizontal form-label-left']);?>

<div class="form-group row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <?php echo Html::textInput('limit', '', ['class' => 'form-control mb-5', 'placeholder' => Yii::t('app', 'Limit')]);?>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php echo Html::submitButton(Yii::t('app', 'Run..'), ['class'=>'btn btn-primary']);?>
    </div>
</div>

<?php echo Html::endForm(); ?>

<div class="log-content">
    <hr/>
    <pre class="preformat"></pre>
</div>