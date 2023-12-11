<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отчеты</title>
    <link rel="stylesheet" href="./app/style.css">
</head>
<body>

<header>
    <h1>Все отчеты</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Главная</a></li>
        <li><a href="get_tasks.php">Посмотреть все задачи</a></li>
        <li><a href="get_projects.php">Посмотреть все проекты</a></li>
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
$stmt = $pdo->query('SELECT * FROM ProjectReports');
?>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Текст отчета</th>
        <th>Дата создания</th>
        <th>Проект</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['Report_ID']; ?></td>
            <td><?php echo $row['Report_Text']; ?></td>
            <td><?php echo $row['Creation_Date']; ?></td>
            <td><?php echo getProjectName($pdo, $row['Project_ID']); ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- Форма для добавления нового отчета -->
<form action="add_report.php" method="post">
    <label for="project">Проект:</label>
    <select id="project" name="project" required>
        <?php
        // Выполнение запроса к таблице Projects
        $stmtProjects = $pdo->query('SELECT * FROM Projects');

        // Динамическое создание опций для каждого проекта
        while ($rowProject = $stmtProjects->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $rowProject['Project_ID'] . '">' . $rowProject['Project_Name'] . '</option>';
        }
        ?>
    </select>

    <label for="report_text">Текст отчета:</label>
    <textarea id="report_text" name="report_text" rows="4" required></textarea>

    <button type="submit">Добавить отчет</button>
</form>

<?php

function getProjectName($pdo, $projectId)
{
    $stmt = $pdo->prepare('SELECT Project_Name FROM Projects WHERE Project_ID = ?');
    $stmt->execute([$projectId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result) ? $result['Project_Name'] : 'Неизвестно';
}

?>

</body>
</html>
