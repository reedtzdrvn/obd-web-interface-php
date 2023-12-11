<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проекты</title>
    <link rel="stylesheet" href="./app/style.css">
</head>
<body>

<header>
    <h1>Все проекты</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Главная</a></li>
        <li><a href="get_tasks.php">Посмотреть все задачи</a></li>
        <li><a href="get_reports.php">Посмотреть все отчеты по задачам</a></li>
        <li><a href="workers.php">Посмотреть работников</a></li>
        <li><a href="get_comments.php">Посмотреть комментарии</a></li>
    </ul>
</nav>

<?php
// Подключение к базе данных (замените параметры подключения на свои)
$host = 'sql11.freemysqlhosting.net';
$db = 'sql11668236';
$user = 'sql11668236';
$pass = '1jjFpaJ7Vj';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die('Подключение не удалось: ' . $e->getMessage());
}

// Выполнение запроса к базе данных
$stmt = $pdo->query('SELECT * FROM Projects');
?>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Название проекта</th>
        <th>Описание</th>
        <th>Дата начала</th>
        <th>Дата конца</th>
        <th>Статус</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['Project_ID']; ?></td>
            <td><?php echo $row['Project_Name']; ?></td>
            <td><?php echo $row['Description']; ?></td>
            <td><?php echo $row['Start_Date']; ?></td>
            <td><?php echo $row['End_Date']; ?></td>
            <td><?php echo getStatusName($pdo, $row['Status']); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<form action="add_project.php" method="post">
    <label for="project_name">Название проекта:</label>
    <input type="text" id="project_name" name="project_name" required>

    <label for="description">Описание:</label>
    <textarea id="description" name="description" rows="4" required></textarea>

    <label for="start_date">Дата начала:</label>
    <input type="date" id="start_date" name="start_date" required>

    <label for="end_date">Дата конца:</label>
    <input type="date" id="end_date" name="end_date" required>

    <label for="status">Статус:</label>
    <select id="status" name="status" required>
        <option value="1">В процессе</option>
        <option value="2">Завершено</option>
    </select>

    <button type="submit">Добавить проект</button>
</form>

<?php

function getStatusName($pdo, $statusId)
{
    $stmt = $pdo->prepare('SELECT Status_Name FROM StatusTable WHERE Status_ID = ?');
    $stmt->execute([$statusId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result) ? $result['Status_Name'] : 'Неизвестно';
}

?>

</body>
</html>
