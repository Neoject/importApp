<?php //файл для установления подключения к бд
//проверка на наличие файла конфигурации
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    //задается путь к конфигурации
    $configFilePath = 'config.ini';
    //сама проверка, если файл есть, то происходит переход на главную страницу
    if (file_exists($configFilePath)) {
        header('Location: index.php');
        exit;
    }
}
//функция для создания конфигурационного файла
function createConfigIni($host, $user, $pass, $db) {
    //запись данных, введенных пользователем с данными для подключения к бд
    $configData = array(
        'host' => $host,
        'user' => $user,
        'pass' => $pass,
        'db' => $db
    );

    //путь к конфигурации
    $configPath = 'config.ini';

    //проверка на его наличие
    if (!file_exists($configPath)) {
        //создание файла и запись данных в него
        $result = file_put_contents($configPath, '');
        //проверка на успешность создания файла
        if ($result !== false) {
            //запись данных в файл
            foreach ($configData as $key => $value) {
                $iniString = "$key = \"$value\"\n";//создание строки
                file_put_contents($configPath, $iniString, FILE_APPEND);//запись в файл
            }
        }
    }
}

//обработка запроса
if ($_SERVER["REQUEST_METHOD"] == "POST") {//проверка на факт запроса
    $host = $_POST['host'] ?? '';
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';
    $db = $_POST['db'] ?? '';

    //вызов функции для создания конфигурационного файла
    createConfigIni($host, $user, $pass, $db);

    //перенапрвление на главную страницу
    header('Location: index.php');
    exit;
}
?>

<!-- интерфейс для создания файла конфигурации-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/init.css">
    <title>Initialization</title>
</head>
<body>
    <div>
        <!-- форма для ввода данных и отправки их в обработчик-->
        <form class="init-form" method="post">
            <div class="btn-dbinit btn-host">
                <label class="field-name" for="host">Host:</label>
                <input class="field-input" type="text" id="host" name="host" required="required">
            </div>
            <div class="btn-dbinit btn-user">
                <label class="field-name" for="user">User:</label>
                <input class="field-input" type="text" id="user" name="user" required="required">
            </div>
            <div class="btn-dbinit btn-pass">
                <label class="field-name" for="pass">Password:</label>
                <input class="field-input" type="password" id="pass" name="pass" required="required">
            </div>
            <div class="btn-dbinit btn-db">
                <label class="field-name" for="db">Database:</label>
                <input class="field-input" type="text" id="db" name="db" required="required">
            </div>
            <div class="btn-dbinit btn-submit">
                <input type="submit" value="Create">
            </div>
        </form>
    </div>
</body>
</html>
