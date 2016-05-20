<?php
/**
 * 微信基类
 *
 * Filename: WeixinBaseAction.class.php
 *
 * @author liyan
 * @since 2015 7 24
 */
abstract class WeixinBaseAction extends XBaseAction {

    protected $xmlObj;
    protected $fromUser;
    protected $toUser;

    final public function execute() {

        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        Trace::debug('post: '.serialize($postStr));

        $echostr = MRequest::get('echostr');
        if (!empty($echostr)) {
            Trace::debug('echostr '.$echostr);
            echo $echostr;
            return;
        }
//*
        if (!$this->checkSignature()) {
            $this->signatureFailed();
            return;
        }
//*/
        //extract post data
        if (empty($postStr)){
            Trace::debug('empty poststr');
            $this->errorOccured();
            return;
        }

        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        $this->fromUser = $postObj->FromUserName;
        $this->toUser = $postObj->ToUserName;

        $this->xmlObj = $postObj;

        $msgtype = strval($postObj->MsgType);

        if ('text' === $msgtype) {
            $text = strval($postObj->Content);
            $this->receivedText($postObj, $text);
        } elseif ('event' === $msgtype) {
            $event = strval($postObj->Event);
            $eventKey = strval($postObj->EventKey);
            $this->receivedEvent($postObj, $event, $eventKey);
        } else {
            $this->receivedUnknown($postObj);
        }

    }

    protected function receivedText($postObj, $text) {}

    protected function receivedEvent($postObj, $event, $eventKey) {}

    protected function receivedUnknown($postObj) {}

    private function checkSignature() {
        $signature = MRequest::get("signature");
        $timestamp = MRequest::get("timestamp");
        $nonce = MRequest::get("nonce");

        // $token = ModuleWeixin::module()->token();
        $token = ModuleWeixin::config('token');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr !== $signature ) {
            Trace::debug('check signature: '.serialize($_GET));
            Trace::debug('tmpstr: '.$tmpStr);
            Trace::debug('debugsigfail:'.serialize($_GET).$tmpStr);
            return false;
        }
        return true;
    }

    protected function signatureFailed() {
        Trace::debug('check signature failed');
    }

    protected function errorOccured() {
        Trace::debug('someting wrong!');
    }

    protected function displayXML($xml) {
        print $xml;
    }

    protected function respondText($content, $flag = 0) {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%u</FuncFlag>
                    </xml>";
        $response = sprintf($textTpl, $this->fromUser, $this->toUser, time(), 'text', $content, $flag);
        $this->displayXML($response);
    }

    protected function respondNews($arrWeixinNews, $flag = 0) {
        $tpl = <<<heredoc
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>%s</Articles>
<FuncFlag>%s</FuncFlag>
</xml>
heredoc;

        $itemTpl = <<<heredoc
<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>
heredoc;

        $strArticles = '';
        foreach ($arrWeixinNews as $news) {
            DAssert::assert($news instanceof MWeixinNews, 'illegal news, must be MWeixinNews');
            $strItem = sprintf($itemTpl, $news->title(), $news->desc(), $news->picurl(), $news->url());
            $strArticles.= $strItem;
        }

        $strRespond = sprintf($tpl, $this->fromUser, $this->toUser, time(),
            count($arrWeixinNews), $strArticles, $flag);
        $this->displayXML($strRespond);
    }

    protected function respondTextNews($arrWeixinNews, $flag = 0) {
        $tpl = <<<heredoc
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>%s</Articles>
<FuncFlag>%s</FuncFlag>
</xml>
heredoc;

        $itemTpl = <<<heredoc
<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[]]></PicUrl>
</item>
heredoc;

        $strArticles = '';
        foreach ($arrWeixinNews as $news) {
            DAssert::assert($news instanceof MWeixinNews, 'illegal news, must be MWeixinNews');
            $strItem = sprintf($itemTpl, $news->title(), $news->desc());
            $strArticles.= $strItem;
        }

        $strRespond = sprintf($tpl, $this->fromUser, $this->toUser, time(),
            count($arrWeixinNews), $strArticles, $flag);
        $this->displayXML($strRespond);
    }

    protected function getOpenid() {
        $code = MRequest::get('code');
        $appid = Config::runtimeConfigForKeyPath('weixin.$.appid');
        $appsecret = Config::runtimeConfigForKeyPath('weixin.$.appsecret');
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
