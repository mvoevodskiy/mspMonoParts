<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/mspMonoParts/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/mspmonoparts')) {
            $cache->deleteTree(
                $dev . 'assets/components/mspmonoparts/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/mspmonoparts/', $dev . 'assets/components/mspmonoparts');
        }
        if (!is_link($dev . 'core/components/mspmonoparts')) {
            $cache->deleteTree(
                $dev . 'core/components/mspmonoparts/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/mspmonoparts/', $dev . 'core/components/mspmonoparts');
        }
    }
}

return true;
