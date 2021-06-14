<?php

namespace Lib;

class Database
{

  public $dbInstance;

  public function __construct()
  {
    try {
      $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";port=" . $_ENV['DB_PORT'] . ";charset=" . $_ENV['DB_CHARSET'];
      $pdo = new \PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);

      if (isset($_ENV['DB_EXCEPTIONS']) && $_ENV['DB_EXCEPTIONS'] === 'true') {
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      }

      $this->dbInstance = $pdo;
    } catch (\PDOException $e) {
      $this->handleError($e->getMessage());
      die('Error on connection with database.');
    }
  }

  public function query($queryString = null, $params = [])
  {
    if (!$this->dbInstance) return false;

    try {
      $stmt = $this->dbInstance->prepare($queryString);
      $stmt->execute($params);
      return [
        'lastId' => (int)$this->dbInstance->lastInsertId(),
        'queryString' => $queryString,
        'params' => $params,
        'rowCount' => $stmt->rowCount(),
        'error' => false,
        'errorMessage' => null,
        'errorCode' => 0,
      ];
    } catch (\PDOException $e) {
      $this->handleError($e->getMessage());
      return [
        'lastId' => 0,
        'queryString' => $queryString,
        'params' => $params,
        'rowCount' => 0,
        'error' => true,
        'errorMessage' => $e->getMessage(),
        'errorCode' => $e->errorInfo[1],
      ];
    }
  }

  public function begin()
  {
    if (!$this->dbInstance) return false;

    return $this->dbInstance->beginTransaction();
  }

  public function commit()
  {
    if (!$this->dbInstance) return false;

    return $this->dbInstance->commit();
  }

  public function rollback()
  {
    if (!$this->dbInstance) return false;

    return $this->dbInstance->rollBack();
  }

  public function fetch($queryString = null, $params = [], $fetchAll = true, $catalog = false)
  {
    if (!$this->dbInstance) return false;

    try {
      $stmt = $this->dbInstance->prepare($queryString);
      $stmt->execute($params);
      return [
        'queryString' => $queryString,
        'params' => $params,
        'rowCount' => $stmt->rowCount(),
        'error' => false,
        'errorMessage' => null,
        'errorCode' => 0,
        'result' => $fetchAll ? $stmt->fetchAll(2) : $stmt->fetch(2)
      ];
    } catch (\PDOException $e) {
      $this->handleError($e->getMessage());
      return [
        'queryString' => $queryString,
        'params' => $params,
        'rowCount' => 0,
        'error' => true,
        'errorMessage' => $e->getMessage(),
        'errorCode' => $e->errorInfo[1],
        'result' => null
      ];
    }
  }

  public function insert($table = null, $data = [])
  {
    if (!$this->dbInstance || !$table || !$data) return false;

    try {
      $paramsCount = count($data);
      $paramsValues = array_values($data);
      $queryString = "INSERT INTO " . $table;
      $queryString .= " (" . implode(",", array_keys($data)) . ")";
      $queryString .= " VALUES(" . substr(str_repeat("?,", $paramsCount), 0, -1) . ")";

      $stmt = $this->dbInstance->prepare($queryString);
      $stmt->execute($paramsValues);

      return [
        'lastId' => (int)$this->dbInstance->lastInsertId(),
        'queryString' => $queryString,
        'params' => $paramsValues,
        'rowCount' => $stmt->rowCount(),
        'error' => false,
        'errorMessage' => null,
        'errorCode' => 0,
      ];
    } catch (\PDOException $e) {
      $this->handleError($e->getMessage());
      return [
        'lastId' => 0,
        'queryString' => $queryString,
        'params' => $paramsValues,
        'rowCount' => 0,
        'error' => true,
        'errorMessage' => $e->getMessage(),
        'errorCode' => $e->errorInfo[1],
      ];
    }
  }

  public function update($table, $data = array(), $whereString = null, $whereData = array())
  {

    if (!$table || !$data) return false;

    try {

      // Keys
      $keys = array_map(function ($item) {
        return $item . '=?';
      }, array_keys($data));

      // Keys implode
      $keysImplode = implode(",", $keys);

      // Query string
      $queryString = "UPDATE {$table} SET {$keysImplode}";

      if ($whereString) {
        $queryString .= " WHERE {$whereString}";
        $values = array_merge(
          array_values($data),
          array_values($whereData)
        );
      } else {
        $values = array_values($data);
      }

      // Stmt
      $stmt = $this->dbInstance->prepare($queryString);
      $stmt->execute($values);

      return [
        'lastId' => (int)$this->dbInstance->lastInsertId(),
        'queryString' => $queryString,
        'params' => $values,
        'rowCount' => $stmt->rowCount(),
        'error' => false,
        'errorMessage' => null,
        'errorCode' => 0,
      ];
    } catch (\PDOException $e) {
      $this->handleError($e->getMessage());
      return [
        'lastId' => 0,
        'queryString' => $queryString,
        'params' => $values,
        'rowCount' => 0,
        'error' => true,
        'errorMessage' => $e->getMessage(),
        'errorCode' => $e->errorInfo[1],
      ];
    }
  }

  public function handleError($string = null)
  {
    if (isset($_ENV['DB_LOG']) && $_ENV['DB_LOG'] === 'true') {
      $logInfo = "[" . date('Y-m-d H:i:s') . "] " . $string . "\n";
      $logPath = ROOT . DS . 'tmp' . DS . 'database_error.log';
      @file_put_contents($logPath, $logInfo, FILE_APPEND);
    }

    if (isset($_ENV['DB_DIE_ON_ERR']) && $_ENV['DB_DIE_ON_ERR'] === 'true') {
      if (isset($_ENV['ERROR_REPORTING']) && $_ENV['ERROR_REPORTING'] == 'E_ALL') {
        die($string);
      }

      die;
    }
  }
}
