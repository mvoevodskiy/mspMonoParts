<?php
/** @var xPDOTransport $transport */
/** @var array $options */
if ($transport->xpdo) {
    /** @var modX $modx */
    $modx =& $transport->xpdo;

    /** @var miniShop2 $miniShop2 */
    if (!$miniShop2 = $modx->getService('miniShop2')) {
        $modx->log(modX::LOG_LEVEL_ERROR, '[mspMonoParts] Could not load miniShop2');

        return false;
    }
    if (!property_exists($miniShop2, 'version') || version_compare($miniShop2->version, '2.4.0-pl', '<')) {
        $modx->log(modX::LOG_LEVEL_ERROR,
            '[mspMonoParts] You need to upgrade miniShop2 at least to version 2.4.0-pl');

        return false;
    }

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $miniShop2->addService('payment', 'mspMonoParts', '{core_path}components/mspmonoparts/model/payment.class.php');
            /** @var msPayment $payment */
            if (!$payment = $modx->getObject('msPayment', ['class' => 'MonoParts'])) {
                $payment = $modx->newObject('msPayment');
                $payment->fromArray([
                    'name' => 'Mono Parts',
                    'active' => false,
                    'class' => 'MonoParts',
                    'rank' => $modx->getCount('msPayment'),
                    'logo' => MODX_ASSETS_URL . 'components/mspmonoparts/logo.png',
                ], '', true);
                $payment->save();
            }
            break;

        case xPDOTransport::ACTION_UNINSTALL:
            $miniShop2->removeService('payment', 'MonoParts');
            $modx->removeCollection('msPayment', ['class' => 'MonoParts']);
            break;
    }
}
return true;