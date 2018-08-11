<?php
/**
 * 微信支付：扫码支付文件
 *
 * @filename  nativeshort.php
 * @author    F。
 * @version   2018-2-23
 * @modify    2018-2-23 10:07:30
 * @copyright © 2016-2018 copyright AA笔记
 */
require __DIR__ . '/../common/common.php';
require __DIR__ . '/../config/wxpay_config.php';
$pay_url = '';
$out_trade_no = getOrder('N');
//使用统一支付接口，获取prepay_id
$native_order = new UnifiedOrder_pub();
$native_order->setParameter('body', '扫码模式二支付_支付demo_AA笔记');//商品描述
$native_order->setParameter('out_trade_no', $out_trade_no);//商户订单号
$native_order->setParameter('total_fee', 1);//总金额
$native_order->setParameter('notify_url', SITE_NAME . WX_NOTIFY);//通知地址
$native_order->setParameter('trade_type', 'NATIVE');//交易类型
$native_order->setParameter('product_id', $out_trade_no);//商品id
$native_order->setParameter('spbill_create_ip', $_SERVER['REMOTE_ADDR']);
$order_result = $native_order->getResult();
if ($order_result['return_code'] == 'SUCCESS' && $order_result['result_code'] == 'SUCCESS') {
    $pay_url = $order_result['code_url'];
}
?>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
        <title>微信支付样例_扫码支付模式二</title>
    </head>
    <body>
        <div align="center">
            <div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">扫描支付模式二</div><br/>
            <div id="pay_qrcode" data="<?php echo $pay_url;?>"></div><br>
            <input type="button" value="返回" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="location='<?php echo SITE_NAME;?>'" />
        </div>
    </body>
    <script type="text/javascript" src="/plugins/QrCode/jquery.min.js"></script>
    <script type="text/javascript" src="/plugins/QrCode/jquery.qrcode.min.js"></script>
    <script>
        $('#pay_qrcode').html('').qrcode({width:280, height:280, text: $('#pay_qrcode').attr('data')});
    </script>
</html>
