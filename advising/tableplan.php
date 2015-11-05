<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include "top.php";

//now print out each record

$query = 'SELECT `pmkPlanId`,`fldDateCreated`, `fldCatalogYear`, `fnkAdvisorNetId` , `fnkStudentNetId`,'
        . '`pmkYear`, `pmkTerm`, `fldRequirement`,'
        . '`fldDepartment`, `fldCourseNumber`, '
        . '`fldCourseName`, `fldCredits` '
        . 'FROM tblFourYearPlan '
        . ''
        . 'JOIN tblSemesterPlan on pmkPlanId = tblSemesterPlan.fnkPlanId '
        . ''
        . 'JOIN tblSemesterPlanCourses '
        . 'ON tblSemesterPlanCourses.fnkPlanId=tblFourYearPlan.pmkPlanId '
        . 'AND tblSemesterPlanCourses.fnkYear = pmkYear '
        . 'AND tblSemesterPlanCourses.fnkTerm = tblSemesterPlan.pmkTerm '
        . 'JOIN tblCourses on pmkCourseId= fnkCourseId '
        . 'WHERE pmkPlanId=1 '
        . ''
        . 'ORDER BY tblSemesterPlan.fldDisplayOrder, tblSemesterPlanCourses.fldDisplayOrder';

$info2 = $thisDatabaseReader->select($query, "", 1, 3, 0, 0, false, false);

$highlight = 0; // used to highlight alternate rows

// loop through all the tables in the database, display fields and properties
foreach ($results as $row) {

    // table name link
    print '<tr class="odd">';
    print '<th colspan="6">';
    print '<a href="?getRecordsFor=' . htmlentities($row[0], ENT_QUOTES) . "#" . htmlentities($row[0], ENT_QUOTES) . '">';
    print htmlentities($row[0], ENT_QUOTES) . '</a>';
    print '</th>';
    print '</tr>';

    //get the fields and any information about them
    $query = 'SHOW COLUMNS FROM ' . $row[0];
    $results2 = $thisDatabaseReader->select($query, "", 0, 0, 0, 0, false, false);
}    
print '<article><p><b>Total Records: ' . count($info2) . '</b></p>';
print '<p><b>SQL: ' . $query . '</b></p>';

// the array $records is both associative and indexed, column zero is associative
// which you see in teh above print_r statement
$fields = array_keys($info2[0]);
$labels = array_filter($fields, "is_string");


$columns = count($labels);
print '<table><article>';
print '<tr><th colspan="' . $columns . '">' . '</th></tr>';
// print out the column headings, note i always use a 3 letter prefix
// and camel case like pmkCustomerId and fldFirstName
print '<tr>';
foreach ($labels as $label) {
    print '<th>';
    $camelCase = preg_split('/(?=[A-Z])/', substr($label, 3));
    foreach ($camelCase as $one) {
        print $one . " ";
    }
    print '</th>';
}
print '</tr>';
//now print out each record
foreach ($info2 as $record) {
    print '<tr>';
    for ($i = 0; $i < $columns; $i++) {
        print '<td>' . $record[$i] . '</td>';
    }
    print '</tr>';
}
// all done
print '</table></article>';

?>