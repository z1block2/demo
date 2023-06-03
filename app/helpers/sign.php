<?php

use Illuminate\Support\Facades\Log;

function sign(array $req, string $secret = '') {
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
    // toSign| api_key=e6c46a81ca8b64e194cb14da75e9f5be&username=testuser&secret=2957cb9d2cd898642454627ba1a7a97c
    // api_key=e6c46a81ca8b64e194cb14da75e9f5be&username=testuser&secret=2957cb9d2cd898642454627ba1a7a97c
    Log::info("toSign:".$toSign);
    return sha1($toSign);
}
