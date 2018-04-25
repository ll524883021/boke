<?php

return [
    'app_id' => 'wx71eccb687226523e',
	'app_secret' => 'b7374bc9b652c7c1dd4b45d41e6c2560',
	// 微信使用code换取用户openid及session_key的url地址
	'login_url' => "https://api.weixin.qq.com/sns/jscode2session?" . "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
	// 微信获取access_token的url地址
	'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?" .
		"grant_type=client_credential&appid=%s&secret=%s",
];