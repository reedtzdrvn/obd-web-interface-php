<?php
// Подключение к базе данных (замените параметры подключения на свои)
$host = 'sql11.freemysqlhosting.net';
$db = 'sql11668236';
$user = 'sql11668236';
$pass = '1jjFpaJ7Vj';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    echo 'Ошибка подключения к базе данных: ' . $e->getMessage();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $taskID = $_POST['task_id'];
    $employeeID = $_POST['employee_id'];
    $commentText = $_POST['comment_text'];
    date_default_timezone_set('Europe/Moscow');
    $commentDate = date('Y-m-d H:i:s'); // Текущая дата и время

    // Выполнение запроса для добавления комментария
    $stmt = $pdo->prepare('INSERT INTO TaskComments (Task_ID, Employee_ID, Comment_Text, Comment_Date) VALUES (?, ?, ?, ?)');
    $stmt->execute([$taskID, $employeeID, $commentText, $commentDate]);

    // Перенаправление на страницу с комментариями после добавления
    header('Location: get_comments.php');
    exit;
} else {
    // Верните ошибку, если метод запроса не POST
    http_response_code(400);
    echo 'Ошибка: Неверный метод запроса';
    exit;
}
?>
