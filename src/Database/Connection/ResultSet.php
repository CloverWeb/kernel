<?php

namespace Joking\Kernel\Database\Connection;


class ResultSet {

    //是否执行成功
    public $success;

    protected $statement;

    public function __construct(\PDOStatement $statement) {
        $this->statement = $statement;
    }

    /**
     * 获取下一行的数据 (注：数据库指针移动到下一行)
     * @return mixed
     */
    public function next() {
        return $this->statement->fetch();
    }

    /**
     * 获取下一行的数据的指定列的数据 (注：数据库指针移动到下一行)
     * @param int $num
     * @return string
     */
    public function column($num = 0) {
        return $this->statement->fetchColumn($num);
    }

    /**
     * 取得所有数据集合
     * @return array
     */
    public function all() {
        return $this->statement->fetchAll();
    }

    /**
     * 返回上一次执行sql语句影响的行数
     * @return int
     */
    public function rowCount() {
        return $this->statement->rowCount();
    }

    /**
     * 返回上一次执行的sql语句
     * @return string
     */
    public function toSql() {
        return $this->statement->queryString;
    }

    /**
     * 获取数据库返回的数据有多少列
     * @return int
     */
    public function columnCount() {
        return $this->statement->columnCount();
    }
}