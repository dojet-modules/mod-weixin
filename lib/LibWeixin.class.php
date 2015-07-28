<?php
/**
 * description
 *
 * Filename: LibWeixin.class.php
 *
 * @author liyan
 * @since 2015 7 28
 */
class LibWeixin {

    protected static function authorizeUrl($appID, $redirectUri, $type, $scope, $state) {
        $url = sprintf("https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=%s&scope=%s&state=%s#wechat_redirect",
            $appID, $redirectUri, $type, $scope, $state);
        return $url;
    }

    public static function snsapiBaseAuthorizeUrl($appID, $redirectUri, $type = 'code', $state = 1) {
        return self::authorizeUrl($appID, $redirectUri, $type, 'snsapi_base', $state);
    }

    public static function snsapiUserinfoAuthorizeUrl($appID, $redirectUri, $type = 'code', $state = 1) {
        return self::authorizeUrl($appID, $redirectUri, $type, 'snsapi_userinfo', $state);
    }

    public static function accesstokenUrl($appID, $appSecret, $code, $grantType = 'authorization_code') {
        $url = sprintf('https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=%s',
            $appid, $appsecret, $code, $grantType);
        return $url;
    }

}
