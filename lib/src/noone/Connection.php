<?php

namespace noone;

use Exception;
use PDO;
use PDOException;

abstract class Connection
{


    protected $link = null;

    /**
     * PDO操作实例
     * @var PDOStatement
     */
    protected $PDOStatement = null;

    /**
     * 返回或者影响记录数
     * @var int
     */
    protected $numRows = 0;

    protected $dataId;

    /**
     * 查询结果类型
     * @var int
     */
    protected $fetchType = PDO::FETCH_ASSOC;

    protected $config  = [
        'host' => 'localhost',
        'username' => '',
        'password' => '',
        'database' => '',
        'hostport' => '',
    ];

    protected $options = [];

    public function __construct(string $dataId, array $config)
    {
        $this->dataId = $dataId;
        $this->config = $config;
    }

    public function connect(array $config = []): PDO
    {
        if (!is_null($this->link[$this->dataId])) {
            return $this->link[$this->dataId];
        }
        if (!empty($config)) {
            $config = array_merge($this->config, $config);
        } else {
            $config = $this->config;
        }
        try {
            $dsn = $this->parseDsn($config);
            $this->link[$this->dataId] = $this->getPdo($dsn, $config['username'], $config['password'], $config['options']);
            return $this->link[$this->dataId];
        } catch (PDOException $e) {
            throw $e;
        }
    }


    public function getPdo($dsn, $username, $passwd, $params)
    {
        return new PDO($dsn, $username, $passwd, $params);
    }

    /**
     * 执行查询 返回数据集
     */
    public function query($sql, array $bind = []): array
    {
        $this->getPDOStatement($sql, $bind);

        $resultSet = $this->getResult();
        return $resultSet;
    }

    /**
     * 获得数据集数组
     * @access protected
     * @param bool $procedure 是否存储过程
     * @return array
     */
    protected function getResult(): array
    {
        $result = $this->PDOStatement->fetchAll($this->fetchType);

        return $result;
    }

    /**
     * 执行查询但只返回PDOStatement对象
     */
    public function getPDOStatement(string $sql, array $bind = [])
    {
        $this->connect();

        // 记录SQL语句
        $this->queryStr = $sql;

        try {

            // 预处理
            $this->PDOStatement = $this->link->prepare($sql);

            
            $this->bindValue($bind);
          

            // 执行查询
            $this->PDOStatement->execute();

            return $this->PDOStatement;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function bindValue(array $bind)
    {
        
    }

    abstract public function parseDsn(array $config);
}
