<?php
namespace app\api\model;
use think\Model;

class Weiyu extends Model{

	public function getCreateAtAttr($value) {
		return date('Y-m-d H:i',$value);
	}

	public static function newWeiyu() {
		$data = self::order('create_at desc')->limit(4)->select();

		return $data;
	}
}