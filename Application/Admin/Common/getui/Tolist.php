<?php
class Tolist
{
    const HOST = 'http://sdk.open.api.igexin.com/apiex.htm';
    const APPKEY = 'IYoE6FIJtu5F3WiBoazKr3';
    const APPID = 'wNCxoU2uo57RmZlz09Wmk3';
    const MASTERSECRET = 'NEqmzQxbCG8Ia3z4J48cd1';
    public $title = '';
    public $content = '';
    public $clientIdarr = [];

    public function __construct($title, $content, $clientIdstr)
    {
        $this->title    = $title;//标题
        $this->content    = $content;//内容
        $this->clientIdarr = explode(',', $clientIdstr);
    }

    //多推接口案例
    public function pushMessageToList(){
        require_once(dirname(__FILE__) . '/' . 'IGt.Push.php');
        require_once(dirname(__FILE__) . '/' . 'igetui/IGt.AppMessage.php');
        require_once(dirname(__FILE__) . '/' . 'igetui/IGt.APNPayload.php');
        require_once(dirname(__FILE__) . '/' . 'igetui/template/IGt.BaseTemplate.php');
        require_once(dirname(__FILE__) . '/' . 'IGt.Batch.php');
        require_once(dirname(__FILE__) . '/' . 'igetui/utils/AppConditions.php');

        putenv("gexin_pushList_needDetails=true");
        $igt = new IGeTui(self::HOST,self::APPKEY,self::MASTERSECRET);
        //$igt = new IGeTui('',APPKEY,MASTERSECRET);//此方式可通过获取服务端地址列表判断最快域名后进行消息推送，每10分钟检查一次最快域名
        //消息模版：
        // LinkTemplate:通知打开链接功能模板
        $template = self::IGtLinkTemplateDemo();


        //定义"ListMessage"信息体
        $message = new IGtListMessage();
        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        $message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，为不限制推送
        $contentId = $igt->getContentId($message);

        $targetList = [];
        //接收方1
        foreach ($this->clientIdarr as $k => $v){
            $target1 = new IGtTarget();
            $target1->set_appId(self::APPID);
            $target1->set_clientId($v);
            $targetList[$k] = $target1;
        }

        //$target1->set_alias(Alias1);
        //接收方2
        /*$target2 = new IGtTarget();
        $target2->set_appId(APPID);
        $target2->set_clientId(CID2);*/
        //$target2->set_alias(Alias2);


        /*$targetList[0] = $target1;
        $targetList[1] = $target2;*/

        $rep = $igt->pushMessageToList($contentId, $targetList);
        return $rep;
    }

    public function IGtLinkTemplateDemo(){
        $template =  new IGtLinkTemplate();
        $template ->set_appId(self::APPID);                  //应用appid
        $template ->set_appkey(self::APPKEY);                //应用appkey
        $template ->set_title($this->title);       //通知栏标题
        $template ->set_text($this->content);        //通知栏内容
        $template->set_logo("");                       //通知栏logo
        $template->set_logoURL("");                    //通知栏logo链接
        $template ->set_isRing(true);                  //是否响铃
        $template ->set_isVibrate(true);               //是否震动
        $template ->set_isClearable(true);             //通知栏是否可清除
        $template ->set_url("http://www.igetui.com/"); //打开连接地址
        //设置通知定时展示时间，结束时间与开始时间相差需大于6分钟，消息推送后，客户端将在指定时间差内展示消息（误差6分钟）
        $begin = "2019-06-25 15:26:22";
        $end = "2019-06-27 15:31:24";
        $template->set_duration($begin,$end);

        // iOS推送需要设置的pushInfo字段(老方法不再介意使用)
        //$template ->set_pushInfo($actionLocKey,$badge,$message,$sound,$payload,$locKey,$locArgs,$launchImage);
        //$template ->set_pushInfo("",2,"","","","","","");
        //iOS推送需要设置的pushInfo字段(推荐使用)
//        $apn = new IGtAPNPayload();
//        $apn->alertMsg = "alertMsg";
//        $apn->badge = 11;
//        $apn->actionLocKey = "启动";
//        $apn->category = "ACTIONABLE";
//        $apn->contentAvailable = 1;
//        $apn->locKey = "通知栏内容";
//        $apn->title = "通知栏标题";
//        $apn->titleLocArgs = array("titleLocArgs");
//        $apn->titleLocKey = "通知栏标题";
//        $apn->body = "body";
//        $apn->customMsg = array("payload"=>"payload");
//        $apn->launchImage = "launchImage";
//        $apn->locArgs = array("locArgs");
//
//        $apn->sound=("test1.wav");;
//        $template->set_apnInfo($apn);
        return $template;
    }
}

/*$Tuisong = new Tolist('000测试标题000','000测试内容0000', 'b00c88c6906120252f127c9040b1f56d,52ef9f58e20396128c7b4c3c3a8bfa5c');
$res = $Tuisong->pushMessageToList();
var_dump($res);*/



?>
