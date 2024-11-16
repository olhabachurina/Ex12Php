<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$dsn = 'mysql:host=localhost;dbname=employees_db';
$username = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Ошибка подключения: ' . $e->getMessage();
    exit();
}

$country = isset($_GET['country']) ? explode(',', $_GET['country']) : [];
$city = isset($_GET['city']) ? explode(',', $_GET['city']) : [];
$sortField = isset($_GET['sort_field']) ? $_GET['sort_field'] : 'Name';
$sortOrder = isset($_GET['sort_order']) && $_GET['sort_order'] === 'desc' ? 'DESC' : 'ASC';

$query = "SELECT * FROM employees WHERE 1=1";
$params = [];

if (!empty($country)) {
    $placeholders = implode(',', array_fill(0, count($country), '?'));
    $query .= " AND Country IN ($placeholders)";
    $params = array_merge($params, $country);
}

if (!empty($city)) {
    $placeholders = implode(',', array_fill(0, count($city), '?'));
    $query .= " AND City IN ($placeholders)";
    $params = array_merge($params, $city);
}

$allowedSortFields = ['Name', 'Surname', 'Country', 'City', 'Salary'];
if (in_array($sortField, $allowedSortFields)) {
    $query .= " ORDER BY $sortField $sortOrder";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($results);
?>