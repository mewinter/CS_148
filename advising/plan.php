<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "top.php";


//now print out each record
$columns = 10;
    $query = 'SELECT `pmkPlanId`, `fnkStudentNetId`,`fldDateCreated`, `fnkAdvisorNetId`, `pmkYear`, `pmkTerm`, `fnkCourseId`,`fldDepartment`, `fldCourseNumber`, `fldCourseName` FROM tblFourYearPlan JOIN tblSemesterPlan on pmkPlanId = tblSemesterPlan.fnkPlanId JOIN tblSemesterPlanCourses ON tblSemesterPlanCourses.fnkPlanId=tblFourYearPlan.pmkPlanId AND tblSemesterPlanCourses.fnkYear = pmkYear AND tblSemesterPlanCourses.fnkTerm = tblSemesterPlan.pmkTerm JOIN tblCourses on pmkCourseId= fnkCourseId WHERE pmkPlanId=1 ORDER BY tblSemesterPlan.fldDisplayOrder, tblSemesterPlanCourses.fldDisplayOrder';
    $info2 = $thisDatabaseReader->select($query, "", 1, 3, 0, 0, false, false);
    $highlight = 0; // used to highlight alternate rows
    print '<article><p><b>Total Records: ' . count($info2) . '</b></p>';
    print '<p><b>SQL: ' . $query . '</b></p>';
    
    $TotalCreditsSem =0;
    $TotalCredits =0;
    $Semester = 0;
    
    foreach($plan as $row){
        //check for new term
        print"<h3>";
        if ($Semester =0)
            print $row["pmkTerm"]. ' '. $row["pmkYear"];
            $Semester = 1;
        $Semester=0;
        print"</h3>";
    }
    
//    print '<p><table><th>Courses</th>';
//    foreach ($info2 as $rec) {
//        $highlight++;
//        if ($highlight % 2 != 0) {
//            $style = ' odd ';
//        } else {
//            $style = ' even ';
//        }
//        print '<tr class="' . $style . '">';
//        for ($i = 0; $i < $columns; $i++) {
//            print '<td>' . $rec[$i] . '</td>';
//        }
//        print '</tr>';
//    }
//    // all done
//    print '</table></article>';
include "footer.php";
?>
