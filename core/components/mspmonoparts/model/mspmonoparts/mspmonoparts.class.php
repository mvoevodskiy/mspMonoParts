<?php

class mspMonoParts
{
    /** @var modX $modx */
    public $modx;
    /**
     * @var array
     */
    public $config;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('mspmonoparts_core_path', $config,
            $this->modx->getOption('core_path') . 'components/mspmonoparts/'
        );
        $assetsUrl = $this->modx->getOption('mspmonoparts_assets_url', $config,
            $this->modx->getOption('assets_url') . 'components/mspmonoparts/'
        );
        $connectorUrl = $assetsUrl . 'connector.php';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $connectorUrl,

            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/',

            'minParts' => (int)$this->modx->getOption('mspmp_min_parts'),
            'maxParts' => (int)$this->modx->getOption('mspmp_max_parts'),
            'frontendJS' => $this->modx->getOption('mspmp_frontend_js', null,
                '/assets/components/mspmonoparts/js/default.js'),

            'paymentUrl' => MODX_ASSETS_URL . 'components/mspmonoparts/payment.php',
            'bankUrl' => $this->modx->getOption('mspmp_bank_url', null, $this->modx->getOption('site_start'), true),
            'successId' => $this->modx->getOption('mspmp_success_id', null, $this->modx->getOption('site_start'), true),
            'storeId' => $this->modx->getOption('mspmp_store_id', null, ''),
            'signKey' => $this->modx->getOption('mspmp_sign_key', null, ''),
            'uri' => [
                'create' => '/api/order/create',
                'redirect' => 'https://payparts2.privatbank.ua/ipp/v2/payment?token=',
                'callback' => 'https://payparts2.privatbank.ua/ipp/v2/payment/callback',
                'confirm' => 'https://payparts2.privatbank.ua/ipp/v2/payment/confirm',
                'cancel' => 'https://payparts2.privatbank.ua/ipp/v2/payment/cancel'
            ],
            'orderStatuses' => [
                'paid' => 2,
                'cancelled' => 4,
            ]
        ), $config);

        $this->modx->addPackage('mspmonoparts', $this->config['modelPath']);
        $this->modx->lexicon->load('mspmonoparts:default');
    }

    /**
     * @param msOrder $order
     *
     * @return string
     */
    public function create(msOrder $order)
    {
//        if (!$order->Payment->phone) {
//            return false;
//        }
        $payment = null;
        /** @var mspMonoPartsOrder $record */
        $record = $this->modx->getObject('mspMonoPartsOrder', ['id' => $order->id]);
        if (empty($record)) {
            try {
                $orderId = $order->id . '_' . time();
                $props = $order->get('properties');
                $countParts = ($props['mspmp'] && $props['mspmp']['countParts']) ? $props['mspmp']['countParts'] : $this->config['minParts'];
                $responseUrl = rtrim($this->modx->getOption('site_url'), '/') . $this->config['paymentUrl'];
                $products = [];

                foreach ($order->Products as $product) {
                    $products[] = [
                        'name' => $product->get('name'),
                        'sum' => round($product->get('price'), 2),
                        'count' => $product->get('count')
                    ];
                }
                $counts = [];
                $phone = !empty($order->UserProfile->phone) ? $order->UserProfile->phone : $order->UserProfile->mobilephone;

                $requestBody = [
                    'store_order_id' => $this->config['storeId'],
                    'orderId' => $orderId,
                    'client_phone' => $this->normalizePhone($phone),
                    'total_sum' => round($order->get('cost'), 2),
                    'result_callback' => $responseUrl,
                    'invoice' => [
                        'date' => date('Y-m-d', strtotime($order->createdon)),
                        'number' => $order->get('num'),
                        'source' => 'INTERNET'
                    ],
                    'products' => $products,
                    'available_programs' => [
                        [
                            'available_parts_count' => [$countParts],
                            'type' => 'payment_installments'
                        ],
                    ]
                ];
//                $this->modx->log(1, 'GET PAYMENT LINK. REQUEST BODY' . print_r($requestBody, 1));

                $response = $this->sendPost($requestBody, $this->config['uri']['create']);
                $payment = $this->modx->fromJSON($response);
                $uid = $payment['order_id'];
//                $this->modx->log(1, 'CREATE. RESPONSE ' . print_r($payment, 1) . ', UID ' . $uid);

                $record = $this->modx->newObject('mspMonoPartsOrder');
                $record->set('id', $order->id);
                $record->set('order_id', $orderId);
                $record->set('uid', $uid);
                $record->save();

//                $this->modx->log(1, 'GET PAYMENT LINK. RECORD FIELDS' . print_r($record->toArray(), 1));
            } catch (Exception $e) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR,
                    '[mspMonoParts] Error on create payment with API: ' . $e->getMessage());
            }
        }

        return true;
    }

    public function changeOrderStatus ($bankOrderStatus)
    {
        $status = 0;
        /** @var miniShop2 $ms2 */
        $ms2 = $this->modx->getService('minishop2');
        $monoOrder = $this->modx->getObject('mspMonoPartsOrder', ['uid' => $bankOrderStatus['order_id']]);
        if ($bankOrderStatus['state'] == 'SUCCESS' && ($bankOrderStatus['order_sub_state'] == 'ACTIVE' || $bankOrderStatus['order_sub_state'] == 'DONE')) {
            $status = $this->config['orderStatuses']['paid'];
        }
        if ($bankOrderStatus['state'] == 'FAIL' || ($bankOrderStatus['state'] == 'SUCCESS' && $bankOrderStatus['order_sub_state'] == 'RETURNED')) {
            $status = $this->config['orderStatuses']['cancelled'];
        }
//        $this->modx->log(1, 'CHANGING STATUS FOR ORDER ' . $monoOrder->get('id') . ' TO ' . $status);
        return $ms2->changeOrderStatus($monoOrder->get('id'), $status);
    }

    /**
     * Send POST
     *
     * @param $params
     * @param $uri
     *
     * @return mixed
     */
    public function sendPost($params, $uri)
    {
        $request_string = $this->modx->toJSON($params);
        $this->modx->log(1, 'MONO PARTS. SEND POST. REQUEST ' . $request_string);
        $signature = base64_encode(hash_hmac("sha256", $request_string, $this->config['signKey'], true));
        $url = $this->config['bankUrl'] . $uri;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'store-id: ' . $this->config['storeId'],
            'signature: ' . $signature,
            'Content-Type: application/json',
            'my-header: a66b0275-9872-4fa2-9489-d91b085495a4',
            'Accept: application/json'
        ));

        $server_output = curl_exec($ch);

//        echo $ch;
//        echo $server_output;

        curl_close($ch);
        $this->modx->log(1, 'MONO PARTS. SEND POST. URL ' . $url);
        $this->modx->log(1, 'MONO PARTS. SEND POST. SIGNATURE ' . $signature);
        $this->modx->log(1, 'MONO PARTS. SEND POST. RESPONSE ' . print_r($server_output, 1));

        return $server_output;
    }

    /**
     * @param $basePhone
     *
     * @return false|int|string
     */
    public function normalizePhone($basePhone)
    {
        $phone = (int) preg_replace("#[^\d]#", "", $basePhone);
        /** Добавление кода страны +380 для номеров без кода  */
        if (000000000 <= $phone and $phone <= 999999999) {
            $phone += 380000000000;
        }

        $phone =  '+' . (string)$phone;
        return preg_match('/\+380\d{9}/', $phone) ? $phone : false;
    }

}