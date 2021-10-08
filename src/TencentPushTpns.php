<?php

namespace tencent\apppush;

class TencentPushTpns
{
    private $parm_url = [
        'APP_PUSH' => "v3/push/app", //推送通知与应用内消息接口
        'ACCOUNT_TOKEN_UPLOAD' => 'v3/push/package/upload', //1.号码包上传接口 2.token包上传接口
        'ACCOUNT_BIND' => 'v3/device/account/batchoperate', //	账号绑定与解绑接口
    ];

    //推送目标
    const AUDIENCE_ALL               = "all"; //全量推送
    const AUDIENCE_TAG               = "tag"; //标签推送
    const AUDIENCE_TOKEN             = "token"; //单设备推送
    const AUDIENCE_TOKEN_LIST        = "token_list"; //设备列表推送
    const AUDIENCE_ACCOUNT           = "account"; //单账号推送
    const AUDIENCE_ACCOUNT_LIST      = "account_list"; //账号列表推送
    const AUDIENCE_ACCOUNT_PACKAGE   = "package_account_push"; //号码包推送
    const AUDIENCE_TOKEN_PACKAGE     = "package_token_push"; //token 文件包推送

    public $tag_type = "all";
    public $url = 'https://api.tpns.tencent.com/';
    public $sign;
    public $appid;
    public $secretKey;

    public function __construct($config)
    {
        $this->appid = $config['appid'];
        $this->secretKey = $config['secretKey'];
    }
    /**
     * 全员发送消息【安装App的】
     *
     * @param array $content
     *
     * @param string $message_type
     *
     */
    public  function send_all($content, $message_type = 'notify')
    {
        $arr = [
            'audience_type' => 'all',
            'message' => $content,
            'message_type' => $message_type,
            'environment' => 'product',
        ];
        return $this->Push($arr, $this->get_url('APP_PUSH'));
    }
    /**
     * 一对一发送消息
     *
     * @param array $content 内容
     * @param string $account 用户账号
     * @param string $message_type 类型
     *
     */
    public function send_account($content, $account, $message_type = 'notify')
    {

        $arr = [
            'audience_type' => self::AUDIENCE_ACCOUNT,
            'message' => $content,
            'message_type' => $message_type,
            'environment' => 'product',
            'account_list' => [
                $account
            ],
        ];
        return $this->Push($arr, $this->get_url('APP_PUSH'));
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

        $arr = json_decode($ret, 1);
        return $arr;
        if ($arr['ret_code'] == 0) {
            return true;
        } else {
            return false;
        }
    }
}
