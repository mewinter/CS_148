<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "top.php";


//now print out each record
$columns = 4;
    $query = 'SELECT DISTINCT fldDays, fldStart, fldStop FROM tblSections JOIN tblTeachers ON tblSections.fnkTeacherNetId = tblTeachers.pmkNetId WHERE tblTeachers.pmkNetId = "jlhorton" ORDER BY tblSections.fldStart';
    $info2 = $thisDatabaseReader->select($query, "", 1, 1, 2, 0, false, false);
    $highlight = 0; // used to highlight alternate rows
    print '<article><p><b>Total Records: ' . count($info2) . '</b></p>';
    print '<p><b>SQL: ' . $query . '</b></p>';
    print '<p><table><th>Course Name</th><th>Days</th><th>Start Time</th><th>End Time</th>';
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
