<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "top.php";


//now print out each record
$columns = 12;
    $query = 'SELECT * FROM tblSections WHERE time(fldStart) = time("13:10:00") AND fldBuilding like "KALKIN%"';
    $info2 = $thisDatabaseReader->select($query, "", 1, 1, 4, 0, false, false);
    $highlight = 0; // used to highlight alternate rows
    print '<p><b>Total Records: ' . count($info2) . '</b></p>';
    print '<p><b>SQL: ' . $query . '</b></p>';
    print '<table>';
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
    print '</table>';
include "footer.php";
?>
