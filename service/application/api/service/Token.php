<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018/2/8
 * Time: 19:33
 */

namespace app\api\service;

use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token {

    protected static function generateToken() {
        //32个字符组成一组随机字符串
        $randChars = getRandChar(32);
        //时间戳
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //salt 盐
        $salt = config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);
    }

	public static function getCurrentTokenVar($key) {
		$token = Request::instance()->header('token');
		$vars = Cache::get($token);
		if (!$vars) {
			throw new TokenException();
		} else {
			if (!is_array($vars)) {
				$vars = json_decode($vars, true);
			}
			if (array_key_exists($key,$vars)) {
				return $vars[$key];
			} else {
				throw new Exception('尝试获取的Token变量并不存在');
			}
		}
	}

	public static function getCurrentUid() {
		$uid = self::getCurrentTokenVar('uid');
		return $uid;
	}

	//用户和CMS管理员都可以访问的接口
	public static function needPrimaryScope() {
		$scope = self::getCurrentTokenVar('scope');
		if ($scope) {
			if ($scope >= ScopeEnum::User) {
				return true;
			} else {
				throw new ForbiddenException();
			}
		} else {
			throw new TokenException();
		}
	}

	//只能是用户才可以访问的接口
	public static function needExclusiveScope() {
		$scope = self::getCurrentTokenVar('scope');
		if ($scope) {
			if ($scope == ScopeEnum::User) {
				return true;
			} else {
				throw new ForbiddenException();
			}
		} else {
			throw new TokenException();
		}
	}

	public static function isValidOperate($checkedUID) {
		if (!$checkedUID) {
			throw new Exception('检查UID时必须传入一个被检查的UID');
		}
		$currentOperateUID = self::getCurrentUid();
		if ($currentOperateUID == $checkedUID) {
			return true;
		}
		return false;
	}

	public static function verifyToken($token) {
		$exist = Cache::get($token);
		if ($exist) {
			return true;
		} else {
			return false;
		}
	}
}