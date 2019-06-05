<?php
class TradeClient
{
    public function __construct()
    {
        $this->_orgId    = "4620183417180383";//机构号
        $this->_merno    = "502018351718032172";//商户号
		$this->_sign_key = "hcbRtRjZfAdZ8NwkAkAxGJFHi54paPiS";//签名密钥
		$this->_des_key  = "BhjFCwGyZHak4QndmGdACQ7QWKeKWT26";//加密密钥
		$this->_api_uri  = "http://118.31.38.147:18888/open-gateway";//接口地址
		$this->_dataSignType = 1;//正式
    }
	
	/**
	 * 获取支付二维码
	*/
	public function invoke($requestId,$order_id,$goods_info,$amount)
    {
		require_once('DesUtils.php');
		$return_url='http://www.baidu.com';
		$notify_url='http://www.baidu.com';
		$data=array(
						'requestId'		=>	$requestId,
						'orgId'			=>	$this->_orgId,
						'productId'		=>	'0100',
						'dataSignType'	=>	$this->_dataSignType.'',
						'timestamp'		=>	date('YmdHis',time())
						); //验证数据结构
		$busMap=array(
						'merno'			=>	$this->_merno,
						'bus_no'		=>	'0701',  //业务编号 0101微信扫码 0201支付宝扫码 0501 QQ钱包扫码 0601京东钱包扫码 0701银联二维码
						'amount'		=>	$amount, //交易金额-单位分
						'goods_info'	=>	$goods_info,
						'order_id'		=>	$order_id,
						'return_url'	=>	$return_url,
						'notify_url'	=>	$notify_url
					  );
		$businessData=json_encode($busMap);
		$businessData = DesUtils::encrypt($businessData,$this->_des_key);//加密
		$businessData = urlencode($businessData); //加密结果 UrlEncode
		$data['businessData']=$businessData;
		ksort($data);
		//dump($mykeyarr);
		$lastvalue=end($data);
		foreach($data as $key=>$value){
			if($value==$lastvalue){
				$b.=$key.'='.$value;
			}else{
				$b.=$key.'='.$value.'&';
			}
		}
		//echo end($data).'<br/>';
		$b.=$this->_sign_key;
		//echo $b.'<br/>';
		$signData=md5($b);
		$signData=strtoupper($signData);
		$data['signData']=$signData;
		//echo "上送参数:". json_encode($data);
		//echo $signData;
		$Jsonstr=json_encode($mykeyarr);
		//echo $Jsonstr;
		//echo DesUtils::decrypt($ciphertext,$this->_des_key);
		//echo $data.'<br/>';
		$posturl=$this->_api_uri . '/trade/invoke';
		include_once('HttpClient.class.php');
		$pageContents = HttpClient::quickPost($posturl, $data); 
		return $pageContents;
	}
	
	
	/**
	 * 订单查询
	*/
	public function orderQuery($requestId, $order_id){
		require_once('DesUtils.php');
		$data=array(
						'requestId'		=>	$requestId,
						'orgId'			=>	$this->_orgId,
						'productId'		=>	'9701',
						'dataSignType'	=>	$this->_dataSignType.'',
						'timestamp'		=>	date('YmdHis',time())
						); //验证数据结构
		//业务参数
		$busMap=array('order_id' => $order_id);
		$businessData=json_encode($busMap);
		$businessData = DesUtils::encrypt($businessData,$this->_des_key);//加密
		$businessData = urlencode($businessData); //加密结果 UrlEncode
		$data['businessData']=$businessData;
		ksort($data);
		//dump($mykeyarr);
		$lastvalue=end($data);
		foreach($data as $key=>$value){
			if($value==$lastvalue){
				$b.=$key.'='.$value;
			}else{
				$b.=$key.'='.$value.'&';
			}
		}
		//echo end($data).'<br/>';
		$b.=$this->_sign_key;
		//echo $b.'<br/>';
		$signData=md5($b);
		$signData=strtoupper($signData);
		$data['signData']=$signData;
		//echo "上送参数:". json_encode($data);
		//echo $signData;
		$Jsonstr=json_encode($mykeyarr);
		//echo $Jsonstr;
		//echo DesUtils::decrypt($ciphertext,$this->_des_key);
		//echo $data.'<br/>';
		$posturl=$this->_api_uri . '/query/invoke';
		include_once('HttpClient.class.php');
		$pageContents = HttpClient::quickPost($posturl, $data); 
		return $pageContents;
	}
	

}

//实例
//list($s1, $s2)	=	explode(' ', microtime());
//list($ling, $haomiao)=	explode('.', $s1);
//$haomiao    =	substr($haomiao,0,3);
//$requestId  =	date("YmdHis",$s2).$haomiao; //商户订单号(out_trade_no).必填(建议是英文字母和数字,不能含有特殊字符)
//$order_id=$requestId;
//$goods_info='我要买一个大奶牛';
//$amount='101';
//$Trade=new TradeClient();
//$pageContents=$Trade->invoke($requestId,$order_id,$goods_info,$amount);
//echo $pageContents;


//20180408180551289
list($s1, $s2)	=	explode(' ', microtime());
list($ling, $haomiao)=	explode('.', $s1);
$haomiao    =	substr($haomiao,0,3);
$requestId  =	date("YmdHis",$s2).$haomiao; //商户订单号(out_trade_no).必填(建议是英文字母和数字,不能含有特殊字符)
$order_id='20180408180551289';
$Trade=new TradeClient();
$orderQuery=$Trade->orderQuery($requestId,$order_id);
echo $orderQuery;