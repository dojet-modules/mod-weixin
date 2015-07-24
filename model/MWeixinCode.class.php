<?php
/**
 *
 * Filename: MWeixinCode.class.php
 *
 * @author liyan
 * @since 2015 7 24
 */
class MWeixinCode {

    public static function getWeixinCode($appID, $redirectUri, $responseType, $scope, $type) {
        $url = sprintf('https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code', $appid, $appsecret, $code);
        $curl = MCurl::curlGetRequest($url);
        $curl->setUseProxy(true);
        $response = $curl->sendRequest();
        $data = json_decode($response);

        if (property_exists($data, 'openid')) {
            $openid = $data->openid;
            $accesstoken = $data->access_token;
        } else {
            $openid = 'test_user_abc';
            $accesstoken = 'test_abc';
        }

        return $openid;
    }

}
