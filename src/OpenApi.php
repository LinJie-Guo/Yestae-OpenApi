<?php

namespace Yestae\OpenApi;

/**
 * 用户中心接口类
 */
class OpenApi
{
    private static $conf;

    // vesion 1.0.0
    private $version = '1.0.0';
   
    public function __construct($conf)
    {
        $this->setConf($conf);
    }


    private function setConf($conf)
    {
        if (
            empty($conf['app_id']) ||
            empty($conf['mch_id']) ||
            empty($conf['app_secret']) ||
            empty($conf['interface_url'])
        ) {
            throw new \Exception("yestae-openapi | config error");
        }

        self::$conf = $conf;
    }

    /**
     * 设置传送数据的参数
     * @param array $param 
     * @return  array
     */
    private function setParam($param)
    {
        $conf = self::$conf;
        $timestamp = $this->millisecond();
        $param_array = json_encode($param);

        $stringSignTemp = "appid={$conf['app_id']}&bizcontent={$param_array}&mchid={$conf['mch_id']}".'&timestamp='.$timestamp.'&version=1.0' . $conf['app_secret'];

        $sign = strtoupper(md5($stringSignTemp));

        return [
            'sign' => $sign,
            'timestamp' => $timestamp,
            'appid' => $conf['app_id'],
            'bizcontent' => $param_array,
            'mchid' => $conf['mch_id'],
            'version' => '1.0'
        ];
    }

    private function getCurl($url, $arrayParam, $type)
    {
        $conf = self::$conf;

        $curl_url = $conf['interface_url'] . $url;

        $ch = curl_init($curl_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($arrayParam));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 跳过证书检查
        curl_setopt($ch, CURLOPT_TIMEOUT, 3000);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception("yestae-openapi | getCurl error! ");
        }

        if($result === false) {
            throw new \Exception("yestae-openapi | getCurl $result False! ");
        }

        curl_close($ch);

        return $result;
    }

    private function postCurl($url, $arrayParam)
    {
        $conf = self::$conf;
        $curlurl = $conf['interface_url'] . $url;

        $ch = curl_init($curlurl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($arrayParam));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//// 跳过证书检查
        curl_setopt($ch, CURLOPT_TIMEOUT, 3000);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception("yestae-openapi | postCurl error!");
        }

        if($result === false) {
            throw new \Exception("yestae-openapi | postCurl  $result False!");
        }

        curl_close($ch);
        return $result;
    }

    public function getData($url, $array, $type='POST') 
    {
        $conf = self::$conf;
        $queryParam = $this->setParam($array);

        $res = $this->getCurl($url, $queryParam, $type);

        $array['appid'] = $conf['app_id'];
        $array['mchid'] = $conf['mch_id'];
        $array['timestamp'] = $this->millisecond();

        return json_decode($res, true);
    }


    public function postData($url, $param)
    {
        $param = $this->setParam($param);
        $rs = $this->postCurl($url, $param);
        return json_decode($rs, true);
    }

    /**
     * 时间戳-毫秒级
     * @return int
     */
    private function millisecond()
    {
        $time = microtime(true);
        $time = explode('.', $time);
        $time[1] = isset($time[1]) ? "{$time[1]}" : '000';
        empty($time[1]) && $time[1] = 0;
        $time = $time[0] . $time[1] . str_repeat('0', 4-strlen($time[1]));

        return substr($time, 0, -1);
    }

}