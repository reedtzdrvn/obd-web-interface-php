<?php
$host = 'sql11.freemysqlhosting.net';
$db = 'sql11668236';
$user = 'sql11668236';
$pass = '1jjFpaJ7Vj';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die('Подключение не удалось: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['Task_ID'])) {
    $task_id = $_GET['Task_ID'];

    // Начало транзакции
    $pdo->beginTransaction();

    try {
        // Подготовка SQL-запроса для удаления из таблицы TaskPriorities
        $sql_delete_priority = "DELETE FROM TaskPriorities WHERE Task_ID = :task_id";

        // Подготовка и выполнение запроса для удаления из таблицы TaskPriorities
        $stmt_delete_priority = $pdo->prepare($sql_delete_priority);
        $stmt_delete_priority->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt_delete_priority->execute();

        $sql_delete_comments = "DELETE FROM TaskComments WHERE Task_ID = :task_id";

        // Подготовка и выполнение запроса для удаления из таблицы TaskPriorities
        $stmt_delete_comments = $pdo->prepare($sql_delete_comments);
        $stmt_delete_comments->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt_delete_comments->execute();

        // Подготовка SQL-запроса для удаления задачи
        $sql_delete_task = "DELETE FROM Tasks WHERE Task_ID = :task_id";

        // Подготовка и выполнение запроса для удаления задачи
        $stmt_delete_task = $pdo->prepare($sql_delete_task);
        $stmt_delete_task->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt_delete_task->execute();

        // Подтверждение транзакции
        $pdo->commit();

        // Редирект на страницу с задачами после удаления
        header('Location: get_tasks.php'); // Замените на ваш путь к странице с задачами
        exit();
    } catch (PDOException $e) {
        // Откат транзакции в случае ошибки
        $pdo->rollBack();
        echo 'Ошибка удаления задачи: ' . $e->getMessage();
    }
} else {
    echo 'Неверные параметры запроса';
}
?>