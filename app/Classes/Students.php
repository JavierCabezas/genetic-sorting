<?php namespace App\Classes;

/**
 * This class readers the excel file and stores the Students in an array
 * Class Students
 * @package App\Classes
 */
class Students
{
    const EXCEL_COL_INDEX_NAME     = 1;
    const EXCEL_COL_INDEX_PREF_1   = 2;
    const EXCEL_COL_INDEX_PREF_2   = 3;
    const EXCEL_COL_INDEX_NOPREF_1 = 4;
    const EXCEL_COL_INDEX_NOPREF_2 = 5;
    
    const INDEX_NAME        = 'name';
    const INDEX_IDX_STUDENT = 'idxStudent';
    const INDEX_PREFERENCES = 'preferences';
    
    const INDEX_PREFERENCES_PREFERENCE_1 = 'pref1';
    const INDEX_PREFERENCES_PREFERENCE_2 = 'pref2';
    const INDEX_PREFERENCES_DEPREF_1     = 'depref1';
    const INDEX_PREFERENCES_DEPREF_2     = 'depref2';
    
    /** @var array $students is an array with the format [
     *     'name'        => string, name of the student
     *     'idxStudent'  => int, unique identifier that is going to be used to identify this student (instead of the name)
     *     'preferences' => [
     *  // Array with the indexes of the preferences and de-preferences of the students (storing the student index in the value or
     *  // null if the user does not have a preference
     *           'pref1' => First preference (the favorite pick),
     *           'pref2' => Second preference (the second fav),
     *           'depref1' => The first de-preference (the one that the student does not one to be matched with)
     *           'depref2' => The second de-preference
     *      ],
     *     'isMatched' => bool
     *  ]
     */
    private $students;
    
    /**
     * Array in the format 'studentName' => $studentIndex
     * @var array
     */
    private $studentCacheDict = [];
    
    /**
     * Students constructor.
     * @param array $matrix
     */
    public function __construct(array $matrix)
    {
        $this->fillStudents($matrix);
        $this->fillPreferences($matrix);
        echo "";
    }
    
    /**
     * @param string $studentName
     * @return bool|mixed
     */
    public function getIndexFromStudentName(string $studentName)
    {
        return $this->studentCacheDict[$studentName] ?? null;
    }
    
    /**
     * Fills the $this->students variable with the data of all the students passed on the $matrix var
     * It also fills the $studentCacheDict variable
     * @param array $matrix
     */
    private function fillStudents(array $matrix): void
    {
        $names = array_column($matrix, self::EXCEL_COL_INDEX_NAME);
        foreach ($names as $idxStudent => $name) {
            $this->students[$idxStudent]   = [
                self::INDEX_NAME        => $name,
                self::INDEX_IDX_STUDENT => $idxStudent,
                self::INDEX_PREFERENCES => []
            ];
            $this->studentCacheDict[$name] = $idxStudent;
        }
    }
    
    /**
     * Fills the $this->students[$idxStudent]['preferences'] array
     * @param array $matrix
     */
    private function fillPreferences(array $matrix): void
    {
        $excelIndexPreferenceIndex = [
            self::EXCEL_COL_INDEX_PREF_1   => self::INDEX_PREFERENCES_PREFERENCE_1,
            self::EXCEL_COL_INDEX_PREF_2   => self::INDEX_PREFERENCES_PREFERENCE_2,
            self::EXCEL_COL_INDEX_NOPREF_1 => self::INDEX_PREFERENCES_DEPREF_1,
            self::EXCEL_COL_INDEX_NOPREF_2 => self::INDEX_PREFERENCES_DEPREF_2,
        ];
        
        foreach ($this->students as $idxStudent => $studentData) {
            foreach ($excelIndexPreferenceIndex as $excelIndex => $preferenceIndex) {
                $this->students[$idxStudent][self::INDEX_PREFERENCES][$preferenceIndex] = $this->getIndexFromStudentName($matrix[$idxStudent][$excelIndex]);
            }
        }
    }
    
    /**
     * @return array
     */
    public function getStudents() : array {
        return $this->students;
    }
    
    /**
     * @return array
     */
    public function getStudentsCacheDict() : array {
        return $this->studentCacheDict;
    }
}