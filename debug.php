<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting debug...\n";

// Test 1: Check if config file exists
if (file_exists('config.php')) {
    echo "✓ Config file exists\n";
    include 'config.php';
    echo "✓ Config loaded\n";
} else {
    echo "✗ Config file missing\n";
    exit;
}

// Test 2: Check if functions file exists
if (file_exists('functions.php')) {
    echo "✓ Functions file exists\n";
    include 'functions.php';
    echo "✓ Functions loaded\n";
} else {
    echo "✗ Functions file missing\n";
    exit;
}

// Test 3: Check database constants
echo "Database config:\n";
echo "- DB Name: " . (defined('BXC_DB_NAME') ? BXC_DB_NAME : 'NOT DEFINED') . "\n";
echo "- DB User: " . (defined('BXC_DB_USER') ? BXC_DB_USER : 'NOT DEFINED') . "\n";
echo "- DB Host: " . (defined('BXC_DB_HOST') ? BXC_DB_HOST : 'NOT DEFINED') . "\n";
echo "- DB Port: " . (defined('BXC_DB_PORT') ? BXC_DB_PORT : 'NOT DEFINED') . "\n";

// Test 4: Try direct MySQL connection
echo "\nTesting direct MySQL connection...\n";
try {
    $mysqli = new mysqli(BXC_DB_HOST, BXC_DB_USER, BXC_DB_PASSWORD, BXC_DB_NAME, BXC_DB_PORT);
    
    if ($mysqli->connect_error) {
        echo "✗ MySQL connection failed: " . $mysqli->connect_error . "\n";
    } else {
        echo "✓ MySQL connection successful\n";
        
        // Test 5: Check if database exists
        $result = $mysqli->query("SHOW DATABASES LIKE '" . BXC_DB_NAME . "'");
        if ($result && $result->num_rows > 0) {
            echo "✓ Database '" . BXC_DB_NAME . "' exists\n";
            
            // Test 6: Check tables
            $result = $mysqli->query("SHOW TABLES");
            if ($result) {
                echo "✓ Tables found: " . $result->num_rows . "\n";
                while ($row = $result->fetch_array()) {
                    echo "  - " . $row[0] . "\n";
                }
            } else {
                echo "✗ No tables found or error: " . $mysqli->error . "\n";
            }
        } else {
            echo "✗ Database '" . BXC_DB_NAME . "' does not exist\n";
        }
        
        $mysqli->close();
    }
} catch (Exception $e) {
    echo "✗ Exception: " . $e->getMessage() . "\n";
}

echo "\nDebug complete.\n";
?> 