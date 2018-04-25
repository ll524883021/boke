<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018/2/5
 * Time: 14:50
 */

namespace app\lib\exception;


class BannerMissException extends BaseException {

    public $code = 404;
    public $msg = '请求的Banner不存在 ';
    public $errorCode = 40000;
}