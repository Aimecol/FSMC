<?php
/**
 * Database Configuration for FSMC Admin System
 * Created: 2025-01-22
 * Description: Database connection and configuration settings
 */

// Prevent direct access
if (!defined('ADMIN_ACCESS')) {
    die('Direct access not permitted');
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'fsmc_db');
define('DB_CHARSET', 'utf8mb4');

/**
 * Database Connection Class using MySQLi
 */
class Database {
    private static $instance = null;
    private $connection;
    private $host = DB_HOST;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    private $charset = DB_CHARSET;

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        $this->connect();
    }

    /**
     * Get database instance (Singleton pattern)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establish database connection
     */
    private function connect() {
        try {
            // Create MySQLi connection
            $this->connection = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->database
            );

            // Check connection
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }

            // Set charset
            if (!$this->connection->set_charset($this->charset)) {
                throw new Exception("Error setting charset: " . $this->connection->error);
            }

            // Set timezone
            $this->connection->query("SET time_zone = '+00:00'");

        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Database connection failed. Please check configuration.");
        }
    }

    /**
     * Get MySQLi connection
     */
    public function getConnection() {
        // Check if connection is still alive
        if (!$this->connection->ping()) {
            $this->connect();
        }
        return $this->connection;
    }

    /**
     * Execute a prepared statement
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->connection->error);
            }

            if (!empty($params)) {
                $types = '';
                $values = [];
                
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } else {
                        $types .= 's';
                    }
                    $values[] = $param;
                }
                
                $stmt->bind_param($types, ...$values);
            }

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            return $stmt;

        } catch (Exception $e) {
            error_log("Database query error: " . $e->getMessage() . " SQL: " . $sql);
            throw $e;
        }
    }

    /**
     * Get single row
     */
    public function getRow($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    /**
     * Get multiple rows
     */
    public function getRows($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    /**
     * Insert record and return ID
     */
    public function insert($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $insertId = $this->connection->insert_id;
        $stmt->close();
        return $insertId;
    }

    /**
     * Update/Delete records and return affected rows
     */
    public function execute($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $affectedRows = $this->connection->affected_rows;
        $stmt->close();
        return $affectedRows;
    }

    /**
     * Begin transaction
     */
    public function beginTransaction() {
        return $this->connection->autocommit(false);
    }

    /**
     * Commit transaction
     */
    public function commit() {
        $result = $this->connection->commit();
        $this->connection->autocommit(true);
        return $result;
    }

    /**
     * Rollback transaction
     */
    public function rollback() {
        $result = $this->connection->rollback();
        $this->connection->autocommit(true);
        return $result;
    }

    /**
     * Escape string for safe SQL usage
     */
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }

    /**
     * Get last error
     */
    public function getError() {
        return $this->connection->error;
    }

    /**
     * Get last error number
     */
    public function getErrorNo() {
        return $this->connection->errno;
    }

    /**
     * Close connection
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }

    /**
     * Close connection on destruction
     */
    public function __destruct() {
        $this->close();
    }
}

/**
 * Helper function to get database instance
 */
function getDB() {
    return Database::getInstance();
}

/**
 * Helper function to execute queries with error handling
 */
function dbQuery($sql, $params = []) {
    try {
        return getDB()->query($sql, $params);
    } catch (Exception $e) {
        error_log("Database query failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Helper function to get single row with error handling
 */
function dbGetRow($sql, $params = []) {
    try {
        return getDB()->getRow($sql, $params);
    } catch (Exception $e) {
        error_log("Database getRow failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Helper function to get multiple rows with error handling
 */
function dbGetRows($sql, $params = []) {
    try {
        return getDB()->getRows($sql, $params);
    } catch (Exception $e) {
        error_log("Database getRows failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Helper function to insert with error handling
 */
function dbInsert($sql, $params = []) {
    try {
        return getDB()->insert($sql, $params);
    } catch (Exception $e) {
        error_log("Database insert failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Helper function to execute with error handling
 */
function dbExecute($sql, $params = []) {
    try {
        return getDB()->execute($sql, $params);
    } catch (Exception $e) {
        error_log("Database execute failed: " . $e->getMessage());
        return false;
    }
}
?>
