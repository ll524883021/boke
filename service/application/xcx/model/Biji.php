<?php
namespace app\xcx\model;

class Biji extends BaseModel{

	public function getNameAttr($value) {
		return json_decode($value);
	}
}