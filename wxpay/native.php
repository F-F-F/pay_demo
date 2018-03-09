<?php
/**
 * 微信支付：扫码支付文件
 *
 * @filename  native.php
 * @author    F。
 * @version   2018-2-23
 * @modify    2018-2-23 10:07:30
 * @copyright © 2016-2018 copyright AA笔记
 */
require '../common/common.php';
require '../config/wxpay_config.php';
//生成二维码
if (empty($GLOBALS['HTTP_RAW_POST_DATA'])) {
    $out_trade_no = getOrder('N');
    //生成扫码长链接，后续生成短链接
    $native_link = new NativeLink_pub();
    $native_link->setParameter('product_id', $out_trade_no);//商品id
    //1、长链接可通过静态js直接生成静态码，用于永久二维码使用，故永久二维码可忽略生成短链接；
    $long_url = $native_link->getUrl();
    //注：使用长链接生成的二维码需要对链接进行urlencode
    //$pay_url = urlencode($long_url);
    //2、请求微信获取短链接
    $pay_url = (new ShortUrl_pub())->getShortUrl($long_url);
    ?>
    <html>
        <head>
            <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
            <title>微信支付样例_扫码支付模式一</title>
        </head>
        <body>
            <div align="center">
                <div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">扫描支付模式一（需要在商户中心后台填写回调地址）</div><br/>
                <img alt="模式一扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo $pay_url;?>" style="width:300px;height:300px;"/><br/>
                <input type="button" value="返回" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="location='<?php echo SITE_NAME;?>'" />
            </div>
        </body>
    </html>
    <?php
} else {
    //扫码回调，并验证签名
    $native_call = new NativeCall_pub();
    $native_call->saveData($GLOBALS['HTTP_RAW_POST_DATA']);
    $native_call->setReturnParameter('return_code', 'FAIL');//返回状态码
    $native_call->setReturnParameter('return_msg', '签名失败');//返回信息
    if ($native_call->checkSign() == true) {
        //统一下单
        $out_trade_no = getOrder('N');
        $native_order = new UnifiedOrder_pub();
        $native_order->setParameter('body', '扫码模式一支付_支付demo_AA笔记');//商品描述
        $native_order->setParameter('out_trade_no', $out_trade_no);//商户订单号
        $native_order->setParameter('total_fee', 1);//总金额
        $native_order->setParameter('notify_url', SITE_NAME . WX_NOTIFY);//通知地址
        $native_order->setParameter('trade_type', 'NATIVE');//交易类型
        $native_order->setParameter('product_id', $out_trade_no);//商品id
        $native_order->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);
        $pre_payid = $native_order->getPrepayId();
        //下单成功之后返回给微信预支付id
        if ($pre_payid) {
            $native_call->setReturnParameter('return_code', 'SUCCESS');//返回状态码
            $native_call->setReturnParameter('result_code', 'SUCCESS');//返回业务结果
            $native_call->setReturnParameter('prepay_id', $pre_payid);
        }
    }
    echo $native_call->returnXml();
}