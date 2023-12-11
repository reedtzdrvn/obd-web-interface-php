<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задачи</title>
    <link rel="stylesheet" href="./app/main.css">
    <style>
        .delete-col {
            height: 158px;
        }
    </style>
</head>
<body>

<header>
    <h1>Все задачи</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Главная</a></li>
        <li><a href="get_reports.php">Посмотреть все отчеты по задачам</a></li>
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
$stmt = $stmt = $pdo->query('
    SELECT 
        t.Task_ID,
        t.Task_Name,
        t.Description,
        t.Project_ID,
        t.Deadline,
        t.Employee,
        t.Status,
        tp.Priority_Level
    FROM 
        Tasks t
    LEFT JOIN 
        TaskPriorities tp ON t.Task_ID = tp.Task_ID
');
?>

<!-- Отображение результатов в виде таблицы -->
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Название задачи</th>
        <th>Описание</th>
        <th>Проект</th>
        <th>Дедлайн</th>
        <th>Работник</th>
        <th>Статус</th>
        <th>Приоритет</th>
        <th></th>
        <th>Изменить статус</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['Task_ID']; ?></td>
            <td><?php echo $row['Task_Name']; ?></td>
            <td><?php echo $row['Description']; ?></td>
            <td><?php echo getProjectName($pdo, $row['Project_ID']); ?></td>
            <td><?php echo $row['Deadline']; ?></td>
            <td><?php echo getEmployeeName($pdo, $row['Employee']); ?></td>
            <td><?php echo getStatusName($pdo, $row['Status']); ?></td>
            <td><?php echo $row['Priority_Level'] ?></td>
            <td class="delete-col"><button class="delete-button" onclick="deleteTask(<?php echo $row['Task_ID']; ?>)"><img src="images/delete-icon.png" /></button></td>
            <td>
                <label for="change_status">Статус:</label>
                <select id="change_status_<?php echo $row['Task_ID']; ?>" name="change_status" class="TaskID" required>
                    <?php
                    // Выполнение запроса к таблице StatusTable
                    $stmtStatus = $pdo->query('SELECT * FROM StatusTable');

                    // Динамическое создание опций для каждого статуса
                    while ($rowStatus = $stmtStatus->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($rowStatus['Status_ID'] == $row['Status']) ? 'selected' : '';
                        echo '<option value="' . $rowStatus['Status_ID'] . '" ' . $selected . '>' . $rowStatus['Status_Name'] . '</option>';
                    }
                    ?>
                </select>
                <button class="change-button" type="button" onclick="changeStatus(<?php echo $row['Task_ID']; ?>)">Изменить статус</button>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- Форма для добавления задачи -->
<form action="add_task.php" method="post">
    <label for="task_name">Название задачи:</label>
    <input type="text" id="task_name" name="task_name" required>

    <label for="project_id">Проект:</label>
    <select id="project_id" name="project_id" required>
        <?php
        // Выполнение запроса к таблице Projects
        $stmtProjects = $pdo->query('SELECT * FROM Projects');

        // Динамическое создание опций для каждого проекта
        while ($rowProject = $stmtProjects->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $rowProject['Project_ID'] . '">' . $rowProject['Project_Name'] . '</option>';
        }
        ?>
    </select>

    <label for="description">Описание:</label>
    <textarea id="description" name="description" rows="4" required></textarea>

    <label for="deadline">Дедлайн:</label>
    <input type="date" id="deadline" name="deadline" required>

    <label for="employee">Имя сотрудника:</label>
    <select id="employee" name="employee" required>
        <?php
        // Выполнение запроса к таблице Projects
        $stmtProjects = $pdo->query('SELECT * FROM Employees');

        // Динамическое создание опций для каждого проекта
        while ($rowProject = $stmtProjects->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $rowProject['Employee_ID'] . '">' . $rowProject['Full_Name'] . '</option>';
        }
        ?>
    </select>

    <label for="status">Статус:</label>
    <select id="status" name="status" required>
        <option value="1">В процессе</option>
        <option value="2">Завершено</option>
    </select>

    <label for="priority">Приоритет:</label>
    <select id="priority" name="priority" required>
        <option value="Наивысший">Наивысший</option>
        <option value="Средний">Средний</option>
        <option value="Низкий">Низкий</option>
    </select>

    <button type="submit">Добавить задачу</button>
</form>

<?php
// Функция для получения имени сотрудника по Employee_ID
function getEmployeeName($pdo, $employeeId)
{
    $stmt = $pdo->prepare('SELECT Full_Name FROM Employees WHERE Employee_ID = ?');
    $stmt->execute([$employeeId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result) ? $result['Full_Name'] : 'Неизвестно';
}

// Функция для получения статуса по Status_ID
function getStatusName($pdo, $statusId)
{
    $stmt = $pdo->prepare('SELECT Status_Name FROM StatusTable WHERE Status_ID = ?');
    $stmt->execute([$statusId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result) ? $result['Status_Name'] : 'Неизвестно';
}

function getProjectName($pdo, $projectId)
{
    $stmt = $pdo->prepare('SELECT Project_Name FROM Projects WHERE Project_ID = ?');
    $stmt->execute([$projectId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($result) ? $result['Project_Name'] : 'Неизвестно';
}

?>

<script>
    // Функция для удаления задачи
    function deleteTask(taskId) {
        if (confirm('Вы уверены, что хотите удалить эту задачу?')) {
            // Выполнение AJAX-запроса для удаления задачи
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'delete_task.php?Task_ID=' + taskId, true);

            // Устанавливаем обработчик события onload
            xhr.onload = function () {
                // Проверяем успешность выполнения запроса
                if (xhr.status === 200) {
                    // Запрос успешно выполнен, теперь можно обновить страницу
                    location.reload();
                } else {
                    // В случае ошибки вы можете добавить соответствующую обработку
                    alert('Ошибка при удалении задачи. Пожалуйста, повторите попытку.');
                }
            };

            // Отправка запроса
            xhr.send();
        }
    }

    function changeStatus(taskId) {
        // Получаем новый статус (в данном случае, предполагается, что новый статус выбран из выпадающего списка)
        var newStatusElement = document.getElementById('change_status_' + taskId);
        var newStatusId = newStatusElement.options[newStatusElement.selectedIndex].value;

        // Выполнение AJAX-запроса для изменения статуса задачи
        var xhr = new XMLHttpRequest();
        xhr.open('PUT', 'change_status.php?Task_ID=' + taskId + '&newStatus=' + newStatusId, true);

        // Устанавливаем обработчик события onload
        xhr.onload = function () {
            // Проверяем успешность выполнения запроса
            if (xhr.status === 200) {
                // Запрос успешно выполнен, теперь можно обновить страницу или выполнить другие действия
                location.reload();
            } else {
                // В случае ошибки вы можете добавить соответствующую обработку
                alert('Ошибка при изменении статуса задачи. Пожалуйста, повторите попытку.');
            }
        };

        // Отправка запроса
        xhr.send();
    }

</script>
</body>
</html>
