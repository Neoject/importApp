<?php //файл для использования бд в запросах
//путь к конфигурации
$configFile = 'config.ini';

//парсинг конфигурации
$config = parse_ini_file($configFile);

//объект для создания новой базы данных
class DB {
    //приватный параметр для подключения к бд
    private $pdo;
    //конструктор для создания объекта
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    //метод для импорта данных из файла
    public function importData($tableName, $fileName) {
        try {
            $sql = "LOAD DATA INFILE ? INTO TABLE $tableName FIELDS TERMINATED BY ';' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$fileName]);
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        } catch (PDOException $e) {
            throw new Exception("Failed:" . $e->getMessage());
        }
    }
}

//проверка полученного файла перед отправкой в обработчик
function checkCsv($numHeaders) {
    switch ($numHeaders) {//кейсы в зависимости от типа файла
        case 3://если 3 столбца
            return 'departments';
        case 11://если 11
            return 'users';
        default://принимаются только файлы по шаблону из задания
            throw new Exception("Unsupported file");
    }
}

//подключение к бд
try {
    //проверка на наличие файла конфигурации
    if (!file_exists('config.ini')) {
        header('Location: db-init.php');//если его нет, то перебрасывает на страницу инициализации
        exit;
    }

    //чтение данных конфигурации
    $dbHost = $config['host'];
    $dbUser = $config['user'];
    $dbPass = $config['pass'];
    $dbName = $config['db'];

    //новое подключение к бд
    $pdo = new PDO("mysql:host=$dbHost", $dbUser, $dbPass);
    //установка параметров подключения
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //если бд нет, то она создается по шаблону
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName;");
    $pdo->exec("USE $dbName;");
    
    //если таблиц нет, то они создаются по шаблону, id устанавливается как главный ключ
    $pdo->exec("CREATE TABLE IF NOT EXISTS departments (
        `xml_id` VARCHAR(5) NOT NULL PRIMARY KEY,
        `parent_xml_id` VARCHAR(5),
        `name_department` VARCHAR(20) NOT NULL
    )");
    //создается индекс для столбца с департаментами для привязки к первой таблице, в данной программе не используется, но так логично
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        `xml_id` VARCHAR(5) NOT NULL PRIMARY KEY,
        `last_name` VARCHAR(20) NOT NULL,
        `name` VARCHAR(20) NOT NULL,
        `second_name` VARCHAR(20) NOT NULL,
        `department` VARCHAR(5) NOT NULL,
        `work_position` VARCHAR(20) NOT NULL,
        `email` VARCHAR(50) NOT NULL,
        `mobile_phone` VARCHAR(20),
        `phone` VARCHAR(20),
        `login` VARCHAR(20) NOT NULL,
        `password` VARCHAR(20) NOT NULL,
        CONSTRAINT `fk_department` FOREIGN KEY (`department`) REFERENCES departments(`xml_id`) ON DELETE NO ACTION ON UPDATE CASCADE
    )");

    //таблица для логов
    $pdo->exec("CREATE TABLE IF NOT EXISTS import_log (
        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `table_name` VARCHAR(20) NOT NULL,
        `file_name` VARCHAR(20) NOT NULL,
        `import_date` DATETIME NOT NULL
    )");
} catch (PDOException $e) {//вывод ошибок в бд
    echo "Error: " . $e->getMessage();
}

//функция для записи в таблицу логов
function logFiles($pdo, $action, $tableName, $fileName) {
    try {
        //подготовка запроса
        $stmt = $pdo->prepare("INSERT INTO import_log (table_name, file_name, import_date) VALUES (?, ?, NOW())");
        $stmt->execute([$tableName, $fileName]);
        echo "File import logged successfully.";
    } catch (PDOException $e) {
        throw new Exception("Error logging file import: " . $e->getMessage());
    }
}