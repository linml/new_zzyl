<?php
class TradeClient
{
    public function __construct($merconfig)
    {
        $this->_orgId    = $merconfig['orgId'];//机构号
        $this->_merno    = $merconfig['merno'];//商户号
        $this->_sign_key = $merconfig['sign_key'];//签名密钥
        $this->_des_key  = $merconfig['des_key'];//加密密钥
        $this->_api_uri  = $merconfig['api_uri'];//接口地址
        $this->_dataSignType = $merconfig['dataSignType'];//正式
        $this->return_url = $merconfig['return_url'];//同步跳转地址
        $this->notify_url = $merconfig['notify_url'];//异步通知地址
    }

    public function doPayment($requestId, $order_sn, $pay_amount)
    {
        $this->payLog('-----------支付宝扫码支付个人对个人---------------请求参数->请求id' . $requestId);
        $this->payLog('-----------支付宝扫码支付个人对个人---------------请求参数->订单号' . $order_sn);
        $this->payLog('-----------支付宝扫码支付个人对个人---------------请求参数->金额' . $pay_amount * 100);
        $resInfo = $this->invoke($requestId, $order_sn, '测试商品',$pay_amount * 100);
        $this->payLog('-----------支付宝扫码支付个人对个人---------------返回参数' . $resInfo);
        $result = json_decode($resInfo, true);
        if($result['key'] != '05'){
            return ['status' => '1', 'msg' => '请求失败'];
        }
        $url = json_decode($result['result'], true);
        return ['status' => '0', 'msg' => '请求成功', 'orderStr' => base64_encode($url['url'])];
    }
	
	/**
	 * 获取支付二维码
	*/
	public function invoke($requestId,$order_id,$goods_info,$amount)
    {
		require_once('DesUtils.class.php');
		//$return_url='http://www.baidu.com';
		//$notify_url='http://www.baidu.com';
		$data=array(
						'requestId'		=>	$requestId,
						'orgId'			=>	$this->_orgId,
						'productId'		=>	'0100',
						'dataSignType'	=>	$this->_dataSignType.'',
						'timestamp'		=>	date('YmdHis',time())
						); //验证数据结构
		//print_r($data);
		$busMap=array(
						'merno'			=>	$this->_merno,
						'bus_no'		=>	'0203',  //业务编号 0101微信扫码 0201支付宝扫码 0501 QQ钱包扫码 0601京东钱包扫码 0701银联二维码
						'amount'		=>	$amount, //交易金额-单位分
						'goods_info'	=>	$goods_info,
						'order_id'		=>	$order_id,
						'return_url'	=>	$this->return_url,
						'notify_url'	=>	$this->notify_url
					  );
		//print_r($busMap);exit;
		$businessData=json_encode($busMap);

		$DesUtils = new DesUtils();
		//print_r($businessData);exit;
		//print_r($this->_des_key);exit;
		$businessData = $DesUtils->encrypt($businessData,$this->_des_key);//加密

		$businessData = urlencode($businessData); //加密结果 UrlEncode
		$data['businessData']=$businessData;
		ksort($data);
		//dump($mykeyarr);
		$lastvalue=end($data);
        $b = '';
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
		//$Jsonstr=json_encode($mykeyarr);
		//echo $Jsonstr;
		//echo DesUtils::decrypt($ciphertext,$this->_des_key);
		//echo $data.'<br/>';
		$posturl=$this->_api_uri . '/trade/invoke';
		include_once('HttpClient.class.php');
		$HttpClient = new HttpClient();
		// var_dump($posturl);
		// var_dump($data);exit;
        /*var_dump($posturl);
        var_dump($data);exit;*/
        $this->payLog('-----------支付宝扫码支付个人对个人---------------请求地址' . $posturl);
        $this->payLog('-----------支付宝扫码支付个人对个人---------------请求参数' . json_encode($data));
		$pageContents = $HttpClient->quickPost($posturl, $data);
        //$this->payLog('-----------支付宝扫码支付个人对个人---------------同步返回参数' . $pageContents);
		//var_dump($pageContents);exit;
		return $pageContents;
	}
	
	
	/**
	 * 订单查询
	*/
	public function orderQuery($requestId, $order_id){
		require_once('DesUtils.class.php');
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
		$DesUtils = new DesUtils();
		$businessData = $DesUtils->encrypt($businessData,$this->_des_key);//加密
		$businessData = urlencode($businessData); //加密结果 UrlEncode
		$data['businessData']=$businessData;
		ksort($data);
		//dump($mykeyarr);
		$lastvalue=end($data);
		$b = '';
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
		//$Jsonstr=json_encode($mykeyarr);
		//echo $Jsonstr;
		//echo DesUtils::decrypt($ciphertext,$this->_des_key);
		//echo $data.'<br/>';
		$posturl=$this->_api_uri . '/query/invoke';
		include_once('HttpClient.class.php');
        $HttpClient = new HttpClient();
		$pageContents = $HttpClient->quickPost($posturl, $data);
		//var_dump($pageContents);exit;
		return $pageContents;
	}

    /**
     * 支付日志
     * @param $msg
     */
    protected function payLog($str)
    {
        $file = fopen(dirname(__FILE__)."/log.txt","a");
        fwrite($file,date('Y-m-d H:i:s')."   ".$str."\r\n");
        fclose($file);
        //print_r($str.'<br/><br/>');

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
/*$Trade=new TradeClient();
$pageContents=$Trade->invoke('20190604200640287','20190604200640000000122001');
echo $pageContents;*/


//20180408180551289
/*list($s1, $s2)	=	explode(' ', microtime());
list($ling, $haomiao)=	explode('.', $s1);
$haomiao    =	substr($haomiao,0,3);
$requestId  =	date("YmdHis",$s2).$haomiao; //商户订单号(out_trade_no).必填(建议是英文字母和数字,不能含有特殊字符)
$order_id='20180408180551289'.mt_rand(1111,9999);
$Trade=new TradeClient();
$orderQuery=$Trade->invoke($requestId,$order_id,'测试商品',30000);
echo $orderQuery;*/