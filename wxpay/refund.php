<?php
/**
 * 微信支付：订单退款文件
 *
 * @filename  refund.php
 * @author    F。
 * @version   2018-2-23
 * @modify    2018-2-23 10:15:13
 * @copyright © 2016-2018 copyright AA笔记
 */
require '../common/common.php';
require '../config/wxpay_config.php';
$transaction_id = $total_fee = $refund_fee = $result = '';
if (!empty($_POST['transaction_id']) && !empty($_POST['total_fee']) && !empty($_POST['refund_fee'])) {
    $transaction_id = $_POST['transaction_id'];
    $total_fee = $_POST['total_fee'];
    $refund_fee = $_POST['refund_fee'];
    $input = new Refund_pub();
    $input->setParameter('transaction_id', $transaction_id);
    $input->setParameter('out_refund_no', getOrder('R'));
    $input->setParameter('total_fee', $total_fee);
    $input->setParameter('refund_fee', $refund_fee);
    $data = $input->getResult();
    $result = '<div style="margin-left:2%;color:#f00">退款结果：申请退款失败，请确认微信订单号、订单金额、退款金额是否正确</div><br/>';
    if (!empty($data['return_code']) && !empty($data['result_code']) && ($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS')) {
        addLog('[refund]transaction_id:' . $transaction_id . ',refund_id:' . $data['refund_id'] . ',time:' . date('Y-m-d H:i:s', time()));
        $result = '<div style="margin-left:2%;color:#f00">退款结果：申请退款成功</div><br/>
                    <div style="margin-left:2%;">微信订单号：' . $transaction_id . '</div><br/>
                    <div style="margin-left:2%;">订单金额：' . $data['total_fee'] . ' 分</div><br/>
                    <div style="margin-left:2%;">现金退款金额：' . $data['cash_refund_fee'] . ' 分</div><br/>';
    } elseif (!empty($data['err_code_des'])) {
        $result = '<div style="margin-left:2%;color:#f00">退款结果：申请退款失败，' . $data['err_code_des'] . '</div><br/>';
    }
}
?>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
        <title>微信支付样例_退款</title>
    </head>
    <body>
        <form method="post">
            <div style="margin-left:2%;">微信订单号：</div><br/>
            <input type="text" style="width:96%;height:35px;margin-left:2%;" name="transaction_id" value="<?php echo $transaction_id;?>" placeholder="微信支付通知中账单记录的交易单号"/><br /><br />
            <div style="margin-left:2%;">订单总金额(分)：</div><br/>
            <input type="text" style="width:96%;height:35px;margin-left:2%;" name="total_fee" value="<?php echo $total_fee;?>" placeholder="订单查询结果中的订单金额"/><br /><br />
            <div style="margin-left:2%;">退款金额(分)：</div><br/>
            <input type="text" style="width:96%;height:35px;margin-left:2%;" name="refund_fee" value="<?php echo $refund_fee;?>" placeholder="退款金额不能大于订单总金额"/><br /><br />
            <div align="center">
                <input type="submit" value="提交退款" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" />
                <input type="button" value="返回" style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="location='<?php echo SITE_NAME;?>'" />
            </div>
        </form>
        <?php echo $result;?>
    </body>
</html>
