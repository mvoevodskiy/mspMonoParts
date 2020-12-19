<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var mspMonoParts $mspMonoParts */
$mspMonoParts = $modx->getService('mspmonoparts', 'mspMonoParts', $modx->getOption('mspmonoparts_core_path', null,
        $modx->getOption('core_path') . 'components/mspmonoparts/') . 'model/mspmonoparts/'
);
$modx->lexicon->load('mspmonoparts:default');

// handle request
$corePath = $modx->getOption('mspmonoparts_core_path', null, $modx->getOption('core_path') . 'components/mspmonoparts/');
$path = $modx->getOption('processorsPath', $mspMonoParts->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));