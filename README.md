# genetic-sorting
This is a small project in where I'll use a (sort of) genetic algorithm to create study-groups of a selectable size based on the students preference. 

It reads an Excel file which contains the student list and their preference of who you would you prefer to work with and who would you not prefer to work with.

The columns have the following format
- B: Student Name 
- C: First option of student that you would like to work with 
- D: Second option of student that you would like to work with 
- E: First option of the student that you would not like to work with
- F: Second option of the student that you would not like to work with

The output of the algorithm is creating N sub-groups of students of a specific size given by the user in a form.

The "score" is calculated by assigning a value to each of the "work with" options (positive for the ones that you would like to and negative for the ones you don't).
Then the score values are:
 - For each student, if in your sub-group there is one of your "work with" options then that respective score is added up. Otherwise it is 0 for each other member.
 - For each sub-group the value is the sum of all the students in that group.
 - The solution score is the sum of the scores of all the selected sub-groups.
 
 
The algorithm works by:
1) Randomly creating a group of students.
2) It randomly swaps a random number of students. 
3) If the score of the randomly created group is better than the current group it changes the current group with the newly created one. 
If the score is the same then the standard deviation of each of the student sub-groups is calculated. If this value is lower than the one of the current solution,
then this solution is considered greater and is swapped. The idea behind the standard deviation is that, even if the score is the same, having groups with similar 
score is preferable before having groups with a great dispersion of scores.
4) This is looped N times.

TO-DOs:
 * Actually implement the tests (they are a WIP)
 * Improve the code legibility and comments
 * Translate everything to english (there is some spanish somewhere)
 * (and the most important one) Make the software more flexible, so it can read n preferences and de-preferences
 from the Excel file.

There are some example excel files in the "test" folder. They can be uploaded in http://armador-grupos.herokuapp.com/ to check the results.