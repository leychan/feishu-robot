# feishu-robot
push message to feishu group by robot

# how to use 
```bash
$ composer require leychan/feishu-robot
```

```php
<?php

require __DIR__ . '/vendor/autoload.php';

try {
    throw new \Exception('hhhh');
} catch (\Exception $e) {
    $webhook = 'your robot webhook';
    $msgType = 'post'; //or text
    (new \Leychan\FeiShuRobot\ExceptionRobot($webhook, $msgType, $e, ''))->notify();
}

try {
    throw new \Exception('hhhh');
} catch (\Exception $e) {
    $webhook = 'your robot webhook';
    $msgType = 'text'; //or text
    (new \Leychan\FeiShuRobot\ExceptionRobot($webhook, $msgType, $e, ''))->notify();
}

try {
    throw new \Exception('hhhh');
} catch (\Exception $e) {
    $webhook = 'your robot webhook';
    $msgType = 'post';
    $params = ['title' => 'test', 'content' => [
        'line1' => 'text',
        'line2' => 'text',
        'line3' => 'text',
    ]];
    (new \Leychan\FeiShuRobot\CommonRobot($webhook, $msgType, $params))->notify();
}

try {
    throw new \Exception('hhhh');
} catch (\Exception $e) {
    $webhook = 'your robot webhook';
    $msgType = 'text';
    (new \Leychan\FeiShuRobot\ExceptionRobot($webhook, $msgType, $e))->notify();
    //$msgType = 'post';
    //title is only effect when msgType='post'
    //(new \Leychan\FeiShuRobot\ExceptionRobot($webhook, $msgType, $e, 'title'))->notify();
}
```
