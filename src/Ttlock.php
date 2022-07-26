<?php
/**
 * Created by PhpStorm.
 * User: 何明兴
 * Date: 2022/7/20
 * Time: 14:50
 */

namespace Hemingxing;

use GuzzleHttp\Exception\ClientException;


class Ttlock
{
    protected $client = null;
    protected $client_id = 'accfeeacda694160890c0170f6a1310f';
    protected $client_secret = 'ca257d5ec7278bee40cc2bd1513103f3';
    protected $username = '';//开放平台账号（手机号）
    protected $password = '';//开放平台密码

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * 注册服务参数
     * @param null $client_id 创建应用分配的client_id
     * @param null $client_secret 创建应用分配的client_secret
     * @param null $username 通通锁或通通锁APP的登录账号，也可以是注册用户接口返回的账号。注意：请别使用开发者账号。
     * @param null $password 账号密码
     */
    public function config($client_id = null, $client_secret = null, $username = null, $password = null)
    {
        $this->client_id = !empty($client_id) ? $client_id : '';
        $this->client_secret = !empty($client_secret) ? $client_secret : '';
        $this->username = !empty($username) ? $username : '';
        $this->password = !empty($password) ? $password : '';
    }


    /**
     * 获取access_token
     * @return string 返回json字符串
     */
    public function getAccessToken()
    {
        //你还可以提供其他可选的构造函数参数
        $url = 'https://api.ttlock.com/oauth2/token';

        $response = $this->client->post($url, [
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'client_id' => $this->client_id,//参数
                'client_secret' => $this->client_secret,//参数
//                'username' => '+8613885168270',//参数
                'username' => '+86' . $this->username,//参数
                'password' => md5($this->password),//参数
            ]
        ]);
        //返回值
        $result = $response->getBody()->getContents();
        return $result;
    }

    /**
     * @param $refresh_token 刷新令牌
     * @return string 返回结果
     */
    public function refreshAccessToken($refresh_token)
    {
        //你还可以提供其他可选的构造函数参数
        $url = 'https://api.ttlock.com/oauth2/token';

        $response = $this->client->post($url, [
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'client_id' => $this->client_id,//参数
                'client_secret' => $this->client_secret,//参数
                'grant_type' => 'refresh_token',//参数
                'refresh_token' => $refresh_token,//参数
            ]
        ]);
        //返回值
        $result = $response->getBody()->getContents();
        return $result;
    }

    /**
     * 添加自定义密码-仅对已通过App初始化过的门锁生效
     * @param $lockId 锁ID，锁初始化获取
     * @param $access_token 访问令牌
     * @param $keyboardPwd 密码
     * @param $startDate 有效期开始时间（时间戳）
     * @param $endDate 有效期开始时间（时间戳)
     * @return string json字符串
     */
    public function createSelfMakeLockPwd($lockId, $access_token, $keyboardPwd, $startDate, $endDate)
    {
        $url = 'https://api.ttlock.com/v3/keyboardPwd/add';
        $response = $this->client->post($url, [
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'clientId' => $this->client_id,//参数
                'accessToken' => $access_token,//参数
                'lockId' => $lockId,//参数
                'keyboardPwd' => $keyboardPwd,//参数
                'startDate' => $startDate * 1000,//参数
                'endDate' => $endDate * 1000,//参数
                'date' => time() * 1000,//参数
            ]
        ]);
        $result = $response->getBody()->getContents();
        return $result;

    }

    /**
     * 获取锁-临时密码-仅对已通过App初始化过的门锁生效
     * @param $lockId 锁ID，锁初始化获取
     * @param $access_token 访问令牌
     * @param $startDate 有效期开始时间（时间戳）
     * @param $endDate 有效期结束时间（时间戳）
     * @return string
     */
    public function getRandPwdUnlock($lockId, $access_token, $startDate, $endDate)
    {
        $url = 'https://api.ttlock.com/v3/keyboardPwd/get';
        $response = $this->client->post($url, [
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'clientId' => $this->client_id,//参数
                'accessToken' => $access_token,//参数
                'lockId' => $lockId,//参数
                'keyboardPwdType' => 3,//参数
                'startDate' => $startDate * 1000,//参数
                'endDate' => $endDate * 1000,
                'date' => time() * 1000,//参数
            ]
        ]);
        //返回值
        $result = $response->getBody()->getContents();
        return $result;
    }

    /**
     * 获取锁的密码列表-仅对已通过App初始化过的门锁生效
     * @param $lockId 锁ID，锁初始化获取
     * @param $access_token 访问令牌
     * @param $pageNo 页码，从1开始
     * @param $pageSize 每页数量，最大100
     * @return string
     */
    public function getListKeyboardPwd($lockId, $access_token, $pageNo, $pageSize)
    {
        $url = "https://api.ttlock.com/v3/lock/listKeyboardPwd";
        $response = $this->client->post($url, [
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'clientId' => $this->client_id,//参数
                'accessToken' => $access_token,//参数
                'lockId' => $lockId,//参数
                'pageNo' => $pageNo,//参数
                'pageSize' => $pageSize,//参数
                'date' => time() * 1000,//参数
            ]
        ]);
        //返回值
        $result = $response->getBody()->getContents();
        return $result;
    }

    /**
     * 修改密码
     * @param $lockId 锁ID
     * @param $access_token 访问令牌
     * @param $keyboardPwdId 键盘密码ID
     * @return string
     */
    public function updateKeyboardPwd($lockId, $access_token, $keyboardPwdId)
    {
        $url = "https://api.ttlock.com/v3/keyboardPwd/change";
        $response = $this->client->post($url, [
            'headers' => ['Content-type' => 'application/x-www-form-urlencoded'],
            'form_params' => [
                'clientId' => $this->client_id,//参数
                'accessToken' => $access_token,//参数
                'lockId' => $lockId,//参数
                'keyboardPwdId' => $keyboardPwdId,//参数
                'date' => time() * 1000,//参数
            ]
        ]);
        //返回值
        $result = $response->getBody()->getContents();
        return $result;
    }

}