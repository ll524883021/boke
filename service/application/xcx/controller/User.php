<?php
namespace app\xcx\controller;
use app\xcx\model\User as UserModel;
use app\xcx\service\UserToken;

class User extends BaseController{

	public function updateUserInfo() {
		$userInfo = input('post.');
		$uid = UserToken::getCurrentUid();

		$data = [
			'nick_name' => json_encode($userInfo['nickName']),
			'sex' => $userInfo['gender'],
			'city' => $userInfo['city'],
			'province' => $userInfo['province'],
			'country' => $userInfo['country'],
			'head_img' => $userInfo['avatarUrl'],
		];

		return UserModel::update($data,['id'=>$uid]);
	}

	public function getUserInfo() {
		$uid = UserToken::getCurrentUid();
		$userInfo= UserModel::get($uid);

		return $userInfo;
	}

	public function updataPersonal() {
		$uid = UserToken::getCurrentUid();
		$postData = input('post.');
		$saveData= [];
		$saveData['intro'] =  json_encode($postData['userInfo']);
		$saveData['sex'] =  $postData['sex'];
		$saveData['tel'] =  $postData['phone'];
		$saveData['address'] =  $postData['address'];

		$check = UserModel::update($saveData,['id'=>$uid]);
		return  $check;
	}
}