# 腾讯 移动推送 TPNS

[官方接口文档](https://cloud.tencent.com/document/product/548/39059)


# 配置参数说明
> 需要配置的参数

 | 参数名字     | 类型   | 说明                                                                   |
 | ------------ | ------ | ---------------------------------------------------------------------- |
 | appid        | string | 申请的appid                                                            |
 | secretKey    | string | 申请的appsecretkey                                                     |
 | message_type | string | 消息类型 【notify】 通知【message】 透传消息/静默消息 【没有就是默认】 |

 ### Config Demo
>本地config的配置
 ```
    $config = [

        'appid' => '',

        'secretKey' => '',

        'message_type' => ''
    ];

 ```

 # 发送消息（全部）
> 给所有安装app的用户发送消息。
 ```
    $content=[
        'title'=>'标题',
        'content'=>'内容'
        ];
    $message_type='notify';

    $app_push = new AppPush($config);

    $app_push->send_all($content,$message_type);

 ```
 # 单发信息
 > 一对一给用户发送消息
 ```
    $content=[
        'title'=>'标题',
        'content'=>'内容'
        ];

    $message_type='notify';

    $app_push = new AppPush($config);

    $app_push->send_all($content,$message_type);

 ```