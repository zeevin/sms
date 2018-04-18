<?php
/**
 * Author: ConnorCho(caokang@outlook.com)
 * Date: 2017/8/23
 * Time: 上午10:36
 * Source: SmsServiceProvider.php
 * Project: PhpStorm
 */

namespace Initlu\Sms;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function register()
    {
        $gateway = Config::get('sms.gateway');
        $view = Config::get('sms.view');
        $this->app->bind('sms','Initlu\Sms\Sms');
        $this->app->bind('Initlu\Sms\Gateways\SmsGatewayInterface','Initlu\Sms\Gateways\\'.$gateway.'Gateway');
        $this->app->bind('Initlu\Sms\Gateways\SmsViewInterface','Initlu\Sms\Sms'.$view.'View');

    }

    public function boot(){
        $this->publishes([
            __DIR__.'/views/sms' => base_path('resources/views/sms'),
            __DIR__.'/config/sms.php' => base_path('config/sms.php'),
        ]);
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
        ];
    }

}