<?php
namespace app\lib\exception;

class RequestMissException extends BaseException {

    public $code = 404;
    public $msg = '请求的内容不存在 ';
    public $errorCode = 40000;
}