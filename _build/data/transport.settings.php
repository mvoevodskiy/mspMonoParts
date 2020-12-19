<?php
/** @var modX $modx */
/** @var array $sources */

$settings = array();

$tmp = array(
//    'get_param' => array(
//        'xtype' => 'textfield',
//        'value' => 'monoParts',
//        'area' => 'mspmonoparts_main',
//    ),
    'bank_url' => array(
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'mspmonoparts_main',
    ),
    'store_id' => array(
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'mspmonoparts_main',
    ),
    'sign_key' => array(
        'xtype' => 'textfield',
        'value' => '',
        'area' => 'mspmonoparts_main',
    ),
    'webhook' => array(
        'xtype' => 'textfield',
        'value' => '/assets/components/mspmonoparts/payment.php',
        'area' => 'mspmonoparts_main',
    ),
    'min_parts' => array(
        'xtype' => 'numberfield',
        'value' => 3,
        'area' => 'mspmonoparts_main',
    ),
    'max_parts' => array(
        'xtype' => 'numberfield',
        'value' => 12,
        'area' => 'mspmonoparts_main',
    ),
);

foreach ($tmp as $k => $v) {
    /** @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        array(
            'key' => 'mspmp_' . $k,
            'namespace' => PKG_NAME_LOWER,
        ), $v
    ), '', true, true);

    $settings[] = $setting;
}
unset($tmp);

return $settings;
