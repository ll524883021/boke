<?php

namespace app\xcx\model;
use think\Model;
use traits\model\SoftDelete;

class BaseModel extends Model{

	use SoftDelete;
	protected  $deleteTime = 'delete_time';//必须
	protected $autoWriteTimestamp = true;
	/**
	 * 时间转换
	 */
	protected function theTime($the_time) {
		$now_time  = date("Y-m-d H:i:s", time());
		$now_time  = strtotime($now_time);
		$dur       = $now_time - $the_time;

		if ($dur < 0) {
			return date("Y-m-d H:i", $the_time);
		} else {
			if ($dur < 60) {
				return "刚刚";
			} else {
				if ($dur < 3600) {
					return floor($dur / 60) . "分钟前";
				} else {
					if ($dur < 86400) {
						return floor($dur / 3600) . "小时前";
					} else {
						if ($dur < 259200) {//3天内
							return floor($dur / 86400) . "天前";
						} else {
							return date("Y-m-d H:i", $the_time);
						}
					}
				}
			}
		}
	}
}