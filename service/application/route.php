<?php
use think\Route;

Route::get('api/home/news/:type', 'api/home.Home/getNewsList');
Route::get('api/home/news/title', 'api/home.Home/getNewsTitle');
Route::get('api/home/news/detail/:id', 'api/home.Home/getNewsDetail');
Route::get('api/home/news/detailNav/:id', 'api/home.Home/getNewsDetailNav');
Route::get('api/home/news/rand', 'api/home.Home/getNewsByRand');

Route::get('api/home/classify', 'api/home.Home/getClassify');

Route::get('api/home/weiyu', 'api/home.Home/getNewWeiyu');
Route::get('api/home/weiyu/list', 'api/home.Home/getWeiyuList');

Route::get('api/home/link', 'api/home.Home/getLink');

Route::get('api/home/message', 'api/home.Message/getMessageList');

Route::post('api/home/message/add', 'api/home.Message/addMessage');

//小程序路由接口
Route::get('xcx/informations', 'xcx/Information/getInformation');
Route::get('xcx/informations/user', 'xcx/Information/getInformationListByUser');
Route::get('xcx/informations/distance', 'xcx/Information/getInformationDistance');
Route::get('xcx/informations/detail/:id', 'xcx/Information/getInformationById');
Route::get('xcx/informations/key/:key', 'xcx/Information/getInformationByKey');
Route::post('xcx/informations/add', 'xcx/Information/addInformation');
Route::post('xcx/informations/view', 'xcx/Information/postJiaJiaView');
Route::post('xcx/informations/delete', 'xcx/Information/deleteData');
Route::post('xcx/informations/collection', 'xcx/Information/collection');
Route::get('xcx/informations/collections', 'xcx/Information/getMeCollection');
Route::get('xcx/informations/reply', 'xcx/Information/getMeReply');


Route::post('xcx/informations/message', 'xcx/InformationMessage/postInformationMessage');

Route::get('xcx/informations/message/:id', 'xcx/InformationMessage/getInformationMessage');
//token验证
Route::post('xcx/token/user','xcx/Token/getToken');
Route::post('xcx/token/verify','xcx/Token/verifyToken');

//用户操作
Route::get('xcx/user/info','xcx/User/getUserInfo');
Route::post('xcx/user/info','xcx/User/updataPersonal');
Route::post('xcx/user/update','xcx/User/updateUserInfo');

//图片上传
Route::post('xcx/images/upload','xcx/Images/upload');

//笔记本
Route::post('xcx/bijiben/add','xcx/BiJiBen/addBiJiBen');
Route::post('xcx/bijiben/update','xcx/BiJiBen/addBiJiBen');
Route::get('xcx/bijiben','xcx/BiJiBen/getBiJiBen');

