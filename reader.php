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
<div class="pure-g">
    <div class="pure-u-5-5">
        <h2>Datos de entrada:</h2>

        <ul>
            <li><b>Personas por grupo: </b> <?= $personsPerGroup ?> </li>
            <li><b>Número de personas: </b> <?= count($studentsClass->getStudentsCacheDict()) ?> </li>
            <li><b>Número de grupos: </b> <?= ceil(count($studentsClass->getStudentsCacheDict()) / $personsPerGroup) ?> </li>
        </ul>

        <table class="pure-table pure-table-bordered pure-table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Primera preferencia</th>
                <th>Segunda preferencia</th>
                <th>Primera de-preferencia</th>
                <th>Segunda de-preferencia</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($matrix as $rowIdx => $row): ?>
                <tr>
                    <td><?= ($rowIdx + 1) ?></td>
                    <td><?= $row[Students::EXCEL_COL_INDEX_NAME] ?> </td>
                    <td><?= $row[Students::EXCEL_COL_INDEX_PREF_1] ?> </td>
                    <td><?= $row[Students::EXCEL_COL_INDEX_PREF_2] ?> </td>
                    <td><?= $row[Students::EXCEL_COL_INDEX_NOPREF_1] ?> </td>
                    <td><?= $row[Students::EXCEL_COL_INDEX_NOPREF_2] ?> </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<p>&nbsp;</p>
<p>&nbsp;</p>

<h1>Datos de salida:</h1>

<h3> Puntaje solución: <b><?= $genetic->getGroupsScore($selectedGroups) ?> </b></h3>

<ul>
    <li> Desviación estándar solución: <b> <?= round($genetic->getGroupStandardDeviation($selectedGroups), 2) ?></b></li>
    <li> Número de loops: <b> <?= Genetic::NUMBER_OF_LOOPS ?></b></li>
    <li> Mejoras hechas: <b> <?= $genetic->candidateSwitches ?></b></li>
    <li> Puntaje preferencia 1: <b> <?= Genetic::SCORE_PREF_1 ?></b></li>
    <li> Puntaje preferencia 2: <b> <?= Genetic::SCORE_PREF_2 ?></b></li>
    <li> Puntaje de-preferencia 1: <b> <?= Genetic::SCORE_DEPREF_1 ?></b></li>
    <li> Puntaje de-preferencia 2: <b> <?= Genetic::SCORE_DEPREF_2 ?></b></li>
</ul>

<div class="pure-g">
    <?php foreach ($selectedGroups as $groupKey => $group): ?>
        <div class="pure-u-1-3">
            <h2>Grupo <?= ($groupKey + 1) ?> </h2>
            <table class="pure-table pure-table-bordered pure-table-striped">
                <thead>
                <tr>
                    <th>Persona</th>
                    <th>Puntaje</th>
                </tr>
                <tbody>
                <?php foreach ($group as $studentIndex): ?>
                    <tr>
                        <td><?= $studentsClass->getStudents()[$studentIndex][Students::INDEX_NAME] ?></td>
                        <td><?= $genetic->getScoreFromStudentPerspective($group, $studentIndex) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p><b>Puntaje grupo:</b> <?= $genetic->getSubGroupScore($group) ?> </p>

        </div>
    <?php endforeach; ?>
</div>

</body>
</html>