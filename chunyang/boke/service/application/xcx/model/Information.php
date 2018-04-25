<?php
namespace app\xcx\model;

use app\lib\enum\TypeEnum;

class Information extends BaseModel{

	public function getReleaseTimeAttr($value) {
		return $this->theTime($value);
	}

	public function getContentAttr($value) {
		return strip_tags (json_decode($value));
	}

	public function getTypeAttr($value) {
		return TypeEnum::TYPE[$value];
	}

	public function getImgsAttr($value) {
		return json_decode($value,true);
}

	public function getMessageAttr($value) {
		return json_decode($value,true);
	}

	public function getNickNameAttr($value) {
		return json_decode($value);
	}

	public static function informationById($id) {
		$field = [
			'a.id',
			'a.uid',
			'a.content',
			'a.release_time',
			'a.type',
			'a.view',
			'a.collection',
			'a.address',
			'b.nick_name',
			'b.head_img',
			'a.imgs',
			'a.phone',
			'a.latitude',
			'a.longitude'
		];

		$data = self::alias('a')->field($field)->join('user b','a.uid=b.id')
			->where('a.id',$id)->order('a.release_time desc')->find();

		return $data;
	}

	public static function informationAll($uid = false) {
		$field = [
			'a.id',
			'a.uid',
			'a.content',
			'a.release_time',
			'a.type',
			'a.view',
			'b.nick_name',
			'b.head_img',
			'a.imgs',
			'a.address',
			'a.latitude',
			'a.longitude',
			'a.message',
			'a.collection'
		];
		if (!$uid) {
			$data = self::alias('a')->field($field)->join('user b','a.uid=b.id','LEFT')
				->order('a.release_time desc')->select();
		} else {
			$data = self::alias('a')->field($field)->join('user b','a.uid=b.id')
				->where('a.uid',$uid)->order('a.release_time desc')->select();
		}

//		$data = $data->toArray();
//		$field = [
//			'b.url'
//		];
//		foreach ($data as $key => $val) {
//			$data[$key]['imgs'] = self::alias('a')->field($field)->join('information_images b','a.id=b.information_id')->select();
//			var_dump(json_encode($data[$key]['imgs']));exit;
//		}
		return $data;
	}

	public static function meCollection($where) {
		$field = [
			'a.id',
			'a.uid',
			'a.content',
			'a.release_time',
			'a.type',
			'a.view',
			'b.nick_name',
			'b.head_img',
			'a.imgs',
			'a.address',
			'a.latitude',
			'a.longitude',
			'a.message',
			'a.collection'
		];
		$data = self::alias('a')->field($field)->join('user b','a.uid=b.id','LEFT')
			->where('a.id','IN',$where)
			->order('a.release_time desc')->select();

//		$data = $data->toArray();
//		$field = [
//			'b.url'
//		];
//		foreach ($data as $key => $val) {
//			$data[$key]['imgs'] = self::alias('a')->field($field)->join('information_images b','a.id=b.information_id')->select();
//			var_dump(json_encode($data[$key]['imgs']));exit;
//		}
		return $data;
	}

	public static function informationByKey($key) {
		$field = [
			'a.id',
			'a.uid',
			'a.content',
			'a.release_time',
			'a.type',
			'a.view',
			'a.collection',
			'b.nick_name',
			'b.head_img',
			'a.imgs',
			'a.address',
			'a.latitude',
			'a.longitude',
			'a.message'
		];
		$data = self::alias('a')->field($field)->join('user b','a.uid=b.id')->where('a.content','LIKE','%'.$key.'%')
			->order('a.release_time desc')->select();
//		$data = $data->toArray();
//		$field = [
//			'b.url'
//		];
//		foreach ($data as $key => $val) {
//			$data[$key]['imgs'] = self::alias('a')->field($field)->join('information_images b','a.id=b.information_id')->select();
//			var_dump(json_encode($data[$key]['imgs']));exit;
//		}
		return $data;
	}

	public static function addInformation($data) {
		$addArr = [];
		if (!empty($data['uid']) && !empty($data)) {
			if (!empty($data['imgs'])) {
				$addArr['imgs'] = json_encode($data['imgs']);
			} else {
				$addArr['imgs'] = null;
			}
			if ($data['latitude'] && $data['longitude']) {
				$addArr['latitude'] = $data['latitude'];
				$addArr['longitude'] = $data['longitude'];
			}
			$addArr['address'] = $data['address'];
			$addArr['uid'] = $data['uid'];
			$addArr['type'] = 1;
			$addArr['content'] = json_encode($data['textarea']);
			$addArr['release_time'] = time();
			$addArr['phone'] = $data['phone'];
			$addArr['flag'] = $data['switch'];
			return self::create($addArr);
		}

		return $addArr;
	}

	public static function getInformationMessageById($id) {
		$data = self::field(['message'])->where('id',$id)->find();
		$data = $data->toArray();

		return  $data['message'];
	}
}