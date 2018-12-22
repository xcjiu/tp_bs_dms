<?php
namespace app\admin\logic;
use think\Session;
use think\Cookie;
use app\admin\model\SysUser as User;
use app\admin\logic\Auth as AuthLogic;
use app\admin\model\SysUserLog;
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
			$user->last_login_time = time(); //保存最后登录时间
			$user->token = self::getToken($user->username);
			$user->save();
			$user = $user->toArray();
			unset($user['password']);
			$session = Session::set('sysUser',$user);
			if(!empty($data['remember'])){
				$cookie = Cookie::set('sys_user', $user, 3600*24*7); //保存一周时间
			}
			SysUserLog::create(['user_id'=>$user['id'], 'type'=>1, 'ip'=>self::get_client_ip()]);
			return true;
		}else{
			return '密码错误！';
		}
	}

	/**
	 * 退出登录
	 * @param  [type] $uid [description]
	 * @return [type]      [description]
	 */
	public static function logout()
	{
    $user = Session::get('sysUser') ? Session::get('sysUser') : Cookie::get('sys_user');
    if($user){
    	SysUserLog::create(['user_id'=>$user['id'], 'type'=>2, 'ip'=>self::get_client_ip()]);
    }
		Session::clear();
    Cookie::clear();
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
			$user = User::where('id', $cookieUser['id'])->where('status','<>', 0)->field('id,username,portrait,email,phone,create_time,token,last_login_time')->find();
			if($user && $user['token']===$cookieUser['token']){
				if( !(\think\Config::get('login_more')) && $user['last_login_time'] != $cookieUser['last_login_time']){
					return 119; //说明在别处有登入操作
				}
				return $user;
			}
		}
		if($user = Session::get('sysUser')){
			$currentUser = User::where('id', $user['id'])->where('status','<>', 0)->field('id,username,portrait,email,phone,create_time,token,last_login_time')->find();
			if($currentUser){
				if( !(\think\Config::get('login_more')) && $user['last_login_time'] != $currentUser['last_login_time']){
					return 119; //说明在别处有登入操作
				}
				return $currentUser;
			}
		}
		return false;
	}

	/**
	 * 获取IP地址
	 * @return [type] [description]
	 */
	protected static function get_client_ip() {
	  if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
	      $ip = getenv('HTTP_CLIENT_IP');
	  } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
	      $ip = getenv('HTTP_X_FORWARDED_FOR');
	  } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
	      $ip = getenv('REMOTE_ADDR');
	  } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
	      $ip = $_SERVER['REMOTE_ADDR'];
	  } else {
	      $ip = '127.0.0.1';
	  }
	  return preg_match('/[\d\.]{7,15}/', $ip, $matches) ? $matches[0] : '';
	}



}