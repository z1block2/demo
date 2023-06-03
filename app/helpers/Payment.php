<?php

namespace App\helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Payment
{
    /**
     * 绑定用户，获取存入地址， 以后存入这个地址的交易都会回调
     *
     * 需要在商户后台设置好存款回调地址
     *
     * @param $username
     * @return void
     */
    function bindAddress($username) {

    }


    static function deposit(string $username, float $cnyAmount,float $usdtAmount) {
        $params = [
            'username'  => 'test_deposit', // 用户标识，相同的用户返回相同的地址， 会在回调时候原样返回
            'order_id'  => 'DP'.date('YMDids'),
            'type'  => 'static' ,// static: 地址和 username 绑定, temp 地址和username绑定
            'expire'    => 1800, // 可选，默认半小时
            'cny_amount'    => sprintf('0.2f', $cnyAmount), // 一定要字符串
            'callback'  => config('app.url').'/payment/deposit_callback', // 可选，如果没有则使用商户后台配置里的
        ];

        $data = self::request($params, config('payment.'))
    }

    static function request($params ,$url) {
        $params['api_key'] = config('payment.api_key');// api_key 从商户后台获取
        $params['timestamp'] = time();
        $params['sign'] = self::sign($params);
        $resp = Http::post($url, $params);

        return $resp->json();
    }

    static function sign(array $req, string $secret = '') {
        ksort($req);

        if ('' == $secret) {
            $secret = config('payment.api_secret');
        }
        Log::info("secret |{$secret}|");
        $fields = [];
        foreach ($req as $field => $val) {
            $fields[] = "{$field}={$val}";
        }

        $toSign = implode('&', $fields).'&secret='.$secret;

        Log::info("toSign:".$toSign);
        return sha1($toSign);
    }
}
