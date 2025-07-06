<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Simple database test...\n";

// Include only config
include 'config.php';

echo "Config loaded. Testing MySQL connection...\n";

try {
    $mysqli = new mysqli(BXC_DB_HOST, BXC_DB_USER, BXC_DB_PASSWORD, BXC_DB_NAME, BXC_DB_PORT);
    
    if ($mysqli->connect_error) {
        echo "Connection failed: " . $mysqli->connect_error . "\n";
    } else {
        echo "Connection successful!\n";
        
        // Check if database exists
        $result = $mysqli->query("SHOW DATABASES LIKE '" . BXC_DB_NAME . "'");
        if ($result && $result->num_rows > 0) {
            echo "Database exists.\n";
            
            // Check tables
            $result = $mysqli->query("SHOW TABLES");
            if ($result) {
                echo "Tables found: " . $result->num_rows . "\n";
                while ($row = $result->fetch_array()) {
                    echo "- " . $row[0] . "\n";
                }
            }
        } else {
            echo "Database does not exist.\n";
        }
        
        $mysqli->close();
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

echo "Test complete.\n";
?> 