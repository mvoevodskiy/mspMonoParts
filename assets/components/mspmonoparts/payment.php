<?php

/** @var modX $modx */
define('MODX_API_MODE', true);
/** @noinspection PhpIncludeInspection */
if (file_exists(dirname(dirname(dirname(__FILE__))) . '/index.php')) {
    require_once dirname(dirname(dirname(__FILE__))) . '/index.php';
} elseif (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php')) {
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
} elseif (file_exists(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/index.php')) {
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
} elseif (file_exists(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/index.php')) {
    require_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/index.php';
}
//require dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
$modx->getService('error', 'error.modError');
/** @var mspMonoParts $mspmp */
if (!$mspmp = $modx->getService('mspmonoparts', 'mspMonoParts', $modx->getOption('mspmonoparts_core_path', null,
        $modx->getOption('core_path') . 'components/mspmonoparts/') . 'model/mspmonoparts/')
) {
    $modx->log(xpdo::LOG_LEVEL_FATAL, 'Could not load mspMonoParts class!');
}

$modx->log(1, 'WEBHOOK FOR MONO BANK PARTS PAYMENT');

$bankResponse = $modx->fromJSON(file_get_contents('php://input'));
$orderId = $bankResponse['order_id'];
$modx->log(1, 'WEBHOOK FOR MONO BANK PARTS PAYMENT. INPUT ' . print_r($bankResponse, 1));
//$modx->log(1, 'WEBHOOK FOR MONO BANK PARTS PAYMENT. INPUT ' . print_r($bankResponse, 1));

$modx->error->message = null;
/** @var miniShop2 $miniShop2 */
/** @var mspMonoPartsOrder $record */
$miniShop2 = $modx->getService('miniShop2');
$miniShop2->loadCustomClasses('payment');
if (!class_exists('MonoParts')) {
    $modx->log(0, 'Error: could not load payment class "MonoParts".');
    exit('Error: could not load payment class "MonoParts".');
} elseif (empty($orderId)) {
    $modx->log(0, 'Error: the order id is not specified.');
    exit('Error: the order id is not specified.');
//} elseif (!) {
//    $modx->log(0, 'Error: could not load specified order.');
//    exit('Error: could not load specified order.');
}

if ($mspmp->changeOrderStatus($bankResponse)) {
    exit('SUCCESS');
}
exit('Error: unknown');
