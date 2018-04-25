<?php
namespace app\xcx\model;

class InformationMessage extends BaseModel{

	protected $autoWriteTimestamp = true;

	public function getCreateTimeAttr($value) {
		return $this->theTime($value);
	}

	public function getNickNameAttr($value) {
		return json_decode($value);
	}

	public static function InformationMessageByInforId($id) {
		$field = [
			'a.create_time',
			'a.content',
			'a.uid',
			'b.nick_name',
			'b.head_img',
			'b.id'
		];
		$data = self::alias('a')->field($field)->join('user b','a.uid=b.id')->
		where('information_id',$id)->order('create_time desc')->select();

		return $data;
	}
}