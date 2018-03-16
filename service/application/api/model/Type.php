<?php
namespace app\api\model;
use think\Db;
use think\Model;

class Type extends Model{

	public static function getAllType() {
		$field = [
			'a.id','a.name','count(b.id)'=>'count'
		];
//		$data = self::alias('a')->field($field)->join('news b','a.id=b.type')->group('a.name')->select();
		$sql = "select a.id,a.name,count(b.id) as count from type a INNER JOIN news b on a.id=b.type GROUP by a.name";
		$data = Db::query($sql);
		return $data;
	}
}