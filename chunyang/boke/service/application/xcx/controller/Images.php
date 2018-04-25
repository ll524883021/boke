<?php
namespace app\xcx\controller;

class Images extends BaseController{

	public function upload() {
		$bucket = 'chunyang';
		$object = msectime().rand(0,10000).'.jpg';
		$path = $_FILES['uploadfile_ant']['tmp_name'];

		return [
			'index' => input('post.imgIndex'),
			'url' => uploadFile($bucket,$object,$path)
		];
	}
}