<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018/2/5
 * Time: 17:16
 */

namespace app\lib\exception;

class ProductException extends BaseException {
    public $code = 404;
    public $msg = '指定类目不存在，请检查参数';
    public $errorCode = 50000;
}