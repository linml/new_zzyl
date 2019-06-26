<?php
class Tuisong
{
    const HOST = 'http://sdk.open.api.igexin.com/apiex.htm';
    const APPKEY = 'IYoE6FIJtu5F3WiBoazKr3';
    const APPID = 'wNCxoU2uo57RmZlz09Wmk3';
    const MASTERSECRET = 'NEqmzQxbCG8Ia3z4J48cd1';
    public $title = '';
    public $content = '';

    public function __construct($title, $content)
    {
        $this->title    = $title;//标题
        $this->content    = $content;//内容
    }

    public function pushMessageToApp(){
        require_once(dirname(__FILE__) . '/' . 'IGt.Push.php');
        require_once(dirname(__FILE__) . '/' . 'igetui/IGt.AppMessage.php');
        require_once(dirname(__FILE__) . '/' . 'igetui/IGt.APNPayload.php');
        require_once(dirname(__FILE__) . '/' . 'igetui/template/IGt.BaseTemplate.php');
        require_once(dirname(__FILE__) . '/' . 'IGt.Batch.php');
        require_once(dirname(__FILE__) . '/' . 'igetui/utils/AppConditions.php');

        $igt = new IGeTui(self::HOST,self::APPKEY,self::MASTERSECRET);
        //定义透传模板，设置透传内容，和收到消息是否立即启动启用
        $template = $this->IGtNotificationTemplateDemo();
        //$template = IGtLinkTemplateDemo();
        // 定义"AppMessage"类型消息对象，设置消息内容模板、发送的目标App列表、是否支持离线发送、以及离线消息有效期(单位毫秒)
        $message = new IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);

        $appIdList=array(self::APPID);
        $phoneTypeList=array('ANDROID');
        $provinceList=array('浙江');
        $tagList=array('haha');
        //用户属性
        //$age = array("0000", "0010");


        //$cdt = new AppConditions();
        // $cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
        // $cdt->addCondition(AppConditions::REGION, $provinceList);
        //$cdt->addCondition(AppConditions::TAG, $tagList);
        //$cdt->addCondition("age", $age);

        $message->set_appIdList($appIdList);
        //$message->set_conditions($cdt->getCondition());

        $rep = $igt->pushMessageToApp($message,"任务组名");

        return $rep;
        //echo ("<br><br>");
    }

    public function IGtNotificationTemplateDemo(){
        $template =  new IGtNotificationTemplate();
        $template->set_appId(self::APPID);                   //应用appid
        $template->set_appkey(self::APPKEY);                 //应用appkey
        $template->set_transmissionType(1);            //透传消息类型
        $template->set_transmissionContent("测试离线");//透传内容
        $template->set_title($this->title);      //通知栏标题
        $template->set_text($this->content);     //通知栏内容
        $template->set_logo("");                       //通知栏logo
        $template->set_logoURL("www.baidu.com");                    //通知栏logo链接
        $template->set_isRing(true);                   //是否响铃
        $template->set_isVibrate(true);                //是否震动
        $template->set_isClearable(true);              //通知栏是否可清除

        return $template;
    }

}

/*$Tuisong = new Tuisong('测试标题','测试内容');
$res = $Tuisong->pushMessageToApp();
var_dump($res);*/

?>
