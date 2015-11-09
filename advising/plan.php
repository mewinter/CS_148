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

$SemesterCredits =0;
$TotalCredits =0;
$semester = '';
$Advisee = $row['fnkStudentNetId'];
$Advisor = $row['fnkAdvisorNetId'];
        
?>

<article>
    <h1>Four Year Plan </h1>
</article>
    
    <?php

foreach ($info2 as $row) {
    if ($semester != $row['pmkTerm'] . $row ['pmkYear']) {
        
        if ($semester != ''){
            print '</ol>';
            print "<p> Total Credits: " . $SemesterCredits;
            print '</section>';
        }
        //if end of academic year print closing div
        if ($semester != '' AND ( $row['pmkTerm'] == 'Fall')){
            echo "</div>". LINE_BREAK;
        }
        
        if ($row['pmkTerm']== 'Fall'){
            print "<div class - 'academicYear clearFloats'>";
        }
        print '<section class = "fourColumns ';
        print $row['pmkTerm'];
        //keep academic year together
        print '">';
        print '<h3>'. $row['pmkTerm'] . " " . $row['pmkYear'] . '</h3>';
        $semester = $row['pmkTerm'] . $row['pmkYear'];
        $SemesterCredits =0;
        
        print "<ol>";
    }
    print '<li class="' . $row['fldRequirement']. '">';
    print $row['fldDepartment']. " " . $row['fldCourseNumber'];
    print '</li>' . LINE_BREAK;
    $semesterCredits = $semesterCredits + $row['fldCredits']; 
            
//    //check for new term
//    print '</ol></div>';
//    print"<div><h3>";
//    if ($Semester != $row['pmkTerm']) {
//        print $row["pmkTerm"] . ' ' . $row["pmkYear"];
//        print"</h3>";
//        $TotalCredits += $row["fldCredits"];
//        $TotalCreditsSem += $row["fldCredits"];
//        print 'total credits: ' . $TotalCredits . ' total semester credits: ' . $TotalCreditsSem;
//        print '<ol><li>' . $row['fldDepartment'] . ' ' . $row ['fldCourseNumber'] . ' ' . $row['fldCredits'] . '</li>';
//    }
    //$Semester = 0;
}

//print '<p><table><th>Courses</th>';



include "footer.php";
?>
