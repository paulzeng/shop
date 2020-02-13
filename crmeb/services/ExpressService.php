<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2018/01/10
 */

namespace crmeb\services;


use crmeb\services\HttpService;
use crmeb\services\SystemConfigService;

class ExpressService
{
    protected static $api = [
		'query' => 'https://jisukdcx.market.alicloudapi.com/express/query'
        //'query' => 'https://wuliu.market.alicloudapi.com/kdi'
    ];

    public static function query($no, $type = 'auto')
    {
        $appCode = SystemConfigService::config('system_express_app_code');
        if (!$appCode) return false;
		$res = HttpService::getRequestAli(compact('no', 'type'), $appCode);
        //$res = HttpService::getRequest(self::$api['query'], compact('no', 'type'), ['Authorization:APPCODE ' . $appCode]);
        $result = json_decode($res, true) ?: false;
        return $result;
    }

}