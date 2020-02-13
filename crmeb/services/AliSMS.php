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
    const VERIFICATION_CODE         = 'SMS_139850095';
    //支付成功
    const PAY_SUCCESS_CODE          = 'SMS_176938021';
    //发货提醒
    const DELIVER_GOODS_CODE        = 'SMS_176927973';
    //确认收货提醒
    const TAKE_DELIVERY_CODE        = 'SMS_176943057';
    //管理员下单提醒
    const ADMIN_PLACE_ORDER_CODE    = 'SMS_176938065';
    //管理员退货提醒
    const ADMIN_RETURN_GOODS_CODE   = 'SMS_176943097';
    //管理员支付成功提醒
    const ADMIN_PAY_SUCCESS_CODE    = 'SMS_176943092';
    //管理员确认收货
    const ADMIN_TAKE_DELIVERY_CODE  = 'SMS_176938071';
    /**
     * 发送短信验证码
     */
    public static function sendCode($phone, $param='',$templateCode) {
		
        $config = array(
            'accessKeyId'=>'LTAI6rJQ6F7gBeA6',
            'accessSecret'=>"mGMZug9PM7FJROdGoPAyzbBjShDRRL",
            'SignName'=>'HDF',
            'TemplateCode'=>$templateCode,
            'regionId'=>'cn-hangzhou',
        );
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
            return ['status'=>200,'msg'=>'发送成功'];
        } catch (ClientException $e) {
            return ['status'=>400,'msg'=>'发送失败'];
        } catch (ServerException $e) {
            return ['status'=>400,'msg'=>'发送失败'];
        }
   }

}
