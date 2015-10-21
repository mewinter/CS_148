<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include "top.php";

if (isset($_GET['start'])) {
    $start = (int) $_GET ["start"];
}else{
    $start = 0;
}

$query = "SELECT * FROM tblStudents ORDER BY fldLastName, fldFirstName LIMIT 10 OFFSET ".$start;

$info2 = $thisDatabaseReader->select($query, "", 0, 1, 0, 0, false, false);
//$info2 = $thisDatabaseReader->testquery($query, "", 0, 1, 0, 0, false, false);


$highlight = 0; // used to highlight alternate rows

print '<article><p><b>Total Records: ' . count($info2) . '</b></p>';
print '<p><b>SQL: ' . $query . '</b></p>';

// the array $records is both associative and indexed, column zero is associative
// which you see in teh above print_r statement
$fields = array_keys($info2[0]);
$labels = array_filter($fields, "is_string");


$columns = count($labels);
print '<table><article>';
print '<tr><th colspan="' . $columns . '">' . $query . '</th></tr>';
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
        print '<td>'. $record[$i] . '</td>';
    }
    print '</tr>';
}
// all done
print '</table></article>';

include "footer.php";
?>
