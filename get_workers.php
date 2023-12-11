<?php
// Подключение к базе данных (замените параметры подключения на свои)
$host = 'sql11.freemysqlhosting.net';
$dbname = 'sql11668236';
$username = 'sql11668236';
$password = '1jjFpaJ7Vj';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Ошибка подключения к базе данных: ' . $e->getMessage();
    exit;
}

// Получение идентификатора проекта из GET-параметра
$projectId = isset($_GET['project_id']) ? $_GET['project_id'] : null;

if ($projectId !== null) {
    // SQL-запрос с INNER JOIN и фильтрацией по project_id
    $sql = "
        SELECT
            E.Full_Name,
            E.Position,
            E.Department,
            E.Employee_ID,
            EA.Role_In_Project
        FROM
            Employees E
        JOIN
            EmployeeAssignments EA ON E.Employee_ID = EA.Employee_ID
        WHERE
            EA.Project_ID = :project_id
    ";

    // Подготовка запроса
    $stmt = $db->prepare($sql);

    // Передача параметра
    $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);

    // Выполнение запроса
    $stmt->execute();

    // Получение результатов
    $employeeAssignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Вывод результатов в виде HTML-таблицы
    echo '<h2 class="emp-header">Работники, принадлежащие Проекту ' . $projectId . '</h2>';
    echo '<table>';
    echo '<tr><th>Фамилия Имя</th><th>Позиция</th><th>Департамент</th><th>Роль в проекте</th><th>Действие</th></tr>';
    foreach ($employeeAssignments as $assignment) {
        echo '<tr>';
        echo '<td>' . $assignment['Full_Name'] . '</td>';
        echo '<td>' . $assignment['Position'] . '</td>';
        echo '<td>' . $assignment['Department'] . '</td>';
        echo '<td>' . $assignment['Role_In_Project'] . '</td>';
        echo '<td class="delete-col"><button class="delete-button" onclick="deleteEmployeeFromProject(' . $projectId . ', ' . $assignment['Employee_ID'] . ')"><img src="./images/delete-icon.png" alt="Удалить"></button></td>';
        echo '</tr>';
    }
    echo '</table';
} else {
    echo '<p>Пожалуйста выберите проект, чтобы получить работников.</p>';
}
?>
