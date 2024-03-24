<?php //файл для экспорта данных их бд в xlsx
//включаем файл для подключения к бд
include_once('db-config.php');
//подключение composer для закгрузки phpoffice, а имнно phpspreadsheet
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;//загрузка phpspreadsheet
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;//загрузка xlsx

//функция для экспорта данных из таблицы
function exportXlsxDepartments($pdo) {
    //создание новой таблицы
    $spreadsheet = new Spreadsheet();
    //загрузка таблицы
    $sql = "SELECT * FROM departments";
    //парсинг результата
    $result = $pdo->query($sql);
    $sheet = $spreadsheet->getActiveSheet();
    //задаем собственные названия столбцов, ниже в функции столбцы из sql обрежутся
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Parent ID');
    $sheet->setCellValue('C1', 'Name');
    
    //если результат не пустой
    if ($result->rowCount() > 0) {
        //начинаем со второй строки, чтобы не писать названия столбцов из sql
        $row = 2;
        //цикл для записи в таблицу
        foreach ($result as $rowData) {
            $sheet->setCellValue('A' . $row, $rowData['xml_id']);
            $sheet->setCellValue('B' . $row, $rowData['parent_xml_id']);
            $sheet->setCellValue('C' . $row, $rowData['name_department']);
            $row++;
        }
        //создание файла
        $writer = new Xlsx($spreadsheet);
        //создание временного файла
        $filename = tempnam(sys_get_temp_dir(), 'xlsx');
        //запись в файл
        $writer->save($filename);
        
        //отправка файла клиенту
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//сообщение о типе файла
        header('Content-Disposition: attachment; filename="exported_departments.xlsx"');//сообщение о имени файла
        header('Content-Length: ' . filesize($filename));//сообщение о размере файла
        readfile($filename);//запись файла в клиент
    }
}

//такая же функция, но для второй таблицы
function exportXlsxUsers($pdo) {
    $spreadsheet = new Spreadsheet();
    $sql = "SELECT * FROM users";
    $result = $pdo->query($sql);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Last name');
    $sheet->setCellValue('C1', 'Name');
    $sheet->setCellValue('D1', 'Second name');
    $sheet->setCellValue('E1', 'Department');   
    $sheet->setCellValue('F1', 'Work position');
    $sheet->setCellValue('G1', 'Email');
    $sheet->setCellValue('H1', 'Mobile phone');
    $sheet->setCellValue('I1', 'Phone');
    $sheet->setCellValue('J1', 'Login');
    $sheet->setCellValue('K1', 'Password');

    if ($result->rowCount() > 0) {
        $row = 2;
        foreach ($result as $rowData) {
            $sheet->setCellValue('A' . $row, $rowData['xml_id']);
            $sheet->setCellValue('B' . $row, $rowData['last_name']);
            $sheet->setCellValue('C' . $row, $rowData['name']);
            $sheet->setCellValue('D' . $row, $rowData['second_name']);
            $sheet->setCellValue('E' . $row, $rowData['department']);
            $sheet->setCellValue('F' . $row, $rowData['work_position']);
            $sheet->setCellValue('G' . $row, $rowData['email']);
            $sheet->setCellValue('H' . $row, $rowData['mobile_phone']);
            $sheet->setCellValue('I' . $row, $rowData['phone']);
            $sheet->setCellValue('J' . $row, $rowData['login']);
            $sheet->setCellValue('K' . $row, $rowData['password']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = tempnam(sys_get_temp_dir(), 'xlsx');
        $writer->save($filename);
        
        // Отправка файла клиенту
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="exported_users.xlsx"');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
        return $filename;
    }
}

//сам экспорт
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['depOut'])) {
    exportXlsxDepartments($pdo);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userOut'])) {
    exportXlsxUsers($pdo);
}
