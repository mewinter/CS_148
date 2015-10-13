<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "top.php";

if(isset($_GET['start'])){
        $start= (int) $_GET ["start"];
}
//now print out each record
$columns = 8;
$query = "SELECT * FROM tblStudents ORDER BY fldLastName, fldFirstName LIMIT 10, " . $start;

$info2 = $thisDatabaseReader->select($query, "", 0, 1, 0, 0, false, false);
// $info2 = $thisDatabaseReader->testquery($query, "", 0, 1, 0, 0, false, false);

$highlight = 0; // used to highlight alternate rows
$fields = array_keys($info[0]);
$labels = array_filter($fields, "is_string");

$camelCase = preg_split('/(?=[A-Z])/', substr($key, 3));

foreach ($camelCase as $one) {
    $message .= $one . " ";
}

print "<p><pre>";
print_r ($fields);
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
