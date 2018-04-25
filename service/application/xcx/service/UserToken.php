<?php

namespace app\xcx\service;

use app\xcx\model\User;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token{
	protected $code;
	protected $wxAppID;
	protected $wxAppSecret;
	protected $wxLoginUrl;

	public function __construct($code) {
		$this->code = $code;
		$this->wxAppID = config('wx.app_id');
		$this->wxAppSecret = config('wx.app_secret');
		$this->wxLoginUrl = sprintf(config('wx.login_url'),$this->wxAppID,$this->wxAppSecret,$this->code);
	}

	public function get() {
		var_dump($this->wxLoginUrl);exit;
		$result = curl_get($this->wxLoginUrl);
		$wxResult = json_decode($result, true);
		if (empty($wxResult)) {
			throw new Exception('获取session_key及openId时异常');
		} else {
			$loginFail = array_key_exists('errcode',$wxResult);
			if ($loginFail) {
				$this->processLoginError($wxResult);
			} else {
				return  $this->grantToken($wxResult);
			}
		}
	}

	private function grantToken($wxResult) {
		// 拿到openid
		// 数据库看一下，这个openid是不是已经存在
		// 如果存在 则不处理，如果不存在那么新增一条user记录
		// 生成令牌，准备缓存数据，写入缓存
		// 把令牌返回到客户端去
		$openid = $wxResult['openid'];
		$user = User::getByOpenID($openid);
		if ($user) {
			$uid = $user->id;
		} else {
			$uid = $this->newUser($openid);
		}
		$cacheValue = $this->prepareCachedValue($wxResult, $uid);
		$token = $this->saveTocache($cacheValue);

		return $token;
	}

	private function newUser($openid) {
		$user = User::create([
			'openid' => $openid
		]);
		return $user->id;
	}

	private function saveTocache($cachedValue) {
		$key = self::generateToken();
		$value = json_encode($cachedValue);
		$expire_in = config('setting.token_expire_id');

		$request = cache($key, $value, $expire_in);
		if (!$request) {
			throw new TokenException([
				'msg' => '服务器缓存异常',
				'errorCode' => '10005',
			]);
		}
		return $key;
	}

	private function prepareCachedValue($wxResult, $uid) {
		$cachedValue = $wxResult;
		$cachedValue['uid'] = $uid;
		$cachedValue['scope'] = ScopeEnum::User;

		return $cachedValue;
	}

	private function processLoginError($wxResult) {
		throw new WeChatException([
			'msg' => $wxResult['errmsg'],
			'errorCode' => $wxResult['errcode']
		]);
	}

}