<?php
/**
 * 判断是否登录
 */
function is_login(){
    if(session('admin_user_id')){
        return session('admin_user_id');
    }else{
        return false;
    }
}

/**
 * 记录操作日志
 */
function operation_record($uid,$_action){
	$data = [
		'uid'		=>	$uid,
		'_action'	=>	$_action,
		'_time'		=>	time(),	
	];
	M('admin_log')->add($data);
}

function getLoginInfo($field = 'a.id, a.username, a.group_id, b.rules, b.disabled') {
	$info = M()->table(\config\MysqlConfig::Table_web_admin_member)->alias('a')
		->join('left join ' . \config\MysqlConfig::Table_web_admin_group . ' b on a.group_id = b.id ')
		->field($field)
		->where(['a.id ' => UID])
		->find();
	return $info;
}

/**
 * 记录配置日志
 */
function configure_record($uid,$_action,$roomID){
	$data = [
		'uid'		=>	$uid,
		'action'	=>	$_action,
        'roomID'    =>  $roomID,
		'time'		=>	time(),	
	];
	M('configurelog')->add($data);
}




// 回复类型
function applosType($k){
	$arr = ['text'=>'文本', 'image'=>'图片', 'music'=>'音乐', 'video'=>'视频', 'thumb'=>'缩略图', 'articleimage'=>'文章内容图片','news'=>'图文'];
	return $arr[$k];
}

// 图文排序
function newsNum($k){
	$arr = [1=>'一','二','三','四','五','六','七','八'];
	return $arr[$k];
}

function agentlevel($l){
	$arr = ['无上级代理','一级代理','二级代理','三级代理'];
	return $arr[$l];
}

function get_select_str1($name){
    return "<select name='{$name}'>
<option value='0'>0档</option>
<option value='-200'>负1档</option>
<option value='-500'>负2档</option>
<option value='-700'>负3档</option>
<option value='-900'>负4档</option>
<option value='-1000' selected='selected'>负5档</option>
</select>";
}

function get_select_str2($name){
    return "<select name='{$name}'>
<option value='0'>0档</option>
<option value='-200'>负1档</option>
<option value='-500'>负2档</option>
<option value='-700'>负3档</option>
<option value='-900' selected='selected'>负4档</option>
<option value='-1000'>负5档</option>
</select>";
}

function get_select_str3($name){
    return "<select name='{$name}'>
<option value='0'>0档</option>
<option value='-200'>负1档</option>
<option value='-500'>负2档</option>
<option value='-700' selected='selected'>负3档</option>
<option value='-900'>负4档</option>
<option value='-1000'>负5档</option>
</select>";
}

function get_select_str4($name){
    return "<select name='{$name}'>
<option value='0'>0档</option>
<option value='-200'>负1档</option>
<option value='-500' selected='selected'>负2档</option>
<option value='-700'>负3档</option>
<option value='-900'>负4档</option>
<option value='-1000'>负5档</option>
</select>";
}

function get_select_str5($name){
    return "<select name='{$name}'>
<option value='0'>0档</option>
<option value='-200' selected='selected'>负1档</option>
<option value='-500'>负2档</option>
<option value='-700'>负3档</option>
<option value='-900'>负4档</option>
<option value='-1000'>负5档</option>
</select>";
}

//0档是区分类型
function get_select_str6($name, $type){
	if ($type == 2) {
        return "<select name='{$name}'>
<option value='-1000'>负5档</option>
<option value='-900'>负4档</option>
<option value='-700'>负3档</option>
<option value='-500'>负2档</option>
<option value='-200'>负1档</option>
<option value='0'  selected='selected'>0档</option>
<option value='200'>正1档</option>
<option value='500'>正2档</option>
<option value='700'>正3档</option>
<option value='900'>正4档</option>
<option value='1000'>正5档</option>
</select>";
	} else {
        return "<select name='{$name}'>
<option value='0'  selected='selected'>0档</option>
<option value='200'>1档</option>
<option value='500'>2档</option>
<option value='700'>3档</option>
<option value='900'>4档</option>
<option value='1000'>5档</option>
</select>";
	}

}

