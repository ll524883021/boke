<?php
namespace app\api\model;

class Weiyu extends BaseModel{

	public static function newWeiyu() {
		$data = self::order('create_at desc')->limit(4)->select();

		return $data;
	}

	public static function getWeiYuList($page = 1, $size = 10){

		$data = self::order(['create_at'=>'desc'])->paginate($size, false, ['page'=>$page]);

		return $data;
	}
}