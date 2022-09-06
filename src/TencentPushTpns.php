<?php

namespace tencent\apppush;

class TencentPushTpns
{
    private $parm_url = [
        'APP_PUSH' => "v3/push/app", //推送通知与应用内消息接口
        "TAG" => 'v3/device/tag', //标签绑定
        'DELETE_TAG' => 'v3/device/tag/delete_all_device', //删除标签下所有设备
        'ACCOUNT_TOKEN_UPLOAD' => 'v3/push/package/upload', //1.号码包上传接口 2.token包上传接口
        'ACCOUNT_BIND' => 'v3/device/account/batchoperate', //	账号绑定与解绑接口
        'ACCOUNT_QUERY' => 'v3/device/account/query', //账号设备绑定查询
    ];

    public $url = 'https://api.tpns.tencent.com/';
    public $appid;
    public $secretKey;

    public function __construct($config)
    {
        $this->appid = $config['appid'];
        $this->secretKey = $config['secretKey'];
        $this->environment = empty($config['environment']) ?  "product" : $config['environment'];
    }
    /**
     * 全员发送消息【安装App的】
     *
     * @param array $content
     *
     * @param string $message_type
     *
     */
    public function send_all($content, $message_type = 'notify')
    {
        $arr = [
            'audience_type' => "all",
            'message' => $content,
            'message_type' => $message_type,
            'environment' => $this->environment,
        ];

        return $this->Push($arr, $this->get_url('APP_PUSH'));
    }
    /**
     * 标签推送
     *
     * @param  string $content
     * @param  array $tag_array
     * @param  string $message_type
     */
    public function send_tag($content, $tag_array, $message_type = 'notify')
    {
        $arr = [
            'audience_type' => "tag",
            'tag_items' => $tag_array,
            'message' => $content,
            'message_type' => $message_type,
            'environment' => $this->environment,
        ];
        return $this->Push($arr, $this->get_url('APP_PUSH'));
    }
    /**
     * 设备推送
     * @param  string $content
     * @param  array $token_array
     * @param  string $message_type
     */
    public function send_token($content, $token_array, $message_type = 'notify')
    {
        $arr = [
            'audience_type' => (count($token_array) > 1) ? "token_list" : "token",
            'token_list' => $token_array,
            'message' => $content,
            'message_type' => $message_type,
            'environment' => $this->environment,
        ];
        return $this->Push($arr, $this->get_url('APP_PUSH'));
    }
    /**
     * 账号推送发送消息
     *
     * @param array $content 内容
     * @param string $account 用户账号
     * @param string $message_type 类型
     *
     */

    public function send_account($content, $account_array, $message_type = 'notify')
    {

        $arr = [
            'audience_type' => (count($account_array) > 1) ? "account_list" : "account",
            'message' => $content,
            'message_type' => $message_type,
            'environment' => $this->environment,
            'account_list' => $account_array,
        ];
        return $this->Push($arr, $this->get_url('APP_PUSH'));
    }
    /**
     * 标签绑定与解绑
     *
     * @param  int    $operator_type
     * @param  array  $array
     * @param  array  $tag_array
     */
    public function set_tag($operator_type, $array = [], $tag_array = [])
    {
        $arr = [
            'operator_type' => $operator_type
        ];
        return $this->Push(array_merge($arr,  $array, $tag_array), $this->get_url('TAG'));
    }

    public function delete_tag($array)
    {
        return $this->Push($array, $this->get_url('DELETE_TAG'));
    }

    /**
     * 账号 绑定或者删除
     *
     * @param  int    $operator_type
     * @param  array  $array
     *
     */
    public function account_save($operator_type, $array)
    {
        $arr = [
            'operator_type' => $operator_type,
        ];
        return $this->Push(array_merge($arr, $array), $this->get_url('ACCOUNT_BIND'));
    }
    /**
     * 账号查询
     *
     * @param  int    $operator_type
     * @param  array  $array
     *
     */
    public function account_query($operator_type, $array)
    {
        $arr = [
            'operator_type' => $operator_type,
        ];
        return $this->Push(array_merge($arr, $array), $this->get_url('ACCOUNT_QUERY'));
    }
    /**
     * 获取 sign
     *
     * @param array $data 内容
     * @param int $TimeStamp 时间戳
     * @return string 字符串
     *
     */
    private function set_sign($data, $TimeStamp)
    {
        $hashData = "{$TimeStamp}{$this->appid}{$data}";
        $hashRes = hash_hmac("sha256", $hashData, $this->secretKey, false);
        return  base64_encode($hashRes);
    }
    /**
     * 获取 发送的URL
     *
     * @param string $api 短路径
     * @return string 全路径
     *
     */
    private function get_url($url_name)
    {
        return $this->url . $this->parm_url[$url_name];
    }
    /**
     * 发送消息
     *
     * @param array $data 消息内容
     * @param string $url 请求路径
     * @return boolean  true false
     *
     */
    public function Push($data, $url)
    {
        $TimeStamp = time();
        $data = json_encode($data);
        $sign = $this->set_sign($data, $TimeStamp);
        $headers = array(
            "Content-type: application/json;charset='utf-8'",
            "AccessId:" . $this->appid,
            "TimeStamp:" . $TimeStamp,
            "Sign:" . $sign
        );
        $options = array(
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_HEADER          => 0,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSL_VERIFYHOST  => 0,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => $data,
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_TIMEOUT         => 10000
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $ret = curl_exec($ch);
        $error = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if ($error != "") {
            throw new \Exception($error);
        }
        $code = $info["http_code"];
        if ($code != 200) {
            throw new \Exception("status: " . $code . ", message: " . $ret);
        }
        return json_decode($ret, true);
    }
}
