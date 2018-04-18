<?php
/**
 * Author: ConnorCho(caokang@outlook.com)
 * Date: 2017/8/21
 * Time: 下午6:45
 * Source: Sms.php
 * Project: PhpStorm
 */

namespace Initlu\Sms;


use Initlu\Sms\Gateways\SmsGatewayInterface;
use Initlu\Sms\Gateways\SmsViewInterface;
use Initlu\Sms\Gateways\YesCloudTreeGateway;

class Sms
{
    protected $gateway;
    protected $view;

    public function __construct(SmsGatewayInterface $gateway,SmsViewInterface $view)
    {
        $this->gateway = $gateway;
        $this->view = $view;
    }

    public function send($mobile,$view,$params){
        $message = $this->view->getView($view,$params)->render();
        return $this->gateway->sendSms($mobile,$message);
    }

    public function send_raw($mobile,$message){
        return $this->gateway->sendSms($mobile,$message);
    }

    public function gateway($name)
    {
        switch ($name)
        {
        case 'YesCloudTree':
            $this->gateway = new YesCloudTreeGateway();
            break;
        default:
            break;
        }
        return $this;
    }

}