<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestSign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:api {?act}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试代码';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $act = $this->argument('act');

        switch ($act) {
            case 'bindAddress':
                $this->bindAddress();
                break;
            case 'deposit': // 存币

        }
    }

    function balance() {

    }

    function deposit() {

    }

    function bindAddress() {
        // 以绑定地址为例
        $req = [
            'username'  => 'testuser1', // 贵司的用户，一个用户绑定一个固定存币地址，存款回调时会带上该参数
            'api_key'   => config('payment.api_key'),// api_key 从商户后台获取
            'timestamp' => time()// - 120, 时间戳差别必须小于60秒
        ];
        $req['sign'] = sign($req);

        Log::info("\$req with sign", $req);
        $resp = Http::post(config('payment.api_create_address'), $req);

        $this->info('resp.body'.$resp->body());

        return Command::SUCCESS;
    }
}
