<?php
/**
 * Author: ConnorCho(caokang@outlook.com)
 * Date: 2017/8/21
 * Time: 下午7:29
 * Source: SmsGatewayInterface.php
 * Project: PhpStorm
 */

namespace Initlu\Sms\Gateways;


interface SmsGatewayInterface
{
    public function getUrl();
    public function sendSms($mobile,$content,...$params);

}