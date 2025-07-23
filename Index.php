<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once './Helper.php';
require_once './connection.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$excelPath = 'C:\projects\native-pratice\excels';
$archivePath = 'C:\projects\native-pratice\excels\archive';
$filepaths = [];
$results;

$filepaths = getFiles($excelPath);

if (count($filepaths) > 0) {
    foreach ($filepaths as $excel) {
        $spreadsheet = IOFactory::load($excel);
        $sheet = $spreadsheet->getActiveSheet();
        $cleanFile = cleanExcel($sheet->toArray(), 5);

        foreach ($cleanFile as $key => $file) {
            $sql = "
        INSERT INTO 
            excel_data (serial, code, name, date) 
        VALUES 
            (?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $dateInput = trim($file[3]);

            $file[3] = null;

            if (!empty($dateInput)) {

                if (preg_match('/^[A-Za-z]+\s+\d{4}$/', $dateInput)) {
                    $dateInput = '1 ' . $dateInput;
                }

                $dt = DateTime::createFromFormat('F j, Y', $dateInput);

                if (!$dt) {
                    $dt = DateTime::createFromFormat('m/d/Y', $dateInput);
                }

                if (!$dt) {
                    $timestamp = strtotime($dateInput);
                    $dt = $timestamp ? new DateTime("@$timestamp") : false;
                }

                if ($dt) {
                    $file[3] = $dt->format('Y-m-d');
                }
            }

            $stmt->bind_param("isss", $file[0], $file[1], $file[2], $file[3]);
            $stmt->execute();
        }

        $results[] = moveExcel($excel, $archivePath);
    }
}
