<?php
/**
 * Filename: ModuleWeixin.class.php
 *
 * @author liyan
 * @since 2015 7 24
 */
class ModuleWeixin extends BaseModule {

    protected static $token;
    protected static $appID;
    protected static $appSecret;
    protected static $redirectUri;

    public static function setToken($token) {
        self::$token = $token;
    }

    public static function token() {
        return self::$token;
    }

    public static function setAppID($appID) {
        self::$appID = $appID;
    }

    public static function appID() {
        return self::$appID;
    }

    public static function setAppSecret($appSecret) {
        self::$appSecret = $appSecret;
    }

    public static function appSecret() {
        return self::$appSecret;
    }

    public static function setRedirectUri($redirectUri) {
        self::$redirectUri = $redirectUri;
    }

    public static function redirectUri() {
        return self::$redirectUri;
    }

}
