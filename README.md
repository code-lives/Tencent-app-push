# 腾讯 移动推送 TPNS

[官方接口文档](https://cloud.tencent.com/document/product/548/39059)


# 参数说明
 | 参数名字     | 类型   | 说明                                                                    |
 | ------------ | ------ | ----------------------------------------------------------------------- |
 | appid        | string | 申请的appid                                                             |
 | secretKey    | string | ｜申请的appsecretkey                                                    |
 | message_type | string | 消息类型 【notify】 通知【message】 透传消息/静默消息 【没有就是默认]】 |




 # 发送消息（全部app）
 ```
    $content=[
        'title'=>'标题',
        'content'=>'内容'
        ];
    $message_type='notify';

    $app_push = new AppPush($config);

    $app_push->send_all($content,message_type);

 ```
 # 发送一对一消息secretKey
 ```
    $content=[
        'title'=>'标题',
        'content'=>'内容'
        ];

    $message_type='notify';

    $app_push = new AppPush($config);

    $app_push->send_all($content,message_type);

 ```