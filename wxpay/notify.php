<?php

/**
 * 微信支付：回调通知文件
 *
 * @filename  notify.php
 * @author    F。
 * @version   2018-2-23
 * @modify    2018-2-23 10:11:33
 * @copyright © 2016-2018 copyright AA笔记
 */
require '../common/common.php';
require '../config/wxpay_config.php';
if (!empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
    $notify = new Notify_pub();
    $notify->saveData($GLOBALS['HTTP_RAW_POST_DATA']);
    $notify->setReturnParameter('return_code', 'FAIL');//返回状态码
    $notify->setReturnParameter('return_msg', '订单失败');//返回信息
    if (($notify->checkSign() == true) && ($notify->data['return_code'] == 'SUCCESS') && ($notify->data['result_code'] == 'SUCCESS')) {
        addLog('[pay]transaction_id:' . $notify->data['transaction_id'] . ',time:' . date('Y-m-d H:i:s', time()));
        $notify->setReturnParameter('return_code', 'SUCCESS');//返回状态码
        $notify->setReturnParameter('return_msg', '订单成功');//返回信息
    }
    echo $notify->returnXml();
}
