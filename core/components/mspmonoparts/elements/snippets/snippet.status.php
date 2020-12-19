<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var mspMonoParts $mspmp */
if (!$mspmp = $modx->getService('mspmonoparts', 'mspMonoParts', $modx->getOption('mspmonoparts_core_path', null,
        $modx->getOption('core_path') . 'components/mspmonoparts/') . 'model/mspmonoparts/', $scriptProperties)
) {
    return 'Could not load mspMonoParts class!';
}
/** @var pdoFetch $pdoTools */
$pdoTools = $modx->getService('pdotools');
/** @var miniShop2 $ms2 */
$ms2 = $modx->getService('minishop2');
$ms2->initialize();

// Do your snippet code here. This demo grabs 5 items from our custom table.
$tpl = $modx->getOption('tpl', $scriptProperties, 'mspMonoParts.select');
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

// Output
$output = $pdoTools->getChunk($tpl, ['min' => $mspmp->config['minParts'], 'max' => $mspmp->config['maxParts']]);
if (!empty($toPlaceholder)) {
    // If using a placeholder, output nothing and set output to specified placeholder
    $modx->setPlaceholder($toPlaceholder, $output);

    return '';
}

$payments = [];
$dbPmnts = $modx->getCollection('msPayment', ['class' => 'MonoParts']);
foreach ($dbPmnts as $dbPmnt) {
    $payments[] = $dbPmnt->get('id');
}
$fields = $ms2->order->get();


$config = [
    'payments' => $payments,
    'show' => in_array($fields['payment'], $payments)
];

$modx->regClientScript('<script type="text/javascript">var mspmpConfig = ' . $modx->toJSON($config) . '</script>');
$modx->regClientScript($mspmp->config['frontendJS']);

// By default just return output
return $output;
