<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Sop\GCM\AESGCM;
use function Psy\bin;

class TestEncrypt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:aes {act?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '测试 aes-256-gcm';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $act = $this->argument('act');
        switch ($act) {
            case 'methods':
                $this->supportedMethods();
                break;
            case 'encrypt_cbc':
                $this->encryptCBC();
                break;
            case 'encrypt_gcm':
                $this->encryptGCM();
                break;
            case 'decrypt_gcm':
                $this->decryptGCM();
                break;
            case 'aes256Decrypt':
                $this->aes256Decrypt();
                break;
        }
        return Command::SUCCESS;
    }

    protected $plaintext = 'hello world, john';

    function encryptCBC() {
        $method = 'aes-256-cbc';
        $key = sha1('test');
        $key = substr($key, 0, 32);
        $this->info('key:'.$key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
        $this->info('$iv:'.base64_encode($iv));
        $encrypted = openssl_encrypt($this->plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
        $base64 = base64_encode($encrypted);
        $this->info('base64:'.$base64);
    }

    function encryptGCM() {
        $this->info('encryptGCM');
        $keyStr = sha1('test');
        $keyStr = substr($keyStr, 0, 32);
        $this->info('key:'.$keyStr);
//        $lkey
        $key = hex2bin($keyStr);
        $method = 'aes-256-gcm';
        $data = 'hello .john, test long text,again';
        $this->info('vi_length:'. openssl_cipher_iv_length($method));
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));

        $tag = '';
        $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv, $tag, 16);
        $this->info('encrypted(hex):'.bin2hex($encrypted));
        $base64 = base64_encode($encrypted);
        $this->info('$tag:|'.base64_encode($tag));
        $this->info('encrypted(base64): '.$base64);
    }

    function decryptGCM() {

        // nonce: fb6eb49d9d5ebf67cf112a39
        //nonce(cipher): fb6eb49d9d5ebf67cf112a39
        //encrypted: fb6eb49d9d5ebf67cf112a39 a40f2077ca1b0b1e3580a3ec8deebce68a99bae0104514b7d20e49 ddb508eb50c643cb83716caedfd9aa92
        //cipherText: +260nZ1ev2fPESo5pA8gd8obCx41gKPsje685oqZuuAQRRS30g5J3bUI61DGQ8uDcWyu39mqkg==
        $cipherBase64 = '+260nZ1ev2fPESo5pA8gd8obCx41gKPsje685oqZuuAQRRS30g5J3bUI61DGQ8uDcWyu39mqkg==';
        $this->info('decryptGCM:2');
        $key = 'a94a8fe5ccb19ba61c4c0873d391e987';
        $method = 'aes-256-gcm';
        $ivLength = openssl_cipher_iv_length($method);
        $this->info('iv_length:'.$ivLength);
        $ciphertext = base64_decode($cipherBase64);
        $this->info('cipherText:'.bin2hex($ciphertext));
        $iv = substr($ciphertext, 0, $ivLength);
        $tag  = substr($ciphertext, -16);
        $ciphertext = substr($ciphertext, $ivLength, strlen($ciphertext) - $ivLength - 16 );
        $this->info('$iv:'.bin2hex($iv));
        $this->info('$tag:'.bin2hex($tag));
        $this->info('cipherText:'.bin2hex($ciphertext));
        try {
//            $plaintext = AESGCM::decrypt($ciphertext, $tag ,'', $key, $iv);
            $plaintext = openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv, $tag);
            $this->info('plaintext:' . $plaintext);
        } catch (\Exception $e) {
            $this->warn('exception'.$e->getMessage());
        }
    }


    function decryptGCM1() {
        // key:a94a8fe5ccb19ba61c4c0873d391e987
        //vi_length:12
        //encrypted(hex):1ded89f5427e3fd29d72c221a42f87ac60324eaea821c6dcb6c135968b1ef80638
        //$tag:|7jvuaY8f4uFk2oWI0Du3vQ==
        //encrypted(base64): He2J9UJ+P9KdcsIhpC+HrGAyTq6oIcbctsE1lose+AY4
        $this->info('decryptGCM');
        $method = 'aes-256-gcm';
//        $encrypted = 'yae+pP9UX1VFiE2teGxiID9zQjyTe01YSZfePt0A6s8LwAa57eP9eTVLFrw61v3NUbnyJ2aI3A==';
        $encrypted = 'He2J9UJ+P9KdcsIhpC+HrGAyTq6oIcbctsE1lose+AY4';
        $string = base64_decode($encrypted);
        $this->info('encrypted(hex):'.bin2hex($string));
        $key = 'a94a8fe5ccb19ba61c4c0873d391e987';
        $key = hex2bin($key);
        $viLength = openssl_cipher_iv_length($method);
        $vi = substr($string, 16, $viLength);
        $tag = substr($string,  -16);
        $string = substr($string, 16+$viLength, -16);
        $this->info('$vi:'.base64_decode($vi));
        try {
            $decrypted = openssl_decrypt($string, $method, $key, OPENSSL_RAW_DATA, $vi, $tag);
            $this->info('$decrypted:|'.$decrypted);
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    function aes256gcmEncrypt() {
//        $key =
    }

    function aes256Decrypt() {
        // nonce: 3169a9c23c0cfe61d1aef2b9
        //3169a9c23c0cfe61d1aef2b9
        //encrypted: 3169a9c23c0cfe61d1aef2b960fbbc7fc1a41be310dfadb38e59fa10f8841496abbd5b89008373b183be40d15751bb8cb44960dfab8d4d
        //encrypted : MWmpwjwM/mHRrvK5YPu8f8GkG+MQ362zjln6EPiEFJarvVuJAINzsYO+QNFXUbuMtElg36uNTQ==
        //  XV9hwnH2YFoQGQUGa3UuhSiPJ8liaI/7rH8rkIvuOw3CnTOp6/ElxYhAJPEPPzH0EVpFJNdUSw==
        //encrypted hex: 3169a9c23c0cfe61d1aef2b960fbbc7fc1a41be310dfadb38e59fa10f8841496abbd5b89008373b183be40d15751bb8cb44960dfab8d4d
        $cipherBase64 = 'XV9hwnH2YFoQGQUGa3UuhSiPJ8liaI/7rH8rkIvuOw3CnTOp6/ElxYhAJPEPPzH0EVpFJNdUSw==';
        $cipherBits = base64_decode($cipherBase64);
        $this->info('hex:'.bin2hex($cipherBits));
        $ivLength = openssl_cipher_iv_length('aes-256-gcm');
        $iv = substr($cipherBits, 0, $ivLength);
        $cipherText = substr($cipherBits, $ivLength);
        $this->info('$iv:'.bin2hex($iv));
        $auth_tag = '';
        $key = 'a94a8fe5ccb19ba61c4c0873d391e987';
        $this->info('decrypting:');
        $plaintext = AESGCM::decrypt($cipherText, $auth_tag, false, $key, $iv);
        $this->info('plaintext:'.$plaintext);
    }

    function supportedMethods() {
        $methods = openssl_get_cipher_methods();
        $this->info('$methods'.implode(',', $methods));

    }
}
