<?php
function logResult($word='') {
	$fp = fopen("log.txt","a");
	flock($fp, LOCK_EX) ;
	fwrite($fp,"\n".$word."\n");
	flock($fp, LOCK_UN);
	fclose($fp);
}
//amount=10000&goods_info=\u5546\u54c1\u6d88\u8d39&merno=332018312915037595&order_id=zzg123_42420180411110423&orgid=5120182929150334&plat_order_id=2018041123135473856519&sign_data=0862e400810f4b10ad4d2bc3b3873b67&timestamp=20180411231546&trade_date=2018-04-11 23:14:47&trade_status=0
if(empty($_POST)) {//判断POST来的数组是否为空
	return false;
}
else {
	//生成签名结果
	$data=$_POST;
	unset($data['sign_data']);
	ksort($data);
	$lastvalue=end($data);
	foreach($data as $key=>$value){
		if($value==$lastvalue){
			$b.=$key.'='.$value;
		}else{
			$b.=$key.'='.$value.'&';
		}
	}
	$b.='3W3p2CEpXJkQQXNnBBpWsfKWje8kww67';
	$signData=md5($b);
	if($signData==$_POST['sign_data']){
		//验证成功
		echo $signData;
	}else{
		//验证失败	
	}
	$responseTxt = json_encode($_POST);
	$log_text = $responseTxt."\n";
	logResult($log_text);
}