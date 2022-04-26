<?php

namespace Leychan\FeiShuRobot;

/**
 * @desc 通用机器人,用来推文本消息或者是富文本消息
 * @date 2022/4/19
 * @user lei
 */
class CommonRobot extends Robot {

    /**
     * @var array 入参
     */
    public $params;

    /**
     * @param $webhook
     * @param $msgType
     * @param $params array[
     *  'title' => string
     *  'content' => string | array
     * ]
     */
    public function __construct($webhook, $msgType, array $params)
    {
        parent::__construct($webhook, $msgType);
        $this->params = $params;
        $this->title = $this->params['title'] ?? '';
    }

    /**
     * @desc 构建消息体正文
     * @user lei
     * @date 2022/4/19
     * @throws \Exception
     */
    protected function generateContentStr()
    {
        if (empty($this->params['content'])) {
            throw new \Exception('content filed cannot be empty');
        }
        static::$contentStr = $this->params['content'];
    }

    /**
     * @desc 富文本消息content内容
     * @user lei
     * @date 2022/4/19
     */
    protected function generateMediaContent() {
        $i = 0;
        foreach ($this->params['content'] as $k => $v) {
            $tmp = [];
            $tmp['tag'] = 'text';
            if (!is_numeric($k)) {
                $tmp['text'] = strtoupper($k) . ': ' . $v;
            } else {
                $tmp['text'] = $v;
            }
            $this->content[$i][] = $tmp;
            ++$i;
        }
    }
}