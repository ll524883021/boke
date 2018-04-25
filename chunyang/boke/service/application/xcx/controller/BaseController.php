<?php

namespace app\xcx\controller;
use think\Controller;

class BaseController extends Controller{

	public function getdistance($lng1, $lat1, $lng2, $lat2) {
		// 将角度转为狐度
		$radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
		$radLat2 = deg2rad($lat2);
		$radLng1 = deg2rad($lng1);
		$radLng2 = deg2rad($lng2);
		$a = $radLat1 - $radLat2;
		$b = $radLng1 - $radLng2;
		$s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
		
		if ( strlen(explode('.',$s)[0]) > 3 ) {
			$distance =  '≈'.round(($s / 1000),2) .'km';
		} else {
			$distance =  '≈'.round($s,0).'m';
		}

		return [
			'distance' => $distance,
			'meter' => $s
		];
	}

}