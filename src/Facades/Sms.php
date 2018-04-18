<?php
/**
 * Author: ConnorCho(caokang@outlook.com)
 * Date: 2017/8/23
 * Time: 上午10:27
 * Source: Sms.php
 * Project: PhpStorm
 */
namespace Initlu\Sms\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }

}