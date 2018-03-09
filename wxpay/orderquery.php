<?php
/**
 * 微信支付：订单查询文件
 *
 * @filename  orderquery.php
 * @author    F。
 * @version   2018-2-23
 * @modify    2018-2-23 10:12:13
 * @copyright © 2016-2018 copyright AA笔记
 */
require '../common/common.php';
require '../config/wxpay_config.php';
$transaction_id = $result = '';
if (!empty($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
    //查询微信订单
    $input = new OrderQuery_pub();
    $input->setParameter('transaction_id', $transaction_id);
    $data = $input->getResult();
    $result = '<div style="margin-left:2%;color:#f00">查询结果：未查询到结果，请确认微信订单号是否正确</div><br/>';
    if (!empty($data['return_code']) && !empty($data['result_code']) && !empty($data['trade_state']) && ($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS') && ($data['trade_state'] == 'SUCCESS')) {
        $result = '<div style="margin-left:2%;color:#f00">查询结果：</div><br/>
                    <div style="margin-left:2%;">微信订单号：' . $transaction_id . '</div><br/>
                    <div style="margin-left:2%;">支付类型：' . $data['trade_type'] . '</div><br/>
                    <div style="margin-left:2%;">订单金额：' . $data['total_fee'] . ' 分</div><br/>
                    <div style="margin-left:2%;">现金支付金额：' . $data['cash_fee'] . ' 分</div><br/>
                    <div style="margin-left:2%;">支付时间：' . date_format(date_create($data['time_end']), 'Y-m-d H:i:s') . '</div><br/>';
    }
}
?>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
        <title>微信支付样例_订单查询</title>
    </head>
    <body>
        <form method="post">
            <div style="margin-left:2%;">微信订单号：</div><br/>
            <input type="text" style="width:96%;height:35px;margin-left:2%;" name="transaction_id" value="<?php echo $transaction_id;?>" placeholder="微信支付通知中账单记录的交易单号"/><br /><br />
            <div align="center">
                <input type="submit" value="查询" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" />
                <input type="button" value="返回" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="location='<?php echo SITE_NAME;?>'" />
            </div>
        </form>
        <?php echo $result;?>
    </body>
</html>
