<?php

namespace App\Database;

require_once 'App/Exceptions/throwException.php';
use App\Exceptions\ThrowException;

error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

class DB {
    private static $instance;
    private $connection;

    private function __construct() {
        $this->connect();
    }

    private function connect() {
        $dbHost = "127.0.0.1";
        $dbUsername = "admin";
        $dbPassword = "admin";
        $dbName = "billing";

        $this->connection = new \mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

        if ($this->connection->connect_error) {
            throw new ThrowException("Unable to connect to the database: " . $this->connection->connect_error);
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function sql($query, $params = []) {
        // Prepare the query
        $statement = $this->prepareStatement($query);

        // Bind parameters if provided
        $this->bindParams($statement, $params);
        
        // Execute the query
        $this->executeStatement($statement);

        // Return result
        return $this->getResult($statement, $query);
        
    }

    private function prepareStatement($query) {
        $statement = $this->connection->prepare($query);
        if (!$statement) {
            throw new ThrowException("Error preparing SQL query: " . $this->connection->error);
        }
        return $statement;
    }

    private function bindParams($statement, $params) {
        if (!empty($params)) {
            $paramTypes = '';
            $bindParams = [];
            foreach ($params as $index => $param) {
                $paramTypes .= $this->getParamType($param);
                $bindParams[$index] = &$params[$index];
            }
            array_unshift($bindParams, $paramTypes);

            if(!empty($paramTypes) && !call_user_func_array([$statement, 'bind_param'], $bindParams)) {
                throw new ThrowException("Error binding SQL query parameters: " . $this->connection->error);
            }
        }
    }

    private function executeStatement($statement) {
        if(!$statement->execute()) {
            throw new ThrowException("Error executing SQL query: " . $statement->error);
        }
    }

    private function getResult($statement, $query) {
        // Determine the query type
        $queryType = strtoupper(explode(" ", trim($query))[0]);

        // Return the result based on query type
        if ($queryType === "SELECT" || $queryType === "DESCRIBE") {
            $result = $statement->get_result();
            if ($result === false) {
                throw new ThrowException("Error getting SQL query result: " . $this->connection->error);
            }

            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }

            $statement->close();
            return $rows;
        } elseif ($queryType === "INSERT" || $queryType === "UPDATE" || $queryType === "DELETE") {
            $rowsAffected = $statement->affected_rows;
            $statement->close();
            return $rowsAffected;
        } else {
            // For other query types, return true or false based on success
            $success = ($statement->errno === 0);
            $statement->close();
            return $success;
        }
    }
    private function getParamType($param) {
        $paramType = "";
        if (is_int($param)) {
            $paramType =  "i";
        } elseif (is_float($param)) {
            $paramType = "d";
        } elseif (is_string($param)) {
            $paramType = "s";
        } else {
            $paramType = "b";
        }
        return $paramType;
    }
}
