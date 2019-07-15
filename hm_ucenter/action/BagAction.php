<?php
namespace action;

use config\ErrorConfig;
use model\AppModel;
use model\BagModel;
use manager\DBManager;
use config\MysqlConfig;
use helper\LogHelper;
use config\EnumConfig;
use helper\FunctionHelper;
use model\UserModel;
/**
 * 背包功能
 */
class  BagAction extends AppAction
{
    private static $_instance = null;

    public static function getInstance()
    {
        return (!self::$_instance instanceof self) ? (new self()) : self::$_instance;
    }

    protected function __construct()
    {
//        parent::__construct();
    }

    private function __clone(){}

    /**
     * 获取背包信息
     */
    public function getUserBag($params)
    {
        $userID = $params['userID'];
        $info = BagModel::getInstance()->bagInfo($userID);
        $itemList = BagModel::getInstance()->getItemAttributeList();
        if (empty($itemList)){
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'背包未配置');
        }
        $bagArr=[];
        foreach ($itemList as $k => &$v) {
            $v['own_num'] = (int)$info[$v['name']];
            $bagArr[] = $v;
        }
        foreach ($bagArr as &$item){
            FunctionHelper::arrayValueToInt($item,['id','type','status','price','minMoney','maxMoney']);
        }
       AppModel::returnJson(ErrorConfig::SUCCESS_CODE,ErrorConfig::SUCCESS_MSG_DEFAULT,$bagArr);
    }

    /**
     * @param $userID
     * @return array|mixed
     */
    public function getBatteryMallList($params)
    {
        $data= BagModel::getInstance()->batteryMallList($params['userID']);
        if (empty($data)){
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'背包未配置');
        }
        foreach ($data as &$item){
            FunctionHelper::arrayValueToInt($item,['id','type','status','price','minMoney','maxMoney']);
        }
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE,ErrorConfig::SUCCESS_MSG_DEFAULT,$data);
    }

    /**
     * 获取道具详细信息
     * @param $params
     * @return array|mixed
     */
    public function getItemDetailInfo($params)
    {
       $attributeID = $params['itemID'];
       $result = BagModel::getInstance()->getItemAttributeByID($attributeID);
       if (is_null($result)) {
           AppModel::returnJson(ErrorConfig::ERROR_CODE,'道具不存在');
       }
        FunctionHelper::arrayValueToInt($result,['id','type','status','price','minMoney','maxMoney']);
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE,ErrorConfig::SUCCESS_MSG_DEFAULT,$result);
    }

    /**
     * 购买机甲
     * @param $params
     */
    public function buyBatteryMall($params)
    {
        $batteryID = $params['batteryID'];
        $userID = $params['userID'];
        $resourcesType = $params['resourceType']?: EnumConfig::E_ResourceType['MONEY'];
        if (empty($batteryID)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'缺少机甲id');
        }
        if ($batteryID<6) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'机甲id 错误');
        }
        $batteryMall = BagModel::getInstance()->getItemAttributeByID($batteryID);
        if (empty($batteryMall)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'机甲id 错误');
        }
        if (!$batteryMall['status']) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'机甲不可售');
        }
        $field = $batteryMall['name'];
        //然后判断用户是否已存在该机甲
        $userBag = BagModel::getInstance()->bagInfo($userID);
        if (empty($userBag)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'用户背包不存在');
        }
        if ($userBag[$field]) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'用户已拥有该机甲');
        }
        //开始购买
        $costMoney = $batteryMall['price'];//消耗金币
        $userMoney = GiveModel::getInstance()->getUserInfo($userID,'money');
        if ($costMoney > $userMoney) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'金币不足');
        }
        BagModel::getInstance()->changeUserResource($userID, $resourcesType, -$costMoney, EnumConfig::E_ResourceChangeReason['BATTERY_MALL_PURCHASE']);
        BagModel::getInstance()->addBatteryMall($field,$userID,'1');
        AppModel::returnJson(ErrorConfig::SUCCESS_CODE,ErrorConfig::SUCCESS_MSG_DEFAULT);
    }

    /**
     * 使用道具
     * @param $params
     */
    public function useItem($params)
    {
        $resourcesType = $params['resourceType']?: EnumConfig::E_ResourceType['MONEY'];  //EnumConfig::E_ResourceType['MONEY']; //先用金币，后续可换其他类型
        $attributeId = $params['itemID'];
        $userID = $params['userID'];
        if (empty($attributeId)){
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'缺少道具id');
        }
        if ($attributeId != 3){
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'该道具暂未开放使用');
        }
        $detail = BagModel::getInstance()->getItemAttributeByID($attributeId); //道具详情
        if (empty($detail)) {
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'该道具不存在');
        }
        //判断红包数量
        $userBag = BagModel::getInstance()->bagInfo($userID);
        if ($count = $userBag[$detail['name']] < 1){
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'道具不足，无法使用');
        }
        $minMoney = $detail['minMoney'];
        $maxMoney = $detail['maxMoney'];
        if ($maxMoney<$minMoney){
            AppModel::returnJson(ErrorConfig::ERROR_CODE,'金币配置错误，请联系客服');
        }
        $randomMoney = mt_rand($minMoney,$maxMoney);
        BagModel::getInstance()->changeAttributeItem($detail['name'],$userID,-1);
        //增加资源
        BagModel::getInstance()->changeUserResource($userID, $resourcesType, $randomMoney, EnumConfig::E_ResourceChangeReason['USE_RED_PACKET']);

        AppModel::returnJson(ErrorConfig::SUCCESS_CODE,ErrorConfig::SUCCESS_MSG_DEFAULT,['money'=>(int)$randomMoney]);
    }
}