<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace crmeb\services;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class AliSMS {
	 //验证码
    const VERIFICATION_CODE         = 518076;
    //支付成功
    const PAY_SUCCESS_CODE          = 'SMS_139850095';
    //发货提醒
    const DELIVER_GOODS_CODE        = 520269;
    //确认收货提醒
    const TAKE_DELIVERY_CODE        = 520271;
    //管理员下单提醒
    const ADMIN_PLACE_ORDER_CODE    = 520272;
    //管理员退货提醒
    const ADMIN_RETURN_GOODS_CODE   = 520274;
    //管理员支付成功提醒
    const ADMIN_PAY_SUCCESS_CODE    = 520273;
    //管理员确认收货
    const ADMIN_TAKE_DELIVERY_CODE  = 520422;
    /**
     * 发送短信验证码
     */
    public static function sendCode($phone, $code,$templateCode) {
        $config = array(
            'accessKeyId'=>'LTAI6rJQ6F7gBeA6',
            'accessSecret'=>"mGMZug9PM7FJROdGoPAyzbBjShDRRL",
            'SignName'=>'HDF',
            'TemplateCode'=>$templateCode,
            'regionId'=>'cn-hangzhou',
        );
        $param = [
            'code' => rand(100000,999999)
        ];
		
        AlibabaCloud::accessKeyClient($config['accessKeyId'], $config['accessSecret'])
                ->regionId($config['regionId'])
                ->asGlobalClient();

        try {
            $result = AlibabaCloud::rpcRequest()
                    ->product('Dysmsapi')
                    ->version('2017-05-25')
                    ->action('SendSms')
                    ->method('POST')
                    ->options([
                        'query' => [
                            'PhoneNumbers' => $phone,
                            'SignName' => $config['SignName'],
                            'TemplateCode' => $config['TemplateCode'],
                            'TemplateParam' => json_encode($param)
                        ],
                    ])
                    ->request();
            return true;
        } catch (ClientException $e) {
           // echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
          //  echo $e->getErrorMessage() . PHP_EOL;
        }
    }

}
