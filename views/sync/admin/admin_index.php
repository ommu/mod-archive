<?php
/**
 * @var $this app\components\View
 * @var $this ommu\archivePengolahan\controllers\sync\AdminController
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 30 December 2022, 15:26 WIB
 * @link https://bitbucket.org/ommu/archive
 *
 */

use yii\helpers\Html;
use yii\helpers\Url;

\app\assets\CentrifugeAsset::register($this);
$js = <<<JS
    const sub = centrifuge.newSubscription('devtool');

    sub.on('publication', function (ctx) {
        let el = $('#modalBroadcast');
        el.find('.log-content pre.preformat').html('');
        el.find('.log-content pre.preformat').append(ctx.data.message);
    }).on('subscribing', function (ctx) {
        console.log('subscribing: ' + ctx.code + ', ' + ctx.reason);
    }).on('subscribed ', function (ctx) {
        console.log('subscribed ', ctx);
    }).on('unsubscribed', function (ctx) {
        console.log('unsubscribed: ' + ctx.code + ', ' + ctx.reason);
    }).subscribe();
JS;
$this->registerJs($js, $this::POS_END);

$context = $this->context;
if ($context->breadcrumbApp) {
	$this->params['breadcrumbs'][] = ['label' => $context->breadcrumbAppParam['name'], 'url' => [$context->breadcrumbAppParam['url']]];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Setting'), 'url' => ['setting/admin/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo Html::a(Yii::t('app', 'Sync FondID'), Url::to(['fond']), ['class' => 'btn btn-primary modal-btn', 'data-target'=> 'modalBroadcast'])?>

