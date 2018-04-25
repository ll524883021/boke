<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018/4/1
 * Time: 15:31
 */

namespace app\xcx\model;


class User extends BaseModel {

	protected $hidden = ['delete_time','update_time'];

	public function address() {
		return $this->hasOne('UserAddress','user_id', 'id');
	}

	public static function getByOpenID($openid) {
		$user = self::where('openid', '=', $openid)->find();
		return $user;
	}

	public function getNickNameAttr($value) {
		return json_decode($value);
	}

	public function getIntroAttr($value) {
		return json_decode($value);
	}

	public static function getIatAndLod($uid) {
		$field = [
			'latitude',
			'longitude'
		];
		return self::field($field)->where(['id'=>$uid])->find();
	}

	public static function getUserInfo($id) {
		$data = self::field(['nick_name'])->where('id',$id)->find();

		return $data['nick_name'];
	}
}