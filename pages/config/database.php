<?php
/**
 * Frontend Database Configuration for FSMC Website
 * Created: 2025-09-24
 * Description: Database connection for frontend website
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'fsmc_db');
define('DB_CHARSET', 'utf8mb4');

/**
 * Simple Database Connection Class for Frontend
 */
class FrontendDB {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset(DB_CHARSET);
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Database connection failed.");
        }
    }

    public function getConnection() {
        if (!$this->connection->ping()) {
            $this->connect();
        }
        return $this->connection;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->connection->error);
            }

            if (!empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } else {
                        $types .= 's';
                    }
                }
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            error_log("Database query error: " . $e->getMessage());
            return false;
        }
    }

    public function getRows($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        if (!$stmt) return [];
        
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    public function getRow($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        if (!$stmt) return null;
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }
}

// Helper functions
function getDB() {
    return FrontendDB::getInstance();
}

function dbGetRows($sql, $params = []) {
    return getDB()->getRows($sql, $params);
}

function dbGetRow($sql, $params = []) {
    return getDB()->getRow($sql, $params);
}

// Helper function to get company settings
function getCompanySettings() {
    static $settings = null;
    if ($settings === null) {
        $rows = dbGetRows("SELECT setting_key, setting_value FROM company_settings WHERE is_public = 1");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings;
}

function getSetting($key, $default = '') {
    $settings = getCompanySettings();
    return isset($settings[$key]) ? $settings[$key] : $default;
}

// Helper function to format price
function formatPrice($price) {
    if ($price === null || $price == 0) {
        return 'Contact for price';
    }
    return number_format($price, 0) . ' RWF';
}

// Helper function to get file URL
function getFileUrl($filePath) {
    if (empty($filePath)) {
        return '../images/placeholder.jpg';
    }
    return '../uploads/' . ltrim($filePath, '/');
}
?>
