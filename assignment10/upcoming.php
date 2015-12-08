<?php

include "top.php";
print '<div id="header">';
print '<h1>Upcoming Movies</h1>';
print '</div>';
print '<br />';


//now print out each record
$columns = 4; 
$query = "SELECT fldPicture, lstTitle, fldDescription, fldStatus FROM tblMovies where fldStatus='Upcoming'";
//$info2 = $thisDatabaseReader->testquery($query, "", 0, 0, 0, 0, false, false);
$queryDescription = $thisDatabaseReader->select($query, "", 1, 0, 2, 0, false, false);

print '<div class="container-fluid">';
print '<div class="row">';
foreach ($queryDescription as $rec) {
    //print '<tr>';
    print '<div class="col-md-3"><img src="' . $rec['fldPicture'] . '">';
    print '<p>' . $rec['lstTitle'] . '</p>';
    print '<br />';
    print '</div>';
    
}

print '</div>';
print '</div>';


include "footer.php";
?>

