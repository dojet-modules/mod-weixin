<?php
/**
 * description
 *
 * Filename: MWeixinNews.class.php
 *
 * @author liyan
 * @since 2014 12 10
 */
class MWeixinNews {

    private $title;
    private $desc;
    private $picurl;
    private $url;

    public static function news($title, $desc, $picurl, $url) {
        $news = new MWeixinNews();
        $news->setTitle($title);
        $news->setDesc($desc);
        $news->setPicurl($picurl);
        $news->setUrl($url);
        return $news;
    }

    public static function textNews($title, $desc, $picurl) {
        $news = new MWeixinNews();
        $news->setTitle($title);
        $news->setDesc($desc);
        $news->setPicurl($picurl);
        return $news;
    }

    public static function linkNews($title, $desc, $url) {
        $news = new MWeixinNews();
        $news->setTitle($title);
        $news->setDesc($desc);
        $news->setUrl($url);
        return $news;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function title() {
        return $this->title;
    }

    public function setDesc($desc) {
        $this->desc = $desc;
    }

    public function desc() {
        return $this->desc;
    }

    public function setPicurl($picurl) {
        $this->picurl = $picurl;
    }

    public function picurl() {
        return $this->picurl;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function url() {
        return $this->url;
    }

}
