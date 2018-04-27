<?php

namespace app\xcx\controller;

use app\lib\exception\ParameterException;
use app\xcx\model\Biji;
use app\xcx\service\Token;

class BiJiBen extends BaseController{

	public function addBiJiBen() {
		$bijibenName = input('post.name');
		$uid = Token::getCurrentUid();

		$check = Biji::create([
			'uid' => $uid,
			'name' => json_encode($bijibenName)
		]);
		if (!$check) {
			throw new ParameterException();
		}

		return $check;
	}

	public function getBiJiBen() {
		$uid = Token::getCurrentUid();
		$data = Biji::all(function($query)use ($uid){
			$query->where('uid',$uid);
		});
		if ($data->isEmpty()) {
			throw new ParameterException();
		} else {
			return $data;
		}
	}

	public function updateBiJiBen() {
		$bijibenName = input('post.name');
		$id = input('post.id');
		$uid = Token::getCurrentUid();
		$bijiInfo = Biji::get(function($query) use ($id){
			$query->field(['uid'])->where('id',$id);
		});
		var_dump($bijiInfo,$id);exit;
	}
}