<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2018/7/4
 * Time: 下午9:58
 * Source: App2eGateway.php
 * Project: sms
 */

namespace Initlu\Sms\Gateways;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class App2eGateway implements SmsGatewayInterface
{
    public static $resultCode
        = [
            '100' => '全部成功',
            '101' => '参数错误',
            '102' => '号码错误',
            '103' => '当日余量不足',
            '104' => '请求超时',
            '105' => '用户余量不足',
            '106' => '非法用户',
            '107' => '提交号码超限，一次最多1000个号码',
            '111' => '签名不合法',
            '120' => '内容过长，请不要超过500个字',
            '121' => '内容中含屏蔽词',
            '131' => '非法的IP地址',
        ];
    protected $gwvars = [];
    protected $url = '';
    protected $request = '';
    public $status = false;
    public $response = '';

    function __construct()
    {
        $this->url = Config::get('sms.app2e.url');
        $this->gwvars = Config::get('sms.app2e.params.others');
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function sendSms($mobile, $message, ...$params)
    {
        $mobile = Config::get('sms.app2e.add_code') ? $this->addCountryCode($mobile) : $mobile;
        if (is_array($mobile))
            $mobile = $this->composeBulkMobile($mobile);

        $this->gwvars[Config::get('sms.app2e.params.send_to_name')] = $mobile;
        $this->gwvars[Config::get('sms.app2e.params.msg_name')] = trim($message);
        $client = new Client();
        Log::info('SMS: app2e '.http_build_query($this->gwvars));
        $this->response = $client->request('POST', $this->getUrl(), ['form_params' => $this->gwvars])->getBody()->getContents();
        Log::info('SMS:app2e Response: '.$this->response);
        $this->setStatus();

        return $this;
    }

    /**
     * Create Send to Mobile for Bulk Messaging
     *
     * @param $mobile
     *
     * @return string
     */
    private function composeBulkMobile($mobile)
    {
        return implode(',', $mobile);
    }

    /**
     * Prepending Country Code to Mobile Numbers
     *
     * @param $mobile
     *
     * @return array|string
     */
    private function addCountryCode($mobile)
    {
        if (is_array($mobile))
        {
            array_walk(
                $mobile,
                function (&$value, $key) {
                    $value = $this->countryCode.$value;
                }
            );

            return $mobile;
        }

        return $this->countryCode.$mobile;
    }

    /**
     * Check Response
     *
     * @return array
     */
    public function response()
    {
        return $this->response;
    }

    public function setStatus()
    {
        $response = json_decode($this->response,true);
        if ($response['status'] == 100)
            $this->status = true;
        else
            $this->response = isset(self::$resultCode[$response]) ? self::$resultCode[$response] : '短信发送失败，请稍后再试';
    }

}