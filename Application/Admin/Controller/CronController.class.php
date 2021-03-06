<?php
namespace Admin\Controller;

use Think\Controller;
use manager\RedisManager;
use config\RedisConfig;

/*
 * 定时任务统计业务
 * */

class CronController extends Controller
{
    protected $time = '';//管理员信息
    protected $newtime = '';//管理员信息
    protected $idArr = []; //所有的下级代理id和会员id
    protected $sgidArr = []; //所有上级代理id

    public function _initialize(){
        /*if(empty(IS_CLI)){
            echo '只能在命令行模式下面运行!';
            exit;
        }*/
    }

    //实时统计客户每天的业绩，没十分钟执行一次
    public function realtimeStatisticalPerformance()
    {
        \Think\Log::write('当前时间错'.time());
        \Think\Log::write('实时统计客户每天的业绩，每十分钟执行一次');
        set_time_limit(0);
        $this->time = time();
        $todayDate = date('Y-m-d', $this->time);
        $startTime = strtotime(date('Y-m-d', $this->time));
        $endTime = strtotime(date('Y-m-d', $this->time)) + 86399;
        $Model = new \Think\Model();
        M()->startTrans();
        try{
            $adddata = [];
            //查询出所有的代理
            $agentMemberInfo = M()->table('web_agent_member')->field('username,userid,agentid')->select();
            foreach ($agentMemberInfo as $k1 => $v1){
                //查询出每个代理的所有下级代理ID
                self::diGui($v1['userid']);
                $subordinate_agent_id = $this->zsdata();
                //$subordinate_agent_id = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_lowerAgentSet . '|' . $v1['userid']);
                /*if($v1['userid'] == 122076){
                    var_dump($v1['userid']);
                    var_dump($v1['agentid']);
                    var_dump($subordinate_agent_id);
                }*/
                //查询出所有下级代理的玩家以及自己的玩家
                $forarr = array_merge($subordinate_agent_id, [$v1['agentid']]);
                $memberidarr = [];
                foreach ($forarr as $k9 => $v9){
                    $memberidarr = array_merge($memberidarr, $this->getmemberid($v9, $forarr));
                }
                //$subordinate_agent_id  所有的下级代理id
                //$memberidarr  自己以及自己所有下级代理的玩家
                //我的团队成员id
                $team_userid_arr = array_merge($subordinate_agent_id, $memberidarr);
                /*if($v1['userid'] == 122076){
                    var_dump($forarr);
                    var_dump($memberidarr);
                    var_dump($team_userid_arr);exit;
                }*/
                //查询出该团队今天到现在的业绩
                if(!empty($team_userid_arr)){
                    $inuserid = implode(',', $team_userid_arr);
                    $sql = "select sum(if(changeMoney > 0, changeMoney, -changeMoney)) as summoney from statistics_moneychange where time >= {$startTime} and time <= {$endTime} and ((reason = 3 and roomID not in (21,22,23,24)) or reason = 12) and userID in ({$inuserid})";
                    //$sql = "select sum(sum_change_money) as summoney from statistical_temporary_performance where create_date >= {$todayDate} and userid in ({$inuserid})";
                    //var_dump($sql);exit;
                    //本日团队贡献
                    $todayTeamAmount = $Model->query($sql);
                    //var_dump($todayTeamAmount);exit;
                }else{
                    $todayTeamAmount[0]['summoney'] = 0;
                }

                //本日个人贡献
                $sql2 = "select sum(if(changeMoney > 0, changeMoney, -changeMoney)) as summoney from statistics_moneychange where time >= {$startTime} and time <= {$endTime} and ((reason = 3 and roomID not in (21,22,23,24)) or reason = 12) and userID = {$v1['userid']}";
                //$sql2 = "select sum_change_money as summoney from statistical_temporary_performance where create_date >= {$todayDate} and userid = {$v1['userid']}";
                //var_dump($sql2);exit;
                $todayPersonalAmount = $Model->query($sql2);
                /*var_dump($todayTeamAmount);
                var_dump($todayPersonalAmount);*/

                //组装插入的数据
                $adddata[$k1]['userid'] = $v1['userid'];
                $adddata[$k1]['name'] = $v1['username'];
                if(empty($todayTeamAmount[0]['summoney'] + $todayPersonalAmount[0]['summoney'])){
                    $adddata[$k1]['day_performance'] = 0;
                }else{
                    $adddata[$k1]['day_performance'] = $todayTeamAmount[0]['summoney'] + $todayPersonalAmount[0]['summoney'];
                }

                if(empty($todayTeamAmount[0]['summoney'])){
                    $adddata[$k1]['day_team_performance'] = 0;
                }else{
                    $adddata[$k1]['day_team_performance'] = $todayTeamAmount[0]['summoney'];
                }

                if(empty($todayPersonalAmount[0]['summoney'])){
                    $adddata[$k1]['day_personal_performance'] = 0;
                }else{
                    $adddata[$k1]['day_personal_performance'] = $todayPersonalAmount[0]['summoney'];
                }

                $adddata[$k1]['create_date'] = $todayDate;
                $adddata[$k1]['create_time'] = $this->time;
                //var_dump($adddata);exit;
                $this->idArr = [];
            }
            //var_dump($adddata);
            //删除今天的所有数据
            M()->table('statistics_day_performance')->where(['create_date' => $todayDate])->delete();
            //批量导入刚才组装好的数据
            $res = M()->table('statistics_day_performance')->addAll($adddata);
            if(empty($res)){
                M()->rollback();
                echo '数据插入失败';
            }

            M()->commit();
            echo '执行成功';exit;
        }catch (Exception $e){
            \Think\Log::write('定时任务错误信息111111:'.$e->getMessage());
            M()->rollback();
            echo $e->getMessage();exit;
        }


    }


