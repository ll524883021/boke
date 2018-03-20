<?php
namespace app\api\model;

class Type extends BaseModel{

	public static function getAllType() {
		$field = [
			'a.id','a.name','count(b.id)'=>'count'
		];
		$data = self::alias('a')->field($field)->join('news b','a.id=b.type')->group('a.name')->select();

		return $data;
	}
}