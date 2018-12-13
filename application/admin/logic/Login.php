<?php
namespace app\admin\logic;
use think\Session;
use think\Cookie;
use app\admin\model\SysUser as User;
use app\admin\logic\Auth as AuthLogic;
/**
* 登录逻辑处理类
*/
class Login
{
	private static $tokenKey = 'dms_sys_user_key';
	/**
	 * 登录逻辑处理
	 * @param  array $data POST请求到的数据
	 * @return bool | string  验证通过返回true否则返回错误信息
	 */
	public static function login($data)
	{
		if(empty($data['username']) || empty($data['password'])){
			return '请输入账号或密码！';
		}
		if(isset($data['captcha'])){ //如果有验证码
			if(!captcha_check($data['captcha'])){
				return '验证码错误！';
			};
		}
		$user = User::get(['username'=>$data['username']]);
		if(!$user){
			return '该账号不存在！';
		}
		if($user->status !== 1){
			return '该账号被禁用，请联系管理员！';
		}
		if(password_verify($data['password'], $user->password)){
			$user->is_login = 1; //保存登录状态
			$user->token = self::getToken($user->username);
			$user->save();
			$user = $user->toArray();
			unset($user['password']);
			$session = Session::set('sysUser',$user);
			if(!empty($data['remember'])){
				$cookie = Cookie::set('sys_user', $user, 3600*24*7); //保存一周时间
			}
			return true;
		}else{
			return '密码错误！';
		}
			
	}

	/**
	 * 生成token
	 * @param  string $string 
	 * @return string(hash)
	 */
	public static function getToken($string)
	{
		$string = (string)$string . microtime() . self::$tokenKey;
		return sha1($string);
	} 

	/**
	 * 验证token
	 * @param  int $uid  用户id
	 * @param  string $hash 
	 * @return bool
	 */
	public static function checkToken($uid, $hash)
	{
		$user = User::get($uid);
		if($user){
			return $user->token == $hash;
		}
		return false;
	}

	/**
	 * 获取登录错误次数
	 * @return int 
	 */
	public static function getLoginNum()
	{
		$login_num = Session::get('login_num');
		if(!empty($login_num) && time()<$login_num['expire_time']){
			return (int)$login_num['num'];
		}
		return 0;
	}

	/**
	 * 保存登录错误次数，保存时间为2小时
	 * @param integer $num 登录次数
	 */
	public static function setLoginNum($num=0)
	{
		Session::set('login_num', ['num'=>(int)$num+1, 'expire_time'=>time()+7200]);
	}

	/**
	 * 清除当前作用域 session
	 */
	public static function clearSession()
	{
		Session::clear();
	}

	/**
	 * 清除当前作用域 cookie
	 */
	public static function clearCookie()
	{
		Cookie::clear();
	}

	/*
	 * 用户是否登录状态
	 */
	public static function isLogin()
	{
		if($cookieUser = Cookie::get('sys_user')){ //记住登入
			$user = User::where('id', $cookieUser['id'])->where('status','<>', 0)->field('id,username,portrait,email,phone,create_time,token')->find();
			if($user && $user['token']===$cookieUser['token']){
				$user->token = '';
				return $user;
			}
		}
		if($user = Session::get('sysUser')){
			$currentUser = User::where('id', $user['id'])->where('status','<>', 0)->field('id,username,portrait,email,phone,create_time')->find();
			if($currentUser){
				return $currentUser;
			}
		}
		return false;
	}
}