    /*
     * 获取所有会员id
     * */
    protected function getmemberid($agentid, $subordinate_agent_id){
        $where['b.agentID'] = $agentid;
        //找到不是代理的用户
        if(!empty($subordinate_agent_id)){
            $where['b.userID'] = ['NOT IN', $subordinate_agent_id];
        }
        $data = M('agent_bind as b')->field('userID')->where($where)->select();
        return array_column($data, 'userid');
    }

    /*
     * 组装数据
     * */
    protected function zsdata(){
        $returndata = [];
        foreach ($this->idArr as $k => $v) {
            if(!in_array($v['userid'], $returndata)){
                $returndata[] = $v['userid'];
            }
            if(!empty($v['totalid'])){
                foreach ($v['totalid'] as $k2 => $v2){
                    if(!in_array($v2['userid'], $returndata)){
                        $returndata[] = $v2['userid'];
                    }
                }
            }

        }
        return $returndata;
    }

    /*
     * 递归函数查询下级代理id
     * */
    protected function diGui($userid){
        $mapWhere['superior_agentid'] = $userid;
        $dataInfo = M('agent_member')->field('userid')->where($mapWhere)->select();
        foreach ($dataInfo as $k => $v) {
            $map1Where['superior_agentid'] = $v['userid'];
            $v['totalid'] = M('agent_member')->field('userid')->where($map1Where)->select();
            $this->idArr[] = $v;
            self::diGui($v['userid']);
        }
    }


