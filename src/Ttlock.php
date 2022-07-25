<?php
/**
 * Created by PhpStorm.
 * User: 何明兴
 * Date: 2022/7/20
 * Time: 14:50
 */

namespace test;

use GuzzleHttp\Exception\ClientException;


class Ttlock
{
    protected $client = null;
    protected $client_id = 'accfeeacda694160890c0170f6a1310f';
    protected $client_secret = 'ca257d5ec7278bee40cc2bd1513103f3';

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    //获取access_token
    public function getAccessToken()
    {
        //你还可以提供其他可选的构造函数参数
        $url = 'https://api.ttlock.com/oauth2/token';

        $response = $this->client->post($url, [
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'client_id' => $this->client_id,//参数
                'client_secret' => $this->client_secret,//参数
                'username' => '+8613885168270',//参数
                'password' => md5('yfb2022tts'),//参数
            ]
        ]);
        //返回值
        $result = json_decode($response->getBody()->getContents(), true);

        $myfile = fopen("access_token", "w") or die("Unable to open file!");
        $txt = json_encode([
            'access_token' => $result['access_token'],
            'expires_in' => $result['expires_in'] + time()
        ]);
        fwrite($myfile, $txt);
        fclose($myfile);
        return $result['access_token'];
    }

    //获取门下锁列表
    public function getLockerList()
    {
        $url = 'https://api.ttlock.com/v3/lock/list';
        try {
            $access_token_file = json_decode(file_get_contents('./access_token'), true);
            if ($access_token_file['expires_in'] <= time()) {
                $access_token = $this->getAccessToken();//刷新token
            }else{
                $access_token = $access_token_file['access_token'];
            }
            $response = $this->client->post($url, [
                'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
                'form_params' => [
                    'clientId' => $this->client_id,//参数
                    'accessToken' => $access_token,//参数
                    'pageNo' => 1,//参数
                    'pageSize' => 10,//参数
                    'date' => time() * 1000,//参数
                ]
            ]);
            //返回值
            $result = $response->getBody()->getContents();
            return $result;
        } catch (ClientException $exception) {
            return 'error';
        }
    }

    //随机密码：解锁
    public function getRandPwdUnlock($lockId)
    {
        $url = 'https://api.ttlock.com/v3/keyboardPwd/get';
        $access_token_file = json_decode(file_get_contents('./access_token'), true);
        if ($access_token_file['expires_in'] <= time()) {
            $access_token = $this->getAccessToken();//刷新token
        }else{
            $access_token = $access_token_file['access_token'];
        }
        $response = $this->client->post($url, [
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'clientId' => $this->client_id,//参数
                'accessToken' => $access_token,//参数
                'lockId' => $lockId,//参数
                'keyboardPwdType' => 3,//参数
                'startDate' => time() * 1000,//参数
                'endDate' => (time() + 24 * 60 * 60) * 1000,
                'date' => time() * 1000,//参数
            ]
        ]);
        //返回值
        $result = $response->getBody()->getContents();
        return $result;
    }

    //添加自定义密码，开锁
    public function selfMakePwd()
    {
        $url = "https://api.ttlock.com/v3/keyboardPwd/add";
        $access_token_file = json_decode(file_get_contents('./access_token'), true);
        if ($access_token_file['expires_in'] <= time()) {
            $access_token = $this->getAccessToken();//刷新token
        }else{
            $access_token = $access_token_file['access_token'];
        }
        $response = $this->client->post($url, [
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'clientId' => $this->client_id,//参数
                'accessToken' => $access_token,//参数
                'lockId' => 6070727,//参数
                'keyboardPwd' => 3,//参数
                'startDate' => time() * 1000,//参数
                'endDate' => (time() + 24 * 60 * 60) * 1000,
                'date' => time() * 1000,//参数
            ]
        ]);
        //返回值
        $result = $response->getBody()->getContents();
        
       return $result;
    }

}