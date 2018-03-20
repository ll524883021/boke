<?php
namespace app\api\model;
use think\Db;

class News extends BaseModel{

	public function getCreateAtAttr($value) {
		return date('Y-m-d H:i',$value);
	}

	public static function getNewsListByType($type=null, $page = 1, $size = 2){
		$field = [
			'a.id','a.title','a.content','a.collect','a.comment','a.create_at','b.name'=>'type','c.path'
		];
		if ($type == 'all') {
			$data = self::alias('a')->field($field)->join('type b','a.type=b.id')->join('img c','a.img=c.id')
				->order(['a.id'=>'asc'])->paginate($size, false, ['page'=>$page]);
		} else {
			$data = self::alias('a')->field($field)->join('type b','a.type=b.id')->join('img c','a.img=c.id')
				->where('a.type',$type)->order(['a.create_at'=>'desc'])->paginate($size, false, ['page'=>$page]);
		}

		return $data;
	}

	public static function getNewsTitle() {
		$field = [
			'id','title'
		];
		$data['news'] = self::field($field)->order('create_at desc')->limit(5)->select();
		$data['hot'] = self::field($field)->where('hot',1)->order('create_at desc')->limit(5)->select();

		return $data;
	}

	public static function getNewsDetail($id) {
		$field = [
			'a.id','a.title','a.content','a.collect','a.comment','a.create_at','b.name'=>'type'
		];
		$data = self::alias('a')->field($field)->join('type b','a.type=b.id')->where('a.id',$id)->find();

		return $data;
	}

	public static function getNewsDetailNav($id) {
		$sql = "SELECT id,title FROM news WHERE id IN (SELECT CASE WHEN SIGN(id - $id) > 0 THEN MIN(id) WHEN SIGN(id - $id) < 0 THEN MAX(id) END AS id
FROM news WHERE id <> $id GROUP BY SIGN(id - $id) ORDER BY SIGN(id - $id)) ORDER BY id ASC";
		$data = Db::query($sql);
		$temp = [];
		foreach ($data as $key=>$val) {
			if ($val['id'] > $id) {
				$temp['next'] = $val;
			}
			if ($val['id'] < $id) {
				$temp['prev'] = $val;
			}
		}

		return $temp;
	}

	public static function getNewsByRand() {
		$sql = "SELECT t1.id,t1.title,t1.create_at
FROM `news` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `news`)-(SELECT MIN(id) FROM `news`))+(SELECT MIN(id) FROM `news`)) AS id) AS t2
WHERE t1.id >= t2.id
ORDER BY t1.id LIMIT 5";

		$data = Db::query($sql);
		if ($data) {
			$newsData = [];
			foreach ($data as $val) {
				$temp = [];
				$temp['id'] = $val['id'];
				$temp['title'] = $val['title'];
				$temp['time'] = date('Y-m-d',$val['create_at']);

				$newsData[] = $temp;
			}
		}
		return $newsData;
	}

}