    //每天凌晨三十分统计客户每天的奖励
    public function statisticsDailyRewards()
    {
        \Think\Log::write('每天凌晨三十分统计客户每天的奖励');
        $this->newtime = (time() - 3600);  //前一天的时间错
        $todayDate = date('Y-m-d', $this->newtime); // 前一天年月日
        $startTime = strtotime(date('Y-m-d', $this->newtime));
        $endTime = strtotime(date('Y-m-d', $this->newtime)) + 86399;
        \Think\Log::write('当前时间错'.time());
        \Think\Log::write('前一天的时间错'.$this->newtime);
        \Think\Log::write('前一天年月日'.$todayDate);
        \Think\Log::write('前一天凌晨'.$startTime);
        \Think\Log::write('今天凌晨'.$endTime);
        $jintianymd = date('Y-m-d', time());
        \Think\Log::write('今天年月日'.$jintianymd);
        $ztymd = date("Y-m-d",strtotime("-1 day"));  //前一天年月日
        \Think\Log::write('昨天年月日'.$ztymd);
        if($todayDate == $jintianymd || $todayDate != $ztymd){
            \Think\Log::write('年月日不对，禁止执行脚本');
            echo 333;exit;
        }


        $Model = new \Think\Model();
        M()->startTrans();
        try{
            $adddata = [];
            //查询出所有的代理
            $agentMemberInfo = M()->table('web_agent_member')->field('username,userid,agentid,new_agent_leval_money')->select();
            foreach ($agentMemberInfo as $k1 => $v1){
                //查询出每个代理的所有下级代理ID
                self::diGui($v1['userid']);
                $subordinate_agent_id = $this->zsdata();
                //$subordinate_agent_id = RedisManager::getRedis()->hGetAll(RedisConfig::Hash_lowerAgentSet . '|' . $v1['userid']);
                //var_dump($subordinate_agent_id);
                //查询出所有下级代理的玩家以及自己的玩家
                $forarr = array_merge($subordinate_agent_id, [$v1['agentid']]);
                $memberidarr = [];
                foreach ($forarr as $k9 => $v9){
                    $memberidarr = array_merge($memberidarr, $this->getmemberid($v9, $forarr));
                }

                //$subordinate_agent_id  所有的下级代理id
                //$memberidarr  自己以及自己所有下级代理的玩家
                //我的团队成员id
                $team_userid_arr = array_merge($subordinate_agent_id, $memberidarr);
                //var_dump($team_userid_arr);exit;
                //var_dump($team_userid_arr);
                //查询出该团队今天到现在的业绩
                if(!empty($team_userid_arr)){
                    $inuserid = implode(',', $team_userid_arr);
                    $sql = "select sum(if(changeMoney > 0, changeMoney, -changeMoney)) as summoney from statistics_moneychange where time >= {$startTime} and time <= {$endTime} and ((reason = 3 and roomID not in (21,22,23,24)) or reason = 12) and userID in ({$inuserid})";
                    //$sql = "select sum(sum_change_money) as summoney from statistical_temporary_performance where create_date >= {$todayDate} and userid in ({$inuserid})";
                    //var_dump($sql);exit;
                    //本日团队贡献
                    $todayTeamAmount = $Model->query($sql);
                    //var_dump($todayTeamAmount);exit;
                }else{
                    $todayTeamAmount[0]['summoney'] = 0;
                }

                //本日个人贡献
                $sql2 = "select sum(if(changeMoney > 0, changeMoney, -changeMoney)) as summoney from statistics_moneychange where time >= {$startTime} and time <= {$endTime} and ((reason = 3 and roomID not in (21,22,23,24)) or reason = 12) and userID = {$v1['userid']}";
                //$sql2 = "select sum_change_money as summoney from statistical_temporary_performance where create_date >= {$todayDate} and userid = {$v1['userid']}";
                //var_dump($sql2);exit;
                $todayPersonalAmount = $Model->query($sql2);
                //var_dump($todayPersonalAmount);exit;

                //组装插入的数据
                $adddata[$k1]['userid'] = $v1['userid'];
                $adddata[$k1]['name'] = $v1['username'];
                $adddata[$k1]['new_agent_leval_money'] = $v1['new_agent_leval_money'];
                if(empty($todayTeamAmount[0]['summoney'] + $todayPersonalAmount[0]['summoney'])){
                    $adddata[$k1]['day_performance'] = 0;
                }else{
                    $adddata[$k1]['day_performance'] = $todayTeamAmount[0]['summoney'] + $todayPersonalAmount[0]['summoney'];
                }

                if(empty($todayTeamAmount[0]['summoney'])){
                    $adddata[$k1]['day_team_performance'] = 0;
                }else{
                    $adddata[$k1]['day_team_performance'] = $todayTeamAmount[0]['summoney'];
                }

                if(empty($todayPersonalAmount[0]['summoney'])){
                    $adddata[$k1]['day_personal_performance'] = 0;
                }else{
                    $adddata[$k1]['day_personal_performance'] = $todayPersonalAmount[0]['summoney'];
                }
                $adddata[$k1]['create_date'] = $todayDate;
                $adddata[$k1]['create_time'] = time();
                $this->idArr = [];
            }

            //计算用户的奖励
            $resadddate = $this->getReward($adddata);
            //var_dump($resadddate);exit;


            //删除今天的所有数据
            M()->table('statistics_day_performance')->where(['create_date' => $todayDate])->delete();
            //批量导入刚才组装好的数据
            $res = M()->table('statistics_day_performance')->addAll($resadddate);
            if(empty($res)){
                M()->rollback();
                echo '数据插入失败';
            }

            M()->commit();
            echo '执行成功';exit;
        }catch (Exception $e){
            \Think\Log::write('定时任务错误信息111111:'.$e->getMessage());
            M()->rollback();
            echo $e->getMessage();exit;
        }

    }

