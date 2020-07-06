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
        $studentsClass  = new Students($matrix);
        $genetic        = new Genetic($studentsClass, $personsPerGroup);
        $selectedGroups = $genetic->calculate();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resultado de super-armador de grupos</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="assets/pure.min.css"/>
</head>
<body>

<table class="pure-table">
    <thead>
    <tr>
        <th>Número de grupo</th>
        <th>Estudiante</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($selectedGroups as $groupKey => $group): ?>
        <?php foreach ($group as $studentIndex): ?>
            <tr>
                <td> Grupo #<?= $groupKey + 1 ?> </td>
                <td> <?= $studentsClass->getStudents()[$studentIndex][Students::INDEX_NAME] ?> </td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>