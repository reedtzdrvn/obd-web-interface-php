<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Работники</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="./app/style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Работники</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="get_tasks.php">Посмотреть все задачи</a></li>
            <li><a href="get_reports.php">Посмотреть все отчеты по задачам</a></li>
            <li><a href="get_projects.php">Посмотреть все проекты</a></li>
            <li><a href="get_comments.php">Посмотреть комментарии</a></li>
        </ul>
    </nav>

    <div class="btns-container">
        <button id="toggleFormButton" onclick="toggleFormAddEmployees()">Форма для добавления работника</button>
        <button id="toggleFormButton" onclick="toggleFormShowEmployees()">Форма для просмотра работников</button>
    </div>


    <!-- Форма для добавления работника -->
    <form id="addEmployeeForm" style="display: none;">
        <label for="full_name">Фамилия Имя:</label>
        <input type="text" name="full_name" id="full_name" required>

        <label for="position">Должность:</label>
        <input type="text" name="position" id="position" required>

        <label for="department">Департамент:</label>
        <input type="text" name="department" id="department" required>

        <button type="button" onclick="addEmployee()">Добавить работника</button>
    </form>


    <!-- Форма для выбора проекта -->
    <form id="showEmployeesForm" style="display: none;" action="get_workers.php" method="get">
        <label for="project_id">Проект:</label>
        <select id="project_id" name="project_id" required>
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

            // Выполнение запроса к таблице Projects
            $stmtProjects = $pdo->query('SELECT * FROM Projects');

            // Динамическое создание опций для каждого проекта
            while ($rowProject = $stmtProjects->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $rowProject['Project_ID'] . '">' . $rowProject['Project_Name'] . '</option>';
            }
            ?>
        </select>
        <button type="button" onclick="getEmployees()">Получить работников</button>
    </form>

    <div id="resultContainer"></div>

</div>

<script>
    function getEmployees() {
        // Получение выбранного проекта
        var projectId = $('#project_id').val();

        // Выполнение AJAX-запроса
        $.ajax({
            url: 'get_workers.php',
            method: 'GET',
            data: { project_id: projectId },
            success: function(data) {
                // Добавление результатов в контейнер
                $('#resultContainer').html(data);
            },
            error: function() {
                alert('Ошибка при получении данных.');
            }
        });
    }

    function toggleFormAddEmployees() {
        // Получаем элемент формы
        var form = $('#addEmployeeForm');

        // Инвертируем свойство 'display' для отображения/скрытия формы
        form.toggle();
    }

    function toggleFormShowEmployees() {
        // Получаем элемент формы
        var form = $('#showEmployeesForm');

        // Инвертируем свойство 'display' для отображения/скрытия формы
        form.toggle();
    }

    function addEmployee() {
        var fullName = $('#full_name').val();
        var position = $('#position').val();
        var department = $('#department').val();

        // Выполнение AJAX-запроса для добавления работника
        $.ajax({
            url: 'add_employee.php',
            method: 'POST',
            data: {
                full_name: fullName,
                position: position,
                department: department
            },
            success: function(data) {
                // Очистка полей формы
                $('#full_name').val('');
                $('#position').val('');
                $('#department').val('');

                // Обновление контейнера с результатами
                $('#resultContainer').html(data);
            },
            error: function() {
                alert('Error adding employee.');
            }
        });
    }

    function deleteEmployeeFromProject(projectId, employeeId) {
        // Подтверждение удаления
        if (confirm('Вы уверены, что хотите удалить этого сотрудника из проекта?')) {
            // Создаем объект XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Формируем URL для отправки запроса
            var url = 'delete_employee.php?project_id=' + projectId + '&employee_id=' + employeeId;

            // Открываем запрос
            xhr.open('GET', url, true);

            // Устанавливаем обработчик события завершения запроса
            xhr.onload = function() {
                // Перезагружаем страницу после удаления
                location.reload();
            };

            // Отправляем запрос
            xhr.send();
        }
    }
</script>
</body>
</html>