    /*
     * 后去每个用户的奖励
     * $param arr  $adddata  统计的数据
     * */
    public function getReward($adddata)
    {
        //var_dump($adddata);exit;
        //查询出抽层比例信息
        $ratioInfo = M()->table('pumping_ratio')->field('agent_start_value,agent_end_value,pump_money')->select();
        foreach ($adddata as $key1 => $val1){
            //获取该用户的所有下一级代理
            $agent_id_arr = M('agent_member')->field('userid')->where(['superior_agentid' => $val1['userid']])->select();

            if(!empty($agent_id_arr)){  //如果存在下级代理减去下级代理的奖励就是自己的奖励
                $subordinaterewardMoney = '';  //下级奖励金额总和
                foreach ($agent_id_arr as $key2 => $val2){
                    foreach ($adddata as $key3 => $val3){
                        if($val2['userid'] === $val3['userid']){
                            $subordinaterewardMoney += $this->getJlmongey($val3['day_performance']/100, $ratioInfo, $val3['new_agent_leval_money']);
                            /*if($val1['userid'] == 122002){
                                var_dump($val3['userid']);
                                var_dump($val3['day_performance']);
                                var_dump($subordinaterewardMoney);
                            }*/
                        }
                    }
                }
                /*if($val1['userid'] == 122021){
                    $myRewardMoney = $this->getJlmongey($val1['day_performance']/100, $ratioInfo);
                    $adddata[$key1]['reward'] = $myRewardMoney - $subordinaterewardMoney;
                }*/
                //计算自己奖励的金额的总和
                $myRewardMoney = $this->getJlmongey($val1['day_performance']/100, $ratioInfo, $val1['new_agent_leval_money']);
                /*if($val1['userid'] == 122027){
                    var_dump($myRewardMoney);
                    var_dump($subordinaterewardMoney);
                    var_dump($myRewardMoney - $subordinaterewardMoney);
                    $int = ($myRewardMoney - $subordinaterewardMoney)*100;
                    var_dump($int);
                    var_dump(settype($int, 'int'));
                    var_dump(intval($int));
                    var_dump(floor($int));
                    exit;
                }*/
                /*if($val1['userid'] == 122001){
                    var_dump($myRewardMoney);
                    var_dump($subordinaterewardMoney);
                    var_dump($myRewardMoney - $subordinaterewardMoney);
                    exit;
                }*/
                $adddata[$key1]['reward'] = floor(($myRewardMoney - $subordinaterewardMoney)*100);
            }else{   //如果没有下级代理根据自己的总业绩计算奖励
                $adddata[$key1]['reward'] = floor(($this->getJlmongey($val1['day_performance']/100, $ratioInfo, $val1['new_agent_leval_money']))*100);
            }
            //更改该用户的可提现金额
            if(!empty($adddata[$key1]['reward'])){
                M()->table('web_agent_member')->where(['userid' => $val1['userid']])->setInc('balance',$adddata[$key1]['reward']);
                //记录账单
                $this->addApplyPos($adddata[$key1]['userid'], $adddata[$key1]['reward']);
            }
            unset($adddata[$key1]['new_agent_leval_money']);

        }

        return $adddata;
    }

