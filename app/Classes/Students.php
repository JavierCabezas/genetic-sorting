<?php namespace App\Classes;

/**
 * This class readers the excel file and stores the Students in an array
 * Class Students
 * @package App\Classes
 */
class Students
{
    const INDEX_NAME     = 1;
    const INDEX_PREF_1   = 2;
    const INDEX_PREF_2   = 3;
    const INDEX_NOPREF_1 = 4;
    const INDEX_NOPREF_2 = 5;
    
    const SCORE_PREF_1   = 3;
    const SCORE_PREF_2   = 2;
    const SCORE_DEPREF_1 = -3;
    const SCORE_DEPREF_2 = -2;
    
    /** @var array $students */
    private $students;
    /** @var array $matrix */
    private $matrix;
    /** @var int $studentsPerGroup */
    private $numStudentsPerGroup;
    
    /**
     * Array in the format 'studentName' => $studentIndex
     * @var array
     */
    private $studentCacheDict = [];
    
    public function __construct(array $matrix, int $numStudentsPerGroup)
    {
        $this->matrix              = $matrix;
        $this->numStudentsPerGroup = intval($numStudentsPerGroup);
        $names                     = array_column($matrix, self::INDEX_NAME);
        foreach ($names as $idxStudent => $name) {
            $this->students[$idxStudent]   = [
                'score'      => 0,
                'name'       => $name,
                'idxStudent' => $idxStudent,
                'isMatched'  => false,
            ];
            $this->studentCacheDict[$name] = $idxStudent;
        }
        
        $this->calculateNegativeScores();
    }
    
    /**
     * @param string $studentName
     * @return bool|mixed
     */
    private function getIndexFromStudentName(string $studentName)
    {
        return $this->studentCacheDict[$studentName] ?? false;
    }
    
    private function calculateNegativeScores()
    {
        $colNegativeScores = [
            self::INDEX_NOPREF_1 => self::SCORE_DEPREF_1,
            self::INDEX_NOPREF_2 => self::SCORE_DEPREF_2,
        ];
        
        foreach ($colNegativeScores as $columnIndex => $score) {
            foreach (array_column($this->matrix, $columnIndex) as $studentName) {
                $idxStudent = $this->getIndexFromStudentName($studentName);
                if ($idxStudent) {
                    $this->students[$idxStudent]['score'] += $score;
                }
            }
        }
        
        function sortScore($a, $b)
        {
            return $a['score'] < $b['score'] ? -1 : $a['score'] == $b['score'] ? 0 : 1;
        }
        
        usort($this->students, 'sortScore');
    }
}