<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 20:23
 */

namespace Joking\Kernel\Database;


use Joking\Kernel\Database\Connection\Connection;
use Joking\Kernel\Support\Singleton;

/**
 * Class ConnectionManager
 * @package Joking\Kernel\Database
 */
class ConnectionManager extends Singleton {


    public function __construct($config) {
        $this->config = $config;
    }

    /**
     * name=> [
     *  connection=mysql
     *  host=localhost
     *  port=3306
     *  database=joking
     *  username=root
     *  password=root
     *  option=[]
     * ]
     *
     * @var array
     */
    protected $config = [];

    /**
     * 数据库连接存放的地方
     * @var array
     */
    protected static $connections = [];


    /**
     * @param $name
     * @return Connection
     */
    public function getConnection($name) {
        $this->isCreated($name) || $this->createConnection($name);
        return static::$connections[$name];
    }

    /**
     * @param string|Connection $connection
     */
    public function close($connection) {
        if ($connection instanceof Connection) {
            $connection->close();
        } else if ($this->isCreated($connection)) {
            static::$connections[$connection]->close();
        }
    }


    /**
     * 创建一个已经配置了的新链接
     * @param $name
     * @return Connection
     */
    protected function createConnection($name) {
        if (empty($this->config[$name])) {
            throw new \PDOException('没有配置链接：' . $name);
        }

        $config = $this->config[$name];
        $config['options'] = isset($config['options']) ? $config['options'] : null;
        $must = ['connection', 'host', 'port', 'database', 'username', 'password'];
        for ($i = 0; $i < count($must); $i++) {
            if (!array_key_exists($must[$i], $config)) {
                throw new \PDOException('数据库配置错误，' . $must[$i] . ' 为必须字段！！');
            }
        }
        $dsn = $config['connection']
            . ':host=' . $config['host']
            . ';port=' . $config['port']
            . ';dbname=' . $config['database'];


        static::$connections[$name] = new Connection($dsn, $config['username'], $config['password'], $config['options']);
        return static::$connections[$name];
    }

    public function isCreated($name) {
        if (isset(static::$connections[$name])) {
            if (static::$connections[$name] instanceof Connection) {
                return !static::$connections[$name]->isClosed();
            }
        }

        return false;
    }
}