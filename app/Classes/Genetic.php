<?php namespace App\Classes;

use App\Classes\Students;

class Genetic
{
    /** @var \App\Classes\Students $studentsClass */
    private $studentsClass;
    /** @var int $studentsPerGroup */
    private $numStudentsPerGroup;
    /** @var array $groups */
    private $groups;
    
    const SCORE_PREF_1   = 3;
    const SCORE_PREF_2   = 1;
    const SCORE_DEPREF_1 = -3;
    const SCORE_DEPREF_2 = -1;
    
    /**
     * Genetic constructor.
     * @param \App\Classes\Students $students
     * @param int                   $numStudentsPerGroup
     */
    public function __construct(\App\Classes\Students $students, int $numStudentsPerGroup)
    {
        $this->numStudentsPerGroup = intval($numStudentsPerGroup);
        $this->studentsClass       = $students;
    }
    
    public function calculate()
    {
        $initialPopulation = $this->createRandomGroup($this->studentsClass->getStudentsCacheDict());
        $score             = $this->getGroupScore($initialPopulation);
        echo "";
    }
    
    /**
     * @param array    $studentIdxArray array of elements to place into groups
     * @param int|null $studentsPerGroup if null it will select the default students per group value
     * @return array
     */
    public function createRandomGroup(array $studentIdxArray, ?int $studentsPerGroup = null): array
    {
        $studentsPerGroup = is_null($studentsPerGroup) ? $this->numStudentsPerGroup : $studentsPerGroup;
        shuffle($studentIdxArray);
        return array_chunk($studentIdxArray, $studentsPerGroup);
    }
    
    /**
     * Gets the score of the given student from the perspective of originStudentIdx
     * @param int $originStudentIdx
     * @param int $studentIdxToCheck
     * @return int
     */
    private function getScoreBetweenIdStudents(int $originStudentIdx, int $studentIdxToCheck): int
    {
        $preferences = $this->studentsClass->getStudents()[$originStudentIdx][Students::INDEX_PREFERENCES];
        return $preferences[Students::INDEX_PREFERENCES_PREFERENCE_1] === $studentIdxToCheck ? self::SCORE_PREF_1 : 0 +
        $preferences[Students::INDEX_PREFERENCES_PREFERENCE_2] === $studentIdxToCheck ? self::SCORE_PREF_2 : 0 +
        $preferences[Students::INDEX_PREFERENCES_DEPREF_1] === $studentIdxToCheck ? self::SCORE_DEPREF_1 : 0 +
        $preferences[Students::INDEX_PREFERENCES_DEPREF_2] === $studentIdxToCheck ? self::SCORE_DEPREF_2 : 0;
    }
    
    /**
     * Calculates the score of a given group
     * @param array $groups array in the format [
     *      [idxStudent1, idxStudent2, ...] ,
     *      [idxStudent3, idxStudent4, ...] ,
     *      [idxStudent5, idxStudent6, ...] ,
     * ]
     * @return int
     */
    private function getGroupScore(array $groups): int
    {
        $totalScore = 0;
        foreach ($groups as $group) {
            //The score is the sum for each of the individual scores on the $group variable
            //$group is an array that contains idStudents
            foreach ($group as $originIdStudent) {
                foreach (array_values($group) as $idStudentToCheck) {
                    if ($idStudentToCheck === $originIdStudent) {
                        $totalScore += 0;
                    } else {
                        $totalScore += $this->getScoreBetweenIdStudents($originIdStudent, $idStudentToCheck);
                    }
                }
            }
        }
        return $totalScore;
    }
    
}