<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018/2/5
 * Time: 14:50
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException {

    public $code = 201;
    public $msg = 'ok ';
    public $errorCode = 0;
}