<?php

/**
 * 微信配置文件
 *
 * @filename  wxpay_config.php
 * @author    F。
 * @version   2018-2-23
 * @modify    2018-2-23 10:05:15
 * @copyright © 2016-2018 copyright AA笔记
 */
//微信回调地址
define('WX_NOTIFY', '/wxpay/notify.php');
//APPid
define('WX_APPID', '*****');
//APPSECRET
define('WX_APPSECRET', '*****');
//商户号
define('WX_MCHID', '*****');
//商户key
define('WX_MCHKEY', '*****');
//证书路径,注意应该填写绝对路径
define('WX_CENT_PATH', __DIR__ . '/cert/apiclient_cert.pem');
//证书秘钥路径,注意应该填写绝对路径
define('WX_CENTKEY_PATH', __DIR__ . '/cert/apiclient_key.pem');
require '../plugins/WxPay/WxPayPubHelper.php';
