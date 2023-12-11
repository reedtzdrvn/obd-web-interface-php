<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Комментарии к задачам</title>
    <link rel="stylesheet" href="./app/style.css">
</head>
<body>

<header>
    <h1>Комментарии к задачам</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Главная</a></li>
        <li><a href="get_tasks.php">Посмотреть все задачи</a></li>
        <li><a href="get_reports.php">Посмотреть все отчеты по задачам</a></li>
        <li><a href="get_projects.php">Посмотреть все проекты</a></li>
        <li><a href="workers.php">Посмотреть работников</a></li>
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

$stmt = $pdo->query('SELECT * FROM TaskComments');

// Выполнение запроса к базе данных для задач
$stmtTasks = $pdo->query('SELECT * FROM Tasks');

// Выполнение запроса к базе данных для работников
$stmtEmployees = $pdo->query('SELECT * FROM Employees');
?>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Задача</th>
        <th>Работник</th>
        <th>Текст комментария</th>
        <th>Дата комментария</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['Comment_ID']; ?></td>
            <td><?php echo $row['Task_ID']; ?></td>
            <td><?php echo $row['Employee_ID']; ?></td>
            <td><?php echo $row['Comment_Text']; ?></td>
            <td><?php echo $row['Comment_Date']; ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- Форма для добавления комментария -->
<form action="add_comment.php" method="post">
    <label for="task_id">Задача:</label>
    <select id="task_id" name="task_id" required>
        <?php while ($task = $stmtTasks->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?php echo $task['Task_ID']; ?>"><?php echo $task['Task_Name']; ?></option>
        <?php endwhile; ?>
    </select>

    <label for="employee_id">Работник:</label>
    <select id="employee_id" name="employee_id" required>
        <?php while ($employee = $stmtEmployees->fetch(PDO::FETCH_ASSOC)): ?>
            <option value="<?php echo $employee['Employee_ID']; ?>"><?php echo $employee['Full_Name']; ?></option>
        <?php endwhile; ?>
    </select>

    <label for="comment_text">Текст комментария:</label>
    <textarea id="comment_text" name="comment_text" rows="4" required></textarea>

    <button type="submit">Добавить комментарий</button>
</form>

</body>
</html>
