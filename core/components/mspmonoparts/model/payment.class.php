<?php

if (!class_exists('msPaymentInterface')) {
    /** @noinspection PhpIncludeInspection */
    if (file_exists(dirname(dirname(dirname(__FILE__))) . '/minishop2/model/minishop2/mspaymenthandler.class.php')) {
        require_once dirname(dirname(dirname(__FILE__))) . '/minishop2/model/minishop2/mspaymenthandler.class.php';
    } elseif (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/minishop2/model/minishop2/mspaymenthandler.class.php')) {
        require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/minishop2/model/minishop2/mspaymenthandler.class.php';
    } elseif (file_exists(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/minishop2/model/minishop2/mspaymenthandler.class.php')) {
        require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/minishop2/model/minishop2/mspaymenthandler.class.php';
    }
}

class MonoParts extends msPaymentHandler implements msPaymentInterface
{
    /** @var modX $modx */
    public $modx;
    /** @var array $config */
    public $config;
    /** @var mspMonoParts */
    public $mspmp;


    /**
     * @param xPDOObject $object
     * @param array $config
     */
    function __construct(xPDOObject $object, $config = [])
    {
        parent::__construct($object, $config);


        if (!$this->mspmp = $this->modx->getService('mspmonoparts', 'mspMonoParts', $this->modx->getOption('mspmonoparts_core_path', null,
                $this->modx->getOption('core_path') . 'components/mspmonoparts/') . 'model/mspmonoparts/')
        ) {
            return 'Could not load mspMonoParts class!';
        }
        $this->config = array_merge([
            // default payment config
        ], $this->mspmp->config, $config);

//        $this->modx->addPackage('mspmonoparts', MODX_CORE_PATH. 'components/mspmonoparts/model');
//        $this->modx->loadClass('mspMonoPartsOrder', MODX_CORE_PATH. 'components/mspmonoparts/model/mspmonoparts');
    }


    /**
     * @param msOrder $order
     *
     * @return array|string
     */
    public function send(msOrder $order)
    {
        $this->mspmp->create($order);
        return $this->success('', array('msorder' => $order->get('id')));
    }


    /**
     * @param msOrder $order
     * @param int $status
     *
     * @return bool
     */
    public function receive(msOrder $order, $status = 2)
    {
        if ($order->get('status') == $status) {
            return true;
        }
        /* @var miniShop2 $miniShop2 */
        $miniShop2 = $this->modx->getService('miniShop2');
        $ctx = $order->get('context');
        if ($ctx != 'web') {
            $this->modx->switchContext($ctx);
        }

        return $miniShop2->changeOrderStatus($order->id, $status);
    }

    /**
     * Send POST
     *
     * @param $param
     * @param $url
     * @return mixed
     */
    private function sendPost($param, $url)
    {
        return $this->mspmp->sendPost($param, $url);
    }

    /**
     * @param $array
     * @return string
     */
    private function calcSignature($array)
    {
        $signature = '';
        foreach ($array as $item) {
            $signature .= $item;
        }

//        $this->modx->log(1, 'CALC SIGN ' . $signature);
        return base64_encode(sha1($signature, true));

    }

}
