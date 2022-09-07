# 腾讯 移动推送 TPNS（点个收藏是我前进的动力）

[官方接口文档](https://cloud.tencent.com/document/product/548/39059)
# 安装
```
composer require code-lives/app-push-tpns 1.1.0
```

# 配置参数说明
> 需要配置的参数

 | 参数名字     | 类型   | 说明                                                                                |
 | ------------ | ------ | ----------------------------------------------------------------------------------- |
 | appid        | string | 申请的appid                                                                         |
 | secretKey    | string | 申请的appsecretkey                                                                  |
 | environment  | string | 用户指定推送环境，仅限 iOS 平台推送使用 默认 product；推送生产环境；dev推送开发环境 |

 ### Config Demo
>本地config的配置
 ```
    $config = [
        'appid' => '',
        'secretKey' => '',
        'environment'=>''
    ];
 ```

 # 全量推送
> 给所有安装app的用户发送消息。
 ```
    $content=[
        'title'=>'标题',
        'content'=>'内容'
        ];
    $app_push = new AppPush($config);
    $app_push->send_all($content,'notify');
 ```
 # 单账号推送或多账号推送
 > 一对一给用户发送消息 或 多个账号发送消息

 | 参数名字     | 类型   | 说明                                                         |
 | ------------ | ------ | ------------------------------------------------------------ |
 | content      | string | 内容                                                         |
 | account      | array  | 根据数量判断 单账号或多账号推送                              |
 | message_type | string | 默认：notify(可不传);notify：通知;message：透传消息/静默消息 |
 ```
    $account=['one'];//单账号推送
    $account=['one','two'];//多个账号推送
    $app_push->send_account($content, $account,$message_type);
 ```
 # 单设备推送或多设备推送
 > 单或多设备推送 传递数量判断

 | 参数名字     | 类型   | 说明                                                         |
 | ------------ | ------ | ------------------------------------------------------------ |
 | content      | string | 内容                                                         |
 | token        | array  | 根据数量判断 单设备或多设备                                  |
 | message_type | string | 默认：notify(可不传);notify：通知;message：透传消息/静默消息 |
 ```
    $token=['one'];//单设备推送
    $token=['one','two'];//多个设备推送
    $app_push->send_token($content, $token,$message_type);
 ```
  # 标签推送
 > tag 字段根据开发文档 tag_items 字段 自定义

 | 参数名字     | 类型   | 说明                                                          |
 | ------------ | ------ | ------------------------------------------------------------- |
 | content      | string | 内容                                                          |
 | tag          | array  | 根据开发文档 tag_items 字段 封装                              |
 | message_type | string | 默认：notify(可不传); notify：通知;message：透传消息/静默消息 |
 ```
    $app_push->send_tag($content, $tag,$message_type);
 ```

  # 标签绑定与解绑

 | 参数名字      | 类型  | 说明                                                                            |
 | ------------- | ----- | ------------------------------------------------------------------------------- |
 | operator_type | int   | 类型看官方文档1-10                                                              |
 | array         | array | operator_type(1-8) ['token_list'=>] operator_type(9-10)    ['tag_token_list'=>] |
 | tag_array     | array | operator_type = 1,2,3,4,6,7,8时  ['tag_list'=>]   operator_type(9-10)可不传递    |

 ```
   //1-8
   $operator_type = 1;
   $array = ['token_list' => []];
   $tag_array = ['tag_list' => []];
   $app_push->set_tag($operator_type, $array = [], $tag_array = []);

   //9-10
   $operator_type = 9;
   $array = ['tag_token_list' => []];
   $app_push->set_tag($operator_type, $array = []);
 ```
   # 删除标签下所有设备

 | 参数名字     | 类型   | 说明                                                          |
 | ------------ | ------ | ------------------------------------------------------------- |
 | tag_list      | array | 待删除标签列表："tag_list": ["test_tag_3_Ik0N0", "test_tag_2_Ik0N0"]                                                          |
 
 ```
    $app_push->delete_tag(['tag_list'=>[]]);
 ```

  # 账号绑定与解绑
 > array 根据开发文档->账号相关接口->账号绑定与解绑 

 | 参数名字      | 类型  | 说明                                                                            |
 | ------------- | ----- | ------------------------------------------------------------------------------- |
 | operator_type | int   | 类型看官方文档                                                              |
 | array         | array | 查看文档|

 ```
   $operator_type = 1;
   $array = ['account_list' => []];
   $array = ['token_list' => []];
   $array = ['token_accounts' => []];
   $app_push->account_save($operator_type, $array);
 ```

  # 账号查询
 > array 根据开发文档->账号相关接口->账号设备绑定查询

 | 参数名字      | 类型  | 说明                                                                            |
 | ------------- | ----- | ------------------------------------------------------------------------------- |
 | operator_type | int   | 类型看官方文档                                                              |
 | array         | array | 查看文档 |

 ```
   $operator_type = 1;
   $array = ['account_list' => []];
   $array = ['token_list' => []];
   $app_push->account_query($operator_type, $array);
 ```
 
