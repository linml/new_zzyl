<?php
namespace model;

use config\GameRedisConfig;
use manager\RedisManager;
use helper\FunctionHelper;
use config\MysqlConfig;
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019/6/20
 * Time: 17:38
 */

class BagModel extends  AppModel
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

    private function __clone(){}


    /**
     * 获取我的背包信息
     * @param $userID
     * @return array
     */
    public function bagInfo($userID)
    {
        $key = GameRedisConfig::Hash_userBag.'|'.$userID;
        $data = RedisManager::getGameRedis()->hGetAll($key);
        if (empty($data)) {
            return $data;
        }
        //去掉机甲相关
        $names = $this->getItemAttributeByType(4);
       foreach ($names as $k => $v) {
           $data =  FunctionHelper::array_remove($data,$v['name']);
       }
       return $data;
    }

    /**
     * 获取不同类型的道具
     * @param int $type
     * @return array|mixed
     */
    public function getItemAttributeByType($type= 0)
    {
        if (empty($type)) {
          $where = 'type>0';
        }else{
            $where = 'type='.$type;
        }
        $sql  = "select * from ".MysqlConfig::Table_bagattribute." where {$where}";
        $result = DBManager::getMysql()->queryAll($sql);
        if (empty($result)){
            return [];
        }
        return $result;
    }

    /**
     * 获取道具列表去掉机甲
     */
    public function getItemAttributeList()
    {
        $sql = "select * from ".MysqlConfig::Table_bagattribute." where type != 4";
        $result = DBManager::getMysql()->queryAll($sql);
        return $result;
    }

    /**
     * 获取机甲列表
     * @return array|mixed
     */
    public function batteryMallList($userID)
    {
        $batteryMallList = $this->getItemAttributeByType(4);
        $data = RedisManager::getGameRedis()->hGetAll(GameRedisConfig::Hash_userBag.'|'.$userID);
        foreach ($batteryMallList as $k => &$v){
           if (!empty($data)&&!empty($data[$v['name']])) {
               $v['is_have'] = 1;
           }else {
               $v['is_have'] = 0;
           }
        }
       return $batteryMallList;
    }

   /**
    * 获取道具
    */
   public function getItemAttributeByID($itemID)
   {
       $sql = "select * from ".MysqlConfig::Table_bagattribute.' where id='.$itemID;
       //先判断是否存在这个id
       $info = DBManager::getMysql()->queryRow($sql);
       return $info;
   }


    /**
     * 购买机甲
     * @param $name
     * @param $userID
     * @param $v
     * @return LONG
     */
   public function addBatteryMall($name,$userID,$v=1)
   {
       $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_userBag.'|'.$userID);
       $result = RedisManager::getGameRedis()->hSet($redisKey,$name,$v);
       if ($result) {
           RedisManager::getGameRedis()->sAdd(GameRedisConfig::Set_cacheUpdateSet, GameRedisConfig::Hash_userBag . '|' . $userID);
       }
       return $result;
   }

    /**
     * 增加或减少某人的道具
     * @param $name
     * @param $userID
     * @param int $step
     * @return int
     */
   public function changeAttributeItem($name,$userID,$step=1){
       $redisKey = RedisManager::makeKey(GameRedisConfig::Hash_userBag.'|'.$userID);
       $result = RedisManager::getGameRedis()->hIncrBy($redisKey,$name,$step);
       if ($result) {
           RedisManager::getGameRedis()->sAdd(GameRedisConfig::Set_cacheUpdateSet, GameRedisConfig::Hash_userBag . '|' . $userID);
       }
       return $result;
   }

}