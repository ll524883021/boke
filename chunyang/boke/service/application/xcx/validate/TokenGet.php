<?php
namespace app\xcx\validate;


class TokenGet extends BaseValidate{

	protected $rule = [
		'code' => 'require|isNotEmpty'
	];

	protected $message = [
		'code' => 'code不存在'
	];
}