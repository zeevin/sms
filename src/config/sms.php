<?php
/**
 * Author: ConnorCho(caokang@outlook.com)
 * Date: 2017/8/23
 * Time: 上午10:33
 * Source: sms.php
 * Project: PhpStorm
 */

return [
    'countryCode' => '+86',
    'gateway' => 'YesCloudTree',
    'view'=>'File',

    //YesCloudTree.cn
    'yct'=>[
        'url'=>'http://www.yescloudtree.cn:28001',
        'params'=>[
            'send_to_name'=>'Mobile',
            'msg_name'=>'Message',
            'others'=>[
                'Action'=>'SendSms',
                'UserName'=>'username',
                'Password'=>md5('pwd'),
//                'Isp2p'=>0,
//                'ExtCode'=>0,
//                'MsgID'=>''
            ]
        ],
        'add_code'=>false
    ]
];