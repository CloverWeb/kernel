<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/20
 * Time: 22:36
 */

namespace Joking\Kernel\Database\Connection;

class Connection {

    protected $dsn;
    protected $username;
    protected $password;
    protected $options = [];

    protected $pdo;

    public function __construct($dsn, $username, $password, $options = []) {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options = $options;
        $this->pdo = new \PDO($this->dsn, $username, $password, $options);
    }

    /**
     * @param $sql
     * @param array $params
     * @return ResultSet
     */
    public function query($sql, $params = null) {
        $result = $this->pdo->prepare($sql);
        return $this->createResultSet($result->execute($params), $result);
    }

    public function insert($sql, $params = null) {
        $result = $this->pdo->prepare($sql);
        return $this->createResultSet($result->execute($params), $result);
    }

    public function update($sql, $params = []) {
        $result = $this->pdo->prepare($sql);
        return $this->createResultSet($result->execute($params), $result);
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }

    /**
     * 开启事物系统
     */
    public function beginTransaction() {
        $this->pdo->beginTransaction();
    }

    /**
     * 提交事物
     */
    public function commit() {
        $this->pdo->commit();
    }

    /**
     * 回滚
     */
    public function rollBack() {
        $this->pdo->rollBack();
    }

    /**
     * 关闭连接
     */
    public function close() {
        $this->pdo = null;
    }

    /**
     * 是否已经关闭
     * @return bool true：已经关闭
     */
    public function isClosed() {
        return is_null($this->pdo);
    }

    /**
     * 创建ResultSet结果集
     * @param bool $success
     * @param \PDOStatement $statement
     * @return ResultSet
     */
    protected function createResultSet($success, \PDOStatement $statement) {
        $resultSet = new ResultSet($statement);
        $resultSet->success = $success;
        return $resultSet;
    }
}