<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018/2/5
 * Time: 14:50
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException {

    public $code = 403;
    public $msg = '权限不够 ';
    public $errorCode = 10001;
}