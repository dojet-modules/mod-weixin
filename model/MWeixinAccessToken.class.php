<?php
/**
 *
 * Filename: MWeixinAccessToken.class.php
 *
 * @author liyan
 * @since 2015 7 24
 */
class MWeixinAccessToken {

    protected $openId;
    protected $accessToken;
    protected $refreshToken;
    protected $expiresIn;
    protected $scope;
    protected $unionId;

    function __construct($openId, $accessToken, $expiresIn, $refreshToken, $scope, $unionId) {
        $this->openId = $openId;
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->refreshToken = $refreshToken;
        $this->scope = $scope;
        $this->unionId = $unionId;
    }

    public static function tokenByCode($appID, $appSecret, $code, $grantType = 'authorization_code') {
        $url = LibWeixin::accesstokenUrl($appID, $appSecret, $code, $grantType);
        $curl = MCurl::curlGetRequest($url);
        $curl->setUseProxy(true);
        $response = $curl->sendRequest();
        $data = json_decode($response);

        if (property_exists($data, 'errcode')) {
            throw new Exception($data->errmsg, $data->errcode);
        }

        $unionId = property_exists($data, 'unionid') ? $data->unionid : '';
        $token = new MWeixinAccessToken($data->openid, $data->access_token, $data->expires_in,
            $data->refresh_token, $data->scope, $unionId);

        return $token;
    }

    protected function openId() {
        return $this->openId;
    }

}
