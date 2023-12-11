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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_POST['project_id'];
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $employee = $_POST['employee'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];

    $stmt = $pdo->prepare('INSERT INTO Tasks (Project_ID, Task_Name, Description, Deadline, Employee, Status) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$projectId, $task_name, $description, $deadline, $employee, $status]);

    $taskId = $pdo->lastInsertId();

    // Вставка новой записи в таблицу TaskPriorities
    $stmt = $pdo->prepare('INSERT INTO TaskPriorities (Task_ID, Priority_Level) VALUES (?, ?)');
    $stmt->execute([$taskId, $priority]);

    // Проверка наличия записи с соответствующим работником
    if (!isEmployeeAssignmentExists($pdo, $projectId, $employee)) {
        // Получение роли сотрудника
        $roleInProject = getPositionRole($pdo, $employee);

        // Вставка новой записи в таблицу EmployeeAssignments
        $stmt = $pdo->prepare('INSERT INTO EmployeeAssignments (Employee_ID, Project_ID, Role_In_Project) VALUES (?, ?, ?)');
        $stmt->execute([$employee, $projectId, $roleInProject]);
    }
}

// Функция для проверки наличия записи с соответствующим работником
function isEmployeeAssignmentExists($pdo, $projectId, $employeeId) {
    $stmt = $pdo->prepare('SELECT * FROM EmployeeAssignments WHERE Project_ID = ? AND Employee_ID = ?');
    $stmt->execute([$projectId, $employeeId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

function getPositionRole($pdo, $employeeId) {
    // SQL-запрос для получения должности сотрудника
    $sql = "SELECT Position FROM Employees WHERE Employee_ID = ?";

    // Подготовка запроса
    $stmt = $pdo->prepare($sql);

    // Выполнение запроса
    $stmt->execute([$employeeId]);

    // Получение результата
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Возвращение роли на основе должности
    return isset($result['Position']) ? $result['Position'] : 'Unknown Role';
}

header("Location: get_tasks.php");
?>
