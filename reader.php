<?php
require __DIR__ . '/vendor/autoload.php';

use App\Classes\Students;
use App\Classes\Genetic;

if (isset($_FILES["file_to_upload"], $_POST["persons_per_group"])) {
    $excelFile       = $_FILES["file_to_upload"]["tmp_name"];
    $matrix          = [];
    $personsPerGroup = $_POST["persons_per_group"] ?? 3;
    
    if ($xlsx = \SimpleXLSX::parse($excelFile)) {
        foreach ($xlsx->rows() as $row) {
            array_push($matrix, $row);
        }
        array_shift($matrix); //Delete headers
        $studentsClass = new Students($matrix);
        $genetic       = new Genetic($studentsClass, $personsPerGroup);
    }
}