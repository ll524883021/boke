<?php
use OSS\OssClient;
use OSS\Core\OssException;


// 应用公共文件
//function curl_get($url, &$httpCode = 0) {
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $url);
//    // 设置是否输出结果
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//	// 设置是否输出header
//	curl_setopt($ch, CURLOPT_HEADER, false);
//    // 设置是否检查服务器端的证书
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
////    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//    $file_contents = curl_exec($ch);
//    var_dump($file_contents);exit;
//    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//    curl_close($ch);
//
//    return $file_contents;
//}

function curl_get($url) {
	$data = '';

	if (!empty($url) && function_exists('curl_init')) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, TRUE);
		
		if (!curl_exec($ch)) {
			error_log(curl_errno($ch).':'.curl_error($ch));
			$data = '';
		} else {
			$data = curl_multi_getcontent($ch);
		}
		curl_close($ch);
	}
	var_dump($data);exit;
	return $data;
}


function curl_post_raw($url, $rawData) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
	curl_setopt(
		$ch, CURLOPT_HTTPHEADER,
		array(
			'Content-Type: text'
		)
	);
	$data = curl_exec($ch);
	curl_close($ch);
	return ($data);
}

function getRandChar($length) {
    $str = null;
    $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    $max = strlen($strPol) - 1;

    for ($i=0;$i<$length;$i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}

//返回当前的毫秒时间戳
function msectime() {
	list($msec, $sec) = explode(' ', microtime());
	$msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

	return $msectime;
}

/**
 * 实例化阿里云OSS
 * @return object 实例化得到的对象
 * @return 此步作为共用对象，可提供给多个模块统一调用
 */
function new_oss(){
	//获取配置项，并赋值给对象$config
	$config=config('aliyun_oss');
	//实例化OSS
	$oss=new \OSS\OssClient($config['KeyId'],$config['KeySecret'],$config['Endpoint']);
	return $oss;
}

/**
 * 上传指定的本地文件内容
 *
 * @param OssClient $ossClient OSSClient实例
 * @param string $bucket 存储空间名称
 * @param string $object 上传的文件名称
 * @param string $Path 本地文件路径
 * @return null
 */
function uploadFile($bucket,$object,$Path){
	//try 要执行的代码,如果代码执行过程中某一条语句发生异常,则程序直接跳转到CATCH块中,由$e收集错误信息和显示
	try{
		//没忘吧，new_oss()是我们上一步所写的自定义函数
		$ossClient = new_oss();
		//uploadFile的上传方法
		$result = $ossClient->uploadFile($bucket, $object, $Path);
	} catch(OssException $e) {
		//如果出错这里返回报错信息
		return $e->getMessage();
	}
	//否则，完成上传操作
	return $result['info']['url'];
}