<?php
use think\Route;

Route::get('api/home/news/:type', 'api/home.Home/getNewsList');
Route::get('api/home/news/title', 'api/home.Home/getNewsTitle');
Route::get('api/home/news/detail/:id', 'api/home.Home/getNewsDetail');
Route::get('api/home/news/detailNav/:id', 'api/home.Home/getNewsDetailNav');
Route::get('api/home/news/rand', 'api/home.Home/getNewsByRand');

Route::get('api/home/classify', 'api/home.Home/getClassify');

Route::get('api/home/weiyu', 'api/home.Home/getNewWeiyu');

Route::get('api/home/link', 'api/home.Home/getLink');

