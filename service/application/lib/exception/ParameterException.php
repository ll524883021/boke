<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018/2/5
 * Time: 17:16
 */

namespace app\lib\exception;

class ParameterException extends BaseException {
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}