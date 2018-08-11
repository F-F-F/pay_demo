<?php

/**
 * 微信支付：H5支付文件
 *
 * @filename  html.php
 * @author    F。
 * @version   2018-2-23
 * @modify    2018-2-23 11:07:15
 * @copyright © 2016-2018 copyright AA笔记
 */
require '../common/common.php';
require '../config/wxpay_config.php';
$js_api_parameters = '';
//微信统一下单
$input = new UnifiedOrder_pub();
$input->setParameter('body', 'H5支付_支付demo_AA笔记');
$input->setParameter('out_trade_no', getOrder('H'));
$input->setParameter('total_fee', 1);
$input->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);
$input->setParameter('notify_url', SITE_NAME . '/pay/notify.php');
$input->setParameter('trade_type', 'MWEB');
$input->setParameter('scene_info', '{"h5_info": {"type":"Wap","wap_url": "' . SITE_NAME . '","wap_name": "微信支付demo_AA笔记"}}');
$result = $input->getResult();
var_dump($result);
die;
if (!empty($result['return_code']) && !empty($result['result_code']) && ($result['return_code'] == 'SUCCESS') && ($result['result_code'] == 'SUCCESS')) {
    header('Location:' . $result['mweb_url']);
}


