<?php

use PHPUnit\Framework\TestCase;
use App\Classes\Students;

class StudentsTest extends TestCase
{
    const JSON_MATRIX_EXAMPLE = '[["2020-06-08 12:02:14","Vishal Quintana","Ailsa Begum","Eugene Chester","Sadie Penn","Hudson Whittaker"],
        ["2020-06-08 12:02:39","Jaspal Armitage","Duke Adam","Aubree Barr","Arlene Vance","-"],
        ["2020-06-08 12:03:18","Orson Ahmad","Aubree Barr","-","Arlene Vance","-"],
        ["2020-06-08 12:03:48","Eugene Chester","Ailsa Begum","Vishal Quintana","Isabel Lovell","Arlene Vance"],
        ["2020-06-08 12:04:28","Duke Adam","Ailsa Begum","-","Arlene Vance","-"],
        ["2020-06-08 12:05:08","Aubree Barr","Jaspal Armitage","Genevieve Kenny","Eugene Chester","Sadie Penn"],
        ["2020-06-08 12:05:43","Daphne Novak","Aubree Barr","Me es indiferente","Arlene Vance","Vishal Quintana"],
        ["2020-06-08 12:06:19","Hudson Whittaker","Isabel Lovell","Aubree Barr","Eugene Chester","Me es indiferente"],
        ["2020-06-08 12:06:47","Monet Gross","Leonidas Mcclure","-","Arlene Vance","Me es indiferente"],
        ["2020-06-08 12:07:13","Sadie Penn","Hudson Whittaker","Isabel Lovell","Arlene Vance","Me es indiferente"],
        ["2020-06-08 12:10:37","Leonidas Mcclure","Monet Gross","Jaspal Armitage","Arlene Vance","Daphne Novak"],
        ["2020-06-08 12:12:53","Isabel Lovell","Hudson Whittaker","Leonidas Mcclure","Eugene Chester","Vishal Quintana"],
        ["2020-06-08 12:13:29","Genevieve Kenny","Eugene Chester","Monet Gross","Arlene Vance","Vishal Quintana"],
        ["2020-06-08 12:14:08","Arlene Vance","Aubree Barr","Genevieve Kenny","Vishal Quintana","Jaspal Armitage"],
        ["2020-06-08 12:14:47","Ailsa Begum","Duke Adam","Eugene Chester","Aubree Barr","Arlene Vance"]]';
    
    const JSON_MATRIX_EXAMPLE_2 = '[
        ["2020-06-09 09:11:30","Orion Mills","Aleeza Espinosa"," - ","Carlie Burrows","Sarah Stewart"],
        ["2020-06-09 09:11:12","Kendal Carlson","Kelsey Smart","Declan Burns","Neive Savage","Sarah Stewart"],
        ["2020-06-09 14:02:49","Vienna Draper","Ricardo Senior","Orion Mills","Humayra Hook","Declan Burns"],
        ["2020-06-09 09:10:20","Atlas Webb","Sana Galindo","Harvie Dickens","Humayra Hook","Aleeza Espinosa"],
        ["2020-06-09 09:11:19","Ricardo Senior","Hanifa Mccarthy","Orion Mills","Sarah Stewart","Carlie Burrows"],
        ["2020-06-09 09:10:37","Sarah Stewart"," - "," - ","Hanifa Mccarthy","Carlie Burrows"],
        ["2020-06-09 09:11:08","Alesha Hatfield","Sana Galindo","Lucie Banks","Carlie Burrows"," - "],
        ["2020-06-09 09:09:33","Hanifa Mccarthy"," - "," - ","Sarah Stewart","Carlie Burrows"],
        ["2020-06-09 09:11:03","Aneurin Parkes","Aleeza Espinosa","Jamie Ray","Humayra Hook"," - "],
        ["2020-06-09 09:10:07","Aleeza Espinosa","Orion Mills","Aneurin Parkes","Carlie Burrows","Humayra Hook"],
        ["2020-06-09 09:11:11","Sana Galindo","Declan Burns","Luna Mccormick","Humayra Hook","Sarah Stewart"],
        ["2020-06-09 09:10:37","Declan Burns","Luna Mccormick","Kelsey Smart","Neive Savage"," - "],
        ["2020-06-09 09:12:27","Jamie Ray","Aleeza Espinosa","Harvie Dickens","Clarissa Guthrie","Carlie Burrows"],
        ["2020-06-09 09:11:42","Harvie Dickens","Jamie Ray","Sana Galindo"," - ","Sarah Stewart"],
        ["2020-06-09 09:11:13","Lucie Banks"," - "," - "," - "," - "],
        ["2020-06-09 09:11:48","Kelsey Smart","Luna Mccormick","Kendal Carlson","Neive Savage","Humayra Hook"],
        ["2020-06-09 09:10:17","Luna Mccormick","Kendal Carlson","Declan Burns","Neive Savage"," - "],
        ["2020-06-09 09:09:47","Neive Savage","Atlas Webb","Hanifa Mccarthy"," - "," - "],
        ["2020-06-09 09:12:08","Zayne Dejesus"," - "," - "," - "," - "],
        ["2020-07-06 07:59:09","Clarissa Guthrie"," - "," - "," - "," - "],
        ["2020-07-06 07:59:31","Darren Obrien"," - "," - "," - "," - "],
        ["2020-07-06 08:00:07","Humayra Hook"," - "," - "," - "," - "],
        ["2020-07-06 08:01:01","Carlie Burrows"," - "," - "," - "," - "]]';
    
    /** @var array $studentMatrix */
    private $studentMatrix;
    
    const MATRIX_ONE_IDX = 0;
    const MATRIX_TWO_IDX = 1;
    
    protected function setUp(): void
    {
        $this->studentMatrix = [
            self::MATRIX_ONE_IDX => json_decode(self::JSON_MATRIX_EXAMPLE, true),
            self::MATRIX_TWO_IDX => json_decode(self::JSON_MATRIX_EXAMPLE_2, true),
        ];
    }
    
    protected function tearDown(): void
    {
        $this->studentMatrix = null;
    }
    
    public function testGetStudents()
    {
        $getStudentsResults = [
            self::MATRIX_ONE_IDX => '[
                {"name":"Vishal Quintana","idxStudent":0,"preferences":{"pref1":14,"pref2":3,"depref1":9,"depref2":7},"isMatched":false},
                {"name":"Jaspal Armitage","idxStudent":1,"preferences":{"pref1":4,"pref2":5,"depref1":13,"depref2":null},"isMatched":false},
                {"name":"Orson Ahmad","idxStudent":2,"preferences":{"pref1":5,"pref2":null,"depref1":13,"depref2":null},"isMatched":false},
                {"name":"Eugene Chester","idxStudent":3,"preferences":{"pref1":14,"pref2":0,"depref1":11,"depref2":13},"isMatched":false},
                {"name":"Duke Adam","idxStudent":4,"preferences":{"pref1":14,"pref2":null,"depref1":13,"depref2":null},"isMatched":false},
                {"name":"Aubree Barr","idxStudent":5,"preferences":{"pref1":1,"pref2":12,"depref1":3,"depref2":9},"isMatched":false},
                {"name":"Daphne Novak","idxStudent":6,"preferences":{"pref1":5,"pref2":null,"depref1":13,"depref2":0},"isMatched":false},
                {"name":"Hudson Whittaker","idxStudent":7,"preferences":{"pref1":11,"pref2":5,"depref1":3,"depref2":null},"isMatched":false},
                {"name":"Monet Gross","idxStudent":8,"preferences":{"pref1":10,"pref2":null,"depref1":13,"depref2":null},"isMatched":false},
                {"name":"Sadie Penn","idxStudent":9,"preferences":{"pref1":7,"pref2":11,"depref1":13,"depref2":null},"isMatched":false},
                {"name":"Leonidas Mcclure","idxStudent":10,"preferences":{"pref1":8,"pref2":1,"depref1":13,"depref2":6},"isMatched":false},
                {"name":"Isabel Lovell","idxStudent":11,"preferences":{"pref1":7,"pref2":10,"depref1":3,"depref2":0},"isMatched":false},
                {"name":"Genevieve Kenny","idxStudent":12,"preferences":{"pref1":3,"pref2":8,"depref1":13,"depref2":0},"isMatched":false},
                {"name":"Arlene Vance","idxStudent":13,"preferences":{"pref1":5,"pref2":12,"depref1":0,"depref2":1},"isMatched":false},
                {"name":"Ailsa Begum","idxStudent":14,"preferences":{"pref1":4,"pref2":3,"depref1":5,"depref2":13},"isMatched":false}
            ]',
            self::MATRIX_TWO_IDX => '[
                {"name":"Orion Mills","idxStudent":0,"preferences":{"pref1":9,"pref2":null,"depref1":22,"depref2":5},"isMatched":false},
                {"name":"Kendal Carlson","idxStudent":1,"preferences":{"pref1":15,"pref2":11,"depref1":17,"depref2":5},"isMatched":false},
                {"name":"Vienna Draper","idxStudent":2,"preferences":{"pref1":4,"pref2":0,"depref1":21,"depref2":11},"isMatched":false},
                {"name":"Atlas Webb","idxStudent":3,"preferences":{"pref1":10,"pref2":13,"depref1":21,"depref2":9},"isMatched":false},
                {"name":"Ricardo Senior","idxStudent":4,"preferences":{"pref1":7,"pref2":0,"depref1":5,"depref2":22},"isMatched":false},
                {"name":"Sarah Stewart","idxStudent":5,"preferences":{"pref1":null,"pref2":null,"depref1":7,"depref2":22},"isMatched":false},
                {"name":"Alesha Hatfield","idxStudent":6,"preferences":{"pref1":10,"pref2":14,"depref1":22,"depref2":null},"isMatched":false},
                {"name":"Hanifa Mccarthy","idxStudent":7,"preferences":{"pref1":null,"pref2":null,"depref1":5,"depref2":22},"isMatched":false},
                {"name":"Aneurin Parkes","idxStudent":8,"preferences":{"pref1":9,"pref2":12,"depref1":21,"depref2":null},"isMatched":false},
                {"name":"Aleeza Espinosa","idxStudent":9,"preferences":{"pref1":0,"pref2":8,"depref1":22,"depref2":21},"isMatched":false},
                {"name":"Sana Galindo","idxStudent":10,"preferences":{"pref1":11,"pref2":16,"depref1":21,"depref2":5},"isMatched":false},
                {"name":"Declan Burns","idxStudent":11,"preferences":{"pref1":16,"pref2":15,"depref1":17,"depref2":null},"isMatched":false},
                {"name":"Jamie Ray","idxStudent":12,"preferences":{"pref1":9,"pref2":13,"depref1":19,"depref2":22},"isMatched":false},
                {"name":"Harvie Dickens","idxStudent":13,"preferences":{"pref1":12,"pref2":10,"depref1":null,"depref2":5},"isMatched":false},
                {"name":"Lucie Banks","idxStudent":14,"preferences":{"pref1":null,"pref2":null,"depref1":null,"depref2":null},"isMatched":false},
                {"name":"Kelsey Smart","idxStudent":15,"preferences":{"pref1":16,"pref2":1,"depref1":17,"depref2":21},"isMatched":false},
                {"name":"Luna Mccormick","idxStudent":16,"preferences":{"pref1":1,"pref2":11,"depref1":17,"depref2":null},"isMatched":false},
                {"name":"Neive Savage","idxStudent":17,"preferences":{"pref1":3,"pref2":7,"depref1":null,"depref2":null},"isMatched":false},
                {"name":"Zayne Dejesus","idxStudent":18,"preferences":{"pref1":null,"pref2":null,"depref1":null,"depref2":null},"isMatched":false},
                {"name":"Clarissa Guthrie","idxStudent":19,"preferences":{"pref1":null,"pref2":null,"depref1":null,"depref2":null},"isMatched":false},
                {"name":"Darren Obrien","idxStudent":20,"preferences":{"pref1":null,"pref2":null,"depref1":null,"depref2":null},"isMatched":false},
                {"name":"Humayra Hook","idxStudent":21,"preferences":{"pref1":null,"pref2":null,"depref1":null,"depref2":null},"isMatched":false},
                {"name":"Carlie Burrows","idxStudent":22,"preferences":{"pref1":null,"pref2":null,"depref1":null,"depref2":null},"isMatched":false}
            ]',
        ];
        
        foreach ([self::MATRIX_ONE_IDX, self::MATRIX_TWO_IDX] as $matrixIdx) {
            $studentsMatrixClass = new Students($this->studentMatrix[$matrixIdx]);
            $expectedResults     = json_decode($getStudentsResults[$matrixIdx], true);
            $this->assertEquals($expectedResults, $studentsMatrixClass->getStudents());
        }
    }
    
    public function testGetIndex()
    {
        $nameIndexArray = [
            self::MATRIX_ONE_IDX => [
                "Jaspal Armitage" => 1,
                "Monet Gross"     => 8,
                "Genevieve Kenny" => 12,
                "Ailsa Begum"     => 14,
            ],
            self::MATRIX_TWO_IDX => [
                "Vienna Draper"  => 2,
                "Jamie Ray"      => 12,
                "Humayra Hook"   => 21,
                "Carlie Burrows" => 22,
            ]
        ];
        
        foreach ([self::MATRIX_ONE_IDX, self::MATRIX_TWO_IDX] as $matrixIdx) {
            $studentsMatrixClass = new Students($this->studentMatrix[$matrixIdx]);
            foreach ($nameIndexArray[$matrixIdx] as $sampleName => $expectedIndex) {
                $this->assertEquals($expectedIndex, $studentsMatrixClass->getIndexFromStudentName($sampleName));
            }
        }
    }
}
