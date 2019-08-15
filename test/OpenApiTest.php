<?php

require_once '../vendor/autoload.php';

use Yestae\OpenApi\OpenApi;

// 加载配置
$conf = [
    'app_id' => 'xxx',                          // app id
    'mch_id' => 'xxx',                          // mch id
    'app_secret' => 'xxx',         // secret
    'interface_url' => 'https://openapi-test.yestae.com/api',   // 接口地址
];


$openApi = new OpenApi($conf);

// 需要访问的路径
$url = '/user/getInfo';

// 参数
$param = [
    'uid' => '1107485144377085954'
];

// 请求
$data = $openApi->postData($url, $param);

var_dump($data);

/**
返回的数据结构
array(8) {
  ["retMsg"]=>
  string(2) "OK"
  ["sign"]=>
  string(32) "20F8E320A6E3CE0F864CC3C0AD940753"
  ["appid"]=>
  string(19) "1130734893714997249"
  ["retCode"]=>
  string(7) "SUCCESS"
  ["result"]=>
  array(6) {
    ["regTime"]=>
    int(1552880009156)
    ["uid"]=>
    string(19) "1107485144377085954"
    ["userType"]=>
    int(1)
    ["mobile"]=>
    string(11) "12000000028"
    ["name"]=>
    string(9) "T47635282"
    ["gender"]=>
    int(0)
  }
  ["resultCode"]=>
  string(7) "SUCCESS"
  ["nonceStr"]=>
  string(16) "DARNEGBDHGKWCOGR"
  ["mchid"]=>
  string(19) "1130734379656904706"
}

 */