    /*
     * 添加账单
     * $userid  用户id
     * $handle_money 提现金额
     * */
    protected function addApplyPos($userid, $handle_money){
        //查询出该代理的信息
        $userInfo = M('agent_member')->field('userid,username,agent_level,balance')->where(['userid' => $userid])->find();
        //记录账单
        $billdata = [
            'username' => $userInfo['username'],
            'agent_level' => $userInfo['agent_level'],
            'front_balance' => $userInfo['balance'] - $handle_money,  //总的可提现金额
            'handle_money' => $handle_money,  //奖励金额
            'after_balance' => $userInfo['balance'], //剩余可提现金额
            '_desc' => '*每日代理奖励*',
            'make_time' => time(),
            'make_userid' => $userInfo['userid'],
            'amount' => 0,
            'commission' => $handle_money,
            'under_amount' => 0,
            'under_commission' => 0,
        ];
        M('bill_detail')->add($billdata);
    }

    /*
     * 根据每个人的业绩计算奖励
     * $param int $performance 用户的业绩
     * $param arr $ratioInfo   抽层比例信息
     * $param int $new_agent_leval_money 保底金额
     * */
    public function getJlmongey($performance, $ratioInfo, $new_agent_leval_money)
    {
        if($performance < 100) return 0;
        foreach ($ratioInfo as $key => $value){
            if($performance >= $value['agent_start_value'] && $performance < $value['agent_end_value']){
                //return floor($performance/10000) * $value['pump_money'];
                $pump_money66 = $value['pump_money'];
            }

        }

        if($pump_money66 >= $new_agent_leval_money){
            // return floor($performance/10000) * $pump_money66;
            //return sprintf("%.2f", ($performance/10000) * $pump_money66);
            //这样操作是为了保留两位小数，不进行四舍五入
            return ($performance/10000) * $pump_money66;
        }else{
            // return floor($performance/10000) * $new_agent_leval_money;
            return ($performance/10000) * $new_agent_leval_money;
            //return sprintf("%.2f", ($performance/10000) * $new_agent_leval_money);
        }

    }

    /*
     * 将用户的所有下级代理存到redis
     * */
    public function depositAgentRelationship(){
        $agentMemberInfo = M()->table('web_agent_member')->field('userid')->select();
        foreach ($agentMemberInfo as $k1 => $v1) {
            //查询出每个代理的所有下级代理ID
            self::diGui($v1['userid']);
            $subordinate_agent_id = $this->zsdata();
            if(!empty($subordinate_agent_id)) RedisManager::getRedis()->hMset(RedisConfig::Hash_lowerAgentSet . '|' . $v1['userid'], $subordinate_agent_id);
            $this->idArr = [];
        }
        echo '结束';
    }

