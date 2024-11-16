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

// Получение уникальных стран и городов
$countryQuery = "SELECT DISTINCT Country FROM employees";
$cityQuery = "SELECT DISTINCT City FROM employees";

$countries = $pdo->query($countryQuery)->fetchAll(PDO::FETCH_COLUMN);
$cities = $pdo->query($cityQuery)->fetchAll(PDO::FETCH_COLUMN);

$response = [
    'countries' => $countries,
    'cities' => $cities
];

echo json_encode($response);
?>