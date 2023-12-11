<?php
// Подключение к базе данных (замените параметры подключения на свои)
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

// Получение данных из GET-параметров
$projectId = isset($_GET['project_id']) ? $_GET['project_id'] : null;
$employeeId = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;

if ($projectId !== null && $employeeId !== null) {
    // SQL-запрос для удаления сотрудника из проекта
    $sql = "DELETE FROM EmployeeAssignments WHERE Project_ID = ? AND Employee_ID = ?";

    // Подготовка запроса
    $stmt = $pdo->prepare($sql);

    // Выполнение запроса
    $stmt->execute([$projectId, $employeeId]);

    // Возвращение успешного ответа
    echo 'Success';
} else {
    // Возвращение ошибки
    echo 'Error: Missing project_id or employee_id';
}
?>
