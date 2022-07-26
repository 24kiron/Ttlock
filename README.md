
# 通通锁SDK

[开放平台](https://open.ttlock.com)

**安装**

```
composer require hemingxing/ttlock
```
## Ttlock/api （均为post请求）
```
-- config 服务参数配置
```
```
-- getAccessToken 获取访问令牌
返回参数为json字符串，结果如下：
    {
         "access_token": "39caac89b0b51c980aa61ad4264b693b", //访问令牌
         "uid": 2340,//用户主键id
         "refresh_token": "1bd2a21a7df889630f444364813738d7",//刷新令牌
         "expires_in": 7776000,//访问令牌过期时间，单位秒
     }
```
```
-- refreshAccessToken 刷新访问令牌
```
###密码相关接口：
```
-- getRandPwdUnlock 获取开锁随机密码（从云端获取一个6-9位数字的随机密码用于开锁，密码由云端的算法规则生成，不需要锁连接网关就可以获取，位数主要由密码类型和有效期长短决定，密码随机生成，不可定制)
```
```
-- createSelfMakeLockPwd 添加自定义密码（向锁里写入一个4-9位的自定义限期密码)
```
```
-- getListKeyboardPwd 获取锁的密码列表（管理员或授权管理员查看这把锁上生成的所有密码，包含随机密码和自定义密码。)
返回参数：
{
    "list": [
        {
            "keyboardPwdId": 3234293, //键盘密码ID
            "lockId": 532323,//锁ID
            "keyboardPwd": "034234",//键盘密码
            "keyboardPwdName":"陈文亮",//键盘密码名称
            "keyboardPwdType": "3",//键盘密码类型（请自行前往开放平台查看对应类型内容）
            "startDate": 1528878944000,//有效期开始时间（时间戳，单位毫秒）
            "endDate": 1628878944000,//有效期结束时间（时间戳，单位毫秒）
            "sendDate": 1528878944000,//发送时间（时间戳，单位毫秒）
            "senderUsername": "alexa@google.com"//发送者用户名
        }
    ],//记录列表
    "pageNo":1,//页码，从1开始
    "pageSize":20,//每页数量，最大100
    "pages":1,//总页数
    "total":1//总条数
}
```
```
-- updateKeyboardPwd 修改密码（修改之前生成的随机密码或自定义密码）
```
## 写在最后

感谢您的使用，正在持续开发更多功能中...