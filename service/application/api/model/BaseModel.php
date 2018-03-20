<?php
namespace app\api\model;
use think\Model;

class BaseModel extends Model{

	protected $autoWriteTimestamp = true;

	public function getCreateAtAttr($value) {
		return date('Y-m-d H:i',$value);
	}
}