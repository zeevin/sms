<?php
/**
 * Author: ConnorCho(caokang@outlook.com)
 * Date: 2017/8/23
 * Time: 上午8:23
 * Source: SmsViewInterface.php
 * Project: PhpStorm
 */

namespace Initlu\Sms\Gateways;


interface SmsViewInterface
{
    public function getView($view,$params);
}