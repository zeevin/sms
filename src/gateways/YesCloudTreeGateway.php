<?php
/**
 * Author: ConnorCho(caokang@outlook.com)
 * Date: 2017/8/21
 * Time: 下午6:44
 * Source: YesCloudTreeGateway.php
 * Project: PhpStorm
 */

namespace Initlu\Sms\Gateways;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class YesCloudTreeGateway implements SmsGatewayInterface
{
    public static $resultCode = [
        0=>'发送成功',
        1=>'目的号码太长（群发2000个）',
        2=>'超过今天的最大发送量',
        3=>'所剩于的发送总量低于您现在的发送量',
        4=>'信息提交失败',
        5=>'出现未知情况，请联系管理员',
        7=>'Action参数错误',
        8=>'系统故障',
        9=>'用户名密码不正确',
        30=>'存在屏蔽词',
        99=>'超出许可连接数',
        100=>'非鉴权请求IP，提供账号绑定后再使用',
        101=>'请求IP与请求账号不匹配，请核对请求账号',
        102=>'请求账号与绑定IP不匹配，请核对请求IP'
    ];
    protected $gwvars = array();
    protected $url = '';
    protected $request = '';
    public $status = false;
    public $response = '';
    public $countryCode='';

    function __construct()
    {
        $this->url = Config::get('sms.yct.url');
        $this->gwvars = Config::get('sms.yct.params.others');
        $this->countryCode = Config::get('sms.countryCode');
    }
    public function getUrl()
    {
//        $this->request = http_build_query($this->gwvars);
        return $this->url;
    }
    public function sendSms($mobile,$message,...$params)
    {
        $mobile = Config::get('sms.yct.add_code')?$this->addCountryCode($mobile):$mobile;
        if(is_array($mobile)){
            $mobile = $this->composeBulkMobile($mobile);
        }
        $this->gwvars[Config::get('sms.yct.params.send_to_name')] = $mobile;
        $this->gwvars[Config::get('sms.yct.params.msg_name')] = trim($message,"\n");
        $client = new Client();
        Log::info('SMS: '.http_build_query($this->gwvars));
        $this->response = $client->request('POST',$this->getUrl(),['form_params'=>$this->gwvars])->getBody()->getContents();
        Log::info('SMS:yesCloud Response: '.$this->response.'['.$mobile.']');
        $this->setStatus();
        return $this;
    }
    /**
     * Create Send to Mobile for Bulk Messaging
     * @param $mobile
     * @return string
     */
    private function composeBulkMobile($mobile)
    {
        return implode(';',$mobile);
    }
    /**
     * Prepending Country Code to Mobile Numbers
     * @param $mobile
     * @return array|string
     */
    private function addCountryCode($mobile)
    {
        if(is_array($mobile)){
            array_walk($mobile, function(&$value, $key) { $value = $this->countryCode.$value; });
            return $mobile;
        }
        return $this->countryCode.$mobile;
    }
    /**
     * Check Response
     * @return array
     */
    public function response(){
        return $this->response;
    }

    public function setStatus()
    {
        if($this->response==0)
            $this->status = true;
        else
            $this->response = isset(self::$resultCode[$this->response])?self::$resultCode[$this->response]:'短信发送失败，请稍后再试';
    }

}