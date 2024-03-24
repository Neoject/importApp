<?php
//подключение к базе данных
include 'db-config.php';
//логика для экспорта данных
include 'db-export.php';
?>

<!--страница интерфейса-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- jquery -->
    <script src="assets/scripts/jquery-3.7.1.min.js"></script>
    <title>Structure</title>
</head>
<body>
    <!--форма для загрузки файлов-->
    <div class="import-form">
        <form id="import-form" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="file" name="file" id="file">
            <input type="submit" value="Add">
        </form>
    </div>
    <!-- форма для экспорта данных-->
    <div>
        <form id="exportForm" method="post" autocomplete="off">
            <button type="submit" name="depOut">Export Departments</button>
            <button type="submit" name="userOut">Export Users</button>
        </form>
    </div>
    <!-- окно для логирования-->
    <button id="open-log">Open log</button>
    <div id="logs" class="logs">
        <button id="close-log">Close log</button>
        <?php
        //загрузка данных из таблицы import_log
        $logOutput = "SELECT * FROM import_log";
        //подготовка запроса
        $stmt = $pdo->prepare($logOutput);
        //выполнение запроса
        $stmt->execute();
        //получение результата
        $result = $stmt->fetchAll();
        //вывод результата циклом по всем записям
        foreach ($result as $row) {
            echo "<p>" . $row['table_name'] . " - " . $row['file_name'] . " - " . $row['import_date'] . "</p>";
        }
        ?>
    </div>
    <!-- окно для отображения данных таблицы departments-->
    <button id="open-departments">Open departments</button>
    <div class="departments" id="departments">
        <button id="close-departments">Close departments</button>
        <h3>Departments</h3>
        <table>
            <tr>
                <th>Department ID</th>
                <th>Parent ID</th>
                <th>Name</th>
            </tr>
            <?php
            //аналогично как и с логами
            $output = "SELECT * FROM departments";
            $stmt = $pdo->prepare($output);
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row['xml_id'] . "</td>";
                echo "<td>" . $row['parent_xml_id'] . "</td>";
                echo "<td>" . $row['name_department'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <!-- окно для отображения данных таблицы users-->
    <button id="open-users">Open users</button>
    <div class="users" id="users">
        <button id="close-users">Close users</button>
        <h3>Users</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Last name</th>
                <th>Name</th>
                <th>Second name</th>
                <th>Department</th>
                <th>Work position</th>
                <th>Email</th>
                <th>Mobile phone</th>
                <th>Phone</th>
                <th>Login</th>
                <th>Password</th>
            </tr>
            <?php
            $output = "SELECT * FROM users";
            $stmt = $pdo->prepare($output);
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row['xml_id'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['second_name'] . "</td>";
                echo "<td>" . $row['department'] . "</td>";
                echo "<td>" . $row['work_position'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['mobile_phone'] . "</td>";
                echo "<td>" . $row['phone'] . "</td>";
                echo "<td>" . $row['login'] . "</td>";
                echo "<td>" . $row['password'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <script src="assets/scripts/script.js"></script>
</body>
</html>