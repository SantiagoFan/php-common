<?php
/**
 * Created by PhpStorm.
 * User: Administrator-范文刚
 * Date: 2019/3/30
 * Time: 15:11
 */

namespace JoinPhpCommon\utils;

use Exception;
use SimpleXMLElement;
use think\facade\Log;

/**
 * http 请求帮助工具类
 * Class HttpHelper
 * @package app\common
 * @method HttpHelper setBaseUrl(string $url)  设置基础地址
 * @method HttpHelper setTimeOut(int $timeout) 设置超时时间
 * @method HttpHelper setCookie(array $cookie) 设置cookie
 * @method HttpHelper setRequestContentType(string $type) 设置请求体类型
 * @method HttpHelper setResponseContentType(string $type) 设置响应体类型
 */

class HttpHelper
{
    const TYPE_JSON = 'application/json';
    const TYPE_FROM = 'application/x-www-form-urlencoded';
    const TYPE_XML = 'application/xml';

    private $base_url = "";// url 前缀
    private $is_log = true;// 是否记录日志
    private $time_out = 25;// 超时（秒）
    private $cookie = []; // 携带cookie
    private $request_content_type = self::TYPE_JSON;
    private $response_content_type = self::TYPE_JSON;

    public function __construct($config = [])
    {
        $this->base_url =$config['base_url'] ?? $this->base_url;
        $this->time_out =$config['time_out'] ?? $this->time_out;
    }
    private function toUnderScore($camelCaps,$separator='_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }
    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        // 属性设置
        if(strpos($name,'set')!== false){
            $prop = str_replace("set_","",$this->toUnderScore($name));
            return $this->__set($prop,...$arguments);
        }
    }

    /**
     * 设置属性
     * @param $p
     * @param $v
     */
    public function __set($p,$v)
    {
        if(property_exists($this, $p)){
            $this->$p = $v;
        }
        return $this;
    }

    /**
     * 记录日志
     */
    private function l($message){
        if($this->is_log){
            Log::info($message);
        }
    }

    /**
     * 处理请求体
     */
    public function buildRequest(array $data){
        $this->l("请求参数：");
        $this->l($data);
        if($this->request_content_type == self::TYPE_JSON){
            return json_encode($data);
        }
        elseif ($this->request_content_type == self::TYPE_FROM){
            return http_build_query($data);
        }
        // 其他格式处理
        return $data;
    }

    /**
     * 处理响应体
     * @param string $result
     * @return array|string
     */
    public function buildRespose(string $result){
        $this->l("响应结果：");
        $this->l("响应结果：" . $result);
        if($this->response_content_type==self::TYPE_JSON){
            $jsonArray = json_decode($result,true);
            return $jsonArray;
        }
        elseif ($this->response_content_type == self::TYPE_XML){
            $postObj = new SimpleXMLElement($result);
            $jsonStr = json_encode($postObj);
            $jsonArray = json_decode($jsonStr,true);
            return $jsonArray;
        }
        return $result;
    }

    /**
     * 发送CURL 请求
     * @param string $url
     * @param array $data
     * @param string $method
     */
    public function request(string $url, array $data=[], string $method='get')
    {
        $this->l("***********************  HTTP请求开始 *************************");
        $time_start = microtime(true);
        $this->l("请求URL：" . $url);

        $requset_data =  $this->buildRequest($data);
        // 请求头
        $header = array(
            'Accept: '.$this->response_content_type, // 希望接收到的响应体类型
            'Content-Type: '.$this->request_content_type, // 发送请求体类型
        );
        // 创建CURL 对象
        $curl = curl_init();
        //设置抓取的url
        $full_url = $this->base_url . $url;
        //------------------- 设置发送数据-------------------
        if ($method == 'post') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $requset_data);
        }
        elseif ($method == 'get'){
            $full_url .= (strpos($full_url,'?')!==false?'&':'?').$requset_data;
        }

        curl_setopt($curl, CURLOPT_URL, $full_url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        // 超时设置,以秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->time_out);
        // 设置请求头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // https 验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        // 设置cookie
        if($this->cookie){
            curl_setopt($curl, CURLOPT_COOKIEJAR, $this->cookie);
        }

        try {
            // 发送请求 获取内容
            $result = curl_exec($curl);
            // 获取状态码
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($http_status == 500) {
                throw new Exception($result);
            }
            // 显示错误信息
            if (curl_error($curl)) {
                throw new Exception(curl_error($curl));
            } else {
                return $this->buildRespose($result);
            }
        } catch (Exception $ex) {
            $this->l("异常信息：");
            $this->l($ex->getMessage());
            throw $ex;
        } finally {
            curl_close($curl);
            $time_end = microtime(true);
            $d = $time_end - $time_start;
            $this->l("请求耗时：" . $d);
            $this->l("***********************  HTTP请求完成 *************************");
        }
    }
    public function get($url,$data = []){
        return $this->request($url,$data);
    }
    public function post(string $url, array $data){
        return $this->request($url,$data,'post');
    }
}