    /*
     * 将用户的所有上级代理存到redis
     * */
    public function depositSuperiorAgent(){
        $agentMemberInfo = M()->table('web_agent_member')->field('userid,superior_agentid')->select();
        foreach ($agentMemberInfo as $k1 => $v1) {
            //查询出每个代理的所有下级代理ID
            //$v1['superior_agentid'] = 122006;
            if(!empty($v1['superior_agentid'])){
                array_push($this->sgidArr, $v1['superior_agentid']);
                self::sjdiGui($v1['superior_agentid']);
                RedisManager::getRedis()->hMset(RedisConfig::Hash_superiorAgentSet . '|' . $v1['userid'], $this->sgidArr);
            }
            $this->sgidArr = [];
        }
        echo '结束';
    }

    /*
     * 递归函数查询上级代理id
     * */
    protected function sjdiGui($superior_agentid){
        $mapWhere['userid'] = $superior_agentid;
        //查询出我上级的上级的用户id
        $ressuperior_agentid = M('agent_member')->where($mapWhere)->getField('superior_agentid');
        if(!empty($ressuperior_agentid)){
            array_push($this->sgidArr, $ressuperior_agentid);
            self::sjdiGui($ressuperior_agentid);
        }
    }

    /*
     * 统计所有代理今天的业绩，临时业绩表,便于定时脚本统计,没八分钟执行一次
     * */
    public function temporary_Performance(){
        $this->time = time();
        $todayDate = date('Y-m-d', $this->time);
        $startTime = strtotime(date('Y-m-d', $this->time));
        $endTime = strtotime(date('Y-m-d', $this->time)) + 86399;
        $Model = new \Think\Model();
        M()->startTrans();
        $sql = "select sum(if(changeMoney > 0, changeMoney, -changeMoney)) as sum_change_money,userID as userid from statistics_moneychange where time >= {$startTime} and time <= {$endTime} and ((reason = 3 and roomID not in (21,22,23,24)) or reason = 12) group by userID";
        //本日团队贡献
        $todayTeamAmount = $Model->query($sql);
        if(!empty($todayTeamAmount)){
            foreach ($todayTeamAmount as &$val){
                $val['create_date'] = $todayDate;
                $val['create_time'] = $this->time;
            }

            //删除今天的所有数据
            M()->table('statistical_temporary_performance')->where(['create_date' => $todayDate])->delete();
            //批量导入刚才组装好的数据
            $res2 = M()->table('statistical_temporary_performance')->addAll($todayTeamAmount);
            if(empty($res2)){
                M()->rollback();
                echo '数据插入失败';
            }
        }

        M()->commit();
        echo '执行成功';exit;

    }


    public function sjImage(){
        $post = file_get_contents("php://input");
        \Think\Log::write('#####呵呵######'.$post);
        $recived = json_decode($post,true);
        \Think\Log::write('######呵呵#####'.dirname(__DIR__));
        /*$image = new \Think\Image();
        // 在图片右下角添加水印文字 ThinkPHP 并保存为new.jpg
        $image->open(".\\hm_ucenter\\synthesis\\20190320\\610f8155c562b6cdc2ba60235808532f.png")->text(122002,'D:\\phpStudy\\PHPTutorial\\vhost\\huo.qq\\OLD_PHP\\hm_ucenter\\synthesis\\logo\\simkai.ttf',20,'#000000',\Think\Image::IMAGE_WATER_EAST)->save(".\\hm_ucenter\\synthesis\\20190320\\610f8155c562b6cdc2ba60235808532f.png");
        echo 33;exit;*/
        $filepath = './hm_ucenter/synthesis/'.date('Ymd').'/'.$recived['filepath'];
        $fontfile = './hm_ucenter/synthesis/logo/simkai.ttf';
        \Think\Log::write('######呵呵111#####'.$filepath);
        \Think\Log::write('######呵呵222#####'.$fontfile);
        $image = new \Think\Image();
        // 在图片右下角添加水印文字 ThinkPHP 并保存为new.jpg
        $image->open($filepath)->text('邀请码:'.$recived['userid'],$fontfile,20,'#000000',\Think\Image::IMAGE_WATER_SOUTH)->save($filepath);
        echo 33;
    }

}
