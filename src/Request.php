<?php

namespace Leychan\FeiShuRobot;

class Request
{

    const E_URL_EMPTY = 400;
    const E_MAP = [
        self::E_URL_EMPTY => 'url connot be emoty'
    ];

    /**
     * @desc get请求简单封装
     * @user lei
     * @date 2022/4/18
     * @param $url
     * @param int $timeout
     * @return bool|string
     * @throws \Exception
     */
    public static function get($url, $header = [], $connTimeout = 0, $readTimeout = 0) {
        self::urlEmptycheck($url);
        $ch = curl_init();
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $readTimeout);
        //结果以字符串返回,而不是直接打印
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    /**
     * @desc post请求简单封装
     * @user lei
     * @date 2022/4/18
     * @param string $url
     * @param array $params
     * @param int $json
     * @param int $connTimeout
     * @return bool|string
     * @throws \Exception
     */
    public static function post(string $url = '', array $params = [], $header = [], $json = 0, int $connTimeout = 0, int $readTimeout = 0) {
        self::urlEmptycheck($url);
        $ch = curl_init($url);
        if ($json) {
            $header = array_merge($header, [
                'Content-Type: application/json',
            ]);
            $params = json_encode($params);
        } else {
            $params = http_build_query($params);

        }
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connTimeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $readTimeout);
        //结果以字符串返回,而不是直接打印
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public static function urlEmptycheck($url) {
        if (empty($url)) {
            throw new \Exception(self::E_MAP[self::E_URL_EMPTY], self::E_URL_EMPTY);
        }
    }
}
