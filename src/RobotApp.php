<?php

namespace Leychan\FeiShuRobot;

class RobotApp {

    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_POST = 'post';

    const FEISHU_SEND_MESSAGE = 'https://open.feishu.cn/open-apis/im/v1/messages';
    const FEISHU_GROUP_SEND_MESSAGE = self::FEISHU_SEND_MESSAGE . '?receive_id_type=chat_id';
//    const MSG_TYPE_IMG = 'image';

    /**
     * @var array 入参
     */
    public $params;

    /**
     * @var null|string|array $content
     */
    public $content = null;

    public $title = '';

    public $msgType = self::MSG_TYPE_POST;

    public $req = [];

    public $receiveId = '';

    public $senderId = '';

    public $token = '';

    /**
     * @param $msgType
     * @param $params array[
     *  'title' => string
     *  'content' => string | array,
     *  'receiveId' => '',
     *  'senderId' => ''
     * ]
     */
    public function __construct($msgType, array $params) {
        $this->msgType = $msgType;
        $this->params = $params;

        $this->parse();
    }

    public function setToken($token) {
        $this->token = $token;
        return $this;
    }

    public function setReq($req = []) {
        $this->req = $req;
        return $this;
    }

    private function parse() {
        if (empty($this->params)) {
            return;
        }
        $this->title = $this->params['title'] ?? '';
        $this->receiveId = $this->params['receiveId'] ?? '';
        $this->senderId = $this->params['senderId'] ?? '';

        switch ($this->msgType) {
            case self::MSG_TYPE_TEXT :
                $this->msgText();
                break;
//            case self::MSG_TYPE_IMG:
//                $this->msgImg();
//                break;
            case self::MSG_TYPE_POST :
            default:
                $this->msgPost();
        }
    }

    private function msgText() {
        $at = '';
        if (!empty($this->senderId)) {
            $at .= '<at user_id="' . $this->senderId . '"></at>';
        }

        $this->content = $at . $this->params['content'];
        $this->req = [
            'receive_id' => $this->receiveId,
            'content' => json_encode([
                'text' => $this->content
            ]),
            'msg_type' => self::MSG_TYPE_TEXT
        ];
    }

    private function msgPost() {
        $this->generatePostContent();
        $this->generatePostAtSender();
        $this->req = [
            'receive_id' => $this->receiveId,
            'content' => json_encode([
                'zh_cn' => [
                    'title' => $this->title,
                    'content' => $this->content
                ]
            ]),
            'msg_type' => self::MSG_TYPE_POST
        ];
    }

    private function generatePostAtSender() {
        if (empty($this->senderId)) {
            return;
        }
        array_push($this->content, [
            [
                'tag' => 'at',
                'user_id' => $this->senderId,
                'user_name' => ''
            ]
        ]);
    }


    private function msgImg() {

    }


    /**
     * @desc 富文本消息content内容
     * @user lei
     * @date 2022/4/19
     */
    private function generatePostContent() {
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

    public function notify() {
        if (empty($this->token)) {
            throw new \Exception('token cannot be empty');
        }
        $header = [
            "Authorization: Bearer {$this->token}",
        ];
//        echo json_encode($this->req);exit;
        return \Leychan\FeiShuRobot\Request::post(self::FEISHU_GROUP_SEND_MESSAGE, $this->req, $header, 1);
    }

}