<?php

namespace app\api\controller\home;
use app\api\controller\BaseController;
use app\api\model\Message as MessageModel;
use app\api\vaildate\AddMessage;
use app\lib\exception\RequestMissException;

class Message extends BaseController{

	public function getMessageList($page) {
		$message = MessageModel::getMessageList($page);
		if (!$message) {
			throw new RequestMissException();
		}
		return $message;
	}

	public function addMessage() {
		(new AddMessage())->goCheck();
		$post = input('post.');
		$check = MessageModel::addMessage($post);

		return $check;
	}
}