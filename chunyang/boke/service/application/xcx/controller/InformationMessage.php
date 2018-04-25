<?php

namespace app\xcx\controller;
use app\xcx\model\Information;
use app\xcx\model\InformationMessage as InformationMessageModel;
use app\xcx\model\User;
use app\xcx\service\Token as TokenService;

class InformationMessage extends BaseController{

	public function postInformationMessage() {
		$id = input('post.id');
		$content = input('post.content');
		$uid = TokenService::getCurrentUid();
		if (!$id || !$content) {
			return;
		}
		$data = [
			'information_id' =>$id,
			'content' => $content,
			'uid' => $uid,
		];
		//1.存数据到用户留言表
		$check = InformationMessageModel::create($data);

		//2.在用户发布表做数据冗余
		$nickName = User::getUserInfo($uid);
		$unshiftArr = [
			'nick_name' => $nickName,
			'content' => $content
		];
		$mseeageInfo = Information::getInformationMessageById($id);
		if (!$mseeageInfo) {
			$mseeageInfo = [];
		}
		$mseeageInfo[] = $unshiftArr;
		$mseeageInfo = array_reverse($mseeageInfo);
		$upInformation = json_encode($mseeageInfo);
		Information::where('id',$id)->update(['message'=>$upInformation]);

		if ($check) {
			$message = InformationMessageModel::InformationMessageByInforId($id);
		}

		return $message;
	}

	public function getInformationMessage($id) {

		$message = InformationMessageModel::InformationMessageByInforId($id);

		return $message;
	}
}