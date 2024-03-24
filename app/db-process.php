<?php //файл для обработки загруженных файлов
include 'db-config.php'; //файл подключения к бд
//путь к конфигурации

try {
    //проверка формы
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
        //получение данных из формы
        $tmpFile = $_FILES['file']['tmp_name'];
        //получение имя файла
        $fileInfo = pathinfo($_FILES['file']['name']);
        //проверка расширения
        $extension = strtolower($fileInfo['extension']);
        if ($extension !== 'csv') {
            echo "Unsupported file";
            exit;
        }
        //код ниже считывает количество столбцов в файле данных и в зависимости от их количества выбирает в какую таблицу их загрузить
        $file = fopen($tmpFile, 'r');//только чтение
        $numHeaders = count(fgetcsv($file, 0, ';'));//число столбцов
        fclose($file);//закрыть файла
        $tableName = checkCsv($numHeaders);//выбирает в какую таблицу загрузить
        //сам импорт
        try {
            $db = new DB($pdo);//создание объекта бд
            $db->importData($tableName, $tmpFile);//импорт
            logFiles($pdo, 'import', $tableName, $fileInfo['basename']);//логирование
        } catch (Exception $e) {
            echo "Error:" . $e->getMessage();
        }
    }
} catch (PDOException $e) {
    echo "Error:" . $e->getMessage();
}
