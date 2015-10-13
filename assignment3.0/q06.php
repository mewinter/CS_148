<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "top.php";


//now print out each record
$columns = 3;
    $query = 'SELECT fldFirstName, fldPhone, fldSalary FROM tblTeachers WHERE fldSalary < (SELECT AVG(fldSalary) FROM tblTeachers)';
    $info2 = $thisDatabaseReader->select($query, "", 1, 0, 0, 1, false, false);
    $highlight = 0; // used to highlight alternate rows
    print '<article><p><b>Total Records: ' . count($info2) . '</b></p>';
    print '<p><b>SQL: ' . $query . '</b></p>';
    print '<p><table><th>First Name</th><th>Phone Number</th><th>Salary</th>';
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
