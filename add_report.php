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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из формы
    $projectId = $_POST['project'];
    $reportText = $_POST['report_text'];
    date_default_timezone_set('Europe/Moscow');
    $creationDate = date('Y-m-d H:i:s'); // Текущая дата и время

    try {
        // Выполнение запроса для добавления нового отчета
        $stmt = $pdo->prepare('INSERT INTO ProjectReports (Project_ID, Report_Text, Creation_Date) VALUES (?, ?, ?)');
        $stmt->execute([$projectId, $reportText, $creationDate]);

        // Верните успешный статус HTTP (200 OK)
        http_response_code(200);
    } catch (PDOException $e) {
        // Ошибка при выполнении запроса
        echo 'Ошибка при добавлении отчета: ' . $e->getMessage();
        http_response_code(500); // Внутренняя ошибка сервера
    }
} else {
    // Верните ошибку, если метод запроса не POST
    http_response_code(400); // Неверный запрос
}

header('Location: get_reports.php');
?>
