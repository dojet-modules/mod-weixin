<?php
/**
 *
 * Filename: MWeixinNews.class.php
 *
 * @author liyan
 * @since 2014 12 10
 */
class MWeixinNews {

    protected $fields = array('title' => null, 'text' => null, 'picurl' => null, 'url' => null);

    function __construct($title, $text, $picurl, $url) {
        $this->fields = array(
            'title' => $title,
            'text' => $text,
            'picurl' => $picurl,
            'url' => $url,
            );
    }

    public static function news($title, $text, $picurl, $url) {
        return new MWeixinNews($title, $text, $picurl, $url);
    }

    public static function textOnlyNews($text) {
        return new MWeixinNews('', $text, '', '');
    }

    public static function linkNews($title, $text, $url) {
        return new MWeixinNews($title, $text, '', $url);
    }

    public function xml() {
        $xmlTpl = <<<heredoc
<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>
heredoc;
        return sprintf($xmlTpl,
            $this->fields['title'],
            $this->fields['text'],
            $this->fields['picurl'],
            $this->fields['url'],
            );
    }

    public function __get($key) {
        DAssert::assertKeyExists($key, $this->fields);
        return $this->fields[$key];
    }

}