function get_select_str7($name, $type = 2){
	if ($type == 2) {
        return "<select name='{$name}'>
<option value='0'>0档</option>
<option value='200' selected='selected'>正1档</option>
<option value='500'>正2档</option>
<option value='700'>正3档</option>
<option value='900'>正4档</option>
<option value='1000'>正5档</option>
</select>";
	} else {
        return "<select name='{$name}'>
<option value='0'>0档</option>
<option value='200' selected='selected'>1档</option>
<option value='500'>2档</option>
<option value='700'>3档</option>
<option value='900'>4档</option>
<option value='1000'>5档</option>
</select>";
	}

}

function get_select_str8($name, $type = 2){
	if ($type == 2) {
		return "<select name='{$name}'>
	<option value='0'>0档</option>
	<option value='200'>正1档</option>
	<option value='500' selected='selected'>正2档</option>
	<option value='700'>正3档</option>
	<option value='900'>正4档</option>
	<option value='1000'>正5档</option>
	</select>";
	}else {
		return "<select name='{$name}'>
	<option value='0'>0档</option>
	<option value='200'>1档</option>
	<option value='500' selected='selected'>2档</option>
	<option value='700'>3档</option>
	<option value='900'>4档</option>
	<option value='1000'>5档</option>
	</select>";
	}

}

function get_select_str9($name, $type = 2){
    if ($type == 2) {
        return "<select name='{$name}'>
	<option value='0'>0档</option>
	<option value='200'>正1档</option>
	<option value='500'>正2档</option>
	<option value='700' selected='selected'>正3档</option>
	<option value='900'>正4档</option>
	<option value='1000'>正5档</option>
	</select>";
    }else {
        return "<select name='{$name}'>
	<option value='0'>0档</option>
	<option value='200'>1档</option>
	<option value='500'>2档</option>
	<option value='700' selected='selected'>3档</option>
	<option value='900'>4档</option>
	<option value='1000'>5档</option>
	</select>";
    }
}

function get_select_str10($name, $type = 2){
    if ($type == 2) {
        return "<select name='{$name}'>
	<option value='0'>0档</option>
	<option value='200'>正1档</option>
	<option value='500'>正2档</option>
	<option value='700'>正3档</option>
	<option value='900' selected='selected'>正4档</option>
	<option value='1000'>正5档</option>
	</select>";
    }else {
        return "<select name='{$name}'>
	<option value='0'>0档</option>
	<option value='200'>1档</option>
	<option value='500'>2档</option>
	<option value='700'>3档</option>
	<option value='900' selected='selected'>4档</option>
	<option value='1000'>5档</option>
	</select>";
    }
}

function get_select_str11($name, $type = 2){
    if ($type == 2) {
        return "<select name='{$name}'>
	<option value='0'>0档</option>
	<option value='200'>正1档</option>
	<option value='500'>正2档</option>
	<option value='700'>正3档</option>
	<option value='900'>正4档</option>
	<option value='1000' selected='selected'>正5档</option>
	</select>";
    }else {
        return "<select name='{$name}'>
	<option value='0'>0档</option>
	<option value='200'>1档</option>
	<option value='500'>2档</option>
	<option value='700'>3档</option>
	<option value='900'>4档</option>
	<option value='1000' selected='selected'>5档</option>
	</select>";
    }
}

//default  没有比对上
function get_select_str12($name, $value, $type = 2){
	if ($type == 2) {
        if ($value < 0) {
            return "<select name='{$name}'>
<option value=''>请选择档位</option>
<option value='0'>0档</option>
<option value='-200'>负1档</option>
<option value='-500'>负2档</option>
<option value='-700'>负3档</option>
<option value='-900'>负4档</option>
<option value='-1000'>负5档</option>
</select>";
        } else {
            return "<select name='{$name}'>
<option value=''>请选择档位</option>
<option value='0'>0档</option>
	<option value='200'>正1档</option>
	<option value='500'>正2档</option>
	<option value='700'>正3档</option>
	<option value='900'>正4档</option>
	<option value='1000'>正5档</option>
</select>";
        }
	}else {
        return "<select name='{$name}'>
<option value=''>请选择档位</option>
<option value='0'>0档</option>
	<option value='200'>1档</option>
	<option value='500'>2档</option>
	<option value='700'>3档</option>
	<option value='900'>4档</option>
	<option value='1000'>5档</option>
</select>";
	}


}
?>
