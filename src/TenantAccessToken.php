<?php

namespace Leychan\FeiShuRobot;

class TenantAccessToken
{

    private $appId = '';

    private $appSecret = '';

    const URL = 'https://open.feishu.cn/open-apis/auth/v3/tenant_access_token/internal';

    /**
     * @desc 设置appid
     * @user lei
     * @date 2022/6/7
     * @param string $appid
     * @return $this
     */
    public function setAppId(string $appid): TenantAccessToken
    {
        $this->appId = $appid;
        return $this;
    }

    /**
     * @desc 设置appsecret
     * @user lei
     * @date 2022/6/7
     * @param string $appSecret
     * @return $this
     */
    public function setAppSecret(string $appSecret): TenantAccessToken
    {
        $this->appSecret = $appSecret;
        return $this;
    }

    /**
     * @desc 获取accessToken
     * @user lei
     * @date 2022/6/7
     * @return mixed|string
     * @throws \Exception
     */
    public function getAccessToken()
    {
        $res = Request::post(self::URL, [
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret
        ], [], 1);
        $response = json_decode($res, true);
        if (json_last_error() != JSON_ERROR_NONE || $response['code'] != 0) {
            throw new \Exception('get feishu tenant_access_token error');
        }
        return $response['tenant_access_token'] ?? '';
    }
}