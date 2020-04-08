<?php
namespace app\admin\controller;
use think\Config;
use think\Controller;

/**
* 数据库安装，启动项目
*/
class Setup extends Controller
{
  public function index()
  {
    $this->assign('action', $this->request->root(true) . '/admin/setup/start');
    return $this->fetch('setup/index');
  }

  //数据库建立
  public function start()
  {
    header("Content-type: text/html; charset=utf-8");
    $postData = $this->request->post();

    $hostname = trim($postData['hostname']);
    $database = trim($postData['database']);
    $username = trim($postData['username']);
    $password = trim($postData['password']);
    $hostport = trim($postData['hostport']);

    if(!$hostname || !$database || !$username || !$password || !$hostport) {
      return '所有选项必填！！！';
    }

    if(!@mysqli_connect($hostname,$username,$password)) {
      return '数据库连接出错！请检查填写内容';
    }

    $con = mysqli_connect($hostname,$username,$password);
    $sql = "CREATE DATABASE IF NOT EXISTS `$database` default charset utf8 COLLATE utf8_general_ci";

    if($con->query($sql)) {
      mysqli_select_db($con, $database);
      $sql = file_get_contents(APP_PATH . '../admin_sql/dms_admin.sql');
      $sqlData = explode(';', $sql);
      foreach ($sqlData as $s) {
        $con->query($s); //执行sql语句
      }
      mysqli_close($con);

      $databaseonfig = "[
        // 数据库类型
        'type'            => 'mysql',
        // 服务器地址
        'hostname'        => '$hostname',
        // 数据库名
        'database'        => '$database',
        // 用户名
        'username'        => '$username',
        // 密码
        'password'        => '$password',
        // 端口
        'hostport'        => '$hostport',
        // 连接dsn
        'dsn'             => '',
        // 数据库连接参数
        'params'          => [],
        // 数据库编码默认采用utf8
        'charset'         => 'utf8',
        // 数据库表前缀
        'prefix'          => 'dms_',
        // 数据库调试模式
        'debug'           => true,
        // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
        'deploy'          => 0,
        // 数据库读写是否分离 主从式有效
        'rw_separate'     => false,
        // 读写分离后 主服务器数量
        'master_num'      => 1,
        // 指定从服务器序号
        'slave_no'        => '',
        // 自动读取主库数据
        'read_master'     => false,
        // 是否严格检查字段是否存在
        'fields_strict'   => true,
        // 数据集返回类型
        'resultset_type'  => 'array',
        // 自动写入时间戳字段
        'auto_timestamp'  => false,
        // 时间字段取出后的默认时间格式
        'datetime_format' => 'Y-m-d H:i:s',
        // 是否需要进行SQL性能分析
        'sql_explain'     => false,
      ];";
      $f = fopen(CONF_PATH . 'admin/database.php', 'w');
      fwrite($f, '<?php'."\r\n".'return ' . (string)$databaseonfig);
      fclose($f);
      return 'success';
    }
    mysqli_close($con);
    return '数据库创建出错！';
  }

  //是否安装
  public function isSetup()
  {
    $databasePath = CONF_PATH . 'admin/database.php';
    $config = Config::get('database');
    $isConfig = (!empty($config['hostname']) && !empty($config['username']) && !empty($config['password']) && !empty($config['database']));
    if(file_exists($databasePath) && $isConfig && @mysqli_connect($config['hostname'],$config['username'],$config['password'],$config['database'])) {
      return true;
    }
    return false;
  }




}