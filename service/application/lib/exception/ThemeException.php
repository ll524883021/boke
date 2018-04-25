<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018/2/5
 * Time: 17:16
 */

namespace app\lib\exception;

class ThemeException extends BaseException {
    public $code = 404;
    public $msg = '请求的主题不存在';
    public $errorCode = 30000;
}