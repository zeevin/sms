<?php
/**
 * Author: ConnorCho(caokang@outlook.com)
 * Date: 2017/8/23
 * Time: 上午10:42
 * Source: SmsFileView.php
 * Project: PhpStorm
 */

namespace Initlu\Sms;


use Initlu\Sms\Gateways\SmsViewInterface;

class SmsFileView implements SmsViewInterface
{
    public function getView($view, $params)
    {
        return view($view,$params);
    }
}