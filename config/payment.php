<?php

return [
    'api_key'   => env('PAYMENT_API_KEY', 'e6c46a81ca8b64e194cb14da75e9f5be'),
    'api_secret'=> env('PAYMENT_API_SECRET', '2957cb9d2cd898642454627ba1a7a97c'), // 正确
//    'api_secret'=> env('PAYMENT_API_SECRET', '2957cb9d2cd898642454627ba1a7a97d'), // 错误
    'api_deposit'   => env('PAYMENT_DEPOSIT' ,'http://localhost:8881/api/v1/deposit/create'), // 存币, 按法币(cny)数量 或者按usdt数量
    'api_create_address'   => env('PAYMENT_CREATE_ADDRESS' ,'http://localhost:8881/api/v1/address/create'), // ；获取地址，以后存入此地址都会回调
    'api_address_list'  => env('PAYMENT_ADDRESS_LIST', 'http://localhost:8881/api/v1/address/list'), // 绑定地址列表
    'api_withdraw'   => env('PAYMENT_WITHDRAW' ,'http://localhost:8881/api/v1/withdraw/create'), //提币
    'api_order_deposit' => env('PAYMENT_ORDER_DEPOSIT', 'http://localhost:8881/api/v1/depost/list'),
    'api_order_withdraw' => env('PAYMENT_ORDER_DEPOSIT', 'http://localhost:8881/api/v1/withdraw/list'),
    'tokens' => [
        'trx'   => 'trx',
        'TNJX6zCsN9Bxt7C3jWdTDfuejU5dEih3G4'    => 'UNT',
    ]
];
