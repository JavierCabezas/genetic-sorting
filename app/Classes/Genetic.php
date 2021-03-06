<?php namespace App\Classes;

use App\Classes\Students;

/**
 * This class calculates the score of the given student group with a sort-of genetic algorithm
 * Class Genetic
 * @package App\Classes
 */
class Genetic
{
    /** @var \App\Classes\Students $studentsClass */
    private $studentsClass;
    /** @var int $studentsPerGroup */
    private $numStudentsPerGroup;
    /** @var array $groups */
    private $groups;
    /** @var int $currentScore */
    private $currentScore;
    /** @var int $candidateSwitches */
    public $candidateSwitches;
    
    const SCORE_PREF_1   = 3;
    const SCORE_PREF_2   = 2;
    const SCORE_DEPREF_1 = -6;
    const SCORE_DEPREF_2 = -4;
    
    const NUMBER_OF_LOOPS = 50000;
    
    const INDEX_SCORE_ARRAY = [
        Students::INDEX_PREFERENCES_PREFERENCE_1 => self::SCORE_PREF_1,
        Students::INDEX_PREFERENCES_PREFERENCE_2 => self::SCORE_PREF_2,
        Students::INDEX_PREFERENCES_DEPREF_1     => self::SCORE_DEPREF_1,
        Students::INDEX_PREFERENCES_DEPREF_2     => self::SCORE_DEPREF_2,
    ];
    
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
    
    /**
     * This class loops and creates new student groups and selects the best one
     * @return array
     */
    public function calculate(): array
    {
        $studentIdxArray         = array_values($this->studentsClass->getStudentsCacheDict());
        $initialPopulation       = $this->createRandomGroup($studentIdxArray);
        $populationSize          = count($studentIdxArray);
        $this->groups            = $initialPopulation;
        $this->currentScore      = $this->getGroupsScore($initialPopulation);
        $this->candidateSwitches = 0;
        
        for ($loopNumber = 0; $loopNumber < self::NUMBER_OF_LOOPS; $loopNumber += 1) {
            $candidateGroup = $this->createGroupByCrossingOver($this->groups, $populationSize, $this->numStudentsPerGroup);
            $candidateScore = $this->getGroupsScore($candidateGroup);
            $shouldSwitch   = false;
            if ($candidateScore > $this->currentScore) {
                $shouldSwitch = true;
            } elseif ($candidateScore === $this->currentScore) {
                //If the scores are the same, check if the candidate group is better by other criteria
                
                //If the candidate group has a lower standard deviation than the current group then chose that one
                // (becase we don't want to have a group with high score and other one with way lower score)
                $currentStd   = $this->getGroupStandardDeviation($this->groups);
                $candidateStd = $this->getGroupStandardDeviation($candidateGroup);
                $shouldSwitch = $candidateStd < $currentStd;
            }
            
            if ($shouldSwitch) {
                $this->groups            = $candidateGroup;
                $this->currentScore      = $candidateScore;
                $this->candidateSwitches += 1;
            }
        }
        
        return $this->groups;
    }
    
    /**
     * Flips some elements of the student group and returns a new group
     * @param array $groupToCrossover
     * @param int   $numberOfElements
     * @param int   $groupSize
     * @return array
     */
    private function createGroupByCrossingOver(array $groupToCrossover, int $numberOfElements, int $groupSize): array
    {
        $numberOfFlips = mt_rand(1, $numberOfElements);
        for ($i = 0; $i < $numberOfFlips; $i++) {
            $firstIdxStudentToFlip   = mt_rand(1, $numberOfElements);
            $secondIdxStudentToFlip  = mt_rand(1, $numberOfElements);
            $firstElementGroupIdx    = ceil($firstIdxStudentToFlip / $groupSize) - 1;
            $secondElementGroupIdx   = ceil($secondIdxStudentToFlip / $groupSize) - 1;
            $firstGroupElementIndex  = mt_rand(0, count($groupToCrossover[$firstElementGroupIdx]) - 1);
            $secondGroupElementIndex = mt_rand(0, count($groupToCrossover[$secondElementGroupIdx]) - 1);
            
            $temp                                                               = $groupToCrossover[$firstElementGroupIdx][$firstGroupElementIndex];
            $groupToCrossover[$firstElementGroupIdx][$firstGroupElementIndex]   = $groupToCrossover[$secondElementGroupIdx][$secondGroupElementIndex];
            $groupToCrossover[$secondElementGroupIdx][$secondGroupElementIndex] = $temp;
        }
        return $groupToCrossover;
    }
    
    /**
     * Generates a random student group
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
        $score       = 0;
        foreach (self::INDEX_SCORE_ARRAY as $studentPreferenceIndex => $givenScore) {
            $score += $preferences[$studentPreferenceIndex] === $studentIdxToCheck ? $givenScore : 0;
        }
        return $score;
    }
    
    /**
     * @param array $subGroup
     * @return int
     */
    public function getSubGroupScore(array $subGroup): int
    {
        $totalScore = 0;
        //The score is the sum for each of the individual scores on the $group variable
        //$group is an array that contains idStudents
        foreach ($subGroup as $originIdStudent) {
            foreach (array_values($subGroup) as $idStudentToCheck) {
                if ($idStudentToCheck === $originIdStudent) {
                    $totalScore += 0;
                } else {
                    $totalScore += $this->getScoreBetweenIdStudents($originIdStudent, $idStudentToCheck);
                }
            }
        }
        
        return $totalScore;
    }
    
    /**
     * Calculates the score of a given group.
     * The algorithm chekcs out the students preferences and adds a positive score by each met preference and
     * substracts a score by each de-preference.
     * @param array $groups array in the format [
     *      [idxStudent1, idxStudent2, ...] ,
     *      [idxStudent3, idxStudent4, ...] ,
     *      [idxStudent5, idxStudent6, ...] ,
     * ]
     * @return int
     */
    public function getGroupsScore(array $groups): int
    {
        $totalScore = 0;
        foreach ($groups as $group) {
            $totalScore += $this->getSubGroupScore($group);
        }
        return $totalScore;
    }
    
    /**
     * @param array $groups
     * @return float
     */
    public function getGroupStandardDeviation(array $groups): float
    {
        $groupsScores = array_map(function (array $group) {
            return $this->getSubGroupScore($group);
        }, $groups);
        
        $num_of_elements = count($groupsScores);
        $variance        = 0.0;
        $average         = array_sum($groupsScores) / $num_of_elements;
        foreach ($groupsScores as $i) {
            $variance += pow(($i - $average), 2);
        }
        return (float)sqrt($variance / $num_of_elements);
    }
    
    /**
     * @param array $studentArray
     * @param int   $idOriginStudent
     * @return int
     */
    public function getScoreFromStudentPerspective(array $studentArray, int $idOriginStudent): int
    {
        $totalScore = 0;
        foreach ($studentArray as $studentIdx) {
            if ($idOriginStudent !== $studentIdx) {
                $totalScore += $this->getScoreBetweenIdStudents($idOriginStudent, $studentIdx);
            }
        }
        return $totalScore;
    }
}