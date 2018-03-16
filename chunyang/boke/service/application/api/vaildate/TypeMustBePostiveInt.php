<?php
namespace app\api\vaildate;

class TypeMustBePostiveInt extends BaseVaildate{

    protected $rule = [
        'type' => 'checkType',
    ];

    protected $message = [
        'type' => 'type必须是正整数或者是all'
    ];

	protected function checkType($value) {
		if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0 || $value == 'all') {
			return true;
		} else {
			return false;
		}
	}
}