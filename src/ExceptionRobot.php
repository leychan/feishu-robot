<?php

namespace Leychan\FeiShuRobot;

use Exception;

/**
 * @desc 错误报警机器人,专门用来推送报错信息
 * @date 2022/4/18
 * @user lei
 */
class ExceptionRobot extends Robot {

    const EXCEPTION_DEFAULT_TITLE = '错误报警!';

    /**
     * @var Exception
     */
    public $e;

    /**
     * @var array 调用栈信息
     */
    protected $trace;

    /**
     * @param $webhook
     * @param $msgType
     * @param Exception $e
     * @param string $title
     */
    public function __construct($webhook, $msgType, Exception $e, $title = '') {
        parent::__construct($webhook, $msgType);
        $this->trace = debug_backtrace();
        $this->e = $e;
        $this->title = $title ?: self::EXCEPTION_DEFAULT_TITLE;
    }

    /**
     * @desc 错误文本报警内容
     * @user lei
     * @date 2022/4/18
     */
    protected function generateContentStr() {
        static::$contentStr = '';
        static::$contentStr .= 'FILE:    ' . $this->e->getFile() . PHP_EOL;
        static::$contentStr .= 'LINE:    ' . $this->e->getLine() . PHP_EOL;
        static::$contentStr .= 'MESSAGE: ' . $this->e->getMessage() . PHP_EOL;
        if (!empty($this->trace[1]['class'])) {
            static::$contentStr .= 'CLASS:   ' . $this->trace[1]['class'] . PHP_EOL;
        }
        if (!empty($this->trace[1]['function'])) {
            static::$contentStr .= 'METHOD:  ' . $this->trace[1]['function'] . PHP_EOL;
        }
    }

    /**
     * @desc 构建富文本消息主体内容
     * @user lei
     * @date 2022/4/19
     */
    protected function generateMediaContent() {
        $this->content[0][] = [
            "tag" => "text",
            "text" => 'FILE:    ' . $this->e->getFile()
        ];
        $this->content[1][] = [
            "tag" => "text",
            "text" => 'LINE:    ' . $this->e->getLine()
        ];
        $this->content[2][] = [
            "tag" => "text",
            "text" => 'MESSAGE:    ' . $this->e->getMessage()
        ];

        if (!empty($this->trace[1]['class'])) {
            $this->content[3][] = [
                "tag" => "text",
                "text" => 'CLASS:    ' . $this->trace[1]['class']
            ];
        }
        if (!empty($this->trace[1]['function'])) {
            $this->content[4][] = [
                "tag" => "text",
                "text" => 'METHOD:    ' . $this->trace[1]['function']
            ];
        }
    }

}