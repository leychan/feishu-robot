<?php

namespace Leychan\FeiShuRobot;

abstract class Robot {

    const TYPE_TEXT = 'text';
    const TYPE_MEDIA = 'post';

    /**
     * @var string url 推送的地址
     */
    public $webhook = '';

    /**
     * @var string text
     */
    public $msgType = '';

    /**
     * @var \Exception 错误实例
     */
    public $e;

    /**
     * @var string 类名
     */
    public $className = '';

    /**
     * @var string 方法名
     */
    public $methodName = '';

    /**
     * @var string 消息字符串正文
     */
    protected static $contentStr = '';

    /**
     * @var string 富文本消息字符串正文
     */
    protected static $mediaArr = [];

    /**
     * @var array 消息体正文
     */
    protected $body = [];

    /**
     * @var array 内容数组,用来构建富文本消息内容
     */
    public $content = [];

    /**
     * @var string 富文本的标题内容
     */
    public $title = '';

    public function __construct($webhook, $msgType) {
        $this->webhook = $webhook;
        $this->msgType = $msgType;
    }

    /**
     * @desc 发送通知
     * @user lei
     * @date 2022/4/18
     */
    public function notify() {
        if ($this->msgType == self::TYPE_TEXT) {
            $this->generateText();
            Request::post($this->webhook, $this->body, 1);
            return;
        }
        $this->generateMedia();
        Request::post($this->webhook, $this->body, 1);
    }

    /**
     * @desc 构建富文本消息正文数组
     * @user lei
     * @date 2022/4/19
     */
    private function generateMediaArr()
    {
        $this->generateMediaContent();
        static::$mediaArr = [
            'post' => [
                'zh_cn' => [
                    'title' => $this->title,
                    'content' => $this->content
                ]
            ]
        ];
    }

    /**
     * @desc 构建请求体参数
     * @user lei
     * @date 2022/4/18
     */
    private function generateText() {
        $this->generateContentStr();
        $this->body = [
            'content' => [
                'text' => static::$contentStr
            ],
            'msg_type' => self::TYPE_TEXT
        ];
    }

    /**
     * @desc 富文本消息构建
     * @user lei
     * @date 2022/4/19
     */
    private function generateMedia() {
        $this->generateMediaArr();
        $this->body = [
            'content' => static::$mediaArr,
            'msg_type' => self::TYPE_MEDIA
        ];
    }

    /**
     * @desc 构建消息content字符串内容
     * @user lei
     * @date 2022/4/18
     */
    abstract protected function generateContentStr();

    /**
     * @desc 构建富文本消息content数组内容
     * @user lei
     * @date 2022/4/19
     * @return mixed
     */
    abstract protected function generateMediaContent();

}