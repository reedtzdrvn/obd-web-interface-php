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
    $projectName = $_POST['project_name'];
    $description = $_POST['description'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $status = $_POST['status'];

    // Выполнение запроса для добавления нового проекта
    $stmt = $pdo->prepare('INSERT INTO Projects (Project_Name, Description, Start_Date, End_Date, Status) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$projectName, $description, $startDate, $endDate, $status]);

    // Перенаправление на страницу с проектами после добавления
    header('Location: get_projects.php');
} else {
    // Верните ошибку, если метод запроса не POST
    http_response_code(400);
    echo 'Ошибка: Неверный метод запроса';
}
?>
