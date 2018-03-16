<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2018/3/14
 * Time: 11:37
 */

namespace app\api\controller\home;
use app\api\controller\BaseController;
use app\api\model\Link;
use app\api\model\News;
use app\api\model\Type;
use app\api\model\Weiyu;
use app\api\vaildate\TypeMustBePostiveInt;
use app\lib\exception\RequestMissException;

class Home extends BaseController{

	public function getNewsList($type,$page) {
		(new TypeMustBePostiveInt())->goCheck();

		$news = News::getNewsListByType($type, $page);
		if ($news->isEmpty()) {
			throw new RequestMissException();
		} else {
			return json($news);
		}
	}

	public function getNewsDetail($id){
		$newsDetail = News::getNewsDetail($id);
		if (!$newsDetail) {
			throw new RequestMissException();
		}
		return $newsDetail;
	}

	public function getNewsDetailNav($id) {
		$newsDetailNav = News::getNewsDetailNav($id);
		return $newsDetailNav;
	}

	public function getNewsByRand(){
		$newsDetailRand = News::getNewsByRand();
		return $newsDetailRand;
	}

	public function getClassify() {
		$type = Type::getAllType();
		if ($type->isEmpty()) {
			throw new RequestMissException();
		} else {
			return $type;
		}
	}

	public function getNewWeiyu() {
		$newWeiyu = Weiyu::newWeiyu();

		return $newWeiyu;
	}

	public function getNewsTitle() {
		$newsTitle = News::getNewsTitle();
		if (!$newsTitle){
			throw new RequestMissException();
		} else {
			return $newsTitle;
		}
	}

	public function getLink() {
		$link = Link::all();
		if ($link->isEmpty()){
			throw new RequestMissException();
		}
		return $link;
	}

}