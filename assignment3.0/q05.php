<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "top.php";

//now print out each record
$columns = 3;
$query = 'SELECT tblTeachers.fldFirstName, tblTeachers.fldLastName,  count(tblStudents.fldFirstName) as total 
    FROM tblSections JOIN tblEnrolls on tblSections.fldCRN  = tblEnrolls.`fnkSectionId` 
    JOIN tblStudents on pmkStudentId = fnkStudentId
    JOIN tblTeachers on tblSections.fnkTeacherNetId=pmkNetId
WHERE fldType != "LAB"
group by fnkTeacherNetId
ORDER BY total desc';

$info2 = $thisDatabaseReader->select($query, "", 0, 1, 2, 0, false, false);
$highlight = 0; // used to highlight alternate rows
print '<article><p><b>Total Records: ' . count($info2) . '</b></p>';
print '<p><b>SQL: ' . $query . '</b></p>';
print '<p><table><th>First Name</th><th>Last Name</th><th>Total</th>';
foreach ($info2 as $rec) {
    $highlight++;
    if ($highlight % 2 != 0) {
        $style = ' odd ';
    } else {
        $style = ' even ';
    }
    print '<tr class="' . $style . '">';
    for ($i = 0; $i < $columns; $i++) {
        print '<td>' . $rec[$i] . '</td>';
    }
    print '</tr>';
}
// all done
print '</table></article>';
include "footer.php";
?>
