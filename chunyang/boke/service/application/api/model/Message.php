<?php
namespace app\api\model;
use think\Db;
use think\Exception;

class Message extends BaseModel{

	public function getCreateTimeAttr($value) {
		return date('Y-m-d H:i',$value);
	}

	public static function getMessageList($page, $size = 5){
		//1.获取分页留言的ID
		$data = self::field(['id'])->where('pid','NULL')->order('id desc')->paginate($size, false, ['page'=>$page]);
		$data = $data->toArray();
		$ids = array_column($data['data'],'id');
		//2.根据ID进行in查询
		$item = self::where('family','in',$ids)->select();
		$item = $item->toArray();
		$treeData = self::genTree($item);
		$sort = array_column($treeData, 'id');
		array_multisort($sort, SORT_DESC, $treeData);
		$data['data'] = $treeData;
		return $data;
	}

	//无限级分类函数
	public static function genTree($arr,$parentid=0){
		$list=array();
		foreach($arr as $key => $v){
			if($v['pid']==$parentid){
				$tmp = self::genTree($arr,$v['id']);
				if($tmp){
					$v['submenu'] = $tmp;
				}
				$list[]=$v;
			}
		}
		return $list;
	}

	public static function addMessage($data) {
		$tempArr = [];

		$tempArr['pid'] = $data['pid'];
		$tempArr['content'] = $data['comment'];
		$tempArr['nike_name'] = $data['comname'];
		$tempArr['link'] = $data['comurl'];
		$tempArr['email'] = $data['commail'];

		Db::startTrans();
		try {
			if ($tempArr['pid'] == 0) {
				unset($tempArr['pid']);
				$message = self::create($tempArr);
				$message->family = $message->id;
				$message->save();
			} else {
				$pidData = self::field(	['id','nike_name','pid','family'])->where('id',$tempArr['pid'])->find();
				$pidData = $pidData->toArray();
				$tempArr['pid'] = $pidData['pid'] ? $pidData['pid'] : $pidData['id'];
				$tempArr['family'] = $pidData['family'];
				$tempArr['reply'] = $pidData['nike_name'];

				self::create($tempArr);
			}
			Db::commit();
			return  ['type' => true];
		} catch(Exception $e) {
			Db::rollback();
			var_dump($e->getMessage());
			return  ['type' => false];
		}
	}

}