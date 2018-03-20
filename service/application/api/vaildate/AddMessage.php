<?php
namespace app\api\vaildate;

class AddMessage extends BaseVaildate{

	protected $rule = [
		'comname' => 'require|isNotEmpty|limitNameLength',
		'comment' => 'require|isNotEmpty|limitConentLength',
		'pid' => 'require|checkPid',
	];

    protected $message = [
		'comname' => '昵称必填或超过长度',
		'comment' => '内容必填或超过长度',
		'pid' => '非法的pid',
    ];

	protected function limitNameLength($value){
		if (mb_strlen($value) >  10) {
			return false;
		} else {
			return true;
		}
	}

	protected function limitConentLength($value){
		if (mb_strlen($value) >  200) {
			return false;
		} else {
			return true;
		}
	}

	protected function checkPid($value){
		if (!is_numeric($value)) {
			return false;
		} else {
			return true;
		}
	}
}