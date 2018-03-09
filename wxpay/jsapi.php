<?php
/**
 * 微信支付：JSAPI支付文件
 *
 * @filename  jsapi.php
 * @author    F。
 * @version   2018-2-23
 * @modify    2018-2-23 10:07:25
 * @copyright © 2016-2018 copyright AA笔记
 */
require '../common/common.php';
require '../config/wxpay_config.php';
$js_api_parameters = '';
$js_api = new JsApi_pub();
if ($_GET['code']) {
    $call_result = $js_api->getOpenid($_GET['code']);
    if (!empty($call_result['openid']) && !empty($call_result['access_token'])) {
        //微信统一下单
        $input = new UnifiedOrder_pub();
        $input->setParameter('body', 'JSAPI支付_支付demo_AA笔记');
        $input->setParameter('out_trade_no', getOrder('J'));
        $input->setParameter('total_fee', 1);
        $input->setParameter('notify_url', SITE_NAME . WX_NOTIFY);
        $input->setParameter('trade_type', 'JSAPI');
        $input->setParameter('openid', $call_result['openid']);
        //设置全局prepay_id
        $pre_payid = $input->getPrepayId();
        if ($pre_payid) {
            $js_api->setPrepayId($pre_payid);
            $js_api_parameters = $js_api->getParameters();
        }
    }
} else {
    //微信授权，此处采用snsapi_base式授权仅获取access_token与openid，若需要获取用户信息则使用snsapi_userinfo式获取授权access_token
    $auth_url = $js_api->createOauthUrlForCode(SITE_NAME . '/wxpay/jsapi.php');
    header('Location:' . $auth_url);
}
?>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
        <title>微信支付样例_JSAPI支付</title>
    </head>
    <body>
        <div align="center">
            <div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">JSAPI支付（需要在公众号后台填写网页授权地址）</div><br/>
            <input type="button" value="返回" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="location='<?php echo SITE_NAME;?>'" />
        </div>
        <?php
        if ($js_api_parameters) {
            echo '<script type="text/javascript">
                //调用微信JS api 支付
                function jsApiCall()
                {
                    WeixinJSBridge.invoke(
                            "getBrandWCPayRequest",
                            ' . $js_api_parameters . ',
                            function(res){
                                if(res.err_msg=="get_brand_wcpay_request:ok"){
                                    alert("支付成功");
                                }
                                if(res.err_msg=="get_brand_wcpay_request:cancel"){
                                    alert("支付取消");
                                }
                                if(res.err_msg=="get_brand_wcpay_request:fail"){
                                    alert("支付失败");
                                }
                                location="' . SITE_NAME . '";
                            }
                    );
                }

                function callpay()
                {
                    if(typeof WeixinJSBridge=="undefined"){
                        if(document.addEventListener){
                            document.addEventListener("WeixinJSBridgeReady",jsApiCall,false);
                        }else if(document.attachEvent){
                            document.attachEvent("WeixinJSBridgeReady",jsApiCall);
                            document.attachEvent("onWeixinJSBridgeReady",jsApiCall);
                        }
                    }else{
                        jsApiCall();
                    }
                }

                window.onload=function(){
                    callpay();
                };
            </script>';
        }
        ?>
    </body>
</html>


