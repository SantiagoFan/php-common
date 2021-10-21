<?php


namespace JoinPhpCommon\utils;

/**
 * 网络请求工具类
 * Class HttpHelper
 * @package JoinPhpCommon\utils
 */
class HttpHelper
{
    private $base_uri = "";
    private $time_out = 60;//秒
    private $header = [];
    public function __construct($config = [])
    {
        if(isset($config['base_uri'])){
            $this->base_uri =$config['base_uri'];
        }
    }
    public function get($url,$data){
        $res = $this->curl($url,$data,'get');
        return $res;
    }

    /**
     * post json 格式数据
     * @param $url
     * @param $data
     * @return mixed
     */
    public function post_json($url,$data){
        $res = $this->curl($url,$data,'post');
        return json_decode($res,true);
    }
    public function download($url,$path){}

    /**
     * @param $url  请求url
     * @param null $data
     * @param string $type 请求类型 post/get
     * @param string $content_type 内容类型 json/
     */
    public function curl($url,$data = null,$type='get',$content_type='json'){
        // 1.初始化curl
        $curl =  curl_init();
        // 2.请求类型
        if($type == 'post'){
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST,true);
            // 数据不为空
            if(!empty($data) && is_array($data)){
                $data = json_encode($data);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            if($content_type =='json'){
                array_push($this->header,'Content-Type: application/json; charset=utf-8');
                array_push($this->header,'Content-Length:' . strlen($data));
            }
        }
        else{
            $get_url = $url.'?'.http_bulid_query($data);
            curl_setopt($curl, CURLOPT_URL, $get_url);
        }
        curl_setopt($curl, CURLOPT_HEADER, 1);              //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  //不验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  //不验证
        curl_setopt($curl, CURLOPT_HTTPHEADER,$this->header); //设置请求头
        curl_setopt($curl, CURLOPT_TIMEOUT, (int)$this->time_out);

        // 3.采集
        $res = curl_exec($curl);

        // 4.响应处理
        $errorno = curl_errno($curl);
        if ($errorno) {
            return array('errorno' => false, 'message' => $errorno);
        }
        // 4.关闭
        curl_close($curl);
        return  $res;
    }

}