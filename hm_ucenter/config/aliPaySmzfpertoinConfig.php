<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 11:20
 */
namespace config;

/**
 * 汇通支付相关配置
 */
final class aliPaySmzfpertoinConfig
{

    /**
     * 商户配置文件项
     */
    const MERCONFIG = array(
        "orgId"     => "5420191303140692",//机构号
        "merno"     => "542019130314067811",//商户号
        "sign_key"     => "4jFiwRHfaajsdzkwJhK6zn85mMQxW4dA",//签名密钥
        "des_key"   => "JbNsR5n5sAz3bKjytT77tYsjFfbJGP32",//加密密钥
        //请求地址
        "api_uri"      => "http://api.78u0rq.cn/open-gateway",
        "dataSignType"=> 1,//正式
        //交易成功异步通知地；商户设置自己异步URL地址 商户设置自己异步URL地址 商户设置自己异步URL地址
        "notify_url"      => "http://zzuls.szbchm.com/hm_ucenter/web/index.php?api=Pay&action=aliPaySmzfpertoin_callback",
        "return_url" =>"http://www.baidu.com",//同步通知URL
    );




}
