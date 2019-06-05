<?php
namespace pay\hui_tong;

use pay\AppPay;
use config\EnumConfig;
use model\PayModel;
use config\aliPaySmzfpertoinConfig;

/*
 * 新接的支付宝扫码支付异步通知回调
 */
class newNotify extends AppPay
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
        parent::__construct();
    }

    private function __clone()
    {
    }

    public function initPayConfig()
    {
    }

    public function doPayment($goods, $userID, array $params = [])
    {
    }

    public function notify_callback(){
        $merconfig = aliPaySmzfpertoinConfig::MERCONFIG;
        $util = new Util ();
        if(empty($_POST)) {//判断POST来的数组是否为空
            $this->payLog('------支付宝扫码支付----------:异步通知参数为空');
        }
        else {
            //生成签名结果
            $data=$_POST;
            $this->payLog('------支付宝扫码支付----------:异步通知参数'.json_encode($data));
            unset($data['sign_data']);
            ksort($data);
            $this->payLog('------支付宝扫码支付----------:排序后参数'.json_encode($data));
            $lastvalue=end($data);
            $b = '';
            foreach($data as $key=>$value){
                if($value==$lastvalue){
                    $b.=$key.'='.$value;
                }else{
                    $b.=$key.'='.$value.'&';
                }
            }
            $this->payLog('------支付宝扫码支付----------:签名字符串'.$b);
            $b.=$merconfig['sign_key'];
            $this->payLog('------支付宝扫码支付----------:拼上密钥后的签名字符串'.$b);
            $signData=md5($b);
            $this->payLog('------支付宝扫码支付----------:生成的签名'.$signData);
            $this->payLog('------支付宝扫码支付----------:传过来的签名'.$_POST['sign_data']);
            if($signData==$_POST['sign_data']){
                /*//验证成功
                echo $signData;*/
                if(empty($_POST['trade_status'])){
                    $resupdatepay = $this->updata_order($_POST['order_id']);
                    if($resupdatepay['code'] != 200){
                        $this->payLog($_POST['order_id'].'-------------支付宝扫码支付-------------:'.$resupdatepay['msg']);
                        echo $resupdatepay['msg'];
                    }else{
                        $this->payLog($_POST['order_id'].'----------------支付宝扫码支付----------:'.$resupdatepay['msg']);
                        echo "success";
                    }
                }else{
                    $this->payLog($_POST['order_id'].'---------支付宝扫码支付------------:异步通知用户还没有支付该订单');
                }

            }else{
                $this->payLog('-----------------------------支付宝扫码支付----------------:异步通知验证失败');
            }
        }

    }

    public function updata_order($order_sn){
        if (!isset($order_sn)) {
            return ['code' => -1, 'msg' => '订单号不能为空'];
        }

        $order = PayModel::getInstance()->getPayOrder($order_sn);
        if (empty($order)) {
            return ['code' => -1, 'msg' => '不存在该订单'];
        }

        //订单状态是否NEW
        if ($order['status'] != EnumConfig::E_OrderStatus['NEW']) {
            return ['code' => -1, 'msg' => '订单状态异常'];
        }

        //先写订单状态 再发货
        $updateStatus = PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['GIVE']);
        if (!empty($updateStatus)) {
            //充值增加玩家资源
            $userID = $order['userID'];
            $resourceType = $order['buyType'];
            $change = $order['buyNum'];
            $ret = PayModel::getInstance()->changeUserResource($userID, $resourceType, $change, EnumConfig::E_ResourceChangeReason['PAY_RECHARGE']);
            //如果发货失败需要补单
            if (!$ret) {
                \helper\LogHelper::printError('充值成功，但订单缺货，需补发' . var_export($_POST, true));
                $this->payLog('-----------------------------huitong----------------:充值成功，但订单缺货，需补发');
                PayModel::getInstance()->updatePayOrderStatus($order_sn, EnumConfig::E_OrderStatus['NOT_GIVE']);
            }
            //插入redis通知消息
            /*$redis = \manager\RedisManager::getRedis();
            $redis->lPush("orderNotify",$order_sn);*/
            \helper\LogHelper::pushSpeech();
            return ['code' => 200, 'msg' => '充值成功'];
        } else {
            \helper\LogHelper::printError('充值成功，但订单更新失败' . var_export($_POST, true));
            return ['code' => -1, 'msg' => '充值成功，但订单更新失败'];
        }
    }


}


?>