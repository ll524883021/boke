<?php

namespace app\xcx\controller;
use app\lib\exception\ParameterException;
use app\xcx\model\Information as InformationModel;
use app\xcx\model\InformationCollection;
use app\xcx\model\InformationMessage;
use app\xcx\model\User;
use app\xcx\service\Token as TokenService;
use think\Exception;

class Information extends BaseController{

	public function getInformation() {
		$info = InformationModel::informationAll();

		return $info;
	}

	public function getInformationListByUser() {
		$uid = TokenService::getCurrentUid();
		$info = InformationModel::informationAll($uid);

		return $info;
	}

	public function getInformationDistance() {
		$info = InformationModel::informationAll();
		$data = $info->toArray();
		$location = User::getIatAndLod(TokenService::getCurrentUid());
		foreach ($data as $key => $val) {
			$temp = $this->getdistance($location['longitude'],$location['latitude'],$val['longitude'],$val['latitude']);
			$data[$key]['distance'] = $temp['distance'];
			$data[$key]['meter'] = $temp['meter'];
		}
		$orderKey = array_column($data,'meter');
		array_multisort($data,SORT_DESC,SORT_NUMERIC  ,$orderKey);

		return $data;
	}

	public function getInformationById($id) {
		$infoDetail = InformationModel::informationById($id);
		$infoDetail = $infoDetail->toArray();
		if (!empty($infoDetail['latitude'])) {
			$uid = TokenService::getCurrentUid();
			$location = User::getIatAndLod($uid);
			$res = $this->getdistance(
				$infoDetail['longitude'],$infoDetail['latitude'],$location['longitude'],$location['latitude']
			);
			$infoDetail['location'] = $res['distance'];
		}
		unset($infoDetail['longitude']);
		unset($infoDetail['latitude']);

		return $infoDetail;
	}

	public function getInformationByKey($key) {
		$key = urldecode($key);
		$key = json_encode($key);
		$key = str_replace('"','',$key);
		$key = addslashes($key);
		$searchData = InformationModel::informationByKey($key);

		return $searchData;
	}

	public function addInformation() {
		$data = input('post.');
		$data['uid'] = TokenService::getCurrentUid();
		$check = InformationModel::addInformation($data);
		if (!$check){
			throw new Exception();
		} else {
			return [
				'check' => 'success'
			];
		}
	}

	public function postJiaJiaView($id) {
		$infoView = InformationModel::get(function($query) use ($id){
			$query->field(['view'])->where('id',$id);
		});
		$infoView->view = ++$infoView->view;
		$viewNum = $infoView->view;
		$infoView->save();

		return $viewNum;
	}

	public function deleteData() {
		$infoId = input('post.id');
		$uid = TokenService::getCurrentUid();
		$inforUid = InformationModel::get(function($query) use ($infoId){
			$query->field(['uid'])->where('id',$infoId);
		});
		$inforUid = $inforUid->toArray();
		if ($uid == $inforUid['uid']) {
			return InformationModel::destroy($infoId);
		} else {
			throw new ParameterException();
		}

	}

	public function collection() {
		$informationId = input('post.informationId');
		$uid = TokenService::getCurrentUid();
		if (!$informationId || !$uid ){
			throw new ParameterException();
		} else {
			$check = InformationCollection::get(function($query)use ($informationId,$uid){
				$query->where('uid',$uid)->where('information_id',$informationId);
			});

			if (!$check) {
				//1.如果没有关注，增加一条记录
				InformationCollection::create([
					'uid'=>$uid,
					'information_id' => $informationId,
					'is_collection' => 1
				]);
				$informodel = InformationModel::get($informationId);
				$informodel->collection = $informodel->collection + 1;
				$informodel->save();
			} else if ($check->is_collection == 1){
				$check->is_collection = 0;
				$check->save();
				$informodel = InformationModel::get($informationId);
				$informodel->collection = $informodel->collection - 1;
				$informodel->save();
			} else if ($check->is_collection == 0) {
				$check->is_collection = 1;
				$check->save();
				$informodel = InformationModel::get($informationId);
				$informodel->collection = $informodel->collection + 1;
				$informodel->save();
			}
		}

		return $this->getInformationById($informationId);

	}

	public function getMeCollection() {
		$uid = TokenService::getCurrentUid();
		$inFormationIdList = InformationCollection::all(function($query)use ($uid){
			$query->field(['information_id'])->where('uid',$uid)->where('is_collection',1);
		});
		$inArray = array_column($inFormationIdList->toArray(),'information_id');
		$meCollection = InformationModel::meCollection($inArray);
		if (!$meCollection) {
			throw new ParameterException();
		}
		return $meCollection;
	}

	public function getMeReply() {
		$uid = TokenService::getCurrentUid();
		$inFormationIdList = InformationMessage::all(function($query) use ($uid) {
			$query->field(['information_id'])->where('uid',$uid);
		});
		$inArray = array_column($inFormationIdList->toArray(),'information_id');
		$meCollection = InformationModel::meCollection($inArray);

		if (!$meCollection) {
			throw new ParameterException();
		}
		return $meCollection;
	}
}