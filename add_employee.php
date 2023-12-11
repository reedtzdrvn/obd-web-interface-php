<?php
// Проверяем, что запрос выполнен методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Получение данных из POST-запроса
    $fullName = isset($_POST['full_name']) ? $_POST['full_name'] : '';
    $position = isset($_POST['position']) ? $_POST['position'] : '';
    $department = isset($_POST['department']) ? $_POST['department'] : '';

    // SQL-запрос для добавления работника в базу данных
    $sql = "
        INSERT INTO Employees (Full_Name, Position, Department)
        VALUES (:full_name, :position, :department)
    ";

    // Подготовка запроса
    $stmt = $db->prepare($sql);

    // Привязка параметров
    $stmt->bindParam(':full_name', $fullName, PDO::PARAM_STR);
    $stmt->bindParam(':position', $position, PDO::PARAM_STR);
    $stmt->bindParam(':department', $department, PDO::PARAM_STR);

    // Выполнение запроса
    if ($stmt->execute()) {
        echo '<p>Employee added successfully.</p>';
    } else {
        echo '<p>Error adding employee.</p>';
    }
} else {
    // Вывод сообщения об ошибке, если запрос не методом POST
    echo '<p>Invalid request method. Please use POST.</p>';
}
?>
