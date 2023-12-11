<?php
$host = 'sql11.freemysqlhosting.net';
$dbname = 'sql11668236';
$username = 'sql11668236';
$password = '1jjFpaJ7Vj';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Ошибка подключения к базе данных: ' . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $taskId = $_GET['Task_ID'];
    $newStatus = $_GET['newStatus'];

    // Выполнение запроса для изменения статуса задачи
    $stmt = $pdo->prepare('UPDATE Tasks SET Status = ? WHERE Task_ID = ?');
    $stmt->execute([$newStatus, $taskId]);

    // Верните успешный статус HTTP (200 OK)
    http_response_code(200);
} else {
    // Верните ошибку, если метод запроса не PUT
    http_response_code(400);
}
?>
