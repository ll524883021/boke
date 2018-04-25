<?php
namespace app\xcx\controller;

use app\xcx\service\UserToken;
use app\xcx\validate\TokenGet;
use app\xcx\service\Token as TokenService;

class Token {

	public function getToken($code='') {
		(new TokenGet())->goCheck();
		$ut = new UserToken($code);
		$token = $ut->get();

		return [
			'token' => $token
		];
	}

	public function verifyToken($token='') {
		if (!$token) {
			throw new ParameterException([
				'msg' => 'token不允许为空'
			]);
		}
		$vaild = TokenService::verifyToken($token);
		return [
			'isValid' => $vaild
		];
	}
}