<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var msOrder $msOrder */
switch ($modx->event->name) {

    case 'msOnBeforeCreateOrder':
        $payment = $msOrder->Payment;
        if ($payment->get('class') === 'MonoParts') {

            /** @var mspMonoParts $mspmp */
            if (!$mspmp = $modx->getService('mspmonoparts', 'mspMonoParts', $modx->getOption('mspmonoparts_core_path', null,
                    $modx->getOption('core_path') . 'components/mspmonoparts/') . 'model/mspmonoparts/', $scriptProperties)
            ) {
                return 'Could not load mspMonoParts class!';
            }
            $phone = !empty($msOrder->UserProfile->phone) ? $msOrder->UserProfile->phone : $msOrder->UserProfile->mobilephone;
            $phone = $mspmp->normalizePhone($phone);
            if ($phone === false) {
                return $modx->lexicon('mspmonoparts_incorrect_phone');
            }

            $countParts = $modx->getOption('mspMonoParts_count', $_POST, $modx->getOption('mspmp_min_parts'));
            $props = $msOrder->get('properties');
            $props['mspmp'] = ['countParts' => $countParts];
            $msOrder->set('properties', $props);
        }

}