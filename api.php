<?php
// Prevent PHP warnings/errors from corrupting any JSON output
error_reporting(0);
ini_set('display_errors', 0);

/**
 * Expense Tracker API for XAMPP
 * Place this file inside your XAMPP htdocs folder (e.g., C:/xampp/htdocs/expense_tracker/api.php)
 * Make sure to start Apache & MySQL from the XAMPP Control Panel.
 * 
 * Access via: http://localhost/expense_tracker/api.php
 */

// Enable CORS so your front-end application can make API requests
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS requests gracefully
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = "localhost";
$username = "root";
$password = ""; // Default XAMPP MySQL password is empty
$dbname = "expense_tracker";

// 1. Establish MySQL Connection
$conn = @new mysqli($host, $username, $password);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// 2. Initialize Database and Tables
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($dbname);

// Table for Expenses
$createExpensesTable = "CREATE TABLE IF NOT EXISTS `expenses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `description` VARCHAR(255) NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `date` DATE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createExpensesTable);

// Table for Profile Settings
$createProfileTable = "CREATE TABLE IF NOT EXISTS `profile` (
    `id` INT PRIMARY KEY DEFAULT 1,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `budget` DECIMAL(10, 2) NOT NULL DEFAULT 15000.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$conn->query($createProfileTable);

// Ensure there is at least one default profile entry
$checkProfile = $conn->query("SELECT * FROM `profile` WHERE `id` = 1");
if ($checkProfile && $checkProfile->num_rows === 0) {
    $conn->query("INSERT INTO `profile` (`id`, `name`, `email`, `budget`) VALUES (1, 'Santya Shelake', 'santyashelake@gmail.com', 15000.00)");
}


// 3. Process API Request Routings
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'profile':
        handleProfileRoute($method, $conn);
        break;
    case 'expenses':
    default:
        handleExpensesRoute($method, $conn);
        break;
}

$conn->close();


/**
 * Helper function to parse JSON input from php://input
 */
function getJsonInput() {
    $rawInput = file_get_contents("php://input");
    return json_decode($rawInput, true);
}


/**
 * Handle routes targeting User Profiles (?action=profile)
 */
function handleProfileRoute($method, $conn) {
    if ($method === 'GET') {
        $result = $conn->query("SELECT * FROM `profile` WHERE `id` = 1");
        if ($row = $result->fetch_assoc()) {
            echo json_encode($row);
        } else {
            echo json_encode(["name" => "Santya Shelake", "email" => "santyashelake@gmail.com", "budget" => 15000]);
        }
    } 
    elseif ($method === 'POST' || $method === 'PUT') {
        $data = getJsonInput();
        if (!$data || empty($data['name']) || empty($data['email'])) {
            http_response_code(400);
            echo json_encode(["error" => "Name, Email are required values."]);
            return;
        }

        $name = $conn->real_escape_string($data['name']);
        $email = $conn->real_escape_string($data['email']);
        $budget = isset($data['budget']) ? (float)$data['budget'] : 15000.00;

        $stmt = $conn->prepare("UPDATE `profile` SET `name` = ?, `email` = ?, `budget` = ? WHERE `id` = 1");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
            return;
        }
        $stmt->bind_param("ssd", $name, $email, $budget);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Profile metrics updated.", "data" => $data]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed saving profile settings: " . $stmt->error]);
        }
        $stmt->close();
    }
}


/**
 * Handle routes targeting Expense entries (?action=expenses)
 */
function handleExpensesRoute($method, $conn) {
    // GET: Retrieve all elements
    if ($method === 'GET') {
        $result = $conn->query("SELECT * FROM `expenses` ORDER BY `date` DESC, `id` DESC");
        $expenses = [];
        while ($row = $result->fetch_assoc()) {
            $expenses[] = [
                "id" => (int)$row['id'],
                "desc" => $row['description'],
                "amount" => (float)$row['amount'],
                "cat" => $row['category'],
                "date" => $row['date']
            ];
        }
        echo json_encode($expenses);
    } 
    
    // POST: Insert a new item
    elseif ($method === 'POST') {
        $data = getJsonInput();
        if (!$data || empty($data['desc']) || !isset($data['amount']) || empty($data['cat']) || empty($data['date'])) {
            http_response_code(400);
            echo json_encode(["error" => "Required parameter fields missing."]);
            return;
        }

        $desc = $conn->real_escape_string($data['desc']);
        $amount = (float)$data['amount'];
        $cat = $conn->real_escape_string($data['cat']);
        $date = $conn->real_escape_string($data['date']);

        $stmt = $conn->prepare("INSERT INTO `expenses` (`description`, `amount`, `category`, `date`) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
            return;
        }
        $stmt->bind_param("sdss", $desc, $amount, $cat, $date);

        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            echo json_encode([
                "status" => "success",
                "message" => "Expense created successfully.",
                "data" => [
                    "id" => $newId,
                    "desc" => $data['desc'],
                    "amount" => $amount,
                    "cat" => $data['cat'],
                    "date" => $date
                ]
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Database insertion failed: " . $stmt->error]);
        }
        $stmt->close();
    } 
    
    // PUT: Update an existing transaction element
    elseif ($method === 'PUT') {
        $data = getJsonInput();
        if (!$data || empty($data['id']) || empty($data['desc']) || !isset($data['amount']) || empty($data['cat']) || empty($data['date'])) {
            http_response_code(400);
            echo json_encode(["error" => "Required parameters missing for update."]);
            return;
        }

        $id = (int)$data['id'];
        $desc = $conn->real_escape_string($data['desc']);
        $amount = (float)$data['amount'];
        $cat = $conn->real_escape_string($data['cat']);
        $date = $conn->real_escape_string($data['date']);

        $stmt = $conn->prepare("UPDATE `expenses` SET `description` = ?, `amount` = ?, `category` = ?, `date` = ? WHERE `id` = ?");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
            return;
        }
        $stmt->bind_param("sdssi", $desc, $amount, $cat, $date, $id);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Expense updated.",
                "data" => $data
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed updating record: " . $stmt->error]);
        }
        $stmt->close();
    } 
    
    // DELETE: Delete a transaction element
    elseif ($method === 'DELETE') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["error" => "Missing or invalid parameter ID for deletion."]);
            return;
        }

        $stmt = $conn->prepare("DELETE FROM `expenses` WHERE `id` = ?");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
            return;
        }
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Expense record deleted successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete: " . $stmt->error]);
        }
        $stmt->close();
    }
}